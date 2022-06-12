<?php
namespace Opencart\Admin\Model\Catalog;
class Attribute extends \Opencart\System\Engine\Model {
	public function addAttribute(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute` SET `attribute_group_id` = '" . (int)$data['attribute_group_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$attribute_id = $this->db->getLastId();

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_description` SET `attribute_id` = '" . (int)$attribute_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		return $attribute_id;
	}

	public function editAttribute(int $attribute_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "attribute` SET `attribute_group_id` = '" . (int)$data['attribute_group_id'] . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `attribute_id` = '" . (int)$attribute_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_description` WHERE `attribute_id` = '" . (int)$attribute_id . "'");

		foreach ($data['attribute_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_description` SET `attribute_id` = '" . (int)$attribute_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}
	}

	public function deleteAttribute(int $attribute_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute` WHERE `attribute_id` = '" . (int)$attribute_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "attribute_description` WHERE `attribute_id` = '" . (int)$attribute_id . "'");
	}

	public function getAttribute(int $attribute_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute` a LEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON (a.`attribute_id` = ad.`attribute_id`) WHERE a.`attribute_id` = '" . (int)$attribute_id . "' AND ad.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getAttributes(array $data = []): array {
		$sql = "SELECT *, (SELECT agd.`name` FROM `" . DB_PREFIX . "attribute_group_description` agd WHERE agd.`attribute_group_id` = a.`attribute_group_id` AND agd.`language_id` = '" . (int)$this->config->get('config_language_id') . "') AS attribute_group FROM `" . DB_PREFIX . "attribute` a LEFT JOIN `" . DB_PREFIX . "attribute_description` ad ON (a.`attribute_id` = ad.`attribute_id`) WHERE ad.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_attribute_group_id'])) {
			$sql .= " AND a.`attribute_group_id` = '" . (int)$data['filter_attribute_group_id'] . "'";
		}

		$sort_data = [
			'ad.name',
			'attribute_group',
			'a.sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `attribute_group`, ad.`name`";
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

	public function getDescriptions(int $attribute_id): array {
		$attribute_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_description` WHERE `attribute_id` = '" . (int)$attribute_id . "'");

		foreach ($query->rows as $result) {
			$attribute_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $attribute_data;
	}

	public function getTotalAttributes(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "attribute`");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}

	public function getTotalAttributesByAttributeGroupId(int $attribute_group_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "attribute` WHERE `attribute_group_id` = '" . (int)$attribute_group_id . "'");

		return (int)$query->row['total'];
	}
}
