# Why PHP

1. Open Source domain specific language for **web programming**.
	- HTML/CSS is an easy and good user interface alternative.
	- `php -S localhost:8000` is available for desktop applications.
2. PHP syntax is similar to C.
3. Functional programming, for simplicity.
4. Easy to learn and remember. Only required learning -
	- Constants
	- Variables
	- Arrays
	- Associative arrays
	- Functions
5. Templating is built-in and is just standard PHP syntax.
6. PHP includes all common modules, along with a `php.ini` configuration file.
7. General purpose scripting with `php-cli`.
8. Great documentation. Downloadable offline.


# Project Specification for PHP Helpers

* `PHP library` to build `http://localhost` and `Local Area Network (LAN)` applications for internal team use.
	- Hosted using PHP built-in `php -S 127.0.0.1:8000` or `Apache`.
	- VPN or similar is used for team member remote access, if needed.
* Less secure is enough (for LAN).
* Integration with internal authentication system, where needed.
* Database is not a requirement for all applications.
	- Team shared file server for resources and `localhost` setup, for a few applications.
	- Applications without file/database requirements.
* Markdown support.
* No external dependencies. Only PHP, PHP GD and other default extensions.
* PHP/HTML/CSS, necessary javascript, for easy learning for everyone in the team.
