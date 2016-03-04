<?php
class ModelUpgrade1003 extends Model {
	public function upgrade() {
		// address
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "address` CHANGE `company` `company` VARCHAR(40) NOT NULL");
		
		// order
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` CHANGE `payment_company` `payment_company` VARCHAR(40) NOT NULL");
		
		// order
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` CHANGE `shipping_company` `shipping_company` VARCHAR(40) NOT NULL");
		
		// affiliate
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "affiliate` CHANGE `company` `company` VARCHAR(40) NOT NULL");
		
		// order_history
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_history` CHANGE `order_status_id` `order_status_id` int(11) NOT NULL");

		// order_recurring
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` CHANGE `status` `status` tinyint(4) NOT NULL AFTER `trial_price`");
		
		// order_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring' AND COLUMN_NAME = 'created'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` CHANGE `created` `date_added` datetime NOT NULL AFTER `status`");
		}

		// order_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring' AND COLUMN_NAME = 'profile_id'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` CHANGE `profile_id` `recurring_id` int(11) NOT NULL");
		}
		
		// order_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring' AND COLUMN_NAME = 'profile_name'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` CHANGE `profile_name` `recurring_name` varchar(255) NOT NULL");
		}
		
		// order_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring' AND COLUMN_NAME = 'profile_description'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring` CHANGE `profile_description` `recurring_description` varchar(255) NOT NULL");
		}
					
		// address
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "address' AND COLUMN_NAME = 'custom_field'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "address` ADD `custom_field` TEXT NOT NULL AFTER `zone_id`");
		}
		
		// customer
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer' AND COLUMN_NAME = 'custom_field'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` ADD `custom_field` TEXT NOT NULL AFTER `address_id`");
		}
		
		// order
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'custom_field'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `custom_field` TEXT NOT NULL AFTER `fax`");
		}
		
		// order
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'payment_custom_field'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `payment_custom_field` TEXT NOT NULL AFTER `payment_address_format`");
		}
		
		// order
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