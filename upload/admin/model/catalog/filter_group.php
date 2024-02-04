<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class FilterGroup
 *
 * @package Opencart\Admin\Model\Catalog
 */
class FilterGroup extends \Opencart\System\Engine\Model {
	public function addFilterGroup($data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "filter_group` SET `sort_order` = '" . (int)$data['sort_order'] . "'");

		$filter_group_id = $this->db->getLastId();

		foreach ($data['filter_group_description'] as $language_id => $filter_group_description) {
			$this->addDescription($filter_group_id, $language_id, $filter_group_description);
		}

		$this->cache->delete('filter_group');

		return $filter_group_id;
	}

	public function editFilterGroup($filter_group_id, $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "filter_group` SET `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		$this->deleteDescription($filter_group_id);

		foreach ($data['filter_group_description'] as $language_id => $filter_group_description) {
			$this->addDescription($filter_group_id, $language_id, $filter_group_description);
		}

		$this->cache->delete('filter_group');
	}

	public function deleteFilterGroup(int $filter_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");

		$this->deleteDescription($filter_group_id);

		$this->cache->delete('filter_group');
	}

	/**
	 * Get Group
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
	 * Get Groups
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getFilterGroups(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "filter_group` `fg` LEFT JOIN `" . DB_PREFIX . "filter_group_description` `fgd` ON (`fg`.`filter_group_id` = `fgd`.`filter_group_id`) WHERE `fgd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

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
	 *	Delete Description
	 *
	 * @param int $filter_group_id primary key of the filter record to be fetched
	 *
	 * @return void
	 */
	public function deleteDescription(int $filter_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "filter_group_description` WHERE `filter_group_id` = '" . (int)$filter_group_id . "'");
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
	 * Get Total Groups
	 *
	 * @return int
	 */
	public function getTotalFilterGroups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "filter_group`");

		return (int)$query->row['total'];
	}
}
