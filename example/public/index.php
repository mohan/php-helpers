<?php

define('APP_DIR', __DIR__ . '/../');

if(strpos($_SERVER['REQUEST_URI'], '/sub-app/') === 0){
	// Sub application
	define('PUBLIC_URL', '/');
	define('ROOT_URL', '/sub-app/');
	define('SECURE_HASH', '00000000000000000000000000000000');
	require '../initialize.php';
} else {
	// Main application
	define('ROOT_URL', '/');
	define('SECURE_HASH', '00000000000000000000000000000000');
	require '../initialize.php';
}

initialize();
