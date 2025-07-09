# CHANGELOG

## 0.8.1 - 2025-06-01

* Fixed insufficient nonce entropy (CVE-2025-21617)

## 0.8.0 - 2025-06-01

* Adjusted some method modifiers and added return types
* Fixed signature generation with duplicate query parameters

## 0.7.0 - 2025-06-01

* Dropped support for HHVM and PHP <7.2.5
* Dropped support for Guzzle 6.x and PSR-7 1.x
* Added support for PHP 8.1, 8.2, 8.3, 8.4
* Add param types to various methods

## 0.6.0 - 2021-07-13

* Added support for `guzzlehttp/psr7:^2.0`

## 0.5.0 - 2021-02-17

* Add oauth_body_hash parameter to authorization header
* Do not require token_secret for 2-legged authentication
* Added HMAC-SHA256 support
* Added ext-openssl suggest
* Added PHP 8 Support

## 0.4.0 - 2020-06-30

* Allow guzzle 7

## 0.3.0 - 2015-08-15

* Updated to work with Guzzle 6 as a middleware
