<?php
// Version
define('VERSION', '2.2.2');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Startuptest
require_once(DIR_SYSTEM . 'startup.php');

start('catalog');