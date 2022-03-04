<?php
/**
 * Copyright 2019 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Generate a consumer token.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

/**
 * Follow the link to get your credentials: https://github.com/klarna/kco_rest_php/#api-credentials
 *
 * Make sure that your credentials belong to the right endpoint. If you have credentials for the US Playground,
 * such credentials will not work for the EU Playground and you will get 401 Unauthorized exception.
 */
$merchantId = getenv('USERNAME') ?: 'K123456_abcd12345';
$sharedSecret = getenv('PASSWORD') ?: 'sharedSecret';
$authorizationToken = getenv('AUTH_TOKEN') ?: 'authorizationToken';

/*
EU_BASE_URL = 'https://api.klarna.com'
EU_TEST_BASE_URL = 'https://api.playground.klarna.com'
NA_BASE_URL = 'https://api-na.klarna.com'
NA_TEST_BASE_URL = 'https://api-na.playground.klarna.com'
OC_BASE_URL = 'https://api-oc.klarna.com'
OC_TEST_BASE_URL = 'https://api-oc.playground.klarna.com'
*/
$apiEndpoint = Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;

$connector = Klarna\Rest\Transport\GuzzleConnector::create(
    $merchantId,
    $sharedSecret,
    $apiEndpoint
);

$data = [
    "purchase_country" => "GB",
    "purchase_currency" => "GBP",
    "locale" => "en-GB",
    "billing_address" => [
        "given_name" => "John",
        "family_name" => "Doe",
        "email" => "johndoe@example.com",
        "title" => "Mr",
        "street_address" => "13 New Burlington St",
        "street_address2" => "Apt 214",
        "postal_code" => "W13 3BG",
        "city" => "London",
        "region" => "",
        "phone" => "01895808221",
        "country" => "GB"
    ],
    "customer" => [ // MUST MATCH line by line to the customer details that was used to get an Authorization Token
        "date_of_birth" => "1970-01-01",
        "gender" => "male",
    ],
    "description" => "For testing purposes",
    "intended_use" => "SUBSCRIPTION"
];

try {
    $order = new Klarna\Rest\Payments\Orders($connector, $authorizationToken);
    $token = $order->generateToken($data);

    print_r($token);
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
