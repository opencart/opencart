<?php
class ModelAccountActivity extends Model {
	public function addActivity($customer_id, $message) {
		$language = new Language();
		
		$this->load->language('activity');
		
		$args = func_get_args();
		
		$args = array_slice($args, 2);
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_activity SET customer_id = '" . (int)$customer_id . "', comment = '" . $this->db->escape(sprintf($this->language->get('text_' . $message), $args)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
	}	
}