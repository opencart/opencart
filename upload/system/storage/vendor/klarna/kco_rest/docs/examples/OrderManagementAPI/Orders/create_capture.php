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
 * Capture the supplied amount.
 *
 * Use this call when fulfillment is completed, e.g. physical goods are
 * being shipped to the customer.
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
$orderId = getenv('ORDER_ID') ?: '12345';

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

try {
    $order = new Klarna\Rest\OrderManagement\Order($connector, $orderId);
    $order->createCapture([
        "captured_amount" => 6000,
        "description" => "Shipped part of the order",
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
            ]
        ],
        "shipping_info" => [
            [
                "shipping_company" => "DHL",
                "shipping_method" => "Home",
                "tracking_uri" => "http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=1234567890",
                "tracking_number" => "1234567890",
                "return_tracking_number" => "E-55-KL",
                "return_shipping_company" => "DHL",
                "return_tracking_uri" => "http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=98389222"
            ]
        ]
    ]);

    echo 'Capture has been created';
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
