<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class WeightClass
 *
 * @package Opencart\Admin\Model\Localisation
 */
class WeightClass extends \Opencart\System\Engine\Model {
	/**
	 * Add Weight Class
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addWeightClass(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "weight_class` SET `value` = '" . (float)$data['value'] . "'");

		$weight_class_id = $this->db->getLastId();

		foreach ($data['weight_class_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "weight_class_description` SET `weight_class_id` = '" . (int)$weight_class_id . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($value['title']) . "', `unit` = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->cache->delete('weight_class');

		return $weight_class_id;
	}

	/**
	 * Edit Weight Class
	 *
	 * @param int                  $weight_class_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editWeightClass(int $weight_class_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "weight_class` SET `value` = '" . (float)$data['value'] . "' WHERE `weight_class_id` = '" . (int)$weight_class_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "weight_class_description` WHERE `weight_class_id` = '" . (int)$weight_class_id . "'");

		foreach ($data['weight_class_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "weight_class_description` SET `weight_class_id` = '" . (int)$weight_class_id . "', `language_id` = '" . (int)$language_id . "', `title` = '" . $this->db->escape($value['title']) . "', `unit` = '" . $this->db->escape($value['unit']) . "'");
		}

		$this->cache->delete('weight_class');
	}

	/**
	 * Delete Weight Class
	 *
	 * @param int $weight_class_id
	 *
	 * @return void
	 */
	public function deleteWeightClass(int $weight_class_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "weight_class` WHERE `weight_class_id` = '" . (int)$weight_class_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "weight_class_description` WHERE `weight_class_id` = '" . (int)$weight_class_id . "'");

		$this->cache->delete('weight_class');
	}

	/**
	 * Get Weight Classes
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getWeightClasses(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "weight_class` `wc` LEFT JOIN `" . DB_PREFIX . "weight_class_description` `wcd` ON (`wc`.`weight_class_id` = `wcd`.`weight_class_id`) WHERE `wcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

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

		$key = md5($sql);

		$weight_class_data = $this->cache->get('weight_class.' . $key);

		if (!$weight_class_data) {
			$query = $this->db->query($sql);

			$weight_class_data = $query->rows;

			$this->cache->set('weight_class.' . $key, $weight_class_data);
		}

		return $weight_class_data;
	}

	/**
	 * Get Weight Class
	 *
	 * @param int $weight_class_id
	 *
	 * @return array<string, mixed>
	 */
	public function getWeightClass(int $weight_class_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weight_class` `wc` LEFT JOIN `" . DB_PREFIX . "weight_class_description` `wcd` ON (`wc`.`weight_class_id` = `wcd`.`weight_class_id`) WHERE `wc`.`weight_class_id` = '" . (int)$weight_class_id . "' AND `wcd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Description By Unit
	 *
	 * @param string $unit
	 *
	 * @return array<string, mixed>
	 */
	public function getDescriptionByUnit(string $unit): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weight_class_description` WHERE `unit` = '" . $this->db->escape($unit) . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $weight_class_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getDescriptions(int $weight_class_id): array {
		$weight_class_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "weight_class_description` WHERE `weight_class_id` = '" . (int)$weight_class_id . "'");

		foreach ($query->rows as $result) {
			$weight_class_data[$result['language_id']] = [
				'title' => $result['title'],
				'unit'  => $result['unit']
			];
		}

		return $weight_class_data;
	}

	/**
	 * Get Total Weight Classes
	 *
	 * @return int
	 */
	public function getTotalWeightClasses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "weight_class`");

		return (int)$query->row['total'];
	}
}
