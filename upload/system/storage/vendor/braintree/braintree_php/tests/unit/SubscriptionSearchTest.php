<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class SubscriptionSearchTest extends Setup
{
    public function testSearch_billingCyclesRemaining_isRangeNode()
    {
        $node = Braintree\SubscriptionSearch::billingCyclesRemaining();
        $this->assertInstanceOf('Braintree\RangeNode', $node);
    }

    public function testSearch_price_isRangeNode()
    {
        $node = Braintree\SubscriptionSearch::price();
        $this->assertInstanceOf('Braintree\RangeNode', $node);
    }

    public function testSearch_daysPastDue_isRangeNode()
    {
        $node = Braintree\SubscriptionSearch::daysPastDue();
        $this->assertInstanceOf('Braintree\RangeNode', $node);
    }

    public function testSearch_createdAt_isRangeNode()
    {
        $node = Braintree\SubscriptionSearch::createdAt();
        $this->assertInstanceOf('Braintree\RangeNode', $node);
    }

    public function testSearch_id_isTextNode()
    {
        $node = Braintree\SubscriptionSearch::id();
        $this->assertInstanceOf('Braintree\TextNode', $node);
    }

    public function testSearch_ids_isMultipleValueNode()
    {
        $node = Braintree\SubscriptionSearch::ids();
        $this->assertInstanceOf('Braintree\MultipleValueNode', $node);
    }

    public function testSearch_inTrialPeriod_isMultipleValueNode()
    {
        $node = Braintree\SubscriptionSearch::inTrialPeriod();
        $this->assertInstanceOf('Braintree\MultipleValueNode', $node);
    }

    public function testSearch_merchantAccountId_isMultipleValueNode()
    {
        $node = Braintree\SubscriptionSearch::merchantAccountId();
        $this->assertInstanceOf('Braintree\MultipleValueNode', $node);
    }

    public function testSearch_planId_isMultipleValueOrTextNode()
    {
        $node = Braintree\SubscriptionSearch::planId();
        $this->assertInstanceOf('Braintree\MultipleValueOrTextNode', $node);
    }

    public function testSearch_status_isMultipleValueNode()
    {
        $node = Braintree\SubscriptionSearch::status();
        $this->assertInstanceOf('Braintree\MultipleValueNode', $node);
    }
}
