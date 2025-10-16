<?php
class ModelUpgrade1004 extends Model {
	public function upgrade() {
		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'required'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `required`");
		}

		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'position'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` DROP `position`");
		}

		// custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "custom_field' AND COLUMN_NAME = 'status'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` ADD `status` tinyint(1) NOT NULL AFTER `location`");
		}

		// custom_field
		$this->db->query("ALTER TABLE `" . DB_PREFIX . "custom_field` CHANGE `location` `location` varchar(10) NOT NULL");

		// order_custom_field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order_field'");

		if ($query->num_rows) {
			$order_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_field`");

			foreach ($order_field_query->rows as $result) {
				$order_custom_field_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_custom_field` WHERE `order_id` = '" . (int)$result['order_id'] . "' AND custom_field_id = '" . (int)$result['custom_field_id'] . "' AND custom_field_value_id = '" . (int)$result['custom_field_value_id'] . "' AND `name` = '" . $this->db->escape($result['name']) . "' AND `value` = '" . $this->db->escape($result['value']) . "'");

				if (!$order_custom_field_query->num_rows) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_custom_field` SET `order_id` = '" . (int)$result['order_id'] . "', custom_field_id = '" . (int)$result['custom_field_id'] . "', custom_field_value_id = '" . (int)$result['custom_field_value_id'] . "', `name` = '" . $this->db->escape($result['name']) . "', `value` = '" . $this->db->escape($result['value']) . "'");
				}
			}

			$this->db->query("DROP TABLE `" . DB_PREFIX . "order_field`");
		}

		// download
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "download' AND COLUMN_NAME = 'remaining'");

		if ($query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "download` DROP `remaining`");
		}

		// information_description
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "information_description' AND COLUMN_NAME = 'meta_title'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "information_description` ADD `meta_title` varchar(255) NOT NULL AFTER `description`");
		}

		// information_description
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "information_description' AND COLUMN_NAME = 'meta_description'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "information_description` ADD `meta_description` varchar(255) NOT NULL AFTER `meta_title`");
		}

		// information_description
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "information_description' AND COLUMN_NAME = 'meta_keyword'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "information_description` ADD `meta_keyword` varchar(255) NOT NULL AFTER `meta_description`");
		}

		// order
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'marketing_id'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `marketing_id` int(11) NOT NULL AFTER `commission`");
		}

		// order
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'tracking'");

		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `tracking` varchar(64) NOT NULL AFTER `marketing_id`");
		}

		// Update some new default settings
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0'");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = json_decode($setting['value'], true);
			}
		}

		// Convert _smtp_ to _mail_smtp_
		if (empty($settings['config_mail_smtp_hostname']) && !empty($settings['config_smtp_host'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '" . $settings['config_smtp_host'] . "', `key` = 'config_mail_smtp_hostname', `code` = 'config', `store_id` = 0");
		}
		if (empty($settings['config_mail_smtp_username']) && !empty($settings['config_smtp_username'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '" . $settings['config_smtp_username'] . "', `key` = 'config_mail_smtp_username', `code` = 'config', `store_id` = 0");
		}
		if (empty($settings['config_mail_smtp_password']) && !empty($settings['config_smtp_password'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '" . $settings['config_smtp_password'] . "', `key` = 'config_mail_smtp_password', `code` = 'config', `store_id` = 0");
		}
		if (empty($settings['config_mail_smtp_port']) && !empty($settings['config_smtp_port'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '" . $settings['config_smtp_port'] . "', `key` = 'config_mail_smtp_port', `code` = 'config', `store_id` = 0");
		}
		if (empty($settings['config_mail_smtp_timeout']) && !empty($settings['config_smtp_timeout'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `value` = '" . $settings['config_smtp_timeout'] . "', `key` = 'config_mail_smtp_timeout', `code` = 'config', `store_id` = 0");
		}

		// setting
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_meta_title'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = 'config_meta_title', `value` = '" . $this->db->escape($settings['config_name']) . "', `code` = 'config', `serialized` = '0', `store_id` = 0");
		}

		// Convert 1.5.x core module format to 2.x (core modules only)
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE serialized = '1'");

		foreach ($query->rows as $result) {
			if ($result['serialized']) {
				$value = json_decode($result['value'], true);

				$module_data = array();
				if (in_array($result['code'], array('latest', 'bestseller', 'special', 'featured'))) {
					if ($value) {
						foreach ($value as $k => $v) {

							// Since 2.x doesn't look good with modules as side boxes, set to content bottom
							if ($v['position'] == 'column_left' || $v['position'] == 'column_right') {
								$v['position'] = 'content_bottom';
							}

							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];
							if (isset($v['image_width'])) {	$module_data['width'] = $v['image_width']; }
							if (isset($v['image_height'])) { $module_data['height'] = $v['image_height']; }
							if (isset($v['limit'])) { $module_data['limit'] = $v['limit']; } else { $module_data['limit'] = 4; }

							if ($result['code'] == 'featured') {
								foreach ($query->rows as $result2) {
									if ($result2['key'] == 'featured_product') {
										$module_data['product'] = explode(",", $result2['value']);
										$module_data['limit'] = 4;
										break;
									} else {
										$featured_product_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `key` = 'featured_product'");
										if ($featured_product_query->num_rows) {
											$module_data['product'] = explode(",", $featured_product_query->row['value']);
											$module_data['limit'] = 4;
										}
									}
								}
							}

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` (`name`, `code`, `setting`) values ('" . $this->db->escape($result['key']) . '_' . $k . "', '" . $this->db->escape($result['code']) . "', '" . $this->db->escape(json_encode($module_data)) . "')");
							$module_id = $this->db->getLastId();
							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` (`layout_id`, `code`, `position`, `sort_order`) values ('" . (int)$v['layout_id'] . "', '" . ($result['code'] . '.' . $module_id)  . "', '" . $this->db->escape($v['position']) . "', '" . (int)$v['sort_order'] . "')");
							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . $result['store_id'] . "' AND `code` = '" . $result['code'] . "'");
						}
					} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
					}
				} elseif (in_array($result['code'], array('category', 'account', 'affiliate', 'filter'))) {
					foreach ($value as $k => $v) {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . $result['store_id'] . "' AND `code` = '" . $result['code'] . "'");
						$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET store_id = '" . $result['store_id'] . "', `code` = '" . $result['code'] . "', `key` = '" . ($result['code'] . '_status') . "', value = 1");
						if ($v['status']) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` (`layout_id`, `code`, `position`, `sort_order`) values ('" . (int)$v['layout_id'] . "', '" . ($result['code'])  . "', '" . $this->db->escape($v['position']) . "', '" . (int)$v['sort_order'] . "')");
						}
					}
				} elseif (in_array($result['code'], array('banner', 'carousel', 'slideshow'))) {
					if ($value) {
						foreach ($value as $k => $v) {
							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];
							$module_data['banner_id'] = $v['banner_id'];
							if (isset($v['image_width'])) {	$module_data['width'] = $v['image_width']; }
							if (isset($v['image_height'])) { $module_data['height'] = $v['image_height']; }
							if (isset($v['width'])) {	$module_data['width'] = $v['width']; }
							if (isset($v['height'])) {	$module_data['height'] = $v['height']; }

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` (`name`, `code`, `setting`) values ('" . $this->db->escape($result['key']) . '_' . $k . "', '" . $this->db->escape($result['code']) . "', '" . $this->db->escape(json_encode($module_data)) . "')");
							$module_id = $this->db->getLastId();
							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` (`layout_id`, `code`, `position`, `sort_order`) values ('" . (int)$v['layout_id'] . "', '" . ($result['code'] . '.' . $module_id)  . "', '" . $this->db->escape($v['position']) . "', '" . (int)$v['sort_order'] . "')");
							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . $result['store_id'] . "' AND `code` = '" . $result['code'] . "'");
						}
					} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
					}
				} elseif (in_array($result['code'], array('welcome'))) {
					if ($value) {
						// Install HTML module if not already installed
						$html_query = $this->db->query("SELECT count(*) FROM " . DB_PREFIX . "extension WHERE code = 'html'");
						if ($html_query->row['count(*)'] == '0') {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'module', `code` = 'html'");
						}
						$result['code'] = 'html';
						foreach ($value as $k => $v) {
							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];
							foreach($v['description'] as $language_id => $description) {
								$module_data['module_description'][$language_id]['title'] = '';
								$module_data['module_description'][$language_id]['description'] = str_replace('image/data', 'image/catalog', $description);
							}

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` (`name`, `code`, `setting`) values ('" . $this->db->escape($result['key']) . '_' . $k . "', '" . $this->db->escape($result['code']) . "', '" . $this->db->escape(json_encode($module_data)) . "')");
							$module_id = $this->db->getLastId();
							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` (`layout_id`, `code`, `position`, `sort_order`) values ('" . (int)$v['layout_id'] . "', '" . ($result['code'] . '.' . $module_id)  . "', '" . $this->db->escape($v['position']) . "', '" . (int)$v['sort_order'] . "')");
							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . $result['store_id'] . "' AND `code` = 'welcome'");
						}
					} else {
						//$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = 'welcome'");
					}
				} else {
					// could add code for other types here
					// If module has position, but not a core module, then disable it because it likely isn't compatible
					if (!empty($value)) {
						foreach ($value as $k => $v) {
							if (isset($v['position'])) {
								$module_data = $v;
								$module_data['name'] = ($result['key'] . '_' . $k);
								$module_data['status'] = '0'; // Disable non-core modules

								$this->db->query("INSERT INTO `" . DB_PREFIX . "module` (`name`, `code`, `setting`) values ('" . $this->db->escape($result['key']) . '_' . $k . "', '" . $this->db->escape($result['code']) . "', '" . $this->db->escape(json_encode($module_data)) . "')");
								//$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
								//$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
								//$this->db->query("DELETE FROM `" . DB_PREFIX . "module` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
							}
						}
					} else {
						$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode($value)) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
					}
				}
			} else {

			}
		}
	}
}