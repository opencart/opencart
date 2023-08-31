<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Event
 *
 * @package Opencart\Admin\Model\Setting
 */
class Event extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addEvent(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = '" . $this->db->escape($data['code']) . "', `description` = '" . $this->db->escape($data['description']) . "', `trigger` = '" . $this->db->escape($data['trigger']) . "', `action` = '" . $this->db->escape($data['action']) . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		return $this->db->getLastId();
	}

	/**
	 * @param int $event_id
	 *
	 * @return void
	 */
	public function deleteEvent(int $event_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . (int)$event_id . "'");
	}

	/**
	 * @param string $code
	 *
	 * @return void
	 */
	public function deleteEventByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * @param int  $event_id
	 * @param bool $status
	 *
	 * @return void
	 */
	public function editStatus(int $event_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `status` = '" . (bool)$status . "' WHERE `event_id` = '" . (int)$event_id . "'");
	}

	/**
	 * @param int $event_id
	 *
	 * @return array
	 */
	public function getEvent(int $event_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "event` WHERE `event_id` = '" . (int)$event_id . "'");

		return $query->row;
	}

	/**
	 * @param string $code
	 *
	 * @return array
	 */
	public function getEventByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "event` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getEvents(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "event`";

		$sort_data = [
			'code',
			'trigger',
			'action',
			'sort_order',
			'status',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `sort_order`";
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
	public function getTotalEvents(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "event`");

		return (int)$query->row['total'];
	}
}
