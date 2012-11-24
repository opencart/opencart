<?php
class ModelUpgrade extends Model {
	public function mysql() {
		$db = new DB('mysql', DB_HOSTNAME, DB_USERNAME, DB_PASSWORD);
		
		// Settings
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' ORDER BY store_id ASC");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = unserialize($setting['value']);
			}
		}

		// 1.5.1
		$db->query("ALTER TABLE `" . DB_PREFIX . "affiliate` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "affiliate` MODIFY `approved` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "banner` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "category` MODIFY `top` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "category` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "country` MODIFY `postcode_required` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "country` MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "coupon` MODIFY `logged` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "coupon` MODIFY `shipping` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "coupon` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "currency` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "customer` MODIFY `newsletter` tinyint(1) NOT NULL DEFAULT '0' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "customer` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "customer` MODIFY `approved` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "information` MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "language` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "order_history` MODIFY `notify` tinyint(1) NOT NULL DEFAULT '0' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY `shipping` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY `subtract` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "product_option` MODIFY `required` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "product_option_value` MODIFY `subtract` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "return_history` MODIFY `notify` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "return_product` MODIFY `opened` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "review` MODIFY `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "user` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "voucher` MODIFY `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "zone`  MODIFY `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "setting` ADD `serialized` tinyint(1) NOT NULL DEFAULT 0 COMMENT '' AFTER value");		
		
		// 1.5.1.3
		$db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tax_rate_to_customer_group` (
			tax_rate_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			customer_group_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			PRIMARY KEY (tax_rate_id, customer_group_id)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "tax_rule` (
			tax_rule_id int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
			tax_class_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			tax_rate_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			based varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			priority int(5) NOT NULL DEFAULT '1' COMMENT '',
			PRIMARY KEY (tax_rule_id)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$db->query("ALTER TABLE `" . DB_PREFIX . "customer` ADD token varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER approved");
		$db->query("ALTER TABLE `" . DB_PREFIX . "option_value` ADD image varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER option_id");
		$db->query("ALTER TABLE `" . DB_PREFIX . "order` MODIFY invoice_prefix varchar(26) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin");
		$db->query("ALTER TABLE `" . DB_PREFIX . "product_image` ADD sort_order int(3) NOT NULL DEFAULT '0' COMMENT '' AFTER image");
		$db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` ADD name varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER geo_zone_id");
		$db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` ADD type char(1) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER rate");
		$db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate DROP tax_class_id");
		$db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` DROP priority");
		$db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` MODIFY rate decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT ''");
		$db->query("ALTER TABLE `" . DB_PREFIX . "tax_rate` DROP description");
		
		$db->query("ALTER TABLE `" . DB_PREFIX . "product_tag` ADD INDEX product_id (product_id)");
		$db->query("ALTER TABLE `" . DB_PREFIX . "product_tag` ADD INDEX language_id (language_id)");
		$db->query("ALTER TABLE `" . DB_PREFIX . "product_tag` ADD INDEX tag (tag)");
		
		// Set defaults for new Store Tax Address and Customer Tax Address
		if (empty($settings['config_tax_default'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = 'shipping', `key` = 'config_tax_default', `group` = 'config', store_id = 0");
		}
		if (empty($settings['config_tax_customer'])) {
			$db->query("INSERT INTO " . DB_PREFIX . "setting SET value = 'shipping', `key` = 'config_tax_customer', `group` = 'config', store_id = 0");
		}	
		
		// 1.5.2
		$db->query("CREATE TABLE IF NOT EXISTS oc_customer_ip_blacklist (
			customer_ip_blacklist_id int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
			ip varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			PRIMARY KEY (customer_ip_blacklist_id),
			INDEX ip (ip)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$db->query("CREATE TABLE IF NOT EXISTS oc_order_fraud (
			order_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			customer_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			country_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			country_code varchar(2) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			high_risk_country varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			distance int(11) NOT NULL DEFAULT 0 COMMENT '',
			ip_region varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_city varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_latitude decimal(10,6) NOT NULL DEFAULT '' COMMENT '',
			ip_longitude decimal(10,6) NOT NULL DEFAULT '' COMMENT '',
			ip_isp varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_org varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_asnum int(11) NOT NULL DEFAULT 0 COMMENT '',
			ip_user_type varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_country_confidence varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_region_confidence varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_city_confidence varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_postal_confidence varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_postal_code varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_accuracy_radius int(11) NOT NULL DEFAULT 0 COMMENT '',
			ip_net_speed_cell varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_metro_code int(3) NOT NULL DEFAULT 0 COMMENT '',
			ip_area_code int(3) NOT NULL DEFAULT 0 COMMENT '',
			ip_time_zone varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_region_name varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_domain varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_country_name varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_continent_code varchar(2) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ip_corporate_proxy varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			anonymous_proxy varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			proxy_score int(3) NOT NULL DEFAULT 0 COMMENT '',
			is_trans_proxy varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			free_mail varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			carder_email varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			high_risk_username varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			high_risk_password varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			bin_match varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			bin_country varchar(2) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			bin_name_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			bin_name varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			bin_phone_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			bin_phone varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			customer_phone_in_billing_location varchar(8) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ship_forward varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			city_postal_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			ship_city_postal_match varchar(3) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			score decimal(10,5) NOT NULL DEFAULT '' COMMENT '',
			explanation text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			risk_score decimal(10,5) NOT NULL DEFAULT '' COMMENT '',
			queries_remaining int(11) NOT NULL DEFAULT 0 COMMENT '',
			maxmind_id varchar(8) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			error text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			date_added datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '',
			PRIMARY KEY (order_id)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$db->query("CREATE TABLE IF NOT EXISTS oc_order_voucher (
			order_voucher_id int(11) NOT NULL DEFAULT 0 COMMENT '' auto_increment,
			order_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			voucher_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			description varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			code varchar(10) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			from_name varchar(64) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			from_email varchar(96) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			to_name varchar(64) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			to_email varchar(96) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			voucher_theme_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			message text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			amount decimal(15,4) NOT NULL DEFAULT '' COMMENT '',
			PRIMARY KEY (order_voucher_id)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$db->query("ALTER TABLE oc_order ADD shipping_code varchar(128) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER shipping_method");
		$db->query("ALTER TABLE oc_order ADD payment_code varchar(128) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER payment_method");
		$db->query("ALTER TABLE oc_order ADD forwarded_ip varchar(15) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER ip");
		$db->query("ALTER TABLE oc_order ADD user_agent varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER forwarded_ip");
		$db->query("ALTER TABLE oc_order ADD accept_language varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER user_agent");
		$db->query("ALTER TABLE oc_order DROP reward");
		
		$db->query("ALTER TABLE oc_order_product ADD reward int(8) NOT NULL DEFAULT 0 COMMENT '' AFTER tax");
		
		$db->query("ALTER TABLE oc_product MODIFY `weight` decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT ''");
		$db->query("ALTER TABLE oc_product MODIFY `length` decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT ''");
		$db->query("ALTER TABLE oc_product MODIFY `width` decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT ''");
		$db->query("ALTER TABLE oc_product MODIFY `height` decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT ''");
		
		$db->query("ALTER TABLE `oc_return` ADD `product_id` int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER `order_id`");
		$db->query("ALTER TABLE `oc_return` ADD `product` varchar(255) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER `telephone`");
		$db->query("ALTER TABLE `oc_return` ADD `model` varchar(64) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER `product`");
		$db->query("ALTER TABLE `oc_return` ADD `quantity` int(4) NOT NULL DEFAULT '0' COMMENT '' AFTER `model`");
		$db->query("ALTER TABLE `oc_return` ADD `opened` tinyint(1) NOT NULL DEFAULT '0' COMMENT '' AFTER `quantity`");
		$db->query("ALTER TABLE `oc_return` ADD `return_reason_id` int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER `opened`");
		$db->query("ALTER TABLE `oc_return` ADD `return_action_id` int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER `return_reason_id`");
		
		$db->query("DROP TABLE IF EXISTS oc_return_product");
		
		$db->query("ALTER TABLE oc_tax_rate_to_customer_group DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		// Disable Category Module to force user to reenable with new settings to avoid php error
		$db->query("UPDATE `oc_setting` SET `value` = replace(`value`, 's:6:\"status\";s:1:\"1\"', 's:6:\"status\";s:1:\"0\"') WHERE `key` = 'category_module'");
		
		// 1.5.2.2
		
		// Disable UPS Extension to force user to reenable with new settings to avoid php error
		$db->query("UPDATE `oc_setting` SET `value` = 0 WHERE `key` = 'ups_status'");
		
		$db->query("CREATE TABLE IF NOT EXISTS oc_customer_group_description (
			customer_group_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			language_id int(11) NOT NULL DEFAULT 0 COMMENT '',
			name varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			description text NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin,
			PRIMARY KEY (customer_group_id, language_id)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$db->query("ALTER TABLE oc_address ADD company_id varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER company");
		$db->query("ALTER TABLE oc_address ADD tax_id varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER company_id");
		$db->query("ALTER TABLE oc_address DROP company_no");
		$db->query("ALTER TABLE oc_address DROP company_tax");
		
		$db->query("ALTER TABLE oc_customer_group ADD approval int(1) NOT NULL DEFAULT 0 COMMENT '' AFTER customer_group_id");
		$db->query("ALTER TABLE oc_customer_group ADD company_id_display int(1) NOT NULL DEFAULT 0 COMMENT '' AFTER approval");
		$db->query("ALTER TABLE oc_customer_group ADD company_id_required int(1) NOT NULL DEFAULT 0 COMMENT '' AFTER company_id_display");
		$db->query("ALTER TABLE oc_customer_group ADD tax_id_display int(1) NOT NULL DEFAULT 0 COMMENT '' AFTER company_id_required");
		$db->query("ALTER TABLE oc_customer_group ADD tax_id_required int(1) NOT NULL DEFAULT 0 COMMENT '' AFTER tax_id_display");
		$db->query("ALTER TABLE oc_customer_group ADD sort_order int(3) NOT NULL DEFAULT 0 COMMENT '' AFTER tax_id_required");
		
		### This line is executed using php in the upgrade model file so we dont lose names
		#ALTER TABLE oc_customer_group DROP name;
		
		$db->query("ALTER TABLE `oc_order` ADD payment_company_id varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER payment_company");
		$db->query("ALTER TABLE `oc_order` ADD payment_tax_id varchar(32) NOT NULL DEFAULT '' COMMENT '' COLLATE utf8_bin AFTER payment_company_id");
		$db->query("ALTER TABLE `oc_information` ADD bottom int(1) NOT NULL DEFAULT '1' COMMENT '' AFTER information_id");
		
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
		
		
		
		// 1.5.4
		$db->query("CREATE TABLE IF NOT EXISTS `oc_customer_online` (
		  `ip` varchar(40) COLLATE utf8_bin NOT NULL,
		  `customer_id` int(11) NOT NULL,
		  `url` text COLLATE utf8_bin NOT NULL,
		  `referer` text COLLATE utf8_bin NOT NULL,
		  `date_added` datetime NOT NULL,
		  PRIMARY KEY (`ip`)
		) DEFAULT CHARSET=utf8 COLLATE=utf8_bin");
		
		$db->query("UPDATE `oc_setting` set `group` = replace(`group`, 'alertpay', 'payza')");
		$db->query("UPDATE `oc_setting` set `key` = replace(`key`, 'alertpay', 'payza')");
		$db->query("UPDATE `oc_order` set `payment_method` = replace(`payment_method`, 'AlertPay', 'Payza')");
		$db->query("UPDATE `oc_order` set `payment_code` = replace(`payment_code`, 'alertpay', 'payza')");
		
		$db->query("ALTER TABLE `oc_affiliate` ADD `salt` varchar(9) COLLATE utf8_bin NOT NULL DEFAULT '' after `password`");
		$db->query("ALTER TABLE `oc_category ADD `left` int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER `parent_id`");
		$db->query("ALTER TABLE `oc_category ADD `right` int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER `left`");
		$db->query("ALTER TABLE `oc_customer` ADD `salt` varchar(9) COLLATE utf8_bin NOT NULL DEFAULT '' AFTER `password`");
		$db->query("ALTER TABLE `oc_customer` MODIFY `ip` varchar(40) NOT NULL");
		$db->query("ALTER TABLE `oc_customer_ip` MODIFY `ip` varchar(40) NOT NULL");
		$db->query("ALTER TABLE `oc_customer_ip_blacklist` MODIFY `ip` varchar(40) NOT NULL");
		$db->query("ALTER TABLE `oc_order` MODIFY `ip` varchar(40) NOT NULL");
		$db->query("ALTER TABLE `oc_order` MODIFY `forwarded_ip` varchar(40) NOT NULL");
		$db->query("ALTER TABLE `oc_order_product` MODIFY `model` varchar(64) NOT NULL");
		$db->query("ALTER TABLE `oc_product` ADD `ean` varchar(12) COLLATE utf8_bin NOT NULL DEFAULT '' AFTER `upc`");
		$db->query("ALTER TABLE `oc_product` ADD `jan` varchar(12) COLLATE utf8_bin NOT NULL DEFAULT '' AFTER `ean`");
		$db->query("ALTER TABLE `oc_product` ADD `isbn` varchar(12) COLLATE utf8_bin NOT NULL DEFAULT '' AFTER `jan`");
		$db->query("ALTER TABLE `oc_product` ADD `mpn` varchar(12) COLLATE utf8_bin NOT NULL DEFAULT '' AFTER `isbn`");
		$db->query("ALTER TABLE `oc_product_description` ADD `tag` text COLLATE utf8_bin NOT NULL DEFAULT '' AFTER `meta_keyword`");
		$db->query("ALTER TABLE `oc_product_description` ADD FULLTEXT (`description`)");
		$db->query("ALTER TABLE `oc_product_description` ADD FULLTEXT (`tag`)");
		$db->query("ALTER TABLE `oc_user` ADD `salt` varchar(9) COLLATE utf8_bin NOT NULL DEFAULT '' after `password`");
		$db->query("ALTER TABLE `oc_user` MODIFY `password` varchar(40) NOT NULL");
		$db->query("ALTER TABLE `oc_user` MODIFY `ip` varchar(40) NOT NULL");
		
		// Sort the categories to take advantage of the nested set model
		$this->path(0, 0);
	}
	
	protected function path($category_id = 0, $level) {
		$this->db->query("UPDATE " . DB_PREFIX . "category SET `left` = '" . (int)$level++ . "' WHERE category_id = '" . (int)$category_id . "'");
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$category_id . "' ORDER BY sort_order");
		
		foreach ($query->rows as $result) {
			$level = $this->path($result['category_id'], $level);
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "category SET `right` = '" . (int)$level++ . "' WHERE category_id = '" . (int)$category_id . "'");
	
		return $level;
	}	
}
?>