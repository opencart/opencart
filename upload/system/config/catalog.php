<?php
// Site
$_['site.base']        = substr(HTTP_SERVER, 7);
$_['site.ssl']         = false;

// Database
$_['db.autostart']     = true;
$_['db.type']          = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db.hostname']      = DB_HOSTNAME;
$_['db.username']      = DB_USERNAME;
$_['db.password']      = DB_PASSWORD;
$_['db.database']      = DB_DATABASE;
$_['db.port']          = DB_PORT;

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

// Actions
$_['action.pre_action'] = array(
	'startup/setting',
	'startup/error',
	'startup/event',
	'startup/session',
	'startup/language',
	'startup/currency',
	'startup/customer_group',
	'startup/tax',
	'startup/tracking',
	'startup/maintenance',
	'startup/seo_url'
);

// Action Events
$_['action.event'] = array(
	'view/*/before' => 'event/theme',
	//'model/*/after' => 'event/debug',
);