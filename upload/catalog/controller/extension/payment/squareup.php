<?php

class ControllerExtensionPaymentSquareup extends Controller {

	public function index() {
		$this->load->language('extension/payment/squareup');

		$data['action'] = $this->url->link('extension/payment/squareup/checkout', '', true);
        
		if ($this->config->get('payment_squareup_enable_sandbox')) {
			$data['sandbox_message'] = $this->language->get('warning_test_mode');
		} else {
			$data['sandbox_message'] = '';
		}

		if ($this->config->get('payment_squareup_quick_pay')) {
			return $this->load->view('extension/payment/squareup', $data);
		}

		if ($this->config->get('payment_squareup_enable_sandbox')) {
			$data['application_id'] = $this->config->get('payment_squareup_sandbox_client_id');
			$data['location_id'] = $this->config->get('payment_squareup_sandbox_location_id');
		} else {
			$data['application_id'] = $this->config->get('payment_squareup_client_id');
			$data['location_id'] = $this->config->get('payment_squareup_location_id');
		}
		$data['squareup_process_payment'] = $this->url->link('extension/payment/squareup/processPayment', '', true);
		if ($this->config->get('payment_squareup_delay_capture')) {
			$data['text_payment'] = $this->language->get('text_authorize');
		} else {
			$data['text_payment'] = $this->language->get('text_capture');
		}

		$theme = $this->config->get('theme_' . $this->config->get('config_theme') . '_directory');
		if (!empty($theme) && file_exists(DIR_TEMPLATE . $theme . '/stylesheet/squareup.css')) {
			$this->document->addStyle('catalog/view/theme/' . $theme . '/stylesheet/squareup.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/squareup.css');
		}

		$this->load->library('squareup');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/squareup');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		list($amount, $currency) = $this->model_extension_payment_squareup->getAmountAndCurrency($this->firstPayment($order_info));

		$data['given_name'] = $order_info['payment_firstname'];
		$data['family_name'] = $order_info['payment_lastname'];
		$data['email'] = $order_info['email'];
		$data['phone'] = $this->squareup->phoneFormat($order_info['telephone'], $order_info['payment_iso_code_2']);
		$data['address_line_1'] = $order_info['payment_address_1'];
		$data['address_line_2'] = $order_info['payment_address_2'];
		$data['address_line_3'] = '';
		$data['city'] = $order_info['payment_city'];
		$data['state'] = $order_info['payment_zone'];
		$data['postal_code'] = $order_info['payment_postcode'];
		$data['country_code'] = $order_info['payment_iso_code_2'];
		$data['is_sandbox'] = $this->config->get('payment_squareup_enable_sandbox');
		$data['currency'] = $currency;
		$data['amount'] = $this->currency->format($amount, $currency, 1, false);

		if ($this->cart->hasRecurringProducts()) {
			if ($amount > 0) {
				$data['intent'] = 'CHARGE_AND_STORE';
			} else {
				$data['intent'] = 'STORE';
			}
		} else {
			$data['intent'] = 'CHARGE';
		}

		$this->session->data['squareup_amount'] = $data['amount'];
		$this->session->data['squareup_currency'] = $data['currency'];
		$this->session->data['squareup_intent'] = $data['intent'];

		$csp = $this->config->get('payment_squareup_content_security');
		$csp = str_replace("\r", "", str_replace("\n", "", $csp));

		$this->response->addHeader("Content-Security-Policy: " . $csp);

		return $this->load->view('extension/payment/squareup_iframe', $data);
	}


