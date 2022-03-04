How to use SDK HTTP Transport
========================================================

The SDK functionality relies on an "HTTP Transport" mechanism to handle HTTP communication 
(requests / responses). The mechanism is decoupled from the SDK implementation itself 
and allows for usage of different libraries that can handle the HTTP communication.

All the transports should be responsible to get the information to be send and return the result. 
There is no any SDK/Api Resources logic inside the transport.

Previously the SDK used Guzzle HTTP library and it forces SDK users to use Composer. There was no
ability to install the Guzzle library manually. Currently, there is a new cURL Transport, that
allows to use the PHP SDK without Composer and any other extra dependencies.

## Basic Transport Usage

The basic SDK usage looks like in the example. You need to create an HTTP Transport connector
and pass the connector to the API Service resource:

```php
$connector = Klarna\Rest\Transport\Connector::create($merchantId, $sharedSecret, $apiEndpoint);
$api = new Klarna\Rest\ApiService\Resource($connector);
$api->someMethod($someData);
```

## Guzzle HTTP Transport (Klarna\Rest\Transport\GuzzleConnector)

By default all the SDK examples use Guzzle HTTP Transport (Composer is required in this case).
You need to include the SDK into your PHP file using the Composer autoloader:

```php
require('/path/to/project/vendor/autoload.php');

$merchantId = 'K123456_abcd12345';
$sharedSecret = 'sharedSecret';
$apiEndpoint = Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;

// Create Guzzle HTTP Transport Connector
$connector = Klarna\Rest\Transport\GuzzleConnector::create(
    $merchantId,
    $sharedSecret,
    $apiEndpoint
);

$checkout = new Klarna\Rest\Checkout\Order($connector);
$checkout->create($order);
```

## cURL HTTP Transport (Klarna\Rest\Transport\CURLConnector)

The transport uses PHP cURL Library (libcurl): https://www.php.net/manual/en/book.curl.php

This library does not require any Composer libraries and relies only on `libcurl`. It means you
do not have any `vendor` autoloader and need to include the SDK SPL autoloader.

```php
//                       `src` instead of vendor
require('/path/to/project/src/autoload.php');

$merchantId = 'K123456_abcd12345';
$sharedSecret = 'sharedSecret';
$apiEndpoint = Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;

// Create cURL HTTP Transport Connector
$connector = Klarna\Rest\Transport\CURLConnector::create(
    $merchantId,
    $sharedSecret,
    $apiEndpoint
);

$checkout = new Klarna\Rest\Checkout\Order($connector);
$checkout->create($order);
```

## HTTP Transport Response and Exceptions

All the Transports should implement the `ConnectorInterface` interface. It imposes some restrictions:
- The only allowed response is [ApiResponse](https://github.com/klarna/kco_rest_php/blob/v4.1/src/Klarna/Rest/Transport/ApiResponse.php)
- The only allowed exceptions that can be thrown from the Transport is `RuntimeException`.
