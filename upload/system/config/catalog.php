<?php
// Site
$_['site_url']           = HTTP_SERVER;

// Database
$_['db_autostart']       = true;
$_['db_engine']          = DB_DRIVER; // mysqli, pdo or pgsql
$_['db_hostname']        = DB_HOSTNAME;
$_['db_username']        = DB_USERNAME;
$_['db_password']        = DB_PASSWORD;
$_['db_database']        = DB_DATABASE;
$_['db_port']            = DB_PORT;
//$_['db_ssl_key']         = DB_SSL_KEY;
//$_['db_ssl_cert']        = DB_SSL_CERT;
//$_['db_ssl_ca']          = DB_SSL_CA;

// Session
$_['session_autostart']  = false;
$_['session_engine']     = 'db'; // db or file

// Actions
$_['action_pre_action']  = [
	'startup/extension',
	'startup/setting',
	'startup/seo_url',
	'startup/session',
	'startup/language',
	'startup/customer',
	'startup/currency',
	'startup/tax',
	'startup/application',
	'startup/startup',
	'startup/marketing',
	'startup/error',
	'startup/event',
	'startup/sass',
	'startup/api',
	'startup/maintenance',
	'startup/authorize'
];

// Action Events
$_['action_event']      = [
	'controller/*/before' => [
		0 => 'event/modification.controller',
		1 => 'event/language.before',
		//2 => 'event/debug.before'
	],
	'controller/*/after' => [
		0 => 'event/language.after',
		//2 => 'event/debug.after'
	],
	'view/*/before' => [
		0   => 'event/modification.view',
		500 => 'event/theme',
		998 => 'event/language'
	],
	'language/*/before' => [
		0 => 'event/modification.language'
	],
	'language/*/after' => [
		0 => 'startup/language.after',
		1 => 'event/translation'
	]
];
