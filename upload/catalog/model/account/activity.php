<?php
class ModelAccountActivity extends Model {
	public function addActivity($customer_id, $message) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_activity SET customer_id = '" . (int)$customer_id . "', action = '" . $this->db->escape($message) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
	}	
}
?>