<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use DateTime;
use DateTimeZone;
use Test;
use Test\Setup;
use Braintree;

class SubscriptionSearchTest extends Setup
{
    public function testSearch_planIdIs()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();
        $trialPlan = SubscriptionHelper::trialPlan();

        $trialSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $trialPlan['id'],
            'price' => '1'
        ])->subscription;

        $triallessSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '1'
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::planId()->is('integration_trial_plan'),
            Braintree\SubscriptionSearch::price()->is('1')
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $trialSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $triallessSubscription));
    }

    public function test_noRequestsWhenIterating()
    {
        $resultsReturned = false;
        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::planId()->is('imaginary')
        ]);

        foreach ($collection as $transaction) {
            $resultsReturned = true;
            break;
        }

        $this->assertSame(0, $collection->maximumCount());
        $this->assertEquals(false, $resultsReturned);
    }

    public function testSearch_inTrialPeriod()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();
        $trialPlan = SubscriptionHelper::trialPlan();

        $trialSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $trialPlan['id'],
            'price' => '1'
        ])->subscription;

        $triallessSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '1'
        ])->subscription;

        $subscriptions_in_trial = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::inTrialPeriod()->is(true)
        ]);

        $this->assertTrue(Test\Helper::includes($subscriptions_in_trial, $trialSubscription));
        $this->assertFalse(Test\Helper::includes($subscriptions_in_trial, $triallessSubscription));

        $subscriptions_not_in_trial = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::inTrialPeriod()->is(false)
        ]);

        $this->assertTrue(Test\Helper::includes($subscriptions_not_in_trial, $triallessSubscription));
        $this->assertFalse(Test\Helper::includes($subscriptions_not_in_trial, $trialSubscription));
    }

    public function testSearch_statusIsPastDue()
    {
        $found = false;
        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::status()->in([Braintree\Subscription::PAST_DUE])
        ]);
        foreach ($collection AS $item) {
            $found = true;
            $this->assertEquals(Braintree\Subscription::PAST_DUE, $item->status);
        }
        $this->assertTrue($found);
    }

    public function testSearch_statusIsExpired()
    {
        $found = false;
        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::status()->in([Braintree\Subscription::EXPIRED])
        ]);
        foreach ($collection as $item) {
            $found = true;
            $this->assertEquals(Braintree\Subscription::EXPIRED, $item->status);
        }
        $this->assertTrue($found);
    }

    public function testSearch_billingCyclesRemaing()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $subscription_4 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'numberOfBillingCycles' => 4
        ])->subscription;

        $subscription_8 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'numberOfBillingCycles' => 8
        ])->subscription;

        $subscription_10 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'numberOfBillingCycles' => 10
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::billingCyclesRemaining()->between(5, 10)
        ]);

        $this->assertFalse(Test\Helper::includes($collection, $subscription_4));
        $this->assertTrue(Test\Helper::includes($collection, $subscription_8));
        $this->assertTrue(Test\Helper::includes($collection, $subscription_10));
    }

    public function testSearch_subscriptionId()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $rand_id = strval(rand());

        $subscription_1 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'id' => 'subscription_123_id_' . $rand_id
        ])->subscription;

        $subscription_2 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'id' => 'subscription_23_id_' . $rand_id
        ])->subscription;

        $subscription_3 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'id' => 'subscription_3_id_' . $rand_id
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::id()->contains('23_id_')
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $subscription_1));
        $this->assertTrue(Test\Helper::includes($collection, $subscription_2));
        $this->assertFalse(Test\Helper::includes($collection, $subscription_3));
    }

    public function testSearch_merchantAccountId()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $rand_id = strval(rand());

        $subscription_1 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'id' => strval(rand()) . '_subscription_' . $rand_id,
            'price' => '2'
        ])->subscription;

        $subscription_2 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'id' => strval(rand()) . '_subscription_' . $rand_id,
            'merchantAccountId' => Test\Helper::nonDefaultMerchantAccountId(),
            'price' => '2'
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::id()->endsWith('subscription_' . $rand_id),
            Braintree\SubscriptionSearch::merchantAccountId()->in([Test\Helper::nonDefaultMerchantAccountId()]),
            Braintree\SubscriptionSearch::price()->is('2')
        ]);

        $this->assertFalse(Test\Helper::includes($collection, $subscription_1));
        $this->assertTrue(Test\Helper::includes($collection, $subscription_2));
    }

    public function testSearch_bogusMerchantAccountId()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $rand_id = strval(rand());

        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'id' => strval(rand()) . '_subscription_' . $rand_id,
            'price' => '11.38'
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::id()->endsWith('subscription_' . $rand_id),
            Braintree\SubscriptionSearch::merchantAccountId()->in(['bogus_merchant_account']),
            Braintree\SubscriptionSearch::price()->is('11.38')
        ]);

        $this->assertFalse(Test\Helper::includes($collection, $subscription));
    }

    public function testSearch_daysPastDue()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $subscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id']
        ])->subscription;

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/subscriptions/' . $subscription->id . '/make_past_due';
        $http->put($path, ['daysPastDue' => 5]);

        $found = false;
        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::daysPastDue()->between(2, 10)
        ]);
        foreach ($collection AS $item) {
            $found = true;
            $this->assertTrue($item->daysPastDue <= 10);
            $this->assertTrue($item->daysPastDue >= 2);
        }
        $this->assertTrue($found);
    }

    public function testSearch_price()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $subscription_850 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '8.50'
        ])->subscription;

        $subscription_851 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '8.51'
        ])->subscription;

        $subscription_852 = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '8.52'
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::price()->between('8.51', '8.52')
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $subscription_851));
        $this->assertTrue(Test\Helper::includes($collection, $subscription_852));
        $this->assertFalse(Test\Helper::includes($collection, $subscription_850));
    }

    public function testSearch_nextBillingDate()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();
        $trialPlan = SubscriptionHelper::trialPlan();

        $triallessSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
        ])->subscription;

        $trialSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $trialPlan['id'],
        ])->subscription;

        $fiveDaysFromNow = new DateTime();
        $fiveDaysFromNow->modify("+5 days");

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::nextBillingDate()->greaterThanOrEqualTo($fiveDaysFromNow),
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $triallessSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $trialSubscription));
    }

    public function testSearch_createdAt_lessThanOrEqualTo()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();

        $subscription= Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ])->subscription;

        $fiveDaysFromNow = new DateTime();
        $fiveDaysFromNow->modify("+5 days");

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->lessThanOrEqualTo($fiveDaysFromNow),
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $subscription));

        $fiveDaysAgo = new DateTime();
        $fiveDaysAgo->modify("-5 days");

        $emptyCollection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->lessThanOrEqualTo($fiveDaysAgo),
        ]);

        $this->assertFalse(Test\Helper::includes($emptyCollection, $subscription));
    }

    public function testSearch_createdAt_greaterThanOrEqualTo()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();

        $subscription= Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ])->subscription;

        $fiveDaysAgo = new DateTime();
        $fiveDaysAgo->modify("-5 days");

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->greaterThanOrEqualTo($fiveDaysAgo),
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $subscription));

        $fiveDaysFromNow = new DateTime();
        $fiveDaysFromNow->modify("+5 days");

        $emptyCollection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->greaterThanOrEqualTo($fiveDaysFromNow),
        ]);

        $this->assertFalse(Test\Helper::includes($emptyCollection, $subscription));
    }

    public function testSearch_createdAt_between()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();

        $subscription= Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ])->subscription;

        $fiveDaysAgo = new DateTime();
        $fiveDaysFromNow = new DateTime();
        $tenDaysFromNow = new DateTime();

        $fiveDaysAgo->modify("-5 days");
        $fiveDaysFromNow->modify("+5 days");
        $tenDaysFromNow->modify("+10 days");

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->between($fiveDaysAgo, $fiveDaysFromNow),
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $subscription));

        $emptyCollection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->between($fiveDaysFromNow, $tenDaysFromNow),
        ]);

        $this->assertFalse(Test\Helper::includes($emptyCollection, $subscription));
    }

    public function testSearch_createdAt_is()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();

        $subscription= Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->is($subscription->createdAt),
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $subscription));

        $oneDayAgo = $subscription->createdAt;
        $oneDayAgo->modify("-1 days");

        $emptyCollection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->is($oneDayAgo),
        ]);

        $this->assertFalse(Test\Helper::includes($emptyCollection, $subscription));
    }

    public function testSearch_createdAt_convertLocalToUTC()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $plan = SubscriptionHelper::trialPlan();

        $subscription= Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $plan['id'],
        ])->subscription;

        $tenMinAgo = date_create("now -10 minutes", new DateTimeZone("US/Pacific"));
        $tenMinFromNow = date_create("now +10 minutes", new DateTimeZone("US/Pacific"));

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::createdAt()->between($tenMinAgo, $tenMinFromNow),
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $subscription));
    }

    public function testSearch_transactionId()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $matchingSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
        ])->subscription;

        $nonMatchingSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::transactionId()->is($matchingSubscription->transactions[0]->id),
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $matchingSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $nonMatchingSubscription));
    }
}
