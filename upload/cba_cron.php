<?php
/*
 * Periodically checks for status changes for orders which were paid
 * using Checkout by Amazon
 */

require('admin/config.php');

require(DIR_SYSTEM . 'library/db.php');
require(DIR_SYSTEM . 'library/cba.php');
require(DIR_SYSTEM . 'library/log.php');

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

$settings = array();

$rows = $db->query("SELECT `key`, `value` FROM `" . DB_PREFIX . "setting` WHERE `group` = 'amazon_checkout'")->rows;

foreach($rows as $row) {
    $settings[$row['key']] = $row['value'];
}

if (!$settings['amazon_checkout_status']) {
    exit();
}

$cba = new CBA($settings['amazon_checkout_merchant_id'], $settings['amazon_checkout_access_key'], $settings['amazon_checkout_access_secret']);
$cba->setMode($settings['amazon_checkout_mode']);

$cba->processOrderReports($settings, $db);

$cba->processFeedResponses($settings, $db);

$db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = 'amazon_checkout' AND `key` = 'amazon_checkout_last_cron_job_run'");

$db->query("INSERT INTO `" . DB_PREFIX . "setting` (`store_id`, `group`, `key`, `value`, `serialized`) VALUES (0, 'amazon_checkout', 'amazon_checkout_last_cron_job_run', NOW(), 0)");