	// for QuickPay only
	public function checkout() {
		$this->load->language('extension/payment/squareup');

		$this->load->model('extension/payment/squareup');
		$this->load->model('checkout/order');
		$this->load->model('localisation/country');

		$this->load->library('squareup');

		if(!isset($this->session->data['order_id'])) {
			return false;
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		unset($this->session->data['squareup_payment_link']);
		unset($this->session->data['squareup_payment_ip']);
		unset($this->session->data['squareup_payment_user_agent']);

		$json = array();

		try {
			$billing_address = $this->model_extension_payment_squareup->getBillingAddress($order_info);
			list($amount,$currency) = $this->model_extension_payment_squareup->getAmountAndCurrency($order_info['total']);

			if ($this->config->get('payment_squareup_enable_sandbox')) {
				$access_token = $this->config->get('payment_squareup_sandbox_token');
			} else {
				$access_token = $this->config->get('payment_squareup_access_token');
			}

			$redirect_url = $this->url->link('extension/payment/squareup/success', '', true);
			$email = $order_info['email'];
			$phone = $this->squareup->phoneFormat($order_info['telephone'], $order_info['payment_iso_code_2']);
			$item_summary = $this->language->get('text_order_id').'='.$order_info['order_id'];

			// use new Square quick_pay
			$result = $this->squareup->createPaymentLink($access_token, $amount, $currency, $redirect_url, $billing_address, $email, $phone, $item_summary);

			$json['payment_link'] = $result['payment_link']['long_url'];

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$user_agent = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$user_agent = '';
			}

			if (isset($this->request->server['REMOTE_ADDR'])) {
				$ip = $this->request->server['REMOTE_ADDR'];
			} else {
				$ip = '';
			}

			$this->session->data['squareup_payment_link'] = $result;
			$this->session->data['squareup_payment_ip'] = $order_info['ip'];
			$this->session->data['squareup_payment_user_agent'] = $order_info['user_agent'];


		} catch (\Squareup\Exception $e) {
			if ($e->isCurlError()) {
				$json['error'] = $this->language->get('text_token_issue_customer_error');
			} else if ($e->isAccessTokenRevoked()) {
				// Send reminder e-mail to store admin to refresh the token
				$this->model_extension_payment_squareup->tokenRevokedEmail();

				$json['error'] = $this->language->get('text_token_issue_customer_error');
			} else if ($e->isAccessTokenExpired()) {
				// Send reminder e-mail to store admin to refresh the token
				$this->model_extension_payment_squareup->tokenExpiredEmail();

				$json['error'] = $this->language->get('text_token_issue_customer_error');
			} else {
				$json['error'] = $e->getMessage();
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	// for QuickPay only
    public function success() {
		$this->load->language('extension/payment/squareup');
		$this->load->library('squareup');
		if (empty($this->session->data['squareup_payment_link'])) {
			$this->session->data['error'] = $this->language->get('error_missing_payment_link');
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}

		$square_payment_link = $this->session->data['squareup_payment_link'];
		if ($this->config->get('payment_squareup_enable_sandbox')) {
			$access_token = $this->config->get('payment_squareup_sandbox_token');
		} else {
			$access_token = $this->config->get('payment_squareup_access_token');
		}
		if (!isset($square_payment_link['payment_link']['order_id'])) {
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}

		$order_id = $square_payment_link['payment_link']['order_id'];
		$order = $this->squareup->retrieveOrder($access_token,$order_id);
		if (!isset($order['order']['tenders'][0]['id'])) {
			$this->session->data['error'] = $this->language->get('error_missing_order_tender_id');
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}

		$payment_id = $order['order']['tenders'][0]['id'];
		$payment = $this->squareup->getPayment($access_token,$payment_id);
		if (!isset($payment['payment']['status'])) {
			$this->session->data['error'] = $this->language->get('error_missing_payment_status');
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}
		$status = $payment['payment']['status'];
		if ($status != 'COMPLETED' && $status != 'PENDING' && $status != 'APPROVED') {
			$this->session->data['error'] = str_replace('%1',$status,$this->language->get('error_payment_status'));
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}

		// update OpenCart order history
		$this->load->model('checkout/order');
		if ($status=='PENDING') {
			$order_status_id = $this->config->get('config_order_status_id');
		} elseif ($status=='APPROVED') {
			$order_status_id = $this->config->get('payment_squareup_status_authorized');
		} else {
			$order_status_id = $this->config->get('payment_squareup_status_captured');
		}
		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id);

		// add squareup payment details to database
		$user_agent = $this->session->data['squareup_payment_user_agent'];
		$ip = $this->session->data['squareup_payment_ip'];
		$this->load->model('extension/payment/squareup');
		$this->model_extension_payment_squareup->addPayment($payment, $this->config->get('payment_squareup_merchant_id'), $this->session->data['order_id'], $user_agent, $ip);

		$this->response->redirect($this->url->link('checkout/success', '', true));
	}


	public function processPayment() {
		$this->load->language('extension/payment/squareup');

		$this->load->model('extension/payment/squareup');
        $this->load->model('checkout/order');
		$this->load->model('localisation/country');

		$this->load->library('squareup');

		$json = array();

		if (empty($this->request->post['source_id'])) {
			$this->session->data['error'] = $this->language->get('error_missing_source_id');
//		} else if (empty($this->request->post['verification_token'])) {
//			$this->session->data['error'] = $this->language->get('error_missing_verification_token');
		} else if (empty($this->session->data['squareup_intent'])) {
			$this->session->data['error'] = $this->language->get('error_missing_intent');
		} else if (!isset($this->session->data['squareup_amount'])) {
			$this->session->data['error'] = $this->language->get('error_missing_amount');
		} else if (empty($this->session->data['squareup_currency'])) {
			$this->session->data['error'] = $this->language->get('error_missing_currency');
		} else if (empty($this->session->data['order_id'])) {
			$this->session->data['error'] = $this->language->get('error_missing_order_id');
		}

		if (!empty($this->session->data['error'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', '', true);
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		$source_id = $this->request->post['source_id'];
		$verification_token = '';
		if (isset($this->request->post['verification_token'])) {
			$verification_token = trim((string)$this->request->post['verification_token']);
		}
		$intent = $this->session->data['squareup_intent'];
		$amount = $this->session->data['squareup_amount'];
		$currency = $this->session->data['squareup_currency'];
		$order_id = $this->session->data['order_id'];

		unset($this->session->data['squareup_amount']);
		unset($this->session->data['squareup_currency']);
		unset($this->session->data['squareup_intent']);

		$order_info = $this->model_checkout_order->getOrder($order_id);

		$email = $order_info['email'];
		$phone = $this->squareup->phoneFormat($order_info['telephone'], $order_info['payment_iso_code_2']);
		$statement_description_identifier = $this->language->get('text_order_id').'='.$order_info['order_id'];
		$reference_id = (string)$order_info['order_id'];

		if ($this->config->get('payment_squareup_enable_sandbox')) {
			$access_token = $this->config->get('payment_squareup_sandbox_token');
		} else {
			$access_token = $this->config->get('payment_squareup_access_token');
		}

		$payment = null;

		if ($intent=='CHARGE' || $intent=='CHARGE_AND_STORE') {
			// call the createPayment API with given source_id to process the payment
			try {
				$billing_address = $this->model_extension_payment_squareup->getBillingAddress($order_info);
				$payment = $this->squareup->createPayment($access_token, $amount, $currency, $billing_address, $email, $phone, $source_id, $reference_id, $statement_description_identifier, '', $verification_token);
			} catch (\Squareup\Exception $e) {
				if ($e->isCurlError()) {
					$error = $this->language->get('text_token_issue_customer_error');
				} else if ($e->isAccessTokenRevoked()) {
					// Send reminder e-mail to store admin to refresh the token
					$this->model_extension_payment_squareup->tokenRevokedEmail();

					$error = $this->language->get('text_token_issue_customer_error');
				} else if ($e->isAccessTokenExpired()) {
					// Send reminder e-mail to store admin to refresh the token
					$this->model_extension_payment_squareup->tokenExpiredEmail();

					$error = $this->language->get('text_token_issue_customer_error');
				} else {
					$error = $e->getMessage();
				}
				$this->session->data['error'] = $error;
			}

			if (!empty($this->session->data['error'])) {
				$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));
				return;
			}

			if (!isset($payment['payment'])) {
				// this error should never happen, if it does, check the error log
				$this->session->data['error'] = $this->language->get('error_payment');
				$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));
				return;
			}

			$status = $payment['payment']['status'];

			if ($status != 'APPROVED' && $status != 'COMPLETED' && $status != 'PENDING') {
				$this->session->data['error'] = str_replace('%s', $status,$this->language->get('error_payment_status'));
				$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));
				return;
			}

			// add squareup payment details to database
			$user_agent = $order_info['user_agent'];
			$ip = $order_info['ip'];
			$this->model_extension_payment_squareup->addPayment($payment, $this->config->get('payment_squareup_merchant_id'), $this->session->data['order_id'], $user_agent, $ip);

			// get updated source_id
			$source_id = $payment['payment']['id'];
		}

