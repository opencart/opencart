<?php
namespace Opencart\Application\Model\Upgrade;
class Upgrade1002 extends \Opencart\System\Engine\Model {
	public function upgrade() {
		// Settings extensions and module changes

		// Remove the `group` field
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "setting' AND COLUMN_NAME = 'group'");

		if ($query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = `group` WHERE `code` IS NULL or `code` = ''");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "setting` DROP `group`");
		}

		// Un-serialize values and change to JSON
		$query = $this->db->query("SELECT `setting_id`, `value` FROM `" . DB_PREFIX . "setting` WHERE `serialized` = '1' AND `value` LIKE 'a:%'");

		foreach ($query->rows as $result) {
			if (preg_match('/^(a:)/', $result['value'])) {
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode(unserialize($result['value']))) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
			}
		}

		// Get the default settings
		$settings = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0'");

		foreach ($query->rows as $setting) {
			if (!$setting['serialized']) {
				$settings[$setting['key']] = $setting['value'];
			} else {
				$settings[$setting['key']] = json_decode($setting['value'], true);
			}
		}

		// Add missing keys and values
		$missing = [
			'config_meta_title'                 => $settings['config_name'],
			'config_product_description_length' => 100,
			'config_pagination'                 => 10,
			'config_encryption'                 => hash('sha512', token(32)),
			'config_voucher_min'                => 1,
			'config_voucher_max'                => 1000,
			'config_fraud_status_id'            => 8,
			'config_api_id'                     => 1,
			'config_mail_smtp_hostname'         => $settings['config_smtp_host'],
			'config_mail_smtp_username'         => $settings['config_smtp_username'],
			'config_mail_smtp_password'         => $settings['config_smtp_password'],
			'config_mail_smtp_port'             => $settings['config_smtp_port'],
			'config_mail_smtp_timeout'          => $settings['config_smtp_timeout'],
			'config_pagination_admin'           => $settings['config_limit_admin'],
		];

		foreach ($missing as $key => $value) {
			$query = $this->db->query("SELECT setting_id FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0' AND `key` = '" . $this->db->escape($key) . "'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "', `code` = 'config', `serialized` = '0', `store_id` = '0'");
			}
		}

		// Add missing keys and serialized values
		$missing = [
			'config_complete_status'   => [5],
			'config_processing_status' => [2],
		];

		foreach ($missing as $key => $value) {
			$query = $this->db->query("SELECT setting_id FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0' AND `key` = '" . $this->db->escape($key) . "'");

			if (!$query->num_rows) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(json_encode($value)) . "', `code` = 'config', `serialized` = '1', `store_id` = '0'");
			}
		}

		// force some settings to prevent errors
		if ($settings['config_template'] == 'basic_defalt') {
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'default' WHERE `key` = 'config_template'");
		}


		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '1' WHERE `key` = 'config_error_display'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '1' WHERE `key` = 'config_error_log'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '0' WHERE `key` = 'config_compression'");

		// Update some language settings
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'en-gb' WHERE `key` = 'config_language' AND `value` = 'en'");
		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'en-gb' WHERE `key` = 'config_language_admin' AND `value` = 'en'");
		$this->db->query("UPDATE `" . DB_PREFIX . "language` SET `code` = 'en-gb' WHERE `code` = 'en'");

		$this->cache->delete('language');





		$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `key` = 'payment_free_checkout_order_status_id' WHERE `key` = 'free_checkout_order_status_id'");





		// Extension
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = 'theme'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'theme', `code` = 'default'");

			// Setting
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_directory', `value` = 'default'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code` = 'theme_default', `key` = 'theme_default_status', `value` = '1'");
		}



