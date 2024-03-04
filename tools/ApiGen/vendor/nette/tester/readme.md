[Nette Tester](https://tester.nette.org): enjoyable unit testing
================================================================

[![Downloads this Month](https://img.shields.io/packagist/dm/nette/tester.svg)](https://packagist.org/packages/nette/tester)
[![Tests](https://github.com/nette/tester/workflows/Tests/badge.svg?branch=master)](https://github.com/nette/tester/actions)
[![Latest Stable Version](https://poser.pugx.org/nette/tester/v/stable)](https://github.com/nette/tester/releases)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/nette/tester/blob/master/license.md)


Introduction
------------

Nette Tester is a productive and enjoyable unit testing framework. It's used by
the [Nette Framework](https://nette.org) and is capable of testing any PHP code.

Documentation is available on the [Nette Tester website](https://tester.nette.org).
Read the [blog](https://blog.nette.org/category/tester/) for new information.


[Support Tester](https://github.com/sponsors/dg)
--------------------------------------------

Do you like Nette Tester? Are you looking forward to the new features?

[![Buy me a coffee](https://files.nette.org/icons/donation-3.svg)](https://github.com/sponsors/dg)

Thank you!


Installation
------------

The recommended way to install Nette Tester is through Composer:

```
composer require nette/tester --dev
```

Alternatively, you can download the [tester.phar](https://github.com/nette/tester/releases) file.

- Nette Tester 2.5 is compatible with PHP 8.0 to 8.2
- Nette Tester 2.4 is compatible with PHP 7.2 to 8.2
- Nette Tester 2.3 is compatible with PHP 7.1 to 8.0
- Nette Tester 2.1 & 2.2 is compatible with PHP 7.1 to 7.3
- Nette Tester 2.0 is compatible with PHP 5.6 to 7.3

Collecting and processing code coverage information depends on Xdebug or PCOV extension, or PHPDBG SAPI.


Writing Tests
-------------

Imagine that we are testing this simple class:

```php
class Greeting
{
	function say($name)
	{
		if (!$name) {
			throw new InvalidArgumentException('Invalid name.');
		}
		return "Hello $name";
	}
}
```

So we create test file named `greeting.test.phpt`:

```php
require 'src/bootstrap.php';

use Tester\Assert;

$h = new Greeting;

// use an assertion function to test say()
Assert::same('Hello John', $h->say('John'));
```

Thats' all!

Now we run tests from command-line using the `tester` command:

```
> tester
 _____ ___  ___ _____ ___  ___
|_   _/ __)( __/_   _/ __)| _ )
  |_| \___ /___) |_| \___ |_|_\  v2.5

PHP 8.2.0 | php -n | 8 threads
.
OK (1 tests, 0 skipped, 0.0 seconds)
```

Nette Tester prints dot for successful test, F for failed test
and S when the test has been skipped.


Assertions
----------

This table shows all assertions (class `Assert` means `Tester\Assert`):

- `Assert::same($expected, $actual)` - Reports an error if $expected and $actual are not the same.
- `Assert::notSame($expected, $actual)` - Reports an error if $expected and $actual are the same.
- `Assert::equal($expected, $actual)` - Like same(), but identity of objects and the order of keys in the arrays are ignored.
- `Assert::notEqual($expected, $actual)` - Like notSame(), but identity of objects and arrays order are ignored.
- `Assert::contains($needle, array $haystack)` - Reports an error if $needle is not an element of $haystack.
- `Assert::contains($needle, string $haystack)` - Reports an error if $needle is not a substring of $haystack.
- `Assert::notContains($needle, array $haystack)` - Reports an error if $needle is an element of $haystack.
- `Assert::notContains($needle, string $haystack)` - Reports an error if $needle is a substring of $haystack.
- `Assert::true($value)` - Reports an error if $value is not true.
- `Assert::false($value)` - Reports an error if $value is not false.
- `Assert::truthy($value)` - Reports an error if $value is not truthy.
- `Assert::falsey($value)` - Reports an error if $value is not falsey.
- `Assert::null($value)` - Reports an error if $value is not null.
- `Assert::nan($value)` - Reports an error if $value is not NAN.
- `Assert::type($type, $value)` -  Reports an error if the variable $value is not of PHP or class type $type.
- `Assert::exception($closure, $class, $message = null, $code = null)` -  Checks if the function throws exception.
- `Assert::error($closure, $level, $message = null)` -  Checks if the function $closure throws PHP warning/notice/error.
- `Assert::noError($closure)` -  Checks that the function $closure does not throw PHP warning/notice/error or exception.
- `Assert::match($pattern, $value)` - Compares result using regular expression or mask.
- `Assert::matchFile($file, $value)` - Compares result using regular expression or mask sorted in file.
- `Assert::count($count, $value)` - Reports an error if number of items in $value is not $count.
- `Assert::with($objectOrClass, $closure)` - Executes function that can access private and protected members of given object via $this.

Testing exceptions:

```php
Assert::exception(function () {
	$h = new Greeting;
	$h->say(null);
}, InvalidArgumentException::class, 'Invalid name.');
```

Testing PHP errors, warnings or notices:

```php
Assert::error(function () {
	$h = new Greeting;
	echo $h->abc;
}, E_NOTICE, 'Undefined property: Greeting::$abc');
```

Testing private access methods:

```php
$h = new Greeting;
Assert::with($h, function () {
	// normalize() is internal private method.
	Assert::same('Hello David', $this->normalize('Hello david')); // $this is Greeting
});
```

Tips and features
-----------------

Running unit tests manually is annoying, so let Nette Tester to watch your folder
with code and automatically re-run tests whenever code is changed:

```
tester -w /my/source/codes
```

Running tests in parallel is very much faster and Nette Tester uses 8 threads as default.
If you wish to run the tests in series use:

```
tester -j 1
```

How do you find code that is not yet tested? Use Code-Coverage Analysis. This feature
requires you have installed [Xdebug](https://xdebug.org/) or [PCOV](https://github.com/krakjoe/pcov)
extension, or you are using PHPDBG SAPI. This will generate nice HTML report in `coverage.html`.

```
tester . -c php.ini --coverage coverage.html --coverage-src /my/source/codes
```

We can load Nette Tester using Composer's autoloader. In this case
it is important to setup Nette Tester environment:

```php
require 'vendor/autoload.php';

Tester\Environment::setup();
```

We can also test HTML pages. Let the [template engine](https://latte.nette.org) generate
HTML code or download existing page to `$html` variable. We will check whether
the page contains form fields for username and password. The syntax is the
same as the CSS selectors:

```php
$dom = Tester\DomQuery::fromHtml($html);

Assert::true($dom->has('input[name="username"]'));
Assert::true($dom->has('input[name="password"]'));
```

For more inspiration see how [Nette Tester tests itself](https://github.com/nette/tester/tree/master/tests).


Running tests
-------------

The command-line test runner can be invoked through the `tester` command (or `php tester.php`). Take a look
at the command-line options:

```
> tester

Usage:
    tester [options] [<test file> | <directory>]...

Options:
    -p <path>                    Specify PHP interpreter to run (default: php).
    -c <path>                    Look for php.ini file (or look in directory) <path>.
    -C                           Use system-wide php.ini.
    -l | --log <path>            Write log to file <path>.
    -d <key=value>...            Define INI entry 'key' with value 'val'.
    -s                           Show information about skipped tests.
    --stop-on-fail               Stop execution upon the first failure.
    -j <num>                     Run <num> jobs in parallel (default: 8).
    -o <console|tap|junit|none>  Specify output format.
    -w | --watch <path>          Watch directory.
    -i | --info                  Show tests environment info and exit.
    --setup <path>               Script for runner setup.
    --temp <path>                Path to temporary directory. Default by sys_get_temp_dir().
    --colors [1|0]               Enable or disable colors.
    --coverage <path>            Generate code coverage report to file.
    --coverage-src <path>        Path to source code.
    -h | --help                  This help.
```
