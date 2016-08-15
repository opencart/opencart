# http_build_url() for PHP

[![Build Status](https://travis-ci.org/jakeasmith/http_build_url.png)](https://travis-ci.org/jakeasmith/http_build_url)
[![Latest Stable Version](https://poser.pugx.org/jakeasmith/http_build_url/v/stable.png)](https://packagist.org/packages/jakeasmith/http_build_url)
[![Latest Unstable Version](https://poser.pugx.org/jakeasmith/http_build_url/v/unstable.png)](https://packagist.org/packages/jakeasmith/http_build_url)
[![Total Downloads](https://poser.pugx.org/jakeasmith/http_build_url/downloads.png)](https://packagist.org/packages/jakeasmith/http_build_url)

This simple library provides functionality for [`http_build_url()`](http://us2.php.net/manual/en/function.http-build-url.php) to environments without pecl_http. It aims to mimic the functionality of the pecl function in every way and ships with a full suite of tests that have been run against both the original function and the one in this package.

## Installation

The easiest way to install this library is to use Composer and add the following
to your project's `composer.json` file:

``` javascript
{
    "require": {
        "jakeasmith/http_build_url": "~0.1.5"
    }
}
```

## License

This project is licensed under the MIT License - see the LICENSE file for details.
