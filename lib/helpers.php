<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress


// Sets $_REQUEST variables based on $_GET, defines constants and sets flash.
function _php_helpers_init()
{
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
        $_GET['a'] = NULL;

        if(isset($_GET['post_action'])) {
            $_REQUEST['CURRENT_ACTION'] = $_GET['post_action'];
            $_REQUEST['CURRENT_METHOD'] = 'post';
        } else if(isset($_GET['patch_action'])) {
            $_REQUEST['CURRENT_ACTION'] = $_GET['patch_action'];
            $_REQUEST['CURRENT_METHOD'] = 'patch';
        } else if(isset($_GET['delete_action'])) {
            $_REQUEST['CURRENT_ACTION'] = $_GET['delete_action'];
            $_REQUEST['CURRENT_METHOD'] = 'delete';
        }
    } else if(isset($_GET['a']) && $_GET['a']) {
        $_REQUEST['CURRENT_ACTION'] = $_GET['a'];
        $_REQUEST['CURRENT_METHOD'] = 'get';
    } else {
        $_REQUEST['CURRENT_ACTION'] = $_GET['a'] = 'root';
        $_REQUEST['CURRENT_METHOD'] = 'get';
    }

    if(!defined('APP_DIR'))         define('APP_DIR', '.');
    if(!defined('APP_NAME'))        define('APP_NAME', 'app');
    if(!defined('ROOT_URL'))        define('ROOT_URL', '/');
    if(!defined('TEMPLATES_DIR'))   define('TEMPLATES_DIR', _path_join( APP_DIR, 'templates' ));
    if(!defined('SECURE_HASH'))     define('SECURE_HASH', md5(
                                                            __FILE__ .
                                                            filemtime(__FILE__) .
                                                            filesize(__FILE__) .
                                                            join('-', _arr_get($_SERVER, [
                                                                    'HTTP_HOST',
                                                                    'DOCUMENT_ROOT',
                                                                    'SERVER_SOFTWARE',
                                                                    'REMOTE_ADDR',
                                                                    'HTTP_USER_AGENT'
                                                            ])) .
                                                            sys_get_temp_dir()
                                                        ));
    
    $_REQUEST['LAYOUT'] = 'layouts/' . APP_NAME . '.html.php';

    _filter_set_flash();
}



// Rewrites URI into $_GET variables
function filter_rewrite_uri($paths)
{
    $request_path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
    $request_path = preg_replace('/^' . preg_quote(ROOT_URL, '/') . '/', '/', $request_path, 1);

    $current_rewrite_args = false;
    $matches = [];
    foreach ($paths as $path => $rewrite_args) {
        if(preg_match($path, $request_path, $matches)){
            $current_rewrite_args = $rewrite_args;
            break;
        }
    }

    if(!$current_rewrite_args) return false;

    // Capture group vars
    foreach ($matches as $key => $value) if(is_string($key)) $_GET[$key] = $value;
    // Given parameters for current_rewrite
    foreach ($current_rewrite_args as $key => $value)   $_GET[$key] = $value;

    // Call init again, as REQUEST vars are modified.
    _php_helpers_init();
}


// 
// END Rewrite
// 








// 
// Permitted Params
// 

// Allows only given parameters and discards anything else.
function filter_permitted_params($get_param_names, $post_param_names=[], $cookie_param_names=[])
{
    if(
        !_arr_validate($_GET, $get_param_names, false) ||
        !_arr_validate($_POST, $post_param_names, false) ||
        !_arr_validate($_COOKIE, $cookie_param_names, false)
    ) return false;

    return true;
}



// 
// END Permitted Params
// 














// 
// Router
// 

// Maps action names to action functions. Also sets template variables based on request.
function filter_routes($get_actions, $post_actions=[], $patch_actions=[], $delete_actions=[])
{
    if(isset($_REQUEST['TEMPLATE_HAS_RENDERED'])) return false;

    switch ($_REQUEST['CURRENT_METHOD']) {
        case 'post':    return _filter_route($post_actions,    $_REQUEST['CURRENT_ACTION'], $_REQUEST['CURRENT_METHOD']);
        case 'patch':   return _filter_route($patch_actions,   $_REQUEST['CURRENT_ACTION'], $_REQUEST['CURRENT_METHOD']);
        case 'delete':  return _filter_route($delete_actions,  $_REQUEST['CURRENT_ACTION'], $_REQUEST['CURRENT_METHOD']);
        case 'get':     return _filter_route($get_actions,     $_REQUEST['CURRENT_ACTION'], $_REQUEST['CURRENT_METHOD']);
    }

    return false;
}


