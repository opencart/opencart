<?php
class ControllerExtensionPaymentBraintree extends Controller {
	private $customer_id_prefix = 'braintree_oc_';
	private $gateway = null;

	public function index() {
		$this->initialise();

		$this->load->language('extension/payment/braintree');

		$data['text_remember'] = $this->language->get('text_remember');
		$data['text_remove'] = $this->language->get('text_remove');
		$data['text_remove_confirm'] = $this->language->get('text_remove_confirm');
		$data['text_month'] = $this->language->get('text_month');
		$data['text_year'] = $this->language->get('text_year');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_new_method'] = $this->language->get('text_new_method');
		$data['text_saved_method'] = $this->language->get('text_saved_method');
		$data['text_paypal'] = $this->language->get('text_paypal');
		$data['text_pay_by_paypal'] = $this->language->get('text_pay_by_paypal');
		$data['text_authentication'] = $this->language->get('text_authentication');

		$data['entry_saved_methods'] = $this->language->get('entry_saved_methods');
		$data['entry_new'] = $this->language->get('entry_new');
		$data['entry_card'] = $this->language->get('entry_card');
		$data['entry_expires'] = $this->language->get('entry_expires');
		$data['entry_cvv'] = $this->language->get('entry_cvv');
		$data['entry_remember_method'] = $this->language->get('entry_remember_method');
		$data['entry_card_placeholder'] = $this->language->get('entry_card_placeholder');
		$data['entry_month_placeholder'] = $this->language->get('entry_month_placeholder');
		$data['entry_year_placeholder'] = $this->language->get('entry_year_placeholder');
		$data['entry_cvv_placeholder'] = $this->language->get('entry_cvv_placeholder');

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_delete_card'] = $this->language->get('button_delete_card');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['error_alert_fields_empty'] = $this->language->get('error_alert_fields_empty');
		$data['error_alert_fields_invalid'] = $this->language->get('error_alert_fields_invalid');
		$data['error_alert_failed_token'] = $this->language->get('error_alert_failed_token');
		$data['error_alert_failed_network'] = $this->language->get('error_alert_failed_network');
		$data['error_alert_unknown'] = $this->language->get('error_alert_unknown');

		$data['payment_url'] = $this->url->link('extension/payment/braintree/payment', '', true);
		$data['vaulted_url'] = $this->url->link('extension/payment/braintree/vaulted', '', true);

		$data['braintree_3ds_status'] = $this->config->get('braintree_3ds_status');
		$data['braintree_vault_cvv_3ds'] = $this->config->get('braintree_vault_cvv_3ds');
		$data['braintree_paypal_option'] = $this->config->get('braintree_paypal_option');
		$data['braintree_vault_cvv'] = $this->config->get('braintree_vault_cvv');
		$data['braintree_settlement_immediate'] = $this->config->get('braintree_settlement_immediate');
		$data['braintree_paypal_button_colour'] = $this->config->get('braintree_paypal_button_colour');
		$data['braintree_paypal_button_size'] = $this->config->get('braintree_paypal_button_size');
		$data['braintree_paypal_button_shape'] = $this->config->get('braintree_paypal_button_shape');

		if (!$this->session->data['order_id']) {
			return false;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$create_token = array();
		$merchant_id = $this->config->get('braintree_merchant_id');

		if ($this->gateway == '') {
			$merchant_accounts = $this->config->get('braintree_account');

			foreach ($merchant_accounts as $merchant_account_currency => $merchant_account) {
				if (($merchant_account_currency == $order_info['currency_code']) && !empty($merchant_account['merchant_account_id'])) {
					$create_token['merchantAccountId'] = $merchant_account['merchant_account_id'];

					$merchant_id = $merchant_account['merchant_account_id'];

					break;
				}
			}
		}

		$data['merchant_id'] = $merchant_id;

		if ($this->customer->isLogged() && $this->config->get('braintree_vault')) {
			$data['braintree_vault'] = 1;
			$vaulted_customer_info = $this->model_extension_payment_braintree->getCustomer($this->gateway, $this->customer_id_prefix . $this->customer->getId(), false);
		} else {
			$data['braintree_vault'] = 0;
			$vaulted_customer_info = false;
		}

		$data['client_token'] = $this->model_extension_payment_braintree->generateToken($this->gateway, $create_token);
		$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['currency_code'] = $order_info['currency_code'];

		// disable paypal option if currency is not in supported array
		if (!in_array($order_info['currency_code'], array('USD', 'EUR', 'GBP', 'CAD', 'AUD', 'DKK', 'NOK', 'PLN', 'SEK', 'CHF', 'TRY'))) {
			$data['braintree_paypal_option'] = false;
		}

		// pass shipping info to paypal if set
		if ($data['braintree_paypal_option'] && $this->cart->hasShipping()) {
			$data['customer_shipping_address'] = array(
				'name'			=> addslashes($order_info['shipping_firstname']) . ' ' . addslashes($order_info['shipping_lastname']),
				'line_1'		=> addslashes($order_info['shipping_address_1']),
				'line_2'		=> addslashes($order_info['shipping_address_2']),
				'city'			=> addslashes($order_info['shipping_city']),
				'state'			=> addslashes($order_info['shipping_zone_code']),
				'post_code'		=> addslashes($order_info['shipping_postcode']),
				'country_code' 	=> addslashes($order_info['shipping_iso_code_2']),
				'phone'			=> addslashes($order_info['telephone']),
			);
		}

		$vaulted_payment_methods = array('cards', 'paypal');
		$vaulted_payment_count = 0;

		if ($vaulted_customer_info) {
			if ($vaulted_customer_info->creditCards && $this->config->get('braintree_vault') == 1) {
				foreach ($vaulted_customer_info->creditCards as $credit_card) {
					$vaulted_payment_methods['cards'][] = array(
						'image'	  => $credit_card->imageUrl,
						'name'	  => sprintf($this->language->get('text_vaulted_payment_method_name'), $credit_card->cardType, $credit_card->last4, $credit_card->expirationDate),
						'token'	  => $credit_card->token,
						'expired' => $credit_card->expired,
						'default' => $credit_card->default
					);
				}
			}

			if ($vaulted_customer_info->paypalAccounts && $this->config->get('braintree_vault') == 1) {
				foreach ($vaulted_customer_info->paypalAccounts as $paypal_account) {
					$vaulted_payment_methods['paypal'][] = array(
						'image'	  => $paypal_account->imageUrl,
						'name'	  => $paypal_account->email,
						'token'	  => $paypal_account->token,
						'default' => $paypal_account->default
					);
				}
			}

			$vaulted_payment_count = count($vaulted_customer_info->paymentMethods);
		}

		$data['vaulted_payment_methods'] = $vaulted_payment_methods;
		$data['vaulted_payment_count'] = $vaulted_payment_count;

		$data['form_styles'] = json_encode("{
		  'input': { 'font-size': '12px', 'font-family': 'Source Sans Pro, sans-serif', 'color': '#7A8494' },
		  'input.invalid': { 'color': 'red' },
		  'input.valid': { 'color': 'green' }
	  	}");

		if ($this->customer->isLogged()) {
			$data['guest'] = false;
		} else {
			$data['guest'] = true;
		}

		return $this->load->view('extension/payment/braintree', $data);
	}

	public function payment() {
		set_time_limit(120);

		$this->initialise();

		$this->load->language('extension/payment/braintree');

		$this->load->model('checkout/order');
		$this->load->model('extension/payment/braintree');

		$this->model_extension_payment_braintree->log('Starting payment');
		$this->model_extension_payment_braintree->log($this->request->post);

		$success = true;

		if (!$this->session->data['order_id']) {
			$this->model_extension_payment_braintree->log('Session data: order_id not found');

			$success = false;
		}

		if (isset($this->request->post['device_data'])) {
			$device_data = $this->request->post['device_data'];
		} else {
			$this->model_extension_payment_braintree->log('Post data: device_data not found');

			$device_data = '';

			$success = false;
		}


		// nonce only =
			// new payment option
			// 3d secured vaulted card
		// nonce and token = vaulted card and cvv type nonce (verify CVV for stored card)
		// token only = existing card/paypal, no cvv or 3ds check




		if (isset($this->request->post['payment_method_token'])) {
			$payment_method_token = $this->request->post['payment_method_token'];
		} else {
			$this->model_extension_payment_braintree->log('Post data: payment_method_token not found');
			$payment_method_token = '';
		}

		if (isset($this->request->post['payment_method_nonce'])) {
			$payment_method_nonce = $this->request->post['payment_method_nonce'];
		} else {
			$this->model_extension_payment_braintree->log('Post data: payment_method_nonce not found');
			$payment_method_nonce = '';
		}

		if ($payment_method_nonce == '' && $payment_method_token == '') {
			$success = false;
		}

		//Start creating transaction array
		if ($success) {
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			$create_sale = array(
				'amount'			 => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false),
				'channel'			 => 'OpenCart_Cart_vzero',
				'orderId'			 => $order_info['order_id'],
				'deviceData'		 => $device_data,
				'customer'           => array(
					'firstName' => $order_info['firstname'],
					'lastName'  => $order_info['lastname'],
					'phone'		=> $order_info['telephone'],
					'fax'		=> $order_info['fax'],
					'email'		=> $order_info['email']
				),
				'billing'			=> array(
					'firstName'			=> $order_info['payment_firstname'],
					'lastName'			=> $order_info['payment_lastname'],
					'company'			=> $order_info['payment_company'],
					'streetAddress'		=> $order_info['payment_address_1'],
					'extendedAddress'	=> $order_info['payment_address_2'],
					'locality'			=> $order_info['payment_city'],
					'countryCodeAlpha2' => $order_info['payment_iso_code_2'],
					'postalCode'		=> $order_info['payment_postcode'],
					'region'			=> $order_info['payment_zone_code']
				),
				'options' => array('three_d_secure' => array('required' => false))
			);

			//Add shipping details
			if ($this->cart->hasShipping()) {
				$create_sale['shipping'] = array(
					'firstName'			=> $order_info['shipping_firstname'],
					'lastName'			=> $order_info['shipping_lastname'],
					'company'			=> $order_info['shipping_company'],
					'streetAddress'		=> $order_info['shipping_address_1'],
					'extendedAddress'	=> $order_info['shipping_address_2'],
					'locality'			=> $order_info['shipping_city'],
					'countryCodeAlpha2' => $order_info['shipping_iso_code_2'],
					'postalCode'		=> $order_info['shipping_postcode'],
					'region'			=> $order_info['shipping_zone_code']
				);
			}

			if ($this->customer->isLogged() && $this->config->get('braintree_vault')) {
				$customer_id = $this->customer_id_prefix . $this->customer->getId();

				$vaulted_customer_info = $this->model_extension_payment_braintree->getCustomer($this->gateway, $customer_id, false);

				if ($vaulted_customer_info) {
					$create_sale['customerId'] = $customer_id;
				} else {
					$create_sale['customer']['id'] = $customer_id;
				}

				if (isset($this->request->post['vault_method']) && $this->request->post['vault_method'] == '1') {
					$create_sale['options']['storeInVaultOnSuccess'] = true;
				}
			}

			if ($payment_method_token != '') {
				$create_sale['paymentMethodToken'] = $payment_method_token;
			}

			if ($payment_method_nonce != '') {
				$create_sale['paymentMethodNonce'] = $payment_method_nonce;
			}

			if ($this->gateway == '') {
				$merchant_accounts = $this->config->get('braintree_account');

				foreach ($merchant_accounts as $merchant_account_currency => $merchant_account) {
					if (($merchant_account_currency == $order_info['currency_code']) && !empty($merchant_account['merchant_account_id'])) {
						$create_sale['merchantAccountId'] = $merchant_account['merchant_account_id'];
					}
				}
			}

			if ($this->config->get('braintree_settlement_immediate') == 1) {
				$create_sale['options']['submitForSettlement'] = true;
			} else {
				$create_sale['options']['submitForSettlement'] = false;
			}
		}

		// If the $payment_method_token is not empty it indicates the vaulted payment used CVV or was set to none
		if ($success && (($this->config->get('braintree_3ds_status') && $payment_method_token == '') || ($this->config->get('braintree_vault_cvv_3ds') == '3ds' && $payment_method_token != ''))) {
			$nonce_info = $this->model_extension_payment_braintree->getPaymentMethodNonce($this->gateway, $payment_method_nonce);

			$this->model_extension_payment_braintree->log($nonce_info);

			if ($nonce_info->type == 'CreditCard' && $this->config->get('braintree_3ds_status') == 1) {
				$create_sale['options']['three_d_secure'] = array(
					'required' => true
				);

				$three_ds_info = $nonce_info->threeDSecureInfo;

				if (!empty($three_ds_info)) {
					$success = false;

					switch ($three_ds_info->status) {
						case 'unsupported_card':
							if ($nonce_info->details['cardType'] == 'American Express') {
								$success = true;
							} else {
								$success = $this->config->get('braintree_3ds_unsupported_card');
							}
							break;
						case 'lookup_error':
							$success = $this->config->get('braintree_3ds_lookup_error');
							break;
						case 'lookup_enrolled':
							$success = $this->config->get('braintree_3ds_lookup_enrolled');
							break;
						case 'authenticate_successful_issuer_not_participating':
							$success = $this->config->get('braintree_3ds_not_participating');
							break;
						case 'authentication_unavailable':
							$success = $this->config->get('braintree_3ds_unavailable');
							break;
						case 'authenticate_signature_verification_failed':
							$success = $this->config->get('braintree_3ds_signature_failed');
							break;
						case 'authenticate_successful':
							$success = $this->config->get('braintree_3ds_successful');
							break;
						case 'authenticate_attempt_successful':
							$success = $this->config->get('braintree_3ds_attempt_successful');
							break;
						case 'authenticate_failed':
							$success = $this->config->get('braintree_3ds_failed');
							break;
						case 'authenticate_unable_to_authenticate':
							$success = $this->config->get('braintree_3ds_unable_to_auth');
							break;
						case 'authenticate_error':
							$success = $this->config->get('braintree_3ds_error');
							break;
					}
				} else {
					$this->model_extension_payment_braintree->log('Liability shift failed, nonce was not 3D Secured');

					$success = false;
				}
			}
		}

		//Create transaction
		if ($success) {
			$this->model_extension_payment_braintree->log('Transaction info before send:');
			$this->model_extension_payment_braintree->log($create_sale);
			$this->model_extension_payment_braintree->log($this->request->post);
			$this->model_extension_payment_braintree->log($this->request->get);
			$transaction = $this->model_extension_payment_braintree->addTransaction($this->gateway, $create_sale);

			$order_status_id = 0;
			switch ($transaction->transaction->status) {
				case 'authorization_expired':
					$order_status_id = $this->config->get('braintree_authorization_expired_id');
					break;
				case 'authorized':
					$order_status_id = $this->config->get('braintree_authorized_id');
					break;
				case 'authorizing':
					$order_status_id = $this->config->get('braintree_authorizing_id');
					break;
				case 'settlement_pending':
					$order_status_id = $this->config->get('braintree_settlement_pending_id');
					break;
				case 'failed':
					$order_status_id = $this->config->get('braintree_failed_id');
					break;
				case 'gateway_rejected':
					$order_status_id = $this->config->get('braintree_gateway_rejected_id');
					break;
				case 'processor_declined':
					$order_status_id = $this->config->get('braintree_processor_declined_id');
					break;
				case 'settled':
					$order_status_id = $this->config->get('braintree_settled_id');
					break;
				case 'settling':
					$order_status_id = $this->config->get('braintree_settling_id');
					break;
				case 'submitted_for_settlement':
					$order_status_id = $this->config->get('braintree_submitted_for_settlement_id');
					break;
				case 'voided':
					$order_status_id = $this->config->get('braintree_voided_id');
					break;
			}

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id);

			if ($transaction->success) {
				$this->model_extension_payment_braintree->log('Transaction success, details below');
				$this->model_extension_payment_braintree->log($transaction);

				$this->response->redirect($this->url->link('checkout/success', '', true));
			} else {
				$this->model_extension_payment_braintree->log('Transaction failed, details below');
				$this->model_extension_payment_braintree->log($transaction);

				$this->session->data['error'] = $this->language->get('error_process_order');
				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			}
		}

