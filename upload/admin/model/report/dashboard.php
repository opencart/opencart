<?php
class ModelReportDashboard extends Model {
	public function getTotalSalesByMonths($limit = 5) {
		$sale_data = array();

		$j = 1;
		
		for ($i = $limit; $i >= 0; $i--) {
			$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = '" . date('m', strtotime('-' . (int)$i . ' month')) . "' AND YEAR(date_added) = '" . date('Y', strtotime('-' . (int)$i . ' month')) . "' GROUP BY YEAR(date_added), MONTH(date_added)");

			if ($query->num_rows) {
				$sale_data[] = array(
					$j,
					$query->row['total']
				);
			} else {
				$sale_data[] = array(
					$j,
					0
				);
			}
			
			$j++;
		}
		
		return $sale_data;
	}	
	
	public function getTotalOrdersByMonths($limit = 5) {
		$order_data = array();
		
		for ($i = $limit; $i >= 0; $i--) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND MONTH(date_added) = '" . date('m', strtotime('-' . (int)$i . ' month')) . "' AND YEAR(date_added) = '" . date('Y', strtotime('-' . (int)$i . ' month')) . "'");
			
			if ($query->num_rows) {
				$order_data[] = $query->row['total'];
			} else {
				$order_data[] = 0;
			}			
		} 
		
		return $order_data;
	}	
	
	public function getTotalCustomersByMonths($limit = 5) {
		$customer_data = array();
		
		for ($i = $limit; $i >= 0; $i--) {
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer` WHERE MONTH(date_added) = '" . date('m', strtotime('-' . (int)$i . ' month')) . "' AND YEAR(date_added) = '" . date('Y', strtotime('-' . (int)$i . ' month')) . "'");
			
			if ($query->num_rows) {
				$customer_data[] = $query->row['total'];
			} else {
				$customer_data[] = 0;
			}
		}

		
		return $customer_data;
	}
	
	public function getTotalPeopleOnline() {
		$online_data = array();
		
		for ($i = 1; $i <= 4; $i++) {
			$start = (($i * 15) - 15);
			$end = ($i * 15);
			
			$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "customer_online` WHERE date_added < DATE_SUB(NOW(), INTERVAL '" . $start . "' MINUTE) AND date_added > DATE_SUB(NOW(), INTERVAL '" . $end . "' MINUTE)");

			if ($query->num_rows) {
				$online_data[] = $query->row['total'];
			} else {
				$online_data[] = 0;
			}
		}	
		
		return $online_data;
	}	
}
?>