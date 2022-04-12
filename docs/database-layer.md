# Database Layer

Database helper functions don't exist in `php-helpers`, as a database is not needed for all applications. Here are a few tips for writing your own SQL functions, similar to an ORM (Object relational mapper).

* Use PHP PDO (PHP Data Objects) for multiple database support, if needed.
* Write `setup` function for creating schema.
* Write `select one`, `select all`, `insert/update/delete` functions.
* Additionally `find_or_insert` or other similar functions may be written.
* Make a key/value array of SQL queries, and use with one of the three above functions.
* Implement SQL value escape, similar to `printf`. *This is important as user input must not be trusted.*
* Benefit from making persistent connections to database servers. Connect/disconnect will slow your requests.
* Cache query results, if needed for performance.
* If database query cache is available, use timestamp field to fetch updated results.

## Example

```php raw
// data.php

define('DATABASE_CONNECTION', new mysqli("localhost", "user", "password", "database"));

fuction sql_first($query)
{
	// Return associative array of row
	// Write log (trigger_error/error_log ...)
}

fuction sql_fetch($query)
{
	// Return array of rows
	// Write log (trigger_error/error_log ...)
}

fuction sql_write($query)
{
	// Return associative array of result
	// Write log (trigger_error/error_log ...)
}

function sql_run($query_key, $params, $action='fetch')
{
	$queries = [
		"get_user"	=>	"select %s from users where %s limit 1;",
		"new_user"	=>	"insert into users (%s) values(%s);"
	];

	// Escape both keys and values
	$q = printf($queries[$query_key], array_map('mysqli_real_escape_string', $params)...);
	...

	switch($action){
		case 'fetch':
			$results = call_user_func('sql_fetch', $q);
			// cache results by query_key, if needed
			return $results;
		...
	}
}

function sql_setup()
{
	// CREATE TABLE IF NOT EXISTS users(name varchar(128));
}
```