function _filter_route($action_names, $action_name, $method_name)
{
    if( !array_key_exists($action_name, $action_names) ) return false;

    $action_id = preg_replace("/[^a-zA-Z0-9]/", '_', $action_name);
    $required_params = $action_names[$action_name];

    // Render template directly
    if(is_string($required_params)){
        // Render template directly
        $_REQUEST['ACTION_ID'] = $action_id;
        $_REQUEST['TEMPLATE'] = $required_params;
        return render();
    }

    // Action function
    $action_function_name = $method_name . '_' . $action_id;

    if($method_name == 'get'){
        if( array_intersect($required_params, array_keys($_GET)) != $required_params) return false;
    } else {
        if( array_intersect($required_params[0], array_keys($_GET)) != $required_params[0]) return false;
        if( array_intersect($required_params[1], array_keys($_POST)) != $required_params[1]) return false;
    }

    $_REQUEST['ACTION_ID'] = $action_id;
    $_REQUEST['TEMPLATE'] = APP_NAME . '/' . $action_id . '.html.php';

    return call_user_func( $action_function_name );
}


// 
// END Router
// 













// 
// Templates
// 


// Renders (using include) template with given arguments as local variables. Accepts multiple argument arrays.
function render(...$all_render_args)
{
    if(isset($_REQUEST['TEMPLATE_HAS_RENDERED']))   trigger_error('Template has already rendered for this request.', E_USER_ERROR);
    $_REQUEST['TEMPLATE_HAS_RENDERED'] = true;

    foreach ($all_render_args as $arg)  extract($arg);
    $args = [];
    foreach ($all_render_args as $arg)  $args = array_merge($args, $arg);

    $template = isset($args['_template']) ? $args['_template'] : $_REQUEST['TEMPLATE'];
    $layout = isset($args['_layout']) ? $args['_layout'] : 
                    (
                        $_REQUEST['LAYOUT'] ? $_REQUEST['LAYOUT'] : 'layouts/app.html.php'
                    );
    
    $_REQUEST['TEMPLATE'] = $template;
    $_REQUEST['LAYOUT'] = $layout;

    if(defined('RENDER_TO_STRING')) ob_start();

    require _path_join(TEMPLATES_DIR, ($layout ? $layout : $template));

    if(defined('RENDER_TO_STRING')) {
        $out = ob_get_contents();
        ob_end_clean();
        return $out;
    }

    return true;
}



// Renders (using include) template partial with given arguments as local variables. Accepts multiple argument arrays.
function render_partial($template, ...$all_render_args)
{
    foreach ($all_render_args as $arg)  extract($arg);
    $args = [];
    foreach ($all_render_args as $arg)  $args = array_merge($args, $arg);

    require _path_join(TEMPLATES_DIR, $template);
}



// Adds redirect header to action
function redirectto($action, $args=[])
{
    $_REQUEST['TEMPLATE_HAS_RENDERED'] = true;
    _header('Location: ' . urltoget($action, $args));
    return true;
}



if(!defined('CUSTOM_GET_404')){
    // Built-in 404-not-found action function
    function get_404($message='')
    {
        _header("HTTP/1.1 404 Not Found");
        $_REQUEST['LAYOUT'] = 'layouts/404.html.php';
        $_REQUEST['TEMPLATE'] = false;
        return render(['_pagetitle'=>'404', 'message' => $message]);
    }
}

// 
// END Templates
// 

















// 
// URL helpers
// 



// Returns URL to public dir asset
function urlto_public_dir($uri)
{
    if(defined('PUBLIC_URL')){
        return PUBLIC_URL . $uri;
    } else {
        return explode('?', ROOT_URL)[0] . $uri;
    }
}



