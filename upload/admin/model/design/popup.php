<?php
namespace Opencart\admin\model\design;
/**
 *  Class Popup
 *
 * @package Opencart\Admin\Model\Design
 */
class Popup extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addPopup(array $data): int {
		$this->db->query(
			"INSERT INTO `" . DB_PREFIX . "popup` SET " .
			"`title` = '" . $this->db->escape((string)$data['title']) . "', " .
			"`status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "'," .
			"`initial_delay` = '" . (int)(isset($data['initial_delay']) ? $data['initial_delay'] : 0) . "'," .
			"`time_to_close` = '" .  (int)(isset($data['time_to_close']) ? $data['time_to_close'] : 0) . "'," .
			"`width` = '" . (int)(isset($data['width']) ? $data['width'] : 0) . "'," .
			"`show_everytime` = '" . (bool)(isset($data['show_everytime']) ? $data['show_everytime'] : 0) . "'," .
			"`store_id` = '" . (int)(isset($data['store_id']) ? $data['store_id'] : 0) . "'"
		);

		$popup_id = $this->db->getLastId();

		if (isset($data['popup_content'])) {
			foreach ($data['popup_content'] as $language_id => $popup_content) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "popup_content` SET `popup_id` = '" . (int)$popup_id . "', `language_id` = '" . (int)$language_id . "', `header` = '" .  $this->db->escape($popup_content['header']) . "', `content` = '" .  $this->db->escape($popup_content['content']) . "'");
			}
		}

		return $popup_id;
	}

	/**
	 * @param int   $popup_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editPopup(int $popup_id, array $data): void {
		$this->db->query(
			"UPDATE `" . DB_PREFIX . "popup` SET " .
			"`title` = '" . $this->db->escape((string)$data['title']) . "', " .
			"`status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "'," .
			"`initial_delay` = '" . (int)(isset($data['initial_delay']) ? $data['initial_delay'] : 0) . "'," .
			"`time_to_close` = '" . max(1, (int)(isset($data['time_to_close']) ? $data['time_to_close'] : 0)) . "'," .
			"`width` = '" . (int)(isset($data['width']) ? $data['width'] : 0) . "'," .
			"`show_everytime` = '" . (bool)(isset($data['show_everytime']) ? $data['show_everytime'] : 0) . "'," .
			"`store_id` = '" . (int)(isset($data['store_id']) ? $data['store_id'] : 0) . "' ".
			"where popup_id = '" . (int)$popup_id . "'"
		);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "popup_content` WHERE `popup_id` = '" . (int)$popup_id . "'");

		if (isset($data['popup_content'])) {
			foreach ($data['popup_content'] as $language_id => $popup_content) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "popup_content` SET `popup_id` = '" . (int)$popup_id . "', `language_id` = '" . (int)$language_id . "', `header` = '" .  $this->db->escape($popup_content['header']) . "', `content` = '" .  $this->db->escape($popup_content['content']) . "'");
			}
		}
	}

	/**
	 * @param int $popup_id
	 *
	 * @return void
	 */
	public function deletePopup(int $popup_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "popup` WHERE `popup_id` = '" . (int)$popup_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "popup_content` WHERE `popup_id` = '" . (int)$popup_id . "'");
	}

	/**
	 * @param int $popup_id
	 *
	 * @return array
	 */
	public function getPopup(int $popup_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "popup` WHERE `popup_id` = '" . (int)$popup_id . "'");

		return $query->row;
	}

	/**
	 * @param int $popup_id
	 * @return array
	 */
	public function getContents(int $popup_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "popup_content` WHERE `popup_id` = '" . (int)$popup_id . "'");

		$result = [];
		foreach ($query->rows as $row){
			$result[$row['language_id']] = $row;
		}

		return $result;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getPopups(array $data = []): array {
		$sql = "SELECT p.*, s.name as store_name  FROM `" . DB_PREFIX . "popup` p left join `" . DB_PREFIX . "store` s on p.store_id = s.store_id";

		$sort_data = [
			'store_id',
			'popup_id'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY p.`" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY p.`store_id`, p.`popup_id`";
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
	 * @return int
	 */
	public function getTotalPopups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "popup`");

		return (int)$query->row['total'];
	}
}
