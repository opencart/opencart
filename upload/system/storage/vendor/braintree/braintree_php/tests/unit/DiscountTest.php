<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class DiscountTest extends Setup
{
    public function testFactory()
    {
        $discount = Braintree\Discount::factory([]);

        $this->assertInstanceOf('Braintree\Discount', $discount);
    }

    public function testToString()
    {
        $discountParams = [
            "amount" => "100.00",
            "description" => "some description",
            "id" => "1",
            "kind" => "discount",
            "name" => "php_discount",
            "neverExpires" => "false",
            "numberOfBillingCycles" => "1"
        ];

        $discount = Braintree\Discount::factory($discountParams);

        $this->assertEquals("Braintree\Discount[amount=100.00, description=some description, id=1, kind=discount, name=php_discount, neverExpires=false, numberOfBillingCycles=1]", (string) $discount);
    }
}
