<?php
class ControllerEventSetting extends Controller {
	public function index() {
		// Database
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
		$registry->set('db', $db);
		
		// Store
		if ($_SERVER['HTTPS']) {
			$store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
		} else {
			$store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
		}
		
		if ($store_query->num_rows) {
			$config->set('config_store_id', $store_query->row['store_id']);
		} else {
			$config->set('config_store_id', 0);
		}
		
		// Settings
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");
		
		foreach ($query->rows as $result) {
			if (!$result['serialized']) {
				$config->set($result['key'], $result['value']);
			} else {
				$config->set($result['key'], json_decode($result['value'], true));
			}
		}
		
		if (!$store_query->num_rows) {
			$config->set('config_url', HTTP_SERVER);
			$config->set('config_ssl', HTTPS_SERVER);
		}
	
	}
}