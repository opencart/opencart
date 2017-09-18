<?php
class ModelMarketingMarketing extends Model {
	public function getMarketingByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "marketing WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function addMarketingHistory($marketing_id, $ip, $country = '') {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "marketing_history` SET marketing_id = '" . (int)$marketing_id . "', ip = '" . $this->db->escape($ip) . "', country = '" . $this->db->escape($country) . "', date_added = NOW()");
	}
}