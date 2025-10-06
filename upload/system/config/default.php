<?php
// Site
$_['site_url']             = '';

// Language
$_['language_code']        = 'en-gb';

// Date
$_['date_timezone']        = 'UTC';

// Database
$_['db_autostart']         = false;
$_['db_option']            = [
	'engine'   => 'mysqli', // mysqli, pdo or pgsql
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => '',
	'port'     => '3306',
	'ssl_key'  => '',
	'ssl_cert' => '',
	'ssl_ca'   => ''
];

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
$_['session_autostart']    = false;
$_['session_engine']       = 'file'; // db or file
$_['session_name']         = 'OCSESSID';
$_['session_domain']       = '';
$_['session_path']         = !empty($_SERVER['PHP_SELF']) ? rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/' : '/';
$_['session_expire']       = 86400;
$_['session_probability']  = 1;
$_['session_divisor']      = 5;
$_['session_samesite']     = 'Strict'; // None, Lax, Strict

// Template
$_['template_engine']      = 'twig';
$_['template_extension']   = '.twig';

// Upload
$_['upload_max_size']      = 20; // MB
$_['upload_type_allowed']  = [
	'txt',
	'zip',
	'png',
	'webp',
	'jpe',
	'jpeg',
	'jpg',
	'gif',
	'bmp',
	'svg',
	'svgz',
	'zip',
	'rar',
	'mp3',
	'mp4',
	'mov',
	'pdf'
];
$_['upload_mime_allowed']  = [
	'text/plain',
	'image/png',
	'image/webp',
	'image/jpeg',
	'image/gif',
	'image/bmp',
	'image/svg+xml',
	'application/zip',
	'application/x-zip',
	'application/x-zip-compressed',
	'application/rar',
	'application/x-rar',
	'application/x-rar-compressed',
	'audio/mpeg',
	'video/mp4',
	'application/pdf'
];

// Error
$_['error_display']        = true; // You need to change this to false on a live site.
$_['error_log']            = true;
$_['error_debug']          = false;
$_['error_filename']       = 'error.log';
$_['error_page']           = 'error.html';

// Response
$_['response_header']      = ['Content-Type: text/html; charset=utf-8'];
$_['response_compression'] = 0;

// Actions
$_['action_default']       = 'common/home';
$_['action_error']         = 'error/not_found';
$_['action_pre_action']    = [];
$_['action_event']         = [];
