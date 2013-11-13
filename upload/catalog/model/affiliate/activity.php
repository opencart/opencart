<?php
class ModelAffiliateActivity extends Model {
	public function addActivity($affiliate_id, $message) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "affiliate_activity SET affiliate_id = '" . (int)$affiliate_id . "', comment = '" . $this->db->escape($message) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
	}	
}
?>