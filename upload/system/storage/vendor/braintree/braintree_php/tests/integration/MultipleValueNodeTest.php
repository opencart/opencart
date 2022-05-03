<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class MultipleValueNodeTest extends Setup
{
    public function testIn_singleValue()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $activeSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '3'
        ])->subscription;

        $canceledSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '3'
        ])->subscription;
        Braintree\Subscription::cancel($canceledSubscription->id);

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::status()->in([Braintree\Subscription::ACTIVE]),
            Braintree\SubscriptionSearch::price()->is('3'),
        ]);
        foreach ($collection AS $item) {
            $this->assertEquals(Braintree\Subscription::ACTIVE, $item->status);
        }

        $this->assertTrue(Test\Helper::includes($collection, $activeSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $canceledSubscription));
    }

    public function testIs()
    {
        $found = false;
        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::status()->is(Braintree\Subscription::PAST_DUE)
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
        foreach ($collection AS $item) {
            $found = true;
            $this->assertEquals(Braintree\Subscription::EXPIRED, $item->status);
        }
        $this->assertTrue($found);
    }

    public function testIn_multipleValues()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();

        $activeSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '4'
        ])->subscription;

        $canceledSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '4'
        ])->subscription;
        Braintree\Subscription::cancel($canceledSubscription->id);

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::status()->in([Braintree\Subscription::ACTIVE, Braintree\Subscription::CANCELED]),
            Braintree\SubscriptionSearch::price()->is('4')
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $activeSubscription));
        $this->assertTrue(Test\Helper::includes($collection, $canceledSubscription));
    }
}
