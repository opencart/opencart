<?php
class ControllerExtensionPaymentPayPal extends Controller {
	private $error = array();
		
	public function __construct($registry) {
		parent::__construct($registry);

		if (version_compare(phpversion(), '7.1', '>=')) {
			ini_set('precision', 14);
			ini_set('serialize_precision', 14);
		}
	}
	
	public function index() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		$this->load->model('localisation/country');
				
		$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
				
		// Setting
		$_config = new Config();
		$_config->load('paypal');
			
		$config_setting = $_config->get('paypal_setting');
		
		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
						
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_id'] = $setting['partner'][$data['environment']]['partner_id'];
		$data['transaction_method'] = $this->config->get('payment_paypal_transaction_method');
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
		$data['currency_code'] = $this->config->get('payment_paypal_currency_code');
		
		$data['express_status'] = $setting['checkout']['express']['status'];

		if (!$setting['currency'][$data['currency_code']]['express_status']) {
			$data['express_status'] = $setting['currency'][$data['currency_code']]['express_status'];
		}
		
		$data['button_align'] = $setting['checkout']['express']['button_align'];
		$data['button_size'] = $setting['checkout']['express']['button_size'];
		$data['button_color'] = $setting['checkout']['express']['button_color'];
		$data['button_shape'] = $setting['checkout']['express']['button_shape'];
		$data['button_label'] = $setting['checkout']['express']['button_label'];

		$data['button_width'] = $setting['button_width'][$data['button_size']];
						
		$data['card_status'] = $setting['checkout']['card']['status'];
		
		if (!$setting['currency'][$data['currency_code']]['card_status']) {
			$data['card_status'] = $setting['currency'][$data['currency_code']]['card_status'];
		}
		
		$data['form_align'] = $setting['checkout']['card']['form_align'];
		$data['form_size'] = $setting['checkout']['card']['form_size'];
		$data['form_width'] = $setting['form_width'][$data['form_size']];
		$data['secure_status'] = $setting['checkout']['card']['secure_status'];
				
		$data['order_id'] = $this->session->data['order_id'];
										
		require_once DIR_SYSTEM .'library/paypal/paypal.php';
		
		$paypal_info = array(
			'partner_id' => $data['partner_id'],
			'client_id' => $data['client_id'],
			'secret' => $data['secret'],
			'environment' => $data['environment']
		);
		
		$paypal = new PayPal($paypal_info);
		
		$token_info = array(
			'grant_type' => 'client_credentials'
		);	
				
		$paypal->setAccessToken($token_info);
		
		$data['client_token'] = $paypal->getClientToken();
						
		if ($paypal->hasErrors()) {
			$error_messages = array();
				
			$errors = $paypal->getErrors();
								
			foreach ($errors as $error) {
				if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
					$error['message'] = $this->language->get('error_timeout');
				}
				
				if (isset($error['details'][0]['description'])) {
					$error_messages[] = $error['details'][0]['description'];
				} else {
					$error_messages[] = $error['message'];
				}
									
				$this->model_extension_payment_paypal->log($error, $error['message']);
			}
				
