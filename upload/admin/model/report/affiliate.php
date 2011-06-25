<?php
class ModelReportAffiliateCommission extends Model {
	public function getCommissionReport($data = array()) { 
		$sql = "SELECT *, SUM(t.amount) AS balance FROM " . DB_PREFIX . "transaction t LEFT JOIN `" . DB_PREFIX . "member` m ON t.member_id = m.member_id GROUP BY t.member_id ORDER BY balance DESC";
		
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

	public function getCommissionReportTotal($data = array()) {
		$query = $this->db->query("SELECT COUNT(DISTINCT member_id) AS total FROM `" . DB_PREFIX . "transaction`");

		return $query->row['total'];
	}
}
?>