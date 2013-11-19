<?php
class ModelAccountActivity extends Model {
	public function addActivity($key, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_activity SET customer_id = '" . (int)$this->customer->getId() . "', key = '" . $this->db->escape($key) . "', data = '" . $this->db->escape(serialize($data)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
	}	
}