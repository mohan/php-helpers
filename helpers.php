<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress


// 
// Rewrite current $_SERVER['REQUEST_URI'] into $_GET
// 

function filter_rewrite_uri($paths)
{
	if(isset($_SERVER['PATH_INFO'])) $request_path = $_SERVER['PATH_INFO'];
	else $request_path = $_SERVER['REQUEST_URI'];

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

	foreach ($matches as $key => $value) {
		if(is_string($key)) $_GET[$key] = $value;
	}
	foreach ($current_rewrite_args as $key => $value) {
		$_GET[$key] = $value;
	}

	_php_helpers_init();
}











// 
// Permitted Params
// 


// Permitted GET, POST, cookie params, with strlen check and typecasting
// Ex: $get_param_names = [ 'param_name' => int_length ... ]
function filter_permitted_params($get_param_names, $post_param_names=[], $cookie_param_names=[], $get_typecasts=[], $post_typecasts=[])
{
	if(!_filter_permitted_params_names($_GET, $get_param_names)) return false;
	if(!_filter_permitted_params_names($_POST, $post_param_names)) return false;
	if(!_filter_permitted_params_names($_COOKIE, $cookie_param_names)) return false;

	_filter_permitted_params_typecast($_GET, $get_typecasts);
	_filter_permitted_params_typecast($_POST, $post_typecasts);

	return true;
}


function _filter_permitted_params_names(&$input, $permitted_arr)
{
	foreach ($input as $key => $value) {
		if(!array_key_exists($key, $permitted_arr)) unset($input[$key]);
		else if(isset($input[$key])){
			if(is_int($permitted_arr[$key]) && strlen($input[$key]) > $permitted_arr[$key]) {
				return false;
			}
			else if(is_string($permitted_arr[$key]) && !preg_match($permitted_arr[$key], $input[$key])) {
				return false;
			}
		}
	}

	return true;
}


function _filter_permitted_params_typecast(&$input, $typecast_def_arr)
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



// 
// END Permitted Params
// 














// 
// Router
// 

// Map action names to functions and call current name
// Max action name 32 chars
function filter_routes($get_action_names, $post_action_names=[], $patch_action_names=[], $delete_action_names=[])
{
	if(isset($_REQUEST['TEMPLATE_HAS_RENDERED'])) return false;

	switch ($_REQUEST['CURRENT_METHOD']) {
		case 'post':	return _filter_routes_method($post_action_names);
		case 'patch':	return _filter_routes_method($patch_action_names);
		case 'delete':	return _filter_routes_method($delete_action_names);
		case 'get':		return _filter_routes_method($get_action_names);
	}

	return false;
}


function _filter_routes_method($current_method_action_names)
{
	$current_action_name = $_REQUEST['CURRENT_ACTION'];
	$current_method_name = $_REQUEST['CURRENT_METHOD'];

	if( !array_key_exists($current_action_name, $current_method_action_names) ) return false;

	// Render template directly
	if(is_string($current_method_action_names[$current_action_name])){
		if(defined('_PHP_HELPERS_EXTRA_IS_DEFINED') && APP_ENV_IS_DEVELOPMENT) {
			$_REQUEST['_REQUEST_ARGS_CURRENT_ACTION'] = [$current_method_name, $current_method_action_names[$current_action_name], 'render'];
		}

		$_REQUEST['ACTION_ID'] = 'render';
		$_REQUEST['TEMPLATE_PATH'] = $current_method_action_names[$current_action_name];
		return render([], $current_method_action_names[$current_action_name]);
	}

	// Required params for action
	$required_params = $current_method_action_names[$current_action_name];
	$action_id = preg_replace("/[^a-zA-Z0-9]/", '_', $current_action_name);
	$action_function_name = $current_method_name . '_' . $action_id;

	if($current_method_name == 'get'){
		if( array_intersect($required_params, array_keys($_GET)) != $required_params) return false;
	} else {
		if( array_intersect($required_params[0], array_keys($_GET)) != $required_params[0]) return false;
		if( array_intersect($required_params[1], array_keys($_POST)) != $required_params[1]) return false;
	}

	if(defined('_PHP_HELPERS_EXTRA_IS_DEFINED') && APP_ENV_IS_DEVELOPMENT) {
		$_REQUEST['_REQUEST_ARGS_CURRENT_ACTION'] = [$current_method_name, $required_params, $action_function_name];
	}

	$_REQUEST['ACTION_ID'] = $action_id;
	$_REQUEST['TEMPLATE_PATH'] = APP_NAME . '/' . $action_id . '.html.php';
	return call_user_func( $action_function_name );
}


// 
// END Router
// 













// 
// Templates
// 

