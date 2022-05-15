<?php

require APP_DIR . '/../../lib/helpers.php';
require APP_DIR . 'template-helpers.php';

function initialize(){
    // Routes
    $response = filter_routes(
        // Get action, with required params from $_GET
        [
            'root'      => [],
            'posts'     => [],
        ]
    );

    if($response === false) return get_404('Invalid URL');
    return $response;
}


function get_root()
{
    return render();
}

function get_posts()
{
    return render();
}
