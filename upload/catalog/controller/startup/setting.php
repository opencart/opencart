<?php
namespace Opencart\Catalog\Controller\Startup;
/**
 * Class Setting
 *
 * @package Opencart\Catalog\Controller\Startup
 */
class Setting extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->model('setting/store');

		$hostname = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . str_replace('www.', '', $this->request->server['HTTP_HOST']) . rtrim(dirname($this->request->server['PHP_SELF']), '/.\\') . '/';

		$store_info = $this->model_setting_store->getStoreByHostname($hostname);

		// Store
		if (isset($this->request->get['store_id'])) {
			$this->config->set('config_store_id', (int)$this->request->get['store_id']);
		} elseif ($store_info) {
			$this->config->set('config_store_id', $store_info['store_id']);
		} else {
			$this->config->set('config_store_id', 0);
		}

		if (!$store_info) {
			// If catalog constant is defined
			if (defined('HTTP_CATALOG')) {
				$this->config->set('config_url', HTTP_CATALOG);
			} else{
				$this->config->set('config_url', HTTP_SERVER);
			}
		}

		// Settings
		$this->load->model('setting/setting');

		$results = $this->model_setting_setting->getSettings($this->config->get('config_store_id'));

		foreach ($results as $result) {
			if (!$result['serialized']) {
				$this->config->set($result['key'], $result['value']);
			} else {
				$this->config->set($result['key'], json_decode($result['value'], true));
			}
		}

		// Url
		$this->registry->set('url', new \Opencart\System\Library\Url($this->config->get('config_url')));

		// Set time zone
		if ($this->config->get('config_timezone')) {
			date_default_timezone_set($this->config->get('config_timezone'));

			// Sync PHP and DB time zones.
			$this->db->query("SET time_zone = '" . $this->db->escape(date('P')) . "'");
		}

		// Response output compression level
		if ($this->config->get('config_compression')) {
			$this->response->setCompression((int)$this->config->get('config_compression'));
		}
	}
}