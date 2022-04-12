<?php

define('APP_DIR', __DIR__ . '/../');

require '../initialize.php';
initialize();


/*
// 
// Sub application example
// 
if(strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0){
	// Sub application
	define('APP_DIR', __DIR__ . '/../dashboard/');
	define('PUBLIC_URL', '/');
	define('ROOT_URL', '/dashboard/');

	require '../dashboard/initialize.php';
	initialize();
} else {
	// Main application
	define('APP_DIR', __DIR__ . '/../app/');

	require '../app/initialize.php';
	initialize();
}
*/
