<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use Test;
use Test\Setup;
use Braintree;

class SubscriptionTest extends Setup
{
    public function testCreate_doesNotAcceptBadAttributes()
    {
        $this->setExpectedException('InvalidArgumentException', 'invalid keys: bad');
        $result = Braintree\Subscription::create([
            'bad' => 'value'
        ]);
    }

    public function testCreate_whenSuccessful()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id']

        ]);
        Test\Helper::assertPrintable($result);
        $this->assertTrue($result->success);
        $subscription = $result->subscription;
        $this->assertEquals($creditCard->token, $subscription->paymentMethodToken);
        $this->assertEquals(0, $subscription->failureCount);
        $this->assertEquals($plan['id'], $subscription->planId);
        $this->assertEquals(Test\Helper::defaultMerchantAccountId(), $subscription->merchantAccountId);
        $this->assertEquals(Braintree\Subscription::ACTIVE, $subscription->status);
        $this->assertEquals('12.34', $subscription->nextBillAmount);
        $this->assertEquals('12.34', $subscription->nextBillingPeriodAmount);
        $this->assertEquals('0.00', $subscription->balance);
        $this->assertEquals(1, $subscription->currentBillingCycle);
        $this->assertInstanceOf('DateTime', $subscription->firstBillingDate);
        $this->assertInstanceOf('DateTime', $subscription->nextBillingDate);
        $this->assertInstanceOf('DateTime', $subscription->billingPeriodStartDate);
        $this->assertInstanceOf('DateTime', $subscription->billingPeriodEndDate);
        $this->assertInstanceOf('DateTime', $subscription->paidThroughDate);
        $this->assertInstanceOf('DateTime', $subscription->updatedAt);
        $this->assertInstanceOf('DateTime', $subscription->createdAt);

        $this->assertEquals('12.34', $subscription->statusHistory[0]->price);
        $this->assertEquals('0.00', $subscription->statusHistory[0]->balance);
        $this->assertEquals('USD', $subscription->statusHistory[0]->currencyIsoCode);
        $this->assertEquals($plan['id'], $subscription->statusHistory[0]->planId);
        $this->assertEquals(Braintree\Subscription::ACTIVE, $subscription->statusHistory[0]->status);
        $this->assertEquals(Braintree\Subscription::API, $subscription->statusHistory[0]->subscriptionSource);
    }

    public function testGatewayCreate_whenSuccessful()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $result = $gateway->subscription()->create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id']

        ]);
        Test\Helper::assertPrintable($result);
        $this->assertTrue($result->success);
        $subscription = $result->subscription;
        $this->assertEquals($creditCard->token, $subscription->paymentMethodToken);
        $this->assertEquals(0, $subscription->failureCount);
        $this->assertEquals($plan['id'], $subscription->planId);
        $this->assertEquals(Test\Helper::defaultMerchantAccountId(), $subscription->merchantAccountId);
        $this->assertEquals(Braintree\Subscription::ACTIVE, $subscription->status);
    }

    public function testCreate_withPaymentMethodNonce()
    {
        $customerId = Braintree\Customer::create()->customer->id;
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ],
            "customerId" => $customerId,
            "share" => true
        ]);
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodNonce' => $nonce,
            'planId' => $plan['id']
        ]);

        $this->assertTrue($result->success);

        $transaction = $result->subscription->transactions[0];
        $this->assertEquals("411111", $transaction->creditCardDetails->bin);
        $this->assertEquals("1111", $transaction->creditCardDetails->last4);
    }

    public function testCreate_returnsTransactionWhenTransactionFails()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'price' => Braintree\Test\TransactionAmounts::$decline

        ]);
        Test\Helper::assertPrintable($result);
        $this->assertFalse($result->success);
        $this->assertEquals(Braintree\Transaction::PROCESSOR_DECLINED, $result->transaction->status);
    }

    public function testCreate_canSetTheId()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $newId = strval(rand());
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'id' => $newId
        ]);

        $this->assertTrue($result->success);
        $subscription = $result->subscription;
        $this->assertEquals($newId, $subscription->id);
    }

    public function testCreate_canSetTheMerchantAccountId()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'merchantAccountId' => Test\Helper::nonDefaultMerchantAccountId()
        ]);

        $this->assertTrue($result->success);
        $subscription = $result->subscription;
        $this->assertEquals(Test\Helper::nonDefaultMerchantAccountId(), $subscription->merchantAccountId);
    }

    public function testCreate_trialPeriodDefaultsToPlanWithoutTrial()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ]);
        $subscription = $result->subscription;
        $this->assertFalse($subscription->trialPeriod);
        $this->assertNull($subscription->trialDuration);
        $this->assertNull($subscription->trialDurationUnit);
    }

    public function testCreate_trialPeriondDefaultsToPlanWithTrial()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ]);
        $subscription = $result->subscription;
        $this->assertTrue($subscription->trialPeriod);
        $this->assertEquals(2, $subscription->trialDuration);
        $this->assertEquals('day', $subscription->trialDurationUnit);
    }

    public function testCreate_alterPlanTrialPeriod()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'trialDuration' => 5,
            'trialDurationUnit' => 'month'
        ]);
        $subscription = $result->subscription;
        $this->assertTrue($subscription->trialPeriod);
        $this->assertEquals(5, $subscription->trialDuration);
        $this->assertEquals('month', $subscription->trialDurationUnit);
    }

    public function testCreate_removePlanTrialPeriod()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'trialPeriod' => false,
        ]);
        $subscription = $result->subscription;
        $this->assertFalse($subscription->trialPeriod);
    }

    public function testCreate_createsATransactionIfNoTrialPeriod()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(1, sizeof($subscription->transactions));
        $transaction = $subscription->transactions[0];
        $this->assertInstanceOf('Braintree\Transaction', $transaction);
        $this->assertEquals($plan['price'], $transaction->amount);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals($subscription->id, $transaction->subscriptionId);
    }

    public function testCreate_doesNotCreateTransactionIfTrialPeriod()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(0, sizeof($subscription->transactions));
    }

    public function testCreate_returnsATransactionWithSubscriptionBillingPeriod()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ]);
        $subscription = $result->subscription;
        $transaction = $subscription->transactions[0];
        $this->assertEquals($subscription->billingPeriodStartDate, $transaction->subscriptionDetails->billingPeriodStartDate);
        $this->assertEquals($subscription->billingPeriodEndDate, $transaction->subscriptionDetails->billingPeriodEndDate);
    }

    public function testCreate_priceCanBeOverriden()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'price' => '2.00'
        ]);
        $subscription = $result->subscription;
        $this->assertEquals('2.00', $subscription->price);
    }

    public function testCreate_billingDayOfMonthIsInheritedFromPlan()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::billingDayOfMonthPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id']
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(5, $subscription->billingDayOfMonth);
    }

    public function testCreate_billingDayOfMonthCanBeOverriden()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::billingDayOfMonthPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'billingDayOfMonth' => 14
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(14, $subscription->billingDayOfMonth);
    }

    public function testCreate_billingDayOfMonthCanBeOverridenWithStartImmediately()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::billingDayOfMonthPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'options' => ['startImmediately' => true]
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(1, sizeof($subscription->transactions));
    }

    public function testCreate_firstBillingDateCanBeSet()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::billingDayOfMonthPlan();

        $tomorrow = new DateTime("now + 1 day");
        $tomorrow->setTime(0,0,0);

        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'firstBillingDate' => $tomorrow
        ]);

        $subscription = $result->subscription;
        $this->assertEquals($tomorrow, $subscription->firstBillingDate);
        $this->assertEquals(Braintree\Subscription::PENDING, $result->subscription->status);
    }

    public function testCreate_firstBillingDateInThePast()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::billingDayOfMonthPlan();

        $past = new DateTime("now - 3 days");
        $past->setTime(0,0,0);

        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'firstBillingDate' => $past
        ]);

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('subscription')->onAttribute('firstBillingDate');
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_FIRST_BILLING_DATE_CANNOT_BE_IN_THE_PAST, $errors[0]->code);
    }

    public function testCreate_numberOfBillingCyclesCanBeOverridden()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id']
        ]);
        $subscription = $result->subscription;
        $this->assertEquals($plan['numberOfBillingCycles'], $subscription->numberOfBillingCycles);

        $result = Braintree\Subscription::create([
            'numberOfBillingCycles' => '10',
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id']
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(10, $subscription->numberOfBillingCycles);
        $this->assertFalse($subscription->neverExpires);
    }

    public function testCreate_numberOfBillingCyclesCanBeOverriddenToNeverExpire()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id']
        ]);
        $subscription = $result->subscription;
        $this->assertEquals($plan['numberOfBillingCycles'], $subscription->numberOfBillingCycles);

        $result = Braintree\Subscription::create([
            'neverExpires' => true,
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id']
        ]);
        $subscription = $result->subscription;
        $this->assertNull($subscription->numberOfBillingCycles);
        $this->assertTrue($subscription->neverExpires);
    }

    public function testCreate_doesNotInheritAddOnsAndDiscountsWhenDoNotInheritAddOnsOrDiscountsIsSet()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'options' => ['doNotInheritAddOnsOrDiscounts' => true]
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(0, sizeof($subscription->addOns));
        $this->assertEquals(0, sizeof($subscription->discounts));
    }

    public function testCreate_inheritsAddOnsAndDiscountsFromPlanByDefault()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(2, sizeof($subscription->addOns));
        $addOns = $subscription->addOns;
        SubscriptionHelper::sortModificationsById($addOns);

        $this->assertEquals($addOns[0]->amount, "10.00");
        $this->assertEquals($addOns[0]->quantity, 1);
        $this->assertEquals($addOns[0]->numberOfBillingCycles, null);
        $this->assertEquals($addOns[0]->neverExpires, true);
        $this->assertEquals($addOns[0]->currentBillingCycle, 0);

        $this->assertEquals($addOns[1]->amount, "20.00");
        $this->assertEquals($addOns[1]->quantity, 1);
        $this->assertEquals($addOns[1]->numberOfBillingCycles, null);
        $this->assertEquals($addOns[1]->neverExpires, true);
        $this->assertEquals($addOns[1]->currentBillingCycle, 0);

        $this->assertEquals(2, sizeof($subscription->discounts));
        $discounts = $subscription->discounts;
        SubscriptionHelper::sortModificationsById($discounts);

        $this->assertEquals($discounts[0]->amount, "11.00");
        $this->assertEquals($discounts[0]->quantity, 1);
        $this->assertEquals($discounts[0]->numberOfBillingCycles, null);
        $this->assertEquals($discounts[0]->neverExpires, true);
        $this->assertEquals($discounts[0]->currentBillingCycle, 0);

        $this->assertEquals($discounts[1]->amount, "7.00");
        $this->assertEquals($discounts[1]->quantity, 1);
        $this->assertEquals($discounts[1]->numberOfBillingCycles, null);
        $this->assertEquals($discounts[1]->neverExpires, true);
        $this->assertEquals($discounts[1]->currentBillingCycle, 0);
    }

    public function testCreate_allowsOverridingInheritedAddOnsAndDiscounts()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'addOns' => [
                'update' => [
                    [
                        'amount' => '50.00',
                        'existingId' => 'increase_10',
                        'quantity' => 2,
                        'numberOfBillingCycles' => 5
                    ],
                    [
                        'amount' => '60.00',
                        'existingId' => 'increase_20',
                        'quantity' => 4,
                        'numberOfBillingCycles' => 9
                    ]
                ],
            ],
            'discounts' => [
                'update' => [
                    [
                        'amount' => '15.00',
                        'existingId' => 'discount_7',
                        'quantity' => 2,
                        'neverExpires' => true
                    ]
                ]
            ]
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(2, sizeof($subscription->addOns));
        $addOns = $subscription->addOns;
        SubscriptionHelper::sortModificationsById($addOns);

        $this->assertEquals($addOns[0]->amount, "50.00");
        $this->assertEquals($addOns[0]->quantity, 2);
        $this->assertEquals($addOns[0]->numberOfBillingCycles, 5);
        $this->assertEquals($addOns[0]->neverExpires, false);
        $this->assertEquals($addOns[0]->currentBillingCycle, 0);

        $this->assertEquals($addOns[1]->amount, "60.00");
        $this->assertEquals($addOns[1]->quantity, 4);
        $this->assertEquals($addOns[1]->numberOfBillingCycles, 9);
        $this->assertEquals($addOns[1]->neverExpires, false);
        $this->assertEquals($addOns[1]->currentBillingCycle, 0);

        $this->assertEquals(2, sizeof($subscription->discounts));
        $discounts = $subscription->discounts;
        SubscriptionHelper::sortModificationsById($discounts);

        $this->assertEquals($discounts[0]->amount, "11.00");
        $this->assertEquals($discounts[0]->quantity, 1);
        $this->assertEquals($discounts[0]->numberOfBillingCycles, null);
        $this->assertEquals($discounts[0]->neverExpires, true);
        $this->assertEquals($discounts[0]->currentBillingCycle, 0);

        $this->assertEquals($discounts[1]->amount, "15.00");
        $this->assertEquals($discounts[1]->quantity, 2);
        $this->assertEquals($discounts[1]->numberOfBillingCycles, null);
        $this->assertEquals($discounts[1]->neverExpires, true);
        $this->assertEquals($discounts[1]->currentBillingCycle, 0);
    }

    public function testCreate_allowsRemovalOfInheritedAddOnsAndDiscounts()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'addOns' => [
                'remove' => ['increase_10', 'increase_20']
            ],
            'discounts' => [
                'remove' => ['discount_7']
            ]
        ]);
        $subscription = $result->subscription;
        $this->assertEquals(0, sizeof($subscription->addOns));

        $this->assertEquals(1, sizeof($subscription->discounts));

        $this->assertEquals($subscription->discounts[0]->amount, "11.00");
        $this->assertEquals($subscription->discounts[0]->quantity, 1);
        $this->assertEquals($subscription->discounts[0]->numberOfBillingCycles, null);
        $this->assertEquals($subscription->discounts[0]->neverExpires, true);
        $this->assertEquals($subscription->discounts[0]->currentBillingCycle, 0);
    }

    public function testCreate_allowsAddingNewAddOnsAndDiscounts()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'addOns' => [
                'add' => [
                    [
                        'inheritedFromId' => 'increase_30',
                        'amount' => '35.00',
                        'neverExpires' => true,
                        'quantity' => 2
                    ],
                ],
            ],
            'discounts' => [
                'add' => [
                    [
                        'inheritedFromId' => 'discount_15',
                        'amount' => '15.50',
                        'numberOfBillingCycles' => 10,
                        'quantity' => 3
                    ]
                ]
            ]
        ]);

        $subscription = $result->subscription;
        $this->assertEquals(3, sizeof($subscription->addOns));
        $addOns = $subscription->addOns;
        SubscriptionHelper::sortModificationsById($addOns);

        $this->assertEquals($addOns[0]->amount, "10.00");
        $this->assertEquals($addOns[1]->amount, "20.00");
        $this->assertEquals($addOns[2]->id, "increase_30");
        $this->assertEquals($addOns[2]->amount, "35.00");
        $this->assertEquals($addOns[2]->neverExpires, true);
        $this->assertEquals($addOns[2]->numberOfBillingCycles, null);
        $this->assertEquals($addOns[2]->quantity, 2);
        $this->assertEquals($addOns[2]->currentBillingCycle, 0);


        $this->assertEquals(3, sizeof($subscription->discounts));
        $discounts = $subscription->discounts;
        SubscriptionHelper::sortModificationsById($discounts);

        $this->assertEquals($discounts[0]->amount, "11.00");

        $this->assertEquals($discounts[1]->amount, "15.50");
        $this->assertEquals($discounts[1]->id, "discount_15");
        $this->assertEquals($discounts[1]->neverExpires, false);
        $this->assertEquals($discounts[1]->numberOfBillingCycles, 10);
        $this->assertEquals($discounts[1]->quantity, 3);
        $this->assertEquals($discounts[1]->currentBillingCycle, 0);

        $this->assertEquals($discounts[2]->amount, "7.00");
    }

    public function testCreate_properlyParsesValidationErrorsForArrays()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'addOns' => [
                'update' => [
                    [
                        'existingId' => 'increase_10',
                        'amount' => 'invalid',
                    ],
                    [
                        'existingId' => 'increase_20',
                        'quantity' => -10,
                    ]
                ]
            ]
        ]);

        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('subscription')->forKey('addOns')->forKey('update')->forIndex(0)->onAttribute('amount');
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_MODIFICATION_AMOUNT_IS_INVALID, $errors[0]->code);
        $errors = $result->errors->forKey('subscription')->forKey('addOns')->forKey('update')->forIndex(1)->onAttribute('quantity');
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_MODIFICATION_QUANTITY_IS_INVALID, $errors[0]->code);
    }

    public function testCreate_withDescriptor()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555',
                'url' => 'ebay.com'
            ]
        ]);
        $this->assertTrue($result->success);
        $subscription = $result->subscription;
        $this->assertEquals('123*123456789012345678', $subscription->descriptor->name);
        $this->assertEquals('3334445555', $subscription->descriptor->phone);
        $this->assertEquals('ebay.com', $subscription->descriptor->url);
        $transaction = $subscription->transactions[0];
        $this->assertEquals('123*123456789012345678', $transaction->descriptor->name);
        $this->assertEquals('3334445555', $transaction->descriptor->phone);
        $this->assertEquals('ebay.com', $transaction->descriptor->url);
    }

    public function testCreate_withDescriptorValidation()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'descriptor' => [
                'name' => 'xxxxxx',
                'phone' => 'xxxx',
                'url' => '12345678901234'
            ]
        ]);
        $this->assertFalse($result->success);
        $subscription = $result->subscription;

        $errors = $result->errors->forKey('subscription')->forKey('descriptor')->onAttribute('name');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_NAME_FORMAT_IS_INVALID, $errors[0]->code);

        $errors = $result->errors->forKey('subscription')->forKey('descriptor')->onAttribute('phone');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_PHONE_FORMAT_IS_INVALID, $errors[0]->code);

        $errors = $result->errors->forKey('subscription')->forKey('descriptor')->onAttribute('url');
        $this->assertEquals(Braintree\Error\Codes::DESCRIPTOR_URL_FORMAT_IS_INVALID, $errors[0]->code);
    }

    public function testCreate_withDescription()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $plan = SubscriptionHelper::triallessPlan();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $paypalResult = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);

        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $paymentMethodToken,
            'planId' => $plan['id'],
            'options' => [
                'paypal' => [
                    'description' => 'A great product'
                ]
            ]
        ]);
        $this->assertTrue($result->success);
        $subscription = $result->subscription;
        $this->assertEquals('A great product', $subscription->description);
        $transaction = $subscription->transactions[0];
        $this->assertEquals('A great product', $transaction->paypalDetails->description);
    }

    public function testCreate_fromPayPalACcount()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $plan = SubscriptionHelper::triallessPlan();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $paypalResult = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);

        $subscriptionResult = Braintree\Subscription::create([
            'paymentMethodToken' => $paymentMethodToken,
            'planId' => $plan['id']

        ]);
        $this->assertTrue($subscriptionResult->success);
        $transaction = $subscriptionResult->subscription->transactions[0];
        $this->assertEquals('payer@example.com', $transaction->paypalDetails->payerEmail);
    }

    public function testCreate_fromPayPalACcountDoesNotWorkWithFutureNonce()
    {
        $plan = SubscriptionHelper::triallessPlan();
        $nonce = Braintree\Test\Nonces::$paypalFuturePayment;

        $subscriptionResult = Braintree\Subscription::create([
            'paymentMethodNonce' => $nonce,
            'planId' => $plan['id']

        ]);
        $this->assertFalse($subscriptionResult->success);
        $errors = $subscriptionResult->errors->forKey('subscription')->errors;
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_PAYMENT_METHOD_NONCE_IS_INVALID, $errors[0]->code);
    }

    public function testCreate_fromPayPalACcountDoesNotWorkWithOnetimeNonce()
    {
        $plan = SubscriptionHelper::triallessPlan();
        $nonce = Braintree\Test\Nonces::$paypalOneTimePayment;

        $subscriptionResult = Braintree\Subscription::create([
            'paymentMethodNonce' => $nonce,
            'planId' => $plan['id']

        ]);
        $this->assertFalse($subscriptionResult->success);
        $errors = $subscriptionResult->errors->forKey('subscription')->errors;
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_PAYMENT_METHOD_NONCE_IS_INVALID, $errors[0]->code);
    }

    public function testValidationErrors_hasValidationErrorsOnId()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'id' => 'invalid token'
        ]);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('subscription')->onAttribute('id');
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_TOKEN_FORMAT_IS_INVALID, $errors[0]->code);
    }

    public function testFind()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $result = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id']
        ]);
        $this->assertTrue($result->success);
        $subscription = Braintree\Subscription::find($result->subscription->id);
        $this->assertEquals($result->subscription->id, $subscription->id);
        $this->assertEquals($plan['id'], $subscription->planId);
    }

    public function testFind_throwsIfNotFound()
    {
        $this->setExpectedException('Braintree\Exception\NotFound', 'subscription with id does-not-exist not found');
        Braintree\Subscription::find('does-not-exist');

    }

    public function testUpdate_whenSuccessful()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $newId = strval(rand());
        $newPlan = SubscriptionHelper::trialPlan();
        $result = Braintree\Subscription::update($subscription->id, [
            'id' => $newId,
            'price' => '999.99',
            'planId' => $newPlan['id']
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals($newId, $result->subscription->id);
        $this->assertEquals($newPlan['id'], $result->subscription->planId);
        $this->assertEquals('999.99', $result->subscription->price);
    }

    public function testUpdate_doesNotAcceptBadAttributes()
    {
        $this->setExpectedException('InvalidArgumentException', 'invalid keys: bad');
        $result = Braintree\Subscription::update('id', [
            'bad' => 'value'
        ]);
    }

    public function testUpdate_canUpdateNumberOfBillingCycles()
    {
        $plan = SubscriptionHelper::triallessPlan();
        $subscription = SubscriptionHelper::createSubscription();
        $this->assertEquals($plan['numberOfBillingCycles'], $subscription->numberOfBillingCycles);

        $updatedSubscription = Braintree\Subscription::update($subscription->id, [
            'numberOfBillingCycles' => 15
        ])->subscription;
        $this->assertEquals(15, $updatedSubscription->numberOfBillingCycles);
    }

    public function testUpdate_canUpdateNumberOfBillingCyclesToNeverExpire()
    {
        $plan = SubscriptionHelper::triallessPlan();
        $subscription = SubscriptionHelper::createSubscription();
        $this->assertEquals($plan['numberOfBillingCycles'], $subscription->numberOfBillingCycles);

        $updatedSubscription = Braintree\Subscription::update($subscription->id, [
            'neverExpires' => true
        ])->subscription;
        $this->assertNull($updatedSubscription->numberOfBillingCycles);
    }

    public function testUpdate_createsTransactionOnProration()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $result = Braintree\Subscription::update($subscription->id, [
            'price' => $subscription->price + 1,
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals(sizeof($subscription->transactions) + 1, sizeof($result->subscription->transactions));
    }

    public function testUpdate_createsProratedTransactionWhenFlagIsPassedTrue()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $result = Braintree\Subscription::update($subscription->id, [
            'price' => $subscription->price + 1,
            'options' => ['prorateCharges' => true]
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals(sizeof($subscription->transactions) + 1, sizeof($result->subscription->transactions));
    }

    public function testUpdate_createsProratedTransactionWhenFlagIsPassedFalse()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $result = Braintree\Subscription::update($subscription->id, [
            'price' => $subscription->price + 1,
            'options' => ['prorateCharges' => false]
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals(sizeof($subscription->transactions), sizeof($result->subscription->transactions));
    }

    public function testUpdate_DoesNotUpdateSubscriptionWhenProrationTransactionFailsAndRevertIsTrue()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $result = Braintree\Subscription::update($subscription->id, [
            'price' => $subscription->price + 2100,
            'options' => ['prorateCharges' => true, 'revertSubscriptionOnProrationFailure' => true]
        ]);
        $this->assertFalse($result->success);
        $this->assertEquals(sizeof($subscription->transactions) + 1, sizeof($result->subscription->transactions));
        $this->assertEquals(Braintree\Transaction::PROCESSOR_DECLINED, $result->subscription->transactions[0]->status);
        $this->assertEquals("0.00", $result->subscription->balance);
        $this->assertEquals($subscription->price, $result->subscription->price);
    }

    public function testUpdate_UpdatesSubscriptionWhenProrationTransactionFailsAndRevertIsFalse()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $result = Braintree\Subscription::update($subscription->id, [
            'price' => $subscription->price + 2100,
            'options' => ['prorateCharges' => true, 'revertSubscriptionOnProrationFailure' => false]
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals(sizeof($subscription->transactions) + 1, sizeof($result->subscription->transactions));
        $this->assertEquals(Braintree\Transaction::PROCESSOR_DECLINED, $result->subscription->transactions[0]->status);
        $this->assertEquals($result->subscription->transactions[0]->amount, $result->subscription->balance);
        $this->assertEquals($subscription->price + 2100, $result->subscription->price);
    }

    public function testUpdate_invalidSubscriptionId()
    {
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\Subscription::update('does-not-exist', []);
    }

    public function testUpdate_validationErrors()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $result = Braintree\Subscription::update($subscription->id, ['price' => '']);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('subscription')->onAttribute('price');
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_PRICE_CANNOT_BE_BLANK, $errors[0]->code);
    }

    public function testUpdate_cannotUpdateCanceledSubscription()
    {
        $subscription = SubscriptionHelper::createSubscription();
        Braintree\Subscription::cancel($subscription->id);
        $result = Braintree\Subscription::update($subscription->id, ['price' => '1.00']);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('subscription')->onAttribute('base');
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_CANNOT_EDIT_CANCELED_SUBSCRIPTION, $errors[0]->code);
    }

    public function testUpdate_canUpdatePaymentMethodToken()
    {
        $oldCreditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $oldCreditCard->token,
            'price' => '54.99',
            'planId' => $plan['id']
        ])->subscription;

        $newCreditCard = Braintree\CreditCard::createNoValidate([
            'number' => '5105105105105100',
            'expirationDate' => '05/2010',
            'customerId' => $oldCreditCard->customerId
        ]);

        $result = Braintree\Subscription::update($subscription->id, [
            'paymentMethodToken' => $newCreditCard->token
        ]);
        $this->assertTrue($result->success);
        $this->assertEquals($newCreditCard->token, $result->subscription->paymentMethodToken);
    }

    public function testUpdate_canUpdatePaymentMethodWithPaymentMethodNonce()
    {
        $oldCreditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $oldCreditCard->token,
            'price' => '54.99',
            'planId' => $plan['id']
        ])->subscription;

        $customerId = Braintree\Customer::create()->customer->id;
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonce_for_new_card([
            "creditCard" => [
                "number" => "4111111111111111",
                "expirationMonth" => "11",
                "expirationYear" => "2099"
            ],
            "customerId" => $oldCreditCard->customerId,
            "share" => true
        ]);

        $result = Braintree\Subscription::update($subscription->id, [
            'paymentMethodNonce' => $nonce
        ]);

        $this->assertTrue($result->success);

        $newCreditCard = Braintree\CreditCard::find($result->subscription->paymentMethodToken);

        $this->assertEquals("1111", $newCreditCard->last4);
        $this->assertNotEquals($oldCreditCard->last4, $newCreditCard->last4);
    }

    public function testUpdate_canUpdateAddOnsAndDiscounts()
    {
        $oldCreditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $oldCreditCard->token,
            'price' => '54.99',
            'planId' => $plan['id']
        ])->subscription;

        $result = Braintree\Subscription::update($subscription->id, [
            'addOns' => [
                'update' => [
                    [
                        'amount' => '99.99',
                        'existingId' => 'increase_10',
                        'quantity' => 99,
                        'numberOfBillingCycles' => 99
                    ],
                    [
                        'amount' => '22.22',
                        'existingId' => 'increase_20',
                        'quantity' => 22,
                        'neverExpires' => true
                    ]
                ],
            ],
            'discounts' => [
                'update' => [
                    [
                        'amount' => '33.33',
                        'existingId' => 'discount_11',
                        'quantity' => 33,
                        'numberOfBillingCycles' => 33
                    ]
                ],
            ],
        ]);
        $this->assertTrue($result->success);

        $subscription = $result->subscription;
        $this->assertEquals(2, sizeof($subscription->addOns));
        $addOns = $subscription->addOns;
        SubscriptionHelper::sortModificationsById($addOns);

        $this->assertEquals($addOns[0]->id, "increase_10");
        $this->assertEquals($addOns[0]->amount, "99.99");
        $this->assertEquals($addOns[0]->neverExpires, false);
        $this->assertEquals($addOns[0]->numberOfBillingCycles, 99);
        $this->assertEquals($addOns[0]->quantity, 99);

        $this->assertEquals($addOns[1]->id, "increase_20");
        $this->assertEquals($addOns[1]->amount, "22.22");
        $this->assertEquals($addOns[1]->neverExpires, true);
        $this->assertEquals($addOns[1]->numberOfBillingCycles, null);
        $this->assertEquals($addOns[1]->quantity, 22);

        $this->assertEquals(2, sizeof($subscription->discounts));
        $discounts = $subscription->discounts;
        SubscriptionHelper::sortModificationsById($discounts);

        $this->assertEquals($discounts[0]->id, "discount_11");
        $this->assertEquals($discounts[0]->amount, "33.33");
        $this->assertEquals($discounts[0]->neverExpires, false);
        $this->assertEquals($discounts[0]->numberOfBillingCycles, 33);
        $this->assertEquals($discounts[0]->quantity, 33);
    }

    public function testUpdate_canAddAndRemoveAddOnsAndDiscounts()
    {
        $oldCreditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $oldCreditCard->token,
            'price' => '54.99',
            'planId' => $plan['id']
        ])->subscription;

        $result = Braintree\Subscription::update($subscription->id, [
            'addOns' => [
                'add' => [
                    [
                        'amount' => '33.33',
                        'inheritedFromId' => 'increase_30',
                        'quantity' => 33,
                        'numberOfBillingCycles' => 33
                    ]
                ],
                'remove' => ['increase_10', 'increase_20']
            ],
            'discounts' => [
                'add' => [
                    [
                        'inheritedFromId' => 'discount_15',
                    ]
                ],
                'remove' => ['discount_7']
            ],
        ]);
        $this->assertTrue($result->success);

        $subscription = $result->subscription;
        $this->assertEquals(1, sizeof($subscription->addOns));
        $addOns = $subscription->addOns;
        SubscriptionHelper::sortModificationsById($addOns);

        $this->assertEquals($addOns[0]->id, "increase_30");
        $this->assertEquals($addOns[0]->amount, "33.33");
        $this->assertEquals($addOns[0]->neverExpires, false);
        $this->assertEquals($addOns[0]->numberOfBillingCycles, 33);
        $this->assertEquals($addOns[0]->quantity, 33);

        $this->assertEquals(2, sizeof($subscription->discounts));
        $discounts = $subscription->discounts;
        SubscriptionHelper::sortModificationsById($discounts);

        $this->assertEquals($discounts[0]->id, "discount_11");
        $this->assertEquals($discounts[1]->id, "discount_15");
        $this->assertEquals($discounts[1]->amount, "15.00");
        $this->assertEquals($discounts[1]->neverExpires, true);
        $this->assertNull($discounts[1]->numberOfBillingCycles);
        $this->assertEquals($discounts[1]->quantity, 1);
    }

    public function testUpdate_canReplaceEntireSetOfAddonsAndDiscounts()
    {
        $oldCreditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::addOnDiscountPlan();
        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $oldCreditCard->token,
            'price' => '54.99',
            'planId' => $plan['id']
        ])->subscription;

        $result = Braintree\Subscription::update($subscription->id, [
            'addOns' => [
                'add' => [
                    ['inheritedFromId' => 'increase_30'],
                    ['inheritedFromId' => 'increase_20']
                ]
            ],
            'discounts' => [
                'add' => [
                    ['inheritedFromId' => 'discount_15']
                ]
            ],
            'options' => ['replaceAllAddOnsAndDiscounts' => true]
        ]);
        $this->assertTrue($result->success);
        $subscription = $result->subscription;

        $this->assertEquals(2, sizeof($subscription->addOns));
        $addOns = $subscription->addOns;
        SubscriptionHelper::sortModificationsById($addOns);

        $this->assertEquals($addOns[0]->id, "increase_20");
        $this->assertEquals($addOns[1]->id, "increase_30");

        $this->assertEquals(1, sizeof($subscription->discounts));
        $discounts = $subscription->discounts;

        $this->assertEquals($discounts[0]->id, "discount_15");
    }

    public function testUpdate_withDescriptor()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::triallessPlan();
        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
            'descriptor' => [
                'name' => '123*123456789012345678',
                'phone' => '3334445555'
            ]
        ])->subscription;
        $result = Braintree\Subscription::update($subscription->id, [
            'descriptor' => [
                'name' => '999*9999999',
                'phone' => '8887776666'
            ]
        ]);
        $updatedSubscription = $result->subscription;
        $this->assertEquals('999*9999999', $updatedSubscription->descriptor->name);
        $this->assertEquals('8887776666', $updatedSubscription->descriptor->phone);
    }

    public function testUpdate_withDescription()
    {
        $paymentMethodToken = 'PAYPAL_TOKEN-' . strval(rand());
        $customer = Braintree\Customer::createNoValidate();
        $plan = SubscriptionHelper::triallessPlan();
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $nonce = $http->nonceForPayPalAccount([
            'paypal_account' => [
                'consent_code' => 'PAYPAL_CONSENT_CODE',
                'token' => $paymentMethodToken
            ]
        ]);

        $paypalResult = Braintree\PaymentMethod::create([
            'customerId' => $customer->id,
            'paymentMethodNonce' => $nonce
        ]);

        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $paymentMethodToken,
            'planId' => $plan['id'],
            'options' => [
                'paypal' => [
                    'description' => 'A great product'
                ]
            ]
        ])->subscription;
        $result = Braintree\Subscription::update($subscription->id, [
            'options' => [
                'paypal' => [
                    'description' => 'An incredible product'
                ]
            ]
        ]);
        $updatedSubscription = $result->subscription;
        $this->assertEquals('An incredible product', $updatedSubscription->description);
    }

    public function testCancel_returnsSuccessIfCanceled()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $result = Braintree\Subscription::cancel($subscription->id);
        $this->assertTrue($result->success);
        $this->assertEquals(Braintree\Subscription::CANCELED, $result->subscription->status);
    }

    public function testCancel_throwsErrorIfRecordNotFound()
    {
        $this->setExpectedException('Braintree\Exception\NotFound');
        Braintree\Subscription::cancel('non-existing-id');
    }

    public function testCancel_returnsErrorIfCancelingCanceledSubscription()
    {
        $subscription = SubscriptionHelper::createSubscription();
        Braintree\Subscription::cancel($subscription->id);
        $result = Braintree\Subscription::cancel($subscription->id);
        $this->assertFalse($result->success);
        $errors = $result->errors->forKey('subscription')->onAttribute('status');
        $this->assertEquals(Braintree\Error\Codes::SUBSCRIPTION_STATUS_IS_CANCELED, $errors[0]->code);
    }

    public function testRetryCharge_WithoutAmount()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/subscriptions/' . $subscription->id . '/make_past_due';
        $http->put($path);

        $result = Braintree\Subscription::retryCharge($subscription->id);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;

        $this->assertEquals($subscription->price, $transaction->amount);
        $this->assertNotNull($transaction->processorAuthorizationCode);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
    }

    public function testRetryCharge_WithAmount()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/subscriptions/' . $subscription->id . '/make_past_due';
        $http->put($path);

        $result = Braintree\Subscription::retryCharge($subscription->id, 1000);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;

        $this->assertEquals(1000, $transaction->amount);
        $this->assertNotNull($transaction->processorAuthorizationCode);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals(Braintree\Transaction::AUTHORIZED, $transaction->status);
    }

    public function testRetryCharge_WithSubmitForSettlement()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/subscriptions/' . $subscription->id . '/make_past_due';
        $http->put($path);

        $result = Braintree\Subscription::retryCharge($subscription->id, null, true);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;

        $this->assertEquals($subscription->price, $transaction->amount);
        $this->assertNotNull($transaction->processorAuthorizationCode);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $transaction->status);
    }

    public function testRetryCharge_WithSubmitForSettlementAndAmount()
    {
        $subscription = SubscriptionHelper::createSubscription();
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/subscriptions/' . $subscription->id . '/make_past_due';
        $http->put($path);

        $result = Braintree\Subscription::retryCharge($subscription->id, 1002, true);

        $this->assertTrue($result->success);
        $transaction = $result->transaction;

        $this->assertEquals(1002, $transaction->amount);
        $this->assertNotNull($transaction->processorAuthorizationCode);
        $this->assertEquals(Braintree\Transaction::SALE, $transaction->type);
        $this->assertEquals(Braintree\Transaction::SUBMITTED_FOR_SETTLEMENT, $transaction->status);
    }
}
