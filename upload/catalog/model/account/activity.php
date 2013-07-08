<?php
class ModelAccountActivity extends Model {
	public function addActivity($customer_id, $message) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_activity SET customer_id = '" . (int)$this->customer->getId() . "', action = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
	}	
}
?>