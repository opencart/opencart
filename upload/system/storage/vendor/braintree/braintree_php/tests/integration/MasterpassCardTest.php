<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Braintree\CreditCardNumbers\CardTypeIndicators;
use Test\Setup;
use Braintree;

class MasterpassCardTest extends Setup
{
    public function testCreateWithMasterpassCardNonce()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$masterpassDiscover,
        ]);

        $this->assertTrue($result->success);
        $masterpassCard = $result->paymentMethod;
        $this->assertSame(Braintree\CreditCard::DISCOVER, $masterpassCard->cardType);
        $this->assertTrue($masterpassCard->default);
        $this->assertContains('discover', $masterpassCard->imageUrl);
        $this->assertTrue(intval($masterpassCard->expirationMonth) > 0);
        $this->assertTrue(intval($masterpassCard->expirationYear) > 0);
        $this->assertSame($customer->id, $masterpassCard->customerId);
        $this->assertSame($masterpassCard->last4, '1117');
        $this->assertSame($masterpassCard->maskedNumber, '601111******1117');

        $this->assertNotNull($masterpassCard->billingAddress);
        $this->assertNotNull($masterpassCard->bin);
        $this->assertNotNull($masterpassCard->cardType);
        $this->assertNotNull($masterpassCard->cardholderName);
        $this->assertNotNull($masterpassCard->commercial);
        $this->assertNotNull($masterpassCard->countryOfIssuance);
        $this->assertNotNull($masterpassCard->createdAt);
        $this->assertNotNull($masterpassCard->customerId);
        $this->assertNotNull($masterpassCard->customerLocation);
        $this->assertNotNull($masterpassCard->debit);
        $this->assertNotNull($masterpassCard->default);
        $this->assertNotNull($masterpassCard->durbinRegulated);
        $this->assertNotNull($masterpassCard->expirationDate);
        $this->assertNotNull($masterpassCard->expirationMonth);
        $this->assertNotNull($masterpassCard->expirationYear);
        $this->assertNotNull($masterpassCard->expired);
        $this->assertNotNull($masterpassCard->healthcare);
        $this->assertNotNull($masterpassCard->imageUrl);
        $this->assertNotNull($masterpassCard->issuingBank);
        $this->assertNotNull($masterpassCard->last4);
        $this->assertNotNull($masterpassCard->maskedNumber);
        $this->assertNotNull($masterpassCard->payroll);
        $this->assertNotNull($masterpassCard->prepaid);
        $this->assertNotNull($masterpassCard->productId);
        $this->assertNotNull($masterpassCard->subscriptions);
        $this->assertNotNull($masterpassCard->token);
        $this->assertNotNull($masterpassCard->uniqueNumberIdentifier);
        $this->assertNotNull($masterpassCard->updatedAt);
    }

    public function testTransactionSearchWithMasterpass()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => Braintree\Test\Nonces::$masterpassDiscover,
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::paymentInstrumentType()->is(Braintree\PaymentInstrumentType::MASTERPASS_CARD)
        ]);


        $this->assertEquals($transaction->paymentInstrumentType, Braintree\PaymentInstrumentType::MASTERPASS_CARD);
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function testCreateCustomerwithMasterpassCard()
    {
        $nonce = Braintree\Test\Nonces::$masterpassDiscover;
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->masterpassCards[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
    }

    public function testCreateTransactionWithMasterpassNonceAndVault()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$masterpassAmEx,
            'options' => [
                'storeInVault' => true
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('47.00', $transaction->amount);
        $masterpassCardDetails = $transaction->masterpassCardDetails;
        $this->assertSame(Braintree\CreditCard::AMEX, $masterpassCardDetails->cardType);

        $this->assertNotNull($masterpassCardDetails->bin);
        $this->assertNotNull($masterpassCardDetails->cardType);
        $this->assertNotNull($masterpassCardDetails->cardholderName);
        $this->assertNotNull($masterpassCardDetails->commercial);
        $this->assertNotNull($masterpassCardDetails->countryOfIssuance);
        $this->assertNotNull($masterpassCardDetails->customerLocation);
        $this->assertNotNull($masterpassCardDetails->debit);
        $this->assertNotNull($masterpassCardDetails->durbinRegulated);
        $this->assertNotNull($masterpassCardDetails->expirationDate);
        $this->assertNotNull($masterpassCardDetails->expirationMonth);
        $this->assertNotNull($masterpassCardDetails->expirationYear);
        $this->assertNotNull($masterpassCardDetails->healthcare);
        $this->assertNotNull($masterpassCardDetails->imageUrl);
        $this->assertNotNull($masterpassCardDetails->issuingBank);
        $this->assertNotNull($masterpassCardDetails->last4);
        $this->assertNotNull($masterpassCardDetails->maskedNumber);
        $this->assertNotNull($masterpassCardDetails->payroll);
        $this->assertNotNull($masterpassCardDetails->prepaid);
        $this->assertNotNull($masterpassCardDetails->productId);
        $this->assertNotNull($masterpassCardDetails->token);
    }
}
