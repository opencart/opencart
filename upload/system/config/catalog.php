<?php
// Site
$_['site_url']           = HTTP_SERVER;

// Database
$_['db_autostart']       = true;
$_['db_option']          = [
	'engine'   => DB_DRIVER, // mysqli, pdo or pgsql
	'hostname' => DB_HOSTNAME,
	'username' => DB_USERNAME,
	'password' => DB_PASSWORD,
	'database' => DB_DATABASE,
	'port'     => DB_PORT,
	'ssl_key'  => DB_SSL_KEY,
	'ssl_cert' => DB_SSL_CERT,
	'ssl_ca'   => DB_SSL_CA
];

// Session
$_['session_autostart']  = false;
$_['session_engine']     = 'db'; // db or file

// Actions
$_['action_pre_action']  = [
	'startup/setting',
	'startup/language',
	'startup/seo_url',
	'startup/session',
	'startup/customer',
	'startup/currency',
	'startup/tax',
	'startup/application',
	'startup/extension',
	'startup/startup',
	'startup/marketing',
	'startup/error',
	'startup/event',
	'startup/api',
	'startup/maintenance',
	'startup/authorize'
];

// Action Events
$_['action_event']      = [
	'controller/*/before' => [
		0 => 'event/language.before',
		//2 => 'event/debug.before'
	],
	'controller/*/after' => [
		0 => 'event/language.after',
		//2 => 'event/debug.after'
	],
	'view/*/before' => [
		500 => 'event/theme',
		998 => 'event/language.template'
	],
	'language/*/after' => [
		0 => 'startup/language.after',
		1 => 'event/translation'
	]
];
