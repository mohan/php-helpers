<?php
// Peanuts
// License: GPL


// 
// Templates
// 

function render($template_name, $args=[], $html_container='index.php')
{
	$template_path = './' . APP_NAME . '/templates/' . APP_TEMPLATE . '/';
	$uri = $_GET['uri'];

	extract($args, EXTR_SKIP);
	include $template_path . $html_container;
	exit;
}


function render_partial($template_name, $args=[], $return=false)
{
	$template_path = './' . APP_NAME . '/templates/' . APP_TEMPLATE . '/';
	
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
// URL helpers
// 

function urlto_template_asset($uri)
{
	return CONFIG_ROOT_URL . APP_NAME . '/templates/' . APP_TEMPLATE . '/assets/' . $uri;
}


function urltoget($uri, $args=[], $arg_separator='&')
{
	if(!$args) $args = [];

	$hash = isset($args['__hash']) ? '#' . $args['__hash'] : '';
	unset($args['__hash']);

	$_args = ['uri' => $uri];
	$args = array_merge($_args, $args);

	return CONFIG_ROOT_URL . '?' . http_build_query($args, '', $arg_separator) . $hash;
}


function urltopost($uri, $args=[], $arg_separator='&')
{
	if(!$args) $args = [];
	
	$_args = ['post_uri' => $uri];
	$args = array_merge($_args, $args);

	return CONFIG_ROOT_URL . '?' . http_build_query($args, '', $arg_separator);
}


function redirectto($uri, $args=[])
{
	header('Location: ' . urltoget($uri, $args));
	exit;
}


function get_404()
{
	header("HTTP/1.1 404 Not Found");
	render('404.php', ['__pagetitle'=>'404']);
}














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

	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";
	
	$out .= "<$name $attrs_str";

	if($name != 'input'){
		$out .= ">" . htmlentities($html);
		if($closing) $out .= "</$name>";
	} else {
		$out .= "value='" . htmlentities($html) . "'";
		$out .= " />";
	}

	return $out;
}


function tag_table($headers, $data, $attrs=[], $escape_values=true)
{
	foreach ($attrs as $key => $value) $attrs_str .= "$key='" . htmlentities($value) . "' ";

	$out = "<table $attrs_str><thead>\n<tr>";
	foreach ($headers as $key => $value) {
		$out .= '<th>' . ($escape_values ? htmlentities($value) : $value) . '</th>';
	}
	$out .= "</tr>\n</thead>\n<tbody>\n";
	foreach ($data as $row_key => $row_value) {
		$out .= "<tr>\n";
		foreach ($row_value as $cell_key => $cell_value) {
			$out .= '<td>' . ($escape_values ? htmlentities($cell_value) : $cell_value) . "</td>\n";
		}
		$out .= "</tr>\n";
	}
	$out .= "</tbody></table>";

	return $out;
}












// 
// Markdown
// 

function render_markdown($text, $shortcodes=false)
{
	$text = strip_tags($text);
	
	// Todo: Optimize, use substr.
	$lines = explode("\n", $text);
	foreach ($lines as $i => $line) {
		if(strlen(trim($line)) == 0) $line = "&nbsp;";
		
		// Shortcode
		if($shortcodes && preg_match("/\[[a-z]+[^\]]*\][^(]/", $line)){
			$out .= process_shortcodes($line);
		} else {
			$out .= "<p>\n$line\n</p>\n";
		}
	}

	return $out;
}












// 
// Shortcodes
// 

// Process all shortcodes using respective functions and replace with return values.
function process_shortcodes($text)
{
	// start with [
	// \[([a-z]+) = name
	// ([^\]]*) = args_str
	// [^\(] = Should not match markdown links
	if(!preg_match_all("/\[([a-z]+)([^\]]*)\][^(]/", $text, $matches)) return $text;

	$shortcodes_list = shortcodes_list();
	$shortcodes_matches = [];
	$shortcode_replacements = [];
	foreach ($matches[0] as $key => $match) {
		$full_shortcode = trim($matches[0][$key]);
		$name = $matches[1][$key];
		$args_str = $matches[2][$key];

		if(!in_array($name, $shortcodes_list)) continue;
		
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
		$shortcode_replacements[] = call_user_func("shortcode_$name", $args);
	}

	return preg_replace($shortcodes_matches, $shortcode_replacements, $text, 1);
}
























// 
// Flash messages
// 

