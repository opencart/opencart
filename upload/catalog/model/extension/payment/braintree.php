<?php
class ModelExtensionPaymentBraintree extends Model {
	private $gateway = null;

	public function generateToken($data) {
		try {
			if ($this->gateway != null) {
				$client_token = $this->gateway->clientToken->generate($data);
			} else {
				$client_token = Braintree_ClientToken::generate($data);
			}

			return $client_token;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function addTransaction($data) {
		try {
			if ($this->gateway != null) {
				$transaction = $this->gateway->transaction->sale($data);
			} else {
				$transaction = Braintree_Transaction::sale($data);
			}

			return $transaction;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function getCustomer($customer_id, $log = true) {
		try {
			if ($this->gateway != null) {
				$customer = $this->gateway->customer->find($customer_id);
			} else {
				$customer = Braintree_Customer::find($customer_id);
			}

			return $customer;
		} catch (Exception $e) {
			if ($log) {
				$this->log($e->getMessage());
			}

			return false;
		}
	}

	public function getPaymentMethod($token) {
		try {
			if ($this->gateway != null) {
				$payment_method = $this->gateway->paymentMethod->find($token);
			} else {
				$payment_method = Braintree_PaymentMethod::find($token);
			}

			return $payment_method;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function addPaymentMethod($data) {
		try {
			if ($this->gateway != null) {
				$payment_method = $this->gateway->paymentMethod->create($data);
			} else {
				$payment_method = Braintree_PaymentMethod::create($data);
			}

			return $payment_method;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function deletePaymentMethod($token) {
		try {
			if ($this->gateway != null) {
				$this->gateway->paymentMethod->delete($token);
			} else {
				Braintree_PaymentMethod::delete($token);
			}

			return true;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function getPaymentMethodNonce($token) {
		try {
			if ($this->gateway != null) {
				$payment_method = $this->gateway->paymentMethodNonce->find($token);
			} else {
				$payment_method = Braintree_PaymentMethodNonce::find($token);
			}

			return $payment_method;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function addPaymentMethodNonce($token) {
		try {
			if ($this->gateway != null) {
				$payment_method = $this->gateway->paymentMethodNonce->create($token);
			} else {
				$payment_method = Braintree_PaymentMethodNonce::create($token);
			}

			return $payment_method;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function setCredentials() {
		if ($this->config->get('braintree_access_token') != '') {
			$this->gateway = new Braintree_Gateway([
				'accessToken' => $this->config->get('braintree_access_token'),
			]);
		} else {
			Braintree_Configuration::environment($this->config->get('braintree_environment'));
			Braintree_Configuration::merchantId($this->config->get('braintree_merchant_id'));
			Braintree_Configuration::publicKey($this->config->get('braintree_public_key'));
			Braintree_Configuration::privateKey($this->config->get('braintree_private_key'));
		}
	}

	public function getMethod($address, $total) {
		$this->load->language('payment/braintree');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('braintree_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('braintree_total') > 0 && $this->config->get('braintree_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('braintree_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		if (!in_array($this->currency->getCode(), $this->getSupportedCurrencies())) {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'		 => 'braintree',
				'title'		 => $this->language->get('text_title'),
				'terms'		 => '',
				'sort_order' => $this->config->get('braintree_sort_order')
			);
		}

		return $method_data;
	}

	public function getSupportedCurrencies() {
		$currencies = array();

		foreach ($this->config->get('braintree_account') as $currency => $account) {
			if ($account['status']) {
				$currencies[] = $currency;
			}
		}

		return $currencies;
	}

	public function log($data) {
		if ($this->config->get('braintree_debug')) {
			$backtrace = debug_backtrace();
			$log = new Log('braintree.log');
			$log->write('(' . $backtrace[1]['class'] . '::' . $backtrace[1]['function'] . ') - ' . print_r($data, true));
		}
	}
}