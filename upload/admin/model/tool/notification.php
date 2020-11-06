<?php
namespace Opencart\Application\Model\Tool;
class Notification extends \Opencart\System\Engine\Model {
	public function addNotification($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "notification` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `message` = '" . $this->db->escape((string)$data['message']) . "', `status` = '" . (int)$data['status'] . "', `date_added` = NOW()");

		$this->cache->delete('notification');

		return $this->db->getLastId();
	}

	public function deleteNotification($notification_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "notification` WHERE `notification_id` = '" . (int)$notification_id . "'");

		$this->cache->delete('notification');
	}

	public function getNotification($notification_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "notification` WHERE `notification_id` = '" . (int)$notification_id . "'");

		return $query->row;
	}

	public function getNotifications($data = []) {
		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "notification`";

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
		} else {
			$country_data = $this->cache->get('notification');

			if (!$country_data) {
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "notification` ORDER BY `date_added` DESC");

				$country_data = $query->rows;

				$this->cache->set('notification', $country_data);
			}

			return $country_data;
		}
	}

	public function getTotalNotifications() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notification`");

		return $query->row['total'];
	}
}
