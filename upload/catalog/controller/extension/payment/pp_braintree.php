<?php
class ControllerExtensionPaymentPPBraintree extends Controller {
	private $customer_id_prefix = 'braintree_oc_';
	private $gateway = null;

	public function index() {
		$this->initialise();

		$this->load->language('extension/payment/pp_braintree');

		$data['payment_url'] = $this->url->link('extension/payment/pp_braintree/payment', 'language=' . $this->config->get('config_language'));
		$data['vaulted_url'] = $this->url->link('extension/payment/pp_braintree/vaulted', 'language=' . $this->config->get('config_language'));

		$data['payment_pp_braintree_3ds_status'] = $this->config->get('payment_pp_braintree_3ds_status');
		$data['payment_pp_braintree_vault_cvv_3ds'] = $this->config->get('payment_pp_braintree_vault_cvv_3ds');
		$data['payment_pp_braintree_paypal_option'] = $this->config->get('payment_pp_braintree_paypal_option');
		$data['payment_pp_braintree_vault_cvv'] = $this->config->get('payment_pp_braintree_vault_cvv');
		$data['payment_pp_braintree_settlement_immediate'] = $this->config->get('payment_pp_braintree_settlement_immediate');
		$data['payment_pp_braintree_paypal_button_colour'] = $this->config->get('payment_pp_braintree_paypal_button_colour');
		$data['payment_pp_braintree_paypal_button_size'] = $this->config->get('payment_pp_braintree_paypal_button_size');
		$data['payment_pp_braintree_paypal_button_shape'] = $this->config->get('payment_pp_braintree_paypal_button_shape');

		if (!$this->session->data['order_id']) {
			return false;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$create_token = array();
		$merchant_id = $this->config->get('payment_pp_braintree_merchant_id');

		if ($this->gateway == '') {
			$merchant_accounts = $this->config->get('payment_pp_braintree_account');

			foreach ($merchant_accounts as $merchant_account_currency => $merchant_account) {
				if (($merchant_account_currency == $order_info['currency_code']) && !empty($merchant_account['merchant_account_id'])) {
					$create_token['merchantAccountId'] = $merchant_account['merchant_account_id'];

					$merchant_id = $merchant_account['merchant_account_id'];

					break;
				}
			}
		}

		$data['merchant_id'] = $merchant_id;

		if ($this->customer->isLogged() && ($this->config->get('payment_pp_braintree_card_vault') || $this->config->get('payment_pp_braintree_paypal_vault'))) {
			$data['payment_pp_braintree_card_vault'] = $this->config->get('payment_pp_braintree_card_vault');
			$data['payment_pp_braintree_paypal_vault'] = $this->config->get('payment_pp_braintree_paypal_vault');
			$data['payment_pp_braintree_card_check_vault'] = $this->config->get('payment_pp_braintree_card_check_vault');
			$data['payment_pp_braintree_paypal_check_vault'] = $this->config->get('payment_pp_braintree_paypal_check_vault');
			$vaulted_customer_info = $this->model_extension_payment_pp_braintree->getCustomer($this->gateway, $this->customer_id_prefix . $this->customer->getId(), false);
		} else {
			$data['payment_pp_braintree_card_vault'] = 0;
			$data['payment_pp_braintree_paypal_vault'] = 0;
			$data['payment_pp_braintree_card_check_vault'] = 0;
			$data['payment_pp_braintree_paypal_check_vault'] = 0;
			$vaulted_customer_info = false;
		}

		$data['client_token'] = $this->model_extension_payment_pp_braintree->generateToken($this->gateway, $create_token);
		$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		$data['currency_code'] = $order_info['currency_code'];

		// disable paypal option if currency is not in supported array
		if (!in_array($order_info['currency_code'], array('USD', 'EUR', 'GBP', 'CAD', 'AUD', 'DKK', 'NOK', 'PLN', 'SEK', 'CHF', 'TRY'))) {
			$data['payment_pp_braintree_paypal_option'] = false;
		}

		// pass shipping info to paypal if set
		if ($data['payment_pp_braintree_paypal_option'] && $this->cart->hasShipping()) {
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
			$vaulted_card_count = 0;
			$vaulted_paypal_count = 0;

			if ($vaulted_customer_info->creditCards && $this->config->get('payment_pp_braintree_card_vault') == 1) {
				$vaulted_card_count = count($vaulted_customer_info->creditCards);

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

			if ($vaulted_customer_info->paypalAccounts && $this->config->get('payment_pp_braintree_paypal_vault') == 1) {
				$vaulted_paypal_count = count($vaulted_customer_info->paypalAccounts);

				foreach ($vaulted_customer_info->paypalAccounts as $paypal_account) {
					$vaulted_payment_methods['paypal'][] = array(
						'image'	  => $paypal_account->imageUrl,
						'name'	  => $paypal_account->email,
						'token'	  => $paypal_account->token,
						'default' => $paypal_account->default
					);
				}
			}

			$vaulted_payment_count = $vaulted_card_count + $vaulted_paypal_count;
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

		return $this->load->view('extension/payment/pp_braintree', $data);
	}

	public function payment() {
		//set_time_limit(120);

		$this->initialise();

		$this->load->language('extension/payment/pp_braintree');

		$this->load->model('checkout/order');
		$this->load->model('extension/payment/pp_braintree');

		$this->model_extension_payment_pp_braintree->log('Starting payment');
		$this->model_extension_payment_pp_braintree->log($this->request->post);

		$success = true;

		if (!$this->session->data['order_id']) {
			$this->model_extension_payment_pp_braintree->log('Session data: order_id not found');

			$success = false;
		}

		if (isset($this->request->post['device_data'])) {
			$device_data = $this->request->post['device_data'];
		} else {
			$this->model_extension_payment_pp_braintree->log('Post data: device_data not found');

			$device_data = '';

			$success = false;
		}

		if (isset($this->request->post['payment_method_token'])) {
			$payment_method_token = $this->request->post['payment_method_token'];
		} else {
			$this->model_extension_payment_pp_braintree->log('Post data: payment_method_token not found');
			$payment_method_token = '';
		}

		if (isset($this->request->post['payment_method_nonce'])) {
			$payment_method_nonce = $this->request->post['payment_method_nonce'];
		} else {
			$this->model_extension_payment_pp_braintree->log('Post data: payment_method_nonce not found');
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

			if ($this->customer->isLogged() && ($this->config->get('payment_pp_braintree_card_vault') || $this->config->get('payment_pp_braintree_paypal_vault'))) {
				$customer_id = $this->customer_id_prefix . $this->customer->getId();

				$vaulted_customer_info = $this->model_extension_payment_pp_braintree->getCustomer($this->gateway, $customer_id, false);

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

				// unset the billing info for a vaulted payment
				$create_sale['billing'] = array();
			}

			if ($payment_method_nonce != '') {
				$create_sale['paymentMethodNonce'] = $payment_method_nonce;
			}

			if ($this->gateway == '') {
				$merchant_accounts = $this->config->get('payment_pp_braintree_account');

				foreach ($merchant_accounts as $merchant_account_currency => $merchant_account) {
					if (($merchant_account_currency == $order_info['currency_code']) && !empty($merchant_account['merchant_account_id'])) {
						$create_sale['merchantAccountId'] = $merchant_account['merchant_account_id'];
					}
				}
			}

			if ($this->config->get('payment_pp_braintree_settlement_immediate') == 1) {
				$create_sale['options']['submitForSettlement'] = true;
			} else {
				$create_sale['options']['submitForSettlement'] = false;
			}
		}

		// If the $payment_method_token is not empty it indicates the vaulted payment used CVV or was set to none
		if ($success && (($this->config->get('payment_pp_braintree_3ds_status') == 1 && $payment_method_token == '') || ($this->config->get('payment_pp_braintree_vault_cvv_3ds') == '3ds' && $payment_method_token != ''))) {
			$nonce_info = $this->model_extension_payment_pp_braintree->getPaymentMethodNonce($this->gateway, $payment_method_nonce);

			$this->model_extension_payment_pp_braintree->log($nonce_info);

			if ($nonce_info->type == 'CreditCard' && $this->config->get('payment_pp_braintree_3ds_status') == 1) {
				$create_sale['options']['three_d_secure'] = array(
					'required' => true
				);

				$three_ds_info = array();

				if (isset($nonce_info->threeDSecureInfo) && !empty($nonce_info->threeDSecureInfo)) {
					$three_ds_info = $nonce_info->threeDSecureInfo;
				}

				if (!empty($three_ds_info)) {
					$success = false;

					switch ($three_ds_info->status) {
						case 'unsupported_card':
							if ($nonce_info->details['cardType'] == 'American Express') {
								$success = true;
							} else {
								$success = $this->config->get('payment_pp_braintree_3ds_unsupported_card');
							}
							break;
						case 'lookup_error':
							$success = $this->config->get('payment_pp_braintree_3ds_lookup_error');
							break;
						case 'lookup_enrolled':
							$success = $this->config->get('payment_pp_braintree_3ds_lookup_enrolled');
							break;
						case 'lookup_not_enrolled':
							$success = $this->config->get('payment_pp_braintree_3ds_lookup_not_enrolled');
							break;
						case 'authenticate_successful_issuer_not_participating':
							$success = $this->config->get('payment_pp_braintree_3ds_not_participating');
							break;
						case 'authentication_unavailable':
							$success = $this->config->get('payment_pp_braintree_3ds_unavailable');
							break;
						case 'authenticate_signature_verification_failed':
							$success = $this->config->get('payment_pp_braintree_3ds_signature_failed');
							break;
						case 'authenticate_successful':
							$success = $this->config->get('payment_pp_braintree_3ds_successful');
							break;
						case 'authenticate_attempt_successful':
							$success = $this->config->get('payment_pp_braintree_3ds_attempt_successful');
							break;
						case 'authenticate_failed':
							$success = $this->config->get('payment_pp_braintree_3ds_failed');
							break;
						case 'authenticate_unable_to_authenticate':
							$success = $this->config->get('payment_pp_braintree_3ds_unable_to_auth');
							break;
						case 'authenticate_error':
							$success = $this->config->get('payment_pp_braintree_3ds_error');
							break;
					}
				} else {
					$this->model_extension_payment_pp_braintree->log('Liability shift failed, nonce was not 3D Secured');

					$success = false;
				}
			}
		}
		$this->model_extension_payment_pp_braintree->log("Success:" . (int)$success);

		//Create transaction
		if ($success) {
			$transaction = $this->model_extension_payment_pp_braintree->addTransaction($this->gateway, $create_sale);

			$order_status_id = 0;
			switch ($transaction->transaction->status) {
				case 'authorization_expired':
					$order_status_id = $this->config->get('payment_pp_braintree_authorization_expired_id');
					break;
				case 'authorized':
					$order_status_id = $this->config->get('payment_pp_braintree_authorized_id');
					break;
				case 'authorizing':
					$order_status_id = $this->config->get('payment_pp_braintree_authorizing_id');
					break;
				case 'settlement_pending':
					$order_status_id = $this->config->get('payment_pp_braintree_settlement_pending_id');
					break;
				case 'failed':
					$order_status_id = $this->config->get('payment_pp_braintree_failed_id');
					break;
				case 'gateway_rejected':
					$order_status_id = $this->config->get('payment_pp_braintree_gateway_rejected_id');
					break;
				case 'processor_declined':
					$order_status_id = $this->config->get('payment_pp_braintree_processor_declined_id');
					break;
				case 'settled':
					$order_status_id = $this->config->get('payment_pp_braintree_settled_id');
					break;
				case 'settling':
					$order_status_id = $this->config->get('payment_pp_braintree_settling_id');
					break;
				case 'submitted_for_settlement':
					$order_status_id = $this->config->get('payment_pp_braintree_submitted_for_settlement_id');
					break;
				case 'voided':
					$order_status_id = $this->config->get('payment_pp_braintree_voided_id');
					break;
			}

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id);

			if ($transaction->success) {
				$this->model_extension_payment_pp_braintree->log('Transaction success, details below');
				$this->model_extension_payment_pp_braintree->log($transaction);

				$this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language')));
			} else {
				$this->model_extension_payment_pp_braintree->log('Transaction failed, details below');
				$this->model_extension_payment_pp_braintree->log($transaction);

				$this->session->data['error'] = $this->language->get('error_process_order');
				$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
			}
		}

		//If this is reached, transaction has failed
		$this->model_extension_payment_pp_braintree->log('Transaction reached end of method without being handled, failure');

		if (isset($this->session->data['order_id'])) {
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_pp_braintree_failed_id'));
		}

		$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
	}

	public function nonce() {
		$this->initialise();

		$this->load->language('extension/payment/pp_braintree');

		$this->load->model('extension/payment/pp_braintree');

		$this->model_extension_payment_pp_braintree->log('Starting vaulted');
		$this->model_extension_payment_pp_braintree->log($this->request->post);

		$json = array();

		$json['payment_method'] = '';

		$success = true;

		if (!isset($this->request->post['vaulted_payment_token'])) {
			$success = false;
		}

		if ($success) {
			$payment_method = $this->model_extension_payment_pp_braintree->createPaymentMethodNonce($this->gateway, $this->request->post['vaulted_payment_token']);

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

		$this->load->language('extension/payment/pp_braintree');

		$this->load->model('extension/payment/pp_braintree');

		$json = array();

		$json['success'] = false;

		if (isset($this->request->post['vaulted_payment_method'])) {
			$vaulted_payment_method = $this->request->post['vaulted_payment_method'];
		} else {
			$vaulted_payment_method = '';
		}

		$delete_payment_method = $this->model_extension_payment_pp_braintree->deletePaymentMethod($this->gateway, $vaulted_payment_method);

		if ($delete_payment_method) {
			$json['success'] = $this->language->get('text_method_removed');
		} else {
			$json['error'] = $this->language->get('text_method_not_removed');
		}

		$vaulted_customer_info = $this->model_extension_payment_pp_braintree->getCustomer($this->gateway, $this->customer_id_prefix . $this->customer->getId());

		$vaulted_card_count = 0;
		$vaulted_paypal_count = 0;

		if ($vaulted_customer_info->creditCards && $this->config->get('payment_pp_braintree_card_vault') == 1) {
			$vaulted_card_count = count($vaulted_customer_info->creditCards);
		}

		if ($vaulted_customer_info->paypalAccounts && $this->config->get('payment_pp_braintree_paypal_vault') == 1) {
			$vaulted_paypal_count = count($vaulted_customer_info->paypalAccounts);
		}

		$json['vaulted_payment_count'] = $vaulted_card_count + $vaulted_paypal_count;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function expressSetup() {
		// check checkout can continue due to stock checks or vouchers
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json = array();
			$json['error'] = true;
			$json['url'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}

		// if user not logged in check that the guest checkout is allowed
		if (!$this->customer->isLogged() && (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload() || $this->cart->hasRecurringProducts())) {
			$json = array();
			$json['error'] = true;
			$json['url'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		} else {
			unset($this->session->data['guest']);
		}

		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);

		if (!$this->customer->isLogged()) {
			$this->session->data['paypal_braintree']['guest'] = true;

			$this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
			$this->session->data['guest']['firstname'] = $this->request->post['details']['firstName'];
			$this->session->data['guest']['lastname'] = $this->request->post['details']['lastName'];
			$this->session->data['guest']['email'] = $this->request->post['details']['email'];

			if (isset($this->request->post['details']['phone'])) {
				$this->session->data['guest']['telephone'] = $this->request->post['details']['phone'];
			} else {
				$this->session->data['guest']['telephone'] = '';
			}

			$this->session->data['guest']['payment']['company'] = '';

			$this->session->data['guest']['payment']['firstname'] = $this->request->post['details']['firstName'];
			$this->session->data['guest']['payment']['lastname'] = $this->request->post['details']['lastName'];

			$this->session->data['guest']['payment']['company_id'] = '';
			$this->session->data['guest']['payment']['tax_id'] = '';

			if ($this->cart->hasShipping()) {
				$shipping_name = explode(' ', $this->request->post['details']['shippingAddress']['recipientName']);
				$shipping_first_name = $shipping_name[0];
				unset($shipping_name[0]);
				$shipping_last_name = implode(' ', $shipping_name);

				$this->session->data['guest']['payment']['address_1'] = $this->request->post['details']['shippingAddress']['line1'];
				if (isset($this->request->post['details']['shippingAddress']['line2'])) {
					$this->session->data['guest']['payment']['address_2'] = $this->request->post['details']['shippingAddress']['line2'];
				} else {
					$this->session->data['guest']['payment']['address_2'] = '';
				}

				$this->session->data['guest']['payment']['postcode'] = $this->request->post['details']['shippingAddress']['postalCode'];
				$this->session->data['guest']['payment']['city'] = $this->request->post['details']['shippingAddress']['city'];

				$this->session->data['guest']['shipping']['firstname'] = $shipping_first_name;
				$this->session->data['guest']['shipping']['lastname'] = $shipping_last_name;
				$this->session->data['guest']['shipping']['company'] = '';
				$this->session->data['guest']['shipping']['address_1'] = $this->request->post['details']['shippingAddress']['line1'];

				if (isset($this->request->post['details']['shippingAddress']['line2'])) {
					$this->session->data['guest']['shipping']['address_2'] =$this->request->post['details']['shippingAddress']['line2'];
				} else {
					$this->session->data['guest']['shipping']['address_2'] = '';
				}

				$this->session->data['guest']['shipping']['postcode'] = $this->request->post['details']['shippingAddress']['postalCode'];
				$this->session->data['guest']['shipping']['city'] = $this->request->post['details']['shippingAddress']['city'];

				$this->session->data['shipping_postcode'] = $this->request->post['details']['shippingAddress']['postalCode'];

				$country_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($this->request->post['details']['shippingAddress']['countryCode']) . "' AND `status` = '1' LIMIT 1")->row;

				if ($country_info) {
					$this->session->data['guest']['shipping']['country_id'] = $country_info['country_id'];
					$this->session->data['guest']['shipping']['country'] = $country_info['name'];
					$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
					$this->session->data['guest']['payment']['country_id'] = $country_info['country_id'];
					$this->session->data['guest']['payment']['country'] = $country_info['name'];
					$this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
					$this->session->data['shipping_country_id'] = $country_info['country_id'];

					if (isset($this->request->post['details']['shippingAddress']['state'])) {
						$returned_shipping_zone = $this->request->post['details']['shippingAddress']['state'];
					} else {
						$returned_shipping_zone = '';
					}

					$zone_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE (`name` = '" . $this->db->escape($returned_shipping_zone) . "' OR `code` = '" . $this->db->escape($returned_shipping_zone) . "') AND `status` = '1' AND `country_id` = '" . (int)$country_info['country_id'] . "' LIMIT 1")->row;
				} else {
					$this->session->data['guest']['shipping']['country_id'] = '';
					$this->session->data['guest']['shipping']['country'] = '';
					$this->session->data['guest']['shipping']['iso_code_2'] = '';
					$this->session->data['guest']['shipping']['iso_code_3'] = '';
					$this->session->data['guest']['shipping']['address_format'] = '';
					$this->session->data['guest']['payment']['country_id'] = '';
					$this->session->data['guest']['payment']['country'] = '';
					$this->session->data['guest']['payment']['iso_code_2'] = '';
					$this->session->data['guest']['payment']['iso_code_3'] = '';
					$this->session->data['guest']['payment']['address_format'] = '';
					$this->session->data['shipping_country_id'] = '';

					$zone_info = array();
				}

				if ($zone_info) {
					$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
					$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
					$this->session->data['guest']['shipping']['zone_id'] = $zone_info['zone_id'];
					$this->session->data['guest']['payment']['zone'] = $zone_info['name'];
					$this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
					$this->session->data['guest']['payment']['zone_id'] = $zone_info['zone_id'];
					$this->session->data['shipping_zone_id'] = $zone_info['zone_id'];
				} else {
					$this->session->data['guest']['shipping']['zone'] = '';
					$this->session->data['guest']['shipping']['zone_code'] = '';
					$this->session->data['guest']['shipping']['zone_id'] = '';
					$this->session->data['guest']['payment']['zone'] = '';
					$this->session->data['guest']['payment']['zone_code'] = '';
					$this->session->data['guest']['payment']['zone_id'] = '';
					$this->session->data['shipping_zone_id'] = '';
				}

				$this->session->data['guest']['shipping_address'] = true;
			} else {
				$this->session->data['guest']['payment']['address_1'] = '';
				$this->session->data['guest']['payment']['address_2'] = '';
				$this->session->data['guest']['payment']['postcode'] = '';
				$this->session->data['guest']['payment']['city'] = '';
				$this->session->data['guest']['payment']['country_id'] = '';
				$this->session->data['guest']['payment']['country'] = '';
				$this->session->data['guest']['payment']['iso_code_2'] = '';
				$this->session->data['guest']['payment']['iso_code_3'] = '';
				$this->session->data['guest']['payment']['address_format'] = '';
				$this->session->data['guest']['payment']['zone'] = '';
				$this->session->data['guest']['payment']['zone_code'] = '';
				$this->session->data['guest']['payment']['zone_id'] = '';
				$this->session->data['guest']['shipping_address'] = false;
			}

			$this->session->data['account'] = 'guest';

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		} else {
			$this->session->data['paypal_braintree']['guest'] = false;

			unset($this->session->data['guest']);
			/**
			 * if the user is logged in, add the address to the account and set the ID.
			 */

			if ($this->cart->hasShipping()) {
				$this->load->model('account/address');

				$addresses = $this->model_account_address->getAddresses();

				/**
				 * Compare all of the user addresses and see if there is a match
				 */
				$match = false;
				foreach($addresses as $address) {
					if (trim(strtolower($address['address_1'])) == trim(strtolower($this->request->post['details']['shippingAddress']['line1'])) && trim(strtolower($address['postcode'])) == trim(strtolower($this->request->post['details']['shippingAddress']['postalCode']))) {
						$match = true;

						$this->session->data['payment_address_id'] = $address['address_id'];
						$this->session->data['payment_country_id'] = $address['country_id'];
						$this->session->data['payment_zone_id'] = $address['zone_id'];

						$this->session->data['shipping_address_id'] = $address['address_id'];
						$this->session->data['shipping_country_id'] = $address['country_id'];
						$this->session->data['shipping_zone_id'] = $address['zone_id'];
						$this->session->data['shipping_postcode'] = $address['postcode'];

						break;
					}
				}

				/**
				 * If there is no address match add the address and set the info.
				 */
				if ($match == false) {
					$shipping_name = explode(' ', trim($this->request->post['details']['shippingAddress']['recipientName']));
					$shipping_first_name = $shipping_name[0];
					unset($shipping_name[0]);
					$shipping_last_name = implode(' ', $shipping_name);

					$country_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($this->request->post['details']['shippingAddress']['countryCode']) . "' AND `status` = '1' LIMIT 1")->row;
					$zone_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE (`name` = '" . $this->db->escape($this->request->post['details']['shippingAddress']['state']) . "' OR `code` = '" . $this->db->escape($this->request->post['details']['shippingAddress']['state']) . "') AND `status` = '1' AND `country_id` = '" . (int)$country_info['country_id'] . "'")->row;

					$address_data = array(
						'firstname'  => $shipping_first_name,
						'lastname'   => $shipping_last_name,
						'company'    => '',
						'company_id' => '',
						'tax_id'     => '',
						'address_1'  => $this->request->post['details']['shippingAddress']['line1'],
						'address_2'  => (isset($this->request->post['details']['shippingAddress']['line2']) ? $this->request->post['details']['shippingAddress']['line2'] : ''),
						'postcode'   => $this->request->post['details']['shippingAddress']['postalCode'],
						'city'       => $this->request->post['details']['shippingAddress']['city'],
						'zone_id'    => (isset($zone_info['zone_id']) ? $zone_info['zone_id'] : 0),
						'country_id' => (isset($country_info['country_id']) ? $country_info['country_id'] : 0)
					);

					$address_id = $this->model_account_address->addAddress($this->customer->getId(), $address_data);

					$this->session->data['payment_address_id'] = $address_id;
					$this->session->data['payment_country_id'] = $address_data['country_id'];
					$this->session->data['payment_zone_id'] = $address_data['zone_id'];

					$this->session->data['shipping_address_id'] = $address_id;
					$this->session->data['shipping_country_id'] = $address_data['country_id'];
					$this->session->data['shipping_zone_id'] = $address_data['zone_id'];
					$this->session->data['shipping_postcode'] = $address_data['postcode'];
				}
			} else {
				$this->session->data['payment_address_id'] = '';
				$this->session->data['payment_country_id'] = '';
				$this->session->data['payment_zone_id'] = '';
			}
		}

		$this->session->data['paypal_braintree'] = $this->request->post;

		$json = array(
			'error' => false,
			'url' => ''
		);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function expressConfirm() {
		if (!isset($this->session->data['paypal_braintree']) || !isset($this->session->data['paypal_braintree']['nonce'])) {
			$this->response->redirect($this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('extension/payment/pp_braintree');
		$this->load->language('checkout/cart');

		$this->load->model('tool/image');
		$this->load->model('extension/payment/pp_braintree');

		// Coupon
		if (isset($this->request->post['coupon']) && $this->validateCoupon()) {
			$this->session->data['coupon'] = $this->request->post['coupon'];

			$this->session->data['success'] = $this->language->get('text_coupon');

			$this->response->redirect($this->url->link('extension/payment/pp_braintree/expressConfirm', 'language=' . $this->config->get('config_language')));
		}

		// Voucher
		if (isset($this->request->post['voucher']) && $this->validateVoucher()) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$this->session->data['success'] = $this->language->get('text_voucher');

			$this->response->redirect($this->url->link('extension/payment/pp_braintree/expressConfirm', 'language=' . $this->config->get('config_language')));
		}

		// Reward
		if (isset($this->request->post['reward']) && $this->validateReward()) {
			$this->session->data['reward'] = abs($this->request->post['reward']);

			$this->session->data['success'] = $this->language->get('text_reward');

			$this->response->redirect($this->url->link('extension/payment/pp_braintree/expressConfirm', 'language=' . $this->config->get('config_language')));
		}

		$this->document->setTitle($this->language->get('text_express_title'));

		$data['heading_title'] = $this->language->get('text_express_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_express_title'),
			'href' => $this->url->link('extension/payment/pp_braintree/expressConfirm', 'language=' . $this->config->get('config_language'))
		);

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');

		$data['button_shipping'] = $this->language->get('button_express_shipping');
		$data['button_confirm'] = $this->language->get('button_express_confirm');

		if (isset($this->request->post['next'])) {
			$data['next'] = $this->request->post['next'];
		} else {
			$data['next'] = '';
		}

		$data['action'] = $this->url->link('extension/payment/pp_braintree/expressConfirm', 'language=' . $this->config->get('config_language'));

		$this->load->model('tool/upload');

		$products = $this->cart->getProducts();

		if (empty($products)) {
			$this->response->redirect($this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));
		}

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
			}

			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
			} else {
				$image = '';
			}

			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

				$price = $this->currency->format($unit_price, $this->session->data['currency']);
				$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
			} else {
				$price = false;
				$total = false;
			}

