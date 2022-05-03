<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class ApplePayTest extends Setup
{
    private static $gateway;

    public static function setUpBeforeClass()
    {
        self::$gateway = self::_buildMerchantGateway();
    }

    public static function tearDownAfterClass()
    {
        self::$gateway = null;
    }

    private static function _buildMerchantGateway()
    {
        $gateway = new Braintree\Gateway([
            'clientId' => 'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret',
        ]);

        $result = $gateway->merchant()->create([
            'email' => 'name@email.com',
            'countryCodeAlpha3' => 'USA',
            'paymentMethods' => ['credit_card', 'paypal'],
        ]);

        return new Braintree\Gateway([
            'accessToken' => $result->credentials->accessToken,
        ]);
    }

    public function testRegisterDomainWithExpectedStubbedResult()
    {
        $result = self::$gateway->applePay()->registerDomain('domain');
        $this->assertEquals(true, $result->success);
    }

    public function testValidationErrorWhenRegisteringNoDomain()
    {
        $result = self::$gateway->applePay()->registerDomain('');
        $this->assertEquals(false, $result->success);
        $this->assertEquals(1, preg_match('/Domain name is required\./', $result->message));
    }

    public function testUnregisterDomainWithExpectedStubbedResult()
    {
        $domain = 'example.com';
        $result = self::$gateway->applePay()->unregisterDomain($domain);
        $this->assertEquals(true, $result->success);
    }

    public function testUnregisterDomainWithSpecialCharactersWithExpectedStubbedResult()
    {
        $domain = 'ex&mple.com';
        $result = self::$gateway->applePay()->unregisterDomain($domain);
        $this->assertEquals(true, $result->success);
    }

    public function testUnregisterDomainWithSchemeWithExpectedStubbedResult()
    {
        $domain = 'http://example.com';
        $result = self::$gateway->applePay()->unregisterDomain($domain);
        $this->assertEquals(true, $result->success);
    }

    public function testRegisteredDomainsWithExpectedStubbedResult()
    {
        $result = self::$gateway->applePay()->registeredDomains();
        $this->assertEquals(true, $result->success);
        $registeredDomains = $result->applePayOptions->domains;
        $this->assertEmpty(array_diff(['www.example.com'], $registeredDomains));
    }
}
