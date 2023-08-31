<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Length Class
 *
 * @package Opencart\Admin\Model\Localisation
 */
class LengthClass extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addLengthClass(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class` SET `value` = '" . (float)$data['value'] . "'");

		$length_class_id = $this->db->getLastId();

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class_description` SET `length_class_id` = '" . (int)$length_class_id . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($value['title']) . "', `unit` = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->cache->delete('length_class');

		return $length_class_id;
	}

	/**
	 * @param int   $length_class_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editLengthClass(int $length_class_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "length_class` SET `value` = '" . (float)$data['value'] . "' WHERE `length_class_id` = '" . (int)$length_class_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "length_class_description` WHERE `length_class_id` = '" . (int)$length_class_id . "'");

		foreach ($data['length_class_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class_description` SET `length_class_id` = '" . (int)$length_class_id . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($value['title']) . "', `unit` = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->cache->delete('length_class');
	}

	/**
	 * @param int $length_class_id
	 *
	 * @return void
	 */
	public function deleteLengthClass(int $length_class_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "length_class` WHERE `length_class_id` = '" . (int)$length_class_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "length_class_description` WHERE `length_class_id` = '" . (int)$length_class_id . "'");

		$this->cache->delete('length_class');
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getLengthClasses(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "length_class` lc LEFT JOIN `" . DB_PREFIX . "length_class_description` lcd ON (lc.`length_class_id` = lcd.`length_class_id`) WHERE lcd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sort_data = [
			'title',
			'unit',
			'value'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `title`";
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

		$length_class_data = $this->cache->get('length_class.' . md5($sql));

		if (!$length_class_data) {
			$query = $this->db->query($sql);

			$length_class_data = $query->rows;

			$this->cache->set('length_class.' . md5($sql), $length_class_data);
		}

		return $length_class_data;
	}

	/**
	 * @param int $length_class_id
	 *
	 * @return array
	 */
	public function getLengthClass(int $length_class_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class` lc LEFT JOIN `" . DB_PREFIX . "length_class_description` lcd ON (lc.`length_class_id` = lcd.`length_class_id`) WHERE lc.`length_class_id` = '" . (int)$length_class_id . "' AND lcd.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param string $unit
	 *
	 * @return array
	 */
	public function getDescriptionByUnit(string $unit): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class_description` WHERE `unit` = '" . $this->db->escape($unit) . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param int $length_class_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $length_class_id): array {
		$length_class_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "length_class_description` WHERE `length_class_id` = '" . (int)$length_class_id . "'");

		foreach ($query->rows as $result) {
			$length_class_data[$result['language_id']] = [
				'title' => $result['title'],
				'unit'  => $result['unit']
			];
		}

		return $length_class_data;
	}

	/**
	 * @return int
	 */
	public function getTotalLengthClasses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "length_class`");

		return (int)$query->row['total'];
	}
}