		//If this is reached, transaction has failed
		$this->model_extension_payment_braintree->log('Transaction reached end of method without being handled, failure');

		if (isset($this->session->data['order_id'])) {
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('braintree_failed_id'));
		}

		$this->response->redirect($this->url->link('checkout/failure', '', true));
	}

	public function getNonceFromToken() {
		$this->initialise();

		$this->load->language('extension/payment/braintree');

		$this->load->model('extension/payment/braintree');

		$this->model_extension_payment_braintree->log('Starting vaulted');
		$this->model_extension_payment_braintree->log($this->request->post);

		$json = array();

		$json['payment_method'] = '';

		$success = true;

		if (!isset($this->request->post['vaulted_payment_token'])) {
			$success = false;
		}

		if ($success) {
			$payment_method = $this->model_extension_payment_braintree->createPaymentMethodNonce($this->gateway, $this->request->post['vaulted_payment_token']);

			if ($payment_method && $payment_method->success) {
				$json['payment_method'] = array(
					'type'  => $payment_method->paymentMethodNonce->type,
					'nonce' => $payment_method->paymentMethodNonce->nonce
				);
			} else {
				$success = false;
			}
		}

		$json['success'] = $success;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->initialise();

		$this->load->language('extension/payment/braintree');

		$this->load->model('extension/payment/braintree');

		$json = array();

		$json['success'] = false;

		if (isset($this->request->post['vaulted_payment_method'])) {
			$vaulted_payment_method = $this->request->post['vaulted_payment_method'];
		} else {
			$vaulted_payment_method = '';
		}

		$delete_payment_method = $this->model_extension_payment_braintree->deletePaymentMethod($this->gateway, $vaulted_payment_method);

		if ($delete_payment_method) {
			$json['success'] = $this->language->get('text_method_removed');
		} else {
			$json['error'] = $this->language->get('text_method_not_removed');
		}

		$customer_info = $this->model_extension_payment_braintree->getCustomer($this->gateway, $this->customer_id_prefix . $this->customer->getId());

		$vaulted_payment_count = 0;

		if ($customer_info && $customer_info->paymentMethods) {
			$vaulted_payment_count = count($customer_info->paymentMethods);
		}

		$json['vaulted_payment_count'] = $vaulted_payment_count;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function initialise() {
		$this->load->model('extension/payment/braintree');

		if ($this->config->get('braintree_access_token') != '') {
			$this->gateway = $this->model_extension_payment_braintree->setGateway($this->config->get('braintree_access_token'));
		} else {
			$this->model_extension_payment_braintree->setCredentials();
		}
	}
}
