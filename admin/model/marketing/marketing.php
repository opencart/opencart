<?php
class ModelMarketingMarketing extends Model {
	public function addMarketing($data) {
		$this->event->trigger('pre.admin.marketing.add', $data);

		$this->db->query("INSERT INTO " . DB_PREFIX . "marketing SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', code = '" . $this->db->escape($data['code']) . "', date_added = NOW()");

		$marketing_id = $this->db->getLastId();

		$this->event->trigger('post.admin.marketing.add', $marketing_id);

		return $marketing_id;
	}

	public function editMarketing($marketing_id, $data) {
		$this->event->trigger('pre.admin.marketing.edit', $data);

		$this->db->query("UPDATE " . DB_PREFIX . "marketing SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "', code = '" . $this->db->escape($data['code']) . "' WHERE marketing_id = '" . (int)$marketing_id . "'");

		$this->event->trigger('post.admin.marketing.edit', $marketing_id);
	}

	public function deleteMarketing($marketing_id) {
		$this->event->trigger('pre.admin.marketing.delete', $marketing_id);

		$this->db->query("DELETE FROM " . DB_PREFIX . "marketing WHERE marketing_id = '" . (int)$marketing_id . "'");

		$this->event->trigger('post.admin.marketing.delete', $marketing_id);
	}

	public function getMarketing($marketing_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "marketing WHERE marketing_id = '" . (int)$marketing_id . "'");

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
			$implode[] = "m.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "m.code = '" . $this->db->escape($data['filter_code']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(m.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
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
			$sql .= " ORDER BY name";
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
			$implode[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "code = '" . $this->db->escape($data['filter_code']) . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
