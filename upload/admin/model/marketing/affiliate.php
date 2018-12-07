<?php
class ModelMarketingAffiliate extends Model {
	public function addAffiliate($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_affiliate SET customer_id = '" . (int)$data['customer_id'] . "', company = '" . $this->db->escape((string)$data['company']) . "', website = '" . $this->db->escape((string)$data['website']) . "', tracking = '" . $this->db->escape((string)$data['tracking']) . "', commission = '" . (float)$data['commission'] . "', tax = '" . $this->db->escape((string)$data['tax']) . "', payment = '" . $this->db->escape((string)$data['payment']) . "', cheque = '" . $this->db->escape((string)$data['cheque']) . "', paypal = '" . $this->db->escape((string)$data['paypal']) . "', bank_name = '" . $this->db->escape((string)$data['bank_name']) . "', bank_branch_number = '" . $this->db->escape((string)$data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape((string)$data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape((string)$data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape((string)$data['bank_account_number']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : json_encode(array())) . "', status = '" . (int)$data['status'] . "', date_added = NOW()");
	}

	public function editAffiliate($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer_affiliate SET company = '" . $this->db->escape((string)$data['company']) . "', website = '" . $this->db->escape((string)$data['website']) . "', tracking = '" . $this->db->escape((string)$data['tracking']) . "', commission = '" . (float)$data['commission'] . "', tax = '" . $this->db->escape((string)$data['tax']) . "', payment = '" . $this->db->escape((string)$data['payment']) . "', cheque = '" . $this->db->escape((string)$data['cheque']) . "', paypal = '" . $this->db->escape((string)$data['paypal']) . "', bank_name = '" . $this->db->escape((string)$data['bank_name']) . "', bank_branch_number = '" . $this->db->escape((string)$data['bank_branch_number']) . "', bank_swift_code = '" . $this->db->escape((string)$data['bank_swift_code']) . "', bank_account_name = '" . $this->db->escape((string)$data['bank_account_name']) . "', bank_account_number = '" . $this->db->escape((string)$data['bank_account_number']) . "', status = '" . (int)$data['status'] . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function deleteAffiliate($customer_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_affiliate WHERE customer_id = '" . (int)$customer_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_affiliate_report WHERE customer_id = '" . (int)$customer_id . "'");
	}

	public function getAffiliate($customer_id) {
		$query = $this->db->query("SELECT DISTINCT *, CONCAT(c.firstname, ' ', c.lastname) AS customer, ca.custom_field FROM " . DB_PREFIX . "customer_affiliate ca LEFT JOIN `" . DB_PREFIX . "customer` c ON (ca.customer_id = c.customer_id) WHERE ca.customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getAffliateByTracking($tracking) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_affiliate WHERE tracking = '" . $this->db->escape($tracking) . "'");

		return $query->row;
	}

	public function getAffiliates($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, ca.status FROM " . DB_PREFIX . "customer_affiliate ca LEFT JOIN " . DB_PREFIX . "customer c ON (ca.customer_id = c.customer_id)";

		$implode = array();

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_tracking'])) {
			$implode[] = "ca.tracking = '" . $this->db->escape((string)$data['filter_tracking']) . "'";
		}

		if (!empty($data['filter_commission'])) {
			$implode[] = "ca.commission = '" . (float)$data['filter_commission'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "ca.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = " AND DATE(ca.date_added) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'ca.tracking',
			'ca.commission',
			'ca.status',
			'ca.date_added'
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

	public function getTotalAffiliates($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_affiliate ca LEFT JOIN " . DB_PREFIX . "customer c ON (ca.customer_id = c.customer_id)";

		$implode = array();

		if (!empty($data['filter_customer'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape((string)$data['filter_customer']) . "%'";
		}

		if (!empty($data['filter_tracking'])) {
			$implode[] = "ca.tracking = '" . $this->db->escape((string)$data['filter_tracking']) . "'";
		}

		if (!empty($data['filter_commission'])) {
			$implode[] = "ca.commission = '" . (float)$data['filter_commission'] . "'";
		}

		if (isset($data['filter_status']) && $data['filter_status'] !== '') {
			$implode[] = "ca.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(ca.date_added) = DATE('" . $this->db->escape((string)$data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getReports($customer_id, $start = 0, $limit = 10) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT ip, store_id, country, date_added FROM " . DB_PREFIX . "customer_affiliate_report WHERE customer_id = '" . (int)$customer_id . "' ORDER BY date_added ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalReports($customer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_affiliate_report WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}
}