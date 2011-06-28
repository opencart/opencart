<?php
class ModelReportCustomer extends Model {
	public function getRewardPoints($data = array()) { 
		$sql = "SELECT cr.customer_id, CONCAT(c.firstname, ' ', c.lastname) AS customer, c.email, cg.name AS customer_group, c.status, SUM(cr.points) AS points, COUNT(o.order_id) AS orders, SUM(o.total) AS total FROM " . DB_PREFIX . "customer_reward cr LEFT JOIN `" . DB_PREFIX . "customer` c ON (cr.customer_id = c.customer_id) LEFT JOIN " . DB_PREFIX . "customer_group cg ON (c.customer_group_id = cg.customer_group_id) LEFT JOIN `" . DB_PREFIX . "order` o ON (cr.order_id = o.order_id)";
		
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
		
		$sql .= " WHERE (DATE(cr.date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(cr.date_added) <= '" . $this->db->escape($date_end) . "') GROUP BY cr.customer_id ORDER BY points DESC";
				
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

	public function getTotalRewardPoints() {
		$sql = "SELECT COUNT(DISTINCT customer_id) AS total FROM `" . DB_PREFIX . "customer_reward`";
		
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
		
		$sql .= " WHERE (DATE(date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(date_added) <= '" . $this->db->escape($date_end) . "') GROUP BY customer_id ORDER BY points DESC";
				
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