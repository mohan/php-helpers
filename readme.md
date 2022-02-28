# PHP Helpers

23 functions for building a PHP application.

License: GPL

Status: Work in progress



## It contains

1. Template rendering
2. URL helpers
3. HTML Tag helpers
4. Markdown
5. Shortcodes
6. Flash message
7. Cookie with added authenticity
8. Debug helper
9. Config file helpers
10. Router
11. Permitted Params


## Note
* **Not tested**, do not use.
* Please feel free to implement it yourself.





## Available functions


### Templates

1. render($template_name, $args=[], $layout='layouts/index.php')
	* Renders a PHP template with $args extracted as local variables.
	* Templates are located in `templates/`
	* `$template_name`, `$template_path`, `$uri` ($_GET['uri']), `$args` are additional local variables.

2. render_partial($template_name, $args=[], $return=false)
	* Renders a partial template, for use within a template.
	* All functionality of `render` exists here.
	* `$return` can be used to get HTML instead of rendering directly.


### URL helpers

1. urlto_template_asset($uri)
	* Returns URI to asset in `assets` dir.
	* Location: CONFIG_ROOT_URL . APP_NAME . '/templates/' . APP_TEMPLATE . '/assets/' . $uri

2. urltoget($uri, $args=[], $arg_separator='&')
	* Returns URL to a get request
	* Ex: CONFIG_ROOT_URL?uri=example&arg1=1&arg2=2
	* Set `$args['__hash']` to set hash part of URL

3. urltopost($uri, $args=[], $arg_separator='&')
	* Returns URL to a post request
	* Ex: CONFIG_ROOT_URL?post_uri=example&arg1=1&arg2=2

4. redirectto($uri, $args=[])
	* Send 404 header and exit

5. get_404()
	* Built-in route action for 404 page.
	* /templates/APP_TEMPLATE/404.php


### HTML Tag helpers

1. formto($uri, $args=[], $attrs=[])
	* Returns HTML form open tag with method=post.
	* Arguments are similar to urltopost
	* $attrs are additional form tag attributes

2. linkto($uri, $html, $args=[], $attrs=[])
	* Returns HTML link tag
	* Arguments are similar to urltoget
	* $attrs are additional link tag attributes

3. tag($html, $attrs=[], $name='div', $closing=true)
	* Builds and returns a HTML tag
	* Uses htmlentities for safe user input

4. tag_table($headers, $data, $attrs=[])
	* Builds and returns a HTML table tag
	* Uses htmlentities for safe user input


### Markdown

1. render_markdown($text, $shortcodes=false)
	* Returns markdown as HTML


### Shortcodes

1. process_shortcodes($text)
	* Process shortcodes `[example #1]` through shortcode function.
	* `shortcodes_list()` must return array of available shortcodes.


### Flash messages

1. flash_set($html, $in_current_request=false)
	* Sets a secure cookie with flash message for use in next request.
	* Removed in the next request in `filter_set_flash`
	* `$in_current_request` will make the message available in current request, not next request.
	* Message is available in `$_REQUEST['flash']`

2. flash_clear()
	* Clears flash cookie

3. filter_set_flash()
	* Sets flash message in `$_REQUEST['flash']`, if flash cookie is preset
	* Deletes flash cookie


### Cookie with added authenticity

1. secure_cookie_set($name, $value)
	* Sets a cookie with built-in authenticity token
	* Value format: `$value%$authenticity`
	* Uses md5 hashing (**less secure**)

2. secure_cookie_get($name)
	* Returns the value, if built-in authenticity is valid

3. cookie_delete($name)
	* Deletes a cookie


### Debug helper

1. __d($exit, ...$args)
	* Debug variables; uses print_r.


### Config file helpers

1. filter_set_config($filepath)
	* Sets ini file data in `CONFIG_property_name` constants.


### Router

1. filter_routes($get_action_names, $post_action_names)
	* Finds the current `$_GET['uri']` or `$_GET['post_uri']`
	* Calls `get_$uri` or `get_$post_uri` action functions.
	* If `$_GET['uri']` is empty, without `post_uri`, calls `get_root`
	* If nothing matches, calls built-in `get_404`
	* Checks if specified required params from $_REQUEST are present, if not calls `get_404`


### Permitted Params

1. filter_permitted_params($get_param_names, $post_param_names, $cookie_param_names, $get_typecasts, $post_typecasts)
	* Remove `$_GET` and `$_POST` params that are not in $get_param_names and $post_param_names.
	* `$get_param_names` key value is key: param_name, value is either number (strlen) or regex.
	* `typecasts` change the type of value to specified type.



## Notes

* Use URL rewrite for nice looking URLs, if needed. `/page/1` -> `/?get=page&id=1`.
	* Custom URL helpers will be needed based on your rewrites.


