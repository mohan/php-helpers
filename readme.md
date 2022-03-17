# PHP Helpers

23 functions for building PHP applications.

License: GPL

Status: Work in progress


## It contains
1. Rewrite URIs
2. Permitted Params
3. Router (to call action functions)
4. Templates
5. URL helpers
6. HTML Tag helpers
7. Markdown
8. Shortcodes
9. Flash messages
10. Secure cookie (Cookie with added authenticity)
11. Config file helpers
12. Debug helpers (helpers-extra.php)


## Note
* **Not tested**, do not use.
* Please feel free to implement it yourself.


## Available functions

```php raw
function filter_rewrite_uri($paths)
function filter_permitted_params($get_param_names, $post_param_names, $cookie_param_names, $get_typecasts, $post_typecasts)
function filter_routes($get_action_names, $post_action_names, $patch_action_names, $delete_action_names)
function redirectto($uri, $args=[])
function get_404($message='')
function render($template_name, $args=[], $layout='layouts/index.php')
function render_partial($template_name, $args=[], $return=false)
function urlto_public_dir($uri)
function urltoget($uri, $args=[], $arg_separator='&')
function urltopost($uri, $args=[], $arg_separator='&')
function formto($uri, $args=[], $attrs=[], $fields=[])
function linkto($uri, $html, $args=[], $attrs=[])
function tag($html, $attrs=[], $name='div', $closing=true, $escape=true)
function tag_table($headers, $data, $attrs=[], $cb=false)
function render_markdown($text, $attrs=[], $enable_shortcodes=false)
function process_shortcodes($text)
function flash_set($html, $in_current_request=false)
function flash_clear()
function filter_set_flash()
function secure_cookie_set($name, $value)
function secure_cookie_get($name)
function cookie_delete($name)
function filter_set_config($filepath)

// Internal utility functions

function _arr_defaults(&$arr, $defaults)
function _str_contains($str, ...$substrs)
```

---

## TODO

- [ ] Code Review
- [ ] Test
- [x] Example application
- [!] URL param names in url
	- Use web server rewrites

