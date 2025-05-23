<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Store
 *
 * Can be loaded using $this->load->model('setting/store');
 *
 * @package Opencart\Admin\Model\Setting
 */
class Store extends \Opencart\System\Engine\Model {
	/**
	 * Add Store
	 *
	 * Create a new store record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new store record
	 *
	 * @example
	 *
	 * $store_data = [
	 *     'name' => 'Store Name',
	 *     'url'  => ''
	 * ];
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_id = $this->model_setting_store->addStore($store_data);
	 */
	public function addStore(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "store` SET `name` = '" . $this->db->escape((string)$data['config_name']) . "', `url` = '" . $this->db->escape((string)$data['config_url']) . "'");

		$store_id = $this->db->getLastId();

		// Layout Route
		$this->load->model('design/layout');

		$results = $this->model_design_layout->getRoutesByStoreId(0);

		foreach ($results as $result) {
			$this->model_design_layout->addRoute($result['layout_id'], ['store_id' => $store_id] + $result);
		}

		// SEO
		$this->load->model('design/seo_url');

		$results = $this->model_design_seo_url->getSeoUrlsByStoreId(0);

		foreach ($results as $result) {
			$this->model_design_seo_url->addSeoUrl($result['key'], $result['value'], $result['keyword'], $store_id, $result['language_id'], $result['sort_order']);
		}

		// Populate countries
		$this->load->model('localisation/country');

		$results = $this->model_localisation_country->getCountries();

		foreach ($results as $result) {
			$this->model_localisation_country->addStore($result['country_id'], $store_id);
		}

		$this->cache->delete('store');

		return $store_id;
	}

	/**
	 * Edit Store
	 *
	 * Edit store record in the database.
	 *
	 * @param int                  $store_id primary key of the store record
	 * @param array<string, mixed> $data     array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $store_data = [
	 *     'name' => 'Store Name',
	 *     'url'  => ''
	 * ];
	 *
	 * $this->load->model('setting/store');
	 *
	 * $this->model_setting_store->editStore($store_id, $store_data);
	 */
	public function editStore(int $store_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "store` SET `name` = '" . $this->db->escape((string)$data['config_name']) . "', `url` = '" . $this->db->escape((string)$data['config_url']) . "' WHERE `store_id` = '" . (int)$store_id . "'");

		$this->cache->delete('store');
	}

	/**
	 * Delete Store
	 *
	 * Delete store record in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $this->model_setting_store->deleteStore($store_id);
	 */
	public function deleteStore(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "store` WHERE `store_id` = '" . (int)$store_id . "'");

		// Category
		$this->load->model('catalog/category');

		$this->model_catalog_category->deleteLayoutsByStoreId($store_id);
		$this->model_catalog_category->deleteStoresByStoreId($store_id);

		// Information
		$this->load->model('catalog/information');

		$this->model_catalog_information->deleteLayoutsByStoreId($store_id);
		$this->model_catalog_information->deleteStoresByStoreId($store_id);

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		$this->model_catalog_manufacturer->deleteLayoutsByStoreId($store_id);
		$this->model_catalog_manufacturer->deleteStoresByStoreId($store_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteLayoutsByStoreId($store_id);
		$this->model_catalog_product->deleteStoresByStoreId($store_id);

		// GDPR
		$this->load->model('customer/gdpr');

		$this->model_customer_gdpr->deleteGdprsByStoreId($store_id);

		// Theme
		$this->load->model('design/theme');

		$this->model_design_theme->deleteThemesByStoreId($store_id);

		// Translation
		$this->load->model('design/translation');

		$this->model_design_translation->deleteTranslationsByStoreId($store_id);

		// SEO
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByStoreId($store_id);

		// Setting
		$this->load->model('setting/setting');

		$this->model_setting_setting->deleteSettingsByStoreId($store_id);

		// Country
		$this->load->model('localisation/country');

		$this->model_localisation_country->deleteStoresByStoreId($store_id);

		$this->cache->delete('store');
	}

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
	 * Get Stores
	 *
	 * Get the record of the store records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> store records
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $results = $this->model_setting_store->getStores();
	 */
	public function getStores(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "store` ORDER BY `url` ASC";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Create Store Instance
	 *
	 * @param int    $store_id primary key of the store record
	 * @param string $language
	 * @param string $currency
	 *
	 * @throws \Exception
	 *
	 * @return \Opencart\System\Engine\Registry
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $this->model_setting_store->createStoreInstance($store_id, $language, $currency);
	 */
	public function createStoreInstance(int $store_id = 0, string $language = '', string $currency = ''): \Opencart\System\Engine\Registry {
		// Autoloader
		$this->autoloader->register('Opencart\Catalog', DIR_CATALOG);

		// Registry
		$registry = new \Opencart\System\Engine\Registry();
		$registry->set('autoloader', $this->autoloader);

		$config = new \Opencart\System\Engine\Config();
		$registry->set('config', $config);

		// Load the default config
		$config->addPath(DIR_CONFIG);
		$config->load('default');
		$config->load('catalog');
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

		// Create a dummy request class, so we can feed the data to the order editor
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
		$template->addPath(DIR_CATALOG . 'view/template/');
		$registry->set('template', $template);

		// Adding language var to the GET variable so there is a default language
		if ($language) {
			$request->get['language'] = $language;
		} else {
			$request->get['language'] = $config->get('language_code');
		}

		// Language
		$language = new \Opencart\System\Library\Language($language);
		$language->addPath(DIR_CATALOG . 'language/');
		$language->load('default');
		$registry->set('language', $language);

		// Url
		$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

		// Document
		$registry->set('document', new \Opencart\System\Library\Document());

		// Language

		// Currency
		$session->data['currency'] = $currency;

		// Run pre actions to load key settings and classes.
		$pre_actions = [
			'startup/setting',
			'startup/language',
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

	/**
	 * Get Total Stores
	 *
	 * Get the total number of total store records in the database.
	 *
	 * @return int total number of store records
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStores();
	 */
	public function getTotalStores(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "store`");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Stores By Layout ID
	 *
	 * Get the total number of total stores by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return int total number of store records that have layout ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStoresByLayoutId($layout_id);
	 */
	public function getTotalStoresByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_layout_id' AND `value` = '" . (int)$layout_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Stores By Language
	 *
	 * @param string $language
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStoresByLanguage($language);
	 */
	public function getTotalStoresByLanguage(string $language): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_language' AND `value` = '" . $this->db->escape($language) . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Stores By Currency
	 *
	 * @param string $currency
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStoresByCurrency($currency);
	 */
	public function getTotalStoresByCurrency(string $currency): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_currency' AND `value` = '" . $this->db->escape($currency) . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Stores By Country ID
	 *
	 * Get the total number of total stores by country records in the database.
	 *
	 * @param int $country_id primary key of the country record
	 *
	 * @return int total number of store records that have country ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStoresByCountryId($country_id);
	 */
	public function getTotalStoresByCountryId(int $country_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_country_id' AND `value` = '" . (int)$country_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Stores By Zone ID
	 *
	 * Get the total number of total stores by zone records in the database.
	 *
	 * @param int $zone_id primary key of the zone record
	 *
	 * @return int total number of store records that have zone ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStoresByZoneId($zone_id);
	 */
	public function getTotalStoresByZoneId(int $zone_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_zone_id' AND `value` = '" . (int)$zone_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Stores By Customer Group ID
	 *
	 * Get the total number of total stores by customer group records in the database.
	 *
	 * @param int $customer_group_id primary key of the customer group record
	 *
	 * @return int total number of store records that have customer group ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStoresByCustomerGroupId($customer_group_id);
	 */
	public function getTotalStoresByCustomerGroupId(int $customer_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_customer_group_id' AND `value` = '" . (int)$customer_group_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Stores By Information ID
	 *
	 * Get the total number of total stores by information records in the database.
	 *
	 * @param int $information_id primary key of the information record
	 *
	 * @return int total number of store records that have information ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStoresByInformationId($information_id);
	 */
	public function getTotalStoresByInformationId(int $information_id): int {
		$account_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND `store_id` != '0'");

		$checkout_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND `store_id` != '0'");

		return $account_query->row['total'] + $checkout_query->row['total'];
	}

	/**
	 * Get Total Stores By Order Status ID
	 *
	 * Get the total number of total stores by order status records in the database.
	 *
	 * @param int $order_status_id primary key of the order status record
	 *
	 * @return int total number of store records that have order status ID
	 *
	 * @example
	 *
	 * $this->load->model('setting/store');
	 *
	 * $store_total = $this->model_setting_store->getTotalStoresByOrderStatusId($order_status_id);
	 */
	public function getTotalStoresByOrderStatusId(int $order_status_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_order_status_id' AND `value` = '" . (int)$order_status_id . "' AND `store_id` != '0'");

		return (int)$query->row['total'];
	}
}
