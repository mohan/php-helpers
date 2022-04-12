# PHP Helpers

23 functions for building PHP applications.

License: GPL

Status: Work in progress


## It contains
1. Rewrite URI
2. Permitted Params
3. Router (to call action functions)
4. Templates
5. URL helpers
6. HTML Tag helpers
7. Flash messages
8. md5 cookie (Cookie with added authenticity)
9. Config file helpers
10. Debug helpers
11. Markdown
12. Shortcodes


## Note
* **Not tested**, do not use.
* Please feel free to implement it yourself.


## Available functions

```php raw
function filter_rewrite_uri($paths)
function filter_permitted_params($get_param_names, $post_param_names=[], $cookie_param_names=[], $get_typecasts=[], $post_typecasts=[])
function filter_routes($get_action_names, $post_action_names=[], $patch_action_names=[], $delete_action_names=[])
function render($args=[], $template_path=true)
function render_partial($template_path, $args=[], $return=false)
function redirectto($action, $args=[])
function get_404($message='')
function urlto_public_dir($uri)
function urltoget($action, $args=[], $arg_separator='&', $skip_action_arg=false)
function urltopost($action, $args=[], $arg_separator='&')
function formto($action, $args=[], $attrs=[], $fields=[])
function form_field($form_id, $field_name, $field_options)
function linkto($action, $html, $args=[], $attrs=[])
function tag($html, $attrs=[], $name='div', $closing=true, $escape=true)
function tag_table($headers, $data, $attrs=[], $cb=false)
function flash_set($html, $in_current_request=false)
function flash_clear()
function md5_cookie_set($name, $value)
function md5_cookie_get($name)
function cookie_delete($name)

// Internal functions

function _to_id($str, $replace_with='-')
function _path_join(...$parts)
function _arr_get($arr, $keys, $prefix='')
function _arr_defaults(&$arr, $defaults)
function _arr_typecast(&$input, $typecast_def_arr)
function _arr_validate(&$input, $validations, $must_contain_all_keys=true)
function _str_contains($str, ...$substrs)
```

---

## TODO

- [ ] Code Review
- [ ] Test
- [ ] More layouts
- [x] Example application
- [x] URL param names in url
	- Use web server rewrites

