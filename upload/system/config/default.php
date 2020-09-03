<?php
// Site
$_['site_url']             = '';

// Language
$_['language_directory']   = 'en-gb';
$_['language_autoload']    = ['en-gb'];

// Date
$_['date_timezone']        = 'UTC';

// Database
$_['db_autostart']         = false;
$_['db_engine']            = 'mysqli'; // mysqli, pdo or pgsql
$_['db_hostname']          = 'localhost';
$_['db_username']          = 'root';
$_['db_password']          = '';
$_['db_database']          = '';
$_['db_port']              = 3306;

// Mail
$_['mail_engine']          = 'mail'; // mail or smtp
$_['mail_from']            = ''; // Your E-Mail
$_['mail_sender']          = ''; // Your name or company name
$_['mail_reply_to']        = ''; // Reply to E-Mail
$_['mail_smtp_hostname']   = '';
$_['mail_smtp_username']   = '';
$_['mail_smtp_password']   = '';
$_['mail_smtp_port']       = 25;
$_['mail_smtp_timeout']    = 5;
$_['mail_verp']            = false;
$_['mail_parameter']       = '';

// Cache
$_['cache_engine']         = 'file'; // apc, file, mem, memcached or redis
$_['cache_expire']         = 3600;

// Session
$_['session_autostart']    = true;
$_['session_engine']       = 'file'; // db or file
$_['session_name']         = 'OCSESSID';
$_['session_expire']       = 360000;

// Template
$_['template_engine']      = 'twig';
$_['template_directory']   = '';
$_['template_extension']   = '.twig';

// Error
$_['error_display']        = true;
$_['error_log']            = true;
$_['error_filename']       = 'error.log';

// Response
$_['response_header']      = ['Content-Type: text/html; charset=utf-8'];
$_['response_compression'] = 0;

// Autoload Configs
$_['config_autoload']      = [];

// Autoload Libraries
$_['library_autoload']     = [];

// Autoload Models
$_['model_autoload']       = [];

// Autoload Helpers
$_['helper_autoload']      = [];

// Actions
$_['action_default']       = 'common/home';
$_['action_error']         = 'error/not_found';
$_['action_pre_action']    = [];
$_['action_event']         = [];
