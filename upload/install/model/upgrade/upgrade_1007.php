<?php
namespace Opencart\Install\Model\Upgrade;
class Upgrade1007 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// Banners and image path replacment

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

		// Banner
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "banner_image' AND COLUMN_NAME = 'language_id'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image` ADD `language_id` INT(11) NOT NULL AFTER `banner_id`");
		}

		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "banner_image' AND COLUMN_NAME = 'title'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "banner_image` ADD `title` VARCHAR(64) NOT NULL AFTER `language_id`");
		}

		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "banner_image_description'");

		if ($query->num_rows) {
			$banner_image_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image`");

			foreach ($banner_image_query->rows as $banner_image) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "banner_image` WHERE banner_image_id = '" . (int)$banner_image['banner_image_id'] . "'");

				$banner_image_description_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "banner_image_description` WHERE `banner_image_id` = '" . (int)$banner_image['banner_image_id'] . "'");

				foreach ($banner_image_description_query->rows as $banner_image_description) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "banner_image` SET `banner_id` = '" . (int)$banner_image['banner_id'] . "', `language_id` = '" . (int)$banner_image_description['language_id'] . "', `title` = '" . $this->db->escape($banner_image_description['title']) . "', `link` = '" . $this->db->escape($banner_image['link']) . "', `image` = '" . $this->db->escape($banner_image['image']) . "', `sort_order` = '" . (int)$banner_image['sort_order'] . "'");
				}
			}

			$this->db->query("DROP TABLE `" . DB_PREFIX . "banner_image_description`");
		}
	}
}
