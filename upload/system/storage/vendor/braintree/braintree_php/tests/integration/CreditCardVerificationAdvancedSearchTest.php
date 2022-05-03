<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class CreditCardVerificationAdvancedSearchTest extends Setup
{
    public function test_searchOnTextFields()
    {
        $searchCriteria = [
            'creditCardCardholderName' => 'Tim Toole',
            'creditCardExpirationDate' => '05/2010',
            'creditCardNumber' => Braintree\Test\CreditCardNumbers::$failsSandboxVerification['Visa'],
            'billingAddressDetailsPostalCode' => '90210',
        ];
        $result = Braintree\Customer::create([
            'creditCard' => [
                'cardholderName' => $searchCriteria['creditCardCardholderName'],
                'number' => $searchCriteria['creditCardNumber'],
                'expirationDate' => $searchCriteria['creditCardExpirationDate'],
                'billingAddress' => [
                    'postalCode' => $searchCriteria['billingAddressDetailsPostalCode']
                ],
                'options' => ['verifyCard' => true],
            ],
        ]);
        $verification = $result->creditCardVerification;

        $query = [Braintree\CreditCardVerificationSearch::id()->is($verification->id)];
        foreach ($searchCriteria AS $criterion => $value) {
            $query[] = Braintree\CreditCardVerificationSearch::$criterion()->is($value);
        }

        $collection = Braintree\CreditCardVerification::search($query);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($result->creditCardVerification->id, $collection->firstItem()->id);

        foreach ($searchCriteria AS $criterion => $value) {
            $collection = Braintree\CreditCardVerification::search([
                Braintree\CreditCardVerificationSearch::$criterion()->is($value),
                Braintree\CreditCardVerificationSearch::id()->is($result->creditCardVerification->id)
            ]);
            $this->assertEquals(1, $collection->maximumCount());
            $this->assertEquals($result->creditCardVerification->id, $collection->firstItem()->id);

            $collection = Braintree\CreditCardVerification::search([
                Braintree\CreditCardVerificationSearch::$criterion()->is('invalid_attribute'),
                Braintree\CreditCardVerificationSearch::id()->is($result->creditCardVerification->id)
            ]);
            $this->assertEquals(0, $collection->maximumCount());
        }
    }

    public function test_searchOnSuccessfulCustomerAndPaymentFields()
    {
        $customerId = uniqid();
        $searchCriteria = [
            'customerId' => $customerId,
            'customerEmail' => $customerId . 'sandworm@example.com',
            'paymentMethodToken' => $customerId . 'token',
        ];
        $result = Braintree\Customer::create([
            'id' => $customerId,
            'email' => $searchCriteria['customerEmail'],
            'creditCard' => [
                'token' => $searchCriteria['paymentMethodToken'],
                'number' => Braintree\Test\CreditCardNumbers::$visa,
                'expirationDate' => '05/2017',
                'options' => ['verifyCard' => true]
            ]
        ]);
        $customer = $result->customer;

        $query = [];
        foreach ($searchCriteria AS $criterion => $value) {
            $query[] = Braintree\CreditCardVerificationSearch::$criterion()->is($value);
        }

        $collection = Braintree\CreditCardVerification::search($query);
        $this->assertEquals(1, $collection->maximumCount());

        foreach ($searchCriteria AS $criterion => $value) {
            $collection = Braintree\CreditCardVerification::search([
                Braintree\CreditCardVerificationSearch::$criterion()->is($value),
            ]);
            $this->assertEquals(1, $collection->maximumCount());

            $collection = Braintree\CreditCardVerification::search([
                Braintree\CreditCardVerificationSearch::$criterion()->is('invalid_attribute'),
            ]);
            $this->assertEquals(0, $collection->maximumCount());
        }
    }

    public function testGateway_searchEmpty()
    {
        $query = [];
        $query[] = Braintree\CreditCardVerificationSearch::creditCardCardholderName()->is('Not Found');

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $collection = $gateway->creditCardVerification()->search($query);

        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_createdAt()
    {
        $result = Braintree\Customer::create([
            'creditCard' => [
                'cardholderName' => 'Joe Smith',
                'number' => '4000111111111115',
                'expirationDate' => '12/2016',
                'options' => ['verifyCard' => true],
            ],
        ]);

        $verification = $result->creditCardVerification;

        $past = clone $verification->createdAt;
        $past->modify('-1 hour');
        $future = clone $verification->createdAt;
        $future->modify('+1 hour');

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($verification->id),
            Braintree\CreditCardVerificationSearch::createdAt()->between($past, $future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($verification->id, $collection->firstItem()->id);

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($verification->id),
            Braintree\CreditCardVerificationSearch::createdAt()->lessThanOrEqualTo($future)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($verification->id, $collection->firstItem()->id);

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($verification->id),
            Braintree\CreditCardVerificationSearch::createdAt()->greaterThanOrEqualTo($past)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($verification->id, $collection->firstItem()->id);
    }

    public function test_multipleValueNode_ids()
    {
        $result = Braintree\Customer::create([
            'creditCard' => [
                'cardholderName' => 'Joe Smith',
                'number' => '4000111111111115',
                'expirationDate' => '12/2016',
                'options' => ['verifyCard' => true],
            ],
        ]);

        $creditCardVerification = $result->creditCardVerification;

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::ids()->is($creditCardVerification->id)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($creditCardVerification->id, $collection->firstItem()->id);

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::ids()->in(
                [$creditCardVerification->id,'1234']
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($creditCardVerification->id, $collection->firstItem()->id);

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::ids()->is('1234')
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_multipleValueNode_creditCardType()
    {
        $result = Braintree\Customer::create([
            'creditCard' => [
                'cardholderName' => 'Joe Smith',
                'number' => '4000111111111115',
                'expirationDate' => '12/2016',
                'options' => ['verifyCard' => true],
            ],
        ]);

        $creditCardVerification = $result->creditCardVerification;

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($creditCardVerification->id),
            Braintree\CreditCardVerificationSearch::creditCardCardType()->is($creditCardVerification->creditCard['cardType'])
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($creditCardVerification->id, $collection->firstItem()->id);

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($creditCardVerification->id),
            Braintree\CreditCardVerificationSearch::creditCardCardType()->in(
                [$creditCardVerification->creditCard['cardType'], Braintree\CreditCard::CHINA_UNION_PAY]
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($creditCardVerification->id, $collection->firstItem()->id);

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($creditCardVerification->id),
            Braintree\CreditCardVerificationSearch::creditCardCardType()->is(Braintree\CreditCard::CHINA_UNION_PAY)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }

    public function test_multipleValueNode_status()
    {
        $result = Braintree\Customer::create([
            'creditCard' => [
                'cardholderName' => 'Joe Smith',
                'number' => '4000111111111115',
                'expirationDate' => '12/2016',
                'options' => ['verifyCard' => true],
            ],
        ]);

        $creditCardVerification = $result->creditCardVerification;

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($creditCardVerification->id),
            Braintree\CreditCardVerificationSearch::status()->is($creditCardVerification->status)
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($creditCardVerification->id, $collection->firstItem()->id);

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($creditCardVerification->id),
            Braintree\CreditCardVerificationSearch::status()->in(
                [$creditCardVerification->status, Braintree\Result\CreditCardVerification::VERIFIED]
            )
        ]);
        $this->assertEquals(1, $collection->maximumCount());
        $this->assertEquals($creditCardVerification->id, $collection->firstItem()->id);

        $collection = Braintree\CreditCardVerification::search([
            Braintree\CreditCardVerificationSearch::id()->is($creditCardVerification->id),
            Braintree\CreditCardVerificationSearch::status()->is(Braintree\Result\CreditCardVerification::VERIFIED)
        ]);
        $this->assertEquals(0, $collection->maximumCount());
    }
}
