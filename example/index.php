<?php

require_once '../helpers.php';
require_once '../helpers-extra.php';

define('CONFIG_ROOT_URL', '/');
// Secure hash of 32 characters
// /usr/bin/php -r "echo md5(rand()*rand());"
define('CONFIG_SECURE_HASH', '00000000000000000000000000000000');

initialize();

function initialize(){
	// Directly render a template, without action function
	$page = isset($_GET['uri']) && $_GET['uri'] == 'markdown' ? 'markdown' : false;
	if($page){
		return render("app/$page.php", ['_pagetitle'=>ucfirst($page)]);
	}

	filter_rewrite_uri([
		"/^\/(?P<uri>post)\/(?P<id>\d+)$/"
	]);

	if(!filter_permitted_params(
		// GET params with regex
		[
			'uri'		=> '/^[a-z0-9_-]+$/',
			'post_uri'	=> '/^[a-z0-9_-]+$/',
			'id'		=> '/^\d+$/',
			'title'		=> 1024,
			'body'		=> 1024
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
		// Get uri, with required params from $_GET, $_REQUEST
		[
			'root'		=> [[], []],
			'new-post'	=> [[], []],
			'posts'		=> [[], []],
			'post'		=> [['id'], []],
			'markdown'	=> [[], []]
		],
		// Post uri, with required params from $_GET, $_POST, $_REQUEST
		[
			'create-post' => [[], ['title', 'body'], []]
		],
		// Patch (update) uri, with required params from $_GET, $_POST, $_REQUEST
		[],
		// Delete uri, with required params from $_GET, $_POST, $_REQUEST
		[]
	)) return get_404('Invalid URL');
}




//
// Actions (if needed, place in APP_NAME/actions.php)
//

function get_root()
{
	return render('app/readme.php', ['_pagetitle'=>'Readme']);
}

function get_new_post()
{
	_arr_defaults($_POST, ['title'=>'', 'body'=>'']);

	return render('app/new_post.php', ['_pagetitle'=>'New Post', 'title'=>$_POST['title'], 'body'=>$_POST['body']]);
}

function get_posts()
{
	_arr_defaults($_GET, ['title'=>'', 'body'=>'']);

	return render('app/posts.php', ['_pagetitle'=>'Posts', 'id'=>1, 'title'=>$_GET['title'], 'body'=>$_GET['body']]);
}

function get_post()
{
	_arr_defaults($_GET, ['title'=>'', 'body'=>'']);
	
	return render('app/posts.php', ['_pagetitle'=>'Post', 'id'=>$_GET['id'], 'title'=>$_GET['title'], 'body'=>$_GET['body']]);
}

function post_create_post()
{
	if($_POST['title'] && $_POST['body']){
		flash_set('Post created!');
		return redirectto('posts', ['title' => $_POST['title'], 'body' => $_POST['body']]);
	} else {
		flash_set('Invalid fields!', true);
		return get_new_post();
	}
}


// 
// Internal
// 
function _shortcodes_list()
{
	return ['timestamp'];
}

function shortcode_timestamp($args)
{
	return time();
}
