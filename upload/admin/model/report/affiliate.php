<?php
class ModelReportAffiliate extends Model {
	public function getCommission($data = array()) { 
		$sql = "SELECT at.affiliate_id, CONCAT(a.firstname, ' ', a.lastname) AS affiliate, a.email, a.status, SUM(at.amount) AS commission, COUNT(o.order_id) AS orders, SUM(o.total) AS total FROM " . DB_PREFIX . "affiliate_transaction at LEFT JOIN `" . DB_PREFIX . "affiliate` a ON (at.affiliate_id = a.affiliate_id) LEFT JOIN `" . DB_PREFIX . "order` o ON (at.order_id = o.order_id)";
		
		$implode = array();
		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(at.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(at.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$sql .= " GROUP BY at.affiliate_id ORDER BY commission DESC";
				
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

	public function getTotalCommission() {
		$sql = "SELECT COUNT(DISTINCT affiliate_id) AS total FROM `" . DB_PREFIX . "affiliate_transaction`";
		
		$implode = array();
		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(at.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(at.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
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
		
		return $query->row['total'];
	}
	
	public function getProducts($data = array()) { 
		$sql = "SELECT at.product_id, CONCAT(a.firstname, ' ', a.lastname) AS affiliate, a.email, a.status, SUM(at.amount) AS commission, COUNT(o.order_id) AS orders, SUM(o.total) AS total FROM " . DB_PREFIX . "affiliate_transaction at LEFT JOIN `" . DB_PREFIX . "affiliate` a ON (at.affiliate_id = a.affiliate_id) LEFT JOIN `" . DB_PREFIX . "order` o ON (at.order_id = o.order_id) LEFT JOIN " . DB_PREFIX . "product";
		
		$implode = array();
		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(at.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(at.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$sql .= " GROUP BY at.affiliate_id ORDER BY commission DESC";
				
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

	public function getTotalProducts() {
		$sql = "SELECT COUNT(DISTINCT product_id) AS total FROM `" . DB_PREFIX . "affiliate_transaction`";
		
		$implode = array();
		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(at.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(at.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
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
		
		return $query->row['total'];
	}	
}
?>