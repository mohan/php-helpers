# helpers.php

## _php_helpers_init

Sets vars
* $_REQUEST['CURRENT_METHOD'] - GET/POST/PATCH/DELETE
* $_REQUEST['CURRENT_ACTION'] - Name of current filtered action
* $_REQUEST['LAYOUT'] - current layout - "templates/layouts/APP_NAME.html.php"
* $_REQUEST['flash'] - flash message

Sets constants, if not defined
* SECURE_HASH - Based on file name and other constants. Manually set if you need more random string.
* APP_DIR - "."
* APP_NAME - "app"
* ROOT_URL - "/"
* TEMPLATES_DIR - "./templates"

This internal function is called at the end of this file. No need to call it manually.
If you modified any REQUEST values, call this to reset above vars.



## filter_rewrite_uri

Rewrite current $_SERVER['REQUEST_URI'] or PATH_INFO into $_GET variables, based on rewrite rules.
It ignores ROOT_URL.

Regular expression can contain capture groups with names, which will also be added to $_GET.

Example:
filter_rewrite_uri([
	"/^\/post\/(?P<id>\d+)$/" 			=> ['a'=>'post'],
	"/^\/docs\/(?P<path>[a-z0-9-]+)$/" 	=> ['a'=>'docs/view'],
	"/^\/docs$/" 						=> ['a'=>'docs']
]);

Translates to $_GET represented as below:
/post/1			->	/?a=post&id=1
/docs/example	->	/?a=docs/view&path=example
/docs/			->	/?a=docs



## filter_permitted_params

Permitted GET, POST, cookie params, with strlen/regex check and typecasting

Example:
filter_permitted_params(
	// GET params with maxlen/regex
	[
		'a'				=> '/^(root|docs|docs\/view|posts|new-post|post|search)$/',
		'post_action'	=> '/^(create-post)$/',
		'id'			=> '/^\d+$/'
	],
	// POST params with maxlen/regex
	[
		'title' => 1024,
		'body'	=> 1024
	],
	// COOKIE params with maxlen/regex
	[
		'flash' => 256
	],
	// GET typecast
	[
		'id' => 'int',
		'number' => 'float',
		'raw'=> 'bool'
	],
	// POST typecast
	[
	]
)



## router

Map action names to functions and call current action function.
Action name function must be named METHODNAME_ACTIONNAME without special characters, ex: function get_list()

Action functions may use extract method to make $_GET/$_POST vars as local vars.
Ex: extract(_arr_get($_GET, ['title'=>'', 'body'=>''])); -> $title, $body local vars similar to function arguments.

Sets vars:
$_REQUEST['ACTION_ID'] = $action_id; // underscored action name
$_REQUEST['TEMPLATE'] = APP_NAME . '/' . $action_id . '.html.php';

Notes:
* GET action name is represented by $_GET['a'] param. "a" stands for action (similar to form action.)
	* Example: /?a=page, /?a=articles
	* This makes URLs to be shorter without needing to rewrite.
	* It also confirms to default PHP spec - $_GET, $_POST.
* HTTP spec contains GET, POST, PATCH (update) and DELETE HTTP methods. Browsers usually only implement GET and POST.
	* PATCH and DELETE are implemente by POST. URL action name is patch_action and delete_action respectively.
* Action function names must be - get_actionname, post_actionname, patch_actionname, delete_actionname.
* Generally only GET requests should render HTML. Post requests should redirect to GET URL.
	* Browsers ask for confirmation on post page refresh, to prevent form re-submission.
* If post form contains errors or similar, post request may render.
	* This is useful for form validations.
* Action functions don't accept any arguments. This allows calling other action functions from within an action function.
	* For example, post form validation fail, call get form with errors by calling get form action function.



## render

Renders a template from TEMPLATES_DIR. Accepts 1 or more arrays of arguments to render.

Default template is $_REQUEST['TEMPLATE'] -> templates/app/actionid.html.php
Default layout is $_REQUEST['LAYOUT'] -> templates/layouts/app.html.php

Example:
return render([
	'id'=>1,
	'title'=>$title,
	'body'=>$body
]);

Default template can be overriden by setting
'_template' => 'app/article-style-2.html.php',
and layout by setting
'_layout' => 'layouts/article.html.php'.


## render_partial

Same as render, but renders a partial from templates directory.
Can be used to render a partial template from within a layout/template.

Example:
<?= render('partials/sidebar.html.php') ?>


## redirectto

Redirects to an action.

Example:
redirectto('article', ['id'=>1]);


## get_404

Built-in 404 error action.
Define CUSTOM_GET_404 to replace it with your own action.



## urlto_public_dir

URL to an css/image/other asset in public directory.

Uses ROOT_URL as the URL prefix.
Define PUBLIC_URL, to override.

Example:
urlto_public_dir('assets/style.css');


## urltoget

Returns URL to a GET action.


## urltopost

Returns URL to a post action.


## formto

Returns HTML for a form with fields.


## form_field

Returns HTML for a form field.


## linkto

Returns HTML for a link to a GET action.


## tag

Returns HTML for a tag.

It auto htmlentities for safe user input.


## tag_table

Returns HTML for a table, for given headers and data.


## flash_set

Sets flash cookie flash message.
For post requests, or flash in current request, set $in_current_request to true.


## flash_clear

Clears flash message.


## _filter_set_flash

Sets $_REQUEST['flash'] message set from previous request, and deletes the cookie.

