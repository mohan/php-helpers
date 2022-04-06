<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress

define('_PHP_HELPERS_EXTRA_IS_DEFINED', true);
define('APP_ENV_IS_DEVELOPMENT', getenv('APP_ENV_IS_DEVELOPMENT') == 'true');
if(!defined('APP_ENV_IS_TEST')) define('APP_ENV_IS_TEST', false);

// 
// Debug helpers
// 

// Simple debug
// Remember to remove all debugs
if(APP_ENV_IS_DEVELOPMENT || APP_ENV_IS_TEST){
	function __d(...$args)
	{
		if(!APP_ENV_IS_TEST) echo "<pre style='width:94%;margin:1%;padding:2%;background:#fff;border:2px solid #aa0000;'>";
		$_debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
		echo "> [" . basename($_debug[0]['file']) . "#{$_debug[0]['line']}]\n";
		foreach($args as $arg) {
			if(APP_ENV_IS_TEST) {
				var_dump($arg);
				continue;
			}
			ob_start();
			var_dump($arg);
			$out = ob_get_contents();
			ob_end_clean();
			echo htmlentities($out);
			echo "<hr/>";
		}
		if(!APP_ENV_IS_TEST) echo "</pre>";
	}


	function __d_(...$args)
	{
		if(!APP_ENV_IS_TEST) echo "<pre style='width:94%;margin:1%;padding:2%;background:#fff;border:2px solid #aa0000;'>";
		$_debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
		echo "> [" . basename($_debug[0]['file']) . "#{$_debug[0]['line']}]\n";
		foreach($args as $arg) {
			if(APP_ENV_IS_TEST) {
				var_dump($arg);
				continue;
			}
			ob_start();
			var_dump($arg);
			$out = ob_get_contents();
			ob_end_clean();
			echo htmlentities($out);
			echo "<hr/>";
		}
		if(!APP_ENV_IS_TEST) echo "</pre>";
		exit;
	}


	function _print_debugpanel(){
		if(isset($_REQUEST['_REQUEST_ARGS_ROUTES_POST'])) _print_debug_routes_post(...$_REQUEST['_REQUEST_ARGS_ROUTES_POST']);

		if(sizeof($_GET) > 0) _print_debug_request_args('$_GET params', $_GET);
		if(sizeof($_POST) > 0) _print_debug_request_args('$_POST params', $_POST);
		if(sizeof($_COOKIE) > 0) _print_debug_request_args('$_COOKIE params', $_COOKIE);
		if(md5_cookie_get('flash')) _print_debug_request_args('$_REQUEST[\'flash\']', ['flash'=>md5_cookie_get('flash')]);

		if(isset($_REQUEST['_REQUEST_ARGS_PERMITTED_PARAMS'])) _print_debug_permitted_params(...$_REQUEST['_REQUEST_ARGS_PERMITTED_PARAMS']);
		if(isset($_REQUEST['_REQUEST_ARGS_ROUTES'])) _print_debug_routes_pre(...$_REQUEST['_REQUEST_ARGS_ROUTES']);
	}


	function _print_debug_permitted_params($get_param_names, $post_param_names, $cookie_param_names)
	{
		_print_debug_request_args('All specified $_GET params that are permitted', $get_param_names);
		_print_debug_request_args('All specified $_POST params that are permitted', $post_param_names);
		_print_debug_request_args('All specified $_COOKIE params that are permitted', $cookie_param_names);
	}


	function _print_debug_routes_pre($get_action_names, $post_action_names, $patch_action_names, $delete_action_names)
	{
		_print_url_helpers($get_action_names, $post_action_names, $patch_action_names, $delete_action_names);
		_print_debug_request_args('PHP Variables $_SERVER',$_SERVER);
	}


	function _print_debug_routes_post($method_name, $required_params, $action_name)
	{
		$args = [
			"\$_REQUEST['CURRENT_METHOD']" => $_REQUEST['CURRENT_METHOD'],
			"\$_REQUEST['CURRENT_ACTION']" => $_REQUEST['CURRENT_ACTION'],
			'ROOT_URL' => defined('ROOT_URL') ? ROOT_URL : '',
			'Action function' => "function $action_name(){ ... }",
			'Required params for action' => $required_params
		];

		if($_SERVER['REQUEST_METHOD'] == 'POST') $args['$_POST'] = json_encode($_POST);

		$args['APP_ENV_IS_DEVELOPMENT'] = (APP_ENV_IS_DEVELOPMENT ? 'true' : 'false') . "\nTODO: Disable in production and test env.";

		_print_debug_request_args('Current Action', $args);
	}


	function _print_debug_request_args($name, $args, $table_headers=['Param', 'Value'], $encode=true)
	{
		$data = [];
		foreach ($args as $key => $value) {
			if($value) $data[] = [$key, $value];
		}
		if($name) echo tag($name, [], 'h3');
		echo tag_table($table_headers, $data,
					['class'=>'table w-100', 'cellspacing'=>0],
					function($row_value, $header_key) use($encode){
						if($header_key == 0){
							return htmlentities($row_value[0]);
						} elseif (is_string($row_value[1]) || is_numeric($row_value[1])) {
							return tag($row_value[$header_key], [], 'textarea');
						} elseif ((is_bool($row_value[1]) || is_array($row_value[1])) && $encode) {
							return tag(json_encode($row_value[$header_key]), [], 'textarea');
						} elseif(is_array($row_value[1])) {
							$out = '';
							foreach ($row_value[1] as $value) {
								$out .= tag($value, ['style'=>'border-width:1px;'], 'textarea');
							}
							return $out;
						}
					});
	}


	function _print_url_helpers($get_action_names, $post_action_names, $patch_action_names, $delete_action_names)
	{
		if(!APP_ENV_IS_DEVELOPMENT) return;

		if(sizeof($get_action_names) > 0){
			$url_helpers_arr = [];
			$url_helpers_arr = _generate_url_helper('get', $get_action_names);
			_print_debug_request_args('All GET actions with required params from [$_GET, $_REQUEST]', $url_helpers_arr, ['GET Route URI', 'Info'], false);
		}
		if(sizeof($post_action_names) > 0){
			$url_helpers_arr = [];
			$url_helpers_arr = _generate_url_helper('post', $post_action_names);
			_print_debug_request_args('All POST actions with required params from [$_GET, $_POST, $_REQUEST]', $url_helpers_arr, ['POST Route URI', 'Info'], false);
		}
		if(sizeof($patch_action_names) > 0){
			$url_helpers_arr = [];
			$url_helpers_arr = _generate_url_helper('patch', $patch_action_names);
			_print_debug_request_args('All PATCH actions with required params from [$_GET, $_POST, $_REQUEST]', $url_helpers_arr, ['PATCH Route URI', 'Info'], false);
		}
		if(sizeof($delete_action_names) > 0){
			$url_helpers_arr = [];
			$url_helpers_arr = _generate_url_helper('delete', $delete_action_names);
			_print_debug_request_args('All DELETE actions with required params from [$_GET, $_POST, $_REQUEST]', $url_helpers_arr, ['DELETE Route URI', 'Info'], false);
		}
	}


	function _generate_url_helper($method_name, $action_names)
	{
		$url_helpers_arr = [];
		foreach ($action_names as $uri_route => $required_params) {
			if($method_name == 'get' && is_string($required_params)){
				$url_helpers_arr[$uri_route] = [$required_params];
				$url_helpers_arr[$uri_route][] = "<?= urltoget('" . $uri_route . "') ?>";
				continue;
			}

			$required_params_as_args = [];
			foreach ($required_params[0] as $param_name) {
				$required_params_as_args[] = "'$param_name'=> ";
			}

			$action_name = $method_name . '_' . preg_replace("/[^a-zA-Z0-9]/", '_', $uri_route);
			$url_helpers_arr[$uri_route] = [json_encode($required_params)];
			$url_helpers_arr[$uri_route][] = "function $action_name(){ ... }";

			switch ($method_name) {
				case 'get':
					if(sizeof($required_params_as_args) == 0){
						$url_helpers_arr[$uri_route][] = "<?= urltoget('" . $uri_route . "') ?>";
					} else {
						$url_helpers_arr[$uri_route][] = "<?= urltoget('" . $uri_route . "', [" . join(", ", $required_params_as_args) . "]) ?>";
					}
					break;
				
				default:
					if(sizeof($required_params_as_args) == 0){
						$url_helpers_arr[$uri_route][] = "<?= formto('" . $uri_route . "') ?>";
						$url_helpers_arr[$uri_route][] = "<?= urltopost('" . $uri_route . "') ?>";
					} else {
						$url_helpers_arr[$uri_route][] = "<?= formto('" . $uri_route . "', [" . join(", ", $required_params_as_args) . "]) ?>";
						$url_helpers_arr[$uri_route][] = "<?= urltopost('" . $uri_route . "') ?>";
					}
					break;
			}
		}

		return $url_helpers_arr;
	}
}

