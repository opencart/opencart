<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test\Setup;
use Braintree;

class UsBankAccountTest extends Setup
{

    public function testIsDefault()
    {
        $usBankAccount = Braintree\UsBankAccount::factory(['default' => true]);
        $this->assertTrue($usBankAccount->isDefault());

        $usBankAccount = Braintree\UsBankAccount::factory(['default' => false]);
        $this->assertFalse($usBankAccount->isDefault());
    }

    public function testHasVerifications()
    {
        $usBankAccount = Braintree\UsBankAccount::factory([
            'verifications' => [
                [
                    'status' => Braintree\Result\UsBankAccountVerification::VERIFIED,
                    'verificationMethod' => Braintree\Result\UsBankAccountVerification::TOKENIZED_CHECK
                ],
                [
                    'status' => Braintree\Result\UsBankAccountVerification::PENDING,
                    'verificationMethod' => Braintree\Result\UsBankAccountVerification::NETWORK_CHECK
                ],
            ]
        ]);


        $this->assertEquals(2, count($usBankAccount->verifications));

        $verification1 = $usBankAccount->verifications[0];
        $this->assertEquals($verification1->status, Braintree\Result\UsBankAccountVerification::VERIFIED);
        $this->assertEquals(Braintree\Result\UsBankAccountVerification::TOKENIZED_CHECK, $verification1->verificationMethod);

        $verification2 = $usBankAccount->verifications[1];
        $this->assertEquals(Braintree\Result\UsBankAccountVerification::PENDING, $verification2->status);
        $this->assertEquals(Braintree\Result\UsBankAccountVerification::NETWORK_CHECK, $verification2->verificationMethod);
    }
}
