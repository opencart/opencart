<?php
/**
 * Update billing and/or shipping address for an order.
 *
 * This is subject to customer credit check.
 */

require_once dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$orderId = getenv('ORDER_ID') ?: '12345';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

$order = new Klarna\Rest\OrderManagement\Order($connector, $orderId);
$order->updateCustomerDetails([
    "billing_address" => [
        "email" => "user@example.com",
        "phone" => "57-3895734"
    ],
    "shipping_address" => [
        "email" => "user@example.com",
        "phone" => "57-3895734"
    ]
]);
