<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade11
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade11 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			$this->load->model('upgrade/upgrade');

			// customer
			$query = $this->db->query("SELECT `customer_id`, `custom_field` FROM `" . DB_PREFIX . "customer` WHERE `custom_field` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['custom_field'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET `custom_field` = '" . $this->db->escape(json_encode(unserialize($result['custom_field']))) . "' WHERE `customer_id` = '" . (int)$result['customer_id'] . "'");
				}
			}

			// customer_activity
			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_activity` WHERE `data` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['data'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `data` = '" . $this->db->escape(json_encode(unserialize($result['data']))) . "' WHERE `customer_activity_id` = '" . (int)$result['customer_activity_id'] . "'");
				}
			}

			// address
			$query = $this->db->query("SELECT `address_id`, `custom_field` FROM `" . DB_PREFIX . "address` WHERE `custom_field` LIKE 'a:%'");

			foreach ($query->rows as $result) {
				if (preg_match('/^(a:)/', $result['custom_field'])) {
					$this->db->query("UPDATE `" . DB_PREFIX . "address` SET `custom_field` = '" . $this->db->escape(json_encode(unserialize($result['custom_field']))) . "' WHERE `address_id` = '" . (int)$result['address_id'] . "'");
				}
			}

			// customer_activity
			if ($this->model_upgrade_upgrade->hasField('customer_activity', 'activity_id')) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer_activity` SET `customer_activity_id` = `activity_id` WHERE `customer_activity_id` IS NULL or `customer_activity_id` = ''");
			}

			// Customer Group
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_group' AND COLUMN_NAME = 'name'");

			if ($query->num_rows) {
				$customer_group_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_group`");

				foreach ($customer_group_query->rows as $customer_group) {
					$language_query = $this->db->query("SELECT `language_id` FROM `" . DB_PREFIX . "language`");

					foreach ($language_query->rows as $language) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_group_description` SET `customer_group_id` = '" . (int)$customer_group['customer_group_id'] . "', `language_id` = '" . (int)$language['language_id'] . "', `name` = '" . $this->db->escape($customer_group['name']) . "'");
					}
				}
			}

			// Affiliate customer merge code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "affiliate'");

			if ($query->num_rows) {
				// Removing affiliate and moving to the customer account.
				$config = new \Opencart\System\Engine\Config();

				$setting_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "setting` WHERE `store_id` = '0'");

				foreach ($setting_query->rows as $setting) {
					$config->set($setting['key'], $setting['value']);
				}

				$affiliate_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate`");

				foreach ($affiliate_query->rows as $affiliate) {
					$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `email` = '" . $this->db->escape($affiliate['email']) . "'");

					if (!$customer_query->num_rows) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET `customer_group_id` = '" . (int)$config->get('config_customer_group_id') . "', `language_id` = '" . (int)$config->get('config_customer_group_id') . "', `firstname` = '" . $this->db->escape($affiliate['firstname']) . "', `lastname` = '" . $this->db->escape($affiliate['lastname']) . "', `email` = '" . $this->db->escape($affiliate['email']) . "', `password` = '" . $this->db->escape($affiliate['password']) . "', `newsletter` = '0', `custom_field` = '" . $this->db->escape(json_encode([])) . "', `ip` = '" . $this->db->escape($affiliate['ip']) . "', `status` = '" . $this->db->escape($affiliate['status']) . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");

						$customer_id = $this->db->getLastId();

						$this->db->query("INSERT INTO `" . DB_PREFIX . "address` SET `customer_id` = '" . (int)$customer_id . "', `firstname` = '" . $this->db->escape($affiliate['firstname']) . "', `lastname` = '" . $this->db->escape($affiliate['lastname']) . "', `company` = '" . $this->db->escape($affiliate['company']) . "', `address_1` = '" . $this->db->escape($affiliate['address_1']) . "', `address_2` = '" . $this->db->escape($affiliate['address_2']) . "', `city` = '" . $this->db->escape($affiliate['city']) . "', `postcode` = '" . $this->db->escape($affiliate['postcode']) . "', `zone_id` = '" . (int)$affiliate['zone_id'] . "', `country_id` = '" . (int)$affiliate['country_id'] . "', `custom_field` = '" . $this->db->escape(json_encode([])) . "'");
					} else {
						$customer_id = $customer_query->row['customer_id'];
					}

					$customer_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

					if (!$customer_query->num_rows) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate` SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($affiliate['company']) . "', `tracking` = '" . $this->db->escape($affiliate['code']) . "', `commission` = '" . (float)$affiliate['commission'] . "', `tax` = '" . $this->db->escape($affiliate['tax']) . "', `payment_method` = '" . $this->db->escape($affiliate['payment_method']) . "', `cheque` = '" . $this->db->escape($affiliate['cheque']) . "', `paypal` = '" . $this->db->escape($affiliate['paypal']) . "', `bank_name` = '" . $this->db->escape($affiliate['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($affiliate['bank_branch_number']) . "', `bank_account_name` = '" . $this->db->escape($affiliate['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($affiliate['bank_account_number']) . "', `status` = '" . (int)($affiliate['approved'] ?? $affiliate['status']) . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");
					}

					$affiliate_transaction_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "affiliate_transaction` WHERE `affiliate_id` = '" . (int)$affiliate['affiliate_id'] . "'");

					foreach ($affiliate_transaction_query->rows as $affiliate_transaction) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_transaction` SET `customer_id` = '" . (int)$customer_id . "', `order_id` = '" . (int)$affiliate_transaction['order_id'] . "', `description` = '" . $this->db->escape($affiliate_transaction['description']) . "', `amount` = '" . (float)$affiliate_transaction['amount'] . "', `date_added` = '" . $this->db->escape($affiliate_transaction['date_added']) . "'");

						$this->db->query("DELETE FROM `" . DB_PREFIX . "affiliate_transaction` WHERE `affiliate_transaction_id` = '" . (int)$affiliate_transaction['affiliate_transaction_id'] . "'");
					}

					$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `affiliate_id` = '" . (int)$customer_id . "' WHERE `affiliate_id` = '" . (int)$affiliate['affiliate_id'] . "'");
				}
			}

			// affiliate payment > payment_method
			if ($this->model_upgrade_upgrade->hasField('customer_affiliate', 'payment')) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET `payment_method` = `payment`");

				$this->model_upgrade_upgrade->dropField('customer_affiliate', 'payment');
			}
			
			// API
			if ($this->model_upgrade_upgrade->hasField('api', 'name')) {
				$this->db->query("UPDATE `" . DB_PREFIX . "api` SET `name` = `username` WHERE `username` IS NULL or `username` = ''");
			}

			// Cart - Subscriptions
			if ($this->model_upgrade_upgrade->hasField('cart', 'recurring_id')) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "cart`");

				$this->model_upgrade_upgrade->dropField('cart', 'recurring_id');
			}

			// Addresses
			if (!$this->model_upgrade_upgrade->hasField('address', 'default')) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "address` ADD COLUMN `default` tinyint(1) NOT NULL AFTER `custom_field`");
			}

			// Drop Fields
			$remove = [];

			$remove[] = [
				'table' => 'affiliate',
				'field' => 'payment'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'name'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'firstname'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'lastname'
			];

			$remove[] = [
				'table' => 'api',
				'field' => 'password'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'cart'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'approved'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'code'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'token'
			];

			$remove[] = [
				'table' => 'customer_activity',
				'field' => 'activity_id'
			];

			$remove[] = [
				'table' => 'customer_group',
				'field' => 'name'
			];

			$remove[] = [
				'table' => 'order',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'language',
				'field' => 'directory'
			];

			$remove[] = [
				'table' => 'location',
				'field' => 'fax'
			];

			$remove[] = [
				'table' => 'store',
				'field' => 'ssl'
			];

			$remove[] = [
				'table' => 'user_login',
				'field' => 'token'
			];

			$remove[] = [
				'table' => 'user_login',
				'field' => 'total'
			];

			$remove[] = [
				'table' => 'user_login',
				'field' => 'status'
			];

			$this->load->model('upgrade/upgrade');

			foreach ($remove as $result) {
				$this->model_upgrade_upgrade->dropField($result['table'], $result['field']);
			}

			// Drop Tables
			$remove = [
				'affiliate',
				'affiliate_activity',
				'affiliate_login',
				'affiliate_transaction',
				'customer_ban_ip',
				'customer_field',
				'customer_payment',
				'order_field',
				'order_custom_field',
				'url_alias'
			];

			foreach ($remove as $table) {
				$this->model_upgrade_upgrade->dropTable($table);
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 11, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_12', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
