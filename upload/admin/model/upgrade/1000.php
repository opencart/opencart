<?php
class ModelUpgrade1000 extends Model {
	public function upgrade() {
		// marketing_report
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "marketing_report'");

		if (!$query->num_rows) {
			$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "marketing_report` (
				`marketing_report_id` int(11) NOT NULL AUTO_INCREMENT,
				`marketing_id` int(11) NOT NULL,
				`ip` varchar(40) NOT NULL,
				`country` varchar(2) NOT NULL,
				`date_added` datetime NOT NULL,
				PRIMARY KEY (`marketing_report_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		}

		// customer_affiliate_report
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_affiliate_report'");

		if (!$query->num_rows) {
			$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "customer_affiliate_report` (
				`customer_affiliate_report_id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_id` int(11) NOT NULL,
				`ip` varchar(40) NOT NULL,
				`country` varchar(2) NOT NULL,
				`date_added` datetime NOT NULL,
				PRIMARY KEY (`customer_affiliate_report_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		}

		// download_report
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "download_report'");

		if (!$query->num_rows) {
			$this->db->query("
			CREATE TABLE `" . DB_PREFIX . "download_report` (
				`download_report_id` int(11) NOT NULL AUTO_INCREMENT,
				`download_id` int(11) NOT NULL,
				`ip` varchar(40) NOT NULL,
				`country` varchar(2) NOT NULL,
				`date_added` datetime NOT NULL,
				PRIMARY KEY (`download_report_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
		}

		// customer_ip
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_ip' AND COLUMN_NAME = 'country'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_ip` ADD `country` varchar(2) NOT NULL AFTER `ip`");
		}
	}
}