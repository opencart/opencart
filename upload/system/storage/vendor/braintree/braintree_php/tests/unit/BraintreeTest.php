<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class BraintreeTest extends Setup
{
    public function testIsset()
    {
        $t = Braintree\Transaction::factory([
            'creditCard' => ['expirationMonth' => '05', 'expirationYear' => '2010', 'bin' => '510510', 'last4' => '5100'],
            'customer' => [],
            'billing' => [],
            'descriptor' => [],
            'shipping' => [],
            'subscription' => ['billingPeriodStartDate' => '1983-07-12'],
            'statusHistory' => [],
        ]);
        $this->assertTrue(isset($t->creditCard));
        $this->assertFalse(empty($t->creditCard));
    }
}
