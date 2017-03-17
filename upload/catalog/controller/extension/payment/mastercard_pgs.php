<?php
class ControllerExtensionPaymentMastercardPGS extends Controller {
	public function index() {
		$this->load->model('extension/payment/mastercard_pgs');

		$this->load->language('extension/payment/mastercard_pgs');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_new_card'] = $this->language->get('text_new_card');
		$data['text_existing_card'] = $this->language->get('text_existing_card');
		$data['text_select_card'] = $this->language->get('text_select_card');

		$data['onclick'] = $this->config->get('mastercard_pgs_onclick');

		$data['error_session'] = '';
		$data['error_event_missing'] = $this->language->get('error_event_missing');

		if (stripos($this->config->get('mastercard_pgs_merchant'), 'TEST') !== FALSE) {
			$data['warning_test_mode'] = $this->language->get('warning_test_mode');
		} else {
			$data['warning_test_mode'] = '';
		}

		if ($this->customer->isLogged()) {
			$data['cards'] = $this->model_extension_payment_mastercard_pgs->getCards($this->customer->getId());
		} else {
			$data['cards'] = array();
		}

		$this->load->helper('json');

		if (
			($this->customer->isLogged() || (!$this->customer->isLogged() && !empty($this->session->data['guest']))) &&
			!empty($this->session->data['payment_address']) && 
			!empty($this->session->data['currency']) && 
			!empty($this->session->data['order_id']) && 
			$this->model_extension_payment_mastercard_pgs->initCheckoutSession($this->session->data['order_id'], $this->session->data['currency'])
		) {
			$data['configuration_json'] = json_encode($this->configuration());
		} else {
			$data['error_session'] = $this->language->get('error_session');
		}

		return $this->load->view('extension/payment/mastercard_pgs', $data);
	}

	public function init($route) {
		$allowed = array(
			'checkout/checkout'
		);

		if (!in_array($route, $allowed)) {
			return;
		}

		$this->document->addScript($this->url->link('extension/payment/mastercard_pgs/js', '', true));
	}

	public function js() {
		$this->load->language('extension/payment/mastercard_pgs');

		$mydata = array();

		$data['complete'] = $this->url->link('extension/payment/mastercard_pgs/success', '', true);
		$data['cancel'] = $this->url->link('checkout/checkout', '', true);
		
		$data['text_loading'] = $this->language->get('text_loading');

		$this->load->model('extension/payment/mastercard_pgs');

		$data['checkout_script'] = $this->model_extension_payment_mastercard_pgs->getGateway() . '/checkout/version/' . $this->model_extension_payment_mastercard_pgs->getApiVersion() . '/checkout.js';

		$this->response->addHeader("Content-Type:application/javascript");
		$this->response->setOutput($this->load->view('extension/payment/mastercard_pgs_js', $data));
	}

	public function checkout() {
		$this->load->language('extension/payment/mastercard_pgs');

		$this->load->model('extension/payment/mastercard_pgs');

		$json = array(
			'redirect' => $this->url->link('extension/payment/mastercard_pgs/success', '', true)
		);

		if ($this->request->server['REQUEST_METHOD'] != 'POST' || empty($this->request->post['token']) || @base64_decode($this->request->post['token']) === FALSE) {
			$json['error'] = $this->language->get('error_invalid_request');
		} else if (
			($this->customer->isLogged() || (!$this->customer->isLogged() && !empty($this->session->data['guest']))) &&
			!empty($this->session->data['payment_address']) && 
			!empty($this->session->data['currency']) && 
			!empty($this->session->data['order_id']) && 
			$this->model_extension_payment_mastercard_pgs->initCheckoutSession($this->session->data['order_id'], $this->session->data['currency'])
		) {
			$configuration = $this->configuration(false);
			$configuration['sourceOfFunds']['token'] = base64_decode($this->request->post['token']);

			if ($this->config->get('mastercard_pgs_checkout') == 'pay') {
				$configuration['apiOperation'] = 'PAY';
			} else {
				$configuration['apiOperation'] = 'AUTHORIZE';
			}

			$response = $this->model_extension_payment_mastercard_pgs->api('order/' . $this->session->data['order_id'] . '/transaction/1', $configuration, 'PUT');

			if (!empty($response['result']) && $response['result'] == 'ERROR' && $response['error']['cause'] == 'INVALID_REQUEST') {
				$json['error'] = sprintf($this->language->get('error_api'), $response['error']['explanation']);
			} else if (empty($response['result']) || $response['result'] != 'SUCCESS') {
				$json['error'] = $this->language->get('error_unknown');
			}
		} else {
			$json['error'] = $this->language->get('error_session');
		}

		$this->response->addHeader("Content-Type:application/json");
		$this->response->setOutput(json_encode($json));
	}

