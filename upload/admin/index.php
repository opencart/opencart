<?php
// Version
//define('VERSION', '3.1.0.0_b');
define('VERSION', '3.0.3.2');

// Configuration
if (is_file('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit();
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Autoloader
$autoloader = new Autoloader();
$autoloader->register('Application', DIR_APPLICATION);
$autoloader->register('Extension', DIR_EXTENSION);
$autoloader->register('Catalog', DIR_CATALOG);
$autoloader->register('Admin', DIR_ADMIN);
$autoloader->register('System', DIR_SYSTEM);
$autoloader->register('Application\Controller\Extension\Opencart', DIR_EXTENSION . 'opencart/admin/controller/');
$autoloader->register('Application\Model\Extension\Opencart', DIR_EXTENSION . 'opencart/admin/model/');
$autoloader->register('System\Extension\Opencart', DIR_EXTENSION . 'opencart/system/');

start('admin');