			$data['products'][] = array(
				'cart_id'               => $product['cart_id'],
				'thumb'                 => $image,
				'name'                  => $product['name'],
				'model'                 => $product['model'],
				'option'                => $option_data,
				'quantity'              => $product['quantity'],
				'stock'                 => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
				'reward'                => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
				'price'                 => $price,
				'total'                 => $total,
				'href'                  => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product['product_id']),
				'remove'                => $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language') . '&remove=' . $product['cart_id']),
			);
		}

		$data['vouchers'] = array();

		if ($this->cart->hasShipping()) {
			$data['has_shipping'] = true;
			/**
			 * Shipping services
			 */
			if ($this->customer->isLogged()) {
				$this->load->model('account/address');
				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			}

			if (!empty($shipping_address)) {
				// Shipping Methods
				$quote_data = array();

				$this->load->model('setting/extension');

				$results = $this->model_setting_extension->getExtensions('shipping');

				if (!empty($results)) {
					foreach ($results as $result) {
						if ($this->config->get('shipping_' . $result['code'] . '_status')) {
							$this->load->model('extension/shipping/' . $result['code']);

							$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);

							if ($quote) {
								$quote_data[$result['code']] = array(
									'title'      => $quote['title'],
									'quote'      => $quote['quote'],
									'sort_order' => $quote['sort_order'],
									'error'      => $quote['error']
								);
							}
						}
					}

					if (!empty($quote_data)) {
						$sort_order = array();

						foreach ($quote_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}

						array_multisort($sort_order, SORT_ASC, $quote_data);

						$this->session->data['shipping_methods'] = $quote_data;
						$data['shipping_methods'] = $quote_data;

						if (!isset($this->session->data['shipping_method'])) {
							//default the shipping to the very first option.
							$key1 = key($quote_data);
							$key2 = key($quote_data[$key1]['quote']);
							$this->session->data['shipping_method'] = $quote_data[$key1]['quote'][$key2];
						}

						$data['code'] = $this->session->data['shipping_method']['code'];
						$data['action_shipping'] = $this->url->link('extension/payment/pp_braintree/shipping', 'language=' . $this->config->get('config_language'));
					} else {
						unset($this->session->data['shipping_methods']);
						unset($this->session->data['shipping_method']);
						$data['error_no_shipping'] = $this->language->get('error_no_shipping');
					}
				} else {
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['shipping_method']);
					$data['error_no_shipping'] = $this->language->get('error_no_shipping');
				}
			}
		} else {
			$data['has_shipping'] = false;
		}

		// Totals
		$this->load->model('setting/extension');

		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);

		// Display prices
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);
		}

		$data['totals'] = array();

		foreach ($totals as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency']),
			);
		}

		/**
		 * Payment methods
		 */
		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$this->load->model('account/address');
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}

		$method_data = array();

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensions('payment');

		$this->model_extension_payment_pp_braintree->log("Payment methods returned based on new data");
		$this->model_extension_payment_pp_braintree->log($results);

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$this->load->model('extension/payment/' . $result['code']);

				$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($payment_address, $total);

				if ($method) {
					$method_data[$result['code']] = $method;
				}
			}
		}

		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		$this->model_extension_payment_pp_braintree->log("Payment methods again - sorted");
		$this->model_extension_payment_pp_braintree->log($method_data);

		if (!isset($method_data['pp_braintree'])) {
			$this->model_extension_payment_pp_braintree->log("Braintree module was no longer an option. Check configured zones or minimum order amount based on user address info");
			$this->session->data['error_warning'] = $this->language->get('error_unavailable');
			$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
		}

		$this->session->data['payment_methods'] = $method_data;
		$this->session->data['payment_method'] = $method_data['pp_braintree'];

		$data['action_confirm'] = $this->url->link('extension/payment/pp_braintree/expressComplete', 'language=' . $this->config->get('config_language'));

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['attention'])) {
			$data['attention'] = $this->session->data['attention'];
			unset($this->session->data['attention']);
		} else {
			$data['attention'] = '';
		}

		$data['coupon'] = $this->load->controller('extension/total/coupon');
		$data['voucher'] = $this->load->controller('extension/total/voucher');
		$data['reward'] = $this->load->controller('extension/total/reward');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/payment/pp_braintree_confirm', $data));
	}

	public function expressComplete() {
		if (!isset($this->session->data['paypal_braintree']) || !isset($this->session->data['paypal_braintree']['nonce'])) {
			$this->response->redirect($this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('extension/payment/pp_braintree');
		$redirect = '';

		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			$this->load->model('account/address');

			if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			}

			if (empty($shipping_address)) {
				$redirect = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$redirect = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
			}
		} else {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate if payment address has been set.
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}

		// Validate if payment method has been set.
		if (!isset($this->session->data['payment_method'])) {
			$redirect = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));
		}

		// Validate minimum quantity requirements.
		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$redirect = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));

				break;
			}
		}

		if ($redirect == '') {
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$this->load->language('checkout/checkout');

			$data = array();

			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');

			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');
			} else {
				$data['store_url'] = HTTP_SERVER;
			}

			if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->config->get('config_customer_group_id');
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();

				$this->load->model('account/address');

				$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$data['firstname'] = $this->session->data['guest']['firstname'];
				$data['lastname'] = $this->session->data['guest']['lastname'];
				$data['email'] = $this->session->data['guest']['email'];
				$data['telephone'] = $this->session->data['guest']['telephone'];

				$payment_address = $this->session->data['guest']['payment'];
			}

			$data['payment_firstname'] = isset($payment_address['firstname']) ? $payment_address['firstname'] : '';
			$data['payment_lastname'] = isset($payment_address['lastname']) ? $payment_address['lastname'] : '';
			$data['payment_company'] = isset($payment_address['company']) ? $payment_address['company'] : '';
			$data['payment_company_id'] = isset($payment_address['company_id']) ? $payment_address['company_id'] : '';
			$data['payment_tax_id'] = isset($payment_address['tax_id']) ? $payment_address['tax_id'] : '';
			$data['payment_address_1'] = isset($payment_address['address_1']) ? $payment_address['address_1'] : '';
			$data['payment_address_2'] = isset($payment_address['address_2']) ? $payment_address['address_2'] : '';
			$data['payment_city'] = isset($payment_address['city']) ? $payment_address['city'] : '';
			$data['payment_postcode'] = isset($payment_address['postcode']) ? $payment_address['postcode'] : '';
			$data['payment_zone'] = isset($payment_address['zone']) ? $payment_address['zone'] : '';
			$data['payment_zone_id'] = isset($payment_address['zone_id']) ? $payment_address['zone_id'] : '';
			$data['payment_country'] = isset($payment_address['country']) ? $payment_address['country'] : '';
			$data['payment_country_id'] = isset($payment_address['country_id']) ? $payment_address['country_id'] : '';
			$data['payment_address_format'] = isset($payment_address['address_format']) ? $payment_address['address_format'] : '';

			$data['payment_method'] = '';
			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			}

			$data['payment_code'] = '';
			if (isset($this->session->data['payment_method']['code'])) {
				$data['payment_code'] = $this->session->data['payment_method']['code'];
			}

			if ($this->cart->hasShipping()) {
				if ($this->customer->isLogged()) {
					$this->load->model('account/address');

					$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
				} elseif (isset($this->session->data['guest'])) {
					$shipping_address = $this->session->data['guest']['shipping'];
				}

				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];
				$data['shipping_company'] = $shipping_address['company'];
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];

				$data['shipping_method'] = '';
				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				}

				$data['shipping_code'] = '';
				if (isset($this->session->data['shipping_method']['code'])) {
					$data['shipping_code'] = $this->session->data['shipping_method']['code'];
				}
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';
				$data['shipping_company'] = '';
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}

			$product_data = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					);
				}

				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'option'     => $option_data,
					'download'   => $product['download'],
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $product['price'],
					'total'      => $product['total'],
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'     => $product['reward']
				);
			}

			// Gift Voucher
			$voucher_data = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$voucher_data[] = array(
						'description'      => $voucher['description'],
						'code'             => token(10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					);
				}
			}

			$data['products'] = $product_data;
			$data['vouchers'] = $voucher_data;
			$data['totals'] = $totals;
			$data['total'] = $total;
			$data['comment'] = '';

			if (isset($this->request->cookie['tracking'])) {
				$data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('account/affiliate');

				$affiliate_info = $this->model_account_affiliate->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id'];
					$data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$data['affiliate_id'] = 0;
					$data['commission'] = 0;
				}

				// Marketing
				$this->load->model('marketing/marketing');

				$marketing_info = $this->model_marketing_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$data['marketing_id'] = 0;
				}
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
				$data['marketing_id'] = 0;
				$data['tracking'] = '';
			}

			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$data['currency_code'] = $this->session->data['currency'];
			$data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$data['accept_language'] = '';
			}

			$this->load->model('account/custom_field');
			$this->load->model('checkout/order');

			$order_id = $this->model_checkout_order->addOrder($data);
			$this->session->data['order_id'] = $order_id;

			$this->load->model('extension/payment/pp_braintree');

			$this->initialise();

			$create_sale = [
				"amount" => $this->currency->format($data['total'], $data['currency_code'], $data['currency_value'], false),
				"paymentMethodNonce" => $this->session->data['paypal_braintree']['nonce'],
				"orderId" => $order_id,
				'channel' => 'OpenCart_Cart_vzero',
			];

			$transaction = $this->model_extension_payment_pp_braintree->addTransaction($this->gateway, $create_sale);

			//handle order status

			$order_status_id = 0;
			switch ($transaction->transaction->status) {
				case 'authorization_expired':
					$order_status_id = $this->config->get('payment_pp_braintree_authorization_expired_id');
					break;
				case 'authorized':
					$order_status_id = $this->config->get('payment_pp_braintree_authorized_id');
					break;
				case 'authorizing':
					$order_status_id = $this->config->get('payment_pp_braintree_authorizing_id');
					break;
				case 'settlement_pending':
					$order_status_id = $this->config->get('payment_pp_braintree_settlement_pending_id');
					break;
				case 'failed':
					$order_status_id = $this->config->get('payment_pp_braintree_failed_id');
					break;
				case 'gateway_rejected':
					$order_status_id = $this->config->get('payment_pp_braintree_gateway_rejected_id');
					break;
				case 'processor_declined':
					$order_status_id = $this->config->get('payment_pp_braintree_processor_declined_id');
					break;
				case 'settled':
					$order_status_id = $this->config->get('payment_pp_braintree_settled_id');
					break;
				case 'settling':
					$order_status_id = $this->config->get('payment_pp_braintree_settling_id');
					break;
				case 'submitted_for_settlement':
					$order_status_id = $this->config->get('payment_pp_braintree_submitted_for_settlement_id');
					break;
				case 'voided':
					$order_status_id = $this->config->get('payment_pp_braintree_voided_id');
					break;
			}

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id);

			if ($transaction->success) {
				$this->model_extension_payment_pp_braintree->log('Transaction success, details below');
				$this->model_extension_payment_pp_braintree->log($transaction);

				$this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language')));
			} else {
				$this->model_extension_payment_pp_braintree->log('Transaction failed, details below');
				$this->model_extension_payment_pp_braintree->log($transaction);

				$this->session->data['error'] = $this->language->get('error_process_order');
				$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
			}
		} else {
			$this->response->redirect($redirect);
		}
	}

	private function initialise() {
		$this->load->model('extension/payment/pp_braintree');

		if ($this->config->get('payment_pp_braintree_access_token') != '') {
			$this->gateway = $this->model_extension_payment_pp_braintree->setGateway($this->config->get('payment_pp_braintree_access_token'));
		} else {
			$this->model_extension_payment_pp_braintree->setCredentials();
		}
	}

	public function shipping() {
		$this->shippingValidate($this->request->post['shipping_method']);

		$this->response->redirect($this->url->link('extension/payment/pp_braintree/expressConfirm', 'language=' . $this->config->get('config_language')));
	}

	protected function shippingValidate($code) {
		$this->load->language('checkout/cart');
		$this->load->language('extension/payment/pp_braintree');

		if (empty($code)) {
			$this->session->data['error_warning'] = $this->language->get('error_shipping');
			return false;
		} else {
			$shipping = explode('.', $code);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$this->session->data['error_warning'] = $this->language->get('error_shipping');
				return false;
			} else {
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				$this->session->data['success'] = $this->language->get('text_shipping_updated');
				return true;
			}
		}
	}

	protected function validateCoupon() {
		$this->load->model('extension/total/coupon');

		$coupon_info = $this->model_extension_total_coupon->getCoupon($this->request->post['coupon']);

		if ($coupon_info) {
			return true;
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_coupon');
			return false;
		}
	}

	protected function validateVoucher() {
		$this->load->model('extension/total/coupon');

		$voucher_info = $this->model_extension_total_voucher->getVoucher($this->request->post['voucher']);

		if ($voucher_info) {
			return true;
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_voucher');
			return false;
		}
	}

	protected function validateReward() {
		$points = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		$error = '';

		if (empty($this->request->post['reward'])) {
			$error = $this->language->get('error_reward');
		}

		if ($this->request->post['reward'] > $points) {
			$error = sprintf($this->language->get('error_points'), $this->request->post['reward']);
		}

		if ($this->request->post['reward'] > $points_total) {
			$error = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if (!$error) {
			return true;
		} else {
			$this->session->data['error_warning'] = $error;
			return false;
		}
	}
}
