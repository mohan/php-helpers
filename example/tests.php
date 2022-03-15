<?php

// 
// To run tests use build command in your programming editor to execute this file
// or `php tests.php`
// 

// Require test-helpers.php before everything
require '../test-helpers.php';
require './index.php';

call_tests([
	'get_root',
	'get_new_post',
	'get_posts',
	'get_post',

	'post_create_post'
]);


function test_get_root()
{
	$uri = urltoget('root');
	$response = do_get($uri);
	t('root page renders', $response
							&& is_not_redirect($response)
							&& _str_contains($response['body'], '<!-- Markdown start -->'));
}


function test_get_new_post()
{
	$uri = urltoget('new-post');
	$response = do_get($uri);
	t('new-post page renders', $response
								&& is_not_redirect($response)
								&& _str_contains($response['body'], formto('create-post')));
}


function test_get_posts()
{
	$uri = urltoget('posts');
	$response = do_get($uri);
	t('posts page renders', $response
					&& is_not_redirect($response)
					&& _str_contains($response['body'], tag('List of all Posts', [], 'h1'))
					&& _str_contains($response['body'], tag('Posts - Example', [], 'title'))
		);
}


function test_get_post()
{
	$uri = urltoget('post', ['p'=>'post/1']);
	$response = do_get($uri);
	t('post page renders', $response
					&& is_not_redirect($response)
					&& _str_contains($response['body'], tag('List of all Posts', [], 'h1'))
					&& _str_contains($response['body'], tag('Post #1 - Example', [], 'title'))
		);
}


function test_post_create_post()
{
	$uri = urltopost('create-post', []);
	$args = ['title'=>'example title 1', 'body'=>'...'];
	$response = do_post($uri, $args);
	t('create-post creates post', $response
					&& is_redirect(urltoget('posts', $args), $response)
					&& is_flash('Post created!', $response)
		);
}
