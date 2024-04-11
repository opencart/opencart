<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Filter
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Filter extends \Opencart\System\Engine\Model {
	/**
	 * Add Filter
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
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
	 * @param int                  $filter_id
	 * @param array<string, mixed> $data
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
	 * @param int $filter_id
	 *
	 * @return void
	 */
	public function deleteFilter(int $filter_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter` WHERE `filter_id` = '" . (int)$filter_id . "'");

		$this->model_catalog_filter->deleteDescriptions($filter_id);

		$this->load->model('catalog/category');

		$this->model_catalog_category->deleteFiltersByFilterId($filter_id);

		$this->load->model('catalog/product');

		$this->model_catalog_product->deleteFiltersByFilterId($filter_id);

		$this->cache->delete('filter');
	}

	/**
	 * Get Filter
	 *
	 * @param int $filter_id
	 *
	 * @return array<string, mixed>
	 */
	public function getFilter(int $filter_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter` `f` LEFT JOIN `" . DB_PREFIX . "filter_description` `fd` ON (`f`.`filter_id` = `fd`.`filter_id`) WHERE `f`.`filter_id` = '" . (int)$filter_id . "' AND `fd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Filters
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getFilters(array $data = []): array {
		$sql = "SELECT *, (SELECT `fgd`.`name` FROM `" . DB_PREFIX . "filter_group_description` `fgd` WHERE `fgd`.`filter_group_id` = `f`.`filter_group_id` AND `fgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS `filter_group` FROM `" . DB_PREFIX . "filter` `f` LEFT JOIN `" . DB_PREFIX . "filter_description` `fd` ON (`f`.`filter_id` = `fd`.`filter_id`) WHERE `fd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`fd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		$sort_data = [
			'fd.name',
			'filter_group',
			'f.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function getTotalFilters(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "filter` `f` LEFT JOIN `" . DB_PREFIX . "filter_description` `fd` ON (`f`.`filter_id` = `fd`.`filter_id`) WHERE `fd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`fd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name'])) . "'";
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}

	/**
	 * Get Total Filters By Filter Group ID
	 *
	 * @param int $filter_group_id
	 *
	 * @return int
	 */
	public function getTotalFiltersByFilterGroupId(int $filter_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "filter` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		return (int)$query->row['total'];
	}

	/**
	 *	Add Description
	 *
	 * @param int                  $filter_id   primary key of the attribute record to be fetched
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addDescription(int $filter_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_description` SET `filter_id` = '" . (int)$filter_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 *	Delete Descriptions
	 *
	 * @param int $filter_id
	 *
	 * @return void
	 */
	public function deleteDescriptions(int $filter_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE `filter_id` = '" . (int)$filter_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $filter_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $filter_id): array {
		$filter_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_description` WHERE `filter_id` = '" . (int)$filter_id . "'");

		foreach ($query->rows as $result) {
			$filter_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $filter_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
