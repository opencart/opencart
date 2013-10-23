<?php

//
// Command line tool for installing opencart
// Author: Vineet Naik <vineet.naik@kodeplay.com> <naikvin@gmail.com>
//
// (Currently tested on linux only)
//
// Usage:
//
//   cd install
//   php cli_install.php install --db_host localhost \
//                               --db_user root \
//                               --db_password pass \
//                               --db_name opencart \
//                               --username admin \
//                               --password admin \
//                               --email youremail@example.com \
//                               --agree_tnc yes \
//                               --http_server http://localhost/opencart
//

ini_set('display_errors', 1);
error_reporting(E_ALL);

// DIR
define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');
define('DIR_SYSTEM', str_replace('\'', '/', realpath(dirname(__FILE__) . '/../')) . '/system/');
define('DIR_OPENCART', str_replace('\'', '/', realpath(DIR_APPLICATION . '../')) . '/');
define('DIR_DATABASE', DIR_SYSTEM . 'database/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/template/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);


function handleError($errno, $errstr, $errfile, $errline, array $errcontext) {
    // error was suppressed with the @-operator
    if (0 === error_reporting()) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

set_error_handler('handleError');


function usage() {
    echo "Usage:\n";
    echo "======\n";
    echo "\n";
    $options = implode(" ", array('--db_host', 'localhost',
                                  '--db_user', 'root',
                                  '--db_password', 'pass',
                                  '--db_name', 'opencart',
                                  '--username', 'admin',
                                  '--password', 'admin',
                                  '--email', 'youremail@example.com',
                                  '--agree_tnc', 'yes',
                                  '--http_server', 'http://localhost/opencart'));
    echo 'php cli_install.php install ' . $options . "\n\n";
}


function get_options($argv) {
    $defaults = array(
        'db_host' => 'localhost',
        'db_name' => 'opencart',
        'db_prefix' => '',
        'username' => 'admin',
        'agree_tnc' => 'no',
    );

    $options = array();
    $total = count($argv);
    for ($i=0; $i < $total; $i=$i+2) {
        $is_flag = preg_match('/^--(.*)$/', $argv[$i], $match);
        if (!$is_flag) {
            throw new Exception($argv[$i] . ' found in command line args instead of a valid option name starting with \'--\'');
        }
        $options[$match[1]] = $argv[$i+1];
    }
    return array_merge($defaults, $options);
}


function valid($options) {
    $required = array(
        'db_host',
        'db_user',
        'db_password',
        'db_name',
        'db_prefix',
        'username',
        'password',
        'email',
        'agree_tnc',
        'http_server',
    );
    $missing = array();
    foreach ($required as $r) {
        if (!array_key_exists($r, $options)) {
            $missing[] = $r;
        }
    }
    if ($options['agree_tnc'] !== 'yes') {
        $missing[] = 'agree_tnc (should be yes)';
    }
    $valid = count($missing) === 0 && $options['agree_tnc'] === 'yes';
    return array($valid, $missing);
}


function install($options) {
    $check = check_requirements();
    if ($check[0]) {
        setup_mysql($options);
        write_config_files($options);
        dir_permissions();
    } else {
        echo 'FAILED! Pre-installation check failed: ' . $check[1] . "\n\n";
        exit(1);
    }
}


function check_requirements() {
    $error = null;
    if (phpversion() < '5.0') {
        $error = 'Warning: You need to use PHP5 or above for OpenCart to work!';
    }

    if (!ini_get('file_uploads')) {
        $error = 'Warning: file_uploads needs to be enabled!';
    }

    if (ini_get('session.auto_start')) {
        $error = 'Warning: OpenCart will not work with session.auto_start enabled!';
    }

    if (!extension_loaded('mysql')) {
        $error = 'Warning: MySQL extension needs to be loaded for OpenCart to work!';
    }

    if (!extension_loaded('gd')) {
        $error = 'Warning: GD extension needs to be loaded for OpenCart to work!';
    }

    if (!extension_loaded('curl')) {
        $error = 'Warning: CURL extension needs to be loaded for OpenCart to work!';
    }

    if (!function_exists('mcrypt_encrypt')) {
        $error = 'Warning: mCrypt extension needs to be loaded for OpenCart to work!';
    }

    if (!extension_loaded('zlib')) {
        $error = 'Warning: ZLIB extension needs to be loaded for OpenCart to work!';
    }

    if (!is_writable(DIR_OPENCART . 'config.php')) {
        $error = 'Warning: config.php needs to be writable for OpenCart to be installed!';
    }

    if (!is_writable(DIR_OPENCART . 'admin/config.php')) {
        $error = 'Warning: admin/config.php needs to be writable for OpenCart to be installed!';
    }

    if (!is_writable(DIR_SYSTEM . 'cache')) {
        $error = 'Warning: Cache directory needs to be writable for OpenCart to work!';
    }

    if (!is_writable(DIR_SYSTEM . 'logs')) {
        $error = 'Warning: Logs directory needs to be writable for OpenCart to work!';
    }

    if (!is_writable(DIR_OPENCART . 'image')) {
        $error = 'Warning: Image directory needs to be writable for OpenCart to work!';
    }

    if (!is_writable(DIR_OPENCART . 'image/cache')) {
        $error = 'Warning: Image cache directory needs to be writable for OpenCart to work!';
    }

    if (!is_writable(DIR_OPENCART . 'image/catalog')) {
        $error = 'Warning: Image catalog directory needs to be writable for OpenCart to work!';
    }

    if (!is_writable(DIR_OPENCART . 'download')) {
        $error = 'Warning: Download directory needs to be writable for OpenCart to work!';
    }

    return array($error === null, $error);
}


function setup_mysql($dbdata) {
    global $loader, $registry;
    $loader->model('install');
    $model = $registry->get('model_install');
    $model->mysql($dbdata);
}


function write_config_files($options) {
    $siteConfig  = "<?php\n";

    $siteConfig .= 'define(\'WWW_ROOT\', $_SERVER[\'HTTP_HOST\'] . str_replace(\'index.php\', \'\', $_SERVER[\'SCRIPT_NAME\']));' . "\n";
    $siteConfig .= "define('ROOT', dirname(__FILE__));\n\n";

    $siteConfig .= "// HTTP\n";
    $siteConfig .= "define('HTTP_SERVER', 'http://' . WWW_ROOT);\n\n";

    $siteConfig .= "// HTTPS\n";
    $siteConfig .= "define('HTTPS_SERVER', 'http://' . WWW_ROOT);\n\n";


    $siteConfig .= "// DIR\n";
    $siteConfig .= "define('DIR_APPLICATION', ROOT . '/catalog/');\n";
    $siteConfig .= "define('DIR_SYSTEM', ROOT. '/system/');\n";
    $siteConfig .= "define('DIR_DATABASE', ROOT . '/system/database/');\n";
    $siteConfig .= "define('DIR_LANGUAGE', ROOT . '/catalog/language/');\n";
    $siteConfig .= "define('DIR_TEMPLATE', ROOT . '/catalog/view/theme/');\n";
    $siteConfig .= "define('DIR_CONFIG', ROOT . '/system/config/');\n";
    $siteConfig .= "define('DIR_IMAGE', ROOT . '/image/');\n";
    $siteConfig .= "define('DIR_CACHE', ROOT . '/system/cache/');\n";
    $siteConfig .= "define('DIR_DOWNLOAD', ROOT . '/system/download/');\n";
    $siteConfig .= "define('DIR_MODIFICATION', ROOT . '/system/modification/');\n";
    $siteConfig .= "define('DIR_LOGS', ROOT . '/system/logs/');\n\n";

    $siteConfig .= "// DB\n";
    $siteConfig .= "include_once ROOT . '/db_config.php';\n";
    $siteConfig .= "?>";

    $dbConfig  = "<?php\n";
    $dbConfig .= "define('DB_DRIVER', 'mysql');\n";
    $dbConfig .= "define('DB_HOSTNAME', '" . addslashes($options['db_host']) . "');\n";
    $dbConfig .= "define('DB_USERNAME', '" . addslashes($options['db_user']) . "');\n";
    $dbConfig .= "define('DB_PASSWORD', '" . addslashes($options['db_password']) . "');\n";
    $dbConfig .= "define('DB_DATABASE', '" . addslashes($options['db_name']) . "');\n";
    $dbConfig .= "define('DB_PREFIX', '" . addslashes($options['db_prefix']) . "');\n";
    $dbConfig .= "?>";

    file_put_contents(DIR_OPENCART . 'config.php', $siteConfig);
    file_put_contents(DIR_OPENCART . 'db_config.php', $dbConfig);

    $adminConfig  = "<?php\n";
    $adminConfig .= 'define(\'WWW_ROOT\', $_SERVER[\'HTTP_HOST\'] . str_replace(\'admin/index.php\', \'\', $_SERVER[\'SCRIPT_NAME\']));' . "\n";
    $adminConfig .= "define('ROOT', dirname(__FILE__) . '/..');\n\n";

    $adminConfig .= "// HTTP\n";
    $adminConfig .= "define('HTTP_SERVER', 'http://' . WWW_ROOT . 'admin/');\n";
    $adminConfig .= "define('HTTP_CATALOG', 'http://' . WWW_ROOT);\n\n";;

    $adminConfig .= "// HTTPS\n";
    $adminConfig .= "define('HTTPS_SERVER', 'http://' . WWW_ROOT . 'admin/');\n";
    $adminConfig .= "define('HTTPS_CATALOG', 'http://' . WWW_ROOT);\n\n";


    $adminConfig .= "// DIR\n";
    $adminConfig .= "define('DIR_APPLICATION', ROOT . '/admin/');\n";
    $adminConfig .= "define('DIR_SYSTEM', ROOT. '/system/');\n";
    $adminConfig .= "define('DIR_DATABASE', ROOT . '/system/database/');\n";
    $adminConfig .= "define('DIR_LANGUAGE', ROOT . '/admin/language/');\n";
    $adminConfig .= "define('DIR_TEMPLATE', ROOT . '/admin/view/template/');\n";
    $adminConfig .= "define('DIR_CONFIG', ROOT . '/system/config/');\n";
    $adminConfig .= "define('DIR_IMAGE', ROOT . '/image/');\n";
    $adminConfig .= "define('DIR_CACHE', ROOT . '/system/cache/');\n";
    $adminConfig .= "define('DIR_DOWNLOAD', ROOT . '/system/download/');\n";
    $adminConfig .= "define('DIR_MODIFICATION', ROOT . '/system/modification/');\n";
    $adminConfig .= "define('DIR_LOGS', ROOT . '/system/logs/');\n";
    $adminConfig .= "define('DIR_CATALOG', ROOT . '/catalog/');\n\n";

    $adminConfig .= "// DB\n";
    $adminConfig .= "include_once ROOT . '/db_config.php';\n";
    $adminConfig .= "?>";

    file_put_contents(DIR_OPENCART . 'admin/config.php', $adminConfig);

}


function dir_permissions() {
    $dirs = array(
        DIR_OPENCART . 'image/',
        DIR_OPENCART . 'download/',
        DIR_SYSTEM . 'cache/',
        DIR_SYSTEM . 'logs/',
    );
    exec('chmod o+w -R ' . implode(' ', $dirs));
}


$argv = $_SERVER['argv'];
$script = array_shift($argv);
$subcommand = array_shift($argv);


switch ($subcommand) {

case "install":
    try {
        $options = get_options($argv);
        define('HTTP_OPENCART', $options['http_server']);
        $valid = valid($options);
        if (!$valid[0]) {
            echo "FAILED! Following inputs were missing or invalid: ";
            echo implode(', ',  $valid[1]) . "\n\n";
            exit(1);
        }
        install($options);
        echo "SUCCESS! Opencart successfully installed on your server\n";
        echo "Store link: " . $options['http_server'] . "\n";
        echo "Admin link: " . $options['http_server'] . "admin/\n\n";
    } catch (ErrorException $e) {
        echo 'FAILED!: ' . $e->getMessage() . "\n";
        exit(1);
    }
    break;
case "usage":
default:
    echo usage();
}
