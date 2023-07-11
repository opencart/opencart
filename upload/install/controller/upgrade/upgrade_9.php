<?php
namespace Opencart\Install\Controller\Upgrade;
class Upgrade9 extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			// Fix https://github.com/opencart/opencart/issues/11594
			$this->db->query("UPDATE `" . DB_PREFIX . "layout_route` SET `route` = REPLACE(`route`, '|', '.')");
			$this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET `value` = REPLACE(`value`, '|', '.') WHERE `key` = 'route'");
			$this->db->query("UPDATE `" . DB_PREFIX . "event` SET `trigger` = REPLACE(`trigger`, '|', '.'), `action` = REPLACE(`action`, '|', '.')");
			$this->db->query("UPDATE `" . DB_PREFIX . "banner_image` SET `link` = REPLACE(`link`, '|', '.')");

			// order
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'payment_code'");

			if ($query->num_rows) {
				$query = $this->db->query("SELECT `order_id`, `payment_code`, `payment_method`, `shipping_method`, `shipping_code` FROM `" . DB_PREFIX . "order`");

				foreach ($query->rows as $result) {
					if (isset($result['payment_code'])) {
						$payment_method = [
							'name' => $result['payment_method'],
							'code' => $result['payment_code']
						];

						$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_custom_field` = '" . $this->db->escape(json_encode($payment_method)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
					}

					if (isset($result['shipping_code'])) {
						$order_total_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$result['order_id'] . "' AND `code` = 'shipping'");

						if ($order_total_query->num_rows) {
							$shipping_method = [
								'name' => $result['shipping_method'],
								'code' => $result['shipping_code'],
								'cost' => $order_total_query->row['value'],
								'text' => $result['shipping_method']
							];

							$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `shipping_method` = '" . $this->db->escape(json_encode($shipping_method)) . "' WHERE `order_id` = '" . (int)$result['order_id'] . "'");
						}
					}
				}

				// Drop Fields
				$remove = [];

				$remove[] = [
					'table' => 'order',
					'field' => 'payment_code'
				];

				// custom_field
				$remove[] = [
					'table' => 'order',
					'field' => 'shipping_code'
				];

				foreach ($remove as $result) {
					$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $result['table'] . "' AND COLUMN_NAME = '" . $result['field'] . "'");

					if ($query->num_rows) {
						$this->db->query("ALTER TABLE `" . DB_PREFIX . $result['table'] . "` DROP `" . $result['field'] . "`");
					}
				}
			}

			// Order Subscription
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'subscription_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `subscription_id` int(11) NOT NULL AFTER `order_id`");
			}

			// Order Transaction
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'transaction_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `transaction_id` varchar(100) NOT NULL AFTER `invoice_prefix`");
			}

			// Order Payment Address ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'payment_address_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `payment_address_id` int(11) NOT NULL AFTER `custom_field`");
			}

			// Order Shipping Address ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'shipping_address_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `shipping_address_id` int(11) NOT NULL AFTER `payment_method`");
			}

			// Order Language Code
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "order' AND COLUMN_NAME = 'language_code'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD COLUMN `language_code` varchar(5) NOT NULL AFTER `language_id`");
			}

			// Subscription Customer Payment ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'customer_payment_id'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` DROP COLUMN `customer_payment_id`");
			}

			// Subscription Name
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'name'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` DROP COLUMN `name`");
			}

			// Subscription Description
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'description'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` DROP COLUMN `description`");
			}

			// Subscription Reference
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'reference'");

			if ($query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` DROP COLUMN `reference`");
			}

			// Subscription Comment
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'comment'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `comment` text NOT NULL AFTER `date_next`");
			}

			// Subscription Affiliate ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'affiliate_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `affiliate_id` int(11) NOT NULL AFTER `subscription_status_id`");
			}

			// Subscription Marketing ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'marketing_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `marketing_id` int(11) NOT NULL AFTER `affiliate_id`");
			}

			// Subscription Tracking
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'tracking'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `tracking` varchar(64) NOT NULL AFTER `marketing_id`");
			}

			// Subscription Language ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'language_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `language_id` int(11) NOT NULL AFTER `tracking`");
			}

			// Subscription Currency ID
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'currency_id'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `currency_id` int(11) NOT NULL AFTER `language_id`");
			}

			// Subscription IP
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'ip'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `ip` varchar(40) NOT NULL AFTER `currency_id`");
			}

			// Subscription Forwarded IP
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'forwarded_ip'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `forwarded_ip` varchar(40) NOT NULL AFTER `ip`");
			}

			// Subscription User Agent
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'user_agent'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `user_agent` varchar(255) NOT NULL AFTER `forwarded_ip`");
			}

			// Subscription Accept Language
			$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "subscription' AND COLUMN_NAME = 'accept_language'");

			if (!$query->num_rows) {
				$this->db->query("ALTER TABLE `" . DB_PREFIX . "subscription` ADD COLUMN `accept_language` varchar(255) NOT NULL AFTER `user_agent`");
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['redirect'] = $this->url->link('install/step_4', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
