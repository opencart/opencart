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

// Session
$_['session_autostart']  = false;
$_['session_engine']     = 'db'; // db or file
$_['session_name']       = 'OCSESSID';

// Template
$_['template_engine']    = 'twig';
$_['template_directory'] = '';

// Autoload Libraries
$_['library_autoload']   = [];

// Actions
$_['action_pre_action']  = [
	'startup/startup',
	'startup/marketing',
	'startup/error',
	'startup/event',
	'startup/sass',
	'startup/maintenance',
	'startup/seo_url'
];

// Action Events
$_['action_event']      = [
	'controller/*/before' => [
		'event/language/before',
		//'event/debug/before'
	],
	'controller/*/after' => [
		'event/language/after',
		//'event/debug/after'
	],
	'view/*/before' => [
		500 => 'event/theme',
		998 => 'event/language'
	],
	'language/*/after' => [
		'event/translation'
	]
];