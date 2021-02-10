<?php
namespace Opencart\Application\Model\Upgrade;
class Upgrade1004 extends \Opencart\System\Engine\Model {
	public function upgrade() {

		// affiliate_activity
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate_activity'");

		if ($query->num_rows) {
			$this->db->query("DROP TABLE `" . DB_PREFIX . "affiliate_activity`");
		}

		// customer_activity
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_activity' AND COLUMN_NAME = 'activity_id'");

		if ($query->num_rows) {
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_activity' AND COLUMN_NAME = 'activity_id'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `customer_activity_id` = `activity_id` WHERE `customer_activity_id` IS NULL or `customer_activity_id` = ''");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_activity` DROP `activity_id`");
			} else {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_activity` CHANGE `activity_id` `customer_activity_id` INT(11) NOT NULL AUTO_INCREMENT");
			}
		}

		// customer
		$query = $this->db->query("SELECT `customer_id`, `cart`, `wishlist`, `custom_field` FROM `" . DB_PREFIX . "customer` WHERE `custom_field` LIKE 'a:%' OR `cart` LIKE 'a:%' OR `wishlist` LIKE 'a:%'");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['cart'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `cart` = '" . $this->db->escape(json_encode(unserialize($result['cart']))) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['wishlist'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `wishlist` = '" . $this->db->escape(json_encode(unserialize($result['wishlist']))) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `custom_field` = '" . $this->db->escape(json_encode(unserialize($result['custom_field']))) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
			}
		}

		// address
		$query = $this->db->query("SELECT `address_id`, `custom_field` FROM `" . DB_PREFIX . "address` WHERE `custom_field` LIKE 'a:%'");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `custom_field` = '" . $this->db->escape(json_encode(unserialize($result['custom_field']))) . "' WHERE `address_id` = '" . (int)$result['address_id'] . "'");
			}
		}

		// order
		$query = $this->db->query("SELECT `order_id`, `custom_field`, `payment_custom_field`, `shipping_custom_field` FROM `" . DB_PREFIX . "order` WHERE `custom_field` LIKE 'a:%' OR `payment_custom_field` LIKE 'a:%' OR `shipping_custom_field` LIKE 'a:%'");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `custom_field` = '" . $this->db->escape(json_encode(unserialize($result['custom_field']))) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['payment_custom_field'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_custom_field` = '" . $this->db->escape(json_encode(unserialize($result['payment_custom_field']))) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}

			if (preg_match('/^(a:)/', $result['shipping_custom_field'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `shipping_custom_field` = '" . $this->db->escape(json_encode(unserialize($result['shipping_custom_field']))) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
			}
		}

		// user_group
		$query = $this->db->query("SELECT `user_group_id`, `permission` FROM `" . DB_PREFIX . "user_group`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['permission'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '" . $this->db->escape(json_encode(unserialize($result['permission']))) . "' WHERE `user_group_id` = '" . (int)$result['user_group_id'] . "'");
			}
		}

		// affiliate_activity
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate_activity'");

		if ($query->num_rows) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_activity` WHERE `data` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['data'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "affiliate_activity` SET `data` = '" . $this->db->escape(json_encode(unserialize($result['data']))) . "' WHERE `affiliate_activity_id` = '" . (int)$result['affiliate_activity_id'] . "'");
				}
			}
		}

		// customer_activity
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity` WHERE `data` LIKE 'a:%'");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['data'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `data` = '" . $this->db->escape(json_encode(unserialize($result['data']))) . "' WHERE `customer_activity_id` = '" . (int)$result['customer_activity_id'] . "'");
			}
		}

		// module
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['setting'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `setting` = '" . $this->db->escape(json_encode(unserialize($result['setting']))) . "' WHERE `module_id` = '" . (int)$result['module_id'] . "'");
			}
		}








		// customer
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_group' AND COLUMN_NAME = 'name'");

		if ($query->num_rows) {
			$customer_group_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group`");

			foreach ($customer_group_query->rows as $customer_group) {
				$language_query = $this->db->query("SELECT `language_id` FROM `" . DB_PREFIX . "language`");

				foreach ($language_query->rows as $language) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group['customer_group_id'] . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($customer_group['name']) . "'");
				}
			}

			$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_group` DROP `name`");
		}




		// Api
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'name'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` DROP COLUMN `username`");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` CHANGE COLUMN `name` `username` VARCHAR(64) NOT NULL");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "api` MODIFY `username` VARCHAR(64) NOT NULL AFTER `api_id`");
		}
	}
}
