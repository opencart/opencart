<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class DiscountTest extends Setup
{
    public function testAll_returnsAllDiscounts()
    {
        $newId = strval(rand());

        $discountParams = [
            "amount" => "100.00",
            "description" => "some description",
            "id" => $newId,
            "kind" => "discount",
            "name" => "php_discount",
            "neverExpires" => "false",
            "numberOfBillingCycles" => "1"
        ];

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . "/modifications/create_modification_for_tests";
        $http->post($path, ["modification" => $discountParams]);

        $discounts = Braintree\Discount::all();

        foreach ($discounts as $discount)
        {
            if ($discount->id == $newId)
            {
                $actualDiscount = $discount;
            }
        }

        $this->assertNotNull($actualDiscount);
        $this->assertEquals($discountParams["amount"], $actualDiscount->amount);
        $this->assertEquals($discountParams["description"], $actualDiscount->description);
        $this->assertEquals($discountParams["id"], $actualDiscount->id);
        $this->assertEquals($discountParams["kind"], $actualDiscount->kind);
        $this->assertEquals($discountParams["name"], $actualDiscount->name);
        $this->assertFalse($actualDiscount->neverExpires);
        $this->assertEquals($discountParams["numberOfBillingCycles"], $actualDiscount->numberOfBillingCycles);
    }

    public function testGatewayAll_returnsAllDiscounts()
    {
        $newId = strval(rand());

        $discountParams = [
            "amount" => "100.00",
            "description" => "some description",
            "id" => $newId,
            "kind" => "discount",
            "name" => "php_discount",
            "neverExpires" => "false",
            "numberOfBillingCycles" => "1"
        ];

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . "/modifications/create_modification_for_tests";
        $http->post($path, ["modification" => $discountParams]);

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $discounts = $gateway->discount()->all();

        foreach ($discounts as $discount)
        {
            if ($discount->id == $newId)
            {
                $actualDiscount = $discount;
            }
        }

        $this->assertNotNull($actualDiscount);
        $this->assertEquals($discountParams["amount"], $actualDiscount->amount);
        $this->assertEquals($discountParams["id"], $actualDiscount->id);
        $this->assertEquals($discountParams["kind"], $actualDiscount->kind);
    }
}
