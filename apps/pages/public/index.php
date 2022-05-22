<?php

define('APP_DIR', __DIR__ . '/../');

require APP_DIR . '../../lib/helpers.php';
require APP_DIR . '../../lib/helpers-extra.php';

filter_rewrite_uri([
    '/^\/$/'                    =>  [ 'a'=>'page', 'name'=>'index' ],
    '/(?P<name>[a-z0-9\/_-]*)/' =>  [ 'a'=>'page' ]
]);

if(!filter_routes([
    'page'  =>  ['name']
])) get_404();


function get_page()
{
    extract(_arr_get($_GET, ['name']));

    $path = APP_DIR . "data/$name.txt";

    if(strpos($name, '.') !== false || !is_file($path)) return false;

    $text = file_get_contents($path);

    return render([
        '_pagetitle' => ucfirst(basename($name)),
        'text'  =>  $text
    ]);
}




// 
// Internal
// 

function _pagetitle($_pagetitle, $args)
{
    return "$_pagetitle - Pages Example";
}
