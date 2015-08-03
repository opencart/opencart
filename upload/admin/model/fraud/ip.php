<?php
class ModelFraudMaxMind extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ip` (
			  `ip` varchar(40) NOT NULL,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`ip`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "maxmind`");
	}

	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "maxmind` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}
}
