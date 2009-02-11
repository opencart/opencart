<?php
class ModelAccountDownload extends Model {
	public function getDownload($order_download_id) {
		$query = $this->db->query("SELECT * FROM order_download od LEFT JOIN `order` o ON (od.order_id = o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.confirm = '1' AND o.order_status_id = '" . (int)$this->config->get('config_download_status') . "' AND od.order_download_id = '" . (int)$order_download_id . "' AND od.remaining > 0");
		
		return $query->row;
	}
	
	public function getDownloads($start = 1, $limit = 20) {
		$query = $this->db->query("SELECT o.order_id, o.date_added, od.order_download_id, od.name, od.filename, od.remaining FROM order_download od LEFT JOIN `order` o ON (od.order_id = o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.confirm = '1' AND o.order_status_id = '" . (int)$this->config->get('config_download_status') . "' ORDER BY o.date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
	
		return $query->rows;
	}
	
	public function updateRemaining($order_download_id) {
		$this->db->query("UPDATE order_download SET remaining = (remaining - 1) WHERE order_download_id = '" . (int)$order_download_id . "'");
	}
	
	public function getTotalDownloads() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM order_download od LEFT JOIN `order` o ON (od.order_id = o.order_id) WHERE o.customer_id = '" . (int)$this->customer->getId() . "' AND o.confirm = '1' AND o.order_status_id = '" . (int)$this->config->get('config_download_status') . "'");
		
		return $query->row['total'];
	}	
}
?>