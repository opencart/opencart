<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade8 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// customer_activity
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_activity' AND COLUMN_NAME = 'activity_id'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `customer_activity_id` = `activity_id` WHERE `customer_activity_id` IS NULL or `customer_activity_id` = ''");
			}

			// Customer Group
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_group' AND COLUMN_NAME = 'name'");

			if ($query->num_rows) {
				$customer_group_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group`");

				foreach ($customer_group_query->rows as $customer_group) {
					$language_query = $this->db->query("SELECT `language_id` FROM `" . DB_PREFIX . "language`");

					foreach ($language_query->rows as $language) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group['customer_group_id'] . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($customer_group['name']) . "'");
					}
				}
			}

			// Affiliate customer merge code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate'");

			if ($query->num_rows) {
				// Removing affiliate and moving to the customer account.
				$config = new \Opencart\System\Engine\Config();

				$setting_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0'");

				foreach ($setting_query->rows as $setting) {
					$config->set($setting['key'], $setting['value']);
				}

				$affiliate_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate`");

				// Customer Affiliate - Company
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_affiliate' AND COLUMN_NAME = 'company' AND COLUMN_TYPE = 'varchar(60)'");

				if (!$query->num_rows) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_affiliate` MODIFY `company` varchar(60) NOT NULL");
				}

				foreach ($affiliate_query->rows as $affiliate) {
					$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `email` = '" . $this->db->escape($affiliate['email']) . "'");

					if (!$customer_query->num_rows) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET `customer_group_id` = '" . (int)$config->get('config_customer_group_id') . "', `language_id` = '" . (int)$config->get('config_customer_group_id') . "', `firstname` = '" . $this->db->escape($affiliate['firstname']) . "', `lastname` = '" . $this->db->escape($affiliate['lastname']) . "', `email` = '" . $this->db->escape($affiliate['email']) . "', `password` = '" . $this->db->escape($affiliate['password']) . "', `newsletter` = '0', `custom_field` = '" . $this->db->escape(json_encode([])) . "', `ip` = '" . $this->db->escape($affiliate['ip']) . "', `status` = '" . $this->db->escape($affiliate['status']) . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");

						$customer_id = $this->db->getLastId();

						$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET `customer_id` = '" . (int)$customer_id . "', `firstname` = '" . $this->db->escape($affiliate['firstname']) . "', `lastname` = '" . $this->db->escape($affiliate['lastname']) . "', `company` = '" . $this->db->escape($affiliate['company']) . "', `address_1` = '" . $this->db->escape($affiliate['address_1']) . "', `address_2` = '" . $this->db->escape($affiliate['address_2']) . "', `city` = '" . $this->db->escape($affiliate['city']) . "', `postcode` = '" . $this->db->escape($affiliate['postcode']) . "', `zone_id` = '" . (int)$affiliate['zone_id'] . "', `country_id` = '" . (int)$affiliate['country_id'] . "', `custom_field` = '" . $this->db->escape(json_encode([])) . "'");
					} else {
						$customer_id = $customer_query->row['customer_id'];
					}

					$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

					if (!$customer_query->num_rows) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate` SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($affiliate['company']) . "', `tracking` = '" . $this->db->escape($affiliate['code']) . "', `commission` = '" . (float)$affiliate['commission'] . "', `tax` = '" . $this->db->escape($affiliate['tax']) . "', `payment` = '" . $this->db->escape($affiliate['payment']) . "', `cheque` = '" . $this->db->escape($affiliate['cheque']) . "', `paypal` = '" . $this->db->escape($affiliate['paypal']) . "', `bank_name` = '" . $this->db->escape($affiliate['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($affiliate['bank_branch_number']) . "', `bank_account_name` = '" . $this->db->escape($affiliate['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($affiliate['bank_account_number']) . "', `status` = '" . (int)(isset($affiliate['approved']) ? $affiliate['approved'] : $affiliate['status']) . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");
					}

					$affiliate_transaction_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_transaction` WHERE `affiliate_id` = '" . (int)$affiliate['affiliate_id'] . "'");

					foreach ($affiliate_transaction_query->rows as $affiliate_transaction) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_transaction` SET `customer_id` = '" . (int)$customer_id . "', `order_id` = '" . (int)$affiliate_transaction['order_id'] . "', `description` = '" . $this->db->escape($affiliate_transaction['description']) . "', `amount` = '" . (float)$affiliate_transaction['amount'] . "', `date_added` = '" . $this->db->escape($affiliate_transaction['date_added']) . "'");

						$this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE `affiliate_transaction_id` = '" . (int)$affiliate_transaction['affiliate_transaction_id'] . "'");
					}

					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `affiliate_id` = '" . (int)$customer_id . "' WHERE `affiliate_id` = '" . (int)$affiliate['affiliate_id'] . "'");
				}
			}

			// Country address_format_id
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "country' AND COLUMN_NAME = 'address_format_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "country` ADD COLUMN `address_format_id` int(11) NOT NULL AFTER `address_format`");				
			}		

			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "address_format'");

			if ($query->num_rows) {
				$address_format_total = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address_format`");

				if (!$address_format_total->row['total']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "address_format` SET `name` = 'Address Format', `address_format` = '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city}, {zone} {postcode}\r\n{country}'");
				}
			}

			// Country
			$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `address_format_id` = '1' WHERE `address_format_id` = '0'");

			// Api
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'name'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "api` SET `name` = `username` WHERE `username` IS NULL or `username` = ''");
			}
			
			// Cart - Subscriptions
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "cart' AND COLUMN_NAME = 'subscription_plan_id'");

			if (!$query->num_rows) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "cart`");
				
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "cart` ADD COLUMN `subscription_plan_id` int(11) NOT NULL AFTER `product_id`");
			}

			// Cart - Override
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "cart' AND COLUMN_NAME = 'override'");

			if (!$query->num_rows) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "cart`");

				$this->db->query("ALTER TABLE `" . DB_PREFIX . "cart` ADD COLUMN `override` tinyint(1) NOT NULL AFTER `quantity`");
			}

			// Cart - Price
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "cart' AND COLUMN_NAME = 'price'");

			if (!$query->num_rows) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "cart`");

				$this->db->query("ALTER TABLE `" . DB_PREFIX . "cart` ADD COLUMN `price` decimal(15,4) NOT NULL AFTER `override`");
			}

			// Coupon - Date Added
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "coupon' AND COLUMN_NAME = 'date_added' AND COLUMN_DEFAULT = '0000-00-00'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "coupon` MODIFY `date_added` date NOT NULL");
			}

			// Currency - Decimal Place
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "currency' AND COLUMN_NAME = 'decimal_place' AND COLUMN_TYPE = 'char(1)'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "currency` MODIFY `decimal_place` int(1) NOT NULL");
			}

			// Customer Search - Category ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_search' AND COLUMN_NAME = 'category_id' AND `IS_NULLABLE` = 'YES'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_search` MODIFY `category_id` int(11) NOT NULL");
			}

			// Event - Code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "event' AND COLUMN_NAME = 'code' AND COLUMN_TYPE = 'varchar(64)'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "event` MODIFY `code` varchar(128) NOT NULL");
			}

			// Extension - Extension
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension' AND COLUMN_NAME = 'extension'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension` ADD COLUMN `extension` varchar(255) NOT NULL AFTER `extension_id`");
			}

			// Extension - Code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension' AND COLUMN_NAME = 'code' AND COLUMN_TYPE = 'varchar(32)'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension` MODIFY `code` varchar(128) NOT NULL");
			}

			// Extension Install - Extension ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension_install' AND COLUMN_NAME = 'extension_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD COLUMN `extension_id` int(11) NOT NULL AFTER `extension_install_id`");
			}

			// Extension Install - Name
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension_install' AND COLUMN_NAME = 'name'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD COLUMN `name` varchar(128) NOT NULL AFTER `extension_download_id`");
			}

			// Extension Install - Code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension_install' AND COLUMN_NAME = 'code'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD COLUMN `code` varchar(255) NOT NULL AFTER `name`");
			}

			// Extension Install - Version
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension_install' AND COLUMN_NAME = 'version'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD COLUMN `version` varchar(255) NOT NULL AFTER `code`");
			}

			// Extension Install - Author
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension_install' AND COLUMN_NAME = 'author'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD COLUMN `author` varchar(255) NOT NULL AFTER `version`");
			}

			// Extension Install - Link
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension_install' AND COLUMN_NAME = 'link'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD COLUMN `link` varchar(255) NOT NULL AFTER `author`");
			}

			// Extension Install - Status
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "extension_install' AND COLUMN_NAME = 'status'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD COLUMN `status` tinyint(1) NOT NULL AFTER `link`");
			}

			// Extension - Google Shopping Category
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "googleshopping_category' AND COLUMN_NAME = 'status'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "extension_install` ADD COLUMN `status` tinyint(1) NOT NULL AFTER `link`");
			}

			// Language - Extension
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "language' AND COLUMN_NAME = 'extension'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "language` ADD COLUMN `extension` varchar(255) NOT NULL AFTER `locale`");
			}

			// Module - Code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "module' AND COLUMN_NAME = 'code' AND COLUMN_TYPE = 'varchar(32)'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "module` MODIFY `code` varchar(64) NOT NULL");
			}

			// Order Product - Master ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_product' AND COLUMN_NAME = 'master_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_product` ADD COLUMN `master_id` int(11) NOT NULL AFTER `product_id`");
			}

			// Addresses
            $query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "address' AND COLUMN_NAME = 'default'");

            if (!$query->num_rows) {
                $this->db->query("ALTER TABLE `" . DB_PREFIX . "address` ADD COLUMN `default` tinyint(1) NOT NULL AFTER `custom_field`");
            }

			// Order Total - Extension
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_total' AND COLUMN_NAME = 'extension'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order_total` ADD COLUMN `extension` varchar(255) NOT NULL AFTER `order_id`");
			}

			// Product - Master ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product' AND COLUMN_NAME = 'master_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD COLUMN `master_id` int(11) NOT NULL AFTER `product_id`");
			}

			// Product - Variant
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product' AND COLUMN_NAME = 'variant'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD COLUMN `variant` text NOT NULL AFTER `location`");
			}

			// Product - Override
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product' AND COLUMN_NAME = 'override'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD COLUMN `override` text NOT NULL AFTER `variant`");
			}

			// Product - Date Available
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product' AND COLUMN_NAME = 'date_available' AND COLUMN_DEFAULT = '0000-00-00'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` MODIFY `date_available` date NOT NULL");
			}

			// Product - Rating
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product' AND COLUMN_NAME = 'rating'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product` ADD COLUMN `rating` int(1) NOT NULL AFTER `minimum`");
			}

			// Product Discount - Date Start
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_discount' AND COLUMN_NAME = 'date_start' AND COLUMN_DEFAULT = '0000-00-00'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_discount` MODIFY `date_start` date NOT NULL");
			}

			// Product Discount - Date End
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_discount' AND COLUMN_NAME = 'date_end' AND COLUMN_DEFAULT = '0000-00-00'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_discount` MODIFY `date_end` date NOT NULL");
			}

			// Product Subscription - Trial Price
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_subscription' AND COLUMN_NAME = 'trial_price'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_subscription` ADDF COLUMN `trial_price` decimal(10,4) NOT NULL");
			}

			// Product Subscription - Price
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_subscription' AND COLUMN_NAME = 'price'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_subscription` ADD COLUMN `price` decimal(10,4) NOT NULL");
			}

			// Product Special - Date Start
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_special' AND COLUMN_NAME = 'date_start' AND COLUMN_DEFAULT = '0000-00-00'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_special` MODIFY `date_start` date NOT NULL");
			}

			// Product Special - Date End
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_special' AND COLUMN_NAME = 'date_end' AND COLUMN_DEFAULT = '0000-00-00'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_special` MODIFY `date_end` date NOT NULL");
			}

			// Return - Date Ordered
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "return' AND COLUMN_NAME = 'date_ordered' AND COLUMN_DEFAULT = '0000-00-00'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "return` MODIFY `date_ordered` date NOT NULL");
			}

			// SEO URL - Key
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "seo_url' AND COLUMN_NAME = 'key'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "seo_url` ADD COLUMN `key` varchar(64) NOT NULL AFTER `language_id`");
			}

			// SEO URL - Value
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "seo_url' AND COLUMN_NAME = 'value'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "seo_url` ADD COLUMN `value` varchar(255) NOT NULL AFTER `key`");
			}

			// SEO URL - Sort Order
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "seo_url' AND COLUMN_NAME = 'sort_order'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "seo_url` ADD COLUMN `sort_order` int(3) NOT NULL AFTER `value`");
			}

			// Subscription Plan - Trial Duration
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription_plan' AND COLUMN_NAME = 'trial_duration' AND COLUMN_TYPE = 'smallint(6)'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription_plan` MODIFY `trial_duration` int(10) NOT NULL");
			}

			// Subscription Plan - Trial Cycle
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription_plan' AND COLUMN_NAME = 'trial_cycle' AND COLUMN_TYPE = 'smallint(6)'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription_plan` MODIFY `trial_cycle` int(10) NOT NULL");
			}

			// Subscription Plan - Duration
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription_plan' AND COLUMN_NAME = 'duration' AND COLUMN_TYPE = 'smallint(6)'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription_plan` MODIFY `duration` int(10) NOT NULL");
			}

			// Subscription Plan - Cycle
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription_plan' AND COLUMN_NAME = 'cycle' AND COLUMN_TYPE = 'smallint(6)'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription_plan` MODIFY `cycle` int(10) NOT NULL");
			}

			// Order Subscription
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'subscription_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `subscription_id` int(11) NOT NULL AFTER `order_id`");
			}

			// Order Transaction
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'transaction_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `transaction_id` varchar(100) NOT NULL AFTER `invoice_prefix`");
			}

			// Order Payment Address ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'payment_address_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `payment_address_id` int(11) NOT NULL AFTER `custom_field`");
			}

			// Order Shipping Address ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'shipping_address_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `shipping_address_id` int(11) NOT NULL AFTER `payment_method`");
			}

			// Order Language Code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'language_code'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `language_code` varchar(5) NOT NULL AFTER `language_id`");
			}

			// Subscription Comment
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'comment'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `comment` text NOT NULL AFTER `date_next`");
			}

			// Subscription Affiliate ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'affiliate_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `affiliate_id` int(11) NOT NULL AFTER `subscription_status_id`");
			}

			// Subscription Marketing ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'marketing_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `marketing_id` int(11) NOT NULL AFTER `affiliate_id`");
			}

			// Subscription Tracking
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'tracking'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `tracking` varchar(64) NOT NULL AFTER `marketing_id`");
			}

			// Subscription Language ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'language_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `language_id` int(11) NOT NULL AFTER `tracking`");
			}

			// Subscription Currency ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'currency_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `currency_id` int(11) NOT NULL AFTER `language_id`");
			}

			// Subscription IP
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'ip'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `ip` varchar(40) NOT NULL AFTER `currency_id`");
			}

			// Subscription Forwarded IP
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'forwarded_ip'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `forwarded_ip` varchar(40) NOT NULL AFTER `ip`");
			}

			// Subscription User Agent
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'user_agent'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `user_agent` varchar(255) NOT NULL AFTER `forwarded_ip`");
			}

			// Subscription Accept Language
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'accept_language'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `accept_language` varchar(255) NOT NULL AFTER `user_agent`");
			}

			// Drop Fields
			$remove = [];

			$remove[] = [
				'table' => 'api',
				'field' => 'name'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'firstname'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'lastname'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'password'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'cart'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'salt'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'approved'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'wishlist'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'address_id'
			];

			$remove[] = [
				'table' => 'customer_activity',
				'field' => 'activity_id'
			];

			$remove[] = [
				'table' => 'customer_group',
				'field' => 'name'
			];

			$remove[] = [
				'table' => 'order',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'language',
				'field' => 'directory'
			];

			$remove[] = [
				'table' => 'location',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'store',
				'field' => 'ssl'
			];

			$remove[] = [
				'table' => 'user',
				'field' => 'salt'
			];

			$remove[] = [
				'table' => 'user_login',
				'field' => 'token'
			];

			$remove[] = [
				'table' => 'user_login',
				'field' => 'total'
			];

			$remove[] = [
				'table' => 'user_login',
				'field' => 'status'
			];

			$remove[] = [
				'table' => 'product',
				'field' => 'viewed'
			];

			$remove[] = [
				'table' => 'seo_url',
				'field' => 'query'
			];

			$remove[] = [
				'table' => 'country',
				'field' => 'address_format'
			];

			$remove[] = [
				'table' => 'cart',
				'field' => 'recurring_id'
			];

			$remove[] = [
				'table' => 'subscription_plan',
				'field' => 'trial_price'
			];

			$remove[] = [
				'table' => 'subscription_plan',
				'field' => 'price'
			];

			$remove[] = [
				'table' => 'subscription_plan_description',
				'field' => 'description'
			];

			$remove[] = [
				'table' => 'theme',
				'field' => 'theme'
			];

			$remove[] = [
				'table' => 'subscription',
				'field' => 'customer_payment_id'
			];

			$remove[] = [
				'table' => 'subscription',
				'field' => 'description'
			];

			$remove[] = [
				'table' => 'subscription',
				'field' => 'name'
			];

			$remove[] = [
				'table' => 'subscription',
				'field' => 'reference'
			];

			foreach ($remove as $result) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $result['table'] . "' AND COLUMN_NAME = '" . $result['field'] . "'");

				if ($query->num_rows) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . $result['table'] . "` DROP `" . $result['field'] . "`");
				}
			}

			// Drop Tables
			$remove = [
				'affiliate',
				'affiliate_activity',
				'affiliate_login',
				'affiliate_transaction',
				'banner_image_description',
				'banner_image_description',
				'banner_image_description',
				'customer_ban_ip',
				'customer_field',
				'customer_payment',
				'modification',
				'order_field',
				'order_custom_field',
				'url_alias'
			];

			foreach ($remove as $table) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "'");

				if ($query->num_rows) {
					$this->db->query("DROP TABLE `" . DB_PREFIX . $table . "`");
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_progress'), 8, 8, 9);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_9', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
