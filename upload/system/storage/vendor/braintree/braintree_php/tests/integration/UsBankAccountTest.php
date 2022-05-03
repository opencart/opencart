<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class UsBankAccountAccountTest extends Setup
{


    public function testReturnUsBankAccount()
    {
        $customer = Braintree\Customer::createNoValidate();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = Test\Helper::generateValidUsBankAccountNonce();

        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount()
            ]
        ]);

        $foundUsBankAccount = $result->paymentMethod;
        $this->assertInstanceOf('Braintree\UsBankAccount', $foundUsBankAccount);
        $this->assertEquals('021000021', $foundUsBankAccount->routingNumber);
        $this->assertEquals('1234', $foundUsBankAccount->last4);
        $this->assertEquals('checking', $foundUsBankAccount->accountType);
        $this->assertEquals('Dan Schulman', $foundUsBankAccount->accountHolderName);
        $this->assertRegExp('/CHASE/', $foundUsBankAccount->bankName);
        $this->assertEquals('cl mandate text', $foundUsBankAccount->achMandate->text);
        $this->assertEquals('DateTime', get_class($foundUsBankAccount->achMandate->acceptedAt));
        $this->assertEquals(true, $foundUsBankAccount->default);
    }

    public function testFind()
    {
        $customer = Braintree\Customer::createNoValidate();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = Test\Helper::generateValidUsBankAccountNonce();

        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount()
            ]
        ]);

        $foundUsBankAccount= Braintree\UsBankAccount::find($result->paymentMethod->token);
        $this->assertInstanceOf('Braintree\UsBankAccount', $foundUsBankAccount);
        $this->assertEquals('021000021', $foundUsBankAccount->routingNumber);
        $this->assertEquals('1234', $foundUsBankAccount->last4);
        $this->assertEquals('checking', $foundUsBankAccount->accountType);
        $this->assertEquals('Dan Schulman', $foundUsBankAccount->accountHolderName);
        $this->assertRegExp('/CHASE/', $foundUsBankAccount->bankName);
        $this->assertEquals('cl mandate text', $foundUsBankAccount->achMandate->text);
        $this->assertEquals('DateTime', get_class($foundUsBankAccount->achMandate->acceptedAt));
        $this->assertEquals(true, $foundUsBankAccount->default);
    }

    public function testFind_throwsIfCannotBeFound()
    {
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\UsBankAccount::find(Test\Helper::generateInvalidUsBankAccountNonce());
    }

    public function testSale_createsASaleUsingGivenToken()
    {
        $customer = Braintree\Customer::createNoValidate();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = Test\Helper::generateValidUsBankAccountNonce();

        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'verificationMerchantAccountId' => Test\Helper::usBankMerchantAccount()
            ]
        ]);

        $result = Braintree\UsBankAccount::sale($result->paymentMethod->token, [
            'merchantAccountId' => Test\Helper::usBankMerchantAccount(),
            'amount' => '100.00'
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals(Braintree\Transaction::SETTLEMENT_PENDING, $transaction->status);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals('100.00', $transaction->amount);
        $this->assertEquals('021000021', $transaction->usBankAccount->routingNumber);
        $this->assertEquals('1234', $transaction->usBankAccount->last4);
        $this->assertEquals('checking', $transaction->usBankAccount->accountType);
        $this->assertEquals('Dan Schulman', $transaction->usBankAccount->accountHolderName);
        $this->assertRegExp('/CHASE/', $transaction->usBankAccount->bankName);
        $this->assertEquals('cl mandate text', $transaction->usBankAccount->achMandate->text);
        $this->assertEquals('DateTime', get_class($transaction->usBankAccount->achMandate->acceptedAt));
    }
}
