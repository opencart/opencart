<?php
// Site
$_['site_url']          = HTTP_SERVER;

// Database
$_['db_autostart']      = true;
$_['db_option']         = [
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
$_['session_autostart'] = false;
$_['session_engine']    = 'db'; // db or file

// Error
$_['error_display']     = true;

// Actions
$_['action_pre_action'] = [
	'startup/setting',
	'startup/session',
	'startup/language',
	'startup/application',
	'startup/extension',
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/sass',
	'startup/login',
	'startup/authorize',
	'startup/permission'
];

// Actions
$_['action_default']     = 'common/dashboard';

// Action Events
$_['action_event']       = [
	'controller/*/before' => [
		0 => 'event/modification.controller',
		1 => 'event/language.before'
	],
	'controller/*/after' => [
		0 => 'event/language.after'
	],
	'model/*/before' => [
		0 => 'event/modification.model'
		//1 => 'event/debug.before'
	],
	//'model/*/after' => [
	//	0 => 'event/debug.after'
	//],
	'view/*/before' => [
		0   => 'event/modification.view',
		999 => 'event/language'
	],
	'language/*/before' => [
		0 => 'event/modification.language'
	],
	'language/*/after' => [
		0 => 'startup/language.after'
	]
];
