<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Braintree\CreditCardNumbers\CardTypeIndicators;
use Test\Setup;
use Braintree;

class VisaCheckoutCardTest extends Setup
{
    public function testCreateWithVisaCheckoutCardNonce()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$visaCheckoutDiscover,
        ]);

        $this->assertTrue($result->success);
        $visaCheckoutCard = $result->paymentMethod;
        $this->assertNotNull($visaCheckoutCard->token);
        $this->assertSame(Braintree\CreditCard::DISCOVER, $visaCheckoutCard->cardType);
        $this->assertTrue($visaCheckoutCard->default);
        $this->assertContains('discover', $visaCheckoutCard->imageUrl);
        $this->assertTrue(intval($visaCheckoutCard->expirationMonth) > 0);
        $this->assertTrue(intval($visaCheckoutCard->expirationYear) > 0);
        $this->assertSame($customer->id, $visaCheckoutCard->customerId);
        $this->assertSame('abc123', $visaCheckoutCard->callId);
        $this->assertSame($visaCheckoutCard->last4, '1117');
        $this->assertSame($visaCheckoutCard->maskedNumber, '601111******1117');

        $this->assertNotNull($visaCheckoutCard->billingAddress);
        $this->assertNotNull($visaCheckoutCard->bin);
        $this->assertNotNull($visaCheckoutCard->callId);
        $this->assertNotNull($visaCheckoutCard->cardType);
        $this->assertNotNull($visaCheckoutCard->cardholderName);
        $this->assertNotNull($visaCheckoutCard->commercial);
        $this->assertNotNull($visaCheckoutCard->countryOfIssuance);
        $this->assertNotNull($visaCheckoutCard->createdAt);
        $this->assertNotNull($visaCheckoutCard->customerId);
        $this->assertNotNull($visaCheckoutCard->customerLocation);
        $this->assertNotNull($visaCheckoutCard->debit);
        $this->assertNotNull($visaCheckoutCard->default);
        $this->assertNotNull($visaCheckoutCard->durbinRegulated);
        $this->assertNotNull($visaCheckoutCard->expirationDate);
        $this->assertNotNull($visaCheckoutCard->expirationMonth);
        $this->assertNotNull($visaCheckoutCard->expirationYear);
        $this->assertNotNull($visaCheckoutCard->expired);
        $this->assertNotNull($visaCheckoutCard->healthcare);
        $this->assertNotNull($visaCheckoutCard->imageUrl);
        $this->assertNotNull($visaCheckoutCard->issuingBank);
        $this->assertNotNull($visaCheckoutCard->last4);
        $this->assertNotNull($visaCheckoutCard->maskedNumber);
        $this->assertNotNull($visaCheckoutCard->payroll);
        $this->assertNotNull($visaCheckoutCard->prepaid);
        $this->assertNotNull($visaCheckoutCard->productId);
        $this->assertNotNull($visaCheckoutCard->subscriptions);
        $this->assertNotNull($visaCheckoutCard->token);
        $this->assertNotNull($visaCheckoutCard->uniqueNumberIdentifier);
        $this->assertNotNull($visaCheckoutCard->updatedAt);
    }

    public function testCreateWithVisaCheckoutCardNonceWithVerification()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$visaCheckoutDiscover,
            'options' => [
                'verifyCard' => true
            ]
        ]);

        $this->assertTrue($result->success);
        $visaCheckoutCard = $result->paymentMethod;
        $verification = $visaCheckoutCard->verification;

        $this->assertNotNull($verification);
        $this->assertNotNull($verification->status);
    }

    public function testTransactionSearchWithVisaCheckout()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => Braintree\Test\Nonces::$visaCheckoutDiscover,
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::paymentInstrumentType()->is(Braintree\PaymentInstrumentType::VISA_CHECKOUT_CARD)
        ]);


        $this->assertEquals($transaction->paymentInstrumentType, Braintree\PaymentInstrumentType::VISA_CHECKOUT_CARD);
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function testCreateCustomerWithVisaCheckoutCard()
    {
        $nonce = Braintree\Test\Nonces::$visaCheckoutDiscover;
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->visaCheckoutCards[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
    }

    public function testCreateTransactionWithVisaCheckoutNonceAndVault()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$visaCheckoutAmEx,
            'options' => [
                'storeInVault' => true
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('47.00', $transaction->amount);
        $visaCheckoutCardDetails = $transaction->visaCheckoutCardDetails;
        $this->assertSame(Braintree\CreditCard::AMEX, $visaCheckoutCardDetails->cardType);

        $this->assertNotNull($visaCheckoutCardDetails->bin);
        $this->assertNotNull($visaCheckoutCardDetails->callId);
        $this->assertNotNull($visaCheckoutCardDetails->cardType);
        $this->assertNotNull($visaCheckoutCardDetails->cardholderName);
        $this->assertNotNull($visaCheckoutCardDetails->commercial);
        $this->assertNotNull($visaCheckoutCardDetails->countryOfIssuance);
        $this->assertNotNull($visaCheckoutCardDetails->customerLocation);
        $this->assertNotNull($visaCheckoutCardDetails->debit);
        $this->assertNotNull($visaCheckoutCardDetails->durbinRegulated);
        $this->assertNotNull($visaCheckoutCardDetails->expirationDate);
        $this->assertNotNull($visaCheckoutCardDetails->expirationMonth);
        $this->assertNotNull($visaCheckoutCardDetails->expirationYear);
        $this->assertNotNull($visaCheckoutCardDetails->healthcare);
        $this->assertNotNull($visaCheckoutCardDetails->imageUrl);
        $this->assertNotNull($visaCheckoutCardDetails->issuingBank);
        $this->assertNotNull($visaCheckoutCardDetails->last4);
        $this->assertNotNull($visaCheckoutCardDetails->maskedNumber);
        $this->assertNotNull($visaCheckoutCardDetails->payroll);
        $this->assertNotNull($visaCheckoutCardDetails->prepaid);
        $this->assertNotNull($visaCheckoutCardDetails->productId);
        $this->assertNotNull($visaCheckoutCardDetails->token);
    }
}
