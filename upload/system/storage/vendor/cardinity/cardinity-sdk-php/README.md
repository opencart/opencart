
Cardinity Client PHP Library
================================================
[![Build Status](https://travis-ci.org/cardinity/cardinity-sdk-php.svg?branch=master)](http://travis-ci.org/cardinity/cardinity-sdk-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cardinity/cardinity-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cardinity/cardinity-sdk-php/?branch=master)

This is official PHP client library for [Cardinity's](https://developers.cardinity.com/api/v1/) API.  
Library includes all the functionality provided by API. Library was designed to be flexible and self-explanatory for developers to implement.

## Documentation
More detailed documentation with usage examples can be found [here](https://github.com/cardinity/cardinity-sdk-php/tree/master/docs).

## Usage
### Installing via [Composer](https://getcomposer.org)
```bash
$ php composer.phar require cardinity/cardinity-sdk-php
```
### Direct Download
You can download the [latest release](https://github.com/cardinity/cardinity-sdk-php/releases/latest) file starting with ```cardinity-sdk-php-*.zip```.

### Making API Calls
#### Initialize the client object
```php
use Cardinity\Client;
$client = Client::create([
    'consumerKey' => 'YOUR_CONSUMER_KEY',
    'consumerSecret' => 'YOUR_CONSUMER_SECRET',
]);
```

#### Create new payment
```php
use Cardinity\Method\Payment;
$method = new Payment\Create([
    'amount' => 50.00,
    'currency' => 'EUR',
    'settle' => false,
    'description' => 'some description',
    'order_id' => '12345678',
    'country' => 'LT',
    'payment_method' => Payment\Create::CARD,
    'payment_instrument' => [
        'pan' => '4111111111111111',
        'exp_year' => 2021,
        'exp_month' => 12,
        'cvc' => '456',
        'holder' => 'Mike Dough'
    ],
]);
/** @type Cardinity\Method\Payment\Payment */
$payment = $client->call($method);
$paymentId = $payment->getId();
// serializes object into string for storing in database
$serialized = serialize($payment);
```

#### Get existing payment
```php
$method = new Payment\Get('cb5e1c95-7685-4499-a2b1-ae0f28297b92');
/** @type Cardinity\Method\Payment\Payment */
$payment = $client->call($method);
```

## API documentation
[https://developers.cardinity.com/api/v1/](https://developers.cardinity.com/api/v1/)

## Development Status
All the API __v1__ methods are implemented.
