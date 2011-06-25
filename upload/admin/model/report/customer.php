<?php
class ModelReportCustomer extends Model {
	public function getRewardPoints($start = 0, $limit = 20) { 
		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
			
		$query = $this->db->query("SELECT *, SUM(cr.points) AS balance FROM " . DB_PREFIX . "customer_reward cr LEFT JOIN `" . DB_PREFIX . "customer` c ON cr.customer_id = c.customer_id GROUP BY cr.customer_id ORDER BY balance DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}

	public function getTotalRewardPoints($data = array()) {
		$query = $this->db->query("SELECT COUNT(DISTINCT customer_id) AS total FROM `" . DB_PREFIX . "customer_reward`");

		return $query->row['total'];
	}
}
?>