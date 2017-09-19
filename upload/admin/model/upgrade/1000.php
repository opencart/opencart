<?php
class ModelUpgrade1000 extends Model {
	public function upgrade() {
		// marketing_history
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "marketing_history'");

		if (!$query->num_rows) {
			$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "marketing_history` (
			`marketing_history_id` int(11) NOT NULL AUTO_INCREMENT,
			`marketing_id` int(11) NOT NULL,
			`ip` varchar(40) NOT NULL,
			`country` varchar(2) NOT NULL,
			`date_added` datetime NOT NULL,
			PRIMARY KEY (`marketing_history_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		}
	}
}