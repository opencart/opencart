<?php
// HTTP
define('HTTP_SERVER', 'http://yourIP:yourPort/upload/');

// HTTPS
define('HTTPS_SERVER', 'http://yourIP:yourPort/upload/');

// DIR
define('DIR_PHYSICALPATH',$_SERVER['DOCUMENT_ROOT'] . '/upload/');

define('DIR_APPLICATION', DIR_PHYSICALPATH.'catalog/');
define('DIR_SYSTEM',  DIR_PHYSICALPATH.'system/');
define('DIR_IMAGE', DIR_PHYSICALPATH.'image/');
define('DIR_LANGUAGE', DIR_PHYSICALPATH.'catalog/language/');
define('DIR_TEMPLATE', DIR_PHYSICALPATH.'catalog/view/theme/');
define('DIR_CONFIG', DIR_PHYSICALPATH.'system/config/');
define('DIR_CACHE', DIR_PHYSICALPATH.'system/storage/cache/');
define('DIR_DOWNLOAD',DIR_PHYSICALPATH.'system/storage/download/');
define('DIR_LOGS', DIR_PHYSICALPATH.'system/storage/logs/');
define('DIR_MODIFICATION', DIR_PHYSICALPATH.'system/storage/modification/');
define('DIR_UPLOAD', DIR_PHYSICALPATH.'system/storage/upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_DATABASE', 'opencart');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');
