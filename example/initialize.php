<?php

require APP_DIR . '../helpers.php';
require APP_DIR . '../helpers-extra.php';

function initialize(){
	if(!filter_permitted_params(
		// GET params with regex
		[
			'a'				=> '/^(root|docs|posts|new-post|post)$/',
			'post_action'	=> '/^(create-post)$/',
			'id'			=> '/^\d+$/',
			'path'			=> '/^(helpers|markdown)$/',
			'title'			=> 1024,
			'body'			=> 1024,
			'raw'			=> '/^(0|1)$/'
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
		]
	)) return get_404('Invalid URL params');


	filter_rewrite_uri([
		"/^\/post\/(?P<id>\d+)$/" 			=> ['a'=>'post'],
		"/^\/docs\/(?P<path>[a-z0-9]+)$/" 	=> ['a'=>'docs/view'],
		"/^\/docs$/" 						=> ['a'=>'docs']
	]);


	// Routes
	$response = filter_routes(
		// Get action, with required params from $_GET
		[
			'root'		=> 'app/readme.html.php',
			'new-post'	=> [],
			'posts'		=> [],
			'post'		=> ['id'],
			'docs/view'	=> ['path'],
			'docs'		=> 'app/docs.html.php'
		],
		// Post action, with required params from $_GET, $_POST
		[
			'create-post' => [[], ['title', 'body']]
		]
	);

	if($response === false) return get_404('Invalid URL');
	return $response;
}




//
// Actions (if needed, place in APP_NAME/actions.php)
//


function get_new_post()
{
	extract(_arr_get($_POST, ['title'=>'', 'body'=>'']));

	return render([
		'_pagetitle'=>'New Post',
		'title'=>$title,
		'body'=>$body
	], 'app/new_post.html.php');
}


function get_posts()
{
	extract(_arr_get($_GET, ['title'=>'', 'body'=>'']));

	return render([
		'_pagetitle'=>'Posts',
		'id'=>1,
		'title'=>$title,
		'body'=>$body
	]);
}


function get_post()
{
	extract(_arr_get($_GET, ['id'=>'', 'title'=>'', 'body'=>'']));
	
	return render([
		'_pagetitle'=>"Post #$id",
		'id'=>$id,
		'title'=>$title,
		'body'=>$body
	], 'app/posts.html.php');
}


function get_docs_view()
{
	extract(_arr_get($_GET, ['path'=>false, 'raw'=>false]));

	return render([
		'_pagetitle'=>$path,
		'raw'=>$raw,
		'text' => file_get_contents(APP_DIR . '/../docs/' . $path . '.md')
	]);
}


function post_create_post()
{
	extract(_arr_get($_POST, ['title'=>false, 'body'=>false]));

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
