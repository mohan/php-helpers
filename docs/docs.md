# helpers.php

## Function _php_helpers_init

Sets the below values
```table
Name | Value
$_REQUEST['CURRENT_METHOD'] | get/post/patch/delete
$_REQUEST['CURRENT_ACTION'] | Name of current filtered action
$_REQUEST['LAYOUT'] | current layout - "templates/layouts/APP_NAME.html.php"
$_REQUEST['flash'] | flash message
```

Sets constants, if not defined
```table
Name | Value
SECURE_HASH | Based on file name and other constants. Manually set if you need more random string.
APP_DIR | "."
APP_NAME | "app"
ROOT_URL | "/"
TEMPLATES_DIR | "./templates"
```

This internal function is called at the end of this file. It is not needed to call manually.
If you modified any REQUEST values, call this to reset above vars.



## Function filter_rewrite_uri

Rewrite current $_SERVER['REQUEST_URI'] or PATH_INFO into $_GET variables, based on rewrite rules.
It ignores ROOT_URL.

Regular expression can contain capture groups with names, which will also be added to $_GET.

Example:
```table
URI | Rewrites to
/post/1 | /?a=post&id=1
/docs/example | /?a=docs/view&path=example
/docs/ | /?a=docs
```

```php raw
filter_rewrite_uri([
	"/^\/post\/(?P<id>\d+)$/" 			=> ['a'=>'post'],
	"/^\/docs\/(?P<path>[a-z0-9-]+)$/" 	=> ['a'=>'docs/view'],
	"/^\/docs$/" 						=> ['a'=>'docs']
]);
```



## Function filter_permitted_params

Permitted GET, POST, cookie params, with strlen/regex check and typecasting

Example:
```php raw
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
```


## Function router

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



## Function render

Renders a template from TEMPLATES_DIR. Accepts 1 or more arrays of arguments to render.

Default template is $_REQUEST['TEMPLATE'] -> templates/app/actionid.html.php
Default layout is $_REQUEST['LAYOUT'] -> templates/layouts/app.html.php

Example:
```php raw
return render([
	'id'=>1,
	'title'=>$title,
	'body'=>$body
]);
```

Default template can be overriden by setting
'_template' => 'app/article-style-2.html.php',
and layout by setting
'_layout' => 'layouts/article.html.php'.


## Function render_partial

Same as render, but renders a partial from templates directory.
Can be used to render a partial template from within a layout/template.

Example:
```php raw
<?= render('partials/sidebar.html.php') ?>
```

## Function redirectto

Redirects to an action.

Example:
```php raw
redirectto('article', ['id'=>1]);
```

## Function get_404

Built-in 404 error action.
Define CUSTOM_GET_404 to replace it with your own action.



## Function urlto_public_dir

URL to an css/image/other asset in public directory.

Uses ROOT_URL as the URL prefix.
Define PUBLIC_URL, to override.

Example:
```php raw
urlto_public_dir('assets/style.css');
```

## Function urltoget

Returns URL to a GET action.


## Function urltopost

Returns URL to a post action.


## Function formto

Returns HTML for a form with fields.


## Function form_field

Returns HTML for a form field.


## Function linkto

Returns HTML for a link to a GET action.


## Function tag

Returns HTML for a tag.

It auto htmlentities for safe user input.


## Function tag_table

Returns HTML for a table, for given headers and data.


## Function flash_set

Sets flash cookie flash message.
For post requests, or flash in current request, set $in_current_request to true.


## Function flash_clear

Clears flash message.


## Function _filter_set_flash

Sets $_REQUEST['flash'] message set from previous request, and deletes the cookie.

