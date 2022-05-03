<?php
namespace Test\Integration\Error;

require_once dirname(dirname(__DIR__)) . '/Setup.php';

use Test\Setup;
use Braintree;

class ErrorCollectionTest extends Setup
{
    public function testDeepSize_withNestedErrors()
    {
        $result = Braintree\Customer::create([
            'email' => 'invalid',
            'creditCard' => [
                'number' => 'invalid',
                'expirationDate' => 'invalid',
                'billingAddress' => [
                    'countryName' => 'invaild'
                ]
            ]
        ]);
        $this->assertEquals(false, $result->success);
        $this->assertEquals(4, $result->errors->deepSize());
    }

    public function testOnHtmlField()
    {
        $result = Braintree\Customer::create([
            'email' => 'invalid',
            'creditCard' => [
                'number' => 'invalid',
                'expirationDate' => 'invalid',
                'billingAddress' => [
                    'countryName' => 'invaild'
                ]
            ]
        ]);
        $this->assertEquals(false, $result->success);
        $errors = $result->errors->onHtmlField('customer[email]');
        $this->assertEquals(Braintree\Error\Codes::CUSTOMER_EMAIL_IS_INVALID, $errors[0]->code);
        $errors = $result->errors->onHtmlField('customer[credit_card][number]');
        $this->assertEquals(Braintree\Error\Codes::CREDIT_CARD_NUMBER_INVALID_LENGTH, $errors[0]->code);
        $errors = $result->errors->onHtmlField('customer[credit_card][billing_address][country_name]');
        $this->assertEquals(Braintree\Error\Codes::ADDRESS_COUNTRY_NAME_IS_NOT_ACCEPTED, $errors[0]->code);
    }

    public function testOnHtmlField_returnsEmptyArrayIfNone()
    {
        $result = Braintree\Customer::create([
            'email' => 'invalid',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
                'billingAddress' => [
                    'streetAddress' => '1 E Main St'
                ]
            ]
        ]);
        $this->assertEquals(false, $result->success);
        $errors = $result->errors->onHtmlField('customer[email]');
        $this->assertEquals(Braintree\Error\Codes::CUSTOMER_EMAIL_IS_INVALID, $errors[0]->code);
        $this->assertEquals([], $result->errors->onHtmlField('customer[credit_card][number]'));
        $this->assertEquals([], $result->errors->onHtmlField('customer[credit_card][billing_address][country_name]'));
    }

    public function testOnHtmlField_returnsEmptyForCustomFieldsIfNoErrors()
    {
        $result = Braintree\Customer::create([
            'email' => 'invalid',
            'creditCard' => [
                'number' => '5105105105105100',
                'expirationDate' => '05/12',
            ],
            'customFields' => ['storeMe' => 'value']
        ]);
        $this->assertEquals(false, $result->success);
        $this->assertEquals([], $result->errors->onHtmlField('customer[custom_fields][store_me]'));
    }

    public function testCount_returnsTheNumberOfErrors()
    {
        $result = Braintree\Customer::create([
            'email' => 'invalid',
            'creditCard' => [
                'number' => 'invalid',
                'expirationDate' => 'invalid',
                'billingAddress' => [
                    'countryName' => 'invaild'
                ]
            ]
        ]);
        $this->assertEquals(false, $result->success);
        $this->assertEquals(4, count($result->errors));
    }
}