		// Get all setting columns from extension table
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension`");

		foreach ($query->rows as $extension) {
			//get all setting from setting table
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $extension['code'] . "'");

			if ($query->num_rows) {
				foreach ($query->rows as $result) {
					//update old column name to adding prefix before the name
					if ($result['code'] == $extension['code'] && $result['code'] != $extension['type'] . "_" . $extension['code'] && $extension['type'] != "theme") {
						$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = '" . $this->db->escape($extension['type'] . "_" . $extension['code']) . "', `key` = '" . $this->db->escape($extension['type'] . "_" . $result['key']) . "', `value` = '" . $this->db->escape($result['value']) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
					}
				}
			}
		}





		// Convert 1.5.x core module format to 2.x (core modules only)
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `serialized` = '1'");

		foreach ($query->rows as $result) {
			if ($result['serialized']) {
				$value = json_decode($result['value'], true);

				$module_data = [];

				if (in_array($result['code'], ['latest', 'bestseller', 'special', 'featured'])) {
					if ($value) {
						foreach ($value as $k => $v) {

							// Since 2.x doesn't look good with modules as side boxes, set to content bottom
							if ($v['position'] == 'column_left' || $v['position'] == 'column_right') {
								$v['position'] = 'content_bottom';
							}

							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];

							if (isset($v['image_width'])) {
								$module_data['width'] = $v['image_width'];
							}

							if (isset($v['image_height'])) {
								$module_data['height'] = $v['image_height'];
							}

							if (isset($v['limit'])) {
								$module_data['limit'] = $v['limit'];
							} else {
								$module_data['limit'] = 4;
							}

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

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $k . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

							$module_id = $this->db->getLastId();

							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$v['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($v['position']) . "', `sort_order` = '" . (int)$v['sort_order'] . "'");

							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE store_id = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
						}
					} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
					}
				} elseif (in_array($result['code'], ['category', 'account', 'affiliate', 'filter'])) {
					foreach ($value as $k => $v) {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
						$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '" . (int)$result['store_id'] . "', `code` = '" . $this->db->escape($result['code']) . "', `key` = '" . ($result['code'] . '_status') . "', `value` = '1'");

						if ($v['status']) {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$v['layout_id'] . "', `code` = '" . $this->db->escape($result['code']) . "', `position` = '" . $this->db->escape($v['position']) . "', `sort_order` = '" . (int)$v['sort_order'] . "'");
						}
					}
				} elseif (in_array($result['code'], ['banner', 'carousel', 'slideshow'])) {
					if ($value) {
						foreach ($value as $k => $v) {
							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];
							$module_data['banner_id'] = $v['banner_id'];

							if (isset($v['image_width'])) {
								$module_data['width'] = $v['image_width'];
							}

							if (isset($v['image_height'])) {
								$module_data['height'] = $v['image_height'];
							}

							if (isset($v['width'])) {
								$module_data['width'] = $v['width'];
							}

							if (isset($v['height'])) {
								$module_data['height'] = $v['height'];
							}

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $k . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

							$module_id = $this->db->getLastId();

							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$v['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($v['position']) . "', `sort_order` = '" . (int)$v['sort_order'] . "'");

							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = '" . $this->db->escape($result['code']) . "'");
						}
					} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = '" . $this->db->escape($result['code']) . "'");
					}
				} elseif (in_array($result['code'], ['welcome'])) {
					if ($value) {
						// Install HTML module if not already installed
						$html_query = $this->db->query("SELECT count(*) FROM `" . DB_PREFIX . "extension` WHERE `code` = 'html'");

						if ($html_query->row['count(*)'] == '0') {
							$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `type` = 'module', `code` = 'html'");
						}

						$result['code'] = 'html';

						foreach ($value as $k => $v) {
							$module_data['name'] = ($result['key'] . '_' . $k);
							$module_data['status'] = $v['status'];

							foreach ($v['description'] as $language_id => $description) {
								$module_data['module_description'][$language_id]['title'] = '';
								$module_data['module_description'][$language_id]['description'] = str_replace('image/data', 'image/catalog', $description);
							}

							$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $k . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");

							$module_id = $this->db->getLastId();

							$this->db->query("INSERT INTO `" . DB_PREFIX . "layout_module` SET `layout_id` = '" . (int)$v['layout_id'] . "', `code` = '" . $this->db->escape($result['code'] . '.' . $module_id) . "', `position` = '" . $this->db->escape($v['position']) . "', `sort_order` = '" . (int)$v['sort_order'] . "'");

							$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '" . (int)$result['store_id'] . "' AND `code` = 'welcome'");
						}
					} else {
						$this->db->query("DELETE FROM `" . DB_PREFIX . "extension` WHERE `code` = 'welcome'");
					}
				} else {
					// Could add code for other types here
					// If module has position, but not a core module, then disable it because it likely isn't compatible
					if (!empty($value)) {
						foreach ($value as $k => $v) {
							if (isset($v['position'])) {
								$module_data = $v;

								$module_data['name'] = ($result['key'] . '_' . $k);
								$module_data['status'] = '0'; // Disable non-core modules

								$this->db->query("INSERT INTO `" . DB_PREFIX . "module` SET `name` = '" . $this->db->escape($result['key']) . '_' . $k . "', `code` = '" . $this->db->escape($result['code']) . "', `setting` = '" . $this->db->escape(json_encode($module_data)) . "'");
							}
						}
					} else {
						$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode($value)) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
					}
				}
			} else {
				// Something should go here?
			}
		}
	}
}