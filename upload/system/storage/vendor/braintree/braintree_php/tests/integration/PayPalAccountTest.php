<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class PayPalAccountTest extends Setup
{
    public function testFind()
    {
        $paymentMethodToken = 'PAYPALToken-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);

        $foundPayPalAccount = Braintree\PayPalAccount::find($paymentMethodToken);

        $this->assertSame('jane.doe@example.com', $foundPayPalAccount->email);
        $this->assertSame($paymentMethodToken, $foundPayPalAccount->token);
        $this->assertNotNull($foundPayPalAccount->imageUrl);
    }

    public function testGatewayFind()
    {
        $paymentMethodToken = 'PAYPALToken-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $foundPayPalAccount = $gateway->paypalAccount()->find($paymentMethodToken);

        $this->assertSame('jane.doe@example.com', $foundPayPalAccount->email);
        $this->assertSame($paymentMethodToken, $foundPayPalAccount->token);
        $this->assertNotNull($foundPayPalAccount->imageUrl);
    }

    public function testFind_doesNotReturnIncorrectPaymentMethodType()
    {
        $creditCardToken = 'creditCardToken-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\CreditCard::create([
            'customerId' => $customer->id,
            'cardholderName' => 'Cardholder',
            'number' => '5105105105105100',
            'expirationDate' => '05/12',
            'token' => $creditCardToken
        ]);
        $this->assertTrue($result->success);

        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PayPalAccount::find($creditCardToken);
    }

    public function test_PayPalAccountExposesTimestamps()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$paypalFuturePayment,
        ]);
        $this->assertTrue($result->success);

        $this->assertNotNull($result->paymentMethod->createdAt);
        $this->assertNotNull($result->paymentMethod->updatedAt);
    }

    public function test_PayPalAccountExposesBillingAgreementId()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$paypalBillingAgreement
        ]);
        $this->assertTrue($result->success);

        $foundPayPalAccount = Braintree\PayPalAccount::find($result->paymentMethod->token);

        $this->assertNotNull($foundPayPalAccount->billingAgreementId);
    }

    public function testFind_throwsIfCannotBeFound()
    {
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PayPalAccount::find('invalid-token');
    }

    public function testFind_throwsUsefulErrorMessagesWhenEmpty()
    {
        $this->setExpectedException('InvalidArgumentException', 'expected paypal account id to be set');
        Braintree\PayPalAccount::find('');
    }

    public function testFind_throwsUsefulErrorMessagesWhenInvalid()
    {
        $this->setExpectedException('InvalidArgumentException', '@ is an invalid paypal account token');
        Braintree\PayPalAccount::find('@');
    }

    public function testFind_returnsSubscriptionsAssociatedWithAPaypalAccount()
    {
        $customer = Braintree\Customer::createNoValidate();
        $paymentMethodToken = 'paypal-account-' . strval(rand());

        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'consent-code',
                'token' => $paymentMethodToken
            ]
        ]);

        $result = Braintree\PaymentMethod::create([
            'paymentMethodNonce' => $nonce,
            'customerId' => $customer->id
        ]);
        $this->assertTrue($result->success);

        $token = $result->paymentMethod->token;
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $subscription1 = Braintree\Subscription::create([
            'paymentMethodToken' => $token,
            'planId' => $triallessPlan['id']
        ])->subscription;

        $subscription2 = Braintree\Subscription::create([
            'paymentMethodToken' => $token,
            'planId' => $triallessPlan['id']
        ])->subscription;

        $paypalAccount = Braintree\PayPalAccount::find($token);
        $getIds = function($sub) { return $sub->id; };
        $subIds = array_map($getIds, $paypalAccount->subscriptions);
        $this->assertTrue(in_array($subscription1->id, $subIds));
        $this->assertTrue(in_array($subscription2->id, $subIds));
    }

    public function testUpdate()
    {
        $originalToken = 'ORIGINAL_PAYPALToken-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $originalToken
            ]
        ]);

        $createResult = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($createResult->success);

        $newToken = 'NEW_PAYPALToken-' . strval(rand());
        $updateResult = Braintree\PayPalAccount::update($originalToken, [
            'token' => $newToken
        ]);

        $this->assertTrue($updateResult->success);
        $this->assertEquals($newToken, $updateResult->paypalAccount->token);

        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PayPalAccount::find($originalToken);

    }

    public function testUpdateAndMakeDefault()
    {
        $customer = Braintree\Customer::createNoValidate();

        $creditCardResult = Braintree\CreditCard::create([
            'customerId' => $customer->id,
            'number' => '5105105105105100',
            'expirationDate' => '05/12'
        ]);
        $this->assertTrue($creditCardResult->success);

        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE'
            ]
        ]);

        $createResult = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($createResult->success);

        $updateResult = Braintree\PayPalAccount::update($createResult->paymentMethod->token, [
            'options' => ['makeDefault' => true]
        ]);

        $this->assertTrue($updateResult->success);
        $this->assertTrue($updateResult->paypalAccount->isDefault());
    }

    public function testUpdate_handleErrors()
    {
        $customer = Braintree\Customer::createNoValidate();

        $firstToken = 'FIRST_PAYPALToken-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $firstNonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $firstToken
            ]
        ]);
        $firstPaypalAccount = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $firstNonce
        ]);
        $this->assertTrue($firstPaypalAccount->success);

        $secondToken = 'SECOND_PAYPALToken-' . strval(rand());
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $secondNonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $secondToken
            ]
        ]);
        $secondPaypalAccount = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $secondNonce
        ]);
        $this->assertTrue($secondPaypalAccount->success);

        $updateResult = Braintree\PayPalAccount::update($firstToken, [
            'token' => $secondToken
        ]);

        $this->assertFalse($updateResult->success);
        $errors = $updateResult->errors->forKey('paypalAccount')->errors;
        $this->assertEquals(Braintree\Error\Codes::PAYPAL_ACCOUNT_TOKEN_IS_IN_USE, $errors[0]->code);
    }

    public function testDelete()
    {
        $paymentMethodToken = 'PAYPALToken-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);

        Braintree\PayPalAccount::delete($paymentMethodToken);

        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\PayPalAccount::find($paymentMethodToken);
    }

    public function testSale_createsASaleUsingGivenToken()
    {
        $nonce = Braintree\Test\Nonces::$paypalFuturePayment;
        $customer = Braintree\Customer::createNoValidate([
            'paymentMethodNonce' => $nonce
        ]);
        $paypalAccount = $customer->paypalAccounts[0];

        $result = Braintree\PayPalAccount::sale($paypalAccount->token, [
            'amount' => '100.00'
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals('100.00', $result->transaction->amount);
        $this->assertEquals($customer->id, $result->transaction->customerDetails->id);
        $this->assertEquals($paypalAccount->token, $result->transaction->paypalDetails->token);
    }
}
