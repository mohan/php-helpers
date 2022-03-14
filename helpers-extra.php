<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress

define('_PHP_HELPERS_EXTRA_IS_DEFINED', true);
define('APP_ENV_IS_DEVELOPMENT', getenv('APP_ENV_IS_DEVELOPMENT') == 'true');


// 
// Debug helpers
// 

// Simple debug
// Remember to remove all debugs
if(APP_ENV_IS_DEVELOPMENT){
	function __d(...$args)
	{
		echo "<pre style='width:94%;margin:1%;padding:2%;background:#fff;border:2px solid #aa0000;'>";
		foreach($args as $arg) {
			ob_start();
			var_dump($arg);
			$out = ob_get_contents();
			ob_end_clean();
			echo htmlentities($out);
			echo "<hr/>";
		}
		echo "</pre>";
	}


	function __d_(...$args)
	{
		echo "<pre style='width:94%;margin:1%;padding:2%;background:#fff;border:2px solid #aa0000;'>";
		foreach($args as $arg) {
			ob_start();
			var_dump($arg);
			$out = ob_get_contents();
			ob_end_clean();
			echo htmlentities($out);
			echo "<hr/>";
		}
		echo "</pre>";
		exit;
	}


	function _print_debug_permitted_params($get_param_names, $post_param_names, $cookie_param_names)
	{
		_print_debug_request_args('$_GET params', $_GET);
		_print_debug_request_args('$_POST params', $_POST);
		_print_debug_request_args('$_COOKIE params', $_COOKIE);
		_print_debug_request_args('$_REQUEST[\'flash\']', ['flash'=>secure_cookie_get('flash')]);
		_print_debug_request_args('All specified $_GET params that are permitted', $get_param_names);
		_print_debug_request_args('All specified $_POST params that are permitted', $post_param_names);
		_print_debug_request_args('All specified $_COOKIE params that are permitted', $cookie_param_names);
	}


	function _print_debug_routes_pre($get_action_names, $post_action_names, $patch_action_names, $delete_action_names)
	{
		_print_url_helpers($get_action_names, $post_action_names, $patch_action_names, $delete_action_names);
		_print_debug_request_args('PHP Variables', [
										"PHP_SAPI" => PHP_SAPI,
										"\$_SERVER['SERVER_NAME']" => $_SERVER['SERVER_NAME'],
										"\$_SERVER['REQUEST_METHOD']" => $_SERVER['REQUEST_METHOD'],
										"\$_SERVER['REQUEST_URI']" => $_SERVER['REQUEST_URI']
									]);
	}


	function _print_debug_routes_post($method_name, $uri_param_key, $required_params, $action_name)
	{
		$args = [
			'Internal HTTP method' => $method_name,
			'Route' => '$_GET[\'' . $uri_param_key . '\'] == "' . $_GET[$uri_param_key] . '"',
			'Action function' => "function $action_name(){ ... }",
			'Required params for action' => $required_params,
			'$_GET' => json_encode($_GET)
		];

		if($_SERVER['REQUEST_METHOD'] == 'POST') $args['$_POST'] = json_encode($_POST);

		$args['APP_ENV_IS_DEVELOPMENT'] = (APP_ENV_IS_DEVELOPMENT ? 'true' : 'false') . "\nTODO: Disable in production and test env.";

		_print_debug_request_args('Current Route', $args);
	}


	function _print_debug_request_args($name, $args, $table_headers=['Param', 'Value'], $encode=true)
	{
		if(!isset($_REQUEST['DEBUG_REQUEST_ARGS_HTML'])) $_REQUEST['DEBUG_REQUEST_ARGS_HTML'] = '';

		$data = [];
		foreach ($args as $key => $value) {
			$data[] = [$key, $value];
		}
		if($name) $_REQUEST['DEBUG_REQUEST_ARGS_HTML'] .= tag($name, [], 'h3');
		$_REQUEST['DEBUG_REQUEST_ARGS_HTML'] .=
					tag_table($table_headers, $data,
						['class'=>'table w-100'],
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

		$url_helpers_arr = [];
		$url_helpers_arr = _generate_url_helper('get', $get_action_names);
		_print_debug_request_args('All GET actions with required params from [$_GET, $_REQUEST]', $url_helpers_arr, ['GET Route URI', 'Info'], false);
		$url_helpers_arr = [];
		$url_helpers_arr = _generate_url_helper('post', $post_action_names);
		_print_debug_request_args('All POST actions with required params from [$_GET, $_POST, $_REQUEST]', $url_helpers_arr, ['POST Route URI', 'Info'], false);
		$url_helpers_arr = [];
		$url_helpers_arr = _generate_url_helper('patch', $patch_action_names);
		_print_debug_request_args('All PATCH actions with required params from [$_GET, $_POST, $_REQUEST]', $url_helpers_arr, ['PATCH Route URI', 'Info'], false);
		$url_helpers_arr = [];
		$url_helpers_arr = _generate_url_helper('delete', $delete_action_names);
		_print_debug_request_args('All DELETE actions with required params from [$_GET, $_POST, $_REQUEST]', $url_helpers_arr, ['DELETE Route URI', 'Info'], false);
	}


	function _generate_url_helper($method_name, $action_names)
	{
		$url_helpers_arr = [];
		foreach ($action_names as $uri_route => $required_params) {
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



