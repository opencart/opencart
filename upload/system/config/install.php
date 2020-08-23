<?php
// Site
$_['site_base']         = HTTP_SERVER;
$_['site_ssl']          = HTTP_SERVER;

// Language
$_['language_default']  = 'en-gb';
$_['language_autoload'] = ['en-gb'];

// Session
$_['session_autostart'] = true;
$_['session_engine']    = 'file'; // db or file
$_['session_name']      = 'OCSESSID';

// Template
$_['template_engine']   = 'twig';

// Error
$_['error_display']     = true;

// Actions
$_['action_default']    = 'install/step_1';
$_['action_error']      = 'error/not_found';
$_['action_pre_action'] = [
	'startup/language',
	'startup/upgrade',
	'startup/database'
];

// Action Events
$_['action_event']      = [];
