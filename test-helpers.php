<?php
// php-helpers
// test-helpers
// License: GPL
// Status: Work in progress

define('RENDER_TO_STRING', true);
define('CUSTOM_HEADER_HANDLERS', true);


if(PHP_SAPI != 'cli') {
	exit;
}



// 
// Test functions
// 
function t($test_name, $result)
{
	if($result === false || $result == NULL || !$result) {
		echo "  ✗ Fail: " . $test_name . "\n\n";
		debug_print_backtrace();
		exit;
	} else {
		echo "  ✓ Pass: " . $test_name . "\n";
	}
}


function is_redirect($expected_redirect_url, $response)
{
	return (array_search("Location: $expected_redirect_url", $response['headers']) !== false);
}

function is_not_redirect($response)
{
	foreach ($response['headers'] as $h) {
		if(strpos($h, 'Location: ') !== false) return false;
	}

	return true;
}

function is_flash($expected_message, $response)
{
	return $response['cookies']['flash'] == $expected_message;
}






// 
// Request functions
// 
function do_get($uri_str, $cookies=[])
{
	_clear_request();
	$uri = parse_url($uri_str);
	parse_str(isset($uri['query']) ? $uri['query'] : '', $_GET);
	$_SERVER['REQUEST_METHOD'] = 'GET';
	_set_params($_COOKIE, $cookies);
	_set_params($_REQUEST, $_GET);

	$body = defined('APP_NAME') ? call_user_func(APP_NAME . '_initialize') : init();
	$headers = _header();
	$cookies = _setcookie();

	return ['url' => $uri_str, 'body' => $body, 'headers' => $headers, 'cookies' => $cookies];
}


function do_post($uri_str, $post_params=[], $cookies=[])
{
	_clear_request();
	$uri = parse_url($uri_str);
	parse_str($uri['query'], $_GET);
	$_SERVER['REQUEST_METHOD'] = 'POST';
	_set_params($_COOKIE, $cookies);
	_set_params($_POST, $post_params);
	_set_params($_REQUEST, $post_params);
	_set_params($_REQUEST, $_GET);

	$body = defined('APP_NAME') ? call_user_func(APP_NAME . '_initialize') : init();
	$headers = _header();
	$cookies = _setcookie();

	return ['url' => $uri_str, 'body' => $body, 'headers' => $headers, 'cookies' => $cookies];
}



function call_tests($function_names)
{
	$functions_to_implement = [];

	$start_time = time();

	echo "\n" . str_repeat('-', 60) . "\n";
	foreach ($function_names as $name) {
		$test_name = "test_$name";
		if(function_exists($test_name)) {
			echo "\n# $test_name\n";
			call_user_func($test_name);
		} else {
			$functions_to_implement[] = $test_name;
		}
	}
	echo "\n" . str_repeat('-', 60) . "\n\n";
	echo "✓ " . (sizeof($function_names) - sizeof($functions_to_implement)) . "/" . sizeof($function_names) . " tests passed.\n\n";

	if(sizeof($functions_to_implement)){
		echo sizeof($functions_to_implement) . " functions not implemented: " . join(', ', $functions_to_implement) . "\n";
	}

	echo "Time taken: " . abs(time() - $start_time) . ' seconds';

	echo "\n\n" . str_repeat('-', 60) . "\n\n\n";
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
