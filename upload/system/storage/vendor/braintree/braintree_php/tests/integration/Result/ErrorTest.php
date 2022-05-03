<?php
namespace Test\Integration\Result;

require_once dirname(dirname(__DIR__)) . '/Setup.php';

use Test\Setup;
use Braintree;

class ErrorTest extends Setup
{
    public function testValueForHtmlField()
    {
        $result = Braintree\Customer::create([
            'email' => 'invalid-email',
            'creditCard' => [
                'number' => 'invalid-number',
                'expirationDate' => 'invalid-exp',
                'billingAddress' => [
                    'countryName' => 'invalid-country'
                ]
            ],
            'customFields' => [
                'store_me' => 'some custom value'
            ]
        ]);
        $this->assertEquals(false, $result->success);
        $this->assertEquals('invalid-email', $result->valueForHtmlField('customer[email]'));
        $this->assertEquals('', $result->valueForHtmlField('customer[credit_card][number]'));
        $this->assertEquals('invalid-exp', $result->valueForHtmlField('customer[credit_card][expiration_date]'));
        $this->assertEquals('invalid-country', $result->valueForHtmlField('customer[credit_card][billing_address][country_name]'));
        $this->assertEquals('some custom value', $result->valueForHtmlField('customer[custom_fields][store_me]'));
    }
}
