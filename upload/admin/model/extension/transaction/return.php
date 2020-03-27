<?php
class ModelExtensionTransactionReturn extends Model {
	public function install() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "return_transaction`");
		
		$this->db->query("CREATE TABLE `" . DB_PREFIX . "return_transaction` (
		  `return_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
		  `custom_field_id` int(11) NOT NULL,		  
		  `date_added` DATETIME NOT NULL,
		  PRIMARY KEY (`return_transaction_id`),
		  KEY `custom_field_id` (`custom_field_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "return_transaction`");
	}

    public function getApproval($custom_field_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "return_transaction` WHERE `custom_field_id` = '" . (int)$custom_field_id . "'");
		
		return $query->row;
	}
	
	public function getApprove($custom_field_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_transaction WHERE custom_field_id = '" . (int)$custom_field_id . "' AND `status` = '1'");
		
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function getDeny($custom_field_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_transaction WHERE custom_field_id = '" . (int)$custom_field_id . "' AND `status` = '2'");
		
		if ($query->num_rows) {
			return $query->row;
		} else {
			return false;
		}
	}
	
	public function approve($custom_field_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return_transaction` SET custom_field_id = '" . (int)$custom_field_id . "', `status` = '1'");
	}
	
	public function deny($custom_field_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "return_transaction` SET custom_field_id = '" . (int)$custom_field_id . "', `status` = '0'");
	}
}
