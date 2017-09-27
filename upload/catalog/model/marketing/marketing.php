<?php
class ModelMarketingMarketing extends Model {
	public function getMarketingByCode($code) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "marketing WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function addMarketingReport($marketing_id, $ip, $country = '') {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "marketing_report` SET marketing_id = '" . (int)$marketing_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', ip = '" . $this->db->escape($ip) . "', country = '" . $this->db->escape($country) . "', date_added = NOW()");
	}
}