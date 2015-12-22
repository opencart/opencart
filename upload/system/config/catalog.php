<?php
// Config
$_['site.base']            = substr(HTTP_SERVER, 5);
$_['site.ssl']             = false;

// Database
$_['db.autostart']         = true;
$_['db.type']              = 'mysqli'; // mpdo, mssql, mysql, mysqli or postgre
$_['db.hostname']          = 'localhost';
$_['db.username']          = 'root';
$_['db.password']          = '';
$_['db.database']          = 'opencart';
$_['db.port']              = 3306;

// Pre Action
$_['action.pre_action'] = array(
	'action/setting',
	'action/error',
	'action/event',
	'action/session',
	'action/language',
	'action/cart',
	'action/maintenance',
	'action/seo_url'
);

// Action Events
$_['action.event'] = array(
	'view/*/before'                            => 'event/theme',
	'model/account/customer/addCustomer/after' => 'mail/account',
	'model/checkout/order/addOrder/after'      => 'mail/order'
);

// Autoload Libraries
$_['library.autoload'] = array(
	'cart/customer',
	'cart/affiliate',
	'cart/currency',
	'cart/tax',
	'cart/weight',
	'cart/length',
	'cart/cart',
	'openbay'
);