<?php
// php-helpers
// test-helpers
// License: GPL
// Status: Work in progress


define('RENDER_TO_STRING', true);
define('CUSTOM_HEADER_HANDLERS', true);
define('APP_ENV_IS_TEST', true);


if(PHP_SAPI != 'cli') {
	exit;
}



// 
// Test functions
// 
function call_tests_for(...$function_names)
{
	_php_helpers_init();
	
	$functions_to_implement = [];

	$start_time = microtime(true);

	echo str_repeat('-', 60);
	foreach ($function_names as $name) {
		$test_name = "test_$name";
		if(function_exists($test_name)) {
			echo "\n# $test_name\n";
			call_user_func($test_name);
		} else {
			$functions_to_implement[] = $test_name;
		}
	}
	echo str_repeat('-', 60) . "\n";
	echo "  = All " . (sizeof($function_names) - sizeof($functions_to_implement)) . "/" . sizeof($function_names) . " tests passed.\n";

	if(sizeof($functions_to_implement)){
		echo '  ' . sizeof($functions_to_implement) . " functions not implemented: " . join(', ', $functions_to_implement) . "\n";
	}

	echo "  Time taken: " . round((microtime(true) - $start_time), 5) . " seconds\n\n";
}


function t($test_name, $result)
{
	$_debug = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
	$_debug_line_number = " [" . basename($_debug[0]['file']) . "#{$_debug[0]['line']}]\n";

	if($result === false || $result == NULL || !$result) {
		echo "  - Fail: " . $test_name . $_debug_line_number . "\n\n";
		debug_print_backtrace();
		exit;
	} else {
		echo "  + Pass: " . $test_name . $_debug_line_number;
	}
}


function is_redirect($expected_redirect_url, $response)
{
	if(array_search("Location: $expected_redirect_url", $response['headers']) === false){
		// var_dump($response);
		return false;
	}

	return true;
}

function is_not_redirect($response)
{
	foreach ($response['headers'] as $h) {
		if(strpos($h, 'Location: ') !== false) {
			// var_dump($response);
			return false;
		}
	}

	return true;
}

function is_flash($expected_message, $response)
{
	if($response['cookies']['flash'] != $expected_message){
		// var_dump($response);
		return false;
	}

	return true;
}

function contains($response, ...$expected_html_substrs)
{
	if(!_str_contains($response['body'], ...$expected_html_substrs)){
		// var_dump($response);
		return false;
	}

	return true;
}

function pagetitle_is($str, $response)
{
	if(!_str_contains($response['body'], $str)){
		// var_dump($response);
		return false;
	}

	return true;
}






// 
// Request functions
// 
function do_get($uri_str, $cookies=[])
{
	_clear_request();
	$uri = parse_url($uri_str);
	parse_str(isset($uri['query']) ? $uri['query'] : '', $_GET);
	$_SERVER['REQUEST_METHOD'] = 'get';
	if($uri_str[0] == '/') $_SERVER['REQUEST_URI'] = $uri_str;
	_set_params($_COOKIE, $cookies);
	_set_params($_REQUEST, $_GET);
	_php_helpers_init();

	$body = initialize();
	$headers = _header();
	$cookies = _setcookie();

	return ['url' => $uri_str, 'body' => $body, 'headers' => $headers, 'cookies' => $cookies];
}


function do_post($uri_str, $post_params=[], $cookies=[])
{
	_clear_request();
	$uri = parse_url($uri_str);
	parse_str($uri['query'], $_GET);
	$_SERVER['REQUEST_METHOD'] = 'post';
	if($uri_str[0] == '/') $_SERVER['REQUEST_URI'] = $uri_str;
	_set_params($_COOKIE, $cookies);
	_set_params($_POST, $post_params);
	_set_params($_REQUEST, $post_params);
	_set_params($_REQUEST, $_GET);
	_php_helpers_init();

	$body = initialize();
	$headers = _header();
	$cookies = _setcookie();

	return ['url' => $uri_str, 'body' => $body, 'headers' => $headers, 'cookies' => $cookies];
}


















// 
// Internal
// 


function _clear_request()
{
	foreach ($_POST as $key => $value) {
		unset($_POST[$key]);
	}
	foreach ($_GET as $key => $value) {
		unset($_GET[$key]);
	}
	foreach ($_COOKIE as $key => $value) {
		unset($_COOKIE[$key]);
	}
	foreach ($_REQUEST as $key => $value) {
		unset($_REQUEST[$key]);
	}
	_header('__reset');
	_setcookie('__reset');
	$_SERVER['REQUEST_URI'] = '';
}


function _set_params(&$input, $params)
{
	foreach ($params as $key => $value) {
		$input[$key] = $value;
	}
}


// Capture req/res headers

function _setcookie($name="", $value="", $expires_or_options=0, $path="", $domain="", $secure=false, $httponly=false)
{
	static $list = [];

	if($name == '__reset') {
		$list = [];
		return;
	}
	if(!$name) {
		if(isset($list['flash']) && $list['flash']){
			$list['flash'] = explode('%', base64_decode($list['flash']))[0];
		} else {
			$list['flash'] = $_REQUEST['flash'];
		}

		return $list;
	}
	$list[$name] = $value;
}

function _header($header=false)
{
	static $list = [];

	if($header == '__reset') {
		$list = [];
		return;
	}
	if(!$header) return $list;
	$list[] = $header;
}
