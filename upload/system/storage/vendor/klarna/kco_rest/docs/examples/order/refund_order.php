<?php
/**
 * Refund an amount of a captured order.
 *
 * The refunded amount will be credited to the customer.
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
$order->refund([
    "refunded_amount" => 3000,
    "description" => "Refunding half the tomatoes",
    "order_lines" => [
        [
            "type" => "physical",
            "reference" => "123050",
            "name" => "Tomatoes",
            "quantity" => 5,
            "quantity_unit" => "kg",
            "unit_price" => 600,
            "tax_rate" => 2500,
            "total_amount" => 3000,
            "total_tax_amount" => 600
        ]
    ]
]);
