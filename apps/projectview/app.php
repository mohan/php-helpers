<?php

require APP_DIR . '../../lib/helpers.php';
require APP_DIR . '../../lib/utils.php';
require APP_DIR . 'template-helpers.php';

function initialize()
{
	if($_SERVER['REMOTE_ADDR'] != '127.0.0.1') return get_404();

	if(!filter_permitted_params([
		'a'		=>	"/^(root|splash|files|viewer)$/",
		'file'	=>	"/^" . preg_quote(__DIR__, '/') . "/",
		'dir'	=>	"/^" . preg_quote(__DIR__, '/') . "/"
	])) return get_404();

	$response = filter_routes(
		[
			'root'		=>	[],
			'splash'	=>	[],
			'files'		=>	['dir'],
			'viewer'	=>	['file']
		]
	);

	if($response) return $response;
	return get_404();
}


function get_root()
{
	return render();
}


function get_splash()
{
	return render([
		'_layout'	=>	'layouts/blank.html.php'
	]);
}


function get_files()
{
	extract(_arr_get($_GET, ['dir'=>APP_DIR]));
	$dir = realpath($dir ? $dir : APP_DIR);

	return render([
		'_layout' => 'layouts/blank.html.php',
		'dir'	=>	$dir,
		'files'	=> _nested_files_list($dir)
	]);
}


function get_viewer()
{
	extract(_arr_get($_GET, ['file'=>'']));

	return render([
		'_layout' => 'layouts/blank.html.php',
		'file'	=>	$file,
		'text'	=>	file_get_contents($file)
	]);
}
