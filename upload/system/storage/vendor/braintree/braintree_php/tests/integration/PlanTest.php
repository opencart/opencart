<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Test\Helper;
use Braintree;

class PlanTest extends Setup
{
    public function testAll_withNoPlans_returnsEmptyArray()
    {
        Helper::testMerchantConfig();
        $plans = Braintree\Plan::all();
        $this->assertEquals($plans, []);
        self::integrationMerchantConfig();

    }

    public function testAll_returnsAllPlans()
    {
        $newId = strval(rand());
        $params = [
            "id" => $newId,
            "billingDayOfMonth" => "1",
            "billingFrequency" => "1",
            "currencyIsoCode" => "USD",
            "description" => "some description",
            "name" => "php test plan",
            "numberOfBillingCycles" => "1",
            "price" => "1.00",
            "trialPeriod" => "false"
        ];

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/plans/create_plan_for_tests';
        $http->post($path, ["plan" => $params]);

        $addOnParams = [
            "kind" => "add_on",
            "plan_id" => $newId,
            "amount" => "1.00",
            "name" => "add_on_name"
        ];

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/modifications/create_modification_for_tests';
        $http->post($path, ['modification' => $addOnParams]);

        $discountParams = [
            "kind" => "discount",
            "plan_id" => $newId,
            "amount" => "1.00",
            "name" => "discount_name"
        ];

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/modifications/create_modification_for_tests';
        $http->post($path, ["modification" => $discountParams]);

        $plans = Braintree\Plan::all();

        foreach ($plans as $plan)
        {
            if ($plan->id == $newId)
            {
                $actualPlan = $plan;
            }
        }

        $this->assertNotNull($actualPlan);
        $this->assertEquals($params["billingDayOfMonth"], $actualPlan->billingDayOfMonth);
        $this->assertEquals($params["billingFrequency"], $actualPlan->billingFrequency);
        $this->assertEquals($params["currencyIsoCode"], $actualPlan->currencyIsoCode);
        $this->assertEquals($params["description"], $actualPlan->description);
        $this->assertEquals($params["name"], $actualPlan->name);
        $this->assertEquals($params["numberOfBillingCycles"], $actualPlan->numberOfBillingCycles);
        $this->assertEquals($params["price"], $actualPlan->price);

        $addOn = $actualPlan->addOns[0];
        $this->assertEquals($addOnParams["name"], $addOn->name);

        $discount = $actualPlan->discounts[0];
        $this->assertEquals($discountParams["name"], $discount->name);
    }

    public function testGatewayAll_returnsAllPlans()
    {
        $newId = strval(rand());
        $params = [
            "id" => $newId,
            "billingDayOfMonth" => "1",
            "billingFrequency" => "1",
            "currencyIsoCode" => "USD",
            "description" => "some description",
            "name" => "php test plan",
            "numberOfBillingCycles" => "1",
            "price" => "1.00",
            "trialPeriod" => "false"
        ];

        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/plans/create_plan_for_tests';
        $http->post($path, ["plan" => $params]);

        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
        $plans = $gateway->plan()->all();

        foreach ($plans as $plan)
        {
            if ($plan->id == $newId)
            {
                $actualPlan = $plan;
            }
        }

        $this->assertNotNull($actualPlan);
        $this->assertEquals($params["billingDayOfMonth"], $actualPlan->billingDayOfMonth);
        $this->assertEquals($params["billingFrequency"], $actualPlan->billingFrequency);
        $this->assertEquals($params["currencyIsoCode"], $actualPlan->currencyIsoCode);
        $this->assertEquals($params["description"], $actualPlan->description);
        $this->assertEquals($params["name"], $actualPlan->name);
        $this->assertEquals($params["numberOfBillingCycles"], $actualPlan->numberOfBillingCycles);
        $this->assertEquals($params["price"], $actualPlan->price);
    }
}
