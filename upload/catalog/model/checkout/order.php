<?php
namespace Opencart\Catalog\Model\Checkout;
class Order extends \Opencart\System\Engine\Model {
	public function addOrder(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET `invoice_prefix` = '" . $this->db->escape((string)$data['invoice_prefix']) . "', `store_id` = '" . (int)$data['store_id'] . "', `store_name` = '" . $this->db->escape((string)$data['store_name']) . "', `store_url` = '" . $this->db->escape((string)$data['store_url']) . "', `customer_id` = '" . (int)$data['customer_id'] . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `custom_field` = '" . $this->db->escape(isset($data['custom_field']) ? json_encode($data['custom_field']) : '') . "', `payment_firstname` = '" . $this->db->escape((string)$data['payment_firstname']) . "', `payment_lastname` = '" . $this->db->escape((string)$data['payment_lastname']) . "', `payment_company` = '" . $this->db->escape((string)$data['payment_company']) . "', `payment_address_1` = '" . $this->db->escape((string)$data['payment_address_1']) . "', `payment_address_2` = '" . $this->db->escape((string)$data['payment_address_2']) . "', `payment_city` = '" . $this->db->escape((string)$data['payment_city']) . "', `payment_postcode` = '" . $this->db->escape((string)$data['payment_postcode']) . "', `payment_country` = '" . $this->db->escape((string)$data['payment_country']) . "', `payment_country_id` = '" . (int)$data['payment_country_id'] . "', `payment_zone` = '" . $this->db->escape((string)$data['payment_zone']) . "', `payment_zone_id` = '" . (int)$data['payment_zone_id'] . "', `payment_address_format` = '" . $this->db->escape((string)$data['payment_address_format']) . "', `payment_custom_field` = '" . $this->db->escape(isset($data['payment_custom_field']) ? json_encode($data['payment_custom_field']) : '') . "', `payment_method` = '" . $this->db->escape((string)$data['payment_method']) . "', `payment_code` = '" . $this->db->escape((string)$data['payment_code']) . "', `shipping_firstname` = '" . $this->db->escape((string)$data['shipping_firstname']) . "', `shipping_lastname` = '" . $this->db->escape((string)$data['shipping_lastname']) . "', `shipping_company` = '" . $this->db->escape((string)$data['shipping_company']) . "', `shipping_address_1` = '" . $this->db->escape((string)$data['shipping_address_1']) . "', `shipping_address_2` = '" . $this->db->escape((string)$data['shipping_address_2']) . "', `shipping_city` = '" . $this->db->escape((string)$data['shipping_city']) . "', `shipping_postcode` = '" . $this->db->escape((string)$data['shipping_postcode']) . "', `shipping_country` = '" . $this->db->escape((string)$data['shipping_country']) . "', `shipping_country_id` = '" . (int)$data['shipping_country_id'] . "', `shipping_zone` = '" . $this->db->escape((string)$data['shipping_zone']) . "', `shipping_zone_id` = '" . (int)$data['shipping_zone_id'] . "', `shipping_address_format` = '" . $this->db->escape((string)$data['shipping_address_format']) . "', `shipping_custom_field` = '" . $this->db->escape(isset($data['shipping_custom_field']) ? json_encode($data['shipping_custom_field']) : '') . "', `shipping_method` = '" . $this->db->escape((string)$data['shipping_method']) . "', `shipping_code` = '" . $this->db->escape((string)$data['shipping_code']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "', `total` = '" . (float)$data['total'] . "', `affiliate_id` = '" . (int)$data['affiliate_id'] . "', `commission` = '" . (float)$data['commission'] . "', `marketing_id` = '" . (int)$data['marketing_id'] . "', `tracking` = '" . $this->db->escape((string)$data['tracking']) . "', `language_id` = '" . (int)$data['language_id'] . "', `currency_id` = '" . (int)$data['currency_id'] . "', `currency_code` = '" . $this->db->escape((string)$data['currency_code']) . "', `currency_value` = '" . (float)$data['currency_value'] . "', `ip` = '" . $this->db->escape((string)$data['ip']) . "', `forwarded_ip` = '" .  $this->db->escape((string)$data['forwarded_ip']) . "', `user_agent` = '" . $this->db->escape((string)$data['user_agent']) . "', `accept_language` = '" . $this->db->escape((string)$data['accept_language']) . "', `date_added` = NOW(), `date_modified` = NOW()");

		$order_id = $this->db->getLastId();

		$this->load->model('checkout/subscription');

		// Products
		if (isset($data['products'])) {
			foreach ($data['products'] as $product) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET `order_id` = '" . (int)$order_id . "', `product_id` = '" . (int)$product['product_id'] . "', `master_id` = '" . (int)$product['master_id'] . "', `name` = '" . $this->db->escape($product['name']) . "', `model` = '" . $this->db->escape($product['model']) . "', `quantity` = '" . (int)$product['quantity'] . "', `price` = '" . (float)$product['price'] . "', `total` = '" . (float)$product['total'] . "', `tax` = '" . (float)$product['tax'] . "', `reward` = '" . (int)$product['reward'] . "'");

				$order_product_id = $this->db->getLastId();

				foreach ($product['option'] as $option) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_option` SET `order_id` = '" . (int)$order_id . "', `order_product_id` = '" . (int)$order_product_id . "', `product_option_id` = '" . (int)$option['product_option_id'] . "', `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "', `name` = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
				}

				if ($product['subscription']) {
					$subscription_data = [
						'order_product_id' 		=> $order_product_id,
						'customer_id'			=> $data['customer_id'],
						'order_id'              => $order_id,
						'subscription_plan_id' 	=> $product['subscription']['subscription_plan_id'],
						'name'              	=> $product['subscription']['name'],
						'description'       	=> $product['subscription']['description'],
						'trial_price'       	=> $product['subscription']['trial_price'],
						'trial_frequency'   	=> $product['subscription']['trial_frequency'],
						'trial_cycle'       	=> $product['subscription']['trial_cycle'],
						'trial_duration'    	=> $product['subscription']['trial_duration'],
						'trial_status'      	=> $product['subscription']['trial_status'],
						'price'             	=> $product['subscription']['price'],
						'frequency'         	=> $product['subscription']['frequency'],
						'cycle'             	=> $product['subscription']['cycle'],
						'duration'          	=> $product['subscription']['duration'],
						'remaining'         	=> $product['subscription']['duration'],
						'date_next'				=> $product['subscription']['date_next'],
						'status'				=> $product['subscription']['status']
					];

					$this->model_checkout_subscription->addSubscription($order_id, $subscription_data);
				}
			}
		}

		// Vouchers
		if (isset($data['vouchers'])) {
			$this->load->model('checkout/voucher');

			foreach ($data['vouchers'] as $voucher) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_voucher` SET `order_id` = '" . (int)$order_id . "', `description` = '" . $this->db->escape($voucher['description']) . "', `code` = '" . $this->db->escape($voucher['code']) . "', `from_name` = '" . $this->db->escape($voucher['from_name']) . "', `from_email` = '" . $this->db->escape($voucher['from_email']) . "', `to_name` = '" . $this->db->escape($voucher['to_name']) . "', `to_email` = '" . $this->db->escape($voucher['to_email']) . "', `voucher_theme_id` = '" . (int)$voucher['voucher_theme_id'] . "', `message` = '" . $this->db->escape($voucher['message']) . "', `amount` = '" . (float)$voucher['amount'] . "'");

				$order_voucher_id = $this->db->getLastId();

				$voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $voucher);

				$this->db->query("UPDATE `" . DB_PREFIX . "order_voucher` SET `voucher_id` = '" . (int)$voucher_id . "' WHERE `order_voucher_id` = '" . (int)$order_voucher_id . "'");
			}
		}

		// Totals
		if (isset($data['totals'])) {
			foreach ($data['totals'] as $total) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET `order_id` = '" . (int)$order_id . "', `extension` = '" . $this->db->escape($total['extension']) . "', `code` = '" . $this->db->escape($total['code']) . "', `title` = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', `sort_order` = '" . (int)$total['sort_order'] . "'");
			}
		}

		return $order_id;
	}

	public function editOrder(int $order_id, array $data): void {
		// 1. Void the order first
		$this->addHistory($order_id, 0);

		$order_info = $this->getOrder($order_id);

		if ($order_info) {
			// 2. Merge the old order data with the new data
			foreach ($order_info as $key => $value) {
				if (!isset($data[$key])) {
					$data[$key] = $value;
				}
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `invoice_prefix` = '" . $this->db->escape((string)$data['invoice_prefix']) . "', `store_id` = '" . (int)$data['store_id'] . "', `store_name` = '" . $this->db->escape((string)$data['store_name']) . "', `store_url` = '" . $this->db->escape((string)$data['store_url']) . "', `customer_id` = '" . (int)$data['customer_id'] . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', `firstname` = '" . $this->db->escape((string)$data['firstname']) . "', `lastname` = '" . $this->db->escape((string)$data['lastname']) . "', `email` = '" . $this->db->escape((string)$data['email']) . "', `telephone` = '" . $this->db->escape((string)$data['telephone']) . "', `custom_field` = '" . $this->db->escape(json_encode($data['custom_field'])) . "', `payment_firstname` = '" . $this->db->escape((string)$data['payment_firstname']) . "', `payment_lastname` = '" . $this->db->escape((string)$data['payment_lastname']) . "', `payment_company` = '" . $this->db->escape((string)$data['payment_company']) . "', `payment_address_1` = '" . $this->db->escape((string)$data['payment_address_1']) . "', `payment_address_2` = '" . $this->db->escape((string)$data['payment_address_2']) . "', `payment_city` = '" . $this->db->escape((string)$data['payment_city']) . "', `payment_postcode` = '" . $this->db->escape((string)$data['payment_postcode']) . "', `payment_country` = '" . $this->db->escape((string)$data['payment_country']) . "', `payment_country_id` = '" . (int)$data['payment_country_id'] . "', `payment_zone` = '" . $this->db->escape((string)$data['payment_zone']) . "', `payment_zone_id` = '" . (int)$data['payment_zone_id'] . "', `payment_address_format` = '" . $this->db->escape((string)$data['payment_address_format']) . "', `payment_custom_field` = '" . $this->db->escape(json_encode($data['payment_custom_field'])) . "', `payment_method` = '" . $this->db->escape((string)$data['payment_method']) . "', `payment_code` = '" . $this->db->escape((string)$data['payment_code']) . "', `shipping_firstname` = '" . $this->db->escape((string)$data['shipping_firstname']) . "', `shipping_lastname` = '" . $this->db->escape((string)$data['shipping_lastname']) . "', `shipping_company` = '" . $this->db->escape((string)$data['shipping_company']) . "', `shipping_address_1` = '" . $this->db->escape((string)$data['shipping_address_1']) . "', `shipping_address_2` = '" . $this->db->escape((string)$data['shipping_address_2']) . "', `shipping_city` = '" . $this->db->escape((string)$data['shipping_city']) . "', `shipping_postcode` = '" . $this->db->escape((string)$data['shipping_postcode']) . "', `shipping_country` = '" . $this->db->escape((string)$data['shipping_country']) . "', `shipping_country_id` = '" . (int)$data['shipping_country_id'] . "', `shipping_zone` = '" . $this->db->escape((string)$data['shipping_zone']) . "', `shipping_zone_id` = '" . (int)$data['shipping_zone_id'] . "', `shipping_address_format` = '" . $this->db->escape((string)$data['shipping_address_format']) . "', `shipping_custom_field` = '" . $this->db->escape(json_encode($data['shipping_custom_field'])) . "', `shipping_method` = '" . $this->db->escape((string)$data['shipping_method']) . "', `shipping_code` = '" . $this->db->escape((string)$data['shipping_code']) . "', `comment` = '" . $this->db->escape((string)$data['comment']) . "', `total` = '" . (float)$data['total'] . "', `affiliate_id` = '" . (int)$data['affiliate_id'] . "', `commission` = '" . (float)$data['commission'] . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");

			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE `order_id` = '" . (int)$order_id . "'");

			$this->load->model('checkout/subscription');

			$this->model_checkout_subscription->deleteSubscriptionByOrderId($order_id);

			// Products
			if (isset($data['products'])) {
				foreach ($data['products'] as $product) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET `order_id` = '" . (int)$order_id . "', `product_id` = '" . (int)$product['product_id'] . "', `master_id` = '" . (int)$product['master_id'] . "', `name` = '" . $this->db->escape($product['name']) . "', `model` = '" . $this->db->escape($product['model']) . "', `quantity` = '" . (int)$product['quantity'] . "', `price` = '" . (float)$product['price'] . "', `total` = '" . (float)$product['total'] . "', `tax` = '" . (float)$product['tax'] . "', `reward` = '" . (int)$product['reward'] . "'");

					$order_product_id = $this->db->getLastId();

					foreach ($product['option'] as $option) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_option` SET `order_id` = '" . (int)$order_id . "', `order_product_id` = '" . (int)$order_product_id . "', `product_option_id` = '" . (int)$option['product_option_id'] . "', `product_option_value_id` = '" . (int)$option['product_option_value_id'] . "', `name` = '" . $this->db->escape($option['name']) . "', `value` = '" . $this->db->escape($option['value']) . "', `type` = '" . $this->db->escape($option['type']) . "'");
					}

					if ($product['subscription']) {
						$this->model_checkout_subscription->addSubscription($order_id, $product['subscription'] + ['order_product_id' => $order_product_id]);
					}
				}
			}

			// Gift Voucher
			$this->load->model('checkout/voucher');

			$this->model_checkout_voucher->deleteVoucherByOrderId($order_id);

			// Vouchers
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = '" . (int)$order_id . "'");

			if (isset($data['vouchers'])) {
				foreach ($data['vouchers'] as $voucher) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_voucher` SET `order_id` = '" . (int)$order_id . "', `description` = '" . $this->db->escape($voucher['description']) . "', `code` = '" . $this->db->escape($voucher['code']) . "', `from_name` = '" . $this->db->escape($voucher['from_name']) . "', `from_email` = '" . $this->db->escape($voucher['from_email']) . "', `to_name` = '" . $this->db->escape($voucher['to_name']) . "', `to_email` = '" . $this->db->escape($voucher['to_email']) . "', `voucher_theme_id` = '" . (int)$voucher['voucher_theme_id'] . "', `message` = '" . $this->db->escape($voucher['message']) . "', `amount` = '" . (float)$voucher['amount'] . "'");

					$order_voucher_id = $this->db->getLastId();

					$voucher_id = $this->model_checkout_voucher->addVoucher($order_id, $voucher);

					$this->db->query("UPDATE `" . DB_PREFIX . "order_voucher` SET `voucher_id` = '" . (int)$voucher_id . "' WHERE `order_voucher_id` = '" . (int)$order_voucher_id . "'");
				}
			}

			// Totals
			$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "'");

			if (isset($data['totals'])) {
				foreach ($data['totals'] as $total) {
					$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET `order_id` = '" . (int)$order_id . "', `extension` = '" . $this->db->escape($total['extension']) . "', `code` = '" . $this->db->escape($total['code']) . "', `title` = '" . $this->db->escape($total['title']) . "', `value` = '" . (float)$total['value'] . "', `sort_order` = '" . (int)$total['sort_order'] . "'");
				}
			}
		}
	}

	public function editTransactionId(int $order_id, string $transaction_id): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `transaction_id` = '" . $this->db->escape($transaction_id) . "' WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function deleteOrder(int $order_id): void {
		// Void the order first
		$this->addHistory($order_id, 0);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "subscription_transaction` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_transaction` WHERE `order_id` = '" . (int)$order_id . "'");

		// Gift Voucher
		$this->load->model('checkout/voucher');

		$this->model_checkout_voucher->deleteVoucherByOrderId($order_id);
	}

	public function getOrder(int $order_id): array {
		$order_query = $this->db->query("SELECT *, (SELECT os.`name` FROM `" . DB_PREFIX . "order_status` os WHERE os.`order_status_id` = o.`order_status_id` AND os.`language_id` = o.`language_id`) AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.`order_id` = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$order_data = $order_query->row;

			$this->load->model('localisation/country');
			$this->load->model('localisation/zone');

			$order_data['custom_field'] = json_decode($order_query->row['custom_field'], true);

			foreach (['payment', 'shipping'] as $column) {
				$country_info = $this->model_localisation_country->getCountry($order_query->row[$column . '_country_id']);

				if ($country_info) {
					$order_data[$column . '_iso_code_2'] = $country_info['iso_code_2'];
					$order_data[$column . '_iso_code_3'] = $country_info['iso_code_3'];
				} else {
					$order_data[$column . '_iso_code_2'] = '';
					$order_data[$column . '_iso_code_3'] = '';
				}

				$zone_info = $this->model_localisation_zone->getZone($order_query->row[$column . '_zone_id']);

				if ($zone_info) {
					$order_data[$column . '_zone_code'] = $zone_info['code'];
				} else {
					$order_data[$column . '_zone_code'] = '';
				}

				$order_data[$column . '_custom_field'] = json_decode($order_query->row[$column . '_custom_field'], true);
			}

			return $order_data;
		}

		return [];
	}

	public function getProducts(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getOptions(int $order_id, int $order_product_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_option` WHERE `order_id` = '" . (int)$order_id . "' AND `order_product_id` = '" . (int)$order_product_id . "'");

		return $query->rows;
	}

	public function getVouchers(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = '" . (int)$order_id . "'");

		return $query->rows;
	}

	public function getTotals(int $order_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `sort_order` ASC");

		return $query->rows;
	}

	public function addHistory(int $order_id, int $order_status_id, string $comment = '', bool $notify = false, bool $override = false): int {
		$order_info = $this->getOrder($order_id);

		if ($order_info) {
			// Fraud Detection
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($order_info['customer_id']);

			if ($customer_info && $customer_info['safe']) {
				$safe = true;
			} else {
				$safe = false;
			}

			// Only do the fraud check if the customer is not on the safe list and the order status is changing into the complete or process order status
			if (!$safe && !$override && in_array($order_status_id, array_merge((array)$this->config->get('config_processing_status'), (array)$this->config->get('config_complete_status')))) {
				// Anti-Fraud
				$this->load->model('setting/extension');

				$extensions = $this->model_setting_extension->getExtensionsByType('fraud');

				foreach ($extensions as $extension) {
					if ($this->config->get('fraud_' . $extension['code'] . '_status')) {
						$this->load->model('extension/' . $extension['extension'] . '/fraud/' . $extension['code']);

						if (property_exists($this->{'model_extension_' . $extension['extension'] . '_fraud_' . $extension['code']}, 'check')) {
							$fraud_status_id = $this->{'model_extension_' . $extension['extension'] . '_fraud_' . $extension['code']}->check($order_info);

							if ($fraud_status_id) {
								$order_status_id = $fraud_status_id;
							}
						}
					}
				}
			}

			// If current order status is not processing or complete but new status is processing or complete then commence completing the order
			if (!in_array($order_info['order_status_id'], array_merge((array)$this->config->get('config_processing_status'), (array)$this->config->get('config_complete_status'))) && in_array($order_status_id, array_merge((array)$this->config->get('config_processing_status'), (array)$this->config->get('config_complete_status')))) {
				// Redeem coupon, vouchers and reward points
				$order_totals = $this->getTotals($order_id);

				foreach ($order_totals as $order_total) {
					$this->load->model('extension/' . $order_total['extension'] . '/total/' . $order_total['code']);

					if (property_exists($this->{'model_extension_' . $order_total['extension'] . '_total_' . $order_total['code']}, 'confirm')) {
						// Confirm coupon, vouchers and reward points
						$fraud_status_id = $this->{'model_extension_' . $order_total['extension'] . '_total_' . $order_total['code']}->confirm($order_info, $order_total);

						// If the balance on the coupon, vouchers and reward points is not enough to cover the transaction or has already been used then the fraud order status is returned.
						if ($fraud_status_id) {
							$order_status_id = $fraud_status_id;
						}
					}
				}

				// Stock subtraction
				$order_products = $this->getProducts($order_id);

				foreach ($order_products as $order_product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` - " . (int)$order_product['quantity'] . ") WHERE `product_id` = '" . (int)$order_product['product_id'] . "' AND `subtract` = '1'");

					// Stock subtraction from master product
					if ($order_product['master_id']) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` - " . (int)$order_product['quantity'] . ") WHERE `product_id` = '" . (int)$order_product['master_id'] . "' AND `subtract` = '1'");
					}

					$order_options = $this->getOptions($order_id, $order_product['order_product_id']);

					foreach ($order_options as $order_option) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = (`quantity` - " . (int)$order_product['quantity'] . ") WHERE `product_option_value_id` = '" . (int)$order_option['product_option_value_id'] . "' AND `subtract` = '1'");
					}
				}
			}

			// Affiliate add commission if complete status
			if (!in_array($order_info['order_status_id'], (array)$this->config->get('config_complete_status')) && in_array($order_status_id, (array)$this->config->get('config_complete_status')) && $order_info['affiliate_id'] && $this->config->get('config_affiliate_auto')) {
				// Add commission if sale is linked to affiliate referral.
				$this->load->language('account/order');

				$this->load->model('account/customer');

				if (!$this->model_account_customer->getTotalTransactionsByOrderId($order_id)) {
					$this->model_account_customer->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
				}
			}

			// Update the DB with the new statuses
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `order_status_id` = '" . (int)$order_status_id . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_history` SET `order_id` = '" . (int)$order_id . "', `order_status_id` = '" . (int)$order_status_id . "', `notify` = '" . (int)$notify . "', `comment` = '" . $this->db->escape($comment) . "', `date_added` = NOW()");

			$order_history_id = $this->db->getLastId();

			// If old order status is the processing or complete status but new status is not then commence restock, and remove coupon, voucher and reward history
			if (in_array($order_info['order_status_id'], array_merge((array)$this->config->get('config_processing_status'), (array)$this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge((array)$this->config->get('config_processing_status'), (array)$this->config->get('config_complete_status')))) {
				// Restock
				$order_products = $this->getProducts($order_id);

				foreach ($order_products as $order_product) {
					$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` + " . (int)$order_product['quantity'] . ") WHERE `product_id` = '" . (int)$order_product['product_id'] . "' AND `subtract` = '1'");

					// Restock the master product stock level if product is a variant
					if ($order_product['master_id']) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` + " . (int)$order_product['quantity'] . ") WHERE `product_id` = '" . (int)$order_product['master_id'] . "' AND `subtract` = '1'");
					}

					$order_options = $this->getOptions($order_id, $order_product['order_product_id']);

					foreach ($order_options as $order_option) {
						$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET `quantity` = (`quantity` + " . (int)$order_product['quantity'] . ") WHERE `product_option_value_id` = '" . (int)$order_option['product_option_value_id'] . "' AND `subtract` = '1'");
					}
				}

				// Remove coupon, vouchers and reward points history
				$order_totals = $this->getTotals($order_id);

				foreach ($order_totals as $order_total) {
					$this->load->model('extension/' . $order_total['extension'] . '/total/' . $order_total['code']);

					if (property_exists($this->{'model_extension_' . $order_total['extension'] . '_total_' . $order_total['code']}, 'unconfirm')) {
						$this->{'model_extension_' . $order_total['extension'] . '_total_' . $order_total['code']}->unconfirm($order_id);
					}
				}
			}

			// Affiliate remove commission.
			if (in_array($order_info['order_status_id'], (array)$this->config->get('config_complete_status')) && !in_array($order_status_id, (array)$this->config->get('config_complete_status')) && $order_info['affiliate_id']) {
				$this->load->model('account/customer');

				$this->model_account_customer->deleteTransactionByOrderId($order_id);
			}

			$this->cache->delete('product');

			return $order_history_id;
		}

		return 0;
	}
}
