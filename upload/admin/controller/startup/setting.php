<?php
namespace Opencart\Application\Controller\Startup;
class Startup extends \Opencart\System\Engine\Controller {
	public function index() {
		// Store
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape(($this->request->server['HTTPS'] ? 'https://' : 'http://') . str_replace('www.', '', $this->request->server['HTTP_HOST']) . rtrim(dirname($this->request->server['PHP_SELF']), '/.\\') . '/') . "'");

		if (isset($this->request->get['store_id'])) {
			$this->config->set('config_store_id', (int)$this->request->get['store_id']);
		} else if ($query->num_rows) {
			$this->config->set('config_store_id', $query->row['store_id']);
		} else {
			$this->config->set('config_store_id', 0);
		}

		if (!$query->num_rows) {
			$this->config->set('config_url', HTTP_SERVER);
		}



		// Settings
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$this->config->set($setting['key'], $setting['value']);
			} else {
				$this->config->set($setting['key'], json_decode($setting['value'], true));
			}
		}

		// Set time zone
		if ($this->config->get('config_timezone')) {
			date_default_timezone_set($this->config->get('config_timezone'));

			// Sync PHP and DB time zones.
			$this->db->query("SET time_zone = '" . $this->db->escape(date('P')) . "'");
		}
	}
}