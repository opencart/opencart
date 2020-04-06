<?php
require_once realpath(dirname(__FILE__)) . '/../TestHelper.php';

class Braintree_AddOnTest extends PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $addOn = \Braintree_AddOn::factory(array());

        $this->assertInstanceOf('Braintree_AddOn', $addOn);
    }

    function testToString()
    {
        $addOnParams = array (
            "amount" => "100.00",
            "description" => "some description",
            "id" => "1",
            "kind" => "add_on",
            "name" => "php_add_on",
            "neverExpires" => "false",
            "numberOfBillingCycles" => "1"
        );

        $addOn = \Braintree_AddOn::factory($addOnParams);

        $this->assertEquals("Braintree_AddOn[amount=100.00, description=some description, id=1, kind=add_on, name=php_add_on, neverExpires=false, numberOfBillingCycles=1]", (string) $addOn);
    }
}
