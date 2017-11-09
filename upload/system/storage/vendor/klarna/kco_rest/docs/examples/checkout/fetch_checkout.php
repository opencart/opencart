<?php
/**
 * Retrieve a checkout order.
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

$checkout = new Klarna\Rest\Checkout\Order($connector, $orderId);
$checkout->fetch();
