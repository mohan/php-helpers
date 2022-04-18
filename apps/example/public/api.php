<?php

define('CUSTOM_GET_404', true);

require '../../../lib/helpers.php';


_arr_defaults($_GET, ['format'=>'xml']);
_header("Content-type: application/{$_GET['format']}");

switch ($_GET['format']) {
	case 'php':
		// Yahoo style
		echo serialize(initialize());
		break;
	
	case 'json':
		// Modern style
		echo json_encode(initialize());
		break;

	case 'xml':
		// Classic style
		$data = initialize();

		$xw = xmlwriter_open_memory();
		xmlwriter_set_indent($xw, 1);
		xmlwriter_set_indent_string($xw, "\t");
		xmlwriter_start_document($xw, '1.0', 'UTF-8');
		xmlwriter_start_element($xw, 'data');
			array_walk_recursive($data, function($item, $key) use($xw){
				xmlwriter_start_element($xw, $key);
				xmlwriter_text($xw, $item);
				xmlwriter_end_element($xw);
			});
		xmlwriter_end_element($xw);
		xmlwriter_end_document($xw);
		echo xmlwriter_output_memory($xw);
		break;
}



function initialize(){
	// Routes
	$response = filter_routes(
		// Get action, with required params from $_GET
		[
			'root'		=> [],
			'posts'		=> [],
			'post'		=> ['id']
		],
		// Post action, with required params from $_GET, $_POST
		[
			'create-post' => [[], ['title']]
		]
	);

	if($response === false) return get_404('Invalid URL');
	return $response;
}



// curl http://127.0.0.1:8080/api.php
function get_root()
{
	return ['root'=>true];
}


// curl http://127.0.0.1:8080/api.php?a=posts
function get_posts()
{
	return [['title'=>'First'], ['title'=>'Second']];
}


// curl 'http://127.0.0.1:8080/api.php?a=post&id=1'
function get_post()
{
	return ['id'=> $_GET['id'], 'title'=>'First'];
}


// curl --data 'title=Post1' http://127.0.0.1:8080/api.php?post_action=create-post
function post_create_post()
{
	return ['success'=>true, 'title'=>$_POST['title']];
}



// 
// Internal
// 

// curl http://127.0.0.1:8080/api.php?a=404
function get_404($message='')
{
	_header("HTTP/1.1 404 Not Found");
	return ['error'=>404, 'message'=>$message];
}


?>

