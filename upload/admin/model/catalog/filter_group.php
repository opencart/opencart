<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class FilterGroup
 *
 * @package Opencart\Admin\Model\Catalog
 */
class FilterGroup extends \Opencart\System\Engine\Model {
	/**
	 * Add Filter Group
	 *
	 * @param array<string, mixed> $data
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
	 * @param int                  $filter_group_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
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
	 * @param int $filter_group_id
	 *
	 * @return void
	 */
	public function deleteFilterGroup(int $filter_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		$this->model_catalog_filter_group->deleteDescriptions($filter_group_id);

		$this->cache->delete('filter_group');
	}

	/**
	 * Get Filter Group
	 *
	 * @param int $filter_group_id
	 *
	 * @return array<string, mixed>
	 */
	public function getFilterGroup(int $filter_group_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group` `fg` LEFT JOIN `" . DB_PREFIX . "filter_group_description` `fgd` ON (`fg`.`filter_group_id` = `fgd`.`filter_group_id`) WHERE `fg`.`filter_group_id` = '" . (int)$filter_group_id . "' AND `fgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Filter Groups
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
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
	 * @return int
	 */
	public function getTotalFilterGroups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "filter_group`");

		return (int)$query->row['total'];
	}

	/**
	 *	Add Description
	 *
	 * @param int                  $filter_group_id primary key of the attribute record to be fetched
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addDescription(int $filter_group_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group_description` SET `filter_group_id` = '" . (int)$filter_group_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 *	Delete Descriptions
	 *
	 * @param int $filter_group_id primary key of the filter record to be fetched
	 *
	 * @return void
	 */
	public function deleteDescriptions(int $filter_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");
	}

	/**
	 * Delete Descriptions By Language ID
	 *
	 * @param int $language_id
	 */
	public function deleteDescriptionsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $filter_group_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $filter_group_id): array {
		$filter_group_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group_description` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		foreach ($query->rows as $result) {
			$filter_group_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $filter_group_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "filter_group_description` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}
}
