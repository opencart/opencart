<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class UnknownPaymentMethodTest extends Setup
{
    public function testHandlesUnknownPaymentMethodResponses()
    {
        $response = [
            'unkownPaymentMethod' => [
                'token' => 'SOME_TOKEN',
                'default' => true
            ]
        ];
        $unknownPaymentMethodObject = Braintree\UnknownPaymentMethod::factory($response);
        $this->assertEquals('SOME_TOKEN', $unknownPaymentMethodObject->token);
        $this->assertTrue($unknownPaymentMethodObject->isDefault());
        $this->assertEquals('https://assets.braintreegateway.com/payment_method_logo/unknown.png', $unknownPaymentMethodObject->imageUrl);
    }
}

