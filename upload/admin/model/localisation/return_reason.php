<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class ReturnReason
 *
 * @package Opencart\Admin\Model\Localisation
 */
class ReturnReason extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addReturnReason(array $data): int {
		foreach ($data['return_reason'] as $language_id => $value) {
			if (isset($return_reason_id)) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "return_reason` SET `return_reason_id` = '" . (int)$return_reason_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "return_reason` SET `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");

				$return_reason_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('return_reason');
		
		return $return_reason_id;
	}

	/**
	 * @param int   $return_reason_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editReturnReason(int $return_reason_id, array $data): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_reason` WHERE `return_reason_id` = '" . (int)$return_reason_id . "'");

		foreach ($data['return_reason'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "return_reason` SET `return_reason_id` = '" . (int)$return_reason_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('return_reason');
	}

	/**
	 * @param int $return_reason_id
	 *
	 * @return void
	 */
	public function deleteReturnReason(int $return_reason_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "return_reason` WHERE `return_reason_id` = '" . (int)$return_reason_id . "'");

		$this->cache->delete('return_reason');
	}

	/**
	 * @param int $return_reason_id
	 *
	 * @return array
	 */
	public function getReturnReason(int $return_reason_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `return_reason_id` = '" . (int)$return_reason_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getReturnReasons(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

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

		$return_reason_data = $this->cache->get('return_reason.' . md5($sql));

		if (!$return_reason_data) {
			$query = $this->db->query($sql);

			$return_reason_data = $query->rows;

			$this->cache->set('return_reason.' . md5($sql), $return_reason_data);
		}

		return $return_reason_data;
	}

	/**
	 * @param int $return_reason_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $return_reason_id): array {
		$return_reason_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_reason` WHERE `return_reason_id` = '" . (int)$return_reason_id . "'");

		foreach ($query->rows as $result) {
			$return_reason_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $return_reason_data;
	}

	/**
	 * @return int
	 */
	public function getTotalReturnReasons(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "return_reason` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return (int)$query->row['total'];
	}
}
