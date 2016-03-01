<?php
class ModelUpgrade1007 extends Model {
	public function upgrade() {
		// Download
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "download` CHANGE `filename` `filename` varchar(140) NOT NULL");

		// Download
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "modification` CHANGE `xml` `xml` mediumtext NOT NULL");
		
		// Extension
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = 'theme'");
		
		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'theme', `code` = 'theme_default'");
			
			// Setting
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_directory', `value` = 'default'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_status', `value` = '1'");		
		}
				
		// Setting
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_product_limit' WHERE `code` = 'config' AND `key` = 'config_product_limit'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_product_description_length' WHERE `code` = 'config' AND `key` = 'config_product_description_length'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_location_width' WHERE `code` = 'config' AND `key` = 'config_image_location_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_location_height' WHERE `code` = 'config' AND `key` = 'config_image_location_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_cart_width' WHERE `code` = 'config' AND `key` = 'config_image_cart_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_cart_height' WHERE `code` = 'config' AND `key` = 'config_image_cart_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_wishlist_width' WHERE `code` = 'config' AND `key` = 'config_image_wishlist_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_wishlist_height' WHERE `code` = 'config' AND `key` = 'config_image_wishlist_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_category_width' WHERE `code` = 'config' AND `key` = 'config_image_category_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_category_height' WHERE `code` = 'config' AND `key` = 'config_image_category_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_thumb_width' WHERE `code` = 'config' AND `key` = 'config_image_thumb_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_thumb_height' WHERE `code` = 'config' AND `key` = 'config_image_thumb_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_popup_width' WHERE `code` = 'config' AND `key` = 'config_image_popup_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_popup_height' WHERE `code` = 'config' AND `key` = 'config_image_popup_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_product_width' WHERE `code` = 'config' AND `key` = 'config_image_product_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default__image_product_height' WHERE `code` = 'config' AND `key` = 'config_image_product_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_additional_width' WHERE `code` = 'config' AND `key` = 'config_image_additional_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_additional_height' WHERE `code` = 'config' AND `key` = 'config_image_additional_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_related_width' WHERE `code` = 'config' AND `key` = 'config_image_related_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_related_height' WHERE `code` = 'config' AND `key` = 'config_image_related_height'");
		
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_compare_width' WHERE `code` = 'config' AND `key` = 'config_image_compare_width'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_image_compare_height' WHERE `code` = 'config' AND `key` = 'config_image_compare_height'");
	
		// Paypal Express
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "paypal_order_transaction' AND COLUMN_NAME = 'parent_transaction_id'");
		
		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "paypal_order_transaction` CHANGE `parent_transaction_id` `parent_id` CHAR(20) NOT NULL");	
		}
	}
}