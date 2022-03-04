<!-- markdownlint-disable MD024 MD036 -->

# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [4.2.3] - 2020-02-14

### Added

- HTTP Transport: Oceania Base URLs (playground and production)


## [4.2.2] - 2019-11-05

### Changed

- Remove "src" folder from a PSR4 autoload definition as fallback directory.


## [4.2.1] - 2019-10-15

### Added

- Test against php 7.3

### Fixed

- CURL Transport: Add processing of multiple HTTP headers in response.[Caught exception: Response is missing a Content-Type header #44](https://github.com/klarna/kco_rest_php/issues/44); 
- Fix exception namespace and PHPDoc
- Stores the KEY ID field in order to restore the ID after fetching data without the KEY field. [Payments Session ID is reset after fetch() #37](https://github.com/klarna/kco_rest_php/issues/37)

## [4.2.0] - 2019-06-11

This release has a number of **[no-backward-compatibility]** flags. But these incompatible changes related
only to the "under the hood" files. It means, if you operate only with high-level instances, like
`Connector` and `Rest\`/`Checkout/Payments/OrderManagement/etc` resources (you have the code,
like in the example files) - you are safe for upgrade.

If you have your own implementation of REST API Services, HTTP Transport or Base Resource instance, 
you need to check the code before go live.

### Added

- Add new HTTP Transport `CURLTransport`. This transport does not require any package dependencies
- Add PHP SDK SPL Autoloader
- Add `ApiResponse` class to unify an HTTP Transport reponse

### Changed

- Decouple the HTTP Transport Connector interface. Remove Guzzle hardcoded dependency  **[no-backward-compatibility]**
- Decouple `ConnectorException`. Remove Guzzle hardcoded dependency **[partial-backward-compatibility]**
  - Change the constructor signature. Replace `RequestException` exception with `int $code`
  - `getResponse` method is not longer return Guzzle `ResponseInterface`.
    This method marked as deprecated and return `null`
- Rename `Klarna/Rest/Transport/Connector.php` to `Klarna/Rest/Transport/GuzzleConnector.php` to  
  make the name explicit. **[backward-compatible]**
  The `Klarna/Rest/Transport/Connector.php` still exists, but marked as deprecated
- Change the structure and return values for all the methods in `GuzzleConnector` (ex `Connector`)
  to follow the new Connector interface. **[no-backward-compatibility]**
- Exception throwing behavior: **[partial-backward-compatibility]**
  - `GuzzleConnector` (ex `Connector`) no longer throws Guzzle related `RuntimeException` exceptions.
    The Connector throws only `RuntimeException`
  - The only two types of exceptions can be thrown by SDK now:
    - `RuntimeException`
    - `ConnectorException`
- Extend `ResponseValidator` with `expectSuccessfull` and `isSuccessfull` methods. The methods allows
  to validate the reponse code and parse the Klarna Error Message if possible

## [4.1.5] - 2019-05-21

### Added

- Add `.gitattributes` file to remove needless load from composer imports
- Examples: Add "How to set a discount" example.

### Changed

- Remove unused phpunit/phpcov package;
- Examples: Checkout API: Add more callback URLs;

### Fixed

- Debug mode: Request body and headers were not displayed when getting 400 HTTP response code.
- Examples: Checkout API: Fix typo in the attachments file;

## [4.1.4] - 2019-03-15

### Added

- Add full support of Instant Shopping API
- Repo now has an Apache 2.0 LICENSE file on its root
- HTTP Transport: Add support of PUT method
- Examples: Add Instant Shopping examples.

### Fixed

- HTTP Transport: Stop throwing an exception when an API service return a bad structured Error;

## [4.1.3] - 2019-01-23

### Added

- HPP API: Add support for disabling an HPP session;
- Customer Token API: Add ability to use Klarna-Idempotency-Key when creating order
- Customer Token API: Add new feature: Update token status;
- Examples: Add example of changing the User-Agent.

## [4.1.2] - 2018-11-22

### Fixed

- Order management API threw Error Notice when fetching an order with refunds.

## [4.1.1] - 2018-10-31

### Added

- Add support of Merchant Card Service API

### Changed

- HPP: HPP service changed API completely without backward compatibility. Adopt SDK to the new changes.
    Mark `getSessionStatus` as `@deprecated`. Replaced by fetch function.
    Return data was changed by HPP API service.
    **[partial-backward-compatibility]**

## [4.0.0] - 2018-08-27 (Major release)

### Added

- `OrderManagement`:
  - Add ability to fetch Captures;
  - Add support of Refunds **[partial-backward-compatibility]**;
- Add full support of Customer Token API;
- Add full support of Settlements API;
- Add full support of Payments API;
- Add full support of Hosted Payment Page API;
- Add 'Debug Mode' to be able to debug requests and responses;
- Put SDK References documentation to GH Pages: [https://klarna.github.io/kco_rest_php/](https://klarna.github.io/kco_rest_php/)
- More Examples for all Klarna Services.

### Changed

- OrderManagementAPI: Changed `refund` function. Before returned `$this`, now returns - `Refund` object;
- OrderManagementAPI: Order object now has an `array` of `Refund` objects instead of just array of data.
    **[backward-compatible]**

### Fixed

- Fix: Settlements API [Unexpected Header #15](https://github.com/klarna/kco_rest_php/issues/15);

## [3.0.1] - 2017-01-16

### Fixed

- smaller fixes

## [3.0.0] - 2017-12-12

### Changed

- support for guzzle >6.0

## [2.2.0] - 2015-12-7

### Added

- **NEW META-13** Allow for 201 response on refund - *Joakim.L*

## [2.1.0] - 2015-07-29

### Changed

- **NEW MINT-2262** Support Guzzle 5.x versions - *Omer.K, Joakim.L*

## [2.0.0] - 2015-06-10

### Added

- **NEW MINT-2214** Add base URLs for North America - *Joakim.L*

### Fixed

- **NEW MINT-2203** Use order id instead of URL for checkout orders - *Joakim.L*

## [1.0.1] - 2015-03-30

### Added

- **NEW MINT-2097** Add apigen and custom styling - *Petros.G*

### Fixed

- **FIX MINT-2002** Handle errors with an empty payload - *David.K*

## 1.0.0 - 2014-10-16

### Added

- **NEW MINT-1804** Support checkout v3 and ordermanagement v1 APIs - *Joakim.L*

[Unreleased]: https://github.com/klarna/kco_rest_php/compare/v4.2.3...HEAD
[4.2.3]: https://github.com/klarna/kco_rest_php/compare/v4.2.2...v4.2.3
[4.2.2]: https://github.com/klarna/kco_rest_php/compare/v4.2.1...v4.2.2
[4.2.1]: https://github.com/klarna/kco_rest_php/compare/v4.2.0...v4.2.1
[4.2.0]: https://github.com/klarna/kco_rest_php/compare/v4.1.5...v4.2.0
[4.1.5]: https://github.com/klarna/kco_rest_php/compare/v4.1.4...v4.1.5
[4.1.4]: https://github.com/klarna/kco_rest_php/compare/v4.1.3...v4.1.4
[4.1.3]: https://github.com/klarna/kco_rest_php/compare/v4.1.2...v4.1.3
[4.1.2]: https://github.com/klarna/kco_rest_php/compare/v4.1.1...v4.1.2
[4.1.1]: https://github.com/klarna/kco_rest_php/compare/v4.0.0...v4.1.2
[4.0.0]: https://github.com/klarna/kco_rest_php/compare/v3.0.1...v4.0.0
[3.0.1]: https://github.com/klarna/kco_rest_php/compare/v3.0.0...v3.0.1
[3.0.0]: https://github.com/klarna/kco_rest_php/compare/v2.1.0...v3.0.0
[2.1.0]: https://github.com/klarna/kco_rest_php/compare/v2.0.0...v2.1.0
[2.0.0]: https://github.com/klarna/kco_rest_php/compare/v1.0.1...v2.0.0
[1.0.1]: https://github.com/klarna/kco_rest_php/compare/v1.0.0...v1.0.1
