<?php
// Site
$_['site_url']          = HTTP_SERVER;

// Database
$_['db_autostart']      = true;
$_['db_engine']         = DB_DRIVER; // mysqli, pdo or pgsql
$_['db_hostname']       = DB_HOSTNAME;
$_['db_username']       = DB_USERNAME;
$_['db_password']       = DB_PASSWORD;
$_['db_database']       = DB_DATABASE;
$_['db_port']           = DB_PORT;

// Session
$_['session_autostart'] = false;
$_['session_engine']    = 'db'; // db or file

// Error
$_['error_display']     = true;

// Actions
$_['action_pre_action'] = [
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/sass',
	'startup/login',
	'startup/permission'
];

// Actions
$_['action_default']    = 'common/dashboard';

// Action Events
$_['action_event']      = [
	'controller/*/before' => [
		'event/language/before'
	],
	'controller/*/after' => [
		'event/language/after'
	],
	//'extension/opencart/controller/after' => [
	//	'event/language/after'
	//],
	//'model/*/before' => [
	//	'event/extension/model'
	//],
	//'view/*/before' => [
	//	'event/extension'
	//],
	'view/*/before' => [
		999 => 'event/language'
	]
	//'model/*/after' => [
	//	'event/debug/before'
	//],
	//'model/*/after' => [
	//	'event/debug/after'
	//]
];
