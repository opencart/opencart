<?php
require_once __DIR__ . '/../TestHelper.php';

class Braintree_MerchantTest extends PHPUnit_Framework_TestCase
{
    function testCreateMerchant()
    {
        $gateway = new Braintree_Gateway(array(
            'clientId' => 'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret',
        ));
        $result = $gateway->merchant()->create(array(
            'email' => 'name@email.com',
            'countryCodeAlpha3' => 'USA',
            'paymentMethods' => ['credit_card', 'paypal'],
        ));

        $this->assertEquals(true, $result->success);
        $merchant = $result->merchant;
        $this->assertNotNull($merchant->id);
        $credentials = $result->credentials;
        $this->assertNotNull($credentials->accessToken);
    }

    /**
    * @expectedException Braintree_Exception_Configuration
    * @expectedExceptionMessage clientId needs to be passed to Braintree_Gateway.
    */
    function testAssertsHasCredentials()
    {
        $gateway = new Braintree_Gateway(array(
            'clientSecret' => 'client_secret$development$integration_client_secret',
        ));
        $gateway->merchant()->create(array(
            'email' => 'name@email.com',
            'countryCodeAlpha3' => 'USA',
        ));
    }

    function testBadPaymentMethods()
    {
        $gateway = new Braintree_Gateway(array(
            'clientId' => 'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret',
        ));
        $result = $gateway->merchant()->create(array(
            'email' => 'name@email.com',
            'countryCodeAlpha3' => 'USA',
            'paymentMethods' => ['fake_money'],
        ));

        $this->assertEquals(false, $result->success);
        $errors = $result->errors->forKey('merchant')->onAttribute('paymentMethods');
        $this->assertEquals(Braintree_Error_Codes::MERCHANT_ACCOUNT_PAYMENT_METHODS_ARE_INVALID, $errors[0]->code);
    }
}
