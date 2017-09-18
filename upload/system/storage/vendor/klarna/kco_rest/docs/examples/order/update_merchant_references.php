<?php
/**
 * Update one or both merchant references.
 *
 * Only the reference(s) in the body will be updated. To clear a reference,
 * set its value to "" (empty string).
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
$order->updateMerchantReferences([
    "merchant_reference1" => "15632423",
    "merchant_reference2" => "special order"
]);
