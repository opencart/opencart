<?php
class ControllerExtensionPaymentGooglePay extends Controller {
	private $error = array();
	private $supported_gateways = array('braintree', 'firstdata', 'globalpayments', 'worldpay');

	public function index() {
		$this->load->language('extension/payment/google_pay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->library('googlepay');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_google_pay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['error_merchant_name'])) {
			$data['error_merchant_name'] = $this->error['error_merchant_name'];
		} else {
			$data['error_merchant_name'] = '';
		}

		if (isset($this->error['error_environment'])) {
			$data['error_environment'] = $this->error['error_environment'];
		} else {
			$data['error_environment'] = '';
		}

		if (isset($this->error['error_status_no_gateway'])) {
			$data['error_status_no_gateway'] = $this->error['error_status_no_gateway'];
		} else {
			$data['error_status_no_gateway'] = '';
		}

		if (isset($this->error['error_auth_method'])) {
			$data['error_auth_method'] = $this->error['error_auth_method'];
		} else {
			$data['error_auth_method'] = '';
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
			'href' => $this->url->link('extension/payment/google_pay', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/google_pay', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_google_pay_merchant_name'])) {
			$data['payment_google_pay_merchant_name'] = $this->request->post['payment_google_pay_merchant_name'];
		} else {
			if ($this->config->get('payment_google_pay_merchant_name')) {
				$data['payment_google_pay_merchant_name'] = $this->config->get('payment_google_pay_merchant_name');
			} else {
				$data['payment_google_pay_merchant_name'] = $this->config->get('config_name');
			}
		}

		$data['supported_gateways'] = array(
			'braintree' => array(
				'title' => $this->language->get('text_braintree'),
				'status' => $this->config->get('payment_pp_braintree_status'),
				'parameters' => array(
					array('key' => 'braintree:apiVersion', 'label' => $this->language->get('text_braintree_api_version'), 'hide' => 1, 'default' => 'v2'),
					array('key' => 'braintree:sdkVersion', 'label' => $this->language->get('text_braintree_sdk_version'), 'hide' => 1, 'default' => '3.42.0'),
					array('key' => 'braintree:merchantId', 'label' => $this->language->get('text_braintree_merchant_id'), 'hide' => 0, 'default' => $this->config->get('payment_pp_braintree_merchant_id')),
					array('key' => 'braintree:clientKey', 'label' => $this->language->get('text_braintree_tokenization_key'), 'hide' => 0, 'default' => ''),
				)
			),
			'firstdata' => array(
				'title' => $this->language->get('text_firstdata'),
				'status' => $this->config->get('payment_firstdata_status'),
				'parameters' => array(
					array('key' => 'gatewayMerchantId', 'label' => $this->language->get('text_firstdata_merchant_id'), 'hide' => 0, 'default' => $this->config->get('payment_firstdata_merchant_id')),
				)
			),
			'globalpayments' => array(
				'title' => $this->language->get('text_globalpayments'),
				'status' => $this->config->get('payment_globalpay_remote_status'),
				'parameters' => array(
					array('key' => 'gatewayMerchantId', 'label' => $this->language->get('text_globalpayments_merchant_id'), 'hide' => 0, 'default' => $this->config->get('payment_globalpay_remote_merchant_id')),
				)
			),
			'worldpay' => array(
				'title' => $this->language->get('text_worldpay'),
				'status' => $this->config->get('payment_worldpay_status'),
				'parameters' => array(
					array('key' => 'gatewayMerchantId', 'label' => $this->language->get('text_worldpay_merchant_id'), 'hide' => 0, 'default' => ''),
				)
			),
		);

		if (isset($this->request->post['payment_google_pay_merchant_gateway'])) {
			$data['payment_google_pay_merchant_gateway'] = $this->request->post['payment_google_pay_merchant_gateway'];
		} else {
			$data['payment_google_pay_merchant_gateway'] = $this->config->get('payment_google_pay_merchant_gateway');
		}

		if (isset($this->request->post['payment_google_pay_merchant_param'])) {
			$data['payment_google_pay_merchant_param'] = $this->request->post['payment_google_pay_merchant_param'];
		} else {
			$data['payment_google_pay_merchant_param'] = $this->config->get('payment_google_pay_merchant_param');
		}

