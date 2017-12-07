<?php
/**
 * Acknowledge an authorized order.
 *
 * Merchants will receive the order confirmation push until the order
 * has been acknowledged.
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
$order->acknowledge();
