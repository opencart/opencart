<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class CreditCardVerificationTest extends Setup
{
	public function test_createWithInvalidArguments()
	{
        $this->setExpectedException('InvalidArgumentException', 'invalid keys: invalidProperty');
		Braintree\CreditCardVerification::create(['options' => ['amount' => '123.45'], 'invalidProperty' => 'foo']);
	}

	public function test_createWithPaymentMethodArguments()
	{
        $this->setExpectedException('InvalidArgumentException', 'invalid keys: creditCard[venmoSdkPaymentMethodCode]');
		Braintree\CreditCardVerification::create(['options' => ['amount' => '123.45'], 'creditCard' => ['venmoSdkPaymentMethodCode' => 'foo']]);
	}
}
