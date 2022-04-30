<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class GatewayTest extends Setup
{
    public function setup()
    {
        Braintree\Configuration::reset();
    }

    public function teardown()
    {
        Braintree\Configuration::environment('development');
        Braintree\Configuration::merchantId('integration_merchant_id');
        Braintree\Configuration::publicKey('integration_public_key');
        Braintree\Configuration::privateKey('integration_private_key');
    }

    /**
     * @expectedException Braintree\Exception\Configuration
    * @expectedExceptionMessage needs to be set (or accessToken needs to be passed to Braintree\Gateway).
     */
    public function testConfigGetsAssertedValid()
    {
        Braintree\Configuration::environment('development');
        //Braintree\Configuration::merchantId('integration_merchant_id');
        Braintree\Configuration::publicKey('integration_public_key');
        Braintree\Configuration::privateKey('integration_private_key');

        $gateway = new Braintree\Gateway(Braintree\Configuration::$global);
        $gateway->addOn();
    }

    public function testConstructWithArrayOfCredentials()
    {
        $gateway = new Braintree\Gateway([
            'environment' => 'sandbox',
            'merchantId' => 'sandbox_merchant_id',
            'publicKey' => 'sandbox_public_key',
            'privateKey' => 'sandbox_private_key'
        ]);

        $this->assertEquals('sandbox', $gateway->config->getEnvironment());
        $this->assertEquals('sandbox_merchant_id', $gateway->config->getMerchantId());
    }
}
