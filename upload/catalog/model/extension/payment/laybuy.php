<?php
class ModelExtensionPaymentLaybuy extends Model {
	public function addTransaction($data = array(), $status) {
		$this->log('Report: ' . print_r($data, true), '1');

		$this->log('Status: ' . $status, '1');

		$this->db->query("INSERT INTO `" . DB_PREFIX . "laybuy_transaction` SET `order_id` = '" . (int)$data['order_id'] . "', `firstname` = '" . $this->db->escape($data['firstname']) . "', `lastname` = '" . $this->db->escape($data['lastname']) . "', `address` = '" . $this->db->escape($data['address']) . "', `suburb` = '" . $this->db->escape($data['suburb']) . "', `state` = '" . $this->db->escape($data['state']) . "', `country` = '" . $this->db->escape($data['country']) . "', `postcode` = '" . $this->db->escape($data['postcode']) . "', `email` = '" . $this->db->escape($data['email']) . "', `amount` = '" . (float)$data['amount'] . "', `currency` = '" . $this->db->escape($data['currency']) . "', `downpayment` = '" . $this->db->escape($data['downpayment']) . "', `months` = '" . (int)$data['months'] . "', `downpayment_amount` = '" . (float)$data['downpayment_amount'] . "', `payment_amounts` = '" . (float)$data['payment_amounts'] . "', `first_payment_due` = '" . $this->db->escape($data['first_payment_due']) . "', `last_payment_due` = '" . $this->db->escape($data['last_payment_due']) . "', `store_id` = '" . (int)$data['store_id'] . "', `status` = '" . (int)$status . "', `report` = '" . $this->db->escape($data['report']) . "', `paypal_profile_id` = '" . $this->db->escape($data['paypal_profile_id']) . "', `laybuy_ref_no` = '" . (int)$data['laybuy_ref_no'] . "', `date_added` = NOW()");
	}