	public function success() {
		$this->load->model('extension/payment/mastercard_pgs');

		if (
			!empty($this->session->data['order_id']) &&
			!empty($this->session->data['mastercard_pgs']['session']['id']) &&
			!empty($this->session->data['mastercard_pgs']['successIndicator']) &&
			!empty($this->request->get['resultIndicator']) &&
			$this->session->data['mastercard_pgs']['successIndicator'] === $this->request->get['resultIndicator']
		) {
			$session_data = $this->model_extension_payment_mastercard_pgs->api('session/' . $this->session->data['mastercard_pgs']['session']['id']);

			if (!empty($session_data['billing']['address']) && !empty($session_data['interaction']['displayControl']['billingAddress']) && in_array($session_data['interaction']['displayControl']['billingAddress'], array('MANDATORY', 'OPTIONAL'))) {
				$this->model_extension_payment_mastercard_pgs->editOrderPaymentDetails($this->session->data['order_id'], $session_data['billing']['address']);
			}

			if ($this->config->get('mastercard_pgs_tokenize') && empty($session_data['sourceOfFunds']['token'])) {
				$this->model_extension_payment_mastercard_pgs->saveToken($this->session->data['order_id'], token(40), $this->session->data['mastercard_pgs']['session']['id'], $session_data['sourceOfFunds']);
			}
		}

		$this->model_extension_payment_mastercard_pgs->clearCheckoutSession();

		$this->response->redirect($this->url->link('checkout/success', '', true));
	}

	public function callback() {
		$this->load->language('extension/payment/mastercard_pgs');

		$this->load->model('extension/payment/mastercard_pgs');

		if ($this->config->get('mastercard_pgs_debug_log')) {
			$log_data = $this->model_extension_payment_mastercard_pgs->getNotificationLogData();
			$this->log->write($log_data);
		}

		if (!$this->validate_callback()) {
			http_response_code(400);

			return;
		}

		// log the notification and add an order transaction entry
		$transaction_info = $this->model_extension_payment_mastercard_pgs->parseWebhookNotification();

		if ($transaction_info) {
			$transaction_info['partnerSolutionId'] = $this->model_extension_payment_mastercard_pgs->getPartnerSolutionId();

			if (empty($transaction_info['risk']['response']['totalScore'])) {
				$transaction_info['risk']['response']['totalScore'] = 0;
			}

			if (empty($transaction_info['billing']['address']['company'])) {
				$transaction_info['billing']['address']['company'] = '';
			}

			if (empty($transaction_info['version'])) {
				$transaction_info['version'] = '';
			}

			$is_transaction_logged = $this->model_extension_payment_mastercard_pgs->isTransactionLogged($transaction_info['transaction']['id'], $transaction_info['order']['reference']);

			if (!$is_transaction_logged) {
				$this->model_extension_payment_mastercard_pgs->saveTransaction($transaction_info);

				// last check if everything went okay
				$is_transaction_logged = $this->model_extension_payment_mastercard_pgs->isTransactionLogged($transaction_info['transaction']['id'], $transaction_info['order']['reference']);
			} else {
				$this->model_extension_payment_mastercard_pgs->updateTransaction($transaction_info);

				//$is_transaction_logged is obviously true
			}
		} else {
			// we are sure input cannot be parsed into a json
			$is_transaction_logged = false;
		}

		if (!$is_transaction_logged) {
			http_response_code(400);

			return;
		} else {
			$this->load->model('checkout/order');

			$risk_gateway_code = !empty($transaction_info['risk']['response']['gatewayCode']) ? $transaction_info['risk']['response']['gatewayCode'] : null;

			switch ($risk_gateway_code) {
				case 'REJECTED' : {
					$this->model_extension_payment_mastercard_pgs->addOrderHistory(array(
						'order_id' => (int)$transaction_info['order']['reference'],
						'order_status_id' => (int)$this->config->get('mastercard_pgs_risk_rejected_order_status_id'),
						'message' => sprintf(
							$this->language->get('text_callback'),
							$transaction_info['transaction']['type'],
							'RISK (REJECTED)',
							$transaction_info['transaction']['id']
						)
					));
				} break;
				case 'REVIEW_REQUIRED' : {
					$decision_exists = !empty($transaction_info['risk']['response']['review']['decision']);
					$decision_skipped = $decision_exists && in_array($transaction_info['risk']['response']['review']['decision'], array('NOT_REQUIRED', 'ACCEPTED'));

					if (!$decision_exists || $decision_skipped) {
						$this->add_default_order_history($transaction_info);
					} else {
						$this->model_extension_payment_mastercard_pgs->addOrderHistory(array(
							'order_id' => (int)$transaction_info['order']['reference'],
							'order_status_id' => (int)$this->config->get('mastercard_pgs_risk_review_' . strtolower($transaction_info['risk']['response']['review']['decision']) . '_order_status_id'),
							'message' => sprintf(
								$this->language->get('text_callback'),
								$transaction_info['transaction']['type'],
								'RISK_REVIEW (' . $transaction_info['risk']['response']['review']['decision'] . ')',
								$transaction_info['transaction']['id']
							)
						));
					}
				} break;
				default : {
					$this->add_default_order_history($transaction_info);
				} break;
			}
		}

		http_response_code(200);
	}

