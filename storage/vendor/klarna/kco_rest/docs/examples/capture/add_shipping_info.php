<?php
/**
 * Appends shipping info to a capture.
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
$capture->addShippingInfo([
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
