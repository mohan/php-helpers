<?php

require APP_DIR . '/../../lib/helpers.php';
require APP_DIR . '/../../lib/helpers-extra.php';
require APP_DIR . 'template-helpers.php';

function initialize(){
    filter_rewrite_uri([
        "/^\/post\/(?P<id>\d+)$/"               => ['a'=>'post'],
        "/^\/docs\/(?P<path>[a-z0-9-.]+)$/"     => ['a'=>'docs/view'],
        "/^\/docs$/"                            => ['a'=>'docs']
    ]);


    if(!filter_permitted_params(
        // GET params with regex
        [
            'a'             =>  '/^(root|docs|docs\/view|posts|new-post|post|search|src|example_redirect)$/',
            'post_action'   =>  '/^(create-post)$/',
            'id'            =>  '/^\d+$/',
            'path'          =>  '/^(markdown|specification|database-layer|notes|colors|docs)$/',
            'src_path'      =>  '/^(helpers|helpers-extra|test-helpers|utils|partials\/_debugpanel.html|' .
                                'partials\/layout-404.html|partials\/layout-blank.html|partials\/layout-navbar.html|' .
                                'partials\/layout-sidebar.html)\.php$/',
            'title'         =>  1024,
            'body'          =>  1024,
            'query'         =>  1024,
            'raw'           =>  '/^(0|1)$/'
        ],
        // POST params with max_length
        [
            'title' => 1024,
            'body'  => 1024
        ],
        // COOKIE params with max_length
        [
            'flash' => 256
        ],
        // GET typecast
        [
            'id'    => 'int',
            'raw'   => 'bool'
        ]
    )) return get_404('Invalid URL params');


    // Routes
    $response = filter_routes(
        // Get action, with required params from $_GET
        [
            'root'      => 'app/readme.html.php',
            'new-post'  => [],
            'posts'     => [],
            'post'      => ['id'],
            'docs'      => [],
            'docs/view' => ['path'],
            'src'       => ['src_path'],
            'search'    => []
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

function get_search()
{
    extract(_arr_get($_GET, ['query'=>'']));

    $results = false;
    if($query){
        $results = [];
        for ($i=1; $i <= 20; $i++) {
            $title = str_repeat(join("{$query[-1]} ", explode($query[-1], $query)), rand(3, 20));
            $results[] = [
                'title' => "#$i \n" . ucfirst(trim( $i%2 == 0 ? $title : strrev($title) ))
            ];
        }
    }

    return render([
        '_pagetitle' => $query ? "$query - Search" : "Search",
        'query'      => $query,
        'results'    => $results
    ]);
}


function get_new_post()
{
    extract(_arr_get($_POST, ['title'=>'', 'body'=>'']));

    return render([
        '_template'  => 'app/new_post.html.php',
        '_pagetitle' => 'New Post',
        'title'      => $title,
        'body'       => $body
    ]);
}


function get_posts()
{
    extract(_arr_get($_GET, ['title'=>'', 'body'=>'']));

    return render([
        '_pagetitle' => 'Posts',
        'id'         => 1,
        'title'      => $title,
        'body'       => $body
    ]);
}


function get_post()
{
    extract(_arr_get($_GET, ['id'=>'', 'title'=>'', 'body'=>'']));
    
    return render([
        '_template'  => 'app/posts.html.php',
        '_pagetitle' => "Post #$id",
        'id'         => $id,
        'title'      => $title,
        'body'       => $body
    ]);
}


function get_docs()
{
    return render([
        '_layout'   => 'layouts/docs.html.php'
    ]);
}


function get_docs_view()
{
    extract(_arr_get($_GET, ['path'=>false, 'raw'=>false]));

    if($path == 'colors'){
        return render([
            '_layout'       =>  'layouts/docs.html.php',
            '_template'     =>  'app/colors.html.php',
            '_pagetitle'    =>  ucfirst($path)
        ]);
    }

    return render([
        '_layout'       =>  'layouts/docs.html.php',
        '_pagetitle'    =>  ucfirst($path),
        'raw'           =>  $raw,
        'text'          =>  file_get_contents( _path_join(APP_DIR, '/../../docs/', "$path.md") )
    ],
    $path == 'markdown' ? [
        '_template'     =>  'app/src.html.php'
    ] : [
    ]);
}


function get_src()
{
    extract(_arr_get($_GET, ['src_path'=>false]));

    $text = file_get_contents( _path_join(APP_DIR, '/../../lib/', $src_path) );
    
    preg_match_all('/function\s+(?P<name>[^_][a-zA-Z0-9_]*)\s*\((?P<args>[^)]*)\)/', $text, $function_names);
    preg_match_all('/function\s+(?P<name>[_][a-zA-Z0-9_]+)\s*\((?P<args>[^)]*)\)/', $text, $internal_function_names);
    preg_match_all("/defined?\('(?P<name>[A-Z_]+)'/", $text, $defined_constants);

    asort($function_names['name']);
    asort($function_names['args']);
    asort($internal_function_names['name']);
    asort($internal_function_names['args']);

    $function_names = array_combine($function_names['name'], $function_names['args']);
    asort($function_names);
    $internal_function_names = array_combine($internal_function_names['name'], $internal_function_names['args']);
    asort($internal_function_names);

    $defined_constants = array_unique($defined_constants['name']);
    $text = highlight_string($text, true);

    return render([
        '_layout'                   =>  'layouts/docs.html.php',
        'function_names'            =>  $function_names,
        'internal_function_names'   =>  $internal_function_names,
        'defined_constants'         =>  $defined_constants,
        '_pagetitle'                =>  $src_path,
        'text'                      =>  $text,
        'raw'                       =>  false
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