	public function deleteRevisedTransaction($id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "laybuy_revise_request` WHERE `laybuy_revise_request_id` = '" . (int)$id . "'");
	}

	public function deleteTransactionByOrderId($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `order_id` = '" . (int)$order_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "laybuy_revise_request` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function getInitialPayments() {
		$minimum = $this->config->get('payment_laybuy_min_deposit') ? $this->config->get('payment_laybuy_min_deposit') : 20;

		$maximum = $this->config->get('payment_laybuy_max_deposit') ? $this->config->get('payment_laybuy_max_deposit') : 50;

		$initial_payments = array();

		for ($i = $minimum; $i <= $maximum; $i += 10) {
			$initial_payments[] = $i;
		}

		return $initial_payments;
	}

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/laybuy');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('payment_laybuy_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

		if ($this->config->get('payment_laybuy_total') > 0 && $this->config->get('payment_laybuy_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_laybuy_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		/* Condition for customer group */
		if ($status && $this->config->get('payment_laybuy_customer_group')) {
			if (isset($this->session->data['guest']) && in_array(0, $this->config->get('payment_laybuy_customer_group'))) {
				$status = true;
			} elseif ($this->customer->isLogged() && $this->session->data['customer_id']) {
				$this->load->model('account/customer');

				$customer = $this->model_account_customer->getCustomer($this->session->data['customer_id']);

				if (in_array($customer['customer_group_id'], $this->config->get('payment_laybuy_customer_group'))) {
					$this->session->data['customer_group_id'] = $customer['customer_group_id'];

					$status = true;
				} else {
					$status = false;
				}
			} else {
				$status = false;
			}
		}

		/* Condition for categories and products */
		if ($status && $this->config->get('payment_laybuy_category')) {
			$allowed_categories = $this->config->get('payment_laybuy_category');

			$xproducts = explode(',', $this->config->get('payment_laybuy_xproducts'));

			$cart_products = $this->cart->getProducts();

			foreach ($cart_products as $cart_product) {
				$product = array();

				if ($xproducts && in_array($cart_product['product_id'], $xproducts)) {
					$status = false;
					break;
				} else {
					$product = $this->db->query("SELECT GROUP_CONCAT(`category_id`) as `categories` FROM `" . DB_PREFIX . "product_to_category` WHERE `product_id` = '" . (int)$cart_product['product_id'] . "'");

					$product = $product->row;

					$product = explode(',', $product['categories']);

					if ($product && count(array_diff($product, $allowed_categories)) > 0) {
						$status = false;
						break;
					}
				}
			}
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'			=> 'laybuy',
				'title'			=> $this->language->get('text_title'),
				'terms'			=> '',
				'sort_order'	=> $this->config->get('payment_laybuy_sort_order')
			);
		}

		return $method_data;
	}

	public function getMonths() {
		$this->load->language('extension/payment/laybuy');

		$max_months = $this->config->get('payment_laybuy_max_months');

		if (!$max_months) {
			$max_months = 3;
		}

		if ($max_months < 1) {
			$max_months = 1;
		}

		$months = array();

		for ($i = 1; $i <= $max_months; $i++) {
			$months[] = array(
				'value' => $i,
				'label' => $i . ' ' . (($i > 1) ? $this->language->get('text_months') : $this->language->get('text_month'))
			);
		}

		return $months;
	}

	public function getPayPalProfileIds() {
		$query = $this->db->query("SELECT `paypal_profile_id` FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `status` = '1'");

		return $query->rows;
	}

	public function getRevisedTransaction($id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_revise_request` WHERE `laybuy_revise_request_id` = '" . (int)$id . "'");

		return $query->row;
	}

	public function getTransaction($id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `laybuy_transaction_id` = '" . (int)$id . "'");

		return $query->row;
	}

	public function getTransactionByLayBuyRefId($laybuy_ref_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `laybuy_ref_no` = '" . (int)$laybuy_ref_id . "'");

		return $query->row;
	}

	public function log($data, $step = 6) {
		if ($this->config->get('payment_laybuy_logging')) {
			$backtrace = debug_backtrace();

			$log = new Log('laybuy.log');

			$log->write('(' . $backtrace[$step]['class'] . '::' . $backtrace[$step]['function'] . ') - ' . $data);
		}
	}

	public function prepareTransactionReport($post_data) {
		$this->load->model('checkout/order');

		$this->load->language('extension/payment/laybuy');

		$data = array_change_key_case($post_data, CASE_LOWER);

		$data['order_id'] = $data['custom'];

		$order_info = $this->model_checkout_order->getOrder($data['order_id']);

		$date_added = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

		$data['store_id'] = $order_info['store_id'];

		$data['date_added'] = $order_info['date_added'];

		$data['first_payment_due'] = date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $data['first_payment_due'])));

		$data['last_payment_due'] = date('Y-m-d h:i:s', strtotime(str_replace('/', '-', $data['last_payment_due'])));

		$months = (int)$data['months'];

		$report_content = array();

		$report_content[] = array(
			'instalment'	=> 0,
			'amount'		=> $this->currency->format($data['downpayment_amount'], $data['currency']),
			'date'			=> $date_added,
			'pp_trans_id'	=> $data['dp_paypal_txn_id'],
			'status'		=> 'Completed'
		);

		for ($month = 1; $month <= $months; $month++) {
			$date = date("Y-m-d h:i:s", strtotime($data['first_payment_due'] . " +" . ($month -1) . " month"));
			$date = date($this->language->get('date_format_short'), strtotime($date));

			$report_content[] = array(
			'instalment'	=> $month,
			'amount'		=> $this->currency->format($data['payment_amounts'], $data['currency']),
			'date'			=> $date,
			'pp_trans_id'	=> '',
			'status'		=> 'Pending'
			);
		}

		$data['report'] = json_encode($report_content);

		return $data;
	}

	public function updateCronRunTime() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `key` = 'laybuy_cron_time'");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '0', `code` = 'laybuy', `key` = 'laybuy_cron_time', `value` = NOW(), `serialized` = '0'");
	}

	public function updateTransaction($id, $status, $report, $transaction) {
		$this->db->query("UPDATE `" . DB_PREFIX . "laybuy_transaction` SET `status` = '" . (int)$status . "', `report` = '" . $this->db->escape($report) . "', `transaction` = '" . (int)$transaction . "' WHERE `laybuy_transaction_id` = '" . (int)$id . "'");
	}
}