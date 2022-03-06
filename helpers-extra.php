<?php
// php-helpers
// 23 functions for building a PHP application.
// License: GPL
// Status: Work in progress

define('__PHP_HELPERS_EXTRA_IS_DEFINED', true);
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
						['class'=>'table w-100', 'style'=>'margin: 0 0 50px 0;'],
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
		_print_debug_request_args('Function calls for GET METHOD request', $url_helpers_arr, ['GET Route Name', 'Function calls'], false);
		$url_helpers_arr = [];
		$url_helpers_arr = _generate_url_helper('post', $get_action_names);
		_print_debug_request_args('Function calls for POST METHOD request', $url_helpers_arr, ['POST Route Name', 'Function calls'], false);
		$url_helpers_arr = [];
		$url_helpers_arr = _generate_url_helper('patch', $get_action_names);
		_print_debug_request_args('Function calls for PATCH METHOD request', $url_helpers_arr, ['PATCH Route Name', 'Function calls'], false);
		$url_helpers_arr = [];
		$url_helpers_arr = _generate_url_helper('delete', $get_action_names);
		_print_debug_request_args('Function calls for DELETE METHOD request', $url_helpers_arr, ['DELETE Route Name', 'Function calls'], false);
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
			$url_helpers_arr[$uri_route] = ["function $action_name()"];

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



