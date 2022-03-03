<?php
// php-helpers
// test-helpers
// License: GPL
// Status: Work in progress

if(PHP_SAPI != 'cli' && !defined('ENABLE_WEB_TEST_RESULTS_INTERFACE')) {
	_test_page_not_found();
}

function _test_page_not_found()
{
	header('HTTP/1.1 404 Not Found');
	echo "404 Not Found";
	exit;	
}

function call_tests($function_names, $filename=APP_NAME){
	if(defined('ENABLE_WEB_TEST_RESULTS_INTERFACE') && PHP_SAPI != 'cli'){
		if($_SERVER['REMOTE_ADDR'] == '127.0.0.1'	&&
			$_SERVER['SERVER_NAME'] == '127.0.0.1'	&&
			$_SERVER['REQUEST_METHOD'] == 'GET'		&&
			$_GET['token'] == "test-" . intval(time() / 10000)
		){
			echo "<style>body{background:#f9f9f9;margin:50px 0 900px 0;}pre{background:#fff;margin:20px auto;max-width:70%;border-radius:5px;border: 1px solid #ccc;padding: 20px 40px;font-size:1.15em;line-height:1.7em;}</style>";
			echo "<pre>";
			echo "<h1 style='border-bottom: 1px solid #ccc;padding-bottom:20px;'>" . basename($filename) . " tests</h1>";
			_call_tests($function_names);
			echo "</pre>";
			// To stop url request
			exit;
		} else {
			_test_page_not_found();
		}
	}

	if(PHP_SAPI == 'cli') {
		echo "Web test results interface token: token=" . intval(time() / 10000) . "\n";
		_call_tests($function_names);
	}
}


define('RENDER_TO_STRING', true);


function _setcookie($name="", $value="", $expires_or_options=0, $path="", $domain="", $secure=false, $httponly=false)
{
	static $list = [];

	if($name == '__reset') {
		$list = [];
		return;
	}
	if(!$name) {
		if($list['flash']){
			$list['flash'] = reset(explode('%', base64_decode($list['flash'])));
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


// 
// Test functions
// 
function _call_tests($function_names)
{
	$functions_to_implement = [];

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
	echo "\n" . str_repeat('-', 60) . "\n\n\n";
	echo "✓ " . (sizeof($function_names) - sizeof($functions_to_implement)) . "/" . sizeof($function_names) . " tests passed.\n\n";

	if(sizeof($functions_to_implement)){
		echo sizeof($functions_to_implement) . " functions not implemented: " . join(', ', $functions_to_implement) . "\n";
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
	parse_str($uri['query'], $_GET);
	$_SERVER['REQUEST_METHOD'] = 'GET';
	_set_params($_COOKIE, $cookies);
	_set_params($_REQUEST, $_GET);

	$body = defined('APP_NAME') ? call_user_func(APP_NAME . '_init') : init();
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

	$body = defined('APP_NAME') ? call_user_func(APP_NAME . '_init') : init();
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
}


function _set_params(&$input, $params)
{
	foreach ($params as $key => $value) {
		$input[$key] = $value;
	}
}