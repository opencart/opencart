<?php
class ModelAccountActivity extends Model {
	public function addActivity($message) {
		$args = func_get_args();

		$args = array_shift($args);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_activity SET customer_id = '" . (int)$this->customer->getId() . "', comment = '" . $this->db->escape($message) . "', data = '" . $this->db->escape(serialize($args)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
	}	
}