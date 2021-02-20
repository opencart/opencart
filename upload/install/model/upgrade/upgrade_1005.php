<?php
namespace Opencart\Install\Model\Upgrade;
class Upgrade1005 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// Convert image/data to image/catalog
		$this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "category` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "product_image` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "option_value` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "voucher_theme` SET `image` = REPLACE (image , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = REPLACE (value , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = REPLACE (value , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `description` = REPLACE (description , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `description` = REPLACE (description , 'data/', 'catalog/')");
		$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `description` = REPLACE (description , 'data/', 'catalog/')");

		// Set Product Meta Title default to product name if empty
		$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `meta_title` = `name` WHERE meta_title = ''");
		$this->db->query("UPDATE `" . DB_PREFIX . "category_description` SET `meta_title` = `name` WHERE meta_title = ''");
		$this->db->query("UPDATE `" . DB_PREFIX . "information_description` SET `meta_title` = `title` WHERE meta_title = ''");

		//  Option
		$this->db->query("UPDATE `" . DB_PREFIX . "option` SET `type` = 'radio' WHERE `type` = 'image'");

		// product_option
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_option' AND COLUMN_NAME = 'option_value'");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product_option` SET `option_value` = `value`");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_option` DROP `option_value`");
		}

		// tags
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_tag'");

		if ($query->num_rows) {
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language`");

			foreach ($query->rows as $language) {
				// Get old tags
				$query = $this->db->query("SELECT p.`product_id`, GROUP_CONCAT(DISTINCT pt.`tag` order by pt.`tag` ASC SEPARATOR ',') as `tags` FROM `" . DB_PREFIX . "product` p LEFT JOIN `" . DB_PREFIX . "product_tag` pt ON (p.`product_id` = pt.`product_id`) WHERE pt.`language_id` = '" . (int)$language['language_id'] . "' GROUP BY p.`product_id`");

				if ($query->num_rows) {
					foreach ($query->rows as $row) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET `tag` = '" . $this->db->escape(strtolower($row['tags'])) . "' WHERE `product_id` = '" . (int)$row['product_id'] . "' AND `language_id` = '" . (int)$language['language_id'] . "'");
						$this->db->query("DELETE FROM `" . DB_PREFIX . "product_tag` WHERE `product_id` = '" . (int)$row['product_id'] . "' AND `language_id` = '" . (int)$language['language_id'] . "'");
					}
				}
			}

			$this->db->query("DROP TABLE `" . DB_PREFIX . "product_tag`");
		}

		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'required'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `required`");
		}

		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'position'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `position`");
		}

		// download
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "download' AND COLUMN_NAME = 'remaining'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "download` DROP `remaining`");
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