function render($args=[], $template_path=true)
{
	if(isset($_REQUEST['TEMPLATE_HAS_RENDERED'])) trigger_error('Template has already rendered for this request.', E_USER_ERROR);
	$_REQUEST['TEMPLATE_HAS_RENDERED'] = true;

	if($template_path === true) $template_path = $_REQUEST['TEMPLATE_PATH'];
	$layout = $_REQUEST['TEMPLATE_LAYOUT'] ? $_REQUEST['TEMPLATE_LAYOUT'] : 'layouts/index.html.php';

	extract($args, EXTR_SKIP);

	if(defined('RENDER_TO_STRING')) ob_start();

	require _path_join(TEMPLATES_DIR, ($layout ? $layout : $template_path));

	if(defined('RENDER_TO_STRING')) {
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	return true;
}


function render_partial($template_path, $args=[], $return=false)
{
	extract($args, EXTR_SKIP);

	if($return) ob_start();
	
	require _path_join(TEMPLATES_DIR, $template_path);
	
	if($return) {
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}
}



function redirectto($action, $args=[])
{
	$_REQUEST['TEMPLATE_HAS_RENDERED'] = true;
	_header('Location: ' . urltoget($action, $args));
	return true;
}


if(!defined('CUSTOM_GET_404')){
	function get_404($message='')
	{
		_header("HTTP/1.1 404 Not Found");
		$_REQUEST['TEMPLATE_LAYOUT'] = 'layouts/404.html.php';
		$_REQUEST['TEMPLATE_PATH'] = false;
		return render(['_pagetitle'=>'404', 'message' => $message]);
	}
}

// 
// END Templates
// 

















// 
// URL helpers
// 

function urlto_public_dir($uri)
{
	if(defined('PUBLIC_URL')){
		return PUBLIC_URL . $uri;
	} else {
		return explode('?', ROOT_URL)[0] . $uri;
	}
}


function urltoget($action, $args=[], $arg_separator='&', $skip_action_arg=false)
{
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
		$out = explode('?', ROOT_URL)[0] . ltrim($action, '/');
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
		$out .= _form_field($form_id, $field_name, $field_options);
	}

	if(sizeof($fields) > 0) $out .= "</form></div>\n";

	return $out;
}


function _form_field($form_id, $field_name, $field_options)
{
	$out = '';

	_arr_defaults($field_options, ['value'=>'', 'label'=>'', 'tag'=>'input', 'type'=>'text', 'name'=>$field_name, 'id'=> "{$form_id}-$field_name"]);
	$value = $field_options['value'];
	$label = $field_options['label'];
	$tag = $field_options['tag'];
	$field_type = $field_options['type'];
	if($field_options['tag'] != 'input') unset($field_options['type']);
	unset($field_options['value'], $field_options['label'], $field_options['tag']);

	if($field_type != 'hidden') $out .= "\n<div id='{$field_options['id']}-container' class='form-field'>\n\t";
	if($field_type != 'hidden') $out .= 	$label ? (tag($label, ['for'=>$field_options['id']], 'label') . "\n\t") : '';
											$out .=	tag($value, $field_options, $tag) . "\n";
	if($field_type != 'hidden') $out .= "</div>\n";

	return $out;
}


function linkto($action, $html, $args=[], $attrs=[])
{
	$url = urltoget($action, $args, '&amp;');

	if( $_REQUEST['CURRENT_METHOD'] == 'get' && ( $_SERVER['REQUEST_URI'] == urltoget($action, $args) || $_GET['a'] == $action) ) {
		_arr_defaults($attrs, ['class'=>'']);
		$attrs['class'] .= 'current-uri-link';
	}
	
	$attrs_str = '';
	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

	return "<a href='$url' $attrs_str>" . htmlentities($html) . "</a>";
}


// Auto htmlentities for safe user input
function tag($html, $attrs=[], $name='div', $closing=true, $escape=true)
{
	if(!$name) return;

	$attrs_str = '';
	foreach ($attrs as $key => $value) $attrs_str .= "$key=\"" . htmlentities($value) . "\" ";
	
	$out = "<$name $attrs_str";

	if($name != 'input'){
		$out .= ">" . ($escape ? htmlentities($html) : $html);
		if($closing) $out .= "</$name>";
	} else {
		$out .= ' value="' . htmlentities($html) . '"';
		$out .= " />";
	}

	return $out;
}


function tag_table($headers, $data, $attrs=[], $cb=false)
{
	$attrs_str = '';
	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

	$out = "<table $attrs_str><thead>\n<tr>";
	foreach ($headers as $key => $value) {
		$out .= '<th>' . htmlentities($value) . '</th>';
	}
	$out .= "</tr>\n</thead>\n<tbody>\n";

	$header_keys = array_keys($headers);
	foreach ($data as $row_key => $row_value) {
		$out .= "<tr>\n";
		foreach ($header_keys as $header_key) {
			if($cb) $out .= '<td>' . call_user_func($cb, $row_value, $header_key, $row_key) . '</td>';
			else $out .= '<td>' . htmlentities($row_value[$header_key]) . "</td>\n";
		}
		$out .= "</tr>\n";
	}
	$out .= "</tbody></table>";

	return $out;
}








