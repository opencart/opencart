<?php
namespace Opencart\Install\Model\Upgrade;
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
		$missing = [];

		$missing[] = [
			'key'        => 'config_meta_title',
			'value'      => $settings['config_name'],
			'code'       => 'config',
			'serialized' => 0
		];

		$missing[] = [
			'key'        => 'config_product_description_length',
			'value'      => 100,
			'code'       => 'config',
			'serialized' => 0
		];

		$missing[] = [
			'key'        => 'config_pagination',
			'value'      => 10,
			'code'       => 'config',
			'serialized' => 0
		];

		$missing[] = [
			'key'        => 'config_pagination_admin',
			'value'      => $settings['config_limit_admin'],
			'code'       => 'config',
			'serialized' => 0
		];

		$missing[] = [
			'key'        => 'config_encryption',
			'value'      => hash('sha512', token(32)),
			'code'       => 'config',
			'serialized' => 0
		];

		$missing[] = [
			'key'        => 'config_voucher_min',
			'value'      => 1,
			'code'       => 'config',
			'serialized' => 0
		];

		$missing[] = [
			'key'        => 'config_voucher_max',
			'value'      => 1000,
			'code'       => 'config',
			'serialized' => 0
		];

		$missing[] = [
			'key'        => 'config_fraud_status_id',
			'value'      => 8,
			'code'       => 'config',
			'serialized' => 0
		];

		$missing[] = [
			'key'        => 'config_api_id',
			'value'      => 1,
			'code'       => 'config',
			'serialized' => 0
		];

		if ($settings['config_smtp_host']) {
			$missing[] = [
				'key' => 'config_mail_smtp_hostname',
				'value' => $settings['config_smtp_host'],
				'code' => 'config',
				'serialized' => 0
			];
		}

		if ($settings['config_smtp_username']) {
			$missing[] = [
				'key' => 'config_mail_smtp_username',
				'value' => $settings['config_smtp_username'],
				'code' => 'config',
				'serialized' => 0
			];
		}

		if ($settings['config_smtp_password']) {
			$missing[] = [
				'key' => 'config_mail_smtp_password',
				'value' => $settings['config_smtp_password'],
				'code' => 'config',
				'serialized' => 0
			];
		}

		if ($settings['config_smtp_port']) {
			$missing[] = [
				'key' => 'config_mail_smtp_port',
				'value' => $settings['config_smtp_port'],
				'code' => 'config',
				'serialized' => 0
			];
		}

		if ($settings['config_smtp_timeout']) {
			$missing[] = [
				'key'        => 'config_mail_smtp_timeout',
				'value'      => $settings['config_smtp_timeout'],
				'code'       => 'config',
				'serialized' => 0
			];
		}

		if ($settings['config_smtp_timeout']) {
			$missing[] = [
				'key'        => 'config_mail_smtp_timeout',
				'value'      => $settings['config_smtp_timeout'],
				'code'       => 'config',
				'serialized' => 0
			];
		}

		// Serialized
		$missing[] = [
			'key'        => 'config_complete_status',
			'value'      => [5],
			'code'       => 'config',
			'serialized' => 1
		];

		$missing[] = [
			'key'        => 'config_processing_status',
			'value'      => [2],
			'code'       => 'config',
			'serialized' => 1
		];

		// Add missing keys and serialized values
		foreach ($missing as $setting) {
			$query = $this->db->query("SELECT setting_id FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0' AND `key` = '" . $this->db->escape($setting['key']) . "'");

			if (!$query->num_rows) {
				if (!isset($settings[$setting['key']])) {
					if (!$setting['serialized']) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = '" . $this->db->escape($setting['key']) . "', `value` = '" . $this->db->escape($setting['value']) . "', `code` = '" . $this->db->escape($setting['code']) . "', `serialized` = '0', `store_id` = '0'");
					} else {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = '" . $this->db->escape($setting['key']) . "', `value` = '" . $this->db->escape(json_encode($setting['value'])) . "', `code` = '" . $this->db->escape($setting['code']) . "', `serialized` = '1', `store_id` = '0'");
					}
				}
			}
		}

		$remove = [
			''

		];




		// force some settings to prevent errors
		if ($settings['config_template'] == 'basic_default') {
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





		// Get all setting columns from extension table
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension`");

		foreach ($query->rows as $extension) {

			//get all setting from setting table
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $extension['code'] . "'");

			if ($query->num_rows) {
				foreach ($query->rows as $result) {
					//update old column name to adding prefix before the name
					if ($result['code'] == $extension['code'] && $result['code'] != $extension['type'] . '_' . $extension['code'] && $extension['type'] != 'theme') {
						$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = '" . $this->db->escape($extension['type'] . '_' . $extension['code']) . "', `key` = '" . $this->db->escape($extension['type'] . '_' . $result['key']) . "', `value` = '" . $this->db->escape($result['value']) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
					}
				}
			}


		}








		// Extension
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "extension` WHERE `type` = 'dashboard'");

		if (!$query->num_rows) {
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `key` = 'payment_free_checkout_order_status_id' WHERE `key` = 'free_checkout_order_status_id'");

			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = 'opencart', `type` = 'dashboard', `code` = 'activity'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = 'opencart', `type` = 'dashboard', `code` = 'sale'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = 'opencart', `type` = 'dashboard', `code` = 'recent'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = 'opencart', `type` = 'dashboard', `code` = 'order'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = 'opencart', `type` = 'dashboard', `code` = 'online'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = 'opencart', `type` = 'dashboard', `code` = 'map'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = 'opencart', `type` = 'dashboard', `code` = 'customer'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "extension` SET `extension` = 'opencart', `type` = 'dashboard', `code` = 'chart'");

			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_activity', `key` = 'dashboard_activity_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_activity', `key` = 'dashboard_activity_sort_order', `value` = '7', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_sale', `key` = 'dashboard_sale_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_sale', `key` = 'dashboard_sale_width', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_chart', `key` = 'dashboard_chart_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_chart', `key` = 'dashboard_chart_width', `value` = '6', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_customer', `key` = 'dashboard_customer_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_customer', `key` = 'dashboard_customer_width', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_map', `key` = 'dashboard_map_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_map', `key` = 'dashboard_map_width', `value` = '6', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_online', `key` = 'dashboard_online_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_online', `key` = 'dashboard_online_width', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_order', `key` = 'dashboard_order_sort_order', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_order', `key` = 'dashboard_order_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_order', `key` = 'dashboard_order_width', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_sale', `key` = 'dashboard_sale_sort_order', `value` = '2', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_customer', `key` = 'dashboard_customer_sort_order', `value` = '3', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_online', `key` = 'dashboard_online_sort_order', `value` = '4', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_map', `key` = 'dashboard_map_sort_order', `value` = '5', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_chart', `key` = 'dashboard_chart_sort_order', `value` = '6', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_recent', `key` = 'dashboard_recent_status', `value` = '1', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_recent', `key` = 'dashboard_recent_sort_order', `value` = '8', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_activity', `key` = 'dashboard_activity_width', `value` = '4', `serialized` = '0'");
			$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'dashboard_recent', `key` = 'dashboard_recent_width', `value` = '8', `serialized` = '0'");

		}




	}
}