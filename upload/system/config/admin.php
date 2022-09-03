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
		0 => 'event/language.before'
	],
	'controller/*/after' => [
		0 => 'event/language.after'
	],
	//'model/*/after' => [
	//	0 => 'event/debug.before'
	//],
	//'model/*/after' => [
	//	0 => 'event/debug.after'
	//],
	'view/*/before' => [
		999 => 'event/language'
	]
];