	protected function add_default_order_history($transaction_info) {
		$this->load->language('extension/payment/mastercard_pgs');

		$this->load->model('extension/payment/mastercard_pgs');

		if ($transaction_info['response']['gatewayCode'] == 'APPROVED') {
			switch ($transaction_info['transaction']['type']) {
				case 'AUTHORIZATION' :
				case 'AUTHORIZATION_UPDATE' : {
					$order_status_id = $this->config->get('mastercard_pgs_approved_authorization_order_status_id');
				} break;
				case 'CAPTURE' : {
					$order_status_id = $this->config->get('mastercard_pgs_approved_capture_order_status_id');
				} break;
				case 'PAYMENT' : {
					$order_status_id = $this->config->get('mastercard_pgs_approved_payment_order_status_id');
				} break;
				case 'REFUND_REQUEST' :
				case 'REFUND' : {
					$order_status_id = $this->config->get('mastercard_pgs_approved_refund_order_status_id');
				} break;
				case 'VOID_AUTHORIZATION' : 
				case 'VOID_CAPTURE' : 
				case 'VOID_PAYMENT' : 
				case 'VOID_REFUND' : {
					$order_status_id = $this->config->get('mastercard_pgs_approved_void_order_status_id');
				} break;
				case 'VERIFICATION' : {
					$order_status_id = $this->config->get('mastercard_pgs_approved_verification_order_status_id');
				} break;
			}
		} else {
			$order_status_id = $this->config->get('mastercard_pgs_' . strtolower($transaction_info['response']['gatewayCode']) . '_order_status_id');
		}

		$this->model_extension_payment_mastercard_pgs->addOrderHistory(array(
			'order_id' => (int)$transaction_info['order']['reference'],
			'order_status_id' => (int)$order_status_id,
			'message' => sprintf(
				$this->language->get('text_callback'),
				$transaction_info['transaction']['type'],
				$transaction_info['result'],
				$transaction_info['transaction']['id']
			)
		));
	}

	protected function validate_callback() {
		$this->load->language('extension/payment/mastercard_pgs');

		$this->load->model('extension/payment/mastercard_pgs');

		// 1. Verify that the notification comes from an HTTPS connection.

		if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on' && $this->request->server['HTTPS'] != '1') || $this->request->server['SERVER_PORT'] != 443) {

			if ($this->config->get('mastercard_pgs_debug_log')) {
				$this->log->write($this->language->get('text_log_validate_callback_intro') . $this->language->get('error_validate_protocol'));
			}

			return false;
		}

		// 2. Verify that the Webhook secret matches the Webhook secret that we have stored.

