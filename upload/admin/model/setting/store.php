<?php
namespace Opencart\Admin\Model\Setting;
class Store extends \Opencart\System\Engine\Model {
	public function addStore(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "store` SET `name` = '" . $this->db->escape((string)$data['config_name']) . "', `url` = '" . $this->db->escape((string)$data['config_url']) . "'");

		$store_id = $this->db->getLastId();

		// Layout Route
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "layout_route` WHERE `store_id` = '0'");

		foreach ($query->rows as $layout_route) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_route` SET `layout_id` = '" . (int)$layout_route['layout_id'] . "', `route` = '" . $this->db->escape($layout_route['route']) . "', `store_id` = '" . (int)$store_id . "'");
		}

		$this->cache->delete('store');

		return $store_id;
	}

	public function editStore(int $store_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "store` SET `name` = '" . $this->db->escape((string)$data['config_name']) . "', `url` = '" . $this->db->escape((string)$data['config_url']) . "' WHERE `store_id` = '" . (int)$store_id . "'");

		$this->cache->delete('store');
	}

	public function deleteStore(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "layout_route` WHERE `store_id` = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$store_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `store_id` = '" . (int)$store_id . "'");

		$this->cache->delete('store');
	}

	public function getStore(int $store_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");

		return $query->row;
	}

	public function getStores(array $data = []): array {
		$store_data = $this->cache->get('store');

		if (!$store_data) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "store` ORDER BY `url`");

			$store_data = $query->rows;

			$this->cache->set('store', $store_data);
		}

		return $store_data;
	}

	public function createStoreInstance(int $store_id = 0, string $language = '', string $session_id = ''): object {
		// Autoloader
		$this->autoloader->register('Opencart\Catalog', DIR_CATALOG);

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
		$registry->set('session', $session);

		// Start session
		$session->start($session_id);

		// Template
		$template = new \Opencart\System\Library\Template($config->get('template_engine'));
		$template->addPath(DIR_CATALOG . 'view/template/');
		$registry->set('template', $template);

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($language);

		if ($language_info) {
			$config->set('config_language_id', $language_info['language_id']);
			$config->set('config_language', $language_info['code']);
		} else {
			$config->set('config_language_id', $this->config->get('config_language_id'));
			$config->set('config_language', $this->config->get('config_language'));
		}

		$language = new \Opencart\System\Library\Language($this->config->get('config_language'));
		$registry->set('language', $language);

		if (!$language_info['extension']) {
			$language->addPath(DIR_CATALOG . 'language/');
		} else {
			$language->addPath(DIR_EXTENSION . $language_info['extension'] . '/catalog/language/');
		}

		// Load default language file
		$language->load('default');

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

	public function getTotalStores(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "store`");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_layout_id' AND `value` = '" . (int)$layout_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByLanguage(string $language): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_language' AND `value` = '" . $this->db->escape($language) . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByCurrency(string $currency): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_currency' AND `value` = '" . $this->db->escape($currency) . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByCountryId(int $country_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_country_id' AND `value` = '" . (int)$country_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByZoneId(int $zone_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_zone_id' AND `value` = '" . (int)$zone_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByCustomerGroupId(int $customer_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_customer_group_id' AND `value` = '" . (int)$customer_group_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	public function getTotalStoresByInformationId(int $information_id): int {
		$account_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND `store_id` != '0'");

		$checkout_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND `store_id` != '0'");

		return ($account_query->row['total'] + $checkout_query->row['total']);
	}

	public function getTotalStoresByOrderStatusId(int $order_status_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_order_status_id' AND `value` = '" . (int)$order_status_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}
}