// Returns URL to get action. Set $args['_hash'] to set hash fragment.
function urltoget($action, $args=[], $arg_separator='&', $skip_action_arg=false)
{
    if($action == '' && isset($args['_hash'])) return "#{$args['_hash']}";

    if(!$args) $args = [];
    if(isset($args['ROOT_URL'])){
        $root_url = $args['ROOT_URL'] . (_str_contains($args['ROOT_URL'], '?') ? '' : '?');
        unset($args['ROOT_URL']);
    } else {
        $root_url = ROOT_URL . (_str_contains(ROOT_URL, '?') ? '' : '?');
    }


    $hash = isset($args['_hash']) ? '#' . $args['_hash'] : '';
    unset($args['_hash']);

    if(strpos($action, '/') === 0){
        $out = explode('?', $root_url)[0] . ltrim($action, '/');
        if(sizeof($args) > 0) $out .= '?' . http_build_query($args, '', $arg_separator);
        if($hash) $out .= $hash;
        return $out;
    }

    if($action == 'root') {
        return explode('?', $root_url)[0] . $hash;
    }

    if(!$skip_action_arg){
        $_args = ['a' => $action];
        $args = array_merge($_args, $args);
    }

    return $root_url . http_build_query($args, '', $arg_separator) . $hash;
}



// Returns URL to post action. Set $args['_method'] to 'patch'/'delete' for patch or delete URLs.
function urltopost($action, $args=[], $arg_separator='&')
{
    if( isset($args['_method']) && ($args['_method'] == 'patch' || $args['_method'] == 'delete') ){
        $_args = [$args['_method'] . '_action' => $action];
    } else {
        $_args = ['post_action' => $action];
    }
    if( isset($args['_method']) ) unset($args['_method']);

    $args = array_merge($_args, $args);

    return urltoget($action, $args, $arg_separator, true);
}



// 
// END URL helpers
// 





















// 
// HTML Tag helpers
// 


// Returns a form with fields
function formto($action, $args=[], $attrs=[], $fields=[])
{
    _arr_defaults($attrs, [
        'id' => '',
        'method'=>'post',
        'action'=> isset($attrs['method']) && $attrs['method'] == 'get' ? urltoget($action, $args) : urltopost($action, $args)
    ]);

    $form_id = $attrs['id'] ? $attrs['id'] : _to_id($action) . '-form';
    unset($attrs['id']);

    $attrs_str = '';
    foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

    $out  = sizeof($fields) == 0 ?
                "<form id='{$form_id}' $attrs_str>" :
                "<div id='{$form_id}-container' class='form-container'><form id='{$form_id}' $attrs_str>";

    foreach ($fields as $field_name => $field_options) {
        $out .= form_field($form_id, $field_name, $field_options);
    }

    if(sizeof($fields) > 0) $out .= "</form></div>\n";

    return $out;
}



// Returns a form field
function form_field($form_id, $field_name, $field_options)
{
    $out = '';

    _arr_defaults($field_options, [
        'value'=>'',
        'label'=>'',
        'tag'=>'input',
        'type'=>'text',
        'name'=>$field_name,
        'id'=> "{$form_id}-$field_name"
    ]);

    $value = $field_options['value'];
    $label = $field_options['label'];
    $tag = $field_options['tag'];
    $field_type = $field_options['type'];

    if($field_options['tag'] != 'input') unset($field_options['type']);
    unset($field_options['value'], $field_options['label'], $field_options['tag']);

    if($field_type != 'hidden') $out .= "\n<div id='{$field_options['id']}-container' class='form-field'>\n\t";
    if($field_type != 'hidden') $out .=     $label ? (tag($label, ['for'=>$field_options['id']], 'label') . "\n\t") : '';
                                            $out .= tag($value, $field_options, $tag) . "\n";
    if($field_type != 'hidden') $out .= "</div>\n";

    return $out;
}



// Returns a link tag to given action. Adds 'current-uri-link' classname to if action is current active link.
function linkto($action, $args, $html, $attrs=[])
{
    $url = urltoget($action, $args, '&amp;');

    if(
        $_REQUEST['CURRENT_METHOD'] == 'get' &&
        $_SERVER['REQUEST_URI'] == urltoget($action, $args)
    ) {
        _arr_defaults($attrs, ['class'=>'']);
        $attrs['class'] .= 'current-uri-link';
    }
    
    $attrs_str = '';
    foreach ($attrs as $key => $value) $attrs_str .= " $key='" . htmlentities($value) . "'";

    return "<a href=\"$url\"$attrs_str>" . htmlentities($html) . "</a>";
}



