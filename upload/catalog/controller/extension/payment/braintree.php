<?php
class ControllerExtensionPaymentBraintree extends Controller {
	private $customer_id_prefix = 'braintree_oc_';

	public function index() {
		$this->initialise();

		$this->load->language('extension/payment/braintree');

		$data['text_choose_card'] = $this->language->get('text_choose_card');
		$data['text_remember_card'] = $this->language->get('text_remember_card');

		$data['entry_card_choice'] = $this->language->get('entry_card_choice');
		$data['entry_existing'] = $this->language->get('entry_existing');
		$data['entry_new'] = $this->language->get('entry_new');
		$data['entry_card'] = $this->language->get('entry_card');
		$data['entry_expires'] = $this->language->get('entry_expires');
		$data['entry_cvv'] = $this->language->get('entry_cvv');
		$data['entry_remember_card'] = $this->language->get('entry_remember_card');
		$data['entry_card_placeholder'] = $this->language->get('entry_card_placeholder');
		$data['entry_month_placeholder'] = $this->language->get('entry_month_placeholder');
		$data['entry_year_placeholder'] = $this->language->get('entry_year_placeholder');
		$data['entry_cvv_placeholder'] = $this->language->get('entry_cvv_placeholder');

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['button_delete_card'] = $this->language->get('button_delete_card');

		$data['payment_url'] = $this->url->link('payment/braintree/payment', '', true);
		$data['vaulted_url'] = $this->url->link('payment/braintree/vaulted', '', true);
		$data['environment'] = $this->config->get('braintree_environment');
		$data['integration_type'] = $this->config->get('braintree_integration_type');
		$data['three_d_secure_status'] = $this->config->get('braintree_3ds_status');
		$data['vault'] = $this->config->get('braintree_vault');
		$data['braintree_js'] = 'https://js.braintreegateway.com/v2/braintree.js';

		if (!$this->session->data['order_id']) {
			return false;
		}

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$merchant_id = $this->config->get('braintree_merchant_id');

		$create_token = array();

		$merchant_accounts = $this->config->get('braintree_account');

		foreach ($merchant_accounts as $merchant_account_currency => $merchant_account) {
			if (($merchant_account_currency == $order_info['currency_code']) && !empty($merchant_account['merchant_account_id'])) {
				$create_token['merchantAccountId'] = $merchant_account['merchant_account_id'];

				$merchant_id = $merchant_account['merchant_account_id'];

				break;
			}
		}

		$data['merchant_id'] = $merchant_id;

		//If user is logged and integration is hosted, provide ability for vaulted cards etc
		if ($this->customer->isLogged() && $this->config->get('braintree_integration_type') == 'hosted' && $this->config->get('braintree_vault')) {
			$vaulted_customer_info = $this->model_extension_payment_braintree->getCustomer($this->customer_id_prefix . $this->customer->getId(), false);
		} else {
			$vaulted_customer_info = false;
		}

		$client_token = $this->model_extension_payment_braintree->generateToken($create_token);

		$data['client_token'] = $client_token;
		$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['currency_code'] = $order_info['currency_code'];

		//Checkout with PayPal
		$checkout_with_paypal = array(
			'status'   => true,
			'amount'   => false,
			'currency' => false,
			'shipping' => array()
		);

		if ($checkout_with_paypal['status']) {
			//PayPal amount
			$checkout_with_paypal['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

			//PayPal currency
			$valid_paypal_currencies = array(
				'USD',
				'EUR',
				'GBP',
				'CAD',
				'AUD',
				'DKK',
				'NOK',
				'PLN',
				'SEK',
				'CHF',
				'TRY'
			);

			if (in_array($order_info['currency_code'], $valid_paypal_currencies)) {
				$checkout_with_paypal['currency'] = $order_info['currency_code'];
			} else {
				$checkout_with_paypal['status'] = false;
			}
		}

		//PayPal shipping
		if ($checkout_with_paypal['status'] && $this->cart->hasShipping()) {
			$checkout_with_paypal['shipping'] = array(
				'recipientName'		=> $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'],
				'type'				=> 'Personal',
				'streetAddress'		=> $order_info['shipping_address_1'],
				'extendedAddress'	=> $order_info['shipping_address_2'],
				'locality'			=> $order_info['shipping_city'],
				'countryCodeAlpha2' => $order_info['shipping_iso_code_2'],
				'postalCode'		=> $order_info['shipping_postcode'],
				'region'			=> $order_info['shipping_zone_code'],
				'phone'				=> $order_info['telephone'],
				'editable'			=> false
			);
		}

		$data['checkout_with_paypal'] = $checkout_with_paypal;

		//Vaulted cards
		$this->load->model('extension/payment/braintree');

		$vaulted_payment_methods = array();

		$initial_card_image = '';

		if ($vaulted_customer_info && $vaulted_customer_info->creditCards) {
			foreach ($vaulted_customer_info->creditCards as $credit_card) {
				$vaulted_payment_methods[] = array(
					'image'	  => $credit_card->imageUrl,
					'name'	  => sprintf($this->language->get('text_vaulted_payment_method_name'), $credit_card->cardType, $credit_card->last4, $credit_card->expirationDate),
					'token'	  => $credit_card->token,
					'expired' => $credit_card->expired
				);

				if (!$initial_card_image) {
					$initial_card_image = $credit_card->imageUrl;
				}
			}
		}

		$data['vaulted_payment_methods'] = $vaulted_payment_methods;

		$data['initial_card_image'] = $initial_card_image;
		
		return $this->load->view('extension/payment/braintree', $data);
	}

	public function payment() {
		set_time_limit(120);

		$this->initialise();

		$this->load->language('extension/payment/braintree');

		$this->load->model('checkout/order');
		$this->load->model('extension/payment/braintree');

		$success = true;

		if (!$this->session->data['order_id']) {
			$this->model_extension_payment_braintree->log('Session data: order_id not found');

			$success = false;
		}

		if (isset($this->request->post['payment_method_nonce'])) {
			$payment_method_nonce = $this->request->post['payment_method_nonce'];
		} else {
			$this->model_extension_payment_braintree->log('Post data: payment_method_nonce not found');

			$success = false;
		}

		if (isset($this->request->post['vault_card']) && $this->request->post['vault_card'] == '1' && $this->config->get('braintree_integration_type') == 'hosted' && $this->config->get('braintree_vault')) {
			$vault_card = true;
		} else {
			$vault_card = false;
		}

		if (isset($this->request->post['device_data'])) {
			$device_data = $this->request->post['device_data'];
		} else {
			$device_data = '';
		}

		//Start creating transaction array
		if ($success) {
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			$create_sale = array(
				'amount'			 => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false),
				'channel'			 => 'OpenCart_Cart_vzero',
				'orderId'			 => $order_info['order_id'],
				'paymentMethodNonce' => $payment_method_nonce,
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
				'options' => array()
			);

			$merchant_accounts = $this->config->get('braintree_account');

			foreach ($merchant_accounts as $merchant_account_currency => $merchant_account) {
				if (($merchant_account_currency == $order_info['currency_code']) && !empty($merchant_account['merchant_account_id'])) {
					$create_sale['merchantAccountId'] = $merchant_account['merchant_account_id'];
				}
			}

			if ($this->config->get('braintree_settlement_type') == 'immediate') {
				$create_sale['options']['submitForSettlement'] = true;
			} else {
				$create_sale['options']['submitForSettlement'] = false;
			}

			$create_sale['options']['three_d_secure'] = array(
				'required' => false
			);

			if ($device_data) {
				$create_sale['deviceData'] = $device_data;
			}
		}

		if ($this->config->get('braintree_3ds_status')) {
			$nonce = $this->model_extension_payment_braintree->getPaymentMethodNonce($payment_method_nonce);

			$this->model_extension_payment_braintree->log($nonce);

			if ($nonce->type == 'CreditCard' && $nonce->details['cardType'] != 'American Express') {
				$create_sale['options']['three_d_secure'] = array(
					'required' => true
				);

				if (!empty($nonce->threeDSecureInfo)) {
					if ($this->config->get('braintree_3ds_full_liability_shift')) {
						if ($nonce->threeDSecureInfo->liabilityShifted == false) {
							$success = false;
						}
					} else {
						switch ($nonce->threeDSecureInfo->status) {
							case 'authenticate_signature_verification_failed':
								$success = false;
								break;
							case 'authenticate_failed':
								$success = false;
								break;
						}
					}

					if ($nonce->threeDSecureInfo->liabilityShiftPossible == true && $nonce->threeDSecureInfo->liabilityShifted == false) {
						$this->model_extension_payment_braintree->log('Liability shift failed, details below');
						$this->model_extension_payment_braintree->log($nonce->threeDSecureInfo);

						$success = false;
					}
				} else {
					$this->model_extension_payment_braintree->log('Liability shift failed, nonce was not 3D Secured');

					$success = false;
				}
			}
		}

		if ($success && $this->customer->isLogged()) {
			$customer_id = $this->customer_id_prefix . $this->customer->getId();

			$vaulted_customer_info = $this->model_extension_payment_braintree->getCustomer($customer_id, false);

			if ($vaulted_customer_info) {
				$create_sale['customerId'] = $customer_id;
			} else {
				$create_sale['customer']['id'] = $customer_id;
			}

			if ($vault_card) {
				$create_sale['options']['storeInVaultOnSuccess'] = true;
			}
		}

		//Add shipping details
		if ($success && $this->cart->hasShipping()) {
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

		//Create transaction
		if ($success) {
			$transaction = $this->model_extension_payment_braintree->addTransaction($create_sale);

			$order_status_id = 0;
			switch ($transaction->success && $transaction->transaction->status) {
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
		$this->response->redirect($this->url->link('checkout/failure', '', true));
	}

	public function vaulted() {
		$this->initialise();

		$this->load->language('extension/payment/braintree');

		$this->load->model('extension/payment/braintree');

		$json = array();

		$json['payment_method'] = '';

		$success = true;

		if (isset($this->request->post['vaulted_payment_method'])) {
			$vaulted_payment_method = $this->request->post['vaulted_payment_method'];
		} else {
			$vaulted_payment_method = '';

			$success = false;
		}

		if ($success) {
			$payment_method = $this->model_extension_payment_braintree->addPaymentMethodNonce($vaulted_payment_method);

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

	public function deletePaymentMethod() {
		$this->initialise();

		$this->load->model('extension/payment/braintree');

		$json = array();

		$json['success'] = false;

		if (isset($this->request->post['vaulted_payment_method'])) {
			$vaulted_payment_method = $this->request->post['vaulted_payment_method'];
		} else {
			$vaulted_payment_method = '';
		}

		$delete_payment_method = $this->model_extension_payment_braintree->deletePaymentMethod($vaulted_payment_method);

		if ($delete_payment_method) {
			$json['success'] = 'Successfully deleted';
		} else {
			$json['error'] = 'Cannot delete card';
		}

		$customer_info = $this->model_extension_payment_braintree->getCustomer($this->customer_id_prefix . $this->customer->getId());

		if ($customer_info && $customer_info->creditCards && !empty($customer_info->creditCards)) {
			$json['vaulted_payment_methods'] = true;
		} else {
			$json['vaulted_payment_methods'] = false;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function initialise() {
		$this->load->model('extension/payment/braintree');

		$this->model_extension_payment_braintree->setCredentials();
	}
}
