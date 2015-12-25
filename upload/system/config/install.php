<?php
// Site
$_['site.base']            = substr(HTTP_SERVER, 7);
$_['site.ssl']             = false;

// Language
$_['language.default']     = 'en-gb';
$_['language.autoload']    = array('en-gb');

// Database
$_['db.autostart']         = false;
$_['db.type']              = 'mysqli'; // mpdo, mssql, mysql, mysqli or postgre
$_['db.hostname']          = 'localhost';
$_['db.username']          = 'root';
$_['db.password']          = '';
$_['db.database']          = '';
$_['db.port']              = 3306;

// Mail
$_['mail.protocol']        = 'mail'; // mail or smtp
$_['mail.from']            = ''; // Your E-Mail
$_['mail.sender']          = ''; // Your name or company name
$_['mail.reply_to']        = ''; // Reply to E-Mail
$_['mail.smtp_hostname']   = '';
$_['mail.smtp_username']   = '';
$_['mail.smtp_password']   = '';
$_['mail.smtp_port']       = 25;
$_['mail.smtp_timeout']    = 5;
$_['mail.verp']            = false;
$_['mail.parameter']       = '';

// Cache
$_['cache.type']           = 'file'; // apc, file or mem
$_['cache.expire']         = 3600;

// Session
$_['session.autostart']    = true;
$_['session.name']         = 'PHPSESSID';

// Template
$_['template.type']        = 'basic';

// Error
$_['error.display']        = true;
$_['error.log']            = true;
$_['error.filename']       = 'error.txt';

// Reponse
$_['response.header']      = array('Content-Type: text/html; charset=utf-8');
$_['response.compression'] = 0;

// Autoload Configs
$_['config.autoload']      = array();

// Autoload Libraries
$_['library.autoload']     = array();

// Autoload Libraries
$_['model.autoload']       = array();

// Actions
$_['action.default']       = 'install/step_1';
$_['action.router']        = 'action/router';
$_['action.error']         = 'error/not_found';
$_['action.pre_action']    = array();
$_['action.event']         = array();