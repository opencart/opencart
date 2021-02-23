<?php
namespace Opencart\Admin\Model\Tool;
class Notification extends \Opencart\System\Engine\Model {
	public function addNotification($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "notification` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `message` = '" . $this->db->escape((string)$data['message']) . "', `status` = '" . (int)$data['status'] . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	public function editStatus($notification_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "notification` SET `status` = '" . (int)$status . "' WHERE `notification_id` = '" . (int)$notification_id . "'");
	}

	public function deleteNotification($notification_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "notification` WHERE `notification_id` = '" . (int)$notification_id . "'");
	}

	public function getNotification($notification_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "notification` WHERE `notification_id` = '" . (int)$notification_id . "'");

		return $query->row;
	}

	public function getNotifications($data = []) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "notification`";

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " WHERE `status` = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " ORDER BY `date_added` DESC";

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

	public function getTotalNotifications($data = []) {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "notification`";

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$sql .= " WHERE `status` = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
