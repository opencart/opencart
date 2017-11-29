<?php
class ModelSettingCron extends Model {
	public function addCron($code, $cycle = 'day', $action, $status) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "cron` SET `code` = '" . $this->db->escape($code) . "', `cycle` = '" . $this->db->escape($cycle) . "', `action` = '" . $this->db->escape($action) . "', `status` = '" . (int)$status . "', `date_added` = NOW(), `date_modified` = NOW()");

		return $this->db->getLastId();
	}

	public function deleteCron($cron_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");
	}
	
	public function deleteCronByCode($code) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	public function editCron($cron_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `date_modified` = NOW() WHERE cron_id = '" . (int)$cron_id . "'");
	}

	public function editStatus($cron_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "cron` SET `status` = '" . (int)$status . "' WHERE cron_id = '" . (int)$cron_id . "'");
	}

	public function getCron($cron_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `cron_id` = '" . (int)$cron_id . "'");

		return $query->row;
	}

	public function getCronByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "cron` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}
		
	public function getCrons($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "cron`";

		$sort_data = array(
			'code',
			'cycle',
			'action',
			'status',
			'date_added',
			'date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `date_added`";
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

	public function getTotalCrons() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "cron`");

		return $query->row['total'];
	}
}