		if (isset($this->request->post['payment_google_pay_environment'])) {
			$data['payment_google_pay_environment'] = $this->request->post['payment_google_pay_environment'];
		} else {
			$data['payment_google_pay_environment'] = $this->config->get('payment_google_pay_environment');
		}

		if (isset($this->request->post['payment_google_pay_geo_zone_id'])) {
			$data['payment_google_pay_geo_zone_id'] = $this->request->post['payment_google_pay_geo_zone_id'];
		} else {
			$data['payment_google_pay_geo_zone_id'] = $this->config->get('payment_google_pay_geo_zone_id');
		}

		if (isset($this->request->post['payment_google_pay_total'])) {
			$data['payment_google_pay_total'] = $this->request->post['payment_google_pay_total'];
		} else {
			$data['payment_google_pay_total'] = $this->config->get('payment_google_pay_total');
		}

		if (isset($this->request->post['payment_google_pay_sort_order'])) {
			$data['payment_google_pay_sort_order'] = $this->request->post['payment_google_pay_sort_order'];
		} else {
			$data['payment_google_pay_sort_order'] = $this->config->get('payment_google_pay_sort_order');
		}

		if (isset($this->request->post['payment_google_pay_status'])) {
			$data['payment_google_pay_status'] = $this->request->post['payment_google_pay_status'];
		} else {
			$data['payment_google_pay_status'] = $this->config->get('payment_google_pay_status');
		}

		if (isset($this->request->post['payment_google_pay_debug'])) {
			$data['payment_google_pay_debug'] = $this->request->post['payment_google_pay_debug'];
		} else {
			$data['payment_google_pay_debug'] = $this->config->get('payment_google_pay_debug');
		}

		if (isset($this->request->post['payment_google_pay_bill_require_phone'])) {
			$data['payment_google_pay_bill_require_phone'] = $this->request->post['payment_google_pay_bill_require_phone'];
		} else {
			$data['payment_google_pay_bill_require_phone'] = $this->config->get('payment_google_pay_bill_require_phone');
		}

		if (isset($this->request->post['payment_google_pay_ship_require_phone'])) {
			$data['payment_google_pay_ship_require_phone'] = $this->request->post['payment_google_pay_ship_require_phone'];
		} else {
			$data['payment_google_pay_ship_require_phone'] = $this->config->get('payment_google_pay_ship_require_phone');
		}

		if (isset($this->request->post['payment_google_pay_accept_prepay_cards'])) {
			$data['payment_google_pay_accept_prepay_cards'] = $this->request->post['payment_google_pay_accept_prepay_cards'];
		} else {
			$data['payment_google_pay_accept_prepay_cards'] = $this->config->get('payment_google_pay_accept_prepay_cards');
		}

		$data['supported_card_networks'] = array(
			'AMEX'			=> $this->language->get('text_card_amex'),
			'DISCOVER'		=> $this->language->get('text_card_discover'),
			'JCB'			=> $this->language->get('text_card_jcb'),
			'MASTERCARD'	=> $this->language->get('text_card_mastercard'),
			'VISA'			=> $this->language->get('text_card_visa'),
		);

		if (isset($this->request->post['payment_google_pay_allow_card_networks'])) {
			$data['payment_google_pay_allow_card_networks'] = $this->request->post['payment_google_pay_allow_card_networks'];
		} else {
			$data['payment_google_pay_allow_card_networks'] = $this->config->get('payment_google_pay_allow_card_networks');
		}

		$data['supported_auth_methods'] = array(
			'PAN_ONLY'		 => $this->language->get('text_pan_only'),
			'CRYPTOGRAM_3DS' => $this->language->get('text_cryptogram_3ds'),
		);

		if (isset($this->request->post['payment_google_pay_allow_auth_methods'])) {
			$data['payment_google_pay_allow_auth_methods'] = $this->request->post['payment_google_pay_allow_auth_methods'];
		} else {
			$data['payment_google_pay_allow_auth_methods'] = $this->config->get('payment_google_pay_allow_auth_methods');
		}

