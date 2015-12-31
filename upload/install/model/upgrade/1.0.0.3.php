<?php
class ModelUpgrade1003 extends Model {
	public function upgrade() {
		// Alter company ID from 32 to 40 chars
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "address` CHANGE `company` `company` VARCHAR(40) NOT NULL");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` CHANGE `payment_company` `payment_company` VARCHAR(40) NOT NULL");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` CHANGE `payment_company` `payment_company` VARCHAR(40) NOT NULL");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "affiliate` CHANGE `company` `company` VARCHAR(40) NOT NULL");
		
		// Check address for missing custom field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "address' AND COLUMN_NAME = 'custom_field'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "address` ADD `custom_field` TEXT NOT NULL AFTER `zone_id`");
		}
		
		// Check customer for missing custom field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer' AND COLUMN_NAME = 'custom_field'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` ADD `custom_field` TEXT NOT NULL AFTER `address_id`");
		}
		
		// custom field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'payment_custom_field'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `payment_custom_field` TEXT NOT NULL AFTER `payment_address_format`");
		}
		
		// custom field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'shipping_custom_field'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `shipping_custom_field` TEXT NOT NULL AFTER `shipping_address_format`");
		}	
		
		// banner
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "banner_image' AND COLUMN_NAME = 'sort_order'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image` ADD `sort_order` INT(3) NOT NULL AFTER `image`");
		}		
	}
}