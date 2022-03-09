<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use DateTimeZone;
use Test\Setup;
use Braintree;

class TransactionAdvancedSearchTest extends Setup
{
    public function testNoMatches()
    {
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::billingFirstName()->is('thisnameisnotreal')
        ]);

        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_noRequestsWhenIterating()
    {
        $resultsReturned = false;
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::billingFirstName()->is('thisnameisnotreal')
        ]);

        foreach($collection as $transaction) {
            $resultsReturned = true;
            break;
        }

        $this->assertSame(0, $collection->maximumCount());
        $this->assertEquals(false, $resultsReturned);
    }

    public function testSearchOnTextFields()
    {
        $firstName  = 'Tim' . rand();
        $token      = 'creditcard' . rand();
        $customerId = 'customer' . rand();

        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012',
                'cardholderName' => 'Tom Smith',
                'token'          => $token,
            ],
            'billing' => [
                'company'         => 'Braintree',
                'countryName'     => 'United States of America',
                'extendedAddress' => 'Suite 123',
                'firstName'       => $firstName,
                'lastName'        => 'Smith',
                'locality'        => 'Chicago',
                'postalCode'      => '12345',
                'region'          => 'IL',
                'streetAddress'   => '123 Main St'
            ],
            'customer' => [
                'company'   => 'Braintree',
                'email'     => 'smith@example.com',
                'fax'       => '5551231234',
                'firstName' => 'Tom',
                'id'        => $customerId,
                'lastName'  => 'Smith',
                'phone'     => '5551231234',
                'website'   => 'http://example.com',
            ],
            'options' => [
                'storeInVault' => true
            ],
            'orderId' => 'myorder',
            'shipping' => [
                'company'         => 'Braintree P.S.',
                'countryName'     => 'Mexico',
                'extendedAddress' => 'Apt 456',
                'firstName'       => 'Thomas',
                'lastName'        => 'Smithy',
                'locality'        => 'Braintree',
                'postalCode'      => '54321',
                'region'          => 'MA',
                'streetAddress'   => '456 Road'
            ],
        ]);

        $search_criteria = [
          'billingCompany' => "Braintree",
          'billingCountryName' => "United States of America",
          'billingExtendedAddress' => "Suite 123",
          'billingFirstName' => $firstName,
          'billingLastName' => "Smith",
          'billingLocality' => "Chicago",
          'billingPostalCode' => "12345",
          'billingRegion' => "IL",
          'billingStreetAddress' => "123 Main St",
          'creditCardCardholderName' => "Tom Smith",
          'creditCardExpirationDate' => "05/2012",
          'creditCardNumber' => Braintree\Test\CreditCardNumbers::$visa,
          'creditCardUniqueIdentifier' => $transaction->creditCardDetails->uniqueNumberIdentifier,
          'currency' => "USD",
          'customerCompany' => "Braintree",
          'customerEmail' => "smith@example.com",
          'customerFax' => "5551231234",
          'customerFirstName' => "Tom",
          'customerId' => $customerId,
          'customerLastName' => "Smith",
          'customerPhone' => "5551231234",
          'customerWebsite' => "http://example.com",
          'orderId' => "myorder",
          'paymentMethodToken' => $token,
          'paymentInstrumentType' => 'CreditCardDetail',
          'processorAuthorizationCode' => $transaction->processorAuthorizationCode,
          'shippingCompany' => "Braintree P.S.",
          'shippingCountryName' => "Mexico",
          'shippingExtendedAddress' => "Apt 456",
          'shippingFirstName' => "Thomas",
          'shippingLastName' => "Smithy",
          'shippingLocality' => "Braintree",
          'shippingPostalCode' => "54321",
          'shippingRegion' => "MA",
          'shippingStreetAddress' => "456 Road",
          'user' => "integration_user_public_id"
        ];

        $query = [Braintree\TransactionSearch::id()->is($transaction->id)];
        foreach ($search_criteria AS $criterion => $value) {
            $query[] = Braintree\TransactionSearch::$criterion()->is($value);
        }

        $collection = Braintree\Transaction::search($query);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        foreach ($search_criteria AS $criterion => $value) {
            $collection = Braintree\Transaction::search([
                Braintree\TransactionSearch::$criterion()->is($value),
                Braintree\TransactionSearch::id()->is($transaction->id)
            ]);
            $this->assertEquals(1, $collection->maximumCount());
            $this->assertEquals($transaction->id, $collection->firstItem()->id);

            $collection = Braintree\Transaction::search([
                Braintree\TransactionSearch::$criterion()->is('invalid_attribute'),
                Braintree\TransactionSearch::id()->is($transaction->id)
            ]);
            $this->assertEquals(0, $collection->maximumCount());
        }
    }

    public function testIs()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012',
                'cardholderName' => 'tom smith'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->is('tom smith')
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->is('somebody else')
        ]);

        $this->assertEquals(0, $collection->maximumCount());
    }

    public function testIsNot()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012',
                'cardholderName' => 'tom smith'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->isNot('somebody else')
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->isNot('tom smith')
        ]);

        $this->assertEquals(0, $collection->maximumCount());
    }

    public function testEndsWith()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012',
                'cardholderName' => 'tom smith'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->endsWith('m smith')
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->endsWith('tom s')
        ]);

        $this->assertEquals(0, $collection->maximumCount());
    }

    public function testStartsWith()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012',
                'cardholderName' => 'tom smith'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->startsWith('tom s')
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->startsWith('m smith')
        ]);

        $this->assertEquals(0, $collection->maximumCount());
    }

    public function testContains()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012',
                'cardholderName' => 'tom smith'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->contains('m sm')
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardholderName()->contains('something else')
        ]);

        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_multipleValueNode_createdUsing()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::createdUsing()->is(Braintree\Transaction::FULL_INFORMATION)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::createdUsing()->in(
                [Braintree\Transaction::FULL_INFORMATION, Braintree\Transaction::TOKEN]
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::createdUsing()->in([Braintree\Transaction::TOKEN])
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_multipleValueNode_paymentInstrumentType_is_creditCard()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::paymentInstrumentType()->is("CreditCardDetail")
        ]);


        $this->assertEquals($transaction->paymentInstrumentType, Braintree\PaymentInstrumentType::CREDIT_CARD);
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_multipleValueNode_paymentInstrumentType_is_paypal()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => Braintree\Test\Nonces::$paypalOneTimePayment
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::paymentInstrumentType()->is("PayPalDetail")
        ]);


        $this->assertEquals($transaction->paymentInstrumentType, Braintree\PaymentInstrumentType::PAYPAL_ACCOUNT);
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_multipleValueNode_paymentInstrumentType_is_applepay()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => Braintree\Test\Nonces::$applePayVisa
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::paymentInstrumentType()->is("ApplePayDetail")
        ]);


        $this->assertEquals($transaction->paymentInstrumentType, Braintree\PaymentInstrumentType::APPLE_PAY_CARD);
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_multipleValueNode_createdUsing_allowedValues()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid argument(s) for created_using: noSuchCreatedUsing');
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::createdUsing()->is('noSuchCreatedUsing')
        ]);
    }

    public function test_multipleValueNode_creditCardCustomerLocation()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCustomerLocation()->is(Braintree\CreditCard::US)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCustomerLocation()->in(
                [Braintree\CreditCard::US, Braintree\CreditCard::INTERNATIONAL]
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCustomerLocation()->in([Braintree\CreditCard::INTERNATIONAL])
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_multipleValueNode_creditCardCustomerLocation_allowedValues()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid argument(s) for credit_card_customer_location: noSuchLocation');
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCustomerLocation()->is('noSuchLocation')
        ]);
    }

    public function test_multipleValueNode_merchantAccountId()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::merchantAccountId()->is($transaction->merchantAccountId)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::merchantAccountId()->in(
                [$transaction->merchantAccountId, "bogus_merchant_account_id"]
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::merchantAccountId()->is('bogus_merchant_account_id')
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_multipleValueNode_creditCardType()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardType()->is($transaction->creditCardDetails->cardType)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardType()->in(
                [$transaction->creditCardDetails->cardType, Braintree\CreditCard::CHINA_UNION_PAY]
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardType()->is(Braintree\CreditCard::CHINA_UNION_PAY)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_elo_creditCardType()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '47.00',
            'creditCard' => [
                'number' => '5066991111111118',
                'expirationMonth' => '10',
                'expirationYear' => '2020',
                'cvv' => '737',
            ],
            'merchantAccountId' => 'adyen_ma',
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::creditCardCardType()->is($transaction->creditCardDetails->cardType)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_multipleValueNode_creditCardType_allowedValues()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid argument(s) for credit_card_card_type: noSuchCardType');
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardType()->is('noSuchCardType')
        ]);
    }

    public function test_multipleValueNode_status()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::status()->is($transaction->status)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::status()->in(
                [$transaction->status, Braintree\Transaction::SETTLED]
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::status()->is(Braintree\Transaction::SETTLED)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_multipleValueNode_status_authorizationExpired()
    {
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::status()->is(Braintree\Transaction::AUTHORIZATION_EXPIRED)
        ]);
        $this->assertGreaterThan(0, $collection->maximumCount());
        $this->assertEquals(Braintree\Transaction::AUTHORIZATION_EXPIRED, $collection->firstItem()->status);
    }

    public function test_multipleValueNode_status_allowedValues()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid argument(s) for status: noSuchStatus');
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::status()->is('noSuchStatus')
        ]);
    }

    public function test_multipleValueNode_source()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'creditCard' => [
                'number'         => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2012'
            ]
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::source()->is(Braintree\Transaction::API)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::source()->in(
                [Braintree\Transaction::API, Braintree\Transaction::RECURRING]
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::source()->is(Braintree\Transaction::RECURRING)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_multipleValueNode_type()
    {
        $customer = Braintree\Customer::createNoValidate();
        $creditCard = Braintree\CreditCard::create([
            'customerId' => $customer->id,
            'cardholderName' => 'Joe Everyman' . rand(),
            'number' => '5105105105105100',
            'expirationDate' => '05/12'
        ])->creditCard;

        $sale = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodToken' => $creditCard->token,
            'options' => ['submitForSettlement' => true]
        ]);
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/transactions/' . $sale->id . '/settle';
        $http->put($path);
        $refund = Braintree\Transaction::refund($sale->id)->transaction;

        $credit = Braintree\Transaction::creditNoValidate([
            'amount' => '100.00',
            'paymentMethodToken' => $creditCard->token
        ]);


        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($creditCard->cardholderName),
            Braintree\TransactionSearch::type()->is($sale->type)
        ]);
        $this->assertEquals(1, $collection->maximumCount());


        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($creditCard->cardholderName),
            Braintree\TransactionSearch::type()->in(
                [$sale->type, $credit->type]
            )
        ]);
        $this->assertEquals(3, $collection->maximumCount());


        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($creditCard->cardholderName),
            Braintree\TransactionSearch::type()->is($credit->type)
        ]);
        $this->assertEquals(2, $collection->maximumCount());
    }

    public function test_multipleValueNode_type_allowedValues()
    {
        $this->setExpectedException('InvalidArgumentException', 'Invalid argument(s) for type: noSuchType');
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::type()->is('noSuchType')
        ]);
    }

    public function test_multipleValueNode_type_withRefund()
    {
        $customer = Braintree\Customer::createNoValidate();
        $creditCard = Braintree\CreditCard::create([
            'customerId' => $customer->id,
            'cardholderName' => 'Joe Everyman' . rand(),
            'number' => '5105105105105100',
            'expirationDate' => '05/12'
        ])->creditCard;

        $sale = Braintree\Transaction::saleNoValidate([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodToken' => $creditCard->token,
            'options' => ['submitForSettlement' => true]
        ]);
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/transactions/' . $sale->id . '/settle';
        $http->put($path);
        $refund = Braintree\Transaction::refund($sale->id)->transaction;

        $credit = Braintree\Transaction::creditNoValidate([
            'amount' => '100.00',
            'paymentMethodToken' => $creditCard->token
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($creditCard->cardholderName),
            Braintree\TransactionSearch::type()->is($credit->type),
            Braintree\TransactionSearch::refund()->is(True)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($refund->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($creditCard->cardholderName),
            Braintree\TransactionSearch::type()->is($credit->type),
            Braintree\TransactionSearch::refund()->is(False)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($credit->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_amount()
    {
        $customer = Braintree\Customer::createNoValidate();
        $creditCard = Braintree\CreditCard::create([
            'customerId' => $customer->id,
            'cardholderName' => 'Jane Everywoman' . rand(),
            'number' => '5105105105105100',
            'expirationDate' => '05/12'
        ])->creditCard;

        $t_1000 = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'paymentMethodToken' => $creditCard->token
        ]);

        $t_1500 = Braintree\Transaction::saleNoValidate([
            'amount' => '1500.00',
            'paymentMethodToken' => $creditCard->token
        ]);

        $t_1800 = Braintree\Transaction::saleNoValidate([
            'amount' => '1800.00',
            'paymentMethodToken' => $creditCard->token
        ]);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($creditCard->cardholderName),
            Braintree\TransactionSearch::amount()->greaterThanOrEqualTo('1700')
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($t_1800->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($creditCard->cardholderName),
            Braintree\TransactionSearch::amount()->lessThanOrEqualTo('1250')
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($t_1000->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($creditCard->cardholderName),
            Braintree\TransactionSearch::amount()->between('1100', '1600')
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($t_1500->id, $collection->firstItem()->id);
    }

    private function runDisbursementDateSearchTests($disbursementDateString, $comparison)
    {
        $knownDepositId = "deposittransaction";
        $now = new DateTime($disbursementDateString);
        $past = clone $now;
        $past->modify("-1 hour");
        $future = clone $now;
        $future->modify("+1 hour");

        $collections = [
            'future' => Braintree\Transaction::search([
                Braintree\TransactionSearch::id()->is($knownDepositId),
                $comparison($future)
            ]),
            'now' => Braintree\Transaction::search([
                Braintree\TransactionSearch::id()->is($knownDepositId),
                $comparison($now)
            ]),
            'past' => Braintree\Transaction::search([
                Braintree\TransactionSearch::id()->is($knownDepositId),
                $comparison($past)
            ])
        ];
        return $collections;
    }

    public function test_rangeNode_disbursementDate_lessThanOrEqualTo()
    {
        $compareLessThan = function($time) {
            return Braintree\TransactionSearch::disbursementDate()->lessThanOrEqualTo($time);
        };
        $collection = $this->runDisbursementDateSearchTests("2013-04-10", $compareLessThan);

        $this->assertEquals(0, $collection['past']->maximumCount());
        $this->assertEquals(1, $collection['now']->maximumCount());
        $this->assertEquals(1, $collection['future']->maximumCount());
    }

    public function test_rangeNode_disbursementDate_GreaterThanOrEqualTo()
    {
        $comparison = function($time) {
            return Braintree\TransactionSearch::disbursementDate()->GreaterThanOrEqualTo($time);
        };
        $collection = $this->runDisbursementDateSearchTests("2013-04-11", $comparison);

        $this->assertEquals(1, $collection['past']->maximumCount());
        $this->assertEquals(0, $collection['now']->maximumCount());
        $this->assertEquals(0, $collection['future']->maximumCount());
    }

    public function test_rangeNode_disbursementDate_between()
    {
        $knownId = "deposittransaction";

        $now = new DateTime("2013-04-10");
        $past = clone $now;
        $past->modify("-1 day");
        $future = clone $now;
        $future->modify("+1 day");
        $future2 = clone $now;
        $future2->modify("+2 days");

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disbursementDate()->between($past, $future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($knownId, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disbursementDate()->between($now, $future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($knownId, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disbursementDate()->between($past, $now)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($knownId, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disbursementDate()->between($future, $future2)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_rangeNode_disbursementDate_is()
    {
        $knownId = "deposittransaction";

        $now = new DateTime("2013-04-10");
        $past = clone $now;
        $past->modify("-1 day");
        $future = clone $now;
        $future->modify("+1 day");
        $future2 = clone $now;
        $future2->modify("+2 days");

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disbursementDate()->is($past)
        ]);
        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disbursementDate()->is($now)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($knownId, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disbursementDate()->is($future)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    private static $disputedTransaction = null;

    private function createTestDisputedTransaction()
    {
        if(self::$disputedTransaction !== null) {
            return self::$disputedTransaction;
        }

        $result = Braintree\Transaction::sale([
            'amount' => '100.00',
            'creditCard' => [
                'number' => Braintree\Test\CreditCardNumbers::$disputes['Chargeback'],
                'expirationDate' => '12/2019',
            ]
        ]);
        self::$disputedTransaction = $result->transaction;

        for($i = 0; $i < 60; $i++) {
            sleep(1);

            $collection = Braintree\Transaction::search([
                Braintree\TransactionSearch::id()->is($result->transaction->id),
                Braintree\TransactionSearch::disputeDate()->is($result->transaction->disputes[0]->receivedDate)
            ]);

            if($collection->maximumCount() > 0) {
                return self::$disputedTransaction;
            }
        }

        throw new \Exception('Unable to find the disputed transaction.');
    }

    private function rundisputeDateSearchTests($comparison)
    {
        $transactionId = $this->createTestDisputedTransaction()->id;
        $disputeDate = $this->createTestDisputedTransaction()->disputes[0]->receivedDate;

        $past = clone $disputeDate;
        $past->modify("-1 day");
        $future = clone $disputeDate;
        $future->modify("+1 day");

        $collections = [
            'future' => Braintree\Transaction::search([
                Braintree\TransactionSearch::id()->is($transactionId),
                $comparison($future)
            ]),
            'now' => Braintree\Transaction::search([
                Braintree\TransactionSearch::id()->is($transactionId),
                $comparison($disputeDate)
            ]),
            'past' => Braintree\Transaction::search([
                Braintree\TransactionSearch::id()->is($transactionId),
                $comparison($past)
            ])
        ];
        return $collections;
    }

    public function test_rangeNode_disputeDate_lessThanOrEqualTo()
    {
        $compareLessThan = function($time) {
            return Braintree\TransactionSearch::disputeDate()->lessThanOrEqualTo($time);
        };

        $collection = $this->rundisputeDateSearchTests($compareLessThan);

        $this->assertEquals(0, $collection['past']->maximumCount());
        $this->assertEquals(1, $collection['now']->maximumCount());
        $this->assertEquals(1, $collection['future']->maximumCount());
    }

    public function test_rangeNode_disputeDate_GreaterThanOrEqualTo()
    {
        $comparison = function($time) {
            return Braintree\TransactionSearch::disputeDate()->GreaterThanOrEqualTo($time);
        };

        $collection = $this->rundisputeDateSearchTests($comparison);

        $this->assertEquals(1, $collection['past']->maximumCount());
        $this->assertEquals(1, $collection['now']->maximumCount());
        $this->assertEquals(0, $collection['future']->maximumCount());
    }

    public function test_rangeNode_disputeDate_between()
    {
        $disputedTransaction = $this->createTestDisputedTransaction();
        $knownId = $disputedTransaction->id;
        $receivedDate = $disputedTransaction->disputes[0]->receivedDate;

        $past = clone $receivedDate;
        $past->modify("-1 day");
        $future = clone $receivedDate;
        $future->modify("+1 day");
        $future2 = clone $receivedDate;
        $future2->modify("+2 days");

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disputeDate()->between($past, $future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($knownId, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disputeDate()->between($receivedDate, $future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($knownId, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disputeDate()->between($past, $receivedDate)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($knownId, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disputeDate()->between($future, $future2)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_rangeNode_disputeDate_is()
    {
        $disputedTransaction = $this->createTestDisputedTransaction();
        $knownId = $disputedTransaction->id;
        $receivedDate = $disputedTransaction->disputes[0]->receivedDate;

        $past = clone $receivedDate;
        $past->modify("-1 day");
        $future = clone $receivedDate;
        $future->modify("+1 day");

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disputeDate()->is($past)
        ]);
        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disputeDate()->is($receivedDate)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($knownId, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($knownId),
            Braintree\TransactionSearch::disputeDate()->is($future)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_rangeNode_createdAt_lessThanOrEqualTo()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'cardholderName' => 'Ted Everywoman' . rand(),
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $past = clone $transaction->createdAt;
        $past->modify("-1 hour");
        $now = $transaction->createdAt;
        $future = clone $transaction->createdAt;
        $future->modify("+1 hour");

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->lessThanOrEqualTo($future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->lessThanOrEqualTo($now)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->lessThanOrEqualTo($past)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_rangeNode_createdAt_GreaterThanOrEqualTo()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'cardholderName' => 'Ted Everyman' . rand(),
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $past = clone $transaction->createdAt;
        $past->modify("-1 hour");
        $now = $transaction->createdAt;
        $future = clone $transaction->createdAt;
        $future->modify("+1 hour");

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->GreaterThanOrEqualTo($future)
        ]);
        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->GreaterThanOrEqualTo($now)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->GreaterThanOrEqualTo($past)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }



    public function test_rangeNode_createdAt_between()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'cardholderName' => 'Ted Everyman' . rand(),
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $past = clone $transaction->createdAt;
        $past->modify("-1 hour");
        $now = $transaction->createdAt;
        $future = clone $transaction->createdAt;
        $future->modify("+1 hour");
        $future2 = clone $transaction->createdAt;
        $future2->modify("+1 day");

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->between($past, $future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->between($now, $future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->between($past, $now)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->between($future, $future2)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_rangeNode_createdAt_is()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'cardholderName' => 'Ted Everyman' . rand(),
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);
        $past = clone $transaction->createdAt;
        $past->modify("-1 hour");
        $now = $transaction->createdAt;
        $future = clone $transaction->createdAt;
        $future->modify("+1 hour");

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->is($future)
        ]);
        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->is($now)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardCardholderName()->is($transaction->creditCardDetails->cardholderName),
            Braintree\TransactionSearch::createdAt()->is($past)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_rangeNode_createdAt_convertLocalToUTC()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'cardholderName' => 'Pingu Penguin' . rand(),
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("US/Pacific"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("US/Pacific"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::createdAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_createdAt_handlesUTCDateTimes()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'cardholderName' => 'Pingu Penguin' . rand(),
                'number' => '5105105105105100',
                'expirationDate' => '05/12'
            ]
        ]);

        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::createdAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_authorizationExpiredAt()
    {
        $two_days_ago = date_create("now -2 days", new DateTimeZone("UTC"));
        $yesterday = date_create("now -1 day", new DateTimeZone("UTC"));
        $tomorrow = date_create("now +1 day", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::authorizationExpiredAt()->between($two_days_ago, $yesterday)
        ]);

        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::authorizationExpiredAt()->between($yesterday, $tomorrow)
        ]);

        $this->assertGreaterThan(0, $collection->maximumCount());
        $this->assertEquals(Braintree\Transaction::AUTHORIZATION_EXPIRED, $collection->firstItem()->status);
    }

    public function test_rangeNode_authorizedAt()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/12'
            ]
        ]);

        $twenty_min_ago = date_create("now -20 minutes", new DateTimeZone("UTC"));
        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::authorizedAt()->between($twenty_min_ago, $ten_min_ago)
        ]);

        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::authorizedAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_failedAt()
    {
        $transaction = Braintree\Transaction::sale([
            'amount' => '3000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/12'
            ]
        ])->transaction;

        $twenty_min_ago = date_create("now -20 minutes", new DateTimeZone("UTC"));
        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::failedAt()->between($twenty_min_ago, $ten_min_ago)
        ]);

        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::failedAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_gatewayRejectedAt()
    {
        $old_merchant_id = Braintree\Configuration::merchantId();
        $old_public_key = Braintree\Configuration::publicKey();
        $old_private_key = Braintree\Configuration::privateKey();

        Braintree\Configuration::merchantId('processing_rules_merchant_id');
        Braintree\Configuration::publicKey('processing_rules_public_key');
        Braintree\Configuration::privateKey('processing_rules_private_key');

        $transaction = Braintree\Transaction::sale([
            'amount' => '1000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/12',
                'cvv' => '200'
            ]
        ])->transaction;

        $twenty_min_ago = date_create("now -20 minutes", new DateTimeZone("UTC"));
        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::gatewayRejectedAt()->between($twenty_min_ago, $ten_min_ago)
        ]);

        $firstCount = $collection->maximumCount();

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::gatewayRejectedAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $secondCount = $collection->maximumCount();
        $firstId = $collection->firstItem()->id;

        Braintree\Configuration::merchantId($old_merchant_id);
        Braintree\Configuration::publicKey($old_public_key);
        Braintree\Configuration::privateKey($old_private_key);

        $this->assertEquals(0, $firstCount);
        $this->assertEquals(1, $secondCount);
        $this->assertEquals($transaction->id, $firstId);
    }

    public function test_rangeNode_processorDeclinedAt()
    {
        $transaction = Braintree\Transaction::sale([
            'amount' => '2000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/12'
            ]
        ])->transaction;

        $twenty_min_ago = date_create("now -20 minutes", new DateTimeZone("UTC"));
        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::processorDeclinedAt()->between($twenty_min_ago, $ten_min_ago)
        ]);

        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::processorDeclinedAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_settledAt()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/12'
            ],
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/transactions/' . $transaction->id . '/settle';
        $http->put($path);
        $transaction = Braintree\Transaction::find($transaction->id);

        $twenty_min_ago = date_create("now -20 minutes", new DateTimeZone("UTC"));
        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::settledAt()->between($twenty_min_ago, $ten_min_ago)
        ]);

        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::settledAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_submittedForSettlementAt()
    {
        $transaction = Braintree\Transaction::sale([
            'amount' => '1000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/12'
            ],
            'options' => [
                'submitForSettlement' => true
            ]
        ])->transaction;

        $twenty_min_ago = date_create("now -20 minutes", new DateTimeZone("UTC"));
        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::submittedForSettlementAt()->between($twenty_min_ago, $ten_min_ago)
        ]);

        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::submittedForSettlementAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_voidedAt()
    {
        $transaction = Braintree\Transaction::saleNoValidate([
            'amount' => '1000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/12'
            ]
        ]);

        $transaction = Braintree\Transaction::void($transaction->id)->transaction;

        $twenty_min_ago = date_create("now -20 minutes", new DateTimeZone("UTC"));
        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::voidedAt()->between($twenty_min_ago, $ten_min_ago)
        ]);

        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::voidedAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_rangeNode_canSearchOnMultipleStatuses()
    {
        $transaction = Braintree\Transaction::sale([
            'amount' => '1000.00',
            'creditCard' => [
                'number' => '4111111111111111',
                'expirationDate' => '05/12'
            ],
            'options' => [
                'submitForSettlement' => true
            ]
        ])->transaction;

        $twenty_min_ago = date_create("now -20 minutes", new DateTimeZone("UTC"));
        $ten_min_ago = date_create("now -10 minutes", new DateTimeZone("UTC"));
        $ten_min_from_now = date_create("now +10 minutes", new DateTimeZone("UTC"));

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::authorizedAt()->between($twenty_min_ago, $ten_min_ago),
            Braintree\TransactionSearch::submittedForSettlementAt()->between($twenty_min_ago, $ten_min_ago)
        ]);

        $this->assertEquals(0, $collection->maximumCount());

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::id()->is($transaction->id),
            Braintree\TransactionSearch::authorizedAt()->between($ten_min_ago, $ten_min_from_now),
            Braintree\TransactionSearch::submittedForSettlementAt()->between($ten_min_ago, $ten_min_from_now)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($transaction->id, $collection->firstItem()->id);
    }

    public function test_advancedSearchGivesIterableResult()
    {
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::creditCardNumber()->startsWith("411111")
        ]);
        $this->assertGreaterThan(100, $collection->maximumCount());

        $arr = [];
        foreach($collection as $transaction) {
            array_push($arr, $transaction->id);
        }
        $unique_transaction_ids = array_unique(array_values($arr));
        $this->assertEquals($collection->maximumCount(), count($unique_transaction_ids));
    }

    public function test_handles_search_timeout()
    {
        $this->setExpectedException('Braintree\Exception\DownForMaintenance');
        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::amount()->is('-5')
        ]);
    }

    public function testHandlesPayPalAccounts()
    {
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'access_token' => 'PAYPAL_ACCESS_TOKEN'
            ]
        ]);

        $result = Braintree\Transaction::sale([
            'amount' => Braintree\Test\TransactionAmounts::$authorize,
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);
        $paypalDetails = $result->transaction->paypalDetails;

        $collection = Braintree\Transaction::search([
            Braintree\TransactionSearch::paypalPaymentId()->is($paypalDetails->paymentId),
            Braintree\TransactionSearch::paypalAuthorizationId()->is($paypalDetails->authorizationId),
            Braintree\TransactionSearch::paypalPayerEmail()->is($paypalDetails->payerEmail)
        ]);

        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($result->transaction->id, $collection->firstItem()->id);
    }
}
