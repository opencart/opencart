<?php
define('VERSION', '2.3.0.3_rc');

define('ADMIN_USERNAME', '');
define('ADMIN_PASSWORD', '');

define('CONFIG_ADMIN', __DIR__ . '/../../upload/admin/config.php');
define('CONFIG_CATALOG', __DIR__ . '/../../upload/config.php');
define('SQL_FILE', __DIR__ . '/../../upload/install/opencart.sql');

// Settings for Amazon Payments' Selenium tests
define('AMAZON_PAYMENTS_SELLER_ID', '');
define('AMAZON_PAYMENTS_ACCESS_KEY', '');
define('AMAZON_PAYMENTS_ACCESS_SECRET', '');
define('AMAZON_PAYMENTS_COUNTRY', '');
define('AMAZON_PAYMENTS_USERNAME', '');
define('AMAZON_PAYMENTS_PASSWORD', '');
define('AMAZON_PAYMENTS_ADDRESS_POSITION', 1);
define('AMAZON_PAYMENTS_CARDS_POSITION', 1);

// Settings for PayPal Express Checkout's Selenium tests
define('PP_EXPRESS_API_USERNAME', '');
define('PP_EXPRESS_API_PASSWORD', '');
define('PP_EXPRESS_API_SIGNATURE', '');
define('PP_EXPRESS_USERNAME', '');
define('PP_EXPRESS_PASSWORD', '');

// Settings for SagePay Direct's selenium tests
define('SAGEPAY_DIRECT_VENDOR', '');

// Settings for OpenBay Pro
define('OPENBAY_EBAY_TOKEN', '');
define('OPENBAY_EBAY_SECRET', '');
