<?php
class ModelReportCustomer extends Model {
	public function getRewardPoints($data = array()) { 
		$sql = "SELECT *, SUM(cr.points) AS balance FROM " . DB_PREFIX . "customer_reward cr LEFT JOIN `" . DB_PREFIX . "customer` c ON cr.customer_id = c.customer_id GROUP BY cr.customer_id ORDER BY balance DESC";
		
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
		$query = $this->db->query("SELECT COUNT(DISTINCT customer_id) AS total FROM `" . DB_PREFIX . "customer_reward`");

		return $query->row['total'];
	}
}
?>