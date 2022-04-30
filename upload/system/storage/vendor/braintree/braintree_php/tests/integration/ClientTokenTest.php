<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test;
use Test\Setup;
use Braintree;

class ClientTokenTest extends Setup
{
    public function test_ClientTokenAuthorizesRequest()
    {
        $clientToken = Test\Helper::decodedClientToken();
        $authorizationFingerprint = json_decode($clientToken)->authorizationFingerprint;
        $http = new HttpClientApi(Braintree\Configuration::$global);
        $response = $http->get_cards([
            "authorization_fingerprint" => $authorizationFingerprint,
            "shared_customer_identifier" => "fake_identifier",
            "shared_customer_identifier_type" => "testing",
        ]);

        $this->assertEquals(200, $response["status"]);
    }

    public function test_VersionOptionSupported()
    {
        $clientToken = Braintree\ClientToken::generate(["version" => 1]);
        $version = json_decode($clientToken)->version;
        $this->assertEquals(1, $version);
    }

    public function test_VersionDefaultsToTwo()
    {
        $encodedClientToken = Braintree\ClientToken::generate();
        $clientToken = base64_decode($encodedClientToken);
        $version = json_decode($clientToken)->version;
        $this->assertEquals(2, $version);
    }

    public function testGateway_VersionDefaultsToTwo()
    {
        $gateway = new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key',
        ]);
        $encodedClientToken = $gateway->clientToken()->generate();
        $clientToken = base64_decode($encodedClientToken);
        $version = json_decode($clientToken)->version;
        $this->assertEquals(2, $version);
    }

    public function test_GatewayRespectsVerifyCard()
    {
        $result = Braintree\Customer::create();
        $this->assertTrue($result->success);
        $customerId = $result->customer->id;

        $clientToken = Test\Helper::decodedClientToken([
            "customerId" => $customerId,
            "options" => [
                "verifyCard" => true
            ]
        ]);
        $authorizationFingerprint = json_decode($clientToken)->authorizationFingerprint;

        $http = new HttpClientApi(Braintree\Configuration::$global);
        $response = $http->post('/client_api/v1/payment_methods/credit_cards.json', json_encode([
            "credit_card" => [
                "number" => "4000111111111115",
                "expirationDate" => "11/2099"
            ],
            "authorization_fingerprint" => $authorizationFingerprint,
            "shared_customer_identifier" => "fake_identifier",
            "shared_customer_identifier_type" => "testing"
        ]));

        $this->assertEquals(422, $response["status"]);
    }

    public function test_GatewayRespectsFailOnDuplicatePaymentMethod()
    {
        $result = Braintree\Customer::create();
        $this->assertTrue($result->success);
        $customerId = $result->customer->id;

        $clientToken = Test\Helper::decodedClientToken([
            "customerId" => $customerId,
        ]);
        $authorizationFingerprint = json_decode($clientToken)->authorizationFingerprint;

        $http = new HttpClientApi(Braintree\Configuration::$global);
        $response = $http->post('/client_api/v1/payment_methods/credit_cards.json', json_encode([
            "credit_card" => [
                "number" => "4242424242424242",
                "expirationDate" => "11/2099"
            ],
            "authorization_fingerprint" => $authorizationFingerprint,
            "shared_customer_identifier" => "fake_identifier",
            "shared_customer_identifier_type" => "testing"
        ]));
        $this->assertEquals(201, $response["status"]);

        $clientToken = Test\Helper::decodedClientToken([
            "customerId" => $customerId,
            "options" => [
                "failOnDuplicatePaymentMethod" => true
            ]
        ]);
        $authorizationFingerprint = json_decode($clientToken)->authorizationFingerprint;

        $http = new HttpClientApi(Braintree\Configuration::$global);
        $response = $http->post('/client_api/v1/payment_methods/credit_cards.json', json_encode([
            "credit_card" => [
                "number" => "4242424242424242",
                "expirationDate" => "11/2099"
            ],
            "authorization_fingerprint" => $authorizationFingerprint,
            "shared_customer_identifier" => "fake_identifier",
            "shared_customer_identifier_type" => "testing"
        ]));
        $this->assertEquals(422, $response["status"]);
    }

    public function test_GatewayRespectsMakeDefault()
    {
        $result = Braintree\Customer::create();
        $this->assertTrue($result->success);
        $customerId = $result->customer->id;

        $result = Braintree\CreditCard::create([
            'customerId' => $customerId,
            'number' => '4111111111111111',
            'expirationDate' => '11/2099'
        ]);
        $this->assertTrue($result->success);

        $clientToken = Test\Helper::decodedClientToken([
            "customerId" => $customerId,
            "options" => [
                "makeDefault" => true
            ]
        ]);
        $authorizationFingerprint = json_decode($clientToken)->authorizationFingerprint;

        $http = new HttpClientApi(Braintree\Configuration::$global);
        $response = $http->post('/client_api/v1/payment_methods/credit_cards.json', json_encode([
            "credit_card" => [
                "number" => "4242424242424242",
                "expirationDate" => "11/2099"
            ],
            "authorization_fingerprint" => $authorizationFingerprint,
            "shared_customer_identifier" => "fake_identifier",
            "shared_customer_identifier_type" => "testing"
        ]));

        $this->assertEquals(201, $response["status"]);

        $customer = Braintree\Customer::find($customerId);
        $this->assertEquals(2, count($customer->creditCards));
        foreach ($customer->creditCards as $creditCard) {
            if ($creditCard->last4 == "4242") {
                $this->assertTrue($creditCard->default);
            }
        }
    }

    public function test_ClientTokenAcceptsMerchantAccountId()
    {
        $expectedMerchantAccountId = Test\Helper::nonDefaultMerchantAccountId();
        $clientToken = Test\Helper::decodedClientToken([
            'merchantAccountId' => $expectedMerchantAccountId
        ]);
        $merchantAccountId = json_decode($clientToken)->merchantAccountId;

        $this->assertEquals($expectedMerchantAccountId, $merchantAccountId);
    }

    public function test_GenerateRaisesExceptionOnGateway422()
    {
        $this->setExpectedException('InvalidArgumentException', 'customer_id');

        Braintree\ClientToken::generate([
            "customerId" => "not_a_customer"
        ]);
    }
}
