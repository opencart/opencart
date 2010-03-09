<?php
class ModelReportViewed extends Model {
	public function getProductViewedReport($start = 0, $limit = 20) {
		$total = 0;

		$product_data = array();
		
		$query = $this->db->query("SELECT SUM(viewed) AS total FROM " . DB_PREFIX . "product");

		$total = $query->row['total'];

		if ($start < 0) {
			$start = 0;
		}
		
		if ($limit < 1) {
			$limit = 20;
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY viewed DESC LIMIT " . (int)$start . "," . (int)$limit);
		
		foreach ($query->rows as $result) {
			if ($result['viewed']) {
				$percent = round(($result['viewed'] / $total) * 100, 2) . '%';
			} else {
				$percent = '0%';
			}
			
			$product_data[] = array(
				'name'    => $result['name'],
				'model'   => $result['model'],
				'viewed'  => $result['viewed'],
				'percent' => $percent
			);
		}
		
		return $product_data;
	}	
	
	public function reset($start = 0, $limit = 20) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET viewed = '0'");
	}
}
?>