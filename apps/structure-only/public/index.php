<?php

// This does not use php-helpers library
// Provides basic structure without using any libraries

if(!isset($_GET['a'])) $_GET['a'] = NULL;

if(isset($_GET['post_action'])){
    switch($_GET['post_action']){
        case 'login':
            post_password();
            break;
        case 'secure-page1':
            get_page('secure-page1');
            break;
        case 'secure-page2':
            get_page('secure-page2');
            break;
    }
} else {
    switch($_GET['a']){
        case NULL:
        case 'root':
            get_root();
            break;
        case 'page1':
            get_page('page1');
            break;
        case 'page2':
            get_page('page2');
            break;
        case 'page3':
            get_page('page3');
            break;
        case 'login':
            get_login();
            break;
        default:
            get_404();
            break;
    }
}


// 
// Action functions
// 

function get_root()
{
    $action_name = 'root';
    include '../templates/layouts/app.html.php';
}


function get_page($action_name)
{
    include '../templates/layouts/app.html.php';
}


function get_login()
{
    $action_name = 'login';
    include '../templates/layouts/app.html.php';
}


function get_404()
{
    include '../templates/layouts/404.html.php';
}


function post_password()
{
    $action_name = $_GET['p'];

    if(
        md5($_POST['password']) == '202cb962ac59075b964b07152d234b70' &&
        array_search($action_name, ['secure-page1', 'secure-page2']) !== false
    ){
        include '../templates/layouts/app.html.php';
    } else {
        header("Location: /?" . http_build_query(['a'=>'login', 'p'=>$action_name]));
    }
}
