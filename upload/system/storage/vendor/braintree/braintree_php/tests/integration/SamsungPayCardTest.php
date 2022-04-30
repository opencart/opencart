<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Braintree\CreditCardNumbers\CardTypeIndicators;
use Test\Setup;
use Braintree;

class SamsungPayCardTest extends Setup
{
    public function testCreateWithSamsungPayCardNonce()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$samsungPayDiscover,
            'cardholderName' => 'Jenny Block',
            'billingAddress' => [
                'firstName' => 'Drew',
                'lastName' => 'Smith',
                'company' => 'Smith Co.',
                'streetAddress' => '1 E Main St',
                'extendedAddress' => 'Suite 101',
                'locality' => 'Chicago',
                'region' => 'IL',
                'postalCode' => '60622',
                'countryName' => 'Micronesia',
                'countryCodeAlpha2' => 'FM',
                'countryCodeAlpha3' => 'FSM',
                'countryCodeNumeric' => '583'
            ]
        ]);

        $this->assertTrue($result->success);
        $samsungPayCard = $result->paymentMethod;
        $this->assertNotNull($samsungPayCard->token);
        $this->assertSame(Braintree\CreditCard::DISCOVER, $samsungPayCard->cardType);
        $this->assertTrue($samsungPayCard->default);
        $this->assertContains('discover', $samsungPayCard->imageUrl);
        $this->assertTrue(intval($samsungPayCard->expirationMonth) > 0);
        $this->assertTrue(intval($samsungPayCard->expirationYear) > 0);
        $this->assertSame($customer->id, $samsungPayCard->customerId);
        $this->assertSame($samsungPayCard->last4, '1117');
        $this->assertSame($samsungPayCard->maskedNumber, '601111******1117');

        $this->assertNotNull($samsungPayCard->billingAddress);
        $this->assertNotNull($samsungPayCard->bin);
        $this->assertNotNull($samsungPayCard->cardholderName);
        $this->assertNotNull($samsungPayCard->cardType);
        $this->assertNotNull($samsungPayCard->commercial);
        $this->assertNotNull($samsungPayCard->countryOfIssuance);
        $this->assertNotNull($samsungPayCard->createdAt);
        $this->assertNotNull($samsungPayCard->customerId);
        $this->assertNotNull($samsungPayCard->customerLocation);
        $this->assertNotNull($samsungPayCard->debit);
        $this->assertNotNull($samsungPayCard->default);
        $this->assertNotNull($samsungPayCard->durbinRegulated);
        $this->assertNotNull($samsungPayCard->expirationDate);
        $this->assertNotNull($samsungPayCard->expirationMonth);
        $this->assertNotNull($samsungPayCard->expirationYear);
        $this->assertNotNull($samsungPayCard->expired);
        $this->assertNotNull($samsungPayCard->healthcare);
        $this->assertNotNull($samsungPayCard->imageUrl);
        $this->assertNotNull($samsungPayCard->issuingBank);
        $this->assertNotNull($samsungPayCard->last4);
        $this->assertNotNull($samsungPayCard->maskedNumber);
        $this->assertNotNull($samsungPayCard->payroll);
        $this->assertNotNull($samsungPayCard->prepaid);
        $this->assertNotNull($samsungPayCard->productId);
        $this->assertNotNull($samsungPayCard->sourceCardLast4);
        $this->assertNotNull($samsungPayCard->subscriptions);
        $this->assertNotNull($samsungPayCard->token);
        $this->assertNotNull($samsungPayCard->uniqueNumberIdentifier);
        $this->assertNotNull($samsungPayCard->updatedAt);
    }

    public function testCreateWithNameAndAddress()
    {
        $customer = Braintree\Customer::createNoValidate();
        $result = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => Braintree\Test\Nonces::$samsungPayDiscover,
            'cardholderName' => 'Jenny Block',
            'billingAddress' => [
                'firstName' => 'Drew',
                'lastName' => 'Smith',
                'company' => 'Smith Co.',
                'streetAddress' => '1 E Main St',
                'extendedAddress' => 'Suite 101',
                'locality' => 'Chicago',
                'region' => 'IL',
                'postalCode' => '60622',
                'countryName' => 'Micronesia',
                'countryCodeAlpha2' => 'FM',
                'countryCodeAlpha3' => 'FSM',
                'countryCodeNumeric' => '583'
            ]
        ]);

        $this->assertTrue($result->success);
        $samsungPayCard = $result->paymentMethod;
        $this->assertEquals($samsungPayCard->cardholderName, 'Jenny Block');

        $address = $samsungPayCard->billingAddress;
        $this->assertEquals('Drew', $address->firstName);
        $this->assertEquals('Smith', $address->lastName);
        $this->assertEquals('Smith Co.', $address->company);
        $this->assertEquals('1 E Main St', $address->streetAddress);
        $this->assertEquals('Suite 101', $address->extendedAddress);
        $this->assertEquals('Chicago', $address->locality);
        $this->assertEquals('IL', $address->region);
        $this->assertEquals('60622', $address->postalCode);
        $this->assertEquals('Micronesia', $address->countryName);
        $this->assertEquals('FM', $address->countryCodeAlpha2);
        $this->assertEquals('FSM', $address->countryCodeAlpha3);
    }

    public function testTransactionSearchWithSamsungPay()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => Braintree\Test\Nonces::$samsungPayDiscover,
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::paymentInstrumentType()->is(Braintree\PaymentInstrumentType::SAMSUNG_PAY_CARD)
        ]);


        $this->assertEquals($transaction->paymentInstrumentType, Braintree\PaymentInstrumentType::SAMSUNG_PAY_CARD);
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function testCreateCustomerWithSamsungPayCard()
    {
        $nonce = Braintree\Test\Nonces::$samsungPayDiscover;
        $result = Braintree\Customer::create([
            'paymentMethodNonce' => $nonce
        ]);
        $this->assertTrue($result->success);
        $customer = $result->customer;
        $this->assertNotNull($customer->samsungPayCards[0]);
        $this->assertNotNull($customer->paymentMethods[0]);
    }

    public function testCreateTransactionWithSamsungPayNonceAndVault()
    {
        $result = Braintree\Transaction::sale([
            'amount' => '47.00',
            'paymentMethodNonce' => Braintree\Test\Nonces::$samsungPayAmEx,
            'options' => [
                'storeInVault' => true
            ],
            'creditCard' => [
                'cardholderName' => 'Jenny Block'
            ]
        ]);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;
        $this->assertEquals('47.00', $transaction->amount);
        $samsungPayCardDetails = $transaction->samsungPayCardDetails;
        $this->assertSame(Braintree\CreditCard::AMEX, $samsungPayCardDetails->cardType);

        $this->assertNotNull($samsungPayCardDetails->bin);
        $this->assertNotNull($samsungPayCardDetails->cardholderName);
        $this->assertNotNull($samsungPayCardDetails->cardType);
        $this->assertNotNull($samsungPayCardDetails->commercial);
        $this->assertNotNull($samsungPayCardDetails->countryOfIssuance);
        $this->assertNotNull($samsungPayCardDetails->customerLocation);
        $this->assertNotNull($samsungPayCardDetails->debit);
        $this->assertNotNull($samsungPayCardDetails->durbinRegulated);
        $this->assertNotNull($samsungPayCardDetails->expirationDate);
        $this->assertNotNull($samsungPayCardDetails->expirationMonth);
        $this->assertNotNull($samsungPayCardDetails->expirationYear);
        $this->assertNotNull($samsungPayCardDetails->healthcare);
        $this->assertNotNull($samsungPayCardDetails->imageUrl);
        $this->assertNotNull($samsungPayCardDetails->issuingBank);
        $this->assertNotNull($samsungPayCardDetails->last4);
        $this->assertNotNull($samsungPayCardDetails->maskedNumber);
        $this->assertNotNull($samsungPayCardDetails->payroll);
        $this->assertNotNull($samsungPayCardDetails->prepaid);
        $this->assertNotNull($samsungPayCardDetails->productId);
        $this->assertNotNull($samsungPayCardDetails->sourceCardLast4);
        $this->assertNotNull($samsungPayCardDetails->token);
    }
}
