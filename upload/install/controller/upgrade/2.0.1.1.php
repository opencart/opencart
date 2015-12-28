<?php
class ControllerUpgrade2001 extends Controller {
	public function index() {
		// Settings
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0' ORDER BY `store_id` ASC");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = json_decode($setting['value'], true);
			}
		}

		// Set defaults for new voucher min/max fields if not set
		if (empty($settings['config_voucher_min'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '1', `key` = 'config_voucher_min', `code` = 'config', `store_id` = 0");
		}

		if (empty($settings['config_voucher_max'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '1000', `key` = 'config_voucher_max', `code` = 'config', `store_id` = 0");
		}

		// Update the customer group table
		if (in_array('name', $table_old_data[DB_PREFIX . 'customer_group'])) {
			// Customer Group 'name' field moved to new customer_group_description table. Need to loop through and move over.
			$customer_group_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group`");

			foreach ($customer_group_query->rows as $customer_group) {
				$language_query = $this->db->query("SELECT `language_id` FROM `" . DB_PREFIX . "language`");

				foreach ($language_query->rows as $language) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group['customer_group_id'] . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($customer_group['name']) . "'");
				}
			}

			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_group` DROP `name`");
		}

		// Rename the option_value field to value
		if (in_array('option_value', $table_old_data[DB_PREFIX . 'product_option'])) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` DROP `value`");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` CHANGE `option_value` `value` TEXT");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` DROP `option_value`");
		}

		//  Change any serialized values to json values and restore in the DB
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting`");

		foreach ($query->rows as $result) {
			if ($result['serialized'] && preg_match('/^(a:)/', $result['value'])) {
				$value = unserialize($result['value']);

				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode($value)) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
			}
		}

		// Customer
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['cart'])) {
				$cart = unserialize($result['cart']);

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `cart` = '" . $this->db->escape(json_encode($cart)) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['wishlist'])) {
				$wishlist = unserialize($result['wishlist']);

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `wishlist` = '" . $this->db->escape(json_encode($wishlist)) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$custom_field = unserialize($result['custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}
		}

		// Address
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "address`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$custom_field = unserialize($result['custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `address_id` = '" . (int)$result['address_id'] . "'");
			}
		}

		// Order
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$custom_field = unserialize($result['custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['payment_custom_field'])) {
				$custom_field = unserialize($result['payment_custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['shipping_custom_field'])) {
				$custom_field = unserialize($result['shipping_custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `shipping_custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}
		}

		// User Group
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_group`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['permission'])) {
				$permission = unserialize($result['permission']);

				$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '" . $this->db->escape(json_encode($permission)) . "' WHERE `user_group_id` = '" . (int)$result['user_group_id'] . "'");
			}
		}

		// Activity
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_activity`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['data'])) {
				$data = unserialize($result['data']);

				$this->db->query("UPDATE `" . DB_PREFIX . "affiliate_activity` SET `data` = '" . $this->db->escape(json_encode($data)) . "' WHERE `affiliate_activity_id` = '" . (int)$result['affiliate_activity_id'] . "'");
			}
		}
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['data'])) {
				$data = unserialize($result['data']);

				$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `data` = '" . $this->db->escape(json_encode($data)) . "' WHERE `customer_activity_id` = '" . (int)$result['customer_activity_id'] . "'");
			}
		}	
		
		// Module
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['setting'])) {
				$setting = unserialize($result['setting']);

				$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `setting` = '" . $this->db->escape(json_encode($setting)) . "' WHERE `module_id` = '" . (int)$result['module_id'] . "'");
			}
		}	
	}
}