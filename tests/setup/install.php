<?php
/**
 * Demo install file allows for config and module settings to be set-up using a single setup file.
 *
 * Designed to be used with build automation services like Jenkins to save time with demo installation sites, no need
 * for admin to login to the store and update settings or installing modules manually for each test build.
 *
 * @todo support for modules & order totals
 * @todo create front end demo user account from config (or re-use current selenium test account)
 */

// Version
define('CONFIG_ADMIN', __DIR__ . '/../../upload/admin/config.php');
require('./config.php');

require(CONFIG_ADMIN);
require(DIR_SYSTEM . 'library/db.php');
require(DIR_SYSTEM . 'library/db/' . DB_DRIVER . '.php');

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

/**
 * Store settings configuration
 */
foreach ($settings as $store_id => $store_settings) {
	$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "'");

	$old_store_config = array();

	foreach ($query->rows as $result) {
		if ($result['serialized'] == 1) {
			$old_store_config[$result['key']] = json_decode($result['value']);
		} else {
			$old_store_config[$result['key']] = $result['value'];

		}
	}

	$new_store_config = array_merge($old_store_config, $store_settings);

	editSetting('config', $new_store_config, $store_id);
}

// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Request
$request = new Request();
$registry->set('request', $request);

// Cache
$cache = new Cache('file');
$registry->set('cache', $cache);

// Session
$session = new Session();
$registry->set('session', $session);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$registry->set('db', $db);

// User
$user = new Cart\User($registry);
$user->login(ADMIN_USERNAME, ADMIN_PASSWORD);

$registry->set('user', $user);

foreach ($module_settings as $module_settings_type => $module_settings_data) {
	$installed_extensions = getInstalledExtension($module_settings_type);

	foreach ($installed_extensions as $remove_extension) {
		$loader->controller($module_settings_type . '/' . $remove_extension . '/uninstall');
		deleteSetting($store_id, $remove_extension);
	}

	$db->query("DELETE FROM " . DB_PREFIX . "extension WHERE `type` = '" . $db->escape($module_settings_type) . "' AND `code` = '" . $db->escape($remove_extension) . "'");

	foreach ($module_settings_data as $module_key => $module_data) {
		$db->query("INSERT INTO " . DB_PREFIX . "extension SET `type` = '" . $db->escape($module_settings_type) . "', `code` = '" . $db->escape($module_key) . "'");

		$loader->model('user/user_group');

		$loader->controller($module_settings_type . '/' . $module_key . '/install');

		editSetting($module_key, $module_data);
	}
}

echo "Setting update completed\r\n";

function deleteSetting($store_id, $code) {
	global $db;

	$db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $db->escape($code) . "'");
}

function editSetting($code, $data, $store_id = 0) {
	global $db;

	$db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$store_id . "' AND `code` = '" . $db->escape($code) . "'");

	foreach ($data as $key => $value) {
		if (substr($key, 0, strlen($code)) == $code) {
			if (!is_array($value)) {
				$db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $db->escape($code) . "', `key` = '" . $db->escape($key) . "', `value` = '" . $db->escape($value) . "'");
			} else {
				$db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $db->escape($code) . "', `key` = '" . $db->escape($key) . "', `value` = '" . $db->escape(json_encode($value)) . "', serialized = '1'");
			}
		}
	}
}

function getInstalledExtension($type) {
	global $db;

	$extension_data = array();

	$query = $db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = '" . $db->escape($type) . "' ORDER BY code");

	foreach ($query->rows as $result) {
		$extension_data[] = $result['code'];
	}

	return $extension_data;
}