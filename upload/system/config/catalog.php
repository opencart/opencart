<?php
// Site
$_['site_base']        = substr(HTTP_SERVER, 7);
$_['site_ssl']         = false;

// Database
$_['db_autostart']     = true;
$_['db_type']          = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']      = DB_HOSTNAME;
$_['db_username']      = DB_USERNAME;
$_['db_password']      = DB_PASSWORD;
$_['db_database']      = DB_DATABASE;
$_['db_port']          = DB_PORT;

// Autoload Libraries
$_['library_autoload'] = array(
	'openbay'
);

// Actions
$_['action_pre_action'] = array(
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/maintenance',
	'startup/seo_url',
	'startup/session'
);

// Action Events
$_['action_event'] = array(
	'view/*/before' => 'event/theme',
	//'model/*/before' => 'event/debug/before'
	//'model/*/after' => 'event/debug/after'
);