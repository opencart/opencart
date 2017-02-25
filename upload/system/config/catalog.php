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
	'model/account/customer/addCustomer/after' => array(
		// E-Mail
		'mail/account_register',
		// Activity
		'event/customer_activity/addCustomer'
	),	
	'model/account/customer/editCustomer/after' => array(
		'event/customer_activity/editCustomer',
	),	
	'model/account/customer/editPassword/after' => array(
		'event/customer_activity/editPassword',
	),
	'model/account/customer/editCode/after' => array(
		// E-Mail
		'mail/account_forgotten',
		// Activity
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
	'model/checkout/order/addOrderHistory/before' => array(
		'mail/order_history/before',
	),		
	'model/checkout/order/addOrderHistory/after' => array(
		// E-Mail
		'mail/order_history/after',
		// Activity
		'event/customer_activity/addOrderHistory',
	),
	'model/account/return/addReturn/after' => array(
		'event/customer_activity/addReturn',
	),	
	'model/affiliate/affiliate/addAffiliate/after' => array(
		// E-Mail
		'mail/affiliate_register',
		// Activity
		'event/affiliate_activity/addAffiliate',
	),	
	'model/affiliate/affiliate/editAffiliate/after' => array(
		'event/affiliate_activity/editAffiliate',
	),	
	'model/affiliate/affiliate/editPassword/after' => array(
		'event/affiliate_activity/editPassword',
	),
	'model/affiliate/affiliate/editPayment/after' => array(
		'event/affiliate_activity/editPayment',
	),			
	'model/affiliate/affiliate/editCode/after' => array(
		'event/affiliate_activity/forgotten',
	),	
	'model/affiliate/affiliate/deleteLoginAttempts/after' => array(
		'event/affiliate_activity/login',
	),						
	//'controller/*/before' => array(
	//	'event/debug/before'
	//),
	//'controller/*/after'  => array(
	//	'event/debug/after'
	//)
);