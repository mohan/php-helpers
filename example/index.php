<?php

require_once '../helpers.php';

define('CONFIG_ROOT_URL', '/');

initialize();

function initialize(){
	if(!filter_permitted_params(
		// GET params with regex
		[
			'uri' => '/^[a-z0-9_-]+$/',
			'post_uri' => '/^[a-z0-9_-]+$/',
			'id' => '/^\d+$/',
			'title' => 1024,
		],
		// POST params with max_length
		[
			'title' => 1024
		],
		// COOKIE params with max_length
		[
			'auth_flash' => 256
		],
		// GET typecast
		[
			'id' => 'int',
		],
		// POST typecast
		[ ]
	)) return get_404('Invalid URL params');

	filter_set_flash();

	// Routes
	if(!filter_routes(
		// Get uri, with required params from $_REQUEST
		[
			'new-post'	=> [],
			'posts'		=> [],
			'post'		=> ['id']
		],
		// Post uri, with required params from $_REQUEST
		[
			'create-post' => ['title', 'body']
		],
		// Patch (update)
		[
		],
		// Delete
		[
		]
	)) return get_404('Invalid URL');
}




//
// Actions (if needed, place in APP_NAME/actions.php)
//

function get_root()
{
	return render('hello.php');
}

function get_new_post()
{
	return render('new_post.php');
}

function get_posts()
{
	return render('posts.php', ['title'=>$_GET['title']]);
}

function get_post()
{
	return render('posts.php', ['id'=>$_GET['id']]);
}

function post_create_post()
{
	flash_set('Post created!');
	return redirectto('posts', ['title' => $_POST['title']]);
}