// Returns html for a given tag name.
function tag($html, $attrs=[], $name='div', $escape=true)
{
    if(!$name) return;

    $attrs_str = '';
    foreach ($attrs as $key => $value) $attrs_str .= "$key=\"" . htmlentities($value) . "\" ";
    $html = $escape ? htmlentities($html) : $html;
    
    $out = "<$name $attrs_str";

    switch ($name) {
        case 'input':
            return $out . " value=\"$html\"/>";
        
        case 'iframe':
        case 'img':
            return $out . " src=\"$html\"/>";

        case 'br':
        case 'hr':
        case 'link':
            return $out . "/>";

        default:
            return $out . ">$html</$name>";
    }
}



// Returns HTML for table.
function tag_table($headers, $data, $attrs=[], $cb=false, $escape=true)
{
    $attrs_str = '';
    foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

    $out = "<table $attrs_str><thead>\n<tr>";
    foreach ($headers as $header) {
        $value = $escape ? htmlentities($header) : $header;
        $out .= "<th>$value</th>";
    }
    $out .= "</tr>\n</thead>\n<tbody>\n";

    if(sizeof($headers) == 0 && sizeof($data) > 0) $headers = array_keys($data[0]);

    foreach ($data as $row_key => $row) {
        $out .= "<tr>\n";
        foreach ($headers as $header_key => $header) {
            if($cb) {
                $value = call_user_func($cb, $row, $header, $row_key, $header_key);
                $out .= "<td>$value</td>\n";
            } else {
                $value = isset($row[$header]) ? $row[$header] : $row[$header_key];
                $value = $escape ? htmlentities($value) : $value;
                $out .= "<td>$value</td>\n";
            }
        }
        $out .= "</tr>\n";
    }
    $out .= "</tbody></table>";

    return $out;
}








// 
// Flash messages
// 


// Sets flash message, for current request or in cookie for next request use.
function flash_set($html, $in_current_request=false)
{
    if($html) {
        if($in_current_request) $_REQUEST['flash'] = $html;
        else md5_cookie_set('flash', $html);
    }
}



// Clears flash cookie
function flash_clear()
{
    if(isset($_COOKIE['flash'])) cookie_delete('flash');
}



// Reads flash into $_REQUEST['flash'] from cookie
function _filter_set_flash()
{
    $flash = md5_cookie_get('flash');

    if($flash){
        $_REQUEST['flash'] = $flash;
        cookie_delete('flash');
    } else {
        $_REQUEST['flash'] = NULL;
    }
}


// 
// END Flash messages
// 
















// 
// md5 cookie - Cookie with added md5 check
// 


// Set cookie with extra md5 token
function md5_cookie_set($name, $value)
{
    $authenticity = _md5_authenticity_token($name, $value, time());

    // Expires end of session/browser close
    _setcookie($name, base64_encode("$value%$authenticity"), 0, ROOT_URL, '', false, true);
}


// Gets value of md5 cookie
function md5_cookie_get($name)
{
    if(!isset($_COOKIE[$name])) return false;

    $parts = explode('%', base64_decode($_COOKIE[$name]));
    $value = $parts[0];
    $given_authenticity = $parts[1];

    $timestamp = time();
    $authenticity = _md5_authenticity_token($name, $value, $timestamp);

    if($given_authenticity != $authenticity) {
        // Check 1 day before, for continuation;
        $authenticity = _md5_authenticity_token($name, $value, $timestamp - (24 * 60 * 60));
        if($given_authenticity != $authenticity) {
            return false;
        }
    }

    return $value;
}


// Adds a cookie delete header
function cookie_delete($name)
{
    _setcookie($name, '', time() - 3600, ROOT_URL, '', false, true);
}


// Returns md5 token for given name, value and timestamp
function _md5_authenticity_token($name, $value, $timestamp)
{
    return md5(
        $name . '#' .
        $value . '-' .
        base64_encode($value) . '-' .
        date('y-m-d', $timestamp) . '#' .
        join(' ', _arr_get($_SERVER, [
                'DOCUMENT_ROOT',
                'SERVER_SOFTWARE',
                'REMOTE_ADDR',
                'HTTP_USER_AGENT'
        ])) .
        SECURE_HASH
    );
}


// 
// END md5 cookie
// 







// 
// Debug helpers
// 

