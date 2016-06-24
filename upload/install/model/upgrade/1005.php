<?php
class ModelUpgrade1005 extends Model {
	public function upgrade() {
		// api
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'username'");

		if ($query->num_rows) {
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'name'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "api` SET `name` = `username` WHERE `name` IS NULL or `name` = ''");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` DROP `username`");
			} else {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` CHANGE `username` `name` varchar(64) NOT NULL");
			}
		}

		// api
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'password'");

		if ($query->num_rows) {
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'key'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "api` SET `key` = `password` WHERE `key` IS NULL or `key` = ''");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` DROP `password`");
			} else {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` CHANGE `password` `key` text NOT NULL");
			}
		}

		// Insert default API record if not set
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "api");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "api (api_id, name, `key`, status, date_added, date_modified) values(1, 'json', '" . hash('sha512', mt_rand()) . "' , 1, now(), now())");
		}

		// customer
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` CHANGE `token` `token` text NOT NULL");

		// customer
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer' AND COLUMN_NAME = 'code'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` ADD `code` tinyint(1) NOT NULL AFTER `token`");
		}
		
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
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'date_added'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring_transaction` SET `date_added` = `created` WHERE `date_added` IS NULL or `date_added` = ''");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` DROP `created`");
			} else {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` CHANGE `created` `date_added` datetime NOT NULL AFTER `amount`");
			}
		}

		// order_recurring_transaction
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_recurring_transaction' AND COLUMN_NAME = 'reference'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` ADD `reference` varchar(255) NOT NULL AFTER `order_recurring_id`");
		}

		// order_recurring_transaction
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_recurring_transaction` CHANGE `type` `type` varchar(255) NOT NULL AFTER `reference`");

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

		// Set Product Meta Title default to product name if empty
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `meta_title` = `name` WHERE meta_title = ''");
		$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `meta_title` = `name` WHERE meta_title = ''");
		$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `meta_title` = `title` WHERE meta_title = ''");
		
		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_complete_status'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_complete_status', `value` = '[\"5\"]', `code` = 'config', `serialized` = '1', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_processing_status'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_processing_status', `value` = '[\"2\"]', `code` = 'config', `serialized` = '1', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_fraud_status_id'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_fraud_status_id', `value` = '8', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_api_id'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_api_id', `value` = '1', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_image_location_height'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_image_location_height', `value` = '50', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_image_location_width'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_image_location_width', `value` = '258', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_product_limit'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_product_limit', `value` = '20', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_product_description_length'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_product_description_length', `value` = '100', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_limit_admin'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_limit_admin', `value` = '20', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_encryption'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_encryption', `value` = '" . hash('sha512', mt_rand()) . "', `code` = 'config', `serialized` = '0', `store_id` = 0");
		} elseif (strlen($query->row['value']) < 28) {
			$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_encryption'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_encryption', `value` = '" . hash('sha512', mt_rand()) . "', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}
		// force some settings to prevent errors
		$this->db->query("UPDATE " . DB_PREFIX . "setting set value = 'default' WHERE `key` = 'config_template'");
		$this->db->query("UPDATE " . DB_PREFIX . "setting set value = '1' WHERE `key` = 'config_error_display'");
		$this->db->query("UPDATE " . DB_PREFIX . "setting set value = '1' WHERE `key` = 'config_error_log'");
		$this->db->query("UPDATE " . DB_PREFIX . "setting set value = '0' WHERE `key` = 'config_compression'");
	}
}