function flash_set($html, $in_current_request=false)
{
	if($html) {
		if($in_current_request) $_REQUEST['flash'] = $html;
		else secure_cookie_set('auth_flash', $html);
	}
}


function flash_clear()
{
	cookie_delete('auth_flash');
}


function filter_set_flash()
{
	$flash = secure_cookie_get('auth_flash');

	if($flash){
		$_REQUEST['flash'] = $flash;
		cookie_delete('auth_flash');
	}
}


















// 
// Secure cookie - Cookie with added authenticity
// 


function secure_cookie_set($name, $value)
{
	// <= 1/2 day; may expire at am/pm;
	$authenticity = md5($name . '%' . $value . '%' . date('y-m-d-a') . '%' . SECURE_HASH);

	// Expires end of session/browser close
	setcookie($name, "$value%$authenticity", 0, CONFIG_ROOT_URL, '', false, true);
}


function secure_cookie_get($name)
{
	if(!isset($_COOKIE[$name])) return false;

	$parts = explode('%', $_COOKIE[$name]);
	$value = $parts[0];
	$given_authenticity = $parts[1];

	$authenticity = md5($name . '%' . $value . '%' . date('y-m-d-a') . '%' . SECURE_HASH);

	// Todo: If it fails, check if it matches -6
	if($given_authenticity != $authenticity) return false;

	return $value;
}


function cookie_delete($name)
{
	setcookie($name, '', time() - 3600);
}
















// 
// Debug helper
// 

// Simple debug
// Remember to remove all debugs
function __d($exit, ...$args)
{
	echo "<pre style='width:94%;margin:1%;padding:2%;background:#fff;border:2px solid #aa0000;'>";
	foreach($args as $arg) {
		echo htmlentities(print_r($arg, true));
		echo "<hr/>";
	}
	echo "</pre>";
	if($exit) exit;
}















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
// Router
// 

// Map action names to functions and call current name
// Max action name 32 chars
function filter_routes($get_action_names, $post_action_names)
{
	if( is_string($_GET['post_uri']) && array_key_exists($_GET['post_uri'], $post_action_names)){
		if( array_intersect($post_action_names[$_GET['post_uri']], array_keys($_REQUEST)) != $post_action_names[$_GET['post_uri']]){
			return get_404();
		}

		return call_user_func( 'post_' . preg_replace("/[^a-zA-Z0-9]/", '_', $_GET['post_uri']));
	}

	if( is_string($_GET['uri']) && array_key_exists($_GET['uri'], $get_action_names)){
		if( array_intersect($get_action_names[$_GET['uri']], array_keys($_REQUEST)) != $get_action_names[$_GET['uri']]){
			return get_404();
		}

		return call_user_func( 'get_' . preg_replace("/[^a-zA-Z0-9]/", '_', $_GET['uri']));
	}

	if( !$_GET['uri'] ){
		return get_root();
	}

	return get_404();
}















// 
// Permitted Params
// 


// Permitted GET, POST, cookie params, with strlen check and typecasting
// Ex: $get_param_names = [ 'param_name' => int_length ... ]
function filter_permitted_params($get_param_names, $post_param_names, $cookie_param_names, $get_typecasts, $post_typecasts)
{
	_filter_permitted_params_names($_GET, $get_param_names);
	_filter_permitted_params_names($_POST, $post_param_names);
	_filter_permitted_params_names($_COOKIE, $cookie_param_names);

	_filter_permitted_params_typecast($_GET, $get_typecasts);
	_filter_permitted_params_typecast($_POST, $post_typecasts);
}


function _filter_permitted_params_names($input, $permitted_arr)
{
	foreach ($input as $key => $value) {
		if(!array_key_exists($key, $permitted_arr)) unset($input[$key]);
		else if(isset($input[$key])){
			if(is_int($permitted_arr[$key]) && strlen($input[$key]) > $permitted_arr[$key]) {
				get_404();
			}
			else if(is_string($permitted_arr[$key]) && !preg_match($permitted_arr[$key], $input[$key])) {
				get_404();
			}
		}
	}
}


function _filter_permitted_params_typecast($input, $typecast_def_arr)
{
	foreach ($typecast_def_arr as $name => $type) {
		if(is_string($input[$name]))
		switch ($type) {
			case 'int': $input[$name] = intval($input[$name]); break;
			case 'float': $input[$name] = floatval($input[$name]); break;
			case 'bool': $input[$name] = boolval($input[$name]); break;
		}
	}
}
