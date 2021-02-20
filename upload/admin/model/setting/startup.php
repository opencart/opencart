<?php
namespace Opencart\Admin\Model\Setting;
class Startup extends \Opencart\System\Engine\Model {
	public function addStartup($code, $action, $status = 1, $sort_order = 0) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "startup` SET `code` = '" . $this->db->escape($code) . "', `action` = '" . $this->db->escape($action) . "', `status` = '" . (int)$status . "', `sort_order` = '" . (int)$sort_order . "'");

		return $this->db->getLastId();
	}

	public function deleteStartup($startup_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "startup` WHERE `startup_id` = '" . (int)$startup_id . "'");
	}

	public function deleteStartupByCode($code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "startup` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	public function editStatus($startup_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "startup` SET `status` = '" . (int)$status . "' WHERE `startup_id` = '" . (int)$startup_id . "'");
	}

	public function getStartup($startup_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "startup` WHERE `startup_id` = '" . (int)$startup_id . "'");

		return $query->row;
	}

	public function getStartupByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "startup` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	public function getStartups($data = []) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "startup`";

		$sort_data = [
			'code',
			'action',
			'status',
			'sort_order',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
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

	public function getTotalStartups() {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "startup`");

		return $query->row['total'];
	}
}
