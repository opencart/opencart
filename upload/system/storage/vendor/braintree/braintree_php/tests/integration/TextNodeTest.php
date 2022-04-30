<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class TextNodeTest extends Setup
{
    public function testIs()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();
        $trialPlan = SubscriptionHelper::trialPlan();

        $trialSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $trialPlan['id'],
            'price' => '5',
        ])->subscription;

        $triallessSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '5',
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::planId()->is("integration_trial_plan"),
            Braintree\SubscriptionSearch::price()->is('5')
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $trialSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $triallessSubscription));
    }

    public function testIsNot()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();
        $trialPlan = SubscriptionHelper::trialPlan();

        $trialSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $trialPlan['id'],
            'price' => '6',
        ])->subscription;

        $triallessSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '6'
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::planId()->isNot("integration_trialless_plan"),
            Braintree\SubscriptionSearch::price()->is("6")
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $trialSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $triallessSubscription));
    }

    public function testStartsWith()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();
        $trialPlan = SubscriptionHelper::trialPlan();

        $trialSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $trialPlan['id'],
            'price' => '7',
        ])->subscription;

        $triallessSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '7',
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::planId()->startsWith("integration_trial_pl"),
            Braintree\SubscriptionSearch::price()->is('7')
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $trialSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $triallessSubscription));
    }

    public function testEndsWith()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();
        $trialPlan = SubscriptionHelper::trialPlan();

        $trialSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $trialPlan['id'],
            'price' => '8'
        ])->subscription;

        $triallessSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '8'
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::planId()->endsWith("rial_plan"),
            Braintree\SubscriptionSearch::price()->is("8")
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $trialSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $triallessSubscription));
    }


    public function testContains()
    {
        $creditCard = SubscriptionHelper::createCreditCard();
        $triallessPlan = SubscriptionHelper::triallessPlan();
        $trialPlan = SubscriptionHelper::trialPlan();

        $trialSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $trialPlan['id'],
            'price' => '9'
        ])->subscription;

        $triallessSubscription = Braintree\Subscription::create([
            'paymentMethodToken' => $creditCard->token,
            'planId' => $triallessPlan['id'],
            'price' => '9'
        ])->subscription;

        $collection = Braintree\Subscription::search([
            Braintree\SubscriptionSearch::planId()->contains("ration_trial_pl"),
            Braintree\SubscriptionSearch::price()->is("9")
        ]);

        $this->assertTrue(Test\Helper::includes($collection, $trialSubscription));
        $this->assertFalse(Test\Helper::includes($collection, $triallessSubscription));
    }
}
