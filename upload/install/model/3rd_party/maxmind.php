<?php
class Model3rdPartyMaxmind extends Model {
	public function editSetting($data) {
		$this->db->query("REPLACE INTO `" . DB_PREFIX . "setting` SET `maxmind_key` = '" . $db->escape($data['fraud_maxmind_key']) . "', `maxmind_score` = '" . (int)$data['fraud_maxmind_score'] . "', `maxmind_order_status_id` = '" . (int)$data['maxmind_order_status_id'] . "' WHERE `store_id` = '0' AND `code` = 'maxmind'");

		$this->db->query("INSERT INTO `oc_extension` (`type`, `code`) VALUES ('fraud', 'maxmind')");
	}
	
	public function getOrderStatuses() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '1' ORDER BY name ASC");
		
		return $query->rows;
	}
}
