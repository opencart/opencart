<?php
class ModelMarketingMarketing extends Model {
	public function addMarketing($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "marketing SET name = '" . $this->db->escape((string)$data['name']) . "', description = '" . $this->db->escape((string)$data['description']) . "', code = '" . $this->db->escape((string)$data['code']) . "', date_added = NOW()");

		return $this->db->getLastId();
	}

	public function editMarketing($marketing_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "marketing SET name = '" . $this->db->escape((string)$data['name']) . "', description = '" . $this->db->escape((string)$data['description']) . "', code = '" . $this->db->escape((string)$data['code']) . "' WHERE marketing_id = '" . (int)$marketing_id . "'");
	}

	public function deleteMarketing($marketing_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "marketing WHERE marketing_id = '" . (int)$marketing_id . "'");
	}

	public function getMarketing($marketing_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "marketing WHERE marketing_id = '" . (int)$marketing_id . "'");

		return $query->row;
	}

	public function getMarketingByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "marketing WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getMarketings($data = array()) {
		$implode = array();

		$order_statuses = $this->config->get('config_complete_status');

		foreach ($order_statuses as $order_status_id) {
			$implode[] = "o.order_status_id = '" . (int)$order_status_id . "'";
		}

		$sql = "SELECT *, (SELECT COUNT(*) FROM `" . DB_PREFIX . "order` o WHERE (" . implode(" OR ", $implode) . ") AND o.marketing_id = m.marketing_id) AS orders FROM " . DB_PREFIX . "marketing m";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "m.name LIKE '" . $this->db->escape((string)$data['filter_name']) . "%'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "m.code = '" . $this->db->escape((string)$data['filter_code']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(m.date_added) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'm.name',
			'm.code',
			'm.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY m.name";
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

	public function getTotalMarketings($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "marketing";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '" . $this->db->escape((string)$data['filter_name']) . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "code = '" . $this->db->escape((string)$data['filter_code']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getReports($marketing_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT ip, store_id, country, date_added FROM " . DB_PREFIX . "marketing_report WHERE marketing_id = '" . (int)$marketing_id . "' ORDER BY date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReports($marketing_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "marketing_report WHERE marketing_id = '" . (int)$marketing_id . "'");

		return $query->row['total'];
	}
}