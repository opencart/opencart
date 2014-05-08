<?php
define('VERSION', '2.0');

// Set your config info here
define('DB_TEST_DRIVER', 'mysqli');
define('DB_TEST_HOSTNAME', 'localhost');
define('DB_TEST_USERNAME', 'root');
define('DB_TEST_PASSWORD', '');
define('DB_TEST_DATABASE', 'test5');
define('DB_TEST_PREFIX', 'oc_');

define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin');
define('CONFIG_ADMIN', __DIR__ . '/../../../upload/admin/config.php');
define('CONFIG_CATALOG', __DIR__ . '/../../../upload/config.php');
define('SQL_FILE', __DIR__ . '/../../../upload/install/opencart.sql');