// 
// Flash messages
// 

function flash_set($html, $in_current_request=false)
{
	if($html) {
		if($in_current_request) $_REQUEST['flash'] = $html;
		else md5_cookie_set('flash', $html);
	}
}


function flash_clear()
{
	cookie_delete('flash');
}


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


function md5_cookie_set($name, $value)
{
	// expires next day - 1hour; reset to keep it continuous;
	$authenticity = _md5_cookie_authenticity_token($name, $value, time());

	// Expires end of session/browser close
	_setcookie($name, base64_encode("$value%$authenticity"), 0, ROOT_URL, '', false, true);
}


function md5_cookie_get($name)
{
	if(!isset($_COOKIE[$name])) return false;

	$parts = explode('%', base64_decode($_COOKIE[$name]));
	$value = $parts[0];
	$given_authenticity = $parts[1];

	$timestamp = time();
	$authenticity = _md5_cookie_authenticity_token($name, $value, $timestamp);

	if($given_authenticity != $authenticity) {
		// Check 1 day before, for continuation;
		$authenticity = _md5_cookie_authenticity_token($name, $value, $timestamp - (24 * 60 * 60));
		if($given_authenticity != $authenticity) {
			return false;
		}
	}

	return $value;
}


function cookie_delete($name)
{
	_setcookie($name, '', time() - 3600, ROOT_URL, '', false, true);
}


function _md5_cookie_authenticity_token($name, $value, $timestamp)
{
	return md5(
		$name . '%' .
		$value . '%' .
		base64_encode($value) . '%' .
		date('y-m-d', $timestamp) . '%' .
		SECURE_HASH . '%' .
		(isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '')  . '%' .
		(isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '')
	);
}


// 
// END md5 cookie
// 



















// 
// Internal functions
// 

function _to_id($str, $replace_with='-')
{
	return strtolower(preg_replace("/[^a-zA-Z\d-]/", $replace_with, $str));
}


function _path_join(...$parts)
{
	return preg_replace('/\/+/', '/', implode('/', array_filter($parts)));
}

// Returns only specified keys, with defaults if not set
function _arr_get($arr, $keys, $prefix='')
{
	$arr_out = [];
	foreach ($keys as $key=>$default) {
		$arr_out[$prefix.$key] = isset($arr[$key]) ? $arr[$key] : $default;
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
function _php_helpers_init()
{
	if(isset($_GET['a']) && $_GET['a'] == '') $_GET['a'] = 'root';
	_arr_defaults($_GET, ['a'=>NULL]);

	if(isset($_GET['a']))		 			$_REQUEST['CURRENT_ACTION'] = $_GET['a'];
	else if(isset($_GET['post_action'])) 	$_REQUEST['CURRENT_ACTION'] = $_GET['post_action'];
	else if(isset($_GET['patch_action'])) 	$_REQUEST['CURRENT_ACTION'] = $_GET['patch_action'];
	else if(isset($_GET['delete_action']))	$_REQUEST['CURRENT_ACTION'] = $_GET['delete_action'];
	else									$_REQUEST['CURRENT_ACTION'] = $_GET['a'] = 'root';

	if(isset($_GET['a'])){
		$_REQUEST['CURRENT_METHOD'] = 'get';
	} else if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if($_GET['post_action']) 			$_REQUEST['CURRENT_METHOD'] = 'post';
		else if($_GET['patch_action']) 		$_REQUEST['CURRENT_METHOD'] = 'patch';
		else if($_GET['delete_action']) 	$_REQUEST['CURRENT_METHOD'] = 'delete';
	} else {
		$_REQUEST['CURRENT_METHOD'] = 'get';
	}

	if(!defined('SECURE_HASH')) define('SECURE_HASH', md5(
		__FILE__ .
		filemtime(__FILE__) .
		filesize(__FILE__) .
		(isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '') .
		(isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '') .
		sys_get_temp_dir()
	));

	if(!defined('APP_DIR'))			define('APP_DIR', '.');
	if(!defined('APP_NAME'))		define('APP_NAME', 'app');
	if(!defined('ROOT_URL')) 		define('ROOT_URL', '/');
	if(!defined('TEMPLATES_DIR')) 	define('TEMPLATES_DIR', _path_join( APP_DIR, 'templates' ));
	$_REQUEST['TEMPLATE_LAYOUT'] = 'layouts/' . APP_NAME . '.html.php';

	_filter_set_flash();
}

if(!defined('APP_ENV_IS_TEST')) _php_helpers_init();
