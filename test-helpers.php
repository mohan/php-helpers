<?php
// php-helpers
// test-helpers
// License: GPL
// Status: Work in progress

if(PHP_SAPI != 'cli'){
	header('HTTP/1.1 404 Not Found');
	echo "404 Not Found";
	exit;
}

define('RENDER_TO_STRING', true);


function _setcookie($name, $value="", $expires_or_options=0, $path="", $domain="", $secure=false, $httponly=false)
{
	// No setcookie in testenv
}

function _header($header=false)
{
	static $list = [];

	if($header == 'reset') {
		$list = [];
		return;
	}
	if(!$header) return $list;
	$list[] = $header;
}


// 
// Test functions
// 
function call_tests($function_names)
{
	echo "\n\n" . str_repeat('-', 60) . "\n";
	foreach ($function_names as $name) {
		$test_name = "test_$name";
		echo "\n# $test_name\n";
		if(function_exists($test_name)) {
			call_user_func($test_name);
		} else {
			echo "  > function $test_name() not found!\n";
		}
	}
	echo "\n" . str_repeat('-', 60) . "\n\n\n";
}


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








// 
// Request functions
// 
function do_get($uri_str, $cookies=[])
{
	_clear_request();
	$uri = parse_url($uri_str);
	parse_str($uri['query'], $_GET);
	$_SERVER['REQUEST_METHOD'] = 'GET';
	_set_params($_COOKIE, $cookies);
	_set_params($_REQUEST, $_GET);

	$body = initialize();
	$headers = _header();

	return ['url' => $uri_str, 'body' => $body, 'headers' => $headers];
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

	$body = initialize();
	$headers = _header();

	return ['url' => $uri_str, 'body' => $body, 'headers' => $headers];
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
	_header('reset');
}


function _set_params(&$input, $params)
{
	foreach ($params as $key => $value) {
		$input[$key] = $value;
	}
}