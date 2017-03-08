<?php
// Site
$_['site_url']          = HTTP_SERVER;
$_['site_ssl']          = HTTPS_SERVER;

// Url
$_['url_autostart']     = false;

// Database
$_['db_autostart']      = true;
$_['db_engine']         = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']       = DB_HOSTNAME;
$_['db_username']       = DB_USERNAME;
$_['db_password']       = DB_PASSWORD;
$_['db_database']       = DB_DATABASE;
$_['db_port']           = DB_PORT;

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
		'mail/register',
		'mail/register/alert',
		// Activity
		'event/activity/addCustomer'
	),
	'model/account/customer/editCustomer/after' => array(
		'event/activity/editCustomer'
	),	
	'model/account/customer/editPassword/after' => array(
		'event/activity/editPassword'
	),
	'model/account/customer/editCode/after' => array(
		// E-Mail
		'mail/forgotten',
		// Activity
		'event/activity/forgotten'
	),		
	'model/account/customer/addTransaction/after' => array(
		'event/activity/addTransaction',
		'mail/transaction'
	),
	'model/account/customer/deleteLoginAttempts/after' => array(
		'event/activity/login'
	),		
	'model/account/address/addAddress/after' => array(
		'event/activity/addAddress'
	),
	'model/account/address/editAddress/after' => array(
		'event/activity/editAddress'
	),	
	'model/account/address/deleteAddress/after' => array(
		'event/activity/deleteAddress'
	),	
	// We want to do a before to grab the last order status
	'model/checkout/order/addOrderHistory/before' => array(
		// E-Mail
		'mail/order/before'
	),
	'model/checkout/order/addOrderHistory/after' => array(
		// E-Mail
		'mail/order/after',
		'mail/order/alert',
		// Activity
		'event/activity/addOrderHistory'
	),
	'model/account/return/addReturn/after' => array(
		'event/activity/addReturn'
	),	
	'model/account/customer/addAffiliate/after' => array(
		// E-Mail
		'mail/affiliate',
		'mail/affiliate/alert',
		// Activity
		//'event/activity/addAffiliate'
	),	
	'model/account/customer/editAffiliate/after' => array(
		'event/activity/editAffiliate'
	),			
	//'controller/*/before' => array(
	//	'event/debug/before'
	//),
	//'controller/*/after'  => array(
	//	'event/debug/after'
	//)
);