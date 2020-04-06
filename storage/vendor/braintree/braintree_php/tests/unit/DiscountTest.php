<?php
require_once realpath(dirname(__FILE__)) . '/../TestHelper.php';

class Braintree_DiscountTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $discount = \Braintree_Discount::factory(array());

        $this->assertInstanceOf('Braintree_Discount', $discount);
    }

    function testToString()
    {
        $discountParams = array (
            "amount" => "100.00",
            "description" => "some description",
            "id" => "1",
            "kind" => "discount",
            "name" => "php_discount",
            "neverExpires" => "false",
            "numberOfBillingCycles" => "1"
        );

        $discount = \Braintree_Discount::factory($discountParams);

        $this->assertEquals("Braintree_Discount[amount=100.00, description=some description, id=1, kind=discount, name=php_discount, neverExpires=false, numberOfBillingCycles=1]", (string) $discount);
    }
}
