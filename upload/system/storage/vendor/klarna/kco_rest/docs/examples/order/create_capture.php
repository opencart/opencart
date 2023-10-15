<?php
/**
 * Capture the supplied amount.
 *
 * Use this call when fulfillment is completed, e.g. physical goods are
 * being shipped to the customer.
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
