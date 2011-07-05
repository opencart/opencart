<?php
class ModelReportReturn extends Model {
	public function getReturns($data = array()) {
		$sql = "SELECT MIN(tmp.date_added) AS date_start, MAX(tmp.date_added) AS date_end, COUNT(tmp.return_id) AS `returns`, SUM(tmp.products) AS products FROM (SELECT r.return_id, (SELECT SUM(rp.quantity) FROM `" . DB_PREFIX . "return_product` rp WHERE rp.return_id = r.return_id) AS products, r.date_added FROM `" . DB_PREFIX . "return` r"; 

		if (isset($data['filter_return_status_id']) && $data['filter_return_status_id']) {
			$sql .= " WHERE r.return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		} else {
			$sql .= " WHERE r.return_status_id > '0'";
		}
		
		if (isset($data['filter_date_start']) && $data['filter_date_start']) {
			$sql .= " AND DATE(r.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_date_end']) && $data['filter_date_end']) {
			$sql .= " AND DATE(r.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= ") tmp";
		
		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql .= " GROUP BY DAY(tmp.date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(tmp.date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(tmp.date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(tmp.date_added)";
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
	
	public function getTotalReturns($data = array()) {
		if (isset($data['filter_group'])) {
			$group = $data['filter_group'];
		} else {
			$group = 'week';
		}
		
		switch($group) {
			case 'day';
				$sql = "SELECT COUNT(DISTINCT DAY(date_added)) AS total FROM `" . DB_PREFIX . "return`";
				break;
			default:
			case 'week':
				$sql = "SELECT COUNT(DISTINCT WEEK(date_added)) AS total FROM `" . DB_PREFIX . "return`";
				break;	
			case 'month':
				$sql = "SELECT COUNT(DISTINCT MONTH(date_added)) AS total FROM `" . DB_PREFIX . "return`";
				break;
			case 'year':
				$sql = "SELECT COUNT(DISTINCT YEAR(date_added)) AS total FROM `" . DB_PREFIX . "return`";
				break;									
		}
		
		if (isset($data['filter_return_status_id']) && $data['filter_return_status_id']) {
			$sql .= " WHERE return_status_id = '" . (int)$data['filter_return_status_id'] . "'";
		} else {
			$sql .= " WHERE return_status_id > '0'";
		}
				
		if (isset($data['filter_date_start']) && $data['filter_date_start']) {
			$sql .= " AND DATE(date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (isset($data['filter_date_end']) && $data['filter_date_end']) {
			$sql .= " AND DATE(date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];	
	}	
}
?>