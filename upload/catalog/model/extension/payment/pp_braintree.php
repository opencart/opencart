<?php
class ModelExtensionPaymentPPBraintree extends Model {
	public function generateToken($gateway, $data) {
		try {
			if ($gateway != null) {
				$client_token = $gateway->clientToken()->generate($data);
			} else {
				$client_token = Braintree_ClientToken::generate($data);
			}

			return $client_token;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function addTransaction($gateway, $data) {
		try {
			if ($gateway != null) {
				$transaction = $gateway->transaction()->sale($data);
			} else {
				$transaction = Braintree_Transaction::sale($data);
			}

			return $transaction;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function getCustomer($gateway, $customer_id, $log = true) {
		try {
			if ($gateway != null) {
				$customer = $gateway->customer()->find($customer_id);
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

	public function getPaymentMethod($gateway, $token) {
		try {
			if ($gateway != null) {
				$payment_method = $gateway->paymentMethod()->find($token);
			} else {
				$payment_method = Braintree_PaymentMethod::find($token);
			}

			return $payment_method;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function addPaymentMethod($gateway, $data) {
		try {
			if ($gateway != null) {
				$payment_method = $gateway->paymentMethod()->create($data);
			} else {
				$payment_method = Braintree_PaymentMethod::create($data);
			}

			return $payment_method;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function deletePaymentMethod($gateway, $token) {
		try {
			if ($gateway != null) {
				$gateway->paymentMethod()->delete($token);
			} else {
				Braintree_PaymentMethod::delete($token);
			}

			return true;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function getPaymentMethodNonce($gateway, $token) {
		try {
			if ($gateway != null) {
				$response = $gateway->paymentMethodNonce()->find($token);
			} else {
				$response = Braintree_PaymentMethodNonce::find($token);
			}

			return $response;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function createPaymentMethodNonce($gateway, $token) {
		try {
			if ($gateway != null) {
				$response = $gateway->paymentMethodNonce()->create($token);
			} else {
				$response = Braintree_PaymentMethodNonce::create($token);
			}

			return $response;
		} catch (Exception $e) {
			$this->log($e->getMessage());

			return false;
		}
	}

	public function setCredentials() {
		Braintree_Configuration::environment($this->config->get('payment_pp_braintree_environment'));
		Braintree_Configuration::merchantId($this->config->get('payment_pp_braintree_merchant_id'));
		Braintree_Configuration::publicKey($this->config->get('payment_pp_braintree_public_key'));
		Braintree_Configuration::privateKey($this->config->get('payment_pp_braintree_private_key'));
	}

	public function setGateway($access_token) {
		return new Braintree_Gateway(array('accessToken' => $access_token));
	}

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/pp_braintree');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_pp_braintree_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('payment_pp_braintree_total') > 0 && $this->config->get('payment_pp_braintree_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('payment_pp_braintree_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'		 => 'pp_braintree',
				'title'		 => $this->language->get('text_title'),
				'terms'		 => '',
				'sort_order' => $this->config->get('payment_pp_braintree_sort_order')
			);
		}

		return $method_data;
	}

	public function getSupportedCurrencies() {
		$currencies = array();

		foreach ($this->config->get('payment_pp_braintree_account') as $currency => $account) {
			if ($account['status']) {
				$currencies[] = $currency;
			}
		}

		return $currencies;
	}

	public function log($data) {
		if ($this->config->get('payment_pp_braintree_debug')) {
			$log = new Log('braintree.log');
			$log->write(print_r($data, true));
		}
	}
}
