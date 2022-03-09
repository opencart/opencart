<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class UsBankAccountVerificationTest extends Setup
{
    public function test_createWithSuccessfulResponse()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Test\Helper::generateValidUsBankAccountNonce(),
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount(),
                'usBankAccountVerificationMethod' => 'network_check',
            ]
        ]);

        $usBankAccount = $result->paymentMethod;
        $this->assertEquals(1, count($usBankAccount->verifications));
        $verification = $usBankAccount->verifications[0];
        $this->assertEquals('network_check', $verification->verificationMethod);
    }

    public function test_findVerification()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Test\Helper::generateValidUsBankAccountNonce(),
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount(),
                'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount(),
                'usBankAccountVerificationMethod' => Braintree\Result\UsBankAccountVerification::NETWORK_CHECK,
            ]
        ]);

        $usBankAccount = $result->paymentMethod;
        $this->assertEquals(1, count($usBankAccount->verifications));
        $createdVerification = $usBankAccount->verifications[0];
        $foundVerification = Braintree\UsBankAccountVerification::find($createdVerification->id);
        $this->assertEquals($foundVerification, $createdVerification);
    }

    public function test_searchVerification()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Test\Helper::generateValidUsBankAccountNonce(),
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount(),
                'usBankAccountVerificationMethod' => Braintree\Result\UsBankAccountVerification::INDEPENDENT_CHECK,
            ]
        ]);

        $usBankAccount = $result->paymentMethod;
        $this->assertEquals(1, count($usBankAccount->verifications));
        $createdVerification = $usBankAccount->verifications[0];

        $query = [Braintree\UsBankAccountVerificationSearch::id()->is($createdVerification->id)];
        $query[] = Braintree\UsBankAccountVerificationSearch::accountNumber()->endsWith("1234");

        $collection = Braintree\UsBankAccountVerification::search($query);

        $this->assertEquals(1, $collection->maximumCount());
    }

    public function test_attemptConfirmMicroTransferAmounts()
    {
        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration2_merchant_id',
            'publicKey' => 'integration2_public_key',
            'privateKey' => 'integration2_private_key'
        ]);

        $customer = $gateway->customer()->create([
            'firstName' => 'Joe',
            'lastName' => 'Brown'
        ])->customer;

        $result = $gateway->paymentMethod()->create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Test\Helper::generateValidUsBankAccountNonce(),
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::anotherUsBankMerchantAccount(),
                'usBankAccountVerificationMethod' => Braintree\Result\UsBankAccountVerification::MICRO_TRANSFERS,
            ]
        ]);

        $usBankAccount = $result->paymentMethod;
        $this->assertEquals(1, count($usBankAccount->verifications));
        $createdVerification = $usBankAccount->verifications[0];

        $result = $gateway->usBankAccountVerification()->confirmMicroTransferAmounts($createdVerification->id, [1, 1]);

        $this->assertFalse($result->success);

        $amountErrors = $result->errors->forKey('usBankAccountVerification')->onAttribute('base');
        $this->assertEquals(
            Braintree\Error\Codes::US_BANK_ACCOUNT_VERIFICATION_AMOUNTS_DO_NOT_MATCH,
            $amountErrors[0]->code
        );
    }

    public function test_confirmMicroTransferAmountsSettled()
    {
        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration2_merchant_id',
            'publicKey' => 'integration2_public_key',
            'privateKey' => 'integration2_private_key'
        ]);

        $customer = $gateway->customer()->create([
            'firstName' => 'Joe',
            'lastName' => 'Brown'
        ])->customer;

        $result = $gateway->paymentMethod()->create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Test\Helper::generateValidUsBankAccountNonce('1000000000'),
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::anotherUsBankMerchantAccount(),
                'usBankAccountVerificationMethod' => Braintree\Result\UsBankAccountVerification::MICRO_TRANSFERS,
            ]
        ]);

        $usBankAccount = $result->paymentMethod;
        $this->assertEquals(1, count($usBankAccount->verifications));
        $createdVerification = $usBankAccount->verifications[0];

        $result = $gateway->usBankAccountVerification()->confirmMicroTransferAmounts($createdVerification->id, [17, 29]);

        $this->assertTrue($result->success);

        $usBankAccountVerification = $result->usBankAccountVerification;
        $this->assertEquals($usBankAccountVerification->status, Braintree\Result\UsBankAccountVerification::VERIFIED);
        $usBankAccount = $gateway->usBankAccount()->find($usBankAccountVerification->usBankAccount->token);
        $this->assertTrue($usBankAccount->verified);
    }

    public function test_confirmMicroTransferAmountsUnsettled()
    {
        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration2_merchant_id',
            'publicKey' => 'integration2_public_key',
            'privateKey' => 'integration2_private_key'
        ]);

        $customer = $gateway->customer()->create([
            'firstName' => 'Joe',
            'lastName' => 'Brown'
        ])->customer;

        $result = $gateway->paymentMethod()->create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Test\Helper::generateValidUsBankAccountNonce('1000000001'),
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::anotherUsBankMerchantAccount(),
                'usBankAccountVerificationMethod' => Braintree\Result\UsBankAccountVerification::MICRO_TRANSFERS,
            ]
        ]);

        $usBankAccount = $result->paymentMethod;
        $this->assertEquals(1, count($usBankAccount->verifications));
        $createdVerification = $usBankAccount->verifications[0];

        $result = $gateway->usBankAccountVerification()->confirmMicroTransferAmounts($createdVerification->id, [17, 29]);

        $this->assertTrue($result->success);

        $usBankAccountVerification = $result->usBankAccountVerification;
        $this->assertEquals($usBankAccountVerification->status, Braintree\Result\UsBankAccountVerification::PENDING);
        $usBankAccount = $gateway->usBankAccount()->find($usBankAccountVerification->usBankAccount->token);
        $this->assertFalse($usBankAccount->verified);
    }

    public function test_exceedRetryThreshold()
    {
        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration2_merchant_id',
            'publicKey' => 'integration2_public_key',
            'privateKey' => 'integration2_private_key'
        ]);

        $customer = $gateway->customer()->create([
            'firstName' => 'Joe',
            'lastName' => 'Brown'
        ])->customer;

        $result = $gateway->paymentMethod()->create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Test\Helper::generateValidUsBankAccountNonce(),
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::anotherUsBankMerchantAccount(),
                'usBankAccountVerificationMethod' => Braintree\Result\UsBankAccountVerification::MICRO_TRANSFERS,
            ]
        ]);

        $usBankAccount = $result->paymentMethod;
        $this->assertEquals(1, count($usBankAccount->verifications));
        $createdVerification = $usBankAccount->verifications[0];

        for ($i = 0; $i < 4; $i++) {
            $result = $gateway->usBankAccountVerification()->confirmMicroTransferAmounts($createdVerification->id, [1, 1]);
            $this->assertFalse($result->success);

            $amountErrors = $result->errors->forKey('usBankAccountVerification')->onAttribute('base');
            $this->assertEquals(
                Braintree\Error\Codes::US_BANK_ACCOUNT_VERIFICATION_AMOUNTS_DO_NOT_MATCH,
                $amountErrors[0]->code
            );
        }

        $result = $gateway->usBankAccountVerification()->confirmMicroTransferAmounts($createdVerification->id, [1, 1]);
        $this->assertFalse($result->success);

        $amountErrors = $result->errors->forKey('usBankAccountVerification')->onAttribute('base');
        $this->assertEquals(
            Braintree\Error\Codes::US_BANK_ACCOUNT_VERIFICATION_TOO_MANY_CONFIRMATION_ATTEMPTS,
            $amountErrors[0]->code
        );
    }
}
