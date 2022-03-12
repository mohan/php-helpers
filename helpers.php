<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress



// 
// Permitted Params
// 


// Permitted GET, POST, cookie params, with strlen check and typecasting
// Ex: $get_param_names = [ 'param_name' => int_length ... ]
function filter_permitted_params($get_param_names, $post_param_names, $cookie_param_names, $get_typecasts, $post_typecasts)
{
	$uri_param_key =  isset($_GET['post_uri']) ? 'post_uri' :
						isset($_GET['patch_uri']) ? 'patch_uri' :
						isset($_GET['delete_uri']) ? 'delete_uri' :
						isset($_GET['uri']) ? 'uri' : false;

	if(isset($_GET['uri']) && $uri_param_key === false) return false;

	if(!_filter_permitted_params_names($_GET, $uri_param_key, $get_param_names)) return false;
	if(!_filter_permitted_params_names($_POST, $uri_param_key, $post_param_names)) return false;
	if(!_filter_permitted_params_names($_COOKIE, $uri_param_key, $cookie_param_names)) return false;

	_filter_permitted_params_typecast($_GET, $get_typecasts);
	_filter_permitted_params_typecast($_POST, $post_typecasts);

	if(defined('__PHP_HELPERS_EXTRA_IS_DEFINED') && APP_ENV_IS_DEVELOPMENT) {
		_print_debug_permitted_params($get_param_names, $post_param_names, $cookie_param_names);
	}

	return true;
}


function _filter_permitted_params_names(&$input, $uri_param_key, $permitted_arr)
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


function _filter_permitted_params_typecast($input, $typecast_def_arr)
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
function filter_routes($get_action_names, $post_action_names, $patch_action_names, $delete_action_names)
{
	if(defined('TEMPLATE_HAS_RENDERED')) return false;

	if(defined('__PHP_HELPERS_EXTRA_IS_DEFINED') && APP_ENV_IS_DEVELOPMENT) {
		_print_debug_routes_pre($get_action_names, $post_action_names, $patch_action_names, $delete_action_names);
	}

	if( isset($_GET['post_uri']) ) {
		if($_SERVER['REQUEST_METHOD'] != 'POST') return false;
		return _filter_routes_method('post', 'post_uri', $post_action_names);
	} else if( isset($_GET['patch_uri']) ) {
		if($_SERVER['REQUEST_METHOD'] != 'POST') return false;
		return _filter_routes_method('patch', 'patch_uri', $patch_action_names);
	} else if( isset($_GET['delete_uri']) ) {
		if($_SERVER['REQUEST_METHOD'] != 'POST') return false;
		return _filter_routes_method('delete', 'delete_uri', $delete_action_names);
	} else if( isset($_GET['uri']) ) {
		if($_SERVER['REQUEST_METHOD'] != 'GET') return false;
		return _filter_routes_method('get', 'uri', $get_action_names);
	} else if( !isset($_GET['uri']) ) {
		if($_SERVER['REQUEST_METHOD'] != 'GET') return false;
		$_GET['uri'] = 'root';
		return _filter_routes_method('get', 'uri', $get_action_names);
	}

	return false;
}


function _filter_routes_method($method_name, $uri_param_key, $action_names)
{
	// $_GET['uri'] / $_GET['post_uri'] / $_GET['patch_uri']
	$uri_route = $_GET[$uri_param_key];

	if( is_string($uri_route) && array_key_exists($uri_route, $action_names)){
		// Required params for action
		$required_params = $action_names[$uri_route];
		$action_name = $method_name . '_' . preg_replace("/[^a-zA-Z0-9]/", '_', $uri_route);

		if($method_name == 'get'){
			if( array_intersect($required_params[0], array_keys($_GET)) != $required_params[0]) return false;
			if( array_intersect($required_params[1], array_keys($_REQUEST)) != $required_params[1]) return false;
		} else {
			if( array_intersect($required_params[0], array_keys($_GET)) != $required_params[0]) return false;
			if( array_intersect($required_params[1], array_keys($_POST)) != $required_params[1]) return false;
			if( array_intersect($required_params[2], array_keys($_REQUEST)) != $required_params[2]) return false;
		}

		if(defined('__PHP_HELPERS_EXTRA_IS_DEFINED') && APP_ENV_IS_DEVELOPMENT) {
			_print_debug_routes_post($method_name, $uri_param_key, $required_params, $action_name);
		}

		return call_user_func( $action_name );
	}

	return false;
}




