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
$autoloader = new Opencart\System\Engine\Autoloader();
$autoloader->register('Opencart\Application', DIR_APPLICATION);
$autoloader->register('Opencart\Extension', DIR_EXTENSION);
$autoloader->register('Opencart\Catalog', DIR_CATALOG);
$autoloader->register('Opencart\Admin', DIR_ADMIN);
$autoloader->register('Opencart\System', DIR_SYSTEM);

$autoloader->register('Opencart\Application\Controller\Extension\Opencart', DIR_EXTENSION . 'opencart/admin/controller/');
$autoloader->register('Opencart\Application\Model\Extension\Opencart', DIR_EXTENSION . 'opencart/admin/model/');
$autoloader->register('Opencart\System\Extension\Opencart', DIR_EXTENSION . 'opencart/system/');

Opencart\start('admin');