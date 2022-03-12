<?php

require_once '../helpers.php';
require_once '../helpers-extra.php';

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
			'title' => 1024,
			'body'	=> 1024
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
			'root'		=> [[], []],
			'new-post'	=> [[], []],
			'posts'		=> [[], []],
			'post'		=> [['id'], []]
		],
		// Post uri, with required params from $_REQUEST
		[
			'create-post' => [[], ['title', 'body'], []]
		],
		// Patch (update)
		[],
		// Delete
		[]
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
	_arr_defaults($_GET, ['title'=>'', 'body'=>'']);

	return render('posts.php', ['id'=>'', 'title'=>$_GET['title'], 'body'=>$_GET['body']]);
}

function get_post()
{
	_arr_defaults($_GET, ['title'=>'', 'body'=>'']);
	
	return render('posts.php', ['id'=>$_GET['id'], 'title'=>$_GET['title'], 'body'=>$_GET['body']]);
}

function post_create_post()
{
	flash_set('Post created!');
	return redirectto('posts', ['title' => $_POST['title'], 'body' => $_POST['body']]);
}


// 
// Internal
// 
function _shortcodes_list()
{
	return ['random-number'];
}

function shortcode_random_number($args)
{
	return rand();
}
