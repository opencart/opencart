<?php
class ControllerExtensionPaymentKlarnaCheckout extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/klarna_checkout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_klarna_checkout', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['api_locations'] = array(
			array(
				'name' => 'North America',
				'code' => 'NA'
			),
			array(
				'name' => 'Europe',
				'code' => 'EU'
			)
		);

		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('extension/payment/klarna_checkout');

		if ($this->model_extension_payment_klarna_checkout->checkForPaymentTaxes()) {
			$data['error_tax_warning'] = $this->language->get('error_tax_warning');
		} else {
			$data['error_tax_warning'] = '';
		}

		if (isset($this->error['account_warning'])) {
			$data['error_account_warning'] = $this->error['account_warning'];
		} else {
			$data['error_account_warning'] = '';
		}

		if (isset($this->error['account'])) {
			$data['error_account'] = $this->error['account'];
		} else {
			$data['error_account'] = array();
		}

		if (isset($this->error['settlement_warning'])) {
			$data['error_settlement_warning'] = $this->error['settlement_warning'];
		} else {
			$data['error_settlement_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/klarna_checkout', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/klarna_checkout', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['klarna_checkout_debug'])) {
			$data['klarna_checkout_debug'] = $this->request->post['klarna_checkout_debug'];
		} else {
			$data['klarna_checkout_debug'] = $this->config->get('klarna_checkout_debug');
		}

		if (isset($this->request->post['klarna_checkout_colour_button'])) {
			$data['klarna_checkout_colour_button'] = $this->request->post['klarna_checkout_colour_button'];
		} elseif ($this->config->get('klarna_checkout_colour_button')) {
			$data['klarna_checkout_colour_button'] = $this->config->get('klarna_checkout_colour_button');
		} else {
			$data['klarna_checkout_colour_button'] = '#0072cc';
		}

		if (isset($this->request->post['klarna_checkout_colour_button_text'])) {
			$data['klarna_checkout_colour_button_text'] = $this->request->post['klarna_checkout_colour_button_text'];
		} elseif ($this->config->get('klarna_checkout_colour_button_text')) {
			$data['klarna_checkout_colour_button_text'] = $this->config->get('klarna_checkout_colour_button_text');
		} else {
			$data['klarna_checkout_colour_button_text'] = '#ffffff';
		}

		if (isset($this->request->post['klarna_checkout_colour_checkbox'])) {
			$data['klarna_checkout_colour_checkbox'] = $this->request->post['klarna_checkout_colour_checkbox'];
		} elseif ($this->config->get('klarna_checkout_colour_checkbox')) {
			$data['klarna_checkout_colour_checkbox'] = $this->config->get('klarna_checkout_colour_checkbox');
		} else {
			$data['klarna_checkout_colour_checkbox'] = '#0072cc';
		}

		if (isset($this->request->post['klarna_checkout_colour_checkbox_checkmark'])) {
			$data['klarna_checkout_colour_checkbox_checkmark'] = $this->request->post['klarna_checkout_colour_checkbox_checkmark'];
		} elseif ($this->config->get('klarna_checkout_colour_checkbox_checkmark')) {
			$data['klarna_checkout_colour_checkbox_checkmark'] = $this->config->get('klarna_checkout_colour_checkbox_checkmark');
		} else {
			$data['klarna_checkout_colour_checkbox_checkmark'] = '#ffffff';
		}

		if (isset($this->request->post['klarna_checkout_colour_header'])) {
			$data['klarna_checkout_colour_header'] = $this->request->post['klarna_checkout_colour_header'];
		} elseif ($this->config->get('klarna_checkout_colour_header')) {
			$data['klarna_checkout_colour_header'] = $this->config->get('klarna_checkout_colour_header');
		} else {
			$data['klarna_checkout_colour_header'] = '#434343';
		}

		if (isset($this->request->post['klarna_checkout_colour_link'])) {
			$data['klarna_checkout_colour_link'] = $this->request->post['klarna_checkout_colour_link'];
		} elseif ($this->config->get('klarna_checkout_colour_link')) {
			$data['klarna_checkout_colour_link'] = $this->config->get('klarna_checkout_colour_link');
		} else {
			$data['klarna_checkout_colour_link'] = '#0072cc';
		}

		if (isset($this->request->post['klarna_checkout_separate_shipping_address'])) {
			$data['klarna_checkout_separate_shipping_address'] = $this->request->post['klarna_checkout_separate_shipping_address'];
		} else {
			$data['klarna_checkout_separate_shipping_address'] = $this->config->get('klarna_checkout_separate_shipping_address');
		}

		if (isset($this->request->post['klarna_checkout_dob_mandatory'])) {
			$data['klarna_checkout_dob_mandatory'] = $this->request->post['klarna_checkout_dob_mandatory'];
		} else {
			$data['klarna_checkout_dob_mandatory'] = $this->config->get('klarna_checkout_dob_mandatory');
		}

		if (isset($this->request->post['klarna_checkout_title_mandatory'])) {
			$data['klarna_checkout_title_mandatory'] = $this->request->post['klarna_checkout_title_mandatory'];
		} else {
			$data['klarna_checkout_title_mandatory'] = $this->config->get('klarna_checkout_title_mandatory');
		}

		if (isset($this->request->post['klarna_checkout_additional_text_box'])) {
			$data['klarna_checkout_additional_text_box'] = $this->request->post['klarna_checkout_additional_text_box'];
		} else {
			$data['klarna_checkout_additional_text_box'] = $this->config->get('klarna_checkout_additional_text_box');
		}

		if (isset($this->request->post['klarna_checkout_total'])) {
			$data['klarna_checkout_total'] = $this->request->post['klarna_checkout_total'];
		} else {
			$data['klarna_checkout_total'] = $this->config->get('klarna_checkout_total');
		}

		if (isset($this->request->post['klarna_checkout_order_status_authorised_id'])) {
			$data['klarna_checkout_order_status_authorised_id'] = $this->request->post['klarna_checkout_order_status_authorised_id'];
		} else {
			$data['klarna_checkout_order_status_authorised_id'] = $this->config->get('klarna_checkout_order_status_authorised_id');
		}

		if (isset($this->request->post['klarna_checkout_order_status_part_captured_id'])) {
			$data['klarna_checkout_order_status_part_captured_id'] = $this->request->post['klarna_checkout_order_status_part_captured_id'];
		} else {
			$data['klarna_checkout_order_status_part_captured_id'] = $this->config->get('klarna_checkout_order_status_part_captured_id');
		}

		if (isset($this->request->post['klarna_checkout_order_status_captured_id'])) {
			$data['klarna_checkout_order_status_captured_id'] = $this->request->post['klarna_checkout_order_status_captured_id'];
		} else {
			$data['klarna_checkout_order_status_captured_id'] = $this->config->get('klarna_checkout_order_status_captured_id');
		}

		if (isset($this->request->post['klarna_checkout_order_status_cancelled_id'])) {
			$data['klarna_checkout_order_status_cancelled_id'] = $this->request->post['klarna_checkout_order_status_cancelled_id'];
		} else {
			$data['klarna_checkout_order_status_cancelled_id'] = $this->config->get('klarna_checkout_order_status_cancelled_id');
		}

		if (isset($this->request->post['klarna_checkout_order_status_refund_id'])) {
			$data['klarna_checkout_order_status_refund_id'] = $this->request->post['klarna_checkout_order_status_refund_id'];
		} else {
			$data['klarna_checkout_order_status_refund_id'] = $this->config->get('klarna_checkout_order_status_refund_id');
		}

		if (isset($this->request->post['klarna_checkout_order_status_fraud_rejected_id'])) {
			$data['klarna_checkout_order_status_fraud_rejected_id'] = $this->request->post['klarna_checkout_order_status_fraud_rejected_id'];
		} else {
			$data['klarna_checkout_order_status_fraud_rejected_id'] = $this->config->get('klarna_checkout_order_status_fraud_rejected_id');
		}

		if (isset($this->request->post['klarna_checkout_order_status_fraud_pending_id'])) {
			$data['klarna_checkout_order_status_fraud_pending_id'] = $this->request->post['klarna_checkout_order_status_fraud_pending_id'];
		} else {
			$data['klarna_checkout_order_status_fraud_pending_id'] = $this->config->get('klarna_checkout_order_status_fraud_pending_id');
		}

		if (isset($this->request->post['klarna_checkout_order_status_fraud_accepted_id'])) {
			$data['klarna_checkout_order_status_fraud_accepted_id'] = $this->request->post['klarna_checkout_order_status_fraud_accepted_id'];
		} else {
			$data['klarna_checkout_order_status_fraud_accepted_id'] = $this->config->get('klarna_checkout_order_status_fraud_accepted_id');
		}

		if (isset($this->request->post['klarna_checkout_terms'])) {
			$data['klarna_checkout_terms'] = $this->request->post['klarna_checkout_terms'];
		} else {
			$data['klarna_checkout_terms'] = $this->config->get('klarna_checkout_terms');
		}

		if (isset($this->request->post['klarna_checkout_status'])) {
			$data['klarna_checkout_status'] = $this->request->post['klarna_checkout_status'];
		} else {
			$data['klarna_checkout_status'] = $this->config->get('klarna_checkout_status');
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post['klarna_checkout_account'])) {
			$data['klarna_checkout_account'] = array();
		} elseif ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['klarna_checkout_account'])) {
			$data['klarna_checkout_account'] = $this->request->post['klarna_checkout_account'];
		} elseif ($this->config->get('klarna_checkout_account')) {
			$data['klarna_checkout_account'] = $this->config->get('klarna_checkout_account');
		} else {
			$data['klarna_checkout_account'] = array();
		}

		if (isset($this->request->post['klarna_checkout_sftp_username'])) {
			$data['klarna_checkout_sftp_username'] = $this->request->post['klarna_checkout_sftp_username'];
		} else {
			$data['klarna_checkout_sftp_username'] = $this->config->get('klarna_checkout_sftp_username');
		}

		if (isset($this->request->post['klarna_checkout_sftp_password'])) {
			$data['klarna_checkout_sftp_password'] = $this->request->post['klarna_checkout_sftp_password'];
		} else {
			$data['klarna_checkout_sftp_password'] = $this->config->get('klarna_checkout_sftp_password');
		}

		if (isset($this->request->post['klarna_checkout_settlement_order_status_id'])) {
			$data['klarna_checkout_settlement_order_status_id'] = $this->request->post['klarna_checkout_settlement_order_status_id'];
		} else {
			$data['klarna_checkout_settlement_order_status_id'] = $this->config->get('klarna_checkout_settlement_order_status_id');
		}

		$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/klarna_checkout', $data));
	}

	public function order() {
		$this->load->language('extension/payment/klarna_checkout');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['order_id'] = $this->request->get['order_id'];

		return $this->load->view('extension/payment/klarna_checkout_order', $data);
	}

	public function getTransaction() {
		$this->load->language('extension/payment/klarna_checkout');

		$this->load->model('extension/payment/klarna_checkout');
		$this->load->model('sale/order');

		if (!$this->config->get('klarna_checkout_status') || !isset($this->request->get['order_id'])) {
			return;
		}

		$order_reference = $this->model_extension_payment_klarna_checkout->getOrder($this->request->get['order_id']);

		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

		if (!$order_reference || !$order_reference['order_ref'] || !$order_info) {
			return;
		}

		list($klarna_account, $connector) = $this->model_extension_payment_klarna_checkout->getConnector($this->config->get('klarna_checkout_account'), $order_info['currency_code']);

		if (!$klarna_account || !$connector) {
			return;
		}

		$klarna_order = $this->model_extension_payment_klarna_checkout->omRetrieve($connector, $order_reference['order_ref']);

		if (!$klarna_order) {
			return;
		}

		$data['order_ref'] = $order_reference['order_ref'];
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['order_id'] = $this->request->get['order_id'];
		
		$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		$extend_authorization_action = $cancel_action = $capture_action = $refund_action = $merchant_reference_action = $address_action = $release_authorization_action = false;

		switch (strtoupper($klarna_order['status'])) {
			case 'AUTHORIZED':
				$merchant_reference_action = true;
				$extend_authorization_action = true;
				$address_action = true;
				$cancel_action = true;
				$capture_action = true;
				break;
			case 'PART_CAPTURED':
				$merchant_reference_action = true;
				$extend_authorization_action = true;
				$release_authorization_action = true;
				$address_action = true;
				$capture_action = true;
				$refund_action = true;
				break;
			case 'CAPTURED':
				$address_action = true;
				$merchant_reference_action = true;
				$refund_action = true;
				break;
			case 'CANCELLED':
				break;
			case 'EXPIRED':
				break;
			case 'CLOSED':
				break;
		}

		$format = '{title} {given_name} {family_name}' . "\n" . '{street_address}' . "\n" . '{street_address2}' . "\n" . '{city} {postcode}' . "\n" . '{region}' . "\n" . '{country}' . "\n" . '{email} {phone}';

		$find = array(
			'{title}',
			'{given_name}',
			'{family_name}',
			'{street_address}',
			'{street_address2}',
			'{city}',
			'{postcode}',
			'{region}',
			'{country}',
			'{email}',
			'{phone}',
		);

		$replace = array(
			'title'				=> $klarna_order['billing_address']['title'],
			'given_name'		=> $klarna_order['billing_address']['given_name'],
			'family_name'		=> $klarna_order['billing_address']['family_name'],
			'street_address'	=> $klarna_order['billing_address']['street_address'],
			'street_address2'	=> $klarna_order['billing_address']['street_address2'],
			'city'				=> $klarna_order['billing_address']['city'],
			'postcode'			=> $klarna_order['billing_address']['postal_code'],
			'region'			=> $klarna_order['billing_address']['region'],
			'country'			=> $klarna_order['billing_address']['country'],
			'email'				=> $klarna_order['billing_address']['email'],
			'phone'				=> $klarna_order['billing_address']['phone']
		);

		$billing_address_formatted = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		$replace = array(
			'title'				=> $klarna_order['shipping_address']['title'],
			'given_name'		=> $klarna_order['shipping_address']['given_name'],
			'family_name'		=> $klarna_order['shipping_address']['family_name'],
			'street_address'	=> $klarna_order['shipping_address']['street_address'],
			'street_address2'	=> $klarna_order['shipping_address']['street_address2'],
			'city'				=> $klarna_order['shipping_address']['city'],
			'postcode'			=> $klarna_order['shipping_address']['postal_code'],
			'region'			=> $klarna_order['shipping_address']['region'],
			'country'			=> $klarna_order['shipping_address']['country'],
			'email'				=> $klarna_order['shipping_address']['email'],
			'phone'				=> $klarna_order['shipping_address']['phone']
		);

		$shipping_address_formatted = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

		$order_lines = array();

		foreach ($klarna_order['order_lines'] as $order_line) {
			$order_lines[] = array(
				'reference'				=> $order_line['reference'],
				'type'					=> $order_line['type'],
				'quantity'				=> $order_line['quantity'],
				'quantity_unit'			=> $order_line['quantity_unit'],
				'name'					=> $order_line['name'],
				'total_amount'			=> $this->currency->format($order_line['total_amount'] / 100, $order_info['currency_code'], '1.00000000'),
				'unit_price'			=> $this->currency->format($order_line['unit_price'] / 100, $order_info['currency_code'], '1.00000000'),
				'total_discount_amount'	=> $this->currency->format($order_line['total_discount_amount'] / 100, $order_info['currency_code'], '1.00000000'),
				'tax_rate'				=> ($order_line['tax_rate'] / 100) . '%',
				'total_tax_amount'		=> $this->currency->format($order_line['total_tax_amount'] / 100, $order_info['currency_code'], '1.00000000')
			);
		}

		$merchant_id = '';
		if ($order_reference['data']) {
			$klarna_checkout_order_data = json_decode($this->encryption->decrypt($this->config->get('config_encryption'), $order_reference['data']), true);
			if ($klarna_checkout_order_data && $klarna_checkout_order_data['merchant_id']) {
				$merchant_id = $klarna_checkout_order_data['merchant_id'];
			}
		}

		$data['transaction'] = array(
			'order_id'					 => $klarna_order['order_id'],
			'merchant_id'				 => $merchant_id,
			'reference'					 => $klarna_order['klarna_reference'],
			'status'					 => $klarna_order['status'],
			'fraud_status'				 => $klarna_order['fraud_status'],
			'merchant_reference_1'		 => $klarna_order['merchant_reference1'],
			'billing_address'			 => $klarna_order['billing_address'],
			'shipping_address'			 => $klarna_order['shipping_address'],
			'billing_address_formatted'	 => $billing_address_formatted,
			'shipping_address_formatted' => $shipping_address_formatted,
			'order_lines'				 => $order_lines,
			'amount'					 => $this->currency->format($klarna_order['order_amount'] / 100, $order_info['currency_code'], '1.00000000'),
			'authorization_expiry'		 => isset($klarna_order['expires_at']) ? date($this->language->get('date_format_short'), strtotime($klarna_order['expires_at'])) : '',
			'authorization_remaining'	 => $this->currency->format($klarna_order['remaining_authorized_amount'] / 100, $order_info['currency_code'], '1.00000000'),
		);

		$max_capture_amount = $klarna_order['remaining_authorized_amount'] / 100;

		$max_refund_amount = $klarna_order['captured_amount'] / 100;

		$data['captures'] = array();

		foreach ($klarna_order['captures'] as $capture) {
			$data['captures'][] = array(
				'capture_id'			=> $capture['capture_id'],
				'shipping_info_title'	=> sprintf($this->language->get('text_capture_shipping_info_title'), $capture['capture_id']),
				'billing_address_title'	=> sprintf($this->language->get('text_capture_billing_address_title'), $capture['capture_id']),
				'date_added'			=> date($this->language->get('datetime_format'), strtotime($capture['captured_at'])),
				'amount'				=> $this->currency->format($capture['captured_amount'] / 100, $order_info['currency_code'], '1.00000000', true),
				'reference'				=> $capture['klarna_reference'],
				'shipping_info'			=> $capture['shipping_info'],
				'billing_address'		=> $capture['billing_address'],
				'shipping_address'		=> $capture['shipping_address']
			);
		}

		$data['refunds'] = array();

		foreach ($klarna_order['refunds'] as $capture) {
			$max_refund_amount -= ($capture['refunded_amount'] / 100);

			$data['refunds'][] = array(
				'date_added' => date($this->language->get('datetime_format'), strtotime($capture['refunded_at'])),
				'amount'	 => $this->currency->format($capture['refunded_amount'] / 100, $order_info['currency_code'], '1.00000000', true)
			);
		}

		if (!$max_capture_amount) {
			$capture_action = false;
		}

		if (!$max_refund_amount) {
			$refund_action = false;
		}

		$data['allowed_shipping_methods'] = array(
			'PickUpStore',
			'Home',
			'BoxReg',
			'BoxUnreg',
			'PickUpPoint',
			'Own'
		);

		$data['extend_authorization_action'] = $extend_authorization_action;
		$data['cancel_action'] = $cancel_action;
		$data['capture_action'] = $capture_action;
		$data['refund_action'] = $refund_action;
		$data['address_action'] = $address_action;
		$data['merchant_reference_action'] = $merchant_reference_action;
		$data['release_authorization_action'] = $release_authorization_action;
		$data['max_capture_amount'] = $this->currency->format($max_capture_amount, $order_info['currency_code'], '1.00000000', false);
		$data['max_refund_amount'] = $this->currency->format($max_refund_amount, $order_info['currency_code'], '1.00000000', false);
		$data['symbol_left'] = $this->currency->getSymbolLeft($order_info['currency_code']);
		$data['symbol_right'] = $this->currency->getSymbolRight($order_info['currency_code']);

		// The URL we send API requests to
		$data['catalog'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
			$session = new Session($this->config->get('session_engine'), $this->registry);

			$session->start();

			$this->model_user_api->deleteApiSessionBySessonId($session->getId());

			$this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

			$session->data['api_id'] = $api_info['api_id'];

			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}

		$this->response->setOutput($this->load->view('extension/payment/klarna_checkout_order_ajax', $data));
	}

	public function install() {
		$this->load->model('extension/payment/klarna_checkout');
		$this->model_extension_payment_klarna_checkout->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/klarna_checkout');
		$this->model_extension_payment_klarna_checkout->uninstall();
	}

	public function transactionCommand() {
		$this->load->language('extension/payment/klarna_checkout');

		$this->load->model('extension/payment/klarna_checkout');
		$this->load->model('sale/order');

		$json = array();

		$success = $error = '';

		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

		list($klarna_account, $connector) = $this->model_extension_payment_klarna_checkout->getConnector($this->config->get('klarna_checkout_account'), $order_info['currency_code']);

		if (!$klarna_account || !$connector) {
			return;
		}

		$klarna_order = $this->model_extension_payment_klarna_checkout->omRetrieve($connector, $this->request->post['order_ref']);

		if (!$klarna_order) {
			return;
		}

		$old_klarna_status = $klarna_order['status'];

		if ($this->request->post['type'] == 'cancel') {
			$action = $this->model_extension_payment_klarna_checkout->omCancel($connector, $this->request->post['order_ref']);
		} elseif ($this->request->post['type'] == 'capture' && $this->request->post['data']) {
			$action = $this->model_extension_payment_klarna_checkout->omCapture($connector, $this->request->post['order_ref'], array(
				'captured_amount' => $this->request->post['data'] * 100
			));
		} elseif ($this->request->post['type'] == 'refund' && $this->request->post['data']) {
			$action = $this->model_extension_payment_klarna_checkout->omRefund($connector, $this->request->post['order_ref'], array(
				'refunded_amount' => $this->request->post['data'] * 100
			));
		} elseif ($this->request->post['type'] == 'extend_authorization') {
			$action = $this->model_extension_payment_klarna_checkout->omExtendAuthorizationTime($connector, $this->request->post['order_ref']);
		} elseif ($this->request->post['type'] == 'merchant_reference' && $this->request->post['data']) {
			$data = array();
			parse_str(html_entity_decode($this->request->post['data']), $data);

			$action = $this->model_extension_payment_klarna_checkout->omUpdateMerchantReference($connector, $this->request->post['order_ref'], array(
				'merchant_reference1' => (string)$data['merchant_reference_1']
			));
		} elseif (($this->request->post['type'] == 'billing_address' || $this->request->post['type'] == 'shipping_address') && $this->request->post['data']) {
			if ($this->request->post['type'] == 'billing_address') {
				$data['billing_address'] = array();
				parse_str(html_entity_decode($this->request->post['data']), $data['billing_address']);
			} else if ($this->request->post['type'] == 'shipping_address') {
				$data['shipping_address'] = array();
				parse_str(html_entity_decode($this->request->post['data']), $data['shipping_address']);
			}

			$action = $this->model_extension_payment_klarna_checkout->omUpdateAddress($connector, $this->request->post['order_ref'], $data);
		} elseif ($this->request->post['type'] == 'release_authorization') {
			$action = $this->model_extension_payment_klarna_checkout->omReleaseAuthorization($connector, $this->request->post['order_ref']);
		} elseif ($this->request->post['type'] == 'capture_shipping_info' && isset($this->request->post['id'])) {
			$data = array();
			parse_str(html_entity_decode($this->request->post['data']), $data);

			$action = $this->model_extension_payment_klarna_checkout->omShippingInfo($connector, $this->request->post['order_ref'], $this->request->post['id'], $data);
		} elseif ($this->request->post['type'] == 'capture_billing_address' && isset($this->request->post['id'])) {
			$data['billing_address'] = array();
			parse_str(html_entity_decode($this->request->post['data']), $data['billing_address']);

			$action = $this->model_extension_payment_klarna_checkout->omCustomerDetails($connector, $this->request->post['order_ref'], $this->request->post['id'], $data);
		} elseif ($this->request->post['type'] == 'trigger_send_out' && isset($this->request->post['id'])) {
			$action = $this->model_extension_payment_klarna_checkout->omTriggerSendOut($connector, $this->request->post['order_ref'], $this->request->post['id']);
		} else {
			$error = true;
		}

		$klarna_order = $this->model_extension_payment_klarna_checkout->omRetrieve($connector, $this->request->post['order_ref']);

		if (!$klarna_order) {
			return;
		}

		$new_klarna_status = $klarna_order['status'];

		$order_status_id = '';
		if ($old_klarna_status != $new_klarna_status) {
			switch ($klarna_order['status']) {
				case 'AUTHORIZED':
					$order_status_id = $this->config->get('klarna_checkout_order_status_authorised_id');

					if ($klarna_order['fraud_status'] == 'PENDING') {
						$order_status_id = $this->config->get('klarna_checkout_order_status_fraud_pending_id');
					} elseif ($klarna_order['fraud_status'] == 'REJECTED') {
						$order_status_id = $this->config->get('klarna_checkout_order_status_fraud_rejected_id');
					}
					break;
				case 'PART_CAPTURED':
					$order_status_id = $this->config->get('klarna_checkout_order_status_part_captured_id');
					break;
				case 'CAPTURED':
					$order_status_id = $this->config->get('klarna_checkout_order_status_captured_id');
					break;
				case 'CANCELLED':
					$order_status_id = $this->config->get('klarna_checkout_order_status_cancelled_id');
					break;
			}
		} elseif ($this->request->post['type'] == 'refund' && ($klarna_order['captured_amount'] - $klarna_order['refunded_amount'] == 0)) {
			$order_status_id = $this->config->get('klarna_checkout_order_status_refund_id');
		}

		if (!$error && $action) {
			$success = $this->language->get('text_success_action');
		} elseif (!$error && $action && isset($action->message)) {
			$error = sprintf($this->language->get('text_error_settle'), $action->message);
		} else {
			$error = $this->language->get('text_error_generic');
		}

		$json['success'] = $success;
		$json['error'] = $error;
		$json['order_status_id'] = $order_status_id;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function downloadSettlementFiles() {
		$this->load->language('extension/payment/klarna_checkout');

		$this->load->model('extension/payment/klarna_checkout');
		$this->load->model('sale/order');

		$json = array();

		$error = array();

		$klarna_checkout_directory = DIR_UPLOAD . 'klarna_checkout/';

		if (isset($this->request->post['username'])) {
			$username = $this->request->post['username'];
		} else {
			$username = '';
		}

		if (isset($this->request->post['password'])) {
			$password = html_entity_decode($this->request->post['password']);
		} else {
			$password = '';
		}

		if (isset($this->request->post['order_status_id'])) {
			$order_status_id = $this->request->post['order_status_id'];
		} else {
			$order_status_id = false;
		}

		if (!$username || !$password || !$order_status_id) {
			$error[] = 'Please supply a username, password and order status';
		}

		if (!$error) {
			// Connect to the site via FTP
			$connection = ftp_connect('mft.klarna.com', '4001');

			$files = array();

			if ($connection) {
				$login = ftp_login($connection, $username, $password);

				if ($login) {
					$files = ftp_nlist($connection, '.');

					rsort($files);

					if (!is_dir($klarna_checkout_directory)) {
						mkdir($klarna_checkout_directory, 0777);
					}

					// Save all files to local
					foreach (array_diff($files, array('.', '..')) as $file) {
						if (!ftp_get($connection, $klarna_checkout_directory . $file, $file, FTP_BINARY)) {
							$error[] = 'There was a problem saving one or more files';
						}
					}
				}
			}
		}

		$orders_to_process = array();

		$files = scandir($klarna_checkout_directory);

		if (!$error) {
			// Loop local files and process
			foreach (array_diff($files, array('.', '..')) as $file) {
				$handle = fopen($klarna_checkout_directory . $file, 'r');

				// Skip first 2 lines, use third as headings
				fgetcsv($handle);
				fgetcsv($handle);
				$headings = fgetcsv($handle);

				while ($data = fgetcsv($handle)) {
					$row = array_combine($headings, $data);

					if ($row['type'] == 'SALE') {
						$order_id = $this->encryption->decrypt($this->config->get('config_encryption'), $row['merchant_reference1']);

						$klarna_order_info = $this->model_extension_payment_klarna_checkout->getOrder($order_id);

						$order_info = $this->model_sale_order->getOrder($order_id);

						// Check if order exists in system, if it does, pass back to process
						if ($klarna_order_info && $order_info && ($order_info['payment_code'] == 'klarna_checkout') && ($order_info['order_status_id'] != $order_status_id)) {
							$orders_to_process[] = $order_id;
						}
					}
				}

				fclose($handle);
			}
		}

		// Delete local files
		foreach (array_diff($files, array('.', '..')) as $file) {
			if (!unlink($klarna_checkout_directory . $file)) {
				$error[] = 'Cannot delete files';
			}
		}

		if ($error) {
			$orders_to_process = array();
		}

		$json['error'] = $error;
		$json['orders'] = $orders_to_process;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		$this->load->model('extension/payment/klarna_checkout');
		$this->load->model('localisation/geo_zone');

		if (version_compare(phpversion(), '5.4.0', '<')) {
			$this->error['warning'] = $this->language->get('error_php_version');
		}

		if (!$this->user->hasPermission('modify', 'extension/payment/klarna_checkout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->config->get('config_secure')) {
			$this->error['warning'] = $this->language->get('error_ssl');
		}

		if (empty($this->request->post['klarna_checkout_account'])) {
			$this->error['account_warning'] = $this->language->get('error_account_minimum');
		} else {
			$currencies = array();

			foreach ($this->request->post['klarna_checkout_account'] as $key => $account) {
				if (in_array($account['currency'], $currencies)) {
					$this->error['account_warning'] = $this->language->get('error_account_currency');

					break;
				} else {
					$currencies[] = $account['currency'];
				}

				if (!$account['merchant_id']) {
					$this->error['account'][$key]['merchant_id'] = $this->language->get('error_merchant_id');
				}

				if (!$account['secret']) {
					$this->error['account'][$key]['secret'] = $this->language->get('error_secret');
				}

				if (!$account['locale']) {
					$this->error['account'][$key]['locale'] = $this->language->get('error_locale');
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}