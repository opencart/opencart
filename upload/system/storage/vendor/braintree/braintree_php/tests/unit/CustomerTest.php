<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class CustomerTest extends Setup
{
    public function testGet_givesErrorIfInvalidProperty()
    {
        $this->setExpectedException('PHPUnit_Framework_Error', 'Undefined property on Braintree\Customer: foo');
        $c = Braintree\Customer::factory([]);
        $c->foo;
    }

    public function testUpdateSignature_doesNotAlterOptionsInCreditCardUpdateSignature()
    {
        Braintree\CustomerGateway::updateSignature();
        foreach (Braintree\CreditCardGateway::updateSignature() as $key => $value) {
            if(is_array($value) and array_key_exists('options', $value)) {
                $this->assertEquals([
                    'makeDefault',
                    'verificationMerchantAccountId',
                    'verifyCard',
                    'verificationAmount',
                    'verificationAccountType',
                    'venmoSdkSession',
                    'failOnDuplicatePaymentMethod',
                ], $value['options']);
            }
        }
    }

    public function testCreateSignature_doesNotIncludeCustomerIdOnCreditCard()
    {
        $signature = Braintree\CustomerGateway::createSignature();
        $creditCardSignatures = array_filter($signature, 'Test\Unit\CustomerTest::findCreditCardArray');
        $creditCardSignature = array_shift($creditCardSignatures)['creditCard'];

        $this->assertNotContains('customerId', $creditCardSignature);
    }

    public function findCreditCardArray($el)
    {
        return is_array($el) && array_key_exists('creditCard', $el);
    }

    public function testFindErrorsOnBlankId()
    {
        $this->setExpectedException('InvalidArgumentException');
        Braintree\Customer::find('');
    }

    public function testFindErrorsOnWhitespaceId()
    {
        $this->setExpectedException('InvalidArgumentException');
        Braintree\Customer::find('\t');
    }
}
