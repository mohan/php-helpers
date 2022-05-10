<?php

define('APP_DIR', __DIR__ . '/../');

require APP_DIR . '../../lib/helpers.php';

if(!filter_routes([
    'root'      =>  'app/root.html.php',
    'hello'     =>  'app/hello.html.php',
    'about'     =>  'app/about.html.php',
    'contact'   =>  'app/contact.html.php',
])) get_404();






// 
// Internal
// 

function _pagetitle($_pagetitle, $args)
{
    switch ($_REQUEST['ACTION_ID']) {
        case 'root':
            return 'Website Example';
        
        default:
            $out = ucfirst($_REQUEST['ACTION_ID']);
            break;
    }

    return "$out Website Example";
}
