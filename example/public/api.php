<?php

define('CUSTOM_GET_404', true);

require '../../helpers.php';


function initialize(){
	_header("Content-type: application/json");

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

echo initialize();


// curl http://127.0.0.1:8080/api.php
function get_root()
{
	return json_encode(['root'=>true]);
}


// curl http://127.0.0.1:8080/api.php?a=posts
function get_posts()
{
	return json_encode([['title'=>'First'], ['title'=>'Second']]);
}


// curl 'http://127.0.0.1:8080/api.php?a=post&id=1'
function get_post()
{
	return json_encode(['id'=> $_GET['id'], 'title'=>'First']);
}


// curl --data 'title=Post1' http://127.0.0.1:8080/api.php?post_action=create-post
function post_create_post()
{
	return json_encode(['success'=>true, 'title'=>$_POST['title']]);
}



// 
// Internal
// 

// curl http://127.0.0.1:8080/api.php?a=404
function get_404($message='')
{
	_header("HTTP/1.1 404 Not Found");
	return json_encode(['error'=>404, 'message'=>$message]);
}


?>

