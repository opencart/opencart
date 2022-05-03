<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class CreditCardVerificationTest extends Setup
{
    public function test_createWithSuccessfulResponse()
    {
        $result = Braintree\CreditCardVerification::create([
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/2011',
            ],
        ]);
        $this->assertTrue($result->success);

        $verification = $result->verification;

        $this->assertEquals($verification->processorResponseCode, '1000');
        $this->assertEquals($verification->processorResponseText, 'Approved');
        $this->assertEquals($verification->processorResponseType, Braintree\ProcessorResponseTypes::APPROVED);
    }

    public function test_createWithUnsuccessfulResponse()
    {
        $result = Braintree\CreditCardVerification::create([
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$failsSandboxVerification['Visa'],
                'expirationDate' => '05/2011',
            ],
        ]);
        $this->assertFalse($result->success);
        $this->assertEquals($result->verification->status, Braintree\Result\CreditCardVerification::PROCESSOR_DECLINED);

        $verification = $result->verification;

        $this->assertEquals($verification->processorResponseCode, '2000');
        $this->assertEquals($verification->processorResponseText, 'Do Not Honor');
        $this->assertEquals($verification->processorResponseType, Braintree\ProcessorResponseTypes::SOFT_DECLINED);
    }

	public function test_createWithInvalidRequest()
	{
        $result = Braintree\CreditCardVerification::create([
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$failsSandboxVerification['Visa'],
                'expirationDate' => '05/2011',
				],
			'options' => [
			    'amount' => '-5.00'
				],
        ]);
        $this->assertFalse($result->success);

		$amountErrors = $result->errors->forKey('verification')->forKey('options')->onAttribute('amount');
		$this->assertEquals(Braintree\Error\Codes::VERIFICATION_OPTIONS_AMOUNT_CANNOT_BE_NEGATIVE, $amountErrors[0]->code);
    }

    public function test_createWithAccountTypeCredit()
    {
        $result = Braintree\CreditCardVerification::create([
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$hiper,
                'expirationDate' => '05/2011',
            ],
            'options' => [
                'merchantAccountId' => 'hiper_brl',
                'accountType' => 'credit'
            ]
        ]);
        $this->assertTrue($result->success);

        $verification = $result->verification;

        $this->assertEquals($verification->creditCard['accountType'], 'credit');
    }

    public function test_createWithAccountTypeDebit()
    {
        $result = Braintree\CreditCardVerification::create([
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$hiper,
                'expirationDate' => '05/2011',
            ],
            'options' => [
                'merchantAccountId' => 'hiper_brl',
                'accountType' => 'debit'
            ]
        ]);
        $this->assertTrue($result->success);

        $verification = $result->verification;

        $this->assertEquals($verification->creditCard['accountType'], 'debit');
    }

    public function test_createErrorsWithAccountTypeIsInvalid()
    {
        $result = Braintree\CreditCardVerification::create([
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$hiper,
                'expirationDate' => '05/2011',
            ],
            'options' => [
                'merchantAccountId' => 'hiper_brl',
                'accountType' => 'wrong'
            ]
        ]);

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('verification')->forKey('options')->onAttribute('accountType');
        $this->assertEquals(Braintree\Error\Codes::VERIFICATION_OPTIONS_ACCOUNT_TYPE_IS_INVALID, $errors[0]->code);
    }

    public function test_createErrorsWithAccountTypeNotSupported()
    {
        $result = Braintree\CreditCardVerification::create([
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2011',
            ],
            'options' => [
                'accountType' => 'credit'
            ]
        ]);

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('verification')->forKey('options')->onAttribute('accountType');
        $this->assertEquals(Braintree\Error\Codes::VERIFICATION_OPTIONS_ACCOUNT_TYPE_NOT_SUPPORTED, $errors[0]->code);
    }
}
