<?php

// 
// To run tests use build command in your programming editor, to execute this file
// or `php tests.php`
// 

define('APP_DIR', __DIR__ . '/');


// Require test-helpers.php before everything
require '../test-helpers.php';
require './initialize.php';


call_tests_for(
	'get_root',
	'get_new_post',
	'get_posts',
	'get_post',
	'get_docs_view',
	'get_docs',

	'post_create_post'
);


function test_get_root()
{
	$resp = do_get(urltoget('root'));
	
	t('root page renders',  is_not_redirect($resp)
							&& contains($resp,
									'<!-- Markdown start -->',
									"<p>\nStatus: Work in progress\n</p>",
									'<!-- Markdown end -->'
								)
	);
}


function test_get_new_post()
{
	$resp = do_get(urltoget('new-post'));
	
	t('new-post page renders', is_not_redirect($resp)
								&& contains($resp,
										formto('create-post'),
										tag('', ['type'=>'text', 'name'=>'title', 'placeholder'=>'title'], 'input'),
										'</form>'
									)
	);
}


function test_get_posts()
{
	$resp = do_get(urltoget('posts'));
	
	t('posts page renders', is_not_redirect($resp)
					&& contains($resp,
							tag('List of all Posts', [], 'h1'),
							tag('Posts - Example', [], 'title')
						)
	);
}


function test_get_post()
{
	$resp = do_get(urltoget('/post/1'));
	
	t('post page renders', is_not_redirect($resp)
					&& contains($resp,
							tag('List of all Posts', [], 'h1'),
							tag('Post #1 - Example', [], 'title')
						)
	);
}


function test_get_docs_view()
{
	$resp = do_get(urltoget('/docs/helpers'));
	
	t('docs_view page renders', is_not_redirect($resp)
					&& contains($resp, '<!-- Markdown start -->')
	);
}


function test_get_docs()
{
	$resp = do_get(urltoget('/docs'));
	
	t('docs page renders', is_not_redirect($resp)
					&& contains($resp, '<!-- Markdown start -->', 'PHP Helpers')
	);
}


// 
// Post functions
// 
function test_post_create_post()
{
	$args = ['title'=>'example title 1', 'body'=>'...'];
	$resp = do_post(urltopost('create-post'), $args);
	
	t('create-post creates post', is_redirect(urltoget('posts', $args), $resp)
									&& is_flash('Post created!', $resp)
	);
}
