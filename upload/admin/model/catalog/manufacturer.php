<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Manufacturer
 *
 * Can be loaded using $this->load->model('catalog/manufacturer');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Manufacturer extends \Opencart\System\Engine\Model {
	/**
	 * Add Manufacturer
	 *
	 * Create a new manufacturer record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new manufacturer record
	 *
	 * @example
	 *
	 * $manufacturer_data = [
	 *     'name'       => 'Manufacturer Name',
	 *     'image'      => 'manufacturer_image',
	 *     'sort_order' => 'DESC'
	 * ];
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $manufacturer_id = $this->model_catalog_manufacturer->addManufacturer($manufacturer_data);
	 */
	public function addManufacturer(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer` SET `image` = '" . $this->db->escape((string)$data['image']) . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$manufacturer_id = $this->db->getLastId();

		// Description
		foreach ($data['manufacturer_description'] as $language_id => $manufacturer_description) {
			$this->model_catalog_manufacturer->addDescription($manufacturer_id, $language_id, $manufacturer_description);
		}

		// Store
		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->model_catalog_manufacturer->addStore($manufacturer_id, $store_id);
			}
		}

		// SEO
		$this->load->model('design/seo_url');

		foreach ($data['manufacturer_seo_url'] as $store_id => $language) {
			foreach ($language as $language_id => $keyword) {
				$this->model_design_seo_url->addSeoUrl('manufacturer_id', $manufacturer_id, $keyword, $store_id, $language_id);
			}
		}

		// Layouts
		if (isset($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->model_catalog_manufacturer->addLayout($manufacturer_id, $store_id, $layout_id);
				}
			}
		}

		$this->cache->delete('manufacturer');

		return $manufacturer_id;
	}

	/**
	 * Edit Manufacturer
	 *
	 * Edit manufacturer record in the database.
	 *
	 * @param int                  $manufacturer_id primary key of the manufacturer record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $manufacturer_data = [
	 *     'name'       => 'Manufacturer Name',
	 *     'image'      => 'manufacturer_image',
	 *     'sort_order' => 'DESC'
	 * ];
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->editManufacturer($manufacturer_id, $manufacturer_data);
	 */
	public function editManufacturer(int $manufacturer_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = '" . $this->db->escape((string)$data['image']) . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");


		// Description
		$this->deleteDescriptions($manufacturer_id);

		foreach ($data['manufacturer_description'] as $language_id => $manufacturer_description) {
			$this->model_catalog_manufacturer->addDescription($manufacturer_id, $language_id, $manufacturer_description);
		}

		// Store
		$this->deleteStores($manufacturer_id);

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->model_catalog_manufacturer->addStore($manufacturer_id, $store_id);
			}
		}

		// SEO
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('manufacturer_id', $manufacturer_id);

		if (isset($data['manufacturer_seo_url'])) {
			foreach ($data['manufacturer_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					$this->model_design_seo_url->addSeoUrl('manufacturer_id', $manufacturer_id, $keyword, $store_id, $language_id);
				}
			}
		}

		// Layouts
		$this->model_catalog_manufacturer->deleteLayouts($manufacturer_id);

		if (isset($data['manufacturer_layout'])) {
			foreach ($data['manufacturer_layout'] as $store_id => $layout_id) {
				if ($layout_id) {
					$this->model_catalog_manufacturer->addLayout($manufacturer_id, $store_id, $layout_id);
				}
			}
		}

		$this->cache->delete('manufacturer');
	}

	/**
	 * Edit Status
	 *
	 * Edit manufacturer status record in the database.
	 *
	 * @param int  $manufacturer_id primary key of the manufacturer record
	 * @param bool $status
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->editStatus($manufacturer_id, $status);
	 */
	public function editStatus(int $manufacturer_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `status` = '" . (bool)$status . "' WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		$this->cache->delete('manufacturer');
	}

	/**
	 * Delete Manufacturer
	 *
	 * Delete manufacturer record in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->deleteManufacturer($manufacturer_id);
	 */
	public function deleteManufacturer(int $manufacturer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		$this->model_catalog_manufacturer->deleteDescriptions($manufacturer_id);
		$this->model_catalog_manufacturer->deleteStores($manufacturer_id);
		$this->model_catalog_manufacturer->deleteLayouts($manufacturer_id);

		// SEO
		$this->load->model('design/seo_url');

		$this->model_design_seo_url->deleteSeoUrlsByKeyValue('manufacturer_id', $manufacturer_id);

		$this->cache->delete('manufacturer');
	}

	/**
	 * Get Manufacturer
	 *
	 * Get the record of the manufacturer record in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return array<string, mixed> manufacturer record that has manufacturer ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);
	 */
	public function getManufacturer(int $manufacturer_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "manufacturer` `m` LEFT JOIN `" . DB_PREFIX . "manufacturer_description` `md` ON (`m`.`manufacturer_id` = `md`.`manufacturer_id`) WHERE `m`.`manufacturer_id` = '" . (int)$manufacturer_id . "' AND `md`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Manufacturers
	 *
	 * Get the record of the manufacturer records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> manufacturer records
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
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $results = $this->model_catalog_manufacturer->getManufacturers($filter_data);
	 */
	public function getManufacturers(array $data = []): array {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT * FROM `" . DB_PREFIX . "manufacturer` `m` LEFT JOIN `" . DB_PREFIX . "manufacturer_description` `md` ON (`m`.`manufacturer_id` = `md`.`manufacturer_id`)";

		if (isset($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "manufacturer_to_store` `m2s` ON (`m`.`manufacturer_id` = `m2s`.`manufacturer_id`)";
		}

		$sql .= " WHERE `md`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`md`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (isset($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$sql .= " AND `m2s`.`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `m`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$sort_data = [
			'name'       => 'md.name',
			'sort_order' => 'm.sort_order'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `name`";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	/**
	 * Get Total Manufacturers
	 *
	 * Get the total number of manufacturer records in the database.
	 *
	 * @return int total number of manufacturer records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $manufacturer_total = $this->model_catalog_manufacturer->getTotalManufacturers();
	 */
	public function getTotalManufacturers(array $data = []): int {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "manufacturer` `m` LEFT JOIN `" . DB_PREFIX . "manufacturer_description` `md` ON (`m`.`manufacturer_id` = `md`.`manufacturer_id`)";

		if (isset($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$sql .= " LEFT JOIN `" . DB_PREFIX . "manufacturer_to_store` `m2s` ON (`m`.`manufacturer_id` = `m2s`.`manufacturer_id`)";
		}

		$sql .= " WHERE `md`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`md`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		if (isset($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$sql .= " AND `m2s`.`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " AND `m`.`status` = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new manufacturer description record in the database.
	 *
	 * @param int                  $manufacturer_id primary key of the manufacturer record
	 * @param int                  $language_id    primary key of the language record
	 * @param array<string, mixed> $data           array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $manufacturer_data['manufacturer_description'] = [
	 *     'title'            => 'manufacturer Title',
	 *     'description'      => 'manufacturer Description',
	 *     'meta_title'       => 'Meta Title',
	 *     'meta_description' => 'Meta Description',
	 *     'meta_keyword'     => 'Meta Keyword'
	 * ];
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->addDescription($manufacturer_id, $language_id, $manufacturer_data);
	 */
	public function addDescription(int $manufacturer_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_description` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape((string)$data['name']) . "', `description` = '" . $this->db->escape((string)$data['description']) . "', `meta_title` = '" . $this->db->escape((string)$data['meta_title']) . "', `meta_description` = '" . $this->db->escape((string)$data['meta_description']) . "', `meta_keyword` = '" . $this->db->escape((string)$data['meta_keyword']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete manufacturer description records in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->deleteDescriptions($manufacturer_id);
	 */
	public function deleteDescriptions(int $manufacturer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_description` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete manufacturer descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the manufacturer description records in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return array<int, array<string, string>> description records that have manufacturer ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $manufacturer_description = $this->model_catalog_manufacturer->getDescriptions($manufacturer_id);
	 */
	public function getDescriptions(int $manufacturer_id): array {
		$manufacturer_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer_description` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_description_data[$result['language_id']] = $result;
		}

		return $manufacturer_description_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the manufacturer descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $results = $this->model_catalog_manufacturer->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Add Store
	 *
	 * Create a new manufacturer store record in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 * @param int $store_id        primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->addStore($manufacturer_id, $store_id);
	 */
	public function addStore(int $manufacturer_id, int $store_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Delete Stores
	 *
	 * Delete manufacturer store records in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->deleteStores($manufacturer_id);
	 */
	public function deleteStores(int $manufacturer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
	}

	/**
	 * Delete Stores By Store ID
	 *
	 * Delete manufacturer stores by store records in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->deleteStoresByStoreId($store_id);
	 */
	public function deleteStoresByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Stores
	 *
	 * Get the record of the manufacturer store records in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return array<int, int> store records that have manufacturer ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $manufacturer_store = $this->model_catalog_manufacturer->getStores($manufacturer_id);
	 */
	public function getStores(int $manufacturer_id): array {
		$manufacturer_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

	/**
	 * Add Layout
	 *
	 * Create a new manufacturer layout record in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 * @param int $store_id        primary key of the store record
	 * @param int $layout_id       primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->addLayout($manufacturer_id, $store_id, $layout_id);
	 */
	public function addLayout(int $manufacturer_id, int $store_id, int $layout_id): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_layout` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `store_id` = '" . (int)$store_id . "', `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts
	 *
	 * Delete manufacturer layout records in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->deleteLayouts($manufacturer_id);
	 */
	public function deleteLayouts(int $manufacturer_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
	}

	/**
	 * Delete Layouts By Layout ID
	 *
	 * Delete manufacturer layouts by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->deleteLayoutsByLayoutId($layout_id);
	 */
	public function deleteLayoutsByLayoutId(int $layout_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");
	}

	/**
	 * Delete Layouts By Store ID
	 *
	 * Delete manufacturer layouts by store records in the database.
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $this->model_catalog_manufacturer->deleteLayoutsByStoreId($store_id);
	 */
	public function deleteLayoutsByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Layouts
	 *
	 * Get the record of the manufacturer layout records in the database.
	 *
	 * @param int $manufacturer_id primary key of the manufacturer record
	 *
	 * @return array<int, int> layout records that have manufacturer ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $manufacturer_layout = $this->model_catalog_manufacturer->getLayouts($manufacturer_id);
	 */
	public function getLayouts(int $manufacturer_id): array {
		$manufacturer_layout_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_layout_data[$result['store_id']] = $result['layout_id'];
		}

		return $manufacturer_layout_data;
	}

	/**
	 * Get Total Layouts By Layout ID
	 *
	 * Get the total number of manufacturer layout by layout records in the database.
	 *
	 * @param int $layout_id primary key of the layout record
	 *
	 * @return int total number of layout records that have layout ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/manufacturer');
	 *
	 * $manufacturer_total = $this->model_catalog_manufacturer->getTotalLayoutsByLayoutId($layout_id);
	 */
	public function getTotalLayoutsByLayoutId(int $layout_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "manufacturer_to_layout` WHERE `layout_id` = '" . (int)$layout_id . "'");

		return (int)$query->row['total'];
	}
}