function redirectto($uri, $args=[])
{
	_header('Location: ' . urltoget($uri, $args));
	return true;
}


if(!defined('CUSTOM_404_ACTION')){
	function get_404($message='')
	{
		_header("HTTP/1.1 404 Not Found");
		return render('layouts/404.php', ['__pagetitle'=>'404', 'message' => $message], false);
	}
}


// 
// END Router
// 













// 
// Templates
// 

function render($template_name, $args=[], $layout='layouts/index.php')
{
	if(isset($_REQUEST['TEMPLATE_HAS_RENDERED'])) trigger_error('Template has already rendered for this request.', E_USER_ERROR);
	$_REQUEST['TEMPLATE_HAS_RENDERED'] = true;

	$template_path = (defined('APP_DIR') ? APP_DIR : '.') . '/templates/' . (defined('APP_TEMPLATE') ? APP_TEMPLATE : '') . '/';
	$uri = isset($_GET['uri']) ? $_GET['uri'] : ''; // Only renders GET; redirect all other methods to get

	extract($args, EXTR_SKIP);

	if(defined('RENDER_TO_STRING')) ob_start();

	include $layout ? $template_path . $layout : $template_path . $template_name;

	if(defined('RENDER_TO_STRING')) {
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
	}

	return true;
}


function render_partial($template_name, $args=[], $return=false)
{
	$template_path = (defined('APP_DIR') ? APP_DIR : '.') . '/templates/' . (defined('APP_TEMPLATE') ? APP_TEMPLATE : '') . '/';
	
	extract($args, EXTR_SKIP);

	if($return) ob_start();
	
	include $template_path . $template_name;
	
	if($return) {
		$out = ob_get_contents();
		ob_end_clean();
		return $out;
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
	return CONFIG_ROOT_URL . $uri;
}


function urltoget($uri, $args=[], $arg_separator='&')
{
	if(!$args) $args = [];

	$hash = isset($args['__hash']) ? '#' . $args['__hash'] : '';
	unset($args['__hash']);

	if($uri) {
		$_args = ['uri' => $uri];
		$args = array_merge($_args, $args);

		return CONFIG_ROOT_URL . '?' . http_build_query($args, '', $arg_separator) . $hash;
	}

	return CONFIG_ROOT_URL . $hash;
}


function urltopost($uri, $args=[], $arg_separator='&')
{
	if(!$args) $args = [];
	
	if( isset($args['__method']) && ($args['__method'] == 'patch' || $args['__method'] == 'delete') ){
		$_args = [$args['__method'] . '_uri' => $uri];
	} else {
		$_args = ['post_uri' => $uri];
	}
	if( isset($args['__method']) ) unset($args['__method']);

	$args = array_merge($_args, $args);

	return CONFIG_ROOT_URL . '?' . http_build_query($args, '', $arg_separator);
}


// 
// END URL helpers
// 





















// 
// HTML Tag helpers
// 

function formto($uri, $args=[], $attrs=[])
{
	$url = urltopost($uri, $args);

	$attrs_str = '';
	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "'";

	return "<form method='post' action='$url' $attrs_str>";
}


function linkto($uri, $html, $args=[], $attrs=[])
{
	$url = urltoget($uri, $args, '&amp;');
	
	$attrs_str = '';
	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

	return "<a href='$url' $attrs_str>" . htmlentities($html) . "</a>";
}


// Auto htmlentities for safe user input
function tag($html, $attrs=[], $name='div', $closing=true)
{
	if($name != 'input' && $name != 'textarea' && !$html) return;

	$attrs_str = '';
	foreach ($attrs as $key => $value) $attrs_str .= " $key=\"" . htmlentities($value) . "\"";
	
	$out = "<$name$attrs_str";

	if($name != 'input'){
		$out .= ">" . htmlentities($html);
		if($closing) $out .= "</$name>";
	} else {
		$out .= 'value="' . htmlentities($html) . '"';
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
// Markdown
// 

function render_markdown($text, $attrs=[], $enable_shortcodes=false)
{
	$out = '';
	
	// Todo: Optimize, use substr.
	$lines = explode("\n", $text);
	$codeblock_start = false;
	$add_tabs = true;
	foreach ($lines as $i => $line) {
		$line = htmlentities($line);

		$matches = [];
		if(preg_match("/^(\t*)```$/", $line, $matches)){
			if($codeblock_start){
				$codeblock_start = false;
				$add_tabs = true;
				$out .= "</div>";
			} else {
				$codeblock_start = true;
				$add_tabs = false;
				$tabs = "style='margin-left:" . strlen($matches[1]) * 40 . "px;'";
				$out .= "<div class='markdown-codeblock' $tabs>";
			}
			continue;
		}

		$line = preg_replace(
			[
				"/\*\*\*([^*]+)\*\*\*/",	// bold italic
				"/\*\*([^*]+)\*\*/",		// italic
				"/([^*\t])\*([^*]+)\*/",	// bold
				"/~~([^~]+)~~/",			// strikethrough
				"/^#\s(.+)/",				// h1
				"/^##\s(.+)/",				// h2
				"/^###\s(.+)/",				// h3
				"/^####\s(.+)/",			// h4
				"/^#####\s(.+)/",			// h5
				"/`([^`]+)`/",				// code
				"/^(\t*)-\s\[x\]\s(.+)$/",	// List checked
				"/^(\t*)-\s\[\s\]\s(.+)$/"	// List checked
			],
			[
				"<span class='markdown-bold-italic'>$1</span>",
				"<span class='markdown-italic'>$1</span>",
				"$1<span class='markdown-bold'>$2</span>",
				"<strike>$1</strike>",
				"<h1>$1</h1>\n",
				"<h2>$1</h2>\n",
				"<h3>$1</h3>\n",
				"<h4>$1</h4>\n",
				"<h5>$1</h5>\n",
				"<span class='markdown-code'>$1</span>",
				"$1<input type='checkbox' checked='checked' disabled='true' /> <strike>$2</strike>",
				"$1<input type='checkbox' disabled='true' /> <span>$2</span>",
			],
			$line
		);

		if(!preg_match("/^\t*<h/", $line)){
			$tabs = '';
			if($add_tabs && preg_match("/^\t+/", $line, $matches)){
				$tabs = " style='margin-left:" . strlen($matches[0]) * 30 . "px;'";
			}

			$line = strlen($line) == 0 ? "<p class='markdown-br'></p>\n" : "<p$tabs>\n" . $line . "\n</p>\n";
		}

		if($enable_shortcodes && $shortcode_line = process_shortcodes($line)){
			$line = $shortcode_line;
		}

		$out .= $line;
	}

	$attrs_str = sizeof($attrs) == 0 ? "class='markdown'" : '';
	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

	return "\n<!-- Markdown start -->\n<div $attrs_str>\n$out\n</div>\n<!-- Markdown end -->\n";
}



// 
// END Markdown
// 










// 
// Shortcodes
// 

// Process all shortcodes using respective functions and replace with return values.
function process_shortcodes($text)
{
	static $shortcode_list_regex = false;

	if(!$shortcode_list_regex) {
		$shortcodes_list = preg_replace("/[^[:alnum:]_-]/", "_", _shortcodes_list());
		$shortcode_list_regex = "/\[(" . join('|', $shortcodes_list) . ")([^\]]*)\]/";
	}

	// start with [
	// \[([a-z]+) = name
	// ([^\]]*) = args_str
	// [^\(] = Should not match markdown links
	if(!preg_match_all($shortcode_list_regex, $text, $matches)) return false;

	
	$shortcodes_matches = [];
	$shortcode_replacements = [];
	foreach ($matches[0] as $key => $match) {
		$full_shortcode = trim($matches[0][$key]);
		$name = $matches[1][$key];
		$args_str = $matches[2][$key];
		
		$args = [];
		if(strpos($args_str, '=') === false){
			$args[0] = $args_str;
		} else {
			if(preg_match_all('/([a-z]+)="?([^"]+)"?/', $args_str, $args_matches)){
				$arg_counts = $args_matches[1] ? array_count_values($args_matches[1]) : [];

				foreach ($args_matches[0] as $arg_key => $arg_value) {
					$key = $args_matches[1][$arg_key];
					$value = $args_matches[2][$arg_key];
					
					if($arg_counts[$key] > 1) $args[$key][] = $value;
					else $args[$key] = $value;
				}
			}
		}

		// Todo: Optimize, insert using substr.
		$shortcodes_matches[] = '/' . preg_quote($full_shortcode) . '/';
		$shortcode_replacements[] = call_user_func("shortcode_" . str_replace('-', '_', $name), $args);
	}

	return preg_replace($shortcodes_matches, $shortcode_replacements, $text, 1);
}



// 
// END Shortcodes
// 




















// 
// Flash messages
// 

function flash_set($html, $in_current_request=false)
{
	if($html) {
		if($in_current_request) $_REQUEST['flash'] = $html;
		else secure_cookie_set('flash', $html);
	}
}


function flash_clear()
{
	cookie_delete('flash');
}


function filter_set_flash()
{
	$flash = secure_cookie_get('flash');

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
// Secure cookie - Cookie with added authenticity
// 


function secure_cookie_set($name, $value)
{
	if(!CONFIG_SECURE_HASH) return;
	
	// expires next day - 1hour; reset to keep it continuous;
	$authenticity = _secure_cookie_authenticity_token($name, $value, time());

	// Expires end of session/browser close
	_setcookie($name, base64_encode("$value%$authenticity"), 0, CONFIG_ROOT_URL, '', false, true);
}


function secure_cookie_get($name)
{
	if(!isset($_COOKIE[$name])) return false;

	$parts = explode('%', base64_decode($_COOKIE[$name]));
	$value = $parts[0];
	$given_authenticity = $parts[1];

	$timestamp = time();
	$authenticity = _secure_cookie_authenticity_token($name, $value, $timestamp);

	if($given_authenticity != $authenticity) {
		// Check 1 day before, for continuation;
		$authenticity = _secure_cookie_authenticity_token($name, $value, $timestamp - (24 * 60 * 60));
		if($given_authenticity != $authenticity) {
			return false;
		}
	}

	return $value;
}


function cookie_delete($name)
{
	_setcookie($name, '', time() - 3600);
}


function _secure_cookie_authenticity_token($name, $value, $timestamp)
{
	return md5(
		$name . '%' . $value . '%' . base64_encode($value) . '%' . date('y-m-d', $timestamp) . '%' . CONFIG_SECURE_HASH
	);
}


// 
// END Secure cookie
// 





















// 
// Config file helpers
//


// Defines constants CONFIG_NAME from config ini file
function filter_set_config($filepath)
{
	$config = parse_ini_file($filepath);

	foreach ($config as $key => $value) {
		define('CONFIG_' . $key, $value);
	}
}


// 
// END Config file helpers
//































// 
// Internal functions
// 

// Fill defaults in arr
function _arr_defaults(&$arr, $defaults)
{
	foreach ($defaults as $key=>$default) {
		if(!isset($arr[$key])) $arr[$key] = $default;
	}
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
