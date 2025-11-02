<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Filter
 *
 * Can be loaded using $this->load->model('catalog/filter');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Filter extends \Opencart\System\Engine\Model {
	/**
	 * Add Filter
	 *
	 * Create a new filter record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new filter record
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_description' => [],
	 *     'sort_order'         => 0
	 * ];
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $filter_id = $this->model_catalog_filter->addFilter($filter_data);
	 */
	public function addFilter(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter` SET `filter_group_id` = '" . (int)$data['filter_group_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$filter_id = $this->db->getLastId();

		foreach ($data['filter_description'] as $language_id => $filter_description) {
			$this->model_catalog_filter->addDescription($filter_id, $language_id, $filter_description);
		}

		$this->cache->delete('filter');

		return $filter_id;
	}

	/**
	 * Edit Filter
	 *
	 * Edit filter record in the database.
	 *
	 * @param int                  $filter_id primary key of the filter record
	 * @param array<string, mixed> $data      array of data
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_description' => [],
	 *     'sort_order'         => 0
	 * ];
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $this->model_catalog_filter->editFilter($filter_id, $filter_data);
	 */
	public function editFilter(int $filter_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "filter` SET `filter_group_id` = '" . (int)$data['filter_group_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `filter_id` = '" . (int)$filter_id . "'");

		$this->model_catalog_filter->deleteDescriptions($filter_id);

		foreach ($data['filter_description'] as $language_id => $filter_description) {
			$this->model_catalog_filter->addDescription($filter_id, $language_id, $filter_description);
		}

		$this->cache->delete('filter');
	}

	/**
	 * Delete Filter
	 *
	 * Delete filter record in the database.
	 *
	 * @param int $filter_id primary key of the filter record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $this->model_catalog_filter->deleteFilter($filter_id);
	 */
	public function deleteFilter(int $filter_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter` WHERE `filter_id` = '" . (int)$filter_id . "'");

		$this->model_catalog_filter->deleteDescriptions($filter_id);

		// Category
		$this->load->model('catalog/category');

		$this->model_catalog_category->deleteFiltersByFilterId($filter_id);

		// Product
		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteFiltersByFilterId($filter_id);

		$this->cache->delete('filter');
	}

	/**
	 * Get Filter
	 *
	 * Get the record of the filter record in the database.
	 *
	 * @param int $filter_id primary key of the filter record
	 *
	 * @return array<string, mixed> filter record that has filter ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $filter_info = $this->model_catalog_filter->getFilter($filter_id);
	 */
	public function getFilter(int $filter_id): array {
		$query = $this->db->query("SELECT *, (SELECT `fgd`.`name` FROM `" . DB_PREFIX . "filter_group_description` `fgd` WHERE `fgd`.`filter_group_id` = `f`.`filter_group_id` AND `fgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `group` FROM `" . DB_PREFIX . "filter` `f` LEFT JOIN `" . DB_PREFIX . "filter_description` `fd` ON (`f`.`filter_id` = `fd`.`filter_id`) WHERE `f`.`filter_id` = '" . (int)$filter_id . "' AND `fd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Filters
	 *
	 * Get the record of the filter records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> filter records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'fgd.name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $results = $this->model_catalog_filter->getFilters($filter_data);
	 */
	public function getFilters(array $data = []): array {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT *, (SELECT `fgd`.`name` FROM `" . DB_PREFIX . "filter_group_description` `fgd` WHERE `fgd`.`filter_group_id` = `f`.`filter_group_id` AND `fgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `filter_group` FROM `" . DB_PREFIX . "filter` `f` LEFT JOIN `" . DB_PREFIX . "filter_description` `fd` ON (`f`.`filter_id` = `fd`.`filter_id`) WHERE `fd`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`fd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		$sort_data = [
			'name'         => 'fd.name',
			'filter_group' => 'filter_group',
			'sort_order'   => 'f.sort_order'
		];

		if (isset($data['sort']) && array_key_exists($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $sort_data[$data['sort']];
		} else {
			$sql .= " ORDER BY `filter_group`";
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
	 * Get Total Filters
	 *
	 * Get the total number of filter records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of filter records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'fgd.name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $filter_total = $this->model_catalog_filter->getTotalFilters();
	 */
	public function getTotalFilters(array $data = []): int {
		if (!empty($data['filter_language_id'])) {
			$language_id = $data['filter_language_id'];
		} else {
			$language_id = $this->config->get('config_language_id');
		}

		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "filter` `f` LEFT JOIN `" . DB_PREFIX . "filter_description` `fd` ON (`f`.`filter_id` = `fd`.`filter_id`) WHERE `fd`.`language_id` = '" . (int)$language_id . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`fd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Filters By Filter Group ID
	 *
	 * Get the total number of filters by filter group records in the database.
	 *
	 * @param int $filter_group_id primary key of the filter group record
	 *
	 * @return int total number of filter records that have filter group ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $filter_total = $this->model_catalog_filter->getTotalFiltersByFilterGroupId($filter_group_id);
	 */
	public function getTotalFiltersByFilterGroupId(int $filter_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "filter` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new filter description record in the database.
	 *
	 * @param int                  $filter_id   primary key of the filter record
	 * @param int                  $language_id primary key of the language record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $filter_data['filter_description'] = [
	 *     'name' => 'Filter Name'
	 * ];
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $this->model_catalog_filter->addDescription($filter_id, $language_id, $filter_data);
	 */
	public function addDescription(int $filter_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_description` SET `filter_id` = '" . (int)$filter_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete filter description records in the database.
	 *
	 * @param int $filter_id primary key of the filter record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $this->model_catalog_filter->deleteDescriptions($filter_id);
	 */
	public function deleteDescriptions(int $filter_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE `filter_id` = '" . (int)$filter_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete filter descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $this->model_catalog_filter->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the filter description records in the database.
	 *
	 * @param int $filter_id primary key of the filter record
	 *
	 * @return array<int, array<string, string>> description records that have filter ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $filter_description = $this->model_catalog_filter->getDescriptions($filter_id);
	 */
	public function getDescriptions(int $filter_id): array {
		$filter_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_description` WHERE `filter_id` = '" . (int)$filter_id . "'");

		foreach ($query->rows as $result) {
			$filter_data[$result['language_id']] = $result;
		}

		return $filter_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the filter descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter');
	 *
	 * $results = $this->model_catalog_filter->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
