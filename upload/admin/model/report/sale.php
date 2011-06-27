<?php
class ModelReportSale extends Model {
	public function getOrders($data = array()) {
		$sql = "SELECT MIN(r.date_added) AS date_start, MAX(r.date_added) AS date_end, COUNT(r.order_id) AS `orders`, SUM(r.products) AS products, SUM(r.tax) AS tax, SUM(r.total) AS total FROM (SELECT o.order_id, (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_product` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS products, (SELECT SUM(ot.value) FROM `" . DB_PREFIX . "order_total` ot WHERE ot.order_id = o.order_id AND ot.code = 'tax' GROUP BY ot.order_id) AS tax, o.total, o.date_added FROM `" . DB_PREFIX . "order` o"; 

		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}
		
		if (isset($data['filter_date_start'])) {
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['filter_date_end'])) {
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(o.date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(o.date_added) <= '" . $this->db->escape($date_end) . "') GROUP BY o.order_id) r";
		
		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(r.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(r.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(r.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(r.date_added)";
				break;									
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
	
	public function getTotalOrders($data = array()) {
		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT DAY(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT WEEK(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;	
			case 'month':
				$sql = "SELECT COUNT(DISTINCT MONTH(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added)) AS total FROM `" . DB_PREFIX . "order`";
				break;									
		}
		
		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " WHERE order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " WHERE order_status_id > '0'";
		}
				
		if (isset($data['filter_date_start'])) {
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['filter_date_end'])) {
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];	
	}
	
	public function getTaxes($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'"; 

		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
		
		if (isset($data['filter_date_start'])) {
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['filter_date_end'])) {
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(o.date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(o.date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY ot.title, DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY ot.title, WEEK(o.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY ot.title, MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY ot.title, YEAR(o.date_added)";
				break;									
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
	
	public function getTotalTaxes($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM (SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'tax'";
		
		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
				
		if (isset($data['filter_date_start'])) {
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['filter_date_end'])) {
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		}
		
		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(o.date_added), ot.title";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;									
		}
		
		$sql .= ") r";
		
		$query = $this->db->query($sql);

		return $query->row['total'];	
	}	
	
	public function getShipping($data = array()) {
		$sql = "SELECT MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'shipping'"; 

		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
		
		if (isset($data['filter_date_start'])) {
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['filter_date_end'])) {
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(o.date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(o.date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY ot.title, DAY(o.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY ot.title, WEEK(o.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY ot.title, MONTH(o.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY ot.title, YEAR(o.date_added)";
				break;									
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
	
	public function getTotalShipping($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM (SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order_total` ot LEFT JOIN `" . DB_PREFIX . "order` o ON (ot.order_id = o.order_id) WHERE ot.code = 'shipping'";
		
		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND order_status_id > '0'";
		}
				
		if (isset($data['filter_date_start'])) {
			$date_start = $data['filter_date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['filter_date_end'])) {
			$date_end = $data['filter_date_end'];
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (isset($data['filter_order_status_id']) && $data['filter_order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		}
		
		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(o.date_added), ot.title";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(o.date_added), ot.title";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(o.date_added), ot.title";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(o.date_added), ot.title";
				break;									
		}
		
		$sql .= ") r";
		
		$query = $this->db->query($sql);

		return $query->row['total'];	
	}		
}
?>