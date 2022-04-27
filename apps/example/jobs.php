<?php
// 
// Example of a background jobs application. (Ex: sending email, cascading delete)
// 
// 
// Usage: php jobs.php [args]
// Args will be available in $_GET
//

define('CUSTOM_GET_404', true);

// See PHP getopt for more options
// $argc / $argv may also be used, but will not filter for required params in filter_routes
// a = action name
$_GET = getopt('a:', ['id:']);

require '../../lib/helpers.php';


function initialize(){
	// Routes
	$response = filter_routes(
		[
			'root'		=> [],
			'posts'		=> [],
			'post'		=> ['id']
		]
	);

	echo $response === false ? get_404('Route not found.') : $response;
}
initialize();


// php cli.php
function get_root()
{
	return "Hello from CLI";
}


// php cli.php -a posts
function get_posts()
{
	return "List of all Posts.";
}


// php cli.php -a post --id 1
function get_post()
{
	return "Post #" . $_GET['id'];
}



// 
// Internal
// 

// php cli.php -a 404
function get_404($message='')
{
	return $message; 
}

?>

