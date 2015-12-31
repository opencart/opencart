<?php
class ModelUpgrade1004 extends Model {
	public function upgrade() {
		// category
		$primary_data = array();
		
		$query = $this->db->query("SHOW KEYS FROM `" . DB_PREFIX . "category` WHERE Key_name = 'PRIMARY'");
		
		foreach ($query->rows as $result) {
			$primary_data[] = $result['Column_name'];
		}
		
		if (!in_array('category_id', $primary_data) || !in_array('parent_id', $primary_data)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` DROP PRIMARY KEY, ADD PRIMARY KEY(`category_id`, `parent_id`)");
		}
				
		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'required'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `required`");
		}
		
		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'position'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `position`");
		}
				
		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'status'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` ADD `status` tinyint(1) NOT NULL AFTER `location`");
		}
		
		// custom_field
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` CHANGE `location` `location` varchar(7) NOT NULL");

		// order_custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_field'");
		
		if ($query->num_rows) {
			$order_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_field`");
			
			foreach ($order_field_query->rows as $result) {
				$order_custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_custom_field` WHERE `order_id` = '" . (int)$result['order_id'] . "' AND custom_field_id = '" . (int)$result['custom_field_id'] . "' AND custom_field_value_id = '" . (int)$result['custom_field_value_id'] . "' AND `name` = '" . $this->db->escape($result['name']) . "' AND `value` = '" . $this->db->escape($result['value']) . "'");
				
				if (!$order_custom_field_query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_custom_field` SET `order_id` = '" . (int)$result['order_id'] . "', custom_field_id = '" . (int)$result['custom_field_id'] . "', custom_field_value_id = '" . (int)$result['custom_field_value_id'] . "', `name` = '" . $this->db->escape($result['name']) . "', `value` = '" . $this->db->escape($result['value']) . "'");
				}
			}
			
			$this->db->query("DROP TABLE `" . DB_PREFIX . "order_field`");
		}
		
		// download
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "download' AND COLUMN_NAME = 'remaining'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "download` DROP `remaining`");
		}	
		
		// information_description
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "information_description' AND COLUMN_NAME = 'meta_title'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "information_description` ADD `meta_title` varchar(255) NOT NULL AFTER `description`");
		}
		
		// information_description
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "information_description' AND COLUMN_NAME = 'meta_description'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "information_description` ADD `meta_description` varchar(255) NOT NULL AFTER `meta_title`");
		}
		
		// information_description
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "information_description' AND COLUMN_NAME = 'meta_keyword'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "information_description` ADD `meta_keyword` varchar(255) NOT NULL AFTER `meta_description`");
		}
			
		// order
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'marketing_id'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `marketing_id` int(11) NOT NULL AFTER `commission`");
		}	
			
		// order
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'tracking'");
		
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `tracking` varchar(64) NOT NULL AFTER `marketing_id`");
		}
	}
}