// Simple debug
// Remember to remove all debugs
function __d(...$args)
{
    if(!defined('APP_ENV_IS_TEST')) echo "<pre style='width:94%;margin:1%;padding:2%;background:#fff;border:2px solid #aa0000;'>";
    $_debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
    if(!defined('APP_ENV_IS_TEST')) echo "<strong>&gt; [" . basename($_debug[0]['file']) . "#{$_debug[0]['line']}]</strong>\n";
    else  echo "> [" . basename($_debug[0]['file']) . "#{$_debug[0]['line']}]\n";
    foreach($args as $arg) {
        if(defined('APP_ENV_IS_TEST')) {
            print_r($arg);
            continue;
        }
        echo "<div style='border-top:1px solid #e9e9e9; padding: 10px 20px 0 20px; margin: 10px 0 0 0;'>";
        ob_start();
        print_r($arg);
        $out = ob_get_contents();
        ob_end_clean();
        echo htmlentities($out);
        echo "</div>";
    }
    if(!defined('APP_ENV_IS_TEST')) echo "</pre>";
}


// Print debug and exit
function __d_(...$args)
{
    __d(func_get_args());
    exit;
}






// 
// Internal functions
// 

// String to id
function _to_id($str, $replace_with='-')
{
    return strtolower(preg_replace("/[^a-zA-Z\d-]/", $replace_with, $str));
}


// Join file paths
function _path_join(...$parts)
{
    return preg_replace('/\/+/', '/', implode('/', array_filter($parts)));
}


// Returns new array with only specified keys, with defaults if not set from values in $keys
function _arr_get($arr, $keys, $prefix='')
{
    $arr_out = [];
    foreach ($keys as $key=>$value) {
        if(is_int($key)){
            $arr_out[$prefix.$value] = isset($arr[$value]) ? $arr[$value] : '';
        } else {
            // default is $value;
            $arr_out[$prefix.$key] = isset($arr[$key]) ? $arr[$key] : $value;
        }
    }

    return $arr_out;
}


// Fill defaults in arr
function _arr_defaults(&$arr, $defaults)
{
    foreach ($defaults as $key=>$default) {
        if(!isset($arr[$key])) $arr[$key] = $default;
    }

    return $arr;
}


// Typecast arr values
// Example def: [ 'id' => 'int', 'raw'=> 'bool' ]
function _arr_typecast(&$input, $typecast_def_arr)
{
    foreach ($typecast_def_arr as $name => $type) {
        if(isset($input[$name]) && is_string($input[$name]))
        switch ($type) {
            case 'int': $input[$name] = intval($input[$name]); break;
            case 'float': $input[$name] = floatval($input[$name]); break;
            case 'bool': $input[$name] = boolval($input[$name]); break;
        }
    }
}


/***

Validates arr key values with regex or strlen
Unsets any keys that are not in rules
Returns true/false
Example rules: [ 'a' => '/^(root|docs|posts|new-post|post|search)$/', 'title' => 1024 ]

***/
function _arr_validate(&$input, $validations, $must_contain_all_keys=true)
{
    if( $must_contain_all_keys && sizeof(array_diff_key($validations, $input)) > 0 ) return false;

    foreach ($input as $key => $value) {
        if(!array_key_exists($key, $validations)) unset($input[$key]);
        else if(isset($input[$key])){
            if(is_int($validations[$key]) && strlen($input[$key]) > $validations[$key]) {
                return false;
            }
            else if(is_string($validations[$key]) && !preg_match($validations[$key], $input[$key])) {
                return false;
            }
        }
    }

    return true;
}



// Checks if substr is in string. Accepts multiple substrings.
// Returns true if atleast str contains one substring.
function _str_contains($str, ...$substrs)
{
    foreach ($substrs as $substr) {
        if(is_string($substr) && strlen($substr) == 0) return true;
        if(strpos($str, $substr) !== false) return true;
    }

    return false;
}


// 
// Headers
// Proxy for header, needed for test env
// 
if(!defined('CUSTOM_HEADER_HANDLERS')){
    function _header($header)
    {
        header($header);
    }

    function _setcookie($name, $value="", $expires_or_options=0, $path="", $domain="", $secure=false, $httponly=false)
    {
        setcookie($name, $value, $expires_or_options, $path, $domain, $secure, $httponly);
    }
}



// 
// END Internal functions
// 






// 
// Init
// 
if(!defined('APP_ENV_IS_TEST')) _php_helpers_init();
