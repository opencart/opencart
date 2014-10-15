<?php
class ModelOpenbayOrder extends Model {
	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order` AS o";

		if ($this->config->get('ebay_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "ebay_order eo ON o.order_id = eo.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) eo ";
		}

		if ($this->config->get('amazon_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "amazon_order ao ON o.order_id = ao.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) ao ";
		}

		if ($this->config->get('amazonus_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "amazonus_order auso ON o.order_id = auso.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) auso ";
		}

		if ($this->config->get('etsy_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "etsy_order eto ON o.order_id = eto.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) eto ";
		}

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE `order_status_id` = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE `order_status_id` > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.`order_id` = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND CONCAT(`firstname`, ' ', `lastname`) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(`date_added`) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_channel'])) {
			$sql .= " AND IF(eto.order_id IS NULL, IF(ao.order_id IS NULL, IF(auso.order_id IS NULL, IF(eo.order_id IS NULL, 'web', 'ebay'), 'amazonus'), 'amazon'), 'etsy') = '" . $this->db->escape($data['filter_channel']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOrders($data = array()) {
		$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.currency_code, o.currency_value, o.date_added, IF(ao.order_id IS NULL, IF(auso.order_id IS NULL, IF(eo.order_id IS NULL, 'web', 'ebay'), 'amazonus'), 'amazon') AS channel FROM `" . DB_PREFIX . "order` o";

		if ($this->config->get('ebay_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "ebay_order eo ON eo.order_id = o.order_id";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) eo ";
		}

		if ($this->config->get('amazon_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "amazon_order ao ON ao.order_id = o.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) ao ";
		}

		if ($this->config->get('amazonus_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "amazonus_order auso ON auso.order_id = o.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) auso ";
		}

		if ($this->config->get('amazonus_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "etsy_order eto ON eto.order_id = o.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) eto ";
		}

		if (isset($data['filter_order_status_id']) && !is_null($data['filter_order_status_id'])) {
			$sql .= " WHERE o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE o.order_status_id > '0'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND o.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_customer'])) {
			$sql .= " AND LCASE(CONCAT(o.firstname, ' ', o.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_customer'])) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if (!empty($data['filter_channel'])) {
			$sql .= " HAVING channel = '" . $this->db->escape($data['filter_channel']) . "'";
		}

		$sort_data = array(
			'o.order_id',
			'customer',
			'status',
			'o.date_added',
			'channel',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY o.order_id";
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

	public function getOrder($order_id) {
		$sql = "SELECT o.order_id, o.order_status_id, o.shipping_method, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status, o.currency_code, o.currency_value, o.date_added, IF(eto.order_id IS NULL, IF(ao.order_id IS NULL, IF(auso.order_id IS NULL, IF(eo.order_id IS NULL, 'web', 'ebay'), 'amazonus'), 'amazon'), 'etsy') AS channel FROM `" . DB_PREFIX . "order` o";

		if ($this->config->get('ebay_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "ebay_order eo ON eo.order_id = o.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) eo ";
		}

		if ($this->config->get('amazon_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "amazon_order ao ON ao.order_id = o.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) ao ";
		}

		if ($this->config->get('amazonus_status')) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "amazonus_order auso ON auso.order_id = o.order_id ";
		} else {
			$sql .= " JOIN (SELECT NULL AS order_id) auso ";
		}

		$sql .= " WHERE `o`.`order_id` = '" . (int)$order_id . "'";

		$sql = $this->db->query($sql);

		return $sql->row;
	}
}