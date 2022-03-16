<?php

define('CONFIG_ROOT_URL', '/');
define('CONFIG_SECURE_HASH', '00000000000000000000000000000000');

require APP_DIR . '/../helpers.php';
require APP_DIR . '/../helpers-extra.php';

function initialize(){
	filter_rewrite_uri([
		"/^\/(?P<uri>post)\/(?P<id>\d+)$/",
	]);

	if(!filter_permitted_params(
		// GET params with regex
		[
			'uri'		=> '/^[a-z0-9_-]+$/',
			'post_uri'	=> '/^[a-z0-9_-]+$/',
			'id'		=> '/^\d+$/',
			'path'		=> '/^[a-z0-9-_\.\/]+$/',
			'title'		=> 1024,
			'body'		=> 1024,
			'raw'		=> '/(0|1)/'
		],
		// POST params with max_length
		[
			'title' => 1024,
			'body'	=> 1024
		],
		// COOKIE params with max_length
		[
			'flash' => 256
		],
		// GET typecast
		[
			'id' => 'int',
			'raw'=> 'bool'
		],
		// POST typecast
		[ ]
	)) return get_404('Invalid URL params');

	filter_set_flash();

	// Routes
	$response = filter_routes(
		// Get uri, with required params from $_GET, $_REQUEST
		[
			'root'		=> [[], []],
			'new-post'	=> [[], []],
			'posts'		=> [[], []],
			'post'		=> [['id'], []],
			'markdown'	=> [['path'], []],
			'docs'		=> 'app/docs.html.php'
		],
		// Post uri, with required params from $_GET, $_POST, $_REQUEST
		[
			'create-post' => [[], ['title', 'body'], []]
		],
		// Patch (update) uri, with required params from $_GET, $_POST, $_REQUEST
		[],
		// Delete uri, with required params from $_GET, $_POST, $_REQUEST
		[]
	);

	if($response === false) return get_404('Invalid URL');
	return $response;
}




//
// Actions (if needed, place in APP_NAME/actions.php)
//

function get_root()
{
	return render('app/readme.html.php', [
		'_pagetitle'=>'Readme'
	]);
}


function get_new_post()
{
	extract(_arr_defaults($_POST, ['title'=>'', 'body'=>'']));

	return render('app/new_post.html.php', [
		'_pagetitle'=>'New Post',
		'title'=>$title,
		'body'=>$body
	]);
}


function get_posts()
{
	extract(_arr_defaults($_GET, ['title'=>'', 'body'=>'']));

	return render('app/posts.html.php', [
		'_pagetitle'=>'Posts',
		'id'=>1,
		'title'=>$title,
		'body'=>$body
	]);
}


function get_post()
{
	extract(_arr_defaults($_GET, ['id'=>'', 'title'=>'', 'body'=>'']));
	
	return render('app/posts.html.php', [
		'_pagetitle'=>"Post #$id",
		'id'=>$id,
		'title'=>$title,
		'body'=>$body
	]);
}


function get_markdown()
{
	extract(_arr_defaults($_GET, ['path'=>false, 'raw'=>false]));
	if(array_search($path, ['readme.md', 'docs/markdown.md', 'docs/helpers.md']) === false) return false;

	return render('app/markdown.html.php', [
		'_pagetitle'=>$path,
		'raw'=>$raw,
		'text' => file_get_contents(APP_DIR . '/../' . $path)
	]);
}


function post_create_post()
{
	extract(_arr_defaults($_POST, ['title'=>'', 'body'=>'']));

	if($title && $body){
		flash_set('Post created!');
		return redirectto('posts', ['title' => $title, 'body' => $body]);
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
