<?php
namespace Test;

require_once __DIR__ . '/Setup.php';

use DateTime;
use DateTimeZone;
use Braintree;

class Helper
{
    public static $valid_nonce_characters = 'bcdfghjkmnpqrstvwxyz23456789';

    public static function testMerchantConfig()
    {
        Braintree\Configuration::reset();

        Braintree\Configuration::environment('development');
        Braintree\Configuration::merchantId('test_merchant_id');
        Braintree\Configuration::publicKey('test_public_key');
        Braintree\Configuration::privateKey('test_private_key');
    }

    public static function integrationMerchantGateway()
    {
        return new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'integration_merchant_id',
            'publicKey' => 'integration_public_key',
            'privateKey' => 'integration_private_key'
        ]);
    }

    public static function advancedFraudIntegrationMerchantGateway()
    {
        return new Braintree\Gateway([
            'environment' => 'development',
            'merchantId' => 'advanced_fraud_integration_merchant_id',
            'publicKey' => 'advanced_fraud_integration_public_key',
            'privateKey' => 'advanced_fraud_integration_private_key'
        ]);
    }

    public static function defaultMerchantAccountId()
    {
        return 'sandbox_credit_card';
    }

    public static function nonDefaultMerchantAccountId()
    {
        return 'sandbox_credit_card_non_default';
    }

    public static function nonDefaultSubMerchantAccountId()
    {
        return 'sandbox_sub_merchant_account';
    }

    public static function threeDSecureMerchantAccountId()
    {
        return 'three_d_secure_merchant_account';
    }

    public static function fakeAmexDirectMerchantAccountId()
    {
        return 'fake_amex_direct_usd';
    }

    public static function fakeVenmoAccountMerchantAccountId()
    {
        return 'fake_first_data_venmo_account';
    }

    public static function usBankMerchantAccount() {
        return 'us_bank_merchant_account';
    }

    public static function anotherUsBankMerchantAccount() {
        return 'another_us_bank_merchant_account';
    }

    public static function createViaTr($regularParams, $trParams)
    {
        $trData = Braintree\TransparentRedirect::transactionData(
            array_merge($trParams, ["redirectUrl" => "http://www.example.com"])
        );
        return self::submitTrRequest(
            Braintree\TransparentRedirect::url(),
            $regularParams,
            $trData
        );
    }

    public static function submitTrRequest($url, $regularParams, $trData)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_HEADER, true);
        // curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array_merge($regularParams, ['tr_data' => $trData])));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        preg_match('/Location: .*\?(.*)/i', $response, $match);
        return trim($match[1]);
    }

    public static function suppressDeprecationWarnings()
    {
        set_error_handler("Test\Helper::_errorHandler", E_USER_NOTICE);
    }

    public static function _errorHandler($errno, $errstr, $errfile, $errline)
    {
        if (preg_match('/^DEPRECATED/', $errstr) == 0) {
            trigger_error('Unknown error received: ' . $errstr, E_USER_ERROR);
        }
    }

    public static function includes($collection, $targetItem)
    {
        foreach ($collection AS $item) {
            if ($item->id == $targetItem->id) {
                return true;
            }
        }
        return false;
    }

    public static function assertPrintable($object)
    {
        " " . $object;
    }

    public static function escrow($transactionId)
    {
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/transactions/' . $transactionId . '/escrow';
        $http->put($path);
    }

    public static function create3DSVerification($merchantAccountId, $params)
    {
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/three_d_secure/create_verification/' . $merchantAccountId;
        $response = $http->post($path, ['threeDSecureVerification' => $params]);
        return $response['threeDSecureVerification']['threeDSecureToken'];
    }

    public static function generate3DSNonce($params)
    {
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $path = Braintree\Configuration::$global->merchantPath() . '/three_d_secure/create_nonce/' . self::threeDSecureMerchantAccountId();
        $response = $http->post($path, $params);
        return $response['paymentMethodNonce']['nonce'];
    }

    public static function nowInEastern()
    {
        $eastern = new DateTimeZone('America/New_York');
        $now = new DateTime('now', $eastern);
        return $now->format('Y-m-d');
    }

    public static function decodedClientToken($params=[]) {
        $encodedClientToken = Braintree\ClientToken::generate($params);
        return base64_decode($encodedClientToken);
    }

    public static function generateValidUsBankAccountNonce($accountNumber = '567891234') {
        $client_token = json_decode(Helper::decodedClientToken(), true);
        $url = $client_token['braintree_api']['url'] . '/tokens';
        $token = $client_token['braintree_api']['access_token'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_URL, $url);

        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Braintree-Version: 2016-10-07';
        $headers[] = 'Authorization: Bearer ' . $token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $requestBody = [
            'type' => 'us_bank_account',
            'billing_address' => [
                'street_address' => '123 Ave',
                'region' => 'CA',
                'locality' => 'San Francisco',
                'postal_code' => '94112'
            ],
            'account_type' => 'checking',
            'ownership_type' => 'personal',
            'routing_number' => '021000021',
            'account_number' => $accountNumber,
            'first_name' => 'Dan',
            'last_name' => 'Schulman',
            'ach_mandate' => [
                'text' => 'cl mandate text'
            ]
        ];

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error_code = curl_errno($curl);
        curl_close($curl);
        $jsonResponse = json_decode($response, true);
        return $jsonResponse['data']['id'];
    }

    public static function generatePlaidUsBankAccountNonce() {
        $client_token = json_decode(Helper::decodedClientToken(), true);
        $url = $client_token['braintree_api']['url'] . '/tokens';
        $token = $client_token['braintree_api']['access_token'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_URL, $url);

        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Braintree-Version: 2016-10-07';
        $headers[] = 'Authorization: Bearer ' . $token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $requestBody = [
            'type' => 'plaid_public_token',
            'public_token' => 'good',
            'account_id' => 'plaid_account_id',
            'billing_address' => [
                'street_address' => '123 Ave',
                'region' => 'CA',
                'locality' => 'San Francisco',
                'postal_code' => '94112'
            ],
            'ownership_type' => 'personal',
            'first_name' => 'Dan',
            'last_name' => 'Schulman',
            'ach_mandate' => [
                'text' => 'cl mandate text'
            ]
        ];

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error_code = curl_errno($curl);
        curl_close($curl);
        $jsonResponse = json_decode($response, true);
        return $jsonResponse['data']['id'];
    }

    public static function generateInvalidUsBankAccountNonce() {
        $valid_characters = str_split(self::$valid_nonce_characters);
        $nonce = 'tokenusbankacct';
        for($i=0; $i<4; $i++) {
            $nonce = $nonce . '_';
            for($j=0; $j<6; $j++) {
                $t = rand(0, sizeof($valid_characters)-1);
                $nonce = $nonce . $valid_characters[$t];
            }
        }
        return $nonce . "_xxx";
    }

    public static function generateValidIdealPaymentId($amount = null) {
        if (null === $amount) {
            $amount = '100.00';
        }

        $client_token = json_decode(Helper::decodedClientToken([
            'merchantAccountId' => 'ideal_merchant_account'
        ]), true);

        $client = new Integration\HttpClientApi(Braintree\Configuration::$global);
        $configuration = $client->get_configuration([
            "authorization_fingerprint" => $client_token['authorizationFingerprint'],
        ]);

        $url = $client_token['braintree_api']['url'] . '/ideal-payments';
        $token = $client_token['braintree_api']['access_token'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_URL, $url);

        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Braintree-Version: 2015-11-01';
        $headers[] = 'Authorization: Bearer ' . $token;
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $requestBody = [
            'issuer' => 'RABONL2U',
            'order_id' => 'ABC123',
            'amount' => $amount,
            'currency' => 'EUR',
            'redirect_url' => 'https://braintree-api.com',
            'route_id' => $configuration->ideal->routeId,
        ];

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestBody));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error_code = curl_errno($curl);
        curl_close($curl);
        $jsonResponse = json_decode($response, true);
        return $jsonResponse['data']['id'];
    }

    public static function generateInvalidIdealPaymentId() {
        $valid_characters = str_split(self::$valid_nonce_characters);
        $ideal_payment_id = 'idealpayment';
        for($i=0; $i<4; $i++) {
            $ideal_payment_id = $ideal_payment_id . '_';
            for($j=0; $j<6; $j++) {
                $t = rand(0, sizeof($valid_characters)-1);
                $ideal_payment_id = $ideal_payment_id . $valid_characters[$t];
            }
        }
        return $ideal_payment_id . "_xxx";
    }

    public static function sampleNotificationFromXml($xml) {
        $config = Helper::integrationMerchantGateway()->config;
        $payload = base64_encode($xml) . "\n";
        $signature = $config->getPublicKey() . "|" . Braintree\Digest::hexDigestSha1($config->getPrivateKey(), $payload);

        return [
            'bt_signature' => $signature,
            'bt_payload' => $payload
        ];
    }
}
