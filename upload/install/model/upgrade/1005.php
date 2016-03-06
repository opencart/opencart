<?php
class ModelUpgrade1005 extends Model {
	public function upgrade() {
		// affiliate_activity
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate_activity' AND COLUMN_NAME = 'activity_id'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "affiliate_activity` CHANGE `activity_id` `affiliate_activity_id` INT(11) NOT NULL AUTO_INCREMENT");
		}
		
		// customer_activity
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_activity' AND COLUMN_NAME = 'activity_id'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_activity` CHANGE `activity_id` `customer_activity_id` INT(11) NOT NULL AUTO_INCREMENT");
		}
		
		// api
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'username'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` CHANGE `username` `name` varchar(64) NOT NULL");
		}
		
		// api
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'password'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` CHANGE `password` `key` text NOT NULL");
		}		
		
		// customer
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` CHANGE `token` `token` text NOT NULL");	
		
		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'validation'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` ADD `validation` varchar(255) NOT NULL AFTER `value`");
		}	
				
		// product
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` CHANGE `isbn` `isbn` VARCHAR(17) NOT NULL");	
		
		// product
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product' AND COLUMN_NAME = 'viewed'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD `viewed` int(5) NOT NULL AFTER `status`");
		}		
		
		// product_description
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_description' AND COLUMN_NAME = 'meta_title'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_description` ADD `meta_title` varchar(255) NOT NULL AFTER `description`");
		}	
				
		// product_image
		$index_data = array();
		
		$query = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "product_image` WHERE Key_name != 'PRIMARY'");
		
		foreach ($query->rows as $result) {
			$index_data[] = $result['Column_name'];
		}
		
		if (!in_array('product_id', $index_data)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_image` ADD INDEX `product_id` (`product_id`)");
		}
		
		// product_to_category
		$index_data = array();
		
		$query = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "product_to_category` WHERE Key_name != 'PRIMARY'");
		
		foreach ($query->rows as $result) {
			$index_data[] = $result['Column_name'];
		}
		
		if (!in_array('category_id', $index_data)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_to_category` ADD INDEX `category_id` (`category_id`)");
		}
		
		// product_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_recurring' AND COLUMN_NAME = 'recurring_id'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_recurring` ADD `recurring_id` int(11) NOT NULL AFTER `product_id`");
		}
				
		// product_recurring
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_recurring' AND COLUMN_NAME = 'customer_group_id'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_recurring` ADD `customer_group_id` int(11) NOT NULL AFTER `recurring_id`");
		}
		
		// order_recurring_transaction
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'created'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` CHANGE `created` `date_added` datetime NOT NULL AFTER `amount`");
		}
				
		// order_recurring_transaction
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'reference'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` ADD `reference` varchar(255) NOT NULL AFTER `order_recurring_id`");
		}	
		
		// order_recurring_transaction
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` CHANGE `type` `type` varchar(255) NOT NULL AFTER `reference`");
								
		// setting
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "setting' AND COLUMN_NAME = 'group'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "setting` CHANGE `group` `code` varchar(32) NOT NULL");
		}
		
		// url_alias
		$index_data = array();
		
		$query = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "url_alias` WHERE Key_name != 'PRIMARY'");
		
		foreach ($query->rows as $result) {
			$index_data[] = $result['Column_name'];
		}
		
		if (!in_array('query', $index_data)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "url_alias` ADD INDEX `query` (`query`)");
		}
		
		if (!in_array('keyword', $index_data)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "url_alias` ADD INDEX `keyword` (`keyword`)");
		}
		
		// user
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "user' AND COLUMN_NAME = 'image'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` ADD `image` varchar(255) NOT NULL AFTER `email`");
		}											
	}
}