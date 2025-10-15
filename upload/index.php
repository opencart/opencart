<?php
// Version
define('VERSION', '4.1.0.4');

// Added dirname function so the system will work from command line.
if (is_file(dirname(__FILE__)  . '/config.php')) {
	require_once('config.php');
}

//include DIR_APPLICATION . 'shop/localhost/';

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit();
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Framework
require_once(DIR_SYSTEM . 'framework.php');