// 
// END Debug helpers
// 







// 
// Markdown
// 

function render_markdown($text, $attrs=[], $enable_shortcodes=false)
{
	static $patterns = [[
		"/^(\t*)---$/",													// hr
		"/\*\*\*([^*]+)\*\*\*/",										// bold italic
		"/\*\*([^*]+)\*\*/",											// italic
		"/([^*\t])\*([^*]+)\*/",										// bold
		"/~~([^~]+)~~/",												// strikethrough
		"/\[([^\]]+)\]\((\/|#|\?|[a-z]+:\/\/)([^\)]*)\)/",				// link with text
		"/\((\/|#|\?|[a-z]+:\/\/)([^\)]*)\)/",							// link without text
		"/`([^`]+)`/",													// code
		"/^(\t*)-\s\[x\]\s(.+)$/",										// Task list item checked
		"/^(\t*)-\s\[X\]\s(.+)$/",										// Task list item checked and striked
		"/^(\t*)-\s\[!\]\s(.+)$/",										// Task list item striked unchecked
		"/^(\t*)-\s\[\s\]\s(.+)$/",										// Task list unchecked
		"/^(\t*)\*\s/",													// Bullet list
		"/^(\t*)\-\s/",													// Dash list
		"/^(\t*)(\d+\.)\s/"												// Numbered list
	],[
		"$1<hr/>",
		"<strong><em>$1</em></strong>",
		"<em>$1</em>",
		"$1<strong>$2</strong>",
		"<strike>$1</strike>",
		"<a href='$2$3'>$1</a>",
		"<a href='$1$2'>$1$2</a>",
		"<span class='md-code'>$1</span>",
		"$1<span class='md-task-list md-task-list-checked'><input type='checkbox' checked='checked' disabled='true' /> $2</span>",
		"$1<span class='md-task-list md-task-list-checked-striked'><input type='checkbox' checked='checked' disabled='true' /> <strike>$2</strike></span>",
		"$1<span class='md-task-list md-task-list-unchecked-striked'><input type='checkbox' disabled='true' /> <strike>$2</strike></span>",
		"$1<span class='md-task-list md-task-list-unchecked'><input type='checkbox' disabled='true' /> $2</span>",
		"$1<span class='md-list-bullet'>&bull;</span> ",
		"$1<span class='md-list-dash'>&ndash;</span> ",
		"$1<span class='md-list-number'>$2</span> "
	]];
	
	$out = '';
	// Todo: Optimize, use substr.
	$lines = explode("\n", $text);
	$is_codeblock = false;
	$tab_size_for_current_block = 0;
	$codeblock_attr = 'raw';
	$data_table_header = [];
	$data_table = [];
	$data_table_i = 0;
	foreach ($lines as $i => $line) {
		$matches = [];
		if(preg_match("/^(\t*)```([[:alnum:]\s]*)$/", $line, $matches)){
			if($is_codeblock){
				if(_str_contains($codeblock_attr, 'table')){
					$out .= tag_table($data_table_header, $data_table);
					$data_table_header = [];
					$data_table = [];
					$data_table_i = 0;
				}

				$is_codeblock = false;
				$tab_size_for_current_block = 0;
				$codeblock_attr = 'raw';
				$out .= "</div>\n";
			} else {
				$is_codeblock = true;
				$tab_size_for_current_block = strlen($matches[1]);
				$codeblock_attr = str_replace(' ', ' md-codeblock-', $matches[2]);
				if(!$codeblock_attr) $codeblock_attr = 'plain';
				$class_list = "tab-count-$tab_size_for_current_block md-codeblock-" . $codeblock_attr;
				$out .= "<div class='md-codeblock $class_list'>\n";
			}
			continue;
		}

		if($is_codeblock && _str_contains($codeblock_attr, 'table')){
			if($data_table_i == 0) $data_table_header = str_getcsv(trim($line));
			else $data_table[] = str_getcsv(trim($line));

			$data_table_i++;
			continue;
		}

		$line = htmlentities($line);

		if($is_codeblock && !_str_contains($codeblock_attr, 'raw')){
			$line = preg_replace($patterns[0], $patterns[1], $line);
		}

		if(!$is_codeblock){
			$line = preg_replace($patterns[0], $patterns[1], $line);
		}

		if(preg_match("/^\t*(#{1,5})\s(.+)$/", $line, $matches)){
			// headings
			$_tag = 'h' . strlen($matches[1]);
			$_id = strtolower(preg_replace("/[^a-zA-Z\d]/", '-', $matches[2]));
			$out .= "<$_tag id='$_id' class='md-heading'><a class='md-hash-link' href='#$_id'>\n" . htmlentities($matches[2]) . "\n</a></$_tag>\n";
			continue;
		}

		$tabs = '';
		if(preg_match("/^\t+/", $line, $matches)){
			$tabs = " class='tab-count-" . (strlen($matches[0]) - $tab_size_for_current_block) . "'";
		}

		$_tag = _str_contains($line, '<hr/>') ? 'div' : 'p';
		$line = strlen($line) == 0 ? "<p class='md-br'></p>\n" : "<$_tag$tabs>\n" . $line . "\n</$_tag>\n";

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
	if(!preg_match_all($shortcode_list_regex, $text, $matches)) return false;

	
	$shortcodes_matches = [];
	$shortcode_replacements = [];
	foreach ($matches[0] as $key => $match) {
		$full_shortcode = trim($matches[0][$key]);
		$name = $matches[1][$key];
		$args_str = $matches[2][$key];
		
		$args = [];
		if(_str_contains($args_str, '=')){
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
// Config file helpers
//

// Defines constants CONFIG_NAME from config ini file
function filter_set_config($filepath, $prefix='CONFIG_')
{
	$config = parse_ini_file($filepath);

	foreach ($config as $key => $value) {
		define(strtoupper($prefix . $key), $value);
	}
}

// 
// END Config file helpers
//