			$this->error['warning'] = implode(' ', $error_messages);
		}			

		return $this->load->view('extension/payment/paypal', $data);
	}
		
	public function createOrder() {					
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		// Setting
		$_config = new Config();
		$_config->load('paypal');
			
		$config_setting = $_config->get('paypal_setting');
		
		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
		
		$client_id = $this->config->get('payment_paypal_client_id');
		$secret = $this->config->get('payment_paypal_secret');
		$merchant_id = $this->config->get('payment_paypal_merchant_id');
		$environment = $this->config->get('payment_paypal_environment');
		$partner_id = $setting['partner'][$environment]['partner_id'];
		$transaction_method = $this->config->get('payment_paypal_transaction_method');
		$currency_code = $this->config->get('payment_paypal_currency_code');
		$currency_value = $this->config->get('payment_paypal_currency_value');
		$decimal_place = $setting['currency'][$currency_code]['decimal_place'];
		
		require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
		$paypal_info = array(
			'partner_id' => $partner_id,
			'client_id' => $client_id,
			'secret' => $secret,
			'environment' => $environment
		);
		
		$paypal = new PayPal($paypal_info);
		
		$token_info = array(
			'grant_type' => 'client_credentials'
		);	
		
		$paypal->setAccessToken($token_info);
		
		$shipping_info = array();

		if ($this->cart->hasShipping()) {
			$shipping_info['name']['full_name'] = $order_info['shipping_firstname'];
			$shipping_info['name']['full_name'] .= $order_info['shipping_lastname'];			
			$shipping_info['address']['address_line_1'] = $order_info['shipping_address_1'];
			$shipping_info['address']['address_line_2'] = $order_info['shipping_address_2'];			
			$shipping_info['address']['admin_area_1'] = $order_info['shipping_zone'];
			$shipping_info['address']['admin_area_2'] = $order_info['shipping_city'];
			$shipping_info['address']['postal_code'] = $order_info['shipping_postcode'];
			
			if ($order_info['shipping_country_id']) {
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);
			
				if ($country_info) {
					$shipping_info['address']['country_code'] = $country_info['iso_code_2'];
				}
			}
			
			$shipping_preference = 'SET_PROVIDED_ADDRESS';
		} else {
			$shipping_preference = 'NO_SHIPPING';
		}
		
		$item_info = array();
		
		$item_total = 0;
				
		foreach ($this->cart->getProducts() as $product) {
			$product_price = number_format($product['price'] * $currency_value, $decimal_place, '.', '');
				
			$item_info[] = array(
				'name' => $product['name'],
				'sku' => $product['model'],
				'url' => $this->url->link('product/product', 'product_id=' . $product['product_id'], true),
				'quantity' => $product['quantity'],
				'unit_amount' => array(
					'currency_code' => $currency_code,
					'value' => $product_price
				)
			);
			
			$item_total += $product_price * $product['quantity'];
		}
				
		$item_total = number_format($item_total, $decimal_place, '.', '');
		$sub_total = $this->cart->getSubTotal();
		$total = $this->cart->getTotal();
		$tax_total = number_format(($total - $sub_total) * $currency_value, $decimal_place, '.', '');
					
		$discount_total = 0;
		$handling_total = 0;
		$shipping_total = 0;
		
		if (isset($this->session->data['shipping_method'])) {
			$shipping_total = $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'], $this->config->get('config_tax'));
			$shipping_total = number_format($shipping_total * $currency_value, $decimal_place, '.', '');
		}
		
		$order_total = number_format($order_info['total'] * $currency_value, $decimal_place, '.', '');
		
		$rebate = number_format($item_total + $tax_total + $shipping_total - $order_total, $decimal_place, '.', '');
		
		if ($rebate > 0) {
			$discount_total = $rebate;
		} elseif ($rebate < 0) {
			$handling_total = -$rebate;
		}

		$amount_info = array(
			'currency_code' => $currency_code,
			'value' => $order_total,
			'breakdown' => array(
				'item_total' => array(
					'currency_code' => $currency_code,
					'value' => $item_total
				),
				'tax_total' => array(
					'currency_code' => $currency_code,
					'value' => $tax_total
				),
				'shipping' => array(
					'currency_code' => $currency_code,
					'value' => $shipping_total
				),
				'handling' => array(
					'currency_code' => $currency_code,
					'value' => $handling_total
				),
				'discount' => array(
					'currency_code' => $currency_code,
					'value' => $discount_total
				)
			)
		);
	
		if ($this->cart->hasShipping()) {
			$order_info = array(
				'intent' => strtoupper($transaction_method),
				'purchase_units' => array(
					array(
						'reference_id' => 'default',
						'description' => 'Your order ' . $order_info['order_id'],
						'invoice_id' => $order_info['order_id'],
						'shipping' => $shipping_info,
						'items' => $item_info,
						'amount' => $amount_info
					)
				),
				'application_context' => array(
					'shipping_preference' => $shipping_preference
				)
			);
		} else {
			$order_info = array(
				'intent' => strtoupper($transaction_method),
				'purchase_units' => array(
					array(
						'reference_id' => 'default',
						'description' => 'Your order ' . $order_info['order_id'],
						'invoice_id' => $order_info['order_id'],
						'items' => $item_info,
						'amount' => $amount_info
					)
				),
				'application_context' => array(
					'shipping_preference' => $shipping_preference
				)
			);
		}

		$result = $paypal->createOrder($order_info);
			
		$data['order_id'] = '';
		
		if (isset($result['id'])) {
			$data['order_id'] = $result['id'];
		}
		
		if ($paypal->hasErrors()) {
			$error_messages = array();
				
			$errors = $paypal->getErrors();
								
			foreach ($errors as $error) {
				if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
					$error['message'] = $this->language->get('error_timeout');
				}
				
				if (isset($error['details'][0]['description'])) {
					$error_messages[] = $error['details'][0]['description'];
				} else {
					$error_messages[] = $error['message'];
				}
					
				$this->model_extension_payment_paypal->log($error, $error['message']);
			}
				
			$this->error['warning'] = implode(' ', $error_messages);
		}
				
		$data['error'] = $this->error;
				
		$this->response->setOutput(json_encode($data));
	}
	
	public function approveOrder() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		// Setting
		$_config = new Config();
		$_config->load('paypal');
			
		$config_setting = $_config->get('paypal_setting');
		
		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
				
		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		}
		
		if (isset($this->request->post['payload'])) {
			$payload = json_decode(htmlspecialchars_decode($this->request->post['payload']), true);
			
			if (isset($payload['orderId'])) {
				$order_id = $payload['orderId'];
						
				if ($setting['checkout']['card']['secure_status']) {					
					$secure_scenario_code = isset($payload['authenticationReason']) ? strtolower($payload['authenticationReason']) : 'undefined';
					
					if (isset($setting['secure_scenario'][$secure_scenario_code]) && isset($setting['checkout']['card']['secure_scenario'][$secure_scenario_code]) && !$setting['checkout']['card']['secure_scenario'][$secure_scenario_code]) {
						$this->error['warning'] = $this->language->get($setting['secure_scenario'][$secure_scenario_code]['error']);
					}
				}
		
				if ($this->error && isset($this->error['warning'])) {
					$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
			}
		}
					
		if (isset($order_id) && !$this->error) {		
			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$transaction_method = $this->config->get('payment_paypal_transaction_method');
			
			require_once DIR_SYSTEM .'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
		
			$paypal->setAccessToken($token_info);
		
			if ($transaction_method == 'authorize') {
				$result = $paypal->setOrderAuthorize($order_id);
				
				if (isset($result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection'])) {
					$seller_protection_status = $result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'];
				}
			} else {
				$result = $paypal->setOrderCapture($order_id);
				
				if (isset($result['purchase_units'][0]['payments']['captures'][0]['seller_protection'])) {
					$seller_protection_status = $result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'];
				}
			}
			
			if (!$this->cart->hasShipping()) {
				$seller_protection_status = 'NOT_ELIGIBLE';
			}
			
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['details'][0]['issue']) && ($error['details'][0]['issue'] == 'INSTRUMENT_DECLINED')) {
						$data['restart'] = true;
					}
					
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
					
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} else {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		
			if (!$this->error) {
				$this->load->model('checkout/order');
				
				$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
				
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('config_order_status_id'), $message);
				
				$data['success'] = $this->url->link('checkout/success', '', true);
			}
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));		
	}
	
	public function webhook() {
		$this->load->model('extension/payment/paypal');
		
		$webhook_data = json_decode(html_entity_decode(file_get_contents('php://input')), true);
		
		$this->model_extension_payment_paypal->log($webhook_data, 'Webhook');
		
		if (isset($webhook_data['event_type']) && isset($webhook_data['resource']['invoice_id'])) {
			// Setting
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
			
			$order_id = $webhook_data['resource']['invoice_id'];
									
			if ($webhook_data['event_type'] == 'PAYMENT.AUTHORIZATION.CREATED') {
				$order_status_id = $setting['order_status']['pending']['id'];
			}
		
			if ($webhook_data['event_type'] == 'PAYMENT.AUTHORIZATION.VOIDED') {
				$order_status_id = $setting['order_status']['voided']['id'];
			}
			
			if ($webhook_data['event_type'] == 'PAYMENT.CAPTURE.COMPLETED') {
				$order_status_id = $setting['order_status']['completed']['id'];
			}
		
			if ($webhook_data['event_type'] == 'PAYMENT.CAPTURE.DENIED') {
				$order_status_id = $setting['order_status']['denied']['id'];
			}
		
			if ($webhook_data['event_type'] == 'PAYMENT.CAPTURE.PENDING') {
				$order_status_id = $setting['order_status']['pending']['id'];
			}
		
			if ($webhook_data['event_type'] == 'PAYMENT.CAPTURE.REFUNDED') {
				$order_status_id = $setting['order_status']['refunded']['id'];
			}
		
			if ($webhook_data['event_type'] == 'PAYMENT.CAPTURE.REVERSED') {
				$order_status_id = $setting['order_status']['reversed']['id'];
			}
		
			if ($webhook_data['event_type'] == 'CHECKOUT.ORDER.COMPLETED') {
				$order_status_id = $setting['order_status']['completed']['id'];
			}
		
			if ($webhook_data['event_type'] == 'CHECKOUT.ORDER.APPROVED') {
				$order_status_id = $setting['order_status']['pending']['id'];
			}
			
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
		}
	}
}