		if (!$this->config->get('mastercard_pgs_notification_secret') || $this->config->get('mastercard_pgs_notification_secret') != $this->model_extension_payment_mastercard_pgs->getHeaderVar('HTTP_X_NOTIFICATION_SECRET')) {

			if ($this->config->get('mastercard_pgs_debug_log')) {
				$this->log->write($this->language->get('text_log_validate_callback_intro') . $this->language->get('error_secret_mismatch'));
			}

			return false;
		}

		// 3. Parse the notification to get the Transaction info.

		$transaction_info = $this->model_extension_payment_mastercard_pgs->parseWebhookNotification();

		if (!$transaction_info) {
			if ($this->config->get('mastercard_pgs_debug_log')) {
				$this->log->write($this->language->get('text_log_validate_callback_intro') . $this->language->get('error_notification_parse'));
			}

			return false;
		}

		// 4. Verify that the Transaction ID is not already present on the order. This could happen because MPGS will retry several times to send the notification until it gets a 200 http response.

		$is_transaction_logged = $this->model_extension_payment_mastercard_pgs->isTransactionLogged($transaction_info['transaction']['id'], $transaction_info['order']['reference']);

		$is_risk_review = 
			!empty($transaction_info['risk']['response']['gatewayCode']) && 
			!empty($transaction_info['risk']['response']['review']['decision']) &&
			$transaction_info['risk']['response']['gatewayCode'] == 'REVIEW_REQUIRED' && 
			!in_array($transaction_info['risk']['response']['review']['decision'], array('NOT_REQUIRED', 'ACCEPTED'));

		if ($is_transaction_logged && !$is_risk_review) {
			if ($this->config->get('mastercard_pgs_debug_log')) {
				$this->log->write($this->language->get('text_log_validate_callback_intro') . $this->language->get('error_transaction_logged'));
			}

			return false;
		}

		return true;
	}

	protected function configuration($hosted = true) {
		$c = array();

		$this->load->helper('utf8');

		$this->load->language('extension/payment/mastercard_pgs');

		// Limitations taken from here: https://eu-gateway.mastercard.com/api/documentation/apiDocumentation/checkout/version/latest/function/configure.html?locale=en_US

		if (!empty($this->session->data['payment_address']['city'])) {
			$c['billing']['address']['city'] = utf8_substr($this->session->data['payment_address']['city'], 0, 100);
		}

		if (!empty($this->session->data['payment_address']['company'])) {
			$c['billing']['address']['company'] = utf8_substr($this->session->data['payment_address']['company'], 0, 100);
		}

		if (!empty($this->session->data['payment_address']['iso_code_3'])) {
			$c['billing']['address']['country'] = $this->session->data['payment_address']['iso_code_3'];
		}

		if (!empty($this->session->data['payment_address']['postcode'])) {
			$c['billing']['address']['postcodeZip'] = utf8_substr($this->session->data['payment_address']['postcode'], 0, 10);
		}

		if (!empty($this->session->data['payment_address']['zone'])) {
			$c['billing']['address']['stateProvince'] = utf8_substr($this->session->data['payment_address']['zone'], 0, 20);
		}

		if (!empty($this->session->data['payment_address']['address_1'])) {
			$c['billing']['address']['street'] = utf8_substr($this->session->data['payment_address']['address_1'], 0, 100);
		}

		if (!empty($this->session->data['payment_address']['address_2'])) {
			$c['billing']['address']['street2'] = utf8_substr($this->session->data['payment_address']['address_2'], 0, 100);
		}

		if ($hosted) {
			$c['interaction']['displayControl']['billingAddress'] = 'OPTIONAL';
		}
		
		if (!empty($this->session->data['shipping_address'])) {
			$shipping_address = $this->session->data['shipping_address'];
		} else {
			$shipping_address = array();

			if ($hosted) {
				$c['interaction']['displayControl']['shipping'] = 'HIDE';
			}
		}

		if (!empty($shipping_address['city'])) {
			$c['shipping']['address']['city'] = utf8_substr($shipping_address['city'], 0, 100);
		}

		if (!empty($shipping_address['company'])) {
			$c['shipping']['address']['company'] = utf8_substr($shipping_address['company'], 0, 100);
		}

		if (!empty($shipping_address['iso_code_3'])) {
			$c['shipping']['address']['country'] = $shipping_address['iso_code_3'];
		}

		if (!empty($shipping_address['postcode'])) {
			$c['shipping']['address']['postcodeZip'] = utf8_substr($shipping_address['postcode'], 0, 10);
		}

		if (!empty($shipping_address['zone'])) {
			$c['shipping']['address']['stateProvince'] = utf8_substr($shipping_address['zone'], 0, 20);
		}

		if (!empty($shipping_address['address_1'])) {
			$c['shipping']['address']['street'] = utf8_substr($shipping_address['address_1'], 0, 100);
		}

		if (!empty($shipping_address['address_2'])) {
			$c['shipping']['address']['street2'] = utf8_substr($shipping_address['address_2'], 0, 100);
		}

		if (!empty($shipping_address['firstname'])) {
			$c['shipping']['contact']['firstName'] = utf8_substr($shipping_address['firstname'], 0, 50);
		}

		if (!empty($shipping_address['lastname'])) {
			$c['shipping']['contact']['lastName'] = utf8_substr($shipping_address['lastname'], 0, 50);
		}

		if ($this->customer->isLogged()) {
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

			if (!empty($customer_info['firstname'])) {
				$c['customer']['firstName'] = utf8_substr($customer_info['firstname'], 0, 50);
			}

			if (!empty($customer_info['lastname'])) {
				$c['customer']['lastName'] = utf8_substr($customer_info['lastname'], 0, 50);
			}

			if (!empty($customer_info['email']) && utf8_strlen($customer_info['email']) >= 3 && filter_var($customer_info['email'], FILTER_VALIDATE_EMAIL)) {
				$c['customer']['email'] = $customer_info['email'];
				$c['shipping']['contact']['email'] = $customer_info['email'];
			}

			if (!empty($customer_info['telephone'])) {
				$c['customer']['phone'] = utf8_substr($customer_info['telephone'], 0, 20);
				$c['shipping']['contact']['phone'] = utf8_substr($customer_info['telephone'], 0, 20);
			}

			$c['order']['custom']['customerId'] = (int)$this->customer->getId();
		} elseif (isset($this->session->data['guest'])) {
			if (!empty($this->session->data['guest']['firstname'])) {
				$c['customer']['firstName'] = utf8_substr($this->session->data['guest']['firstname'], 0, 50);
			}

			if (!empty($this->session->data['guest']['lastname'])) {
				$c['customer']['lastName'] = utf8_substr($this->session->data['guest']['lastname'], 0, 50);
			}

			if (!empty($this->session->data['guest']['email']) && utf8_strlen($this->session->data['guest']['email']) >= 3 && filter_var($this->session->data['guest']['email'], FILTER_VALIDATE_EMAIL)) {
				$c['customer']['email'] = $this->session->data['guest']['email'];
				$c['shipping']['contact']['email'] = $this->session->data['guest']['email'];
			}

			if (!empty($this->session->data['guest']['telephone'])) {
				$c['customer']['phone'] = utf8_substr($this->session->data['guest']['telephone'], 0, 20);
				$c['shipping']['contact']['phone'] = utf8_substr($this->session->data['guest']['telephone'], 0, 20);
			}
		}

		if ($hosted && $this->config->get('mastercard_pgs_google_analytics_property_id')) {
			$c['interaction']['googleAnalytics']['propertyId'] = $this->config->get('mastercard_pgs_google_analytics_property_id');
		}

		if ($hosted && utf8_strlen($this->language->get('code')) >= 2 && utf8_strlen($this->language->get('code')) <= 5) {
			$c['interaction']['locale'] = $this->language->get('code');
		}

		$store_address_raw = $this->config->get('config_address');
		$store_address = array_values(array_filter(explode(PHP_EOL, $this->config->get('config_address'))));

		if ($hosted && !empty($store_address[0])) {
			$c['interaction']['merchant']['address']['line1'] = utf8_substr($store_address[0], 0, 100);
		}

		if ($hosted && !empty($store_address[1])) {
			$c['interaction']['merchant']['address']['line2'] = utf8_substr($store_address[1], 0, 100);
		}

		if ($hosted && !empty($store_address[2])) {
			$c['interaction']['merchant']['address']['line3'] = utf8_substr($store_address[2], 0, 100);
		}

		if ($hosted && !empty($store_address[3])) {
			$c['interaction']['merchant']['address']['line4'] = utf8_substr($store_address[3], 0, 100);
		}

		if ($hosted && $this->config->get('config_email') && utf8_strlen($this->config->get('config_email')) >= 3 && filter_var($this->config->get('config_email'), FILTER_VALIDATE_EMAIL)) {
			$c['interaction']['merchant']['email'] = $this->config->get('config_email');
		}

		if ($hosted && $this->config->get('config_image')) {
			$this->load->model('tool/image');

			$c['interaction']['merchant']['logo'] = $this->model_tool_image->resize($this->config->get('config_image'), 140, 140);
		}

		if ($hosted) {
			$c['interaction']['merchant']['name'] = utf8_substr($this->config->get('config_name'), 0, 40);
			$c['interaction']['merchant']['phone'] = utf8_substr($this->config->get('config_telephone'), 0, 20);

			$c['merchant'] = $this->config->get('mastercard_pgs_merchant');

			$c['order']['id'] = $this->session->data['order_id'];
		}

		if (!$hosted) {

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$c['device']['browser'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$c['device']['browser'] = 'Unknown';
			}

			$c['device']['ipAddress'] = $this->request->server['REMOTE_ADDR'];
		}

		$c['order']['reference'] = $this->session->data['order_id'];
		
		// Totals
		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);

		$this->load->model('extension/extension');

		$sort_order = array();

		$results = $this->model_extension_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
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

		$skip_totals = array(
			'sub_total',
			'total',
			'tax'
		);

		$result_sub_total = 0;
		$result_tax = 0;
		$result_tax_data = array();
		$result_shipping_handling = 0;
		$result_total = $total;

		foreach ($totals as $key => $value) {
			if ($value['code'] == 'sub_total') {
				$result_sub_total += $value['value'];
			}

			if ($value['code'] == 'tax') {
				$result_tax += $value['value'];
				$result_tax_data[] = array(
					'amount' => $value['value'],
					'type' => $value['title']
				);
			}

			if (!in_array($value['code'], $skip_totals)) {
				$result_shipping_handling += $value['value'];
			}
		}

		if ($result_sub_total + $result_tax + $result_shipping_handling == $result_total) {
			$c['order']['amount'] = $result_total;
			$c['order']['itemAmount'] = $result_sub_total;
			$c['order']['shippingAndHandlingAmount'] = $result_shipping_handling;
			$c['order']['taxAmount'] = $result_tax;
		}

		// End of taxes

		$c['order']['currency'] = $this->session->data['currency'];

		if (!empty($this->session->data['comment'])) {
			$c['order']['customerNote'] = utf8_substr($this->session->data['comment'], 0, 250);
		}

		$c['order']['customerOrderDate'] = date('Y-m-d');

		$most_expensive_sku = '';
		$most_expensive_max = 0;
		
		$this->load->model('catalog/product');

		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();
			$product_info = $this->model_catalog_product->getProduct($product['product_id']);
			
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

				$option_data[] = $option['name'] . ':' . utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value;
			}

			$entry = array();
			
			$entry['name'] = utf8_substr($product['name'], 0, 127);
			$entry['quantity'] = $product['quantity'];
			$entry['unitPrice'] = $product['price'];

			if ($option_data) {
				$entry['description'] = utf8_substr(implode(', ', $option_data), 0, 127);
			} else if ($product['model']) {
				$entry['description'] = utf8_substr($product['model'], 0, 127);
			}

			if ($product['model']) {
				$entry['sku'] = utf8_substr($product['model'], 0, 127);
			}

			if ($product_info['manufacturer']) {
				$entry['brand'] = utf8_substr($product_info['manufacturer'], 0, 127);
			}

			$c['order']['item'][] = $entry;

			if ($most_expensive_max < $product['price']) {
				$most_expensive_max = $product['price'];
				$most_expensive_sku = utf8_substr($product['model'], 0, 127);
			}
		}

		$c['order']['description'] = $this->language->get('text_items');
		$c['order']['productSKU'] = $most_expensive_sku;
		
		if (!empty($result_tax_data)) {
			$c['order']['tax'] = $result_tax_data;
		}

		$c['partnerSolutionId'] = $this->model_extension_payment_mastercard_pgs->getPartnerSolutionId();

		$c['session']['id'] = $this->session->data['mastercard_pgs']['session']['id'];
		$c['session']['version'] = $this->session->data['mastercard_pgs']['session']['version'];

		return $c;
	}
}
