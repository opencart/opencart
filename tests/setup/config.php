<?php
define('VERSION', '2.3.0.3_rc');
define('ADMIN_USERNAME', '');
define('ADMIN_PASSWORD', '');

/*
 * Use the $settings array to change store settings. The key must match the store ID.
 */
$settings = array(
	0 => array(
		'config_maintenance' => 1,
	)
);

/*
 * Use the $module_settings array to install payment, shipping or feed modules
 */
$module_settings = array(
	'payment' => array(
		'cheque' => array(
			'cheque_status' => 1,
			'cheque_payable' => 'OpenCart test store',
			'cheque_order_status_id' => 1,
		),
		'free_checkout' => array(
			'free_checkout_status' => 1,
			'free_checkout_order_status_id' => 1,
		),
	),
	'shipping' => array(
		'item' => array(
			'item_status' => 1,
			'item_cost' => 1.25,
		),
	),
	'feed' => array(
		'google_sitemap' => array(
			'google_sitemap_status' => 1
		)
	),
);
