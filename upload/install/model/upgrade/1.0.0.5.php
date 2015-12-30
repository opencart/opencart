<?php
class ModelUpgrade1005 extends Model {
	public function upgrade() {
		// Affiliate Activity changes
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate_activity' AND COLUMN_NAME = 'activity_id'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "affiliate_activity` CHANGE `activity_id` `affiliate_activity_id` INT(11) NOT NULL AUTO_INCREMENT");
		}
		
		// Customer Activity changes
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_activity' AND COLUMN_NAME = 'activity_id'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_activity` CHANGE `activity_id` `customer_activity_id` INT(11) NOT NULL AUTO_INCREMENT");
		}
		
		// API changes fix
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'username'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` CHANGE `username` `name` varchar(64) NOT NULL");
		}
		
		// API changes fix
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'password'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` CHANGE `password` `key` text NOT NULL");
		}		
		
		// product
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` CHANGE `isbn` `isbn` VARCHAR(17) NOT NULL");	
		
		// product
		$index_data = array();
		
		$query = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "product_image`");
		
		foreach ($query->rows as $result) {
			$index_data[] = $result['Column_name'];
		}
		
		if (!in_array('product_id', $index_data)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_image` ADD INDEX `product_id` (`product_id`)");
		}
		
		// product_to_category
		
	}
}