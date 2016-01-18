<?php
class ModelUpgrade1001 extends Model {
	public function upgrade() {
		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting`");

		foreach ($query->rows as $result) {
			if ($result['serialized'] && preg_match('/^(a:)/', $result['value'])) {
				$value = unserialize($result['value']);

				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode($value)) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
			}
		}

		// customer
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

		// address
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "address`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['custom_field'])) {
				$custom_field = unserialize($result['custom_field']);

				$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `custom_field` = '" . $this->db->escape(json_encode($custom_field)) . "' WHERE `address_id` = '" . (int)$result['address_id'] . "'");
			}
		}

		// order
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

		// user_group
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "user_group`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['permission'])) {
				$permission = unserialize($result['permission']);

				$this->db->query("UPDATE `" . DB_PREFIX . "user_group` SET `permission` = '" . $this->db->escape(json_encode($permission)) . "' WHERE `user_group_id` = '" . (int)$result['user_group_id'] . "'");
			}
		}

		// affiliate_activity
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_activity`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['data'])) {
				$data = unserialize($result['data']);

				$this->db->query("UPDATE `" . DB_PREFIX . "affiliate_activity` SET `data` = '" . $this->db->escape(json_encode($data)) . "' WHERE `affiliate_activity_id` = '" . (int)$result['affiliate_activity_id'] . "'");
			}
		}
		
		// customer_activity
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['data'])) {
				$data = unserialize($result['data']);

				$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `data` = '" . $this->db->escape(json_encode($data)) . "' WHERE `customer_activity_id` = '" . (int)$result['customer_activity_id'] . "'");
			}
		}	
		
		// module
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "module`");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['setting'])) {
				$setting = unserialize($result['setting']);

				$this->db->query("UPDATE `" . DB_PREFIX . "module` SET `setting` = '" . $this->db->escape(json_encode($setting)) . "' WHERE `module_id` = '" . (int)$result['module_id'] . "'");
			}
		}	

	}
}