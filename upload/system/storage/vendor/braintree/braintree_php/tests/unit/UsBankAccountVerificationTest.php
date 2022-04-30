<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test\Setup;
use Braintree;

class UsBankAccountVerificationTest extends Setup
{

    public function testAttributes()
    {
        $verification = Braintree\UsBankAccountVerification::factory([
            'status' => Braintree\Result\UsBankAccountVerification::VERIFIED,
            'verificationMethod' => Braintree\Result\UsBankAccountVerification::NETWORK_CHECK,
            'usBankAccount' => [
                'token' => 'abc123',
            ],
        ]);

        $this->assertEquals(Braintree\Result\UsBankAccountVerification::VERIFIED, $verification->status);
        $this->assertEquals(
            Braintree\Result\UsBankAccountVerification::NETWORK_CHECK,
            $verification->verificationMethod
        );

        $this->assertEquals(
            'abc123',
            $verification->usBankAccount->token
        );
    }
}
