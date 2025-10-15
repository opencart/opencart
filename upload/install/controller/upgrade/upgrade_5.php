<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade5
 *
 * Setting changes and default values
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade5 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// Adds any missing setting keys or default values that need changing or removed
		try {
			// Alter setting table
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "setting' AND COLUMN_NAME = 'group'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `code` = `group` WHERE `code` IS NULL or `code` = ''");

				// Remove the `group` field
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "setting` DROP `group`");
			}

			// Un-serialize values and change to JSON
			$query = $this->db->query("SELECT `setting_id`, `value` FROM `" . DB_PREFIX . "setting` WHERE `serialized` = '1' AND `value` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['value'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = '" . $this->db->escape(json_encode(unserialize($result['value']))) . "' WHERE `setting_id` = '" . (int)$result['setting_id'] . "'");
				}
			}

			// Get all settings
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

			if (!isset($settings['config_description'])) {
				if (isset($settings['config_meta_title'])) {
					$meta_title = $settings['config_meta_title'];
				} else {
					$meta_title = $settings['config_name'];
				}

				if (isset($settings['config_meta_description'])) {
					$meta_description = $settings['config_meta_description'];
				} else {
					$meta_description = '';
				}

				if (isset($settings['config_meta_keyword'])) {
					$meta_keyword = $settings['config_meta_keyword'];
				} else {
					$meta_keyword = '';
				}

				$description_data = [];

				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language`");

				foreach ($query->rows as $language) {
					$description_data[$language['language_id']] = [
						'meta_title'       => $meta_title,
						'meta_description' => $meta_description,
						'meta_keyword'     => $meta_keyword
					];
				}

				$missing[] = [
					'key'        => 'config_description',
					'value'      => $description_data,
					'code'       => 'config',
					'serialized' => 1
				];
			}

			// Add config_theme if missing and still using config_template
			if (isset($settings['config_template'])) {
				$missing[] = [
					'key'        => 'config_theme',
					'value'      => 'basic',
					'code'       => 'config',
					'serialized' => 0
				];
			}

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

			if (isset($settings['config_admin_language'])) {
				$missing[] = [
					'key'        => 'config_language_admin',
					'value'      => $settings['config_admin_language'],
					'code'       => 'config',
					'serialized' => 0
				];
			}

			if (isset($settings['config_limit_admin'])) {
				$missing[] = [
					'key'        => 'config_pagination_admin',
					'value'      => $settings['config_limit_admin'],
					'code'       => 'config',
					'serialized' => 0
				];
			}

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

			if (isset($settings['config_smtp_host'])) {
				$missing[] = [
					'key'        => 'config_mail_smtp_hostname',
					'value'      => $settings['config_smtp_host'],
					'code'       => 'config',
					'serialized' => 0
				];
			}

			if (isset($settings['config_smtp_username'])) {
				$missing[] = [
					'key'        => 'config_mail_smtp_username',
					'value'      => $settings['config_smtp_username'],
					'code'       => 'config',
					'serialized' => 0
				];
			}

			if (isset($settings['config_smtp_password'])) {
				$missing[] = [
					'key'        => 'config_mail_smtp_password',
					'value'      => $settings['config_smtp_password'],
					'code'       => 'config',
					'serialized' => 0
				];
			}

			if (isset($settings['config_smtp_port'])) {
				$missing[] = [
					'key'        => 'config_mail_smtp_port',
					'value'      => $settings['config_smtp_port'],
					'code'       => 'config',
					'serialized' => 0
				];
			}

			if (isset($settings['config_smtp_timeout'])) {
				$missing[] = [
					'key'        => 'config_mail_smtp_timeout',
					'value'      => $settings['config_smtp_timeout'],
					'code'       => 'config',
					'serialized' => 0
				];
			}

			$missing[] = [
				'key'        => 'config_article_description_length',
				'value'      => 100,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_image_default_width',
				'value'      => 300,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_image_default_height',
				'value'      => 300,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_image_article_width',
				'value'      => 1140,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_image_article_height',
				'value'      => 380,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_image_topic_width',
				'value'      => 1140,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_image_topic_height',
				'value'      => 380,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_cookie_id',
				'value'      => 0,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_gdpr_id',
				'value'      => 0,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_gdpr_limit',
				'value'      => 180,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_affiliate_status',
				'value'      => 1,
				'code'       => 'config',
				'serialized' => 0
			];

			// Subscriptions
			$missing[] = [
				'key'        => 'config_subscription_status_id',
				'value'      => 1,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_subscription_active_status_id',
				'value'      => 2,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_subscription_expired_status_id',
				'value'      => 6,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_subscription_canceled_status_id',
				'value'      => 4,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_subscription_failed_status_id',
				'value'      => 3,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_subscription_denied_status_id',
				'value'      => 5,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_fraud_status_id',
				'value'      => 8,
				'code'       => 'config',
				'serialized' => 0
			];

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

			$missing[] = [
				'key'        => 'config_2fa_expire',
				'value'      => 90,
				'code'       => 'config',
				'serialized' => 0
			];

			$missing[] = [
				'key'        => 'config_password_length',
				'value'      => 6,
				'code'       => 'config',
				'serialized' => 0
			];

			// Add missing keys and serialized values
			foreach ($missing as $setting) {
				$query = $this->db->query("SELECT setting_id FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0' AND `key` = '" . $this->db->escape($setting['key']) . "'");

				if (!$query->num_rows && !isset($settings[$setting['key']])) {
					if (!$setting['serialized']) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = '" . $this->db->escape($setting['key']) . "', `value` = '" . $this->db->escape($setting['value']) . "', `code` = '" . $this->db->escape($setting['code']) . "', `serialized` = '0', `store_id` = '0'");
					} else {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `key` = '" . $this->db->escape($setting['key']) . "', `value` = '" . $this->db->escape(json_encode($setting['value'])) . "', `code` = '" . $this->db->escape($setting['code']) . "', `serialized` = '1', `store_id` = '0'");
					}
				}
			}

			// Remove some setting keys
			$remove = [
				'config_meta_title',
				'config_meta_description',
				'config_meta_keywords',
				'config_template',
				'config_limit_admin',
				'config_smtp_host',
				'config_smtp_username',
				'config_smtp_password',
				'config_smtp_port',
				'config_smtp_timeout'
			];

			foreach ($remove as $key) {
				$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = '" . $this->db->escape($key) . "'");
			}

			// Update some language settings
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'en-gb' WHERE `key` = 'config_language' AND `value` = 'en'");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `value` = 'en-gb' WHERE `key` = 'config_language_admin' AND `value` = 'en'");
			$this->db->query("UPDATE `" . DB_PREFIX . "setting` SET `key` = 'config_language_catalog' WHERE `key` = 'config_language'");

			$this->cache->delete('language');
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 5, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_6', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
