RobotLoader: comfortable autoloading
====================================

[![Downloads this Month](https://img.shields.io/packagist/dm/nette/robot-loader.svg)](https://packagist.org/packages/nette/robot-loader)
[![Tests](https://github.com/nette/robot-loader/workflows/Tests/badge.svg?branch=master)](https://github.com/nette/robot-loader/actions)
[![Coverage Status](https://coveralls.io/repos/github/nette/robot-loader/badge.svg?branch=master)](https://coveralls.io/github/nette/robot-loader?branch=master)
[![Latest Stable Version](https://poser.pugx.org/nette/robot-loader/v/stable)](https://github.com/nette/robot-loader/releases)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/nette/robot-loader/blob/master/license.md)


Introduction
------------

RobotLoader is a tool that gives you comfort of automated class loading for your entire application including third-party libraries.

- get rid of all `require`
- does not require strict directory or file naming conventions
- extremely fast
- no manual cache updates, everything runs automatically
- highly mature, stable and widely used library

So we can forget about those famous code blocks:

```php
require_once 'Utils/Page.php';
require_once 'Utils/Style.php';
require_once 'Utils/Paginator.php';
...
```

[Support Me](https://github.com/sponsors/dg)
--------------------------------------------

Do you like RobotLoader? Are you looking forward to the new features?

[![Buy me a coffee](https://files.nette.org/icons/donation-3.svg)](https://github.com/sponsors/dg)

Thank you!


Installation
------------

The recommended way to install is via Composer:

```shell
composer require nette/robot-loader
```

It requires PHP version 8.0 and supports PHP up to 8.2.


Usage
-----

Like the Google robot crawls and indexes websites, [RobotLoader](https://api.nette.org/3.0/Nette/Loaders/RobotLoader.html) crawls all PHP scripts and records what classes and interfaces were found in them. These records are then saved in cache and used during all subsequent requests. You just need to specify what directories to index and where to save the cache:

```php
$loader = new Nette\Loaders\RobotLoader;

// directories to be indexed by RobotLoader (including subdirectories)
$loader->addDirectory(__DIR__ . '/app');
$loader->addDirectory(__DIR__ . '/libs');

// use 'temp' directory for cache
$loader->setTempDirectory(__DIR__ . '/temp');
$loader->register(); // Run the RobotLoader
```

And that's all. From now on, you don't need to use `require`. Great, isn't it?

When RobotLoader encounters duplicate class name during indexing, it throws an exception and informs you about it. RobotLoader also automatically updates the cache when it has to load a class it doesn't know. We recommend disabling this on production servers, see [Caching](#Caching).

If you want RobotLoader to skip some directories, use `$loader->excludeDirectory('temp')` (it can be called multiple times or you can pass multiple directories).

By default, RobotLoader reports errors in PHP files by throwing exception `ParseError`. It can be disabled via `$loader->reportParseErrors(false)`.


PHP Files Analyzer
------------------

RobotLoader can also be used purely to find classes, interfaces, and trait in PHP files **without** using the autoloading feature:

```php
$loader = new Nette\Loaders\RobotLoader;
$loader->addDirectory(__DIR__ . '/app');

// Scans directories for classes / intefaces / traits
$loader->rebuild();

// Returns array of class => filename pairs
$res = $loader->getIndexedClasses();
```

Even with such use, you can use the cache. As a result, unmodified files will not be repeatedly analyzed when rescanning:

```php
$loader = new Nette\Loaders\RobotLoader;
$loader->addDirectory(__DIR__ . '/app');
$loader->setTempDirectory(__DIR__ . '/temp');

// Scans directories using a cache
$loader->refresh();

// Returns array of class => filename pairs
$res = $loader->getIndexedClasses();
```

Caching
-------

RobotLoader is very fast because it cleverly uses the cache.

When developing with it, you have practically no idea that it runs on the background. It continuously updates the cache because it knows that classes and files can be created, deleted, renamed, etc. And it doesn't repeatedly scan unmodified files.

When used on a production server, on the other hand, we recommend disabling the cache update using `$loader->setAutoRefresh(false)`, because the files are not changing. At the same time, it is necessary to **clear the cache** when uploading a new version on the hosting.

Of course, the initial scanning of files, when the cache does not already exist, may take a few seconds for larger applications. RobotLoader has built-in prevention against [cache stampede](https://en.wikipedia.org/wiki/Cache_stampede).
This is a situation where production server receives a large number of concurrent requests and because RobotLoader's cache does not yet exist, they would all start scanning the files. Which spikes CPU and filesystem usage.
Fortunately, RobotLoader works in such a way that for multiple concurrent requests, only the first thread indexes the files, creates a cache, the others wait, and then use the cache.


PSR-4
-----

Today, Composer can be used for autoloading in compliance with PSR-4. Simply saying, it is a system where the namespaces and class names correspond to the directory structure and file names, ie `App\Router\RouterFactory` is located in the file `/path/to/App/Router/RouterFactory.php`.

RobotLoader is not tied to any fixed structure, therefore, it is useful in situations where it does not suit you to have the directory structure designed as namespaces in PHP, or when you are developing an application that has historically not used such conventions. It is also possible to use both loaders together.


If you like RobotLoader, **[please make a donation now](https://nette.org/donate)**. Thank you!
