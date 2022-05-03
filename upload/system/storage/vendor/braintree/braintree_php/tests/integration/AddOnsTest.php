<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class AddOnsTest extends Setup
{
    public function testAll_returnsAllAddOns()
    {
        $newId = strval(rand());

        $addOnParams = [
            "amount" => "100.00",
            "description" => "some description",
            "id" => $newId,
            "kind" => "add_on",
            "name" => "php_add_on",
            "neverExpires" => "false",
            "numberOfBillingCycles" => "1"
        ];

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . "/modifications/create_modification_for_tests";
        $http->post($path, ["modification" => $addOnParams]);

        $addOns = Braintree\AddOn::all();

        foreach ($addOns as $addOn)
        {
            if ($addOn->id == $newId)
            {
                $actualAddOn = $addOn;
            }
        }

        $this->assertNotNull($actualAddOn);
        $this->assertEquals($addOnParams["amount"], $actualAddOn->amount);
        $this->assertEquals($addOnParams["description"], $actualAddOn->description);
        $this->assertEquals($addOnParams["id"], $actualAddOn->id);
        $this->assertEquals($addOnParams["kind"], $actualAddOn->kind);
        $this->assertEquals($addOnParams["name"], $actualAddOn->name);
        $this->assertFalse($actualAddOn->neverExpires);
        $this->assertEquals($addOnParams["numberOfBillingCycles"], $actualAddOn->numberOfBillingCycles);
    }

    public function testGatewayAll_returnsAllAddOns()
    {
        $newId = strval(rand());

        $addOnParams = [
            "amount" => "100.00",
            "description" => "some description",
            "id" => $newId,
            "kind" => "add_on",
            "name" => "php_add_on",
            "neverExpires" => "false",
            "numberOfBillingCycles" => "1"
        ];

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . "/modifications/create_modification_for_tests";
        $http->post($path, ["modification" => $addOnParams]);

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $addOns = $gateway->addOn()->all();

        foreach ($addOns as $addOn)
        {
            if ($addOn->id == $newId)
            {
                $actualAddOn = $addOn;
            }
        }

        $this->assertNotNull($actualAddOn);
        $this->assertEquals($addOnParams["amount"], $actualAddOn->amount);
        $this->assertEquals($addOnParams["description"], $actualAddOn->description);
        $this->assertEquals($addOnParams["id"], $actualAddOn->id);
    }
}
