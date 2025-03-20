<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Filter Group
 *
 * Can be loaded using $this->load->model('catalog/filter_group');
 *
 * @package Opencart\Admin\Model\Catalog
 */
class FilterGroup extends \Opencart\System\Engine\Model {
	/**
	 * Add Filter Group
	 *
	 * Create a new filter group record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new filter group record
	 *
	 * @example
	 *
	 * $filter_group_data = [
	 *     'filter_group_description' => [],
	 *     'sort_order'               => 0
	 * ];
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $filter_group_id = $this->model_catalog_filter_group->addFilterGroup($filter_group_data);
	 */
	public function addFilterGroup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET `sort_order` = '" . (int)$data['sort_order'] . "'");

		$filter_group_id = $this->db->getLastId();

		foreach ($data['filter_group_description'] as $language_id => $filter_group_description) {
			$this->model_catalog_filter_group->addDescription($filter_group_id, $language_id, $filter_group_description);
		}

		$this->cache->delete('filter_group');

		return $filter_group_id;
	}

	/**
	 * Edit Filter Group
	 *
	 * Edit filter group record in the database.
	 *
	 * @param int                  $filter_group_id primary key of the filter group record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $filter_group_data = [
	 *     'filter_group_description' => [],
	 *     'sort_order'               => 0
	 * ];
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $this->model_catalog_filter_group->editFilterGroup($filter_group_id, $filter_group_data);
	 */
	public function editFilterGroup(int $filter_group_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "filter_group` SET `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		$this->model_catalog_filter_group->deleteDescriptions($filter_group_id);

		foreach ($data['filter_group_description'] as $language_id => $filter_group_description) {
			$this->model_catalog_filter_group->addDescription($filter_group_id, $language_id, $filter_group_description);
		}

		$this->cache->delete('filter_group');
	}

	/**
	 * Delete Filter Group
	 *
	 * Delete filter group record in the database.
	 *
	 * @param int $filter_group_id primary key of the filter group record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $this->model_catalog_filter_group->deleteFilterGroup($filter_group_id);
	 */
	public function deleteFilterGroup(int $filter_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		$this->model_catalog_filter_group->deleteDescriptions($filter_group_id);

		$this->cache->delete('filter_group');
	}

	/**
	 * Get Filter Group
	 *
	 * Get the record of the filter group record in the database.
	 *
	 * @param int $filter_group_id primary key of the filter group record
	 *
	 * @return array<string, mixed> filter group record that has filter group ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $filter_group_info = $this->model_catalog_filter_group->getFilterGroup($filter_group_id);
	 */
	public function getFilterGroup(int $filter_group_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` `fg` LEFT JOIN `" . DB_PREFIX . "filter_group_description` `fgd` ON (`fg`.`filter_group_id` = `fgd`.`filter_group_id`) WHERE `fg`.`filter_group_id` = '" . (int)$filter_group_id . "' AND `fgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Filter Groups
	 *
	 * Get the record of the filter group records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> filter group records
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
	 * $this->load->model('catalog/filter_group');
	 *
	 * $results = $this->model_catalog_filter_group->getFilterGroups($filter_data);
	 */
	public function getFilterGroups(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "filter_group` `fg` LEFT JOIN `" . DB_PREFIX . "filter_group_description` `fgd` ON (`fg`.`filter_group_id` = `fgd`.`filter_group_id`) WHERE `fgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`fgd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		$sort_data = [
			'fgd.name',
			'fg.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `fgd`.`name`";
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
	 * Get Total Filter Groups
	 *
	 * Get the total number of filter group records in the database.
	 *
	 * @return int total number of filter group records
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $filter_group_total = $this->model_catalog_filter_group->getTotalFilterGroups();
	 */
	public function getTotalFilterGroups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "filter_group`");

		return (int)$query->row['total'];
	}

	/**
	 * Add Description
	 *
	 * Create a new filter group description record in the database.
	 *
	 * @param int                  $filter_group_id primary key of the filter group record
	 * @param int                  $language_id     primary key of the language record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $filter_group_data['filter_group_description'] = [
	 *     'name' => 'Filter Group Name'
	 * ];
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $this->model_catalog_filter_group->addDescription($filter_group_id, $language_id, $filter_group_data);
	 */
	public function addDescription(int $filter_group_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group_description` SET `filter_group_id` = '" . (int)$filter_group_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Delete Descriptions
	 *
	 * Delete filter group description records in the database.
	 *
	 * @param int $filter_group_id primary key of the filter group record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $this->model_catalog_filter_group->deleteDescriptions($filter_group_id);
	 */
	public function deleteDescriptions(int $filter_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * Delete filter group descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $this->model_catalog_filter_group->deleteDescriptionsByLanguageId($language_id);
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the filter group description records in the database.
	 *
	 * @param int $filter_group_id primary key of the filter group record
	 *
	 * @return array<int, array<string, string>> description records that have filter group ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $filter_group_description = $this->model_catalog_filter_group->getDescriptions($filter_group_id);
	 */
	public function getDescriptions(int $filter_group_id): array {
		$filter_group_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group_description` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		foreach ($query->rows as $result) {
			$filter_group_data[$result['language_id']] = $result;
		}

		return $filter_group_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the filter group descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, string>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('catalog/filter_group');
	 *
	 * $results = $this->model_catalog_filter_group->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