		// update OpenCart order history
		if (isset($status) && $status=='PENDING') {
			$order_status_id = $this->config->get('config_order_status_id');
		} elseif ($this->config->get('payment_squareup_delay_capture')) {
			$order_status_id = $this->config->get('payment_squareup_status_authorized');
		} else {
			$order_status_id = $this->config->get('payment_squareup_status_captured');
		}
		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id);

		if ($intent=='STORE' || $intent=='CHARGE_AND_STORE') {
			// initialise possible future recurring payments
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$error = $this->initRecurringPayments($access_token, $source_id, $verification_token, $order_info, $payment);
			if ($error) {
				$this->session->data['error'] = $error;
				$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput(json_encode($json));
				return;
			}
		}

		$json['redirect'] = $this->url->link('checkout/success', '', true);
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function initRecurringPayments($access_token, $source_id, $verification_token, $order_info, $payment=null) {
		$card_fingerprint = isset($payment['payment']['card_details']['card']['fingerprint']) ? $payment['payment']['card_details']['card']['fingerprint'] : '';
		$billing_address = $this->model_extension_payment_squareup->getBillingAddress($order_info);
		$email = $order_info['email'];
		$phone = $this->squareup->phoneFormat($order_info['telephone'], $order_info['payment_iso_code_2']);

		$error = '';
		if ($email=='') {
			$error .= $this->language->get('error_missing_email');
		}
		if ($phone=='') {
			if ($error) {
				$error += "<br>\n";
			}
			$error .= $this->language->get('error_missing_phone');
		}

		if ($error) {
			return $error;
		}

		try {
			$customers = $this->squareup->searchCustomers($access_token, $email, $phone);
			if (!empty($customers['customers'][0])) {
				$customer = array('customer' => $customers['customers'][0]);
			} else {
				$customer = $this->squareup->createCustomer($access_token, $billing_address, $email, $phone);
			}

			if (empty($customer['customer']['id'])) {
				// this should never happen
				$error = str_replace('%2',$phone,str_replace('%1',$email,$this->language->get('error_customer')));
				return $error;
			}

			$customer_id = $customer['customer']['id'];

			$payment_card = null;
			if ($card_fingerprint) {
				$cards = $this->squareup->listCards($access_token, $customer['customer']['id']);
				if (!empty($cards['cards'])) {
					foreach ($cards['cards'] as $card) {
						if ($card['fingerprint'] == $card_fingerprint) {
							$payment_card = array('card' => $card);
							break;
						}
					}
				}
				if (empty($payment_card['card'])) {
					$payment_card = $this->squareup->createCard($access_token, $source_id, $verification_token, $customer['customer']['id'], $billing_address);
				}
			} else {
				$new_card = $this->squareup->createCard($access_token, $source_id, $verification_token, $customer['customer']['id'], $billing_address);
				if ($new_card) {
					$new_card_fingerprint = $new_card['card']['fingerprint'];
					$new_card_id = $new_card['card']['id'];
					$cards = $this->squareup->listCards($access_token, $customer['customer']['id']);
					if (!empty($cards['cards'])) {
						foreach ($cards['cards'] as $card) {
							if ($card['id'] == $new_card_id) {
								continue;
							}
							if ($card['fingerprint'] == $new_card_fingerprint) {
								$this->squareup->disableCard($access_token,$new_card['card']['id']);
								$new_card = array('card'=>$card);
								break;
							}
						}
					}
					$payment_card = $new_card;
				}
			}

			if (empty($payment_card['card'])) {
				// this should never happen
				$error = str_replace('%1',$email,$this->language->get('error_card'));
				return $error;
			}
			$payment_card_id = $payment_card['card']['id'];

			// we now know the card and customer details for future recurring payments
			// do the OpenCart-specific storing of recurring payments into the database
			foreach ($this->cart->getRecurringProducts() as $item) {
				if ($item['recurring']['trial']) {
					$trial_price = $this->tax->calculate($item['recurring']['trial_price'] * $item['quantity'], $item['tax_class_id']);
					$trial_amt = $this->currency->format($trial_price, $this->session->data['currency']);
					$trial_text =  sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration']);
					$item['recurring']['trial_price'] = $trial_price;
				} else {
					$trial_text = '';
				}

				$recurring_price = $this->tax->calculate($item['recurring']['price'] * $item['quantity'], $item['tax_class_id']);
				$recurring_amt = $this->currency->format($recurring_price, $this->session->data['currency']);
				$recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency']);

				$item['recurring']['price'] = $recurring_price;

				if ($item['recurring']['duration'] > 0) {
					$recurring_description .= sprintf($this->language->get('text_length'), $item['recurring']['duration']);
				}

				if (!$item['recurring']['trial']) {
					// We need to override this value for the proper calculation in updateRecurringExpired
					$item['recurring']['trial_duration'] = 0;
				}

				$customer_id = $customer['customer']['id'];

				$payment_id = empty($payment['payment']['id']) ? '' : $payment['payment']['id'];
				if ($payment_id) {
					$this->model_extension_payment_squareup->updatePaymentCustomerId($payment_id, $customer_id);
				}
				$order_recurring_id = $this->model_extension_payment_squareup->createRecurring($item, $this->session->data['order_id'], $recurring_description, $payment_card_id);
				if ($item['recurring']['trial']) {
					$amount = $item['recurring']['trial_price'];
				} else {
					$amount = $item['recurring']['price'];
				}
				$reference = $payment_card_id;
				$this->model_extension_payment_squareup->addRecurringTransaction($order_recurring_id, $reference, $amount, true);
			}
		} catch (\Squareup\Exception $e) {
			if ($e->isCurlError()) {
				$error = $this->language->get('text_token_issue_customer_error');
			} else if ($e->isAccessTokenRevoked()) {
				// Send reminder e-mail to store admin to refresh the token
				$this->model_extension_payment_squareup->tokenRevokedEmail();

				$error = $this->language->get('text_token_issue_customer_error');
			} else if ($e->isAccessTokenExpired()) {
				// Send reminder e-mail to store admin to refresh the token
				$this->model_extension_payment_squareup->tokenExpiredEmail();

				$error = $this->language->get('text_token_issue_customer_error');
			} else {
				$error = $e->getMessage();
			}
		}

		return $error;
	}

	protected function firstPayment($order_info) {
		// the first payment includes the regular items price plus first recurring prices
		if ($this->cart->hasRecurringProducts()) {
			$total = (float)$order_info['total'];
			foreach ($this->cart->getProducts() as $product) {
				if (!empty($product['recurring'])) {
					if ($product['recurring']['trial']) {
						$recurring_price = $this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id']);
						$total += $recurring_price;
					} else {
						$recurring_price = $this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id']);
						$total += $recurring_price;
					}
				}
			}
		} else {
			$total = (float)$order_info['total'];
		}

		return $total;
	}

	// event handler for catalog/view/checkout/confirm/after
	public function eventViewCheckoutConfirmAfter( &$route, &$data, &$output ) {
		if (!$this->config->get('payment_squareup_status')) {
			return null;
		}

		if ($route != 'checkout/confirm') {
			return null;
		}

		if (!isset($this->session->data['payment_method']['code'])) {
			return null;
		}

		if ($this->session->data['payment_method']['code'] != 'squareup') {
			return null;
		}

		if ($this->config->get('payment_squareup_quick_pay')) {
			return null;
		}

		$csp = $this->config->get('payment_squareup_content_security');
		$csp = str_replace("\r", "", str_replace("\n", "", $csp));

		$this->response->addHeader("Content-Security-Policy: " . $csp);

		return null;
	}
}
