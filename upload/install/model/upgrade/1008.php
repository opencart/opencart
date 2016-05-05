<?php
class ModelUpgrade1008 extends Model {
	public function upgrade() {
		//  Option
		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = 'radio' WHERE `type` = 'image'");
		
		// Event
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "event' AND COLUMN_NAME = 'status'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "event` ADD `status` TINYINT(1) NOT NULL AFTER `action`");
		}
		
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "event' AND COLUMN_NAME = 'date_added'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "event` ADD `date_added` DATETIME NOT NULL AFTER `status`");
		}
	}
}
