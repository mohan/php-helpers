<?php

define('APP_DIR', __DIR__ . '/../');

// require '../app.php';
// initialize();



// 
// Sub application example
// 
if(strpos($_SERVER['REQUEST_URI'], '/dashboard') === 0){
	// Sub application
	define('PUBLIC_URL', '/');
	define('ROOT_URL', '/dashboard/');
	define('APP_NAME', 'dashboard');

	require '../dashboard.php';
} else {
	// Main application
	require '../app.php';
}

initialize();
