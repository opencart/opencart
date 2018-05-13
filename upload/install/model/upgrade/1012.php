<?php
class ModelUpgrade1012 extends Model {
	public function upgrade() {

		// repair custom_field (issue from upgrade 1004 line 26)
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` CHANGE `location` `location` varchar(9) NOT NULL");
		$this->db->query("UPDATE `" . DB_PREFIX . "custom_field` SET `location` = 'affiliate' WHERE `location` = 'affilia'");
	}
}
