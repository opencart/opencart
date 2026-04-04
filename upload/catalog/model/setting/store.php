<?php
namespace Opencart\Catalog\Model\Setting;
/**
 * Class Store
 *
 * Can be called using $this->load->model('setting/store');
 *
 * @package Opencart\Catalog\Model\Setting
 */
class Store extends \Opencart\System\Engine\Model {
	/**
	 * Get Store
	 *
	 * Get the record of the store record in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return array<string, mixed> store record that has store ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_info = $this->model_setting_store->getStore($store_id);
	 */
	public function getStore(int $store_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");

		return $query->row;
	}

	/**
	 * Get Store By Hostname
	 *
	 * @param string $url
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_info = $this->model_setting_store->getStoreByHostname($url);
	 */
	public function getStoreByHostname(string $url): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` WHERE REPLACE(`url`, 'www.', '') = '" . $this->db->escape($url) . "'");

		return $query->row;
	}

	/**
	 * Get Stores
	 *
	 * Get the record of the store records in the database.
	 *
	 * @return array<int, array<string, mixed>> store records
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $stores = $this->model_setting_store->getStores();
	 */
	public function getStores(): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "store` ORDER BY `url`";

		$key = md5($sql);

		$store_data = $this->cache->get('store.' . $key);

		if (!$store_data) {
			$query = $this->db->query($sql);

			$store_data = $query->rows;

			$this->cache->set('store.' . $key, $store_data);
		}

		return $store_data;
	}

	/**
	 * Create Store Instance
	 *
	 * @param int    $store_id
	 * @param string $language
	 * @param string $session_id
	 *
	 * @throws \Exception
	 *
	 * @return \Opencart\System\Engine\Registry
	 */
	public function createStoreInstance(int $store_id = 0, string $language = '', string $session_id = ''): \Opencart\System\Engine\Registry {
		// Autoloader

		$autoloader = new \Opencart\System\Engine\Autoloader();
		$autoloader->register('Opencart\Catalog', DIR_APPLICATION);
		// Registry
		$registry = new \Opencart\System\Engine\Registry();
		$registry->set('autoloader', $autoloader);

		// Registry
		$registry = new \Opencart\System\Engine\Registry();
		$registry->set('autoloader', $this->autoloader);

		// Config
		$config = new \Opencart\System\Engine\Config();
		$registry->set('config', $config);

		// Load the default config
		$config->addPath(DIR_CONFIG);
		$config->load('default');
		$config->set('application', 'Catalog');

		// Store
		$config->set('config_store_id', $store_id);

		// Logging
		$registry->set('log', $this->log);

		// Event
		$event = new \Opencart\System\Engine\Event($registry);
		$registry->set('event', $event);

		// Event Register
		if ($config->has('action_event')) {
			foreach ($config->get('action_event') as $key => $value) {
				foreach ($value as $priority => $action) {
					$event->register($key, new \Opencart\System\Engine\Action($action), $priority);
				}
			}
		}

		// Factory
		$registry->set('factory', new \Opencart\System\Engine\Factory($registry));

		// Loader
		$loader = new \Opencart\System\Engine\Loader($registry);
		$registry->set('load', $loader);

		// Create a dummy request class so we can feed the data to the order editor
		$request = new \stdClass();
		$request->get = [];
		$request->post = [];
		$request->server = $this->request->server;
		$request->cookie = [];

		// Request
		$registry->set('request', $request);

		// Response
		$response = new \Opencart\System\Library\Response();
		$registry->set('response', $response);

		// Database
		$registry->set('db', $this->db);

		// Cache
		$registry->set('cache', $this->cache);

		// Session
		$session = new \Opencart\System\Library\Session($config->get('session_engine'), $registry);
		$session->start();
		$registry->set('session', $session);

		// Template
		$template = new \Opencart\System\Library\Template($config->get('template_engine'));
		$template->addPath(DIR_TEMPLATE);
		$registry->set('template', $template);

		// Adding language var to the GET variable so there is a default language
		if ($language) {
			$request->get['language'] = $language;
		} else {
			$request->get['language'] = $config->get('language_code');
		}

		// Language
		$language = new \Opencart\System\Library\Language($request->get['language']);
		$language->addPath(DIR_APPLICATION . 'language/');
		$language->load('default');
		$registry->set('language', $language);

		// Url
		$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

		// Document
		$registry->set('document', new \Opencart\System\Library\Document());

		// Run pre actions to load key settings and classes.
		$pre_actions = [
			'startup/setting',
			'startup/extension',
			'startup/customer',
			'startup/tax',
			'startup/currency',
			'startup/application',
			'startup/startup',
			'startup/event'
		];

		// Pre Actions
		foreach ($pre_actions as $pre_action) {
			$loader->controller($pre_action);
		}

		return $registry;
	}
}
