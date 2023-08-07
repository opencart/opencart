<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Attribute Group
 *
 * @package Opencart\Admin\Model\Catalog
 */
class AttributeGroup extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addAttributeGroup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group` SET `sort_order` = '" . (int)$data['sort_order'] . "'");

		$attribute_group_id = $this->db->getLastId();

		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group_description` SET `attribute_group_id` = '" . (int)$attribute_group_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		return $attribute_group_id;
	}

	/**
	 * @param int   $attribute_group_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editAttributeGroup(int $attribute_group_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "attribute_group` SET `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group_description` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		foreach ($data['attribute_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group_description` SET `attribute_group_id` = '" . (int)$attribute_group_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}
	}

	/**
	 * @param int $attribute_group_id
	 *
	 * @return void
	 */
	public function deleteAttributeGroup(int $attribute_group_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_group_description` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");
	}

	/**
	 * @param int $attribute_group_id
	 *
	 * @return array
	 */
	public function getAttributeGroup(int $attribute_group_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_group` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getAttributeGroups(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "attribute_group` ag LEFT JOIN `" . DB_PREFIX . "attribute_group_description` agd ON (ag.`attribute_group_id` = agd.`attribute_group_id`) WHERE agd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = ['agd.name', 'ag.sort_order'];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY agd.`name`";
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
	 * @param int $attribute_group_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $attribute_group_id): array {
		$attribute_group_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_group_description` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		foreach ($query->rows as $result) {
			$attribute_group_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $attribute_group_data;
	}

	/**
	 * @return int
	 */
	public function getTotalAttributeGroups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "attribute_group`");

		return (int)$query->row['total'];
	}
}
