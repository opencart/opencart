<?php
namespace Test\Unit;

require_once dirname(__dir__) . '/Setup.php';

use Test\Setup;
use Braintree;

class AuthorizationAdjustmentTest extends Setup
{
    public function testFactory()
    {
        $authorizationAdjustment = Braintree\AuthorizationAdjustment::factory([]);

        $this->assertinstanceof('Braintree\AuthorizationAdjustment', $authorizationAdjustment);
    }

    public function testToString()
    {
        $authorizationAdjustmentParams = [
            'amount' => '100.00',
            'timestamp' => new \DateTime('2017-07-12 01:02:03'),
            'success' => true,
            'processorResponseCode' => '1000',
            'processorResponseText' => 'Approved',
        ];

        $authorizationAdjustment = Braintree\AuthorizationAdjustment::factory($authorizationAdjustmentParams);

        $this->assertEquals('Braintree\AuthorizationAdjustment[amount=100.00, timestamp=Wednesday, 12-Jul-17 01:02:03 UTC, success=1, processorResponseCode=1000, processorResponseText=Approved]', (string) $authorizationAdjustment);
    }
}
