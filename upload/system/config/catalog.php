<?php
// Site
$_['site_url']         = HTTP_SERVER;
$_['site_ssl']         = HTTPS_SERVER;

// Url
$_['url_autostart']    = false;

// Database
$_['db_autostart']     = true;
$_['db_engine']        = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']      = DB_HOSTNAME;
$_['db_username']      = DB_USERNAME;
$_['db_password']      = DB_PASSWORD;
$_['db_database']      = DB_DATABASE;
$_['db_port']          = DB_PORT;

// Session
$_['session_autostart'] = false;

// Template
$_['template_engine']   = 'twig';

// Autoload Libraries
$_['library_autoload']  = array(
	'openbay'
);

// Actions
$_['action_pre_action'] = array(
	'startup/session',
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/maintenance',
	'startup/seo_url'
);

// Action Events
$_['action_event'] = array(
	// Themes
	'view/*/before' => array(
		'event/theme'
	),
	// Language
	'language/*/after' => array(
		'event/translation'
	),
	// E-Mail	
	'model/account/customer/addCustomer/after' => array(
		'mail/register',
	),	
	'model/account/customer/editCode/after' => array(
		'mail/forgotten',
		'event/customer_activity',
	),	
	'model/checkout/order/addOrderHistory/before' => array(
		'mail/order_history/before',
	),		
	'model/checkout/order/addOrderHistory/after' => array(
		'mail/order_history/after',
	),
	'model/account/affiliate/addAffiliate/after' => array(
		'mail/register',
	),	
	// Activity
	'model/account/customer/addCustomer/after' => array(
		'event/customer_activity/addCustomer',
	),	
	'model/account/customer/editCustomer/after' => array(
		'event/customer_activity/editCustomer',
	),	
	'model/account/customer/editPassword/after' => array(
		'event/customer_activity/editPassword',
	),
	'model/account/customer/editCode/after' => array(
		'event/customer_activity/forgotten',
	),	
	'model/account/customer/deleteLoginAttempts/after' => array(
		'event/customer_activity/login',
	),	
	'model/account/address/addAddress/after' => array(
		'event/customer_activity/addAddress',
	),	
	'model/account/address/editAddress/after' => array(
		'event/customer_activity/editAddress',
	),	
	'model/account/address/deleteAddress/after' => array(
		'event/customer_activity/deleteAddress',
	),
	'model/checkout/order/addOrderHistory/afterr' => array(
		'event/customer_activity/addOrderHistory',
	),		
	'model/account/return/addReturn/after' => array(
		'event/customer_activity/addReturn',
	),
	
	
	'model/affiliate/affiliate/addAffiliate/after' => array(
		'event/activity/addAffiliate',
	),	
	'model/affiliate/affiliate/editAffiliate/after' => array(
		'event/activity/editAffiliate',
	),	
					
	//'controller/*/before' => array(
	//	'event/debug/before'
	//),
	//'controller/*/after'  => array(
	//	'event/debug/after'
	//)
);



$_['text_customer_order_account']  = '<a href="customer_id=%d">%s</a> added a <a href="order_id=%d">new order</a>.';
$_['text_customer_order_guest']    = '%s created a <a href="order_id=%d">new order</a>.';


$_['text_affiliate_edit']          = '<a href="affiliate_id=%d">%s</a> updated their account details.';
$_['text_affiliate_forgotten']     = '<a href="affiliate_id=%d">%s</a> has requested a new password.';
$_['text_affiliate_login']         = '<a href="affiliate_id=%d">%s</a> logged in.';
$_['text_affiliate_password']      = '<a href="affiliate_id=%d">%s</a> updated their account password.';
$_['text_affiliate_payment']       = '<a href="affiliate_id=%d">%s</a> updated their payment details.';
$_['text_affiliate_register']      = '<a href="affiliate_id=%d">%s</a> registered for a new account.';
