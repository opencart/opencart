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
 * Update the setup options for a specific button key.
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
$buttonKey = getenv('BUTTON_KEY') ?: 'ab12345c-1234-abcd-1234-1234abcd';

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
    $buttonsApi = new Klarna\Rest\InstantShopping\ButtonKeys($connector, $buttonKey);
    $data = [
        'merchant_name' => 'New name',
        'merchant_urls' => [
            'place_order' => 'https://example.com/place-callback',
            'push' => 'https://example.com/push-callback',
            'confirmation' => 'https://example.com/confirmation-callback',
            'terms' => 'https://example.com/terms-callback',
            'notification' => 'https://example.com/notification-callback',
            'update' => 'https://example.com/update-callback',
          ],
        'shipping_options' => [
          [
            'id' => 'my-new-shipping-id',
            'name' => 'Priority delivery',
            'description' => '',
            'price' => 300,
            'tax_amount' => 0,
            'tax_rate' => 0,
            'preselected' => false,
            'shipping_method' => 'PRIME_DELIVERY',
          ],
        ],
    ];
    $button = $buttonsApi->update($data);

    echo 'Button has been successfully updated' . PHP_EOL;
    print_r($button);
} catch (Exception $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}
