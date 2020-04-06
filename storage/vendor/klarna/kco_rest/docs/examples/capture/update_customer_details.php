<?php
/**
 * Update the billing address for a capture. Shipping address can not be updated.
 */

require_once dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';
$orderId = getenv('ORDER_ID') ?: '12345';
$captureId = getenv('CAPTURE_ID') ?: '34567';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

$order = new Klarna\Rest\OrderManagement\Order($connector, $orderId);

$capture = $order->fetchCapture($captureId);
$capture->updateCustomerDetails([
    "billing_address" => [
        "email" => "user@example.com",
        "phone" => "57-3895734"
    ]
]);
