<?php
class ModelExtensionReportMarketing extends Model {
	public function getMarketing($data = array()) {
		$sql = "SELECT m.marketing_id, m.name AS campaign, m.code, m.date_added from `".DB_PREFIX."marketing` m ";

		$implode = array();

		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(m.date_added) >= '" . $this->db->escape((string)$data['filter_date_start']) . "' ";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(m.date_added) <= '" . $this->db->escape((string)$data['filter_date_end']) . "' ";
		}

		if (!empty($implode)){
			$sql .= "WHERE ".implode( "and", $implode );
		}

		$sql.="ORDER BY m.date_added DESC ";

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

	public function getTotalMarketing($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "marketing`");

		return $query->row['total'];
	}

	public function getmarketingClicks($data = array()){
		$sql = "SELECT m.marketing_id, m.name AS campaign, m.code, COUNT(DISTINCT mr.marketing_report_id) AS `clicks`, MIN(mr.date_added) AS `date_start`, MAX(mr.date_added) AS `date_end` FROM `".DB_PREFIX."marketing_report` mr
			LEFT JOIN `".DB_PREFIX."marketing` m ON (mr.marketing_id = m.marketing_id)
			LEFT JOIN `".DB_PREFIX."country` c ON (mr.country = c.iso_code_2) ";

		$implode = array();
		if (!empty($data['filter_marketing_id']) ){
			$implode[] = " m.marketing_id = '" . (int)$data['filter_marketing_id'] . "' ";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = " DATE(mr.date_added) >= '" . $this->db->escape((string)$data['filter_date_start']) . "' ";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = " DATE(mr.date_added) <= '" . $this->db->escape((string)$data['filter_date_end']) . "' ";
		}

		if (!empty($data['filter_country_id'])) {
			$implode[] = " c.country_id = '" . (int)$data['filter_country_id'] . "' ";
		}

		if (!empty($implode)){
			$sql .= " WHERE ".implode( " AND ", $implode );
		}

		$sql .= " GROUP BY m.marketing_id ";

		if (!empty($data['filter_group'])) {
			switch($data['filter_group']) {
				case 'day';
					$sql .= " , YEAR(mr.date_added), MONTH(mr.date_added), DAY(mr.date_added) ";
					break;
				case 'week':
					$sql .= " , YEAR(mr.date_added), WEEK(mr.date_added) ";
					break;
				case 'month':
					$sql .= " , YEAR(mr.date_added), MONTH(mr.date_added) ";
					break;
				case 'year':
					$sql .= " , YEAR(mr.date_added) ";
					break;
			}
		}

		$sql .= " order by `date_start` DESC ";

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
	public function getTotalmarketingClicks($data = array()){
		switch($data['filter_group']) {
			case 'day';
				$sql = " SELECT COUNT(DISTINCT YEAR(mr.date_added), MONTH(mr.date_added), DAY(mr.date_added ), m.marketing_id ) ";
				break;
			case 'week':
				$sql = " SELECT COUNT(DISTINCT YEAR(mr.date_added), WEEK(mr.date_added), m.marketing_id ) ";
				break;
			case 'month':
				$sql = " SELECT COUNT(DISTINCT YEAR(mr.date_added), MONTH(mr.date_added), m.marketing_id ) ";
				break;
			case 'year':
				$sql = " SELECT COUNT(DISTINCT YEAR(mr.date_added), m.marketing_id ) ";
				break;
			default:
				$sql = " SELECT COUNT(DISTINCT m.marketing_id) ";
		}

		$sql .= " AS total FROM `" . DB_PREFIX . "marketing_report` mr
			LEFT JOIN `".DB_PREFIX."marketing` m ON (mr.marketing_id = m.marketing_id)
			LEFT JOIN `".DB_PREFIX."country` c ON (mr.country = c.iso_code_2) ";

		$implode = array();

		if (!empty($data['filter_marketing_id']) ){
			$implode[] = " m.marketing_id = '" . (int)$data['filter_marketing_id'] . "' ";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = " DATE(mr.date_added) >= '" . $this->db->escape((string)$data['filter_date_start']) . "' ";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = " DATE(mr.date_added) <= '" . $this->db->escape((string)$data['filter_date_end']) . "' ";
		}

		if (!empty($data['filter_country_id'])) {
			$implode[] = " c.country_id = '" . (int)$data['filter_country_id'] . "' ";
		}

		if (!empty($implode)){
			$sql .= " WHERE ".implode( " AND ", $implode );
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getmarketingSales($data = array()){
		$sql = "SELECT m.marketing_id, m.name AS campaign, m.code, COUNT(DISTINCT o.order_id)  AS `orders`, SUM(o.total) AS `total`, MIN(o.date_added) AS `date_start`, MAX(o.date_added) AS `date_end` FROM `".DB_PREFIX."order` o
			LEFT JOIN `".DB_PREFIX."marketing` m ON (o.marketing_id = m.marketing_id)
			LEFT JOIN `".DB_PREFIX."country` pc ON (o.payment_country_id = pc.country_id)
			LEFT JOIN `".DB_PREFIX."country` sc ON (o.shipping_country_id = sc.country_id) ";

		$implode = array();

		if (!empty($data['filter_marketing_id']) ){
			$implode[] = " m.marketing_id = '" . (int)$data['filter_marketing_id'] . "' ";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = " DATE(o.date_added) >= '" . $this->db->escape((string)$data['filter_date_start']) . "' ";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = " DATE(o.date_added) <= '" . $this->db->escape((string)$data['filter_date_end']) . "' ";
		}

		if (!empty($data['filter_country_id'])) {
			$implode[] = " (pc.country_id = '" . (int)$data['filter_country_id'] . "' OR sc.country_id = '" . (int)$data['filter_country_id'] . "') ";
		}

		if (!empty($data['filter_order_status_id'])) {
			$implode[] = " o.order_status_id = '" . (int)$data['filter_order_status_id'] . "' ";
		} else {
			$implode[] = " o.order_status_id > '0' ";
		}

		if (!empty($implode)){
			$sql .= "WHERE ".implode( " AND ", $implode );
		}

		$sql .= " GROUP BY m.marketing_id ";

		if (!empty($data['filter_group'])) {
			switch($data['filter_group']) {
				case 'day';
					$sql .= " , YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added) ";
					break;
				case 'week':
					$sql .= " , YEAR(o.date_added), WEEK(o.date_added) ";
					break;
				case 'month':
					$sql .= " , YEAR(o.date_added), MONTH(o.date_added) ";
					break;
				case 'year':
					$sql .= " , YEAR(o.date_added) ";
					break;
			}
		}

		$sql .= " order by `date_start` DESC ";

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

	public function getTotalmarketingSales($data = array()){
		switch($data['filter_group']) {
			case 'day';
				$sql = " SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), DAY(o.date_added ), m.marketing_id ) ";
				break;
			case 'week':
				$sql = " SELECT COUNT(DISTINCT YEAR(o.date_added), WEEK(o.date_added), m.marketing_id ) ";
				break;
			case 'month':
				$sql = " SELECT COUNT(DISTINCT YEAR(o.date_added), MONTH(o.date_added), m.marketing_id ) ";
				break;
			case 'year':
				$sql = " SELECT COUNT(DISTINCT YEAR(o.date_added), m.marketing_id ) ";
				break;
			default:
				$sql = " SELECT COUNT(DISTINCT m.marketing_id ) ";
		}

		$sql .= " AS total FROM `" . DB_PREFIX . "order` o
			LEFT JOIN `".DB_PREFIX."marketing` m ON (o.marketing_id = m.marketing_id)
			LEFT JOIN `".DB_PREFIX."country` pc ON (o.payment_country_id = pc.country_id)
			LEFT JOIN `".DB_PREFIX."country` sc ON (o.shipping_country_id = sc.country_id) ";

		$implode = array();

		if (!empty($data['filter_marketing_id'])){
			$implode[] = " m.marketing_id = '" . (int)$data['filter_marketing_id'] . "' ";
		}

		if (!empty($data['filter_date_start'])) {
			$implode[] = " DATE(o.date_added) >= '" . $this->db->escape((string)$data['filter_date_start']) . "' ";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = " DATE(o.date_added) <= '" . $this->db->escape((string)$data['filter_date_end']) . "' ";
		}

		if (!empty($data['filter_country_id'])) {
			$implode[] = " (pc.country_id = '" . (int)$data['filter_country_id'] . "' OR sc.country_id = '" . (int)$data['filter_country_id'] . "') ";
		}

		if (!empty($data['filter_order_status_id'])) {
			$implode[] = " o.order_status_id = '" . (int)$data['filter_order_status_id'] . "' ";
		} else {
			$implode[] = " o.order_status_id > '0' ";
		}

		if (!empty($implode)){
			$sql .= " WHERE ".implode( " AND ", $implode );
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
