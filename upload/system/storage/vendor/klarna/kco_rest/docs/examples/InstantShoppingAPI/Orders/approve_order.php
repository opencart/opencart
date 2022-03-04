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
 * Approve the authorized order and place an order identified by the authorization token.
 */

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';

/**
 * Follow the link to get your credentials => https://github.com/klarna/kco_rest_php/#api-credentials
 *
 * Make sure that your credentials belong to the right endpoint. If you have credentials for the US Playground,
 * such credentials will not work for the EU Playground and you will get 401 Unauthorized exception.
 */
$merchantId = getenv('USERNAME') ?: 'K123456_abcd12345';
$sharedSecret = getenv('PASSWORD') ?: 'sharedSecret';
$authToken = getenv('AUTH_TOKEN') ?: 'authorization_token';

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

$order = [
    "order_id" => "f3392f8b-6116-4073-ab96-e330819e2c07",
    "purchase_country" => "gb",
    "purchase_currency" => "gbp",
    "locale" => "en-gb",
    "order_amount" => 10000,
    "order_tax_amount" => 2000,
    "billing_address" => [
        "given_name" => "Jane",
        "family_name"=> "Doe",
        "email"=> "jane-doe@example.com",
        "title"=> "Ms",
        "street_address"=> "Lombard St 10",
        "street_address2"=> "Apt 214",
        "postal_code"=> "90210",
        "city"=> "Beverly Hills",
        "region"=> "CA",
        "phone"=> "333444555",
        "country"=> "US"
    ],
    "order_lines" => [
        [
            "type" => "physical",
            "reference" => "123050",
            "name" => "Tomatoes",
            "quantity" => 10,
            "quantity_unit" => "kg",
            "unit_price" => 600,
            "tax_rate" => 2500,
            "total_amount" => 6000,
            "total_tax_amount" => 1200
        ],
        [
            "type" => "physical",
            "reference" => "543670",
            "name" => "Bananas",
            "quantity" => 1,
            "quantity_unit" => "bag",
            "unit_price" => 5000,
            "tax_rate" => 2500,
            "total_amount" => 4000,
            "total_discount_amount" => 1000,
            "total_tax_amount" => 800
        ]
    ],
    "merchant_urls" => [
        "terms" => "https://example.com/toc",
        "checkout" => "https://example.com/checkout?klarna_order_id={checkout.order.id}",
        "confirmation" => "https://example.com/thank-you?klarna_order_id={checkout.order.id}",
        "push" => "https://example.com/create_order?klarna_order_id={checkout.order.id}"
    ],
    "customer" => [
        "date_of_birth" => "1995-10-20",
        "title" => "Mr",
        "gender" => "male",
        "last_four_ssn" => "0512",
        "national_identification_number" => "3108971100",
        "type" => "person",
        "vat_id" => "string",
        "organization_registration_id" => "556737-0431",
        "organization_entity_type" => "LIMITED_COMPANY"
    ]
];

try {
    $orderApi = new Klarna\Rest\InstantShopping\Orders($connector, $authToken);
    $status = $orderApi->approve($order);

    echo 'The order has been approved' . PHP_EOL;
    print_r($status);
} catch (Exception $e) {
    echo 'Caught exception => ' . $e->getMessage() . "\n";
}
