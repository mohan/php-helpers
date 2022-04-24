# PHP Helpers

23 functions for building PHP applications.

SLOC: ~500

License: GPL

Status: Work in progress


1. Rewrite URI
	* Function to rewrite URI paths to $_GET arguments, for fancy URLs.
2. Permitted Params
	* Function to allow only permitted $_GET parameters and regex matching values for the whole application.
3. Router (to call action functions)
	* $_GET['action'] regular string paramater to function name mapping
		* articles		->	$_GET['action'] = 'articles';						->	function get_articles()
		* article/view	->	$_GET['action'] = 'article/view'; $_GET['id'] = 1;	->	function get_article_view()
		* article		->	$_GET['post_action'] = 'article';					->	function post_article()
4. Templates
	* Functions to echo layouts and templates.
5. URL helpers
	* Functions to echo URLs to actions.
6. HTML Tag helpers
	* Functions to echo HTML tags like `linkto`, with HTML escaping.
7. Flash messages
	* Function to display action success/error messages in next request.
8. md5 cookie (Cookie with added authenticity)
	* Cookie with extra md5 hash, to prevent modification by client.
9. Config file helpers
	* Function to read ini file to constants.
10. Debug helpers
	* Alias functions to `print_r` / `var_dump`.
11. Markdown
	* Function to display markdown text format as HTML.
12. Shortcodes
	* Function to interpret and display special markdown tags with arguments to HTML.


## Note
* **Not tested**, do not use.


## Example structure for a new project

```php raw
mkdir APPLICATION-NAME
cd APPLICATION-NAME

mkdir -p public/assets templates/app templates/layouts templates/partials
touch public/index.php public/assets/style.css app.php template-helpers.php templates/layouts/app.html.php templates/layouts/404.html.php
```

### Templates based application/website
```php raw
mkdir APPLICATION-NAME
cd APPLICATION-NAME

mkdir -p public/assets templates/app templates/layouts templates/partials
touch public/index.php public/assets/style.css templates/layouts/app.html.php templates/layouts/404.html.php
```

* For API style application, refer to `apps/example/public/api.php`.
* For background jobs, refer to `apps/example/cli.php`.

---

## TODO

- [ ] Code Review
- [ ] Test
- [ ] More layouts
- [ ] Redirect shortcut for action name
- [x] Example application
- [x] URL param names in url
	- Use web server rewrites
