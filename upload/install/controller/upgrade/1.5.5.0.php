<?php
class ControllerUpgrade1550 extends Controller {
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
						
		// Sort the categories to take advantage of the nested set model
		$this->repairCategories(0);
	}
	
	// Function to repair any erroneous categories that are not in the category path table.
	public function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category` WHERE `parent_id` = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE `category_id` = '" . (int)$parent_id . "' ORDER BY `level` ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET `category_id` = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', `level` = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}	
}