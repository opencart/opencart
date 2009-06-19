<?php
class ModelReportReport extends Model {
	public function getProductViewedReport($start = 0, $limit = 20) {
		$total = 0;

		$product_data = array();
		
		$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product");

		$total = $query->row['total'];
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->language->getId() . "' ORDER BY viewed DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		foreach ($query->rows as $result) {
			$product_data[] = array(
				'name'    => $result['name'],
				'model'   => $result['model'],
				'viewed'  => $result['viewed'],
				'percent' => round(($result['viewed'] / $total) * 100, 2) . '%'
			);
		}
		
		return $product_data;
	}	
	
	public function getProductPurchasedReport($start = 0, $limit = 20) {
		$query = $this->db->query("SELECT op.name, op.model, SUM(op.quantity) AS quantity, SUM(op.total + op.tax) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE o.order_status_id > '0' GROUP BY model ORDER BY total DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}

	public function getSaleReport($data = array()) {
		$sql = "SELECT MIN(date_added) AS date_start, MAX(date_added) AS date_end, COUNT(*) AS orders, SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'"; 
		
		if (isset($data['date_start'])) {
			$date_start = $data['date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['date_end'])) {
			$date_end = $data['date_end'];
		} else {
			$date_end = date('Y-m-d', time());
		}
		
		$sql .= " AND (DATE(date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (@$data['order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['order_status_id'] . "'";
		}
		
		switch(@$data['group']) {
			case 'day';
				$sql .= " GROUP BY DAY(date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(date_added)";
				break;									
		}

		if (isset($data['start']) || isset($data['limit'])) {
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	
	
	public function getTotalOrderedProducts() {
      	$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` GROUP BY model");
		
		return $query->num_rows;
	}
	
	public function getSaleReportTotal($data = array()) {
		$sql = "SELECT MIN(date_added) AS date_start, MAX(date_added) AS date_end, COUNT(*) AS orders, SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'";
		
		if (isset($data['date_start'])) {
			$date_start = $data['date_start'];
		} else {
			$date_start = date('Y-m-d', strtotime('-7 day'));
		}

		if (isset($data['date_end'])) {
			$date_end = $data['date_end'];
		} else {
			$date_end = date('Y-m-d', strtotime($date_start));
		}
		
		$sql .= " AND (DATE(date_added) >= '" . $this->db->escape($date_start) . "' AND DATE(date_added) <= '" . $this->db->escape($date_end) . "')";
		
		if (@$data['order_status_id']) {
			$sql .= " AND order_status_id = '" . (int)$data['order_status_id'] . "'";
		}
		
		switch(@$data['group']) {
			case 'day';
				$sql .= " GROUP BY DAY(date_added)";
				break;
			default:
			case 'week':
				$sql .= " GROUP BY WEEK(date_added)";
				break;	
			case 'month':
				$sql .= " GROUP BY MONTH(date_added)";
				break;
			case 'year':
				$sql .= " GROUP BY YEAR(date_added)";
				break;									
		}

		$query = $this->db->query($sql);

		return $query->num_rows;	
	}
}
?>