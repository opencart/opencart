<?php
/**
 * Create a checkout order with a extra merchant data attachment.
 */

require_once dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php';

const EMD_FORMAT = 'Y-m-d\TH:m:s\Z';

$merchantId = getenv('MERCHANT_ID') ?: '0';
$sharedSecret = getenv('SHARED_SECRET') ?: 'sharedSecret';

$connector = Klarna\Rest\Transport\Connector::create(
    $merchantId,
    $sharedSecret,
    Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
);

$emd = [
    "payment_history_full" => [
        [
            "unique_account_identifier" => "Test Testperson",
            "payment_option" => "card",
            "number_paid_purchases" => 1,
            "total_amount_paid_purchases" => 10000,
            "date_of_last_paid_purchase" => (new DateTime())->format(EMD_FORMAT),
            "date_of_first_paid_purchase" => (new DateTime())->format(EMD_FORMAT)
        ]
    ]
];

$checkout = new Klarna\Rest\Checkout\Order($connector);
$checkout->create([
    "purchase_country" => "gb",
    "purchase_currency" => "gbp",
    "locale" => "en-gb",
    "order_amount" => 10000,
    "order_tax_amount" => 2000,
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
        ],
        [
            "type" => "physical",
            "reference" => "543670",
            "name" => "Bananas",
            "quantity" => 1,
            "quantity_unit" => "bag",
            "unit_price" => 5000,
            "tax_rate" => 2500,
            "total_amount" => 4000,
            "total_discount_amount" => 1000,
            "total_tax_amount" => 800
        ]
    ],
    "merchant_urls" => [
        "terms" => "http://www.merchant.com/toc",
        "checkout" => "http://www.merchant.com/checkout?klarna_order_id={checkout.order.id}",
        "confirmation" => "http://www.merchant.com/thank-you?klarna_order_id={checkout.order.id}",
        "push" => "http://www.merchant.com/create_order?klarna_order_id={checkout.order.id}"
    ],
    "attachment" => [
        "content_type" => "application/vnd.klarna.internal.emd-v2+json",
        "body" => json_encode($emd)
    ]
]);

$checkout->fetch();

// Store checkout order id
$orderId = $checkout->getId();
