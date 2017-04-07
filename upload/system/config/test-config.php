<?php

define('VERSION', '2.3.0.1');

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

// Action Events
$_['action_event'] = array(
    'view/*/before' => 'event/theme',
    //'model/*/before' => 'event/debug/before'
    //'model/*/after' => 'event/debug/after'
);

if (defined('HTTP_ADMIN')) { // is defined iff in catalog
    $_['config_theme'] = 'theme_default';
    $_['theme_default_status'] = 1;

    // Actions
    $_['action_pre_action'] = array(
        'startup/test_startup'
    );
} else { // admin

    $_['action_default'] = 'common/dashboard';

    // Actions
    $_['action_pre_action'] = array(
        'startup/startup' ,
        'startup/error',
        'startup/event',
        'startup/sass',
        'startup/login',
        'startup/permission'
    );
}

// Test Settings
$_['session_autostart'] = false;