		if (isset($this->request->post['payment_google_pay_button_color'])) {
			$data['payment_google_pay_button_color'] = $this->request->post['payment_google_pay_button_color'];
		} else {
			$data['payment_google_pay_button_color'] = $this->config->get('payment_google_pay_button_color');
		}

		if (isset($this->request->post['payment_google_pay_button_type'])) {
			$data['payment_google_pay_button_type'] = $this->request->post['payment_google_pay_button_type'];
		} else {
			$data['payment_google_pay_button_type'] = $this->config->get('payment_google_pay_button_type');
		}

//		$data['auth_methods'] = array(
//			'PAN_ONLY' 			=> $this->language->get('text_pan_only'),
//			'CRYPTOGRAM_3DS' 	=> $this->language->get('text_cryptogram_3ds'),
//		);
//
//		if (isset($this->request->post['payment_google_pay_auth_methods'])) {
//			$data['payment_google_pay_auth_methods'] = $this->request->post['payment_google_pay_auth_methods'];
//		} else {
//			$data['payment_google_pay_auth_methods'] = $this->config->get('payment_google_pay_auth_methods');
//		}

		$data['card_networks'] = array(
			'AMEX'			=> $this->language->get('text_card_amex'),
			'DISCOVER'		=> $this->language->get('text_card_discover'),
			'JCB'			=> $this->language->get('text_card_jcb'),
			'MASTERCARD'	=> $this->language->get('text_card_mastercard'),
			'VISA'			=> $this->language->get('text_card_visa'),
		);
//
//		if (isset($this->request->post['payment_google_pay_supported_cards'])) {
//			$data['payment_google_pay_supported_cards'] = $this->request->post['payment_google_pay_supported_cards'];
//		} else {
//			$data['payment_google_pay_supported_cards'] = $this->config->get('payment_google_pay_supported_cards');
//		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/google_pay', $data));
		
		/**
		 * DEFAULT OC SETTINGS
		 *# payment_google_pay_status
		 *# payment_google_pay_debug
		 *# payment_google_pay_total
		 *# payment_google_pay_sort_order
		 * MERCHANT INFO
		 *# payment_google_pay_merchant_name UTF-8 optional
		 * PAYMENT PROCESSOR
		 *# payment_google_pay_environment PRODUCTION | TEST
* payment_google_pay_auth_methods PAN_ONLY | CRYPTOGRAM_3DS
* payment_google_pay_supported_cards CHECKBOX | AMEX,DISCOVER,JCB,MASTERCARD,VISA
		 *# payment_google_pay_accept_prepay_cards
		 *
		 * BILLING
* payment_google_pay_billing_address_format MIN | FULL (default is min)
		 *# payment_google_pay_billing_require_phone BOOL
		 *
		 * SHIPPING
* payment_google_pay_shipping_restrict BOOL
* payment_google_pay_ship_allow_countries (ISO 3166-1 alpha-2) | DEFAULT = NOT SET WILL ALLOW ALL
		 *# payment_google_pay_ship_require_phone BOOL
		 */
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/google_pay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->post['payment_google_pay_merchant_name']) || strlen($this->request->post['payment_google_pay_merchant_name']) <= 3 || strlen($this->request->post['payment_google_pay_merchant_name']) > 50) {
			$this->error['error_merchant_name'] = $this->language->get('error_merchant_name');
		}

		if (isset($this->request->post['payment_google_pay_environment']) && !in_array(strtoupper($this->request->post['payment_google_pay_environment']), array("PRODUCTION", "TEST"))) {
			$this->error['error_environment'] = $this->language->get('error_environment');
		}

		if ($this->request->post['payment_google_pay_merchant_gateway'] == '' && $this->request->post['payment_google_pay_status'] == 1) {
			$this->error['error_status_no_gateway'] = $this->language->get('error_status_no_gateway');
		}

		if (empty($this->request->post['payment_google_pay_allow_auth_methods'])) {
			$this->error['error_auth_method'] = $this->language->get('error_auth_method');
		}

		/**
		 * @todo
		 *
		 * Add check for minimum of 1 card type if auth method is chosen for PAN (cards)
		 */

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/payment/google_pay');

		$this->model_extension_payment_google_pay->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/google_pay');

		$this->model_extension_payment_google_pay->uninstall();
	}
}