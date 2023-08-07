<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade8
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade8 extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// customer_activity
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_activity' AND COLUMN_NAME = 'activity_id'");

			if ($query->num_rows) {
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
						$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_affiliate` SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($affiliate['company']) . "', `tracking` = '" . $this->db->escape($affiliate['code']) . "', `commission` = '" . (float)$affiliate['commission'] . "', `tax` = '" . $this->db->escape($affiliate['tax']) . "', `payment_method` = '" . $this->db->escape($affiliate['payment_method']) . "', `cheque` = '" . $this->db->escape($affiliate['cheque']) . "', `paypal` = '" . $this->db->escape($affiliate['paypal']) . "', `bank_name` = '" . $this->db->escape($affiliate['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($affiliate['bank_branch_number']) . "', `bank_account_name` = '" . $this->db->escape($affiliate['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($affiliate['bank_account_number']) . "', `status` = '" . (int)(isset($affiliate['approved']) ? $affiliate['approved'] : $affiliate['status']) . "', `date_added` = '" . $this->db->escape($affiliate['date_added']) . "'");
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
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "customer_affiliate' AND COLUMN_NAME = 'payment'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "customer_affiliate` SET `payment_method` = `payment`");

				$this->db->query("ALTER TABLE `" . DB_PREFIX . "customer_affiliate` DROP COLUMN `payment`");
			}

			// Country address_format_id
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "country' AND COLUMN_NAME = 'address_format_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "country` ADD COLUMN `address_format_id` int(11) NOT NULL AFTER `address_format`");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "country` DROP COLUMN `address_format`");
			}		

			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "address_format'");

			if ($query->num_rows) {
				$address_format_total = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "address_format`");

				if (!$address_format_total->row['total']) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "address_format` SET `name` = 'Address Format', `address_format` = '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city}, {zone} {postcode}\r\n{country}'");
				}
			}

			// Country
			$this->db->query("UPDATE `" . DB_PREFIX . "country` SET `address_format_id` = '1' WHERE `address_format_id` = '0'");

			// Api
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "api' AND COLUMN_NAME = 'name'");

			if ($query->num_rows) {
				$this->db->query("UPDATE `" . DB_PREFIX . "api` SET `name` = `username` WHERE `username` IS NULL or `username` = ''");
			}
			
			// Cart - Subscriptions
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "cart' AND COLUMN_NAME = 'subscription_plan_id'");

			if (!$query->num_rows) {
				$this->db->query("TRUNCATE TABLE `" . DB_PREFIX . "cart`");

				$this->db->query("ALTER TABLE `" . DB_PREFIX . "cart` DROP COLUMN `recurring_id`");
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "cart` ADD COLUMN `subscription_plan_id` int(11) NOT NULL AFTER `product_id`");
			}
			
			// Addresses
            $query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "address' AND COLUMN_NAME = 'default'");

            if (!$query->num_rows) {
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
				'field' => 'salt'
			];

			$remove[] = [
				'table' => 'customer',
				'field' => 'approved'
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
				'table' => 'user',
				'field' => 'salt'
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

			foreach ($remove as $result) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $result['table'] . "' AND COLUMN_NAME = '" . $result['field'] . "'");

				if ($query->num_rows) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . $result['table'] . "` DROP `" . $result['field'] . "`");
				}
			}

			// Drop Tables
			$remove = [
				'affiliate',
				'affiliate_activity',
				'affiliate_login',
				'affiliate_transaction',
				'banner_image_description',
				'customer_ban_ip',
				'customer_field',
				'customer_payment',
				'modification',
				'order_field',
				'order_custom_field',
				'url_alias'
			];

			foreach ($remove as $table) {
				$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "'");

				if ($query->num_rows) {
					$this->db->query("DROP TABLE `" . DB_PREFIX . $table . "`");
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_progress'), 8, 8, 9);

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_9', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
