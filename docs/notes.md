# Notes

## Tips for php.ini 

### Include path

Instead of copying `php-helpers`, set path to `php-helpers` parent directory using `set_include_path`.
This can be done in `php.ini` as well. `include_path=".;c:\php\includes"`

```php raw
$path = '/a/b/c/my-lib';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

// include without the whole path
include 'php-helpers/lib/helpers.php';
```

### Restrict PHP functions 

`disable_functions` directive in `php.ini` can be used to disable internal functions.

```php raw
disable_functions = eval,mail,sendmail,symlink,exec,passthru,shell_exec,system,proc_open,
popen,curl_exec,curl_multi_exec,parse_ini_file,show_source,fopen,file_put_contents,php_info
```

### Other directive modifications

These are some additional customizations to `php.ini`. `php.ini` contains comments about each of these.

```table
directive	|	value
max_input_time	|	3
max_execution_time	|	3
memory_limit	|	64M
error_reporting	|	E_ALL
display_errors	|	off in production
error_prepend_string	|	"""<pre style='background:#ccc;padding:20px;border-radius:3px;word-break:break-word;font-size:0.95em;'>"""
error_append_string	|	"""</pre>"""
post_max_size	|	1M
file_uploads	|	On
allow_url_fopen	|	Off
```


## Offline PHP Documentation

Download documentation from PHP website and extract it to a location.
Save the below file with filename `php-docs` to path and `chmod +x`.
Run `php-docs` to start documentation.

```dark
php -S 127.0.0.1:9000 -t path-to-docs/php-chunked-xhtml
```

Files can be opened directly in the browser, without the web server. `chm` format is also available.


## PHP built-in web server (for development environment only)

```dark
php -S 127.0.0.1:8000
```

Options:
-S <addr>:<port> Run with built-in web server.
-t <docroot>     Specify document root <docroot> for built-in web server.


## PHP in command line

```php
// Run single lines
php -r "echo 123;"

// Run REPL (Read Eval Print Loop) console
php -a

// Custom php-ini file
php -c /a/b/c/php.ini
```
