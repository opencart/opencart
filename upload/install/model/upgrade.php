<?php
class ModelUpgrade extends Model {
	public function mysql() {
		$this->db = new DB('mysql', DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);

		// 1.5.1
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "affiliate` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "affiliate` MODIFY `approved` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "banner` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` MODIFY `top` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "category` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "country` MODIFY `postcode_required` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "country` MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "coupon` MODIFY `logged` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "coupon` MODIFY `shipping` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "coupon` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "currency` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` MODIFY `newsletter` tinyint(1) NOT NULL DEFAULT '0' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` MODIFY `approved` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "information` MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "language` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_history` MODIFY `notify` tinyint(1) NOT NULL DEFAULT '0' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY `shipping` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY `subtract` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` MODIFY `required` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` MODIFY `subtract` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "return_history` MODIFY `notify` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "return_product` MODIFY `opened` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "review` MODIFY `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "voucher` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "zone`  MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "setting` ADD `serialized` tinyint(1) NOT NULL DEFAULT 0 COMMENT '' AFTER value");		
		
		// 1.5.1.3
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tax_rate_to_customer_group` (
			tax_rate_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			customer_group_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			PRIMARY KEY (tax_rate_id, customer_group_id)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tax_rule` (
			tax_rule_id int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
			tax_class_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			tax_rate_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			based varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			priority int(5) NOT NULL DEFAULT '1' COMMENT '',
			PRIMARY KEY (tax_rule_id)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer` ADD token varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER approved");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "option_value` ADD image varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER option_id");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` MODIFY invoice_prefix varchar(26) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_image` ADD sort_order int(3) NOT NULL DEFAULT '0' COMMENT '' AFTER image");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` ADD name varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER geo_zone_id");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` ADD type char(1) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER rate");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate DROP tax_class_id");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` DROP priority");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` MODIFY rate decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT ''");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` DROP description");
		
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_tag` ADD INDEX product_id (product_id)");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_tag` ADD INDEX language_id (language_id)");
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_tag` ADD INDEX tag (tag)");
		
		// Set defaults for new Store Tax Address and Customer Tax Address
		if (empty($settings['config_tax_default'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = 'shipping', `key` = 'config_tax_default', `group` = 'config', store_id = 0");
		}
		if (empty($settings['config_tax_customer'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = 'shipping', `key` = 'config_tax_customer', `group` = 'config', store_id = 0");
		}		
	
	}

	public function modifications() {

		### Additional Changes
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);


		// Settings
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' ORDER BY store_id ASC");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = unserialize($setting['value']);
			}
		}

		## v1.5.1.3
		// Set defaults for new Store Tax Address and Customer Tax Address
		if (empty($settings['config_tax_default'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = 'shipping', `key` = 'config_tax_default', `group` = 'config', store_id = 0");
		}
		if (empty($settings['config_tax_customer'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = 'shipping', `key` = 'config_tax_customer', `group` = 'config', store_id = 0");
		}

		## v1.5.3
		
		// Set defaults for new Voucher Min/Max fields
		if (empty($settings['config_voucher_min'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = '1', `key` = 'config_voucher_min', `group` = 'config', store_id = 0");
		}
		if (empty($settings['config_voucher_max'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = '1000', `key` = 'config_voucher_max', `group` = 'config', store_id = 0");
		}

		// Layout routes now require "%" for wildcard paths
		$layout_route_query = $db->query("SELECT * FROM " . DB_PREFIX . "layout_route");
		
		foreach ($layout_route_query->rows as $layout_route) {
			if (strpos($layout_route['route'], '/') === false) { // If missing the trailing slash, add "/%"
					$db->query("UPDATE " . DB_PREFIX . "layout_route SET route = '" . $layout_route['route'] . "/%' WHERE `layout_route_id` = '" . $layout_route['layout_route_id'] . "'");
			} elseif (strrchr($layout_route['route'], '/') == "/") { // If has the trailing slash, then just add "%"
					$db->query("UPDATE " . DB_PREFIX . "layout_route SET route = '" . $layout_route['route'] . "%' WHERE `layout_route_id` = '" . $layout_route['layout_route_id'] . "'");
			}
		}

		// Customer Group 'name' field moved to new customer_group_description table. Need to loop through and move over.
		$column_query = $db->query("DESC " . DB_PREFIX . "customer_group `name`");

		if ($column_query->num_rows) {
			$customer_group_query = $db->query("SELECT * FROM " . DB_PREFIX . "customer_group");

			$default_language_query = $db->query("SELECT language_id FROM " . DB_PREFIX . "language WHERE code = '" . $settings['config_admin_language'] . "'");

			$default_language_id = $default_language_query->row['language_id'];

			foreach ($customer_group_query->rows as $customer_group) {
				$db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$default_language_id . "', `name` = '" . $db->escape($customer_group['name']) . "' ON DUPLICATE KEY UPDATE customer_group_id = customer_group_id");
			}
			// Comment this for now in case people want to roll back to 1.5.2 from 1.5.3
			// Uncomment it when 1.5.4 is out.
			//$db->query("ALTER TABLE " . DB_PREFIX . "customer_group DROP `name`");			
		}

		// Default to "default" customer group display for registration if this is the first time using this version to avoid registration confusion.
		// In 1.5.2 and earlier, the default install uses "8" as the "Default" customer group
		// In 1.5.3 the default install uses "1" as the "Default" customer group.
		// Since this is an upgrade script and only triggers if the checkboxes aren't selected, I use 8 since that is what people will be upgrading from.
		$query = $db->query("SELECT setting_id FROM " . DB_PREFIX . "setting WHERE `group` = 'config' AND `key` = 'config_customer_group_display'");

		if (!$query->num_rows) {
			$db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = 0, `group` = 'config', `key` = 'config_customer_group_display', `value` = 'a:1:{i:0;s:1:\"8\";}', `serialized` = 1");
		}
	}
}
?>