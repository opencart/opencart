<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Language
 *
 * Can be loaded using $this->load->model('localisation/language');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Language extends \Opencart\System\Engine\Model {
	/**
	 * Add Language
	 *
	 * Create a new language record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new language record
	 *
	 * @example
	 *
	 * $language_data = [
	 *     'name'       => 'Language Name',
	 *     'code'       => 'Language Code',
	 *     'locale'     => 'Language Locale',
	 *     'extension'  => '',
	 *     'sort_order' => 0,
	 *     'status'     => 0
	 * ];
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $language_id = $this->model_localisation_language->addLanguage($language_data);
	 */
	public function addLanguage(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "language` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `locale` = '" . $this->db->escape((string)$data['locale']) . "', `extension` = '" . $this->db->escape((string)$data['extension']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "'");

		$this->cache->delete('language');

		$language_id = $this->db->getLastId();

		$models = [
			'catalog/attribute' => [
				'model' => 'model_catalog_attribute',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'catalog/attribute_group' => [
				'model' => 'model_catalog_attribute_group',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'design/banner' => [
				'model' => 'model_design_banner',
				'methods' => ['getImagesByLanguageId', 'addImage']
			],
			'catalog/category' => [
				'model' => 'model_catalog_category',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'localisation/country' => [
				'model' => 'model_localisation_country',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'customer/customer_group' => [
				'model' => 'model_customer_customer_group',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'customer/custom_field' => [
				'model' => 'model_customer_custom_field',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription', 'getValueDescriptionsByLanguageId', 'addValueDescription']
			],
			'catalog/download' => [
				'model' => 'model_catalog_download',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'catalog/filter' => [
				'model' => 'model_catalog_filter',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'catalog/filter_group' => [
				'model' => 'model_catalog_filter_group',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'catalog/information' => [
				'model' => 'model_catalog_information',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'localisation/length_class' => [
				'model' => 'model_localisation_length_class',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'catalog/option' => [
				'model' => 'model_catalog_option',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription', 'getValueDescriptionsByLanguageId', 'addValueDescription']
			],
			'localisation/order_status' => [
				'model' => 'model_localisation_order_status',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'catalog/product' => [
				'model' => 'model_catalog_product',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription', 'getAttributesByLanguageId', 'addAttribute']
			],
			'localisation/return_action' => [
				'model' => 'model_localisation_return_action',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'localisation/return_reason' => [
				'model' => 'model_localisation_return_reason',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'localisation/return_status' => [
				'model' => 'model_localisation_return_status',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'localisation/stock_status' => [
				'model' => 'model_localisation_stock_status',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'localisation/weight_class' => [
				'model' => 'model_localisation_weight_class',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'catalog/subscription_plan' => [
				'model' => 'model_catalog_subscription_plan',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'localisation/subscription_status' => [
				'model' => 'model_localisation_subscription_status',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'cms/topic' => [
				'model' => 'model_cms_topic',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			],
			'localisation/zone' => [
				'model' => 'model_localisation_zone',
				'methods' => ['getDescriptionsByLanguageId', 'addDescription']
			]
		];
		
		foreach ($models as $key => $methods) {
			// Load the model dynamically
			$this->load->model($key);

			$keyParts = explode('/', $key);
			$modelName = end($keyParts);

			// Call the first method dynamically (e.g., getDescriptionsByLanguageId)
			$results = $this->{$methods['model']}->{$methods['methods'][0]}($this->config->get('config_language_id'));

			// Loop through the results and call the second method (e.g., addDescription)
			foreach ($results as $result) {
				$this->{$methods['model']}->{$methods['methods'][1]}($result[$modelName . '_id'], $language_id, $result);
			}

			// Special case for addValueDescription &  addAttribute
			if (isset($methods['methods'][2]) && isset($methods['methods'][3])) {
				$results = $this->{$methods['model']}->{$methods['methods'][2]}($this->config->get('config_language_id'));

				foreach ($results as $result) {
					if($methods['methods'][3] === 'addValueDescription') {
						$this->{$methods['model']}->{$methods['methods'][3]}(
							$result[$modelName . '_value_id'],
							$result[$modelName . '_id'],
							$language_id,
							$result
						);
					} elseif($methods['methods'][3] === 'addAttribute') {
						$this->{$methods['model']}->{$methods['methods'][3]}(
							$result[$modelName . '_id'],
							$result['attribute_id'],
							$language_id,
							$result
						);
					}
				}
			}
		}

		// SEO
		$this->load->model('design/seo_url');

		$results = $this->model_design_seo_url->getSeoUrlsByLanguageId($this->config->get('config_language_id'));

		foreach ($results as $seo_url) {
			$this->model_design_seo_url->addSeoUrl($seo_url['key'], $seo_url['value'], $seo_url['keyword'], $seo_url['store_id'], $language_id, $seo_url['sort_order']);
		}

		// Setup new SEO URL language keyword
		$languages = $this->getLanguages();

		foreach ($languages as $language) {
			// Set default store
			$this->model_design_seo_url->addSeoUrl('language', (string)$data['code'], (string)$data['code'], 0, $language['language_id'], -2);
		}


		$this->load->model('setting/store');
		$this->load->model('setting/setting');

		$stores = $this->model_setting_store->getStores();
		$store_ids = [0]; 

		foreach ($stores as $store) {
			$store_ids[] = $store['store_id'];

			foreach ($languages as $language) {
				$this->model_design_seo_url->addSeoUrl('language', (string)$data['code'], (string)$data['code'], $store['store_id'], $language['language_id'], -2);
			}
		}
		
		foreach ($store_ids as $store_id) {
			// Get all settings for this store
			$settings = $this->model_setting_setting->getSetting('config', $store_id);
	
			// Ensure 'config_description' exists
			if (isset($settings['config_description'])) {
				$config_description = $settings['config_description'];
			} else {
				$config_description = [];
			}

			// Get the first language's data
			$firstLang = reset($config_description);

			// Add new language entry if not already present
			if (!isset($config_description[$language_id])) {
				$config_description[$language_id] = [
					'meta_title'       => $firstLang['meta_title'] ?? '',
					'meta_description' => $firstLang['meta_description'] ?? '',
					'meta_keyword'     => $firstLang['meta_keyword'] ?? ''
				];
			}

			// Update the settings array
			$settings['config_description'] = $config_description;

			// Save back to the database
			$this->model_setting_setting->editSetting('config', $settings, $store_id);
		}

		return $language_id;
	}

	/**
	 * Edit Language
	 *
	 * Edit language record in the database.
	 *
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $language_data = [
	 *     'name'       => 'Language Name',
	 *     'code'       => 'Language Code',
	 *     'locale'     => 'Language Locale',
	 *     'extension'  => '',
	 *     'sort_order' => 0,
	 *     'status'     => 1
	 * ];
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $this->model_localisation_language->editLanguage($language_id, $language_data);
	 */
	public function editLanguage(int $language_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "language` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `locale` = '" . $this->db->escape((string)$data['locale']) . "', `extension` = '" . $this->db->escape((string)$data['extension']) . "', `sort_order` = '" . (int)$data['sort_order'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "' WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('language');
	}


	public function deleteLanguage(int $language_id): void {
		$language_info = $this->getLanguage($language_id);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('language');

		// Article
		$this->load->model('cms/article');

		$this->model_cms_article->deleteDescriptionsByLanguageId($language_id);

		// Attribute
		$this->load->model('catalog/attribute');

		$this->model_catalog_attribute->deleteDescriptionsByLanguageId($language_id);

		// Attribute Group
		$this->load->model('catalog/attribute_group');

		$this->model_catalog_attribute_group->deleteDescriptionsByLanguageId($language_id);

		// Banner
		$this->load->model('design/banner');

		$this->model_design_banner->deleteImagesByLanguageId($language_id);

		// Category
		$this->load->model('catalog/category');

		$this->model_catalog_category->deleteDescriptionsByLanguageId($language_id);

		// Country
		$this->load->model('localisation/country');

		$this->model_localisation_country->deleteDescriptionsByLanguageId($language_id);

		// Customer Group
		$this->load->model('customer/customer_group');

		$this->model_customer_customer_group->deleteDescriptionsByLanguageId($language_id);

		// Custom Field
		$this->load->model('customer/custom_field');

		$this->model_customer_custom_field->deleteDescriptionsByLanguageId($language_id);
		$this->model_customer_custom_field->deleteValueDescriptionsByLanguageId($language_id);

		// Download
		$this->load->model('catalog/download');

		$this->model_catalog_download->deleteDescriptionsByLanguageId($language_id);

		// Filter
		$this->load->model('catalog/filter');

		$this->model_catalog_filter->deleteDescriptionsByLanguageId($language_id);

		// Filter Group
		$this->load->model('catalog/filter_group');

		$this->model_catalog_filter_group->deleteDescriptionsByLanguageId($language_id);

		// Information
		$this->load->model('catalog/information');

		$this->model_catalog_information->deleteDescriptionsByLanguageId($language_id);

		// Length Class
		$this->load->model('localisation/length_class');

		$this->model_localisation_length_class->deleteDescriptionsByLanguageId($language_id);

		// Option
		$this->load->model('catalog/option');

		$this->model_catalog_option->deleteDescriptionsByLanguageId($language_id);
		$this->model_catalog_option->deleteValueDescriptionsByLanguageId($language_id);

		// Order Status
		$this->load->model('localisation/order_status');

		$this->model_localisation_order_status->deleteOrderStatusesByLanguageId($language_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteDescriptionsByLanguageId($language_id);
		$this->model_catalog_product->deleteAttributesByLanguageId($language_id);

		// Return Action
		$this->load->model('localisation/return_action');

		$this->model_localisation_return_action->deleteReturnActionsByLanguageId($language_id);

		// Return Reason
		$this->load->model('localisation/return_reason');

		$this->model_localisation_return_reason->deleteReturnReasonsByLanguageId($language_id);

		// Return Status
		$this->load->model('localisation/return_status');

		$this->model_localisation_return_status->deleteReturnStatusesByLanguageId($language_id);

		// Stock Status
		$this->load->model('localisation/stock_status');

		$this->model_localisation_stock_status->deleteStockStatusesByLanguageId($language_id);

		// Weight Class
		$this->load->model('localisation/weight_class');

		$this->model_localisation_weight_class->deleteDescriptionsByLanguageId($language_id);

		// Subscription Status
		$this->load->model('localisation/subscription_status');

		$this->model_localisation_subscription_status->deleteStockStatusesByLanguageId($language_id);

		// SEO
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByLanguageId($language_id);

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('language', $language_info['code']);

		// Topic Status
		$this->load->model('cms/topic');

		$this->model_cms_topic->deleteDescriptionsByLanguageId($language_id);

		// Zone
		$this->load->model('localisation/zone');

		$this->model_localisation_zone->deleteDescriptionsByLanguageId($language_id);
	}

	/**
	 * Get Language
	 *
	 * Get the record of the language record in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<string, mixed> language record that has language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $language_info = $this->model_localisation_language->getLanguage($language_id);
	 */
	public function getLanguage(int $language_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "language` WHERE `language_id` = '" . (int)$language_id . "'");

		$language = $query->row;

		if ($language) {
			$language['image'] = HTTP_CATALOG;

			if (!$language['extension']) {
				$language['image'] .= 'catalog/';
			} else {
				$language['image'] .= 'extension/' . $language['extension'] . '/catalog/';
			}

			$language['image'] .= 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}

		return $language;
	}

	/**
	 * Get Language By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $language_info = $this->model_localisation_language->getLanguageByCode($code);
	 */
	public function getLanguageByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `code` = '" . $this->db->escape($code) . "'");

		$language = $query->row;

		if ($language) {
			$language['image'] = HTTP_CATALOG;

			if (!$language['extension']) {
				$language['image'] .= 'catalog/';
			} else {
				$language['image'] .= 'extension/' . $language['extension'] . '/catalog/';
			}

			$language['image'] .= 'language/' . $language['code'] . '/' . $language['code'] . '.png';
		}

		return $language;
	}

	/**
	 * Get Languages
	 *
	 * Get the record of the language records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<string, array<string, mixed>> language records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $languages = $this->model_localisation_language->getLanguages($filter_data);
	 */
	public function getLanguages(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "language`";

		$sort_data = [
			'name',
			'code',
			'sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `sort_order`, `name`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$results = $this->cache->get('language.' . md5($sql));

		if (!$results) {
			$query = $this->db->query($sql);

			$results = $query->rows;

			$this->cache->set('language.' . md5($sql), $results);
		}

		$language_data = [];

		foreach ($results as $result) {
			$image = HTTP_CATALOG;

			if (!$result['extension']) {
				$image .= 'catalog/';
			} else {
				$image .= 'extension/' . $result['extension'] . '/catalog/';
			}

			$language_data[$result['code']] = $result + ['image' => $image . 'language/' . $result['code'] . '/' . $result['code'] . '.png'];
		}

		return $language_data;
	}

	/**
	 * Get Languages By Extension
	 *
	 * @param string $extension
	 *
	 * @return array<int, array<string, mixed>>
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $results = $this->model_localisation_language->getLanguagesByExtension($extension);
	 */
	public function getLanguagesByExtension(string $extension): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE `extension` = '" . $this->db->escape($extension) . "'");

		return $query->rows;
	}

	/**
	 * Get Total Languages
	 *
	 * Get the total number of language records in the database.
	 *
	 * @return int total number of language records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/language');
	 *
	 * $language_total = $this->model_localisation_language->getTotalLanguages();
	 */
	public function getTotalLanguages(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "language`");

		return (int)$query->row['total'];
	}
}
