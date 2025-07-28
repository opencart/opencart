<?php
class ControllerExtensionPaymentPayPal extends Controller {
	private $error = array();
		
	public function __construct($registry) {
		parent::__construct($registry);

		if (version_compare(phpversion(), '7.1', '>=')) {
			ini_set('precision', 14);
			ini_set('serialize_precision', 14);
		}
		
		if (empty($this->config->get('paypal_version')) || (!empty($this->config->get('paypal_version')) && (version_compare($this->config->get('paypal_version'), '3.1.4', '<')))) {
			$this->update();
		}
	}
	
	public function index() {	
		$this->load->model('extension/payment/paypal');
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if ($this->config->get('payment_paypal_status') && $this->config->get('payment_paypal_client_id') && $this->config->get('payment_paypal_secret') && !$this->callback() && !$this->webhook() && !$this->cron() && $agree_status) {
			$this->load->language('extension/payment/paypal');
							
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
						
			$data['client_id'] = $this->config->get('payment_paypal_client_id');
			$data['secret'] = $this->config->get('payment_paypal_secret');
			$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
			$data['environment'] = $this->config->get('payment_paypal_environment');
			$data['partner_id'] = $setting['partner'][$data['environment']]['partner_id'];
			$data['partner_attribution_id'] = $setting['partner'][$data['environment']]['partner_attribution_id'];
			$data['vault_status'] = $setting['general']['vault_status'];
			$data['checkout_mode'] = $setting['general']['checkout_mode'];
			$data['transaction_method'] = $setting['general']['transaction_method'];
			
			$data['button_status'] = $setting['button']['checkout']['status'];
			$data['googlepay_button_status'] = $setting['googlepay_button']['checkout']['status'];
			$data['card_status'] = $setting['card']['status'];
			
			if ($setting['applepay_button']['checkout']['status'] && !empty($this->session->data['paypal']['applepay'])) {
				$data['applepay_button_status'] = $setting['applepay_button']['checkout']['status'];
			} else {
				$data['applepay_button_status'] = false;
			}

			$data['logged'] = $this->customer->isLogged();
										
			require_once DIR_SYSTEM .'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $data['partner_id'],
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['partner_attribution_id']
			);
			
			if (isset($this->session->data['paypal_client_metadata_id'])) {
				$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
			}
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
				
			$paypal->setAccessToken($token_info);
								
			if ($paypal->hasErrors()) {
				$error_messages = array();
				
				$errors = $paypal->getErrors();
								
				foreach ($errors as $error) {
					if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
						$error['message'] = $this->language->get('error_timeout');
					}
				
					if (isset($error['details'][0]['description'])) {
						$error_messages[] = $error['details'][0]['description'];
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
									
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}

			if (!empty($this->error['warning'])) {
				$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}			

			return $this->load->view('extension/payment/paypal/paypal', $data);
		}
		
		return '';
	}
	
	public function modal() {
		$this->load->language('extension/payment/paypal');
							
		$_config = new Config();
		$_config->load('paypal');
			
		$config_setting = $_config->get('paypal_setting');
		
		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
						
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_id'] = $setting['partner'][$data['environment']]['partner_id'];
		$data['partner_attribution_id'] = $setting['partner'][$data['environment']]['partner_attribution_id'];
		$data['vault_status'] = $setting['general']['vault_status'];
		$data['transaction_method'] = $setting['general']['transaction_method'];
			
		$data['button_status'] = $setting['button']['checkout']['status'];
		$data['googlepay_button_status'] = $setting['googlepay_button']['checkout']['status'];
		$data['card_status'] = $setting['card']['status'];
		
		if ($setting['applepay_button']['checkout']['status'] && !empty($this->session->data['paypal']['applepay'])) {
			$data['applepay_button_status'] = $setting['applepay_button']['checkout']['status'];
		} else {
			$data['applepay_button_status'] = false;
		}
		
		$data['logged'] = $this->customer->isLogged();
							
		require_once DIR_SYSTEM .'library/paypal/paypal.php';
		
		$paypal_info = array(
			'partner_id' => $data['partner_id'],
			'client_id' => $data['client_id'],
			'secret' => $data['secret'],
			'environment' => $data['environment'],
			'partner_attribution_id' => $data['partner_attribution_id']
		);
		
		if (isset($this->session->data['paypal_client_metadata_id'])) {
			$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
		}
		
		$paypal = new PayPal($paypal_info);
		
		$token_info = array(
			'grant_type' => 'client_credentials'
		);	
				
		$paypal->setAccessToken($token_info);
								
		if ($paypal->hasErrors()) {
			$error_messages = array();
				
			$errors = $paypal->getErrors();
								
			foreach ($errors as $error) {
				if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
					$error['message'] = $this->language->get('error_timeout');
				}
				
				if (isset($error['details'][0]['description'])) {
					$error_messages[] = $error['details'][0]['description'];
				} elseif (isset($error['message'])) {
					$error_messages[] = $error['message'];
				}
									
				$this->model_extension_payment_paypal->log($error, $error['message']);
			}
				
			$this->error['warning'] = implode(' ', $error_messages);
		}

		if (!empty($this->error['warning'])) {
			$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
		}			
						
		$data['error'] = $this->error;

		$this->response->setOutput($this->load->view('extension/payment/paypal/paypal_modal', $data));
	}
	
	public function getData() {
		$this->load->model('extension/payment/paypal');
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if ($this->config->get('payment_paypal_status') && $this->config->get('payment_paypal_client_id') && $this->config->get('payment_paypal_secret') && $agree_status && !empty($this->request->post['page_code'])) {	
			$this->load->language('extension/payment/paypal');
		
			$this->load->model('localisation/country');
			$this->load->model('checkout/order');
		
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
			
			$secret = $this->config->get('payment_paypal_secret');
						
			$data['page_code'] = $this->request->post['page_code'];
			$data['client_id'] = $this->config->get('payment_paypal_client_id');
			$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
			$data['environment'] = $this->config->get('payment_paypal_environment');
			$data['googlepay_environment'] = (($data['environment'] == 'production') ? 'PRODUCTION' : 'TEST');
			$data['partner_id'] = $setting['partner'][$data['environment']]['partner_id'];
			$data['partner_attribution_id'] = $setting['partner'][$data['environment']]['partner_attribution_id'];
			$data['vault_status'] = $setting['general']['vault_status'];
			$data['transaction_method'] = $setting['general']['transaction_method'];
			
			$country = $this->model_extension_payment_paypal->getCountryByCode($setting['general']['country_code']);
			
			$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
				
			$data['currency_code'] = $this->session->data['currency'];
			$data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
		
			if (empty($setting['currency'][$data['currency_code']]['status'])) {
				$data['currency_code'] = $setting['general']['currency_code'];
				$data['currency_value'] = $setting['general']['currency_value'];
			}
		
			$data['decimal_place'] = $setting['currency'][$data['currency_code']]['decimal_place'];
			
			if (!empty($this->request->post['applepay'])) {
				$this->session->data['paypal']['applepay'] = true;
			}
			
			$data['components'] = array();
			
			if ($this->request->post['page_code'] == 'home') {				
				if ($setting['message']['home']['status'] && !empty($setting['paylater_country'][$setting['general']['country_code']]) && ($data['currency_code'] == $setting['general']['currency_code'])) {
					$data['components'][] = 'messages';
					
					$data['message_status'] = $setting['message']['home']['status'];
					$data['message_insert_tag'] = html_entity_decode($setting['message']['home']['insert_tag']);
					$data['message_insert_type'] = $setting['message']['home']['insert_type'];
					$data['message_layout'] = $setting['message']['home']['layout'];
					$data['message_logo_type'] = $setting['message']['home']['logo_type'];
					$data['message_logo_position'] = $setting['message']['home']['logo_position'];
					$data['message_text_color'] = $setting['message']['home']['text_color'];
					$data['message_text_size'] = $setting['message']['home']['text_size'];
					$data['message_flex_color'] = $setting['message']['home']['flex_color'];
					$data['message_flex_ratio'] = $setting['message']['home']['flex_ratio'];
									
					$item_total = 0;
								
					foreach ($this->cart->getProducts() as $product) {
						$product_price = $this->tax->calculate($product['price'], $product['tax_class_id'], true);
									
						$item_total += $product_price * $product['quantity'];
					}
					
					if (!empty($this->session->data['vouchers'])) {
						foreach ($this->session->data['vouchers'] as $voucher) {
							$item_total += $voucher['amount'];
						}
					}
			
					$data['message_amount'] = number_format($item_total * $data['currency_value'], $data['decimal_place'], '.', '');
				}
			}
			
			if (!empty($this->request->post['product'])) {
				$this->request->post['product'] = $this->unserialize($this->request->post['product']);
			}
			
			if (($this->request->post['page_code'] == 'product') && !empty($this->request->post['product']['product_id'])) {
				$product = $this->request->post['product'];
				$product_id = (int)$this->request->post['product']['product_id'];
				$product_price = 0;
				
				if (isset($product['quantity'])) {
					$quantity = (int)$product['quantity'];
				} else {
					$quantity = 1;
				}

				if (isset($product['option'])) {
					$option = array_filter($product['option']);
				} else {
					$option = array();
				}
				
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info && ($this->customer->isLogged() || !$this->config->get('config_customer_price'))) {				
					$option_price = 0;

					$product_options = $this->model_catalog_product->getProductOptions($product_id);
						
					foreach ($product_options as $product_option) {
						if (isset($option[$product_option['product_option_id']])) {
							if (($product_option['type'] == 'select') || ($product_option['type'] == 'radio')) {
								foreach ($product_option['product_option_value'] as $product_option_value) {
									if (!$product_option_value['subtract'] || ($product_option_value['quantity'] > 0)) {
										if ((float)$product_option_value['price']) {
											if ($option[$product_option['product_option_id']] == $product_option_value['product_option_value_id']) {
												if ($product_option_value['price_prefix'] == '+') {
													$option_price += $product_option_value['price'];
												} elseif ($product_option_value['price_prefix'] == '-') {
													$option_price -= $product_option_value['price'];
												}
											}
										}
									}	
								}
							} elseif (($product_option['type'] == 'checkbox') && is_array($option[$product_option['product_option_id']])) {
								foreach ($product_option['product_option_value'] as $product_option_value) {
									if (!$product_option_value['subtract'] || ($product_option_value['quantity'] > 0)) {
										if ((float)$product_option_value['price']) {
											if (in_array($product_option_value['product_option_value_id'], $option[$product_option['product_option_id']])) {
												if ($product_option_value['price_prefix'] == '+') {
													$option_price += $product_option_value['price'];
												} elseif ($product_option_value['price_prefix'] == '-') {
													$option_price -= $product_option_value['price'];
												}
											}
										}
									}	
								}
							}
						}
					}
					
					if ((float)$product_info['special']) {
						$product_price = $this->tax->calculate(($product_info['special'] + $option_price) * $quantity, $product_info['tax_class_id'], true);
					} else {
						$product_price = $this->tax->calculate(($product_info['price'] + $option_price) * $quantity, $product_info['tax_class_id'], true);
					}
				}
				
				if ($setting['button']['product']['status']) {
					$data['components'][] = 'buttons';
					
					$data['button_status'] = $setting['button']['product']['status'];
					$data['button_insert_tag'] = html_entity_decode($setting['button']['product']['insert_tag']);
					$data['button_insert_type'] = $setting['button']['product']['insert_type'];
					$data['button_align'] = $setting['button']['product']['align'];
					$data['button_size'] = $setting['button']['product']['size'];
					$data['button_width'] = $setting['button_width'][$data['button_size']];
					$data['button_color'] = $setting['button']['product']['color'];
					$data['button_shape'] = $setting['button']['product']['shape'];
					$data['button_label'] = $setting['button']['product']['label'];
										
					$data['button_enable_funding'] = array();
					$data['button_disable_funding'] = array();
				
					foreach ($setting['button_funding'] as $button_funding) {
						if ($setting['button']['product']['funding'][$button_funding['code']] == 1) {
							$data['button_enable_funding'][] = $button_funding['code'];
						} 
				
						if ($setting['button']['product']['funding'][$button_funding['code']] == 2) {
							$data['button_disable_funding'][] = $button_funding['code'];
						}
					}
				}
				
				if ($setting['googlepay_button']['product']['status']) {
					$data['components'][] = 'googlepay';
					
					$data['googlepay_button_status'] = $setting['googlepay_button']['product']['status'];
					$data['googlepay_button_insert_tag'] = html_entity_decode($setting['googlepay_button']['product']['insert_tag']);
					$data['googlepay_button_insert_type'] = $setting['googlepay_button']['product']['insert_type'];
					$data['googlepay_button_align'] = $setting['googlepay_button']['product']['align'];
					$data['googlepay_button_size'] = $setting['googlepay_button']['product']['size'];
					$data['googlepay_button_width'] = $setting['googlepay_button_width'][$data['googlepay_button_size']];
					$data['googlepay_button_color'] = $setting['googlepay_button']['product']['color'];
					$data['googlepay_button_shape'] = $setting['googlepay_button']['product']['shape'];
					$data['googlepay_button_type'] = $setting['googlepay_button']['product']['type'];
					
					if ($product_price) {
						$data['googlepay_amount'] = number_format($product_price * $data['currency_value'], $data['decimal_place'], '.', ''); 			
					}
				}
				
				if ($setting['applepay_button']['product']['status'] && !empty($this->session->data['paypal']['applepay'])) {
					$data['components'][] = 'applepay';
					
					$data['applepay_button_status'] = $setting['applepay_button']['product']['status'];
					$data['applepay_button_insert_tag'] = html_entity_decode($setting['applepay_button']['product']['insert_tag']);
					$data['applepay_button_insert_type'] = $setting['applepay_button']['product']['insert_type'];
					$data['applepay_button_align'] = $setting['applepay_button']['product']['align'];
					$data['applepay_button_size'] = $setting['applepay_button']['product']['size'];
					$data['applepay_button_width'] = $setting['applepay_button_width'][$data['applepay_button_size']];
					$data['applepay_button_color'] = $setting['applepay_button']['product']['color'];
					$data['applepay_button_shape'] = $setting['applepay_button']['product']['shape'];
					$data['applepay_button_type'] = $setting['applepay_button']['product']['type'];
					
					if ($product_price) {
						$data['applepay_amount'] = number_format($product_price * $data['currency_value'], $data['decimal_place'], '.', ''); 			
					}
				}
								
				if ($setting['message']['product']['status'] && !empty($setting['paylater_country'][$setting['general']['country_code']]) && ($data['currency_code'] == $setting['general']['currency_code'])) {
					$data['components'][] = 'messages';
					
					$data['message_status'] = $setting['message']['product']['status'];
					$data['message_insert_tag'] = html_entity_decode($setting['message']['product']['insert_tag']);
					$data['message_insert_type'] = $setting['message']['product']['insert_type'];
					$data['message_layout'] = $setting['message']['product']['layout'];
					$data['message_logo_type'] = $setting['message']['product']['logo_type'];
					$data['message_logo_position'] = $setting['message']['product']['logo_position'];
					$data['message_text_color'] = $setting['message']['product']['text_color'];
					$data['message_text_size'] = $setting['message']['product']['text_size'];
					$data['message_flex_color'] = $setting['message']['product']['flex_color'];
					$data['message_flex_ratio'] = $setting['message']['product']['flex_ratio'];
									
					if ($product_price) {
						$data['message_amount'] = number_format($product_price * $data['currency_value'], $data['decimal_place'], '.', ''); 			
					}
				}
			}
			
			if (($this->request->post['page_code'] == 'cart') && ($this->cart->hasProducts() || !empty($this->session->data['vouchers']))) {
				if ($setting['button']['cart']['status']) {
					$data['components'][] = 'buttons';
					
					$data['button_status'] = $setting['button']['cart']['status'];
					$data['button_insert_tag'] = html_entity_decode($setting['button']['cart']['insert_tag']);
					$data['button_insert_type'] = $setting['button']['cart']['insert_type'];
					$data['button_align'] = $setting['button']['cart']['align'];
					$data['button_size'] = $setting['button']['cart']['size'];
					$data['button_width'] = $setting['button_width'][$data['button_size']];
					$data['button_color'] = $setting['button']['cart']['color'];
					$data['button_shape'] = $setting['button']['cart']['shape'];
					$data['button_label'] = $setting['button']['cart']['label'];

					$data['button_enable_funding'] = array();
					$data['button_disable_funding'] = array();
				
					foreach ($setting['button_funding'] as $button_funding) {
						if ($setting['button']['cart']['funding'][$button_funding['code']] == 1) {
							$data['button_enable_funding'][] = $button_funding['code'];
						} 
				
						if ($setting['button']['cart']['funding'][$button_funding['code']] == 2) {
							$data['button_disable_funding'][] = $button_funding['code'];
						}
					}
				}
				
				if ($setting['googlepay_button']['cart']['status']) {
					$data['components'][] = 'googlepay';
					
					$data['googlepay_button_status'] = $setting['googlepay_button']['cart']['status'];
					$data['googlepay_button_insert_tag'] = html_entity_decode($setting['googlepay_button']['cart']['insert_tag']);
					$data['googlepay_button_insert_type'] = $setting['googlepay_button']['cart']['insert_type'];
					$data['googlepay_button_align'] = $setting['googlepay_button']['cart']['align'];
					$data['googlepay_button_size'] = $setting['googlepay_button']['cart']['size'];
					$data['googlepay_button_width'] = $setting['googlepay_button_width'][$data['googlepay_button_size']];
					$data['googlepay_button_color'] = $setting['googlepay_button']['cart']['color'];
					$data['googlepay_button_shape'] = $setting['googlepay_button']['cart']['shape'];
					$data['googlepay_button_type'] = $setting['googlepay_button']['cart']['type'];
					
					$item_total = 0;
								
					foreach ($this->cart->getProducts() as $product) {
						$product_price = $this->tax->calculate($product['price'], $product['tax_class_id'], true);
									
						$item_total += $product_price * $product['quantity'];
					}
						
					if (!empty($this->session->data['vouchers'])) {
						foreach ($this->session->data['vouchers'] as $voucher) {
							$item_total += $voucher['amount'];
						}
					}
			
					$data['googlepay_amount'] = number_format($item_total * $data['currency_value'], $data['decimal_place'], '.', '');
				}
				
				if ($setting['applepay_button']['cart']['status'] && !empty($this->session->data['paypal']['applepay'])) {
					$data['components'][] = 'applepay';
					
					$data['applepay_button_status'] = $setting['applepay_button']['cart']['status'];
					$data['applepay_button_insert_tag'] = html_entity_decode($setting['applepay_button']['cart']['insert_tag']);
					$data['applepay_button_insert_type'] = $setting['applepay_button']['cart']['insert_type'];
					$data['applepay_button_align'] = $setting['applepay_button']['cart']['align'];
					$data['applepay_button_size'] = $setting['applepay_button']['cart']['size'];
					$data['applepay_button_width'] = $setting['applepay_button_width'][$data['applepay_button_size']];
					$data['applepay_button_color'] = $setting['applepay_button']['cart']['color'];
					$data['applepay_button_shape'] = $setting['applepay_button']['cart']['shape'];
					$data['applepay_button_type'] = $setting['applepay_button']['cart']['type'];
					
					$item_total = 0;
								
					foreach ($this->cart->getProducts() as $product) {
						$product_price = $this->tax->calculate($product['price'], $product['tax_class_id'], true);
								
						$item_total += $product_price * $product['quantity'];
					}
						
					if (!empty($this->session->data['vouchers'])) {
						foreach ($this->session->data['vouchers'] as $voucher) {
							$item_total += $voucher['amount'];
						}
					}
			
					$data['applepay_amount'] = number_format($item_total * $data['currency_value'], $data['decimal_place'], '.', '');
				}
				
				if ($setting['message']['cart']['status'] && !empty($setting['paylater_country'][$setting['general']['country_code']]) && ($data['currency_code'] == $setting['general']['currency_code'])) {
					$data['components'][] = 'messages';
					
					$data['message_status'] = $setting['message']['cart']['status'];
					$data['message_insert_tag'] = html_entity_decode($setting['message']['cart']['insert_tag']);
					$data['message_insert_type'] = $setting['message']['cart']['insert_type'];
					$data['message_layout'] = $setting['message']['cart']['layout'];
					$data['message_logo_type'] = $setting['message']['cart']['logo_type'];
					$data['message_logo_position'] = $setting['message']['cart']['logo_position'];
					$data['message_text_color'] = $setting['message']['cart']['text_color'];
					$data['message_text_size'] = $setting['message']['cart']['text_size'];
					$data['message_flex_color'] = $setting['message']['cart']['flex_color'];
					$data['message_flex_ratio'] = $setting['message']['cart']['flex_ratio'];
									
					$item_total = 0;
								
					foreach ($this->cart->getProducts() as $product) {
						$product_price = $this->tax->calculate($product['price'], $product['tax_class_id'], true);
									
						$item_total += $product_price * $product['quantity'];
					}
					
					if (!empty($this->session->data['vouchers'])) {
						foreach ($this->session->data['vouchers'] as $voucher) {
							$item_total += $voucher['amount'];
						}
					}
			
					$data['message_amount'] = number_format($item_total * $data['currency_value'], $data['decimal_place'], '.', '');
				}
			}
			
			if (($this->request->post['page_code'] == 'checkout') && ($this->cart->hasProducts() || !empty($this->session->data['vouchers']))) {
				if (!empty($this->session->data['order_id'])) {
					$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				}
				
				if ($setting['button']['checkout']['status']) {
					$data['components'][] = 'buttons';
					$data['components'][] = 'funding-eligibility';
					
					$data['button_status'] = $setting['button']['checkout']['status'];
					$data['button_align'] = $setting['button']['checkout']['align'];
					$data['button_size'] = $setting['button']['checkout']['size'];
					$data['button_width'] = $setting['button_width'][$data['button_size']];
					$data['button_color'] = $setting['button']['checkout']['color'];
					$data['button_shape'] = $setting['button']['checkout']['shape'];
					$data['button_label'] = $setting['button']['checkout']['label'];
									
					$data['button_enable_funding'] = array();
					$data['button_disable_funding'] = array();
				
					foreach ($setting['button_funding'] as $button_funding) {
						if ($setting['button']['checkout']['funding'][$button_funding['code']] == 1) {
							$data['button_enable_funding'][] = $button_funding['code'];
						} 
				
						if ($setting['button']['checkout']['funding'][$button_funding['code']] == 2) {
							$data['button_disable_funding'][] = $button_funding['code'];
						}
					}
										
					if (isset($this->session->data['payment_method']['code']) && ($this->session->data['payment_method']['code'] == 'paypal_paylater')) {
						$data['button_funding_source'] = 'paylater';
					}
				}
				
				if ($setting['googlepay_button']['checkout']['status']) {
					$data['components'][] = 'googlepay';
					
					$data['googlepay_button_status'] = $setting['googlepay_button']['checkout']['status'];
					$data['googlepay_button_align'] = $setting['googlepay_button']['checkout']['align'];
					$data['googlepay_button_size'] = $setting['googlepay_button']['checkout']['size'];
					$data['googlepay_button_width'] = $setting['googlepay_button_width'][$data['googlepay_button_size']];
					$data['googlepay_button_color'] = $setting['googlepay_button']['checkout']['color'];
					$data['googlepay_button_shape'] = $setting['googlepay_button']['checkout']['shape'];
					$data['googlepay_button_type'] = $setting['googlepay_button']['checkout']['type'];
					
					if (!empty($order_info)) {
						$data['googlepay_amount'] = number_format($order_info['total'] * $data['currency_value'], $data['decimal_place'], '.', '');
					} else {
						$item_total = 0;
								
						foreach ($this->cart->getProducts() as $product) {
							$product_price = $this->tax->calculate($product['price'], $product['tax_class_id'], true);
									
							$item_total += $product_price * $product['quantity'];
						}
						
						if (!empty($this->session->data['vouchers'])) {
							foreach ($this->session->data['vouchers'] as $voucher) {
								$item_total += $voucher['amount'];
							}
						}
			
						$data['googlepay_amount'] = number_format($item_total * $data['currency_value'], $data['decimal_place'], '.', '');
					}
				}
				
				if ($setting['applepay_button']['checkout']['status'] && !empty($this->session->data['paypal']['applepay'])) {
					$data['components'][] = 'applepay';
					
					$data['applepay_button_status'] = $setting['applepay_button']['checkout']['status'];
					$data['applepay_button_align'] = $setting['applepay_button']['checkout']['align'];
					$data['applepay_button_size'] = $setting['applepay_button']['checkout']['size'];
					$data['applepay_button_width'] = $setting['applepay_button_width'][$data['applepay_button_size']];
					$data['applepay_button_color'] = $setting['applepay_button']['checkout']['color'];
					$data['applepay_button_shape'] = $setting['applepay_button']['checkout']['shape'];
					$data['applepay_button_type'] = $setting['applepay_button']['checkout']['type'];
					
					if (!empty($order_info)) {
						$data['applepay_amount'] = number_format($order_info['total'] * $data['currency_value'], $data['decimal_place'], '.', '');
					} else {
						$item_total = 0;
								
						foreach ($this->cart->getProducts() as $product) {
							$product_price = $this->tax->calculate($product['price'], $product['tax_class_id'], true);
									
							$item_total += $product_price * $product['quantity'];
						}
						
						if (!empty($this->session->data['vouchers'])) {
							foreach ($this->session->data['vouchers'] as $voucher) {
								$item_total += $voucher['amount'];
							}
						}
			
						$data['applepay_amount'] = number_format($item_total * $data['currency_value'], $data['decimal_place'], '.', '');
					}
				}
								
				if ($setting['card']['status']) {										
					$data['components'][] = 'card-fields';
					
					$data['card_status'] = $setting['card']['status'];
					$data['card_align'] = $setting['card']['align'];
					$data['card_size'] = $setting['card']['size'];
					$data['card_width'] = $setting['card_width'][$data['card_size']];
										
					$data['card_customer_tokens'] = array();
					
					if ($setting['general']['vault_status'] && $this->customer->isLogged()) {
						$card_customer_tokens = $this->model_extension_payment_paypal->getPayPalCustomerTokens($this->customer->getId(), 'card');
			
						foreach ($card_customer_tokens as $card_customer_token) {
							$data['card_customer_tokens'][] = array(
								'vault_id' => $card_customer_token['vault_id'],
								'card_type' => $card_customer_token['card_type'],
								'card_number' => sprintf($this->language->get('text_card_number'), $card_customer_token['card_nice_type'], $card_customer_token['card_last_digits'])
							);
						}
					}
				}
				
				if ($setting['fastlane']['status'] && ($setting['general']['country_code'] == 'US') && !$this->customer->isLogged()) {
					$data['components'][] = 'fastlane';
					
					$data['fastlane_status'] = $setting['fastlane']['status'];
					$data['fastlane_card_align'] = $setting['fastlane']['card']['align'];
					$data['fastlane_card_size'] = $setting['fastlane']['card']['size'];
					$data['fastlane_card_width'] = $setting['fastlane_card_width'][$data['fastlane_card_size']];
																				
					$data['error_fastlane_billing_address'] = $this->language->get('error_fastlane_billing_address');
		
					$data['fastlane_html'] = $this->load->view('extension/payment/paypal/fastlane_customer', $data);
				}
				
				if ($setting['message']['checkout']['status'] && !empty($setting['paylater_country'][$setting['general']['country_code']]) && ($data['currency_code'] == $setting['general']['currency_code'])) {
					$data['components'][] = 'messages';
					
					$data['message_status'] = $setting['message']['checkout']['status'];
					$data['message_layout'] = $setting['message']['checkout']['layout'];
					$data['message_logo_type'] = $setting['message']['checkout']['logo_type'];
					$data['message_logo_position'] = $setting['message']['checkout']['logo_position'];
					$data['message_text_color'] = $setting['message']['checkout']['text_color'];
					$data['message_text_size'] = $setting['message']['checkout']['text_size'];
					$data['message_flex_color'] = $setting['message']['checkout']['flex_color'];
					$data['message_flex_ratio'] = $setting['message']['checkout']['flex_ratio'];
									
					if (!empty($order_info)) {
						$data['message_amount'] = number_format($order_info['total'] * $data['currency_value'], $data['decimal_place'], '.', '');
					} else {
						$item_total = 0;
								
						foreach ($this->cart->getProducts() as $product) {
							$product_price = $this->tax->calculate($product['price'], $product['tax_class_id'], true);
									
							$item_total += $product_price * $product['quantity'];
						}
						
						if (!empty($this->session->data['vouchers'])) {
							foreach ($this->session->data['vouchers'] as $voucher) {
								$item_total += $voucher['amount'];
							}
						}
			
						$data['message_amount'] = number_format($item_total * $data['currency_value'], $data['decimal_place'], '.', '');
					}
				}
			}
			
			require_once DIR_SYSTEM .'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $data['partner_id'],
				'client_id' => $data['client_id'],
				'secret' => $secret,
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['partner_attribution_id']
			);
			
			if (isset($this->session->data['paypal_client_metadata_id'])) {
				$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
			}
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);
						
			if ($setting['general']['vault_status'] && $this->customer->isLogged()) {
				$paypal_customer_token = $this->model_extension_payment_paypal->getPayPalCustomerMainToken($this->customer->getId(), 'paypal');
				
				if (!empty($paypal_customer_token['vault_customer_id'])) {
					$token_info['response_type'] = 'id_token';
					$token_info['target_customer_id'] = $paypal_customer_token['vault_customer_id'];
				}
			}
			
			if (($this->request->post['page_code'] == 'checkout') && $setting['fastlane']['status'] && ($setting['general']['country_code'] == 'US') && !$this->customer->isLogged()) {
				$token_info['response_type'] = 'client_token';
				$token_info['intent'] = 'sdk_init';
				$token_info['domains'][] = $this->request->server['HTTP_HOST'];
			}
							
			$result = $paypal->setAccessToken($token_info);
			
			if ($setting['general']['vault_status'] && !empty($result['id_token'])) {
				$data['id_token'] = $result['id_token'];
				
				$data['client_token'] = $paypal->getClientToken();
			}
			
			if (($this->request->post['page_code'] == 'checkout') && $setting['fastlane']['status'] && ($setting['general']['country_code'] == 'US') && !$this->customer->isLogged()) {
				$data['sdk_client_token'] = $paypal->getAccessToken();
				$data['client_metadata_id'] = $paypal->getToken();
				
				$this->session->data['paypal_client_metadata_id'] = $data['client_metadata_id'];
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
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
									
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}

			if (!empty($this->error['warning'])) {
				$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
			}
		}
				
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));		
	}
			
	public function createOrder() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		if (!empty($this->request->post['page_code']) && !empty($this->request->post['payment_type'])) {
			$page_code = $this->request->post['page_code'];
			$payment_type = $this->request->post['payment_type'];
			
			$payment_method = '';
					
			if ($payment_type == 'button') {
				$payment_method = 'paypal';
			}
					
			if ($payment_type == 'card') {
				$payment_method = 'card';
			}
			
			$errors = array();
		
			$data['paypal_order_id'] = '';
			$data['url'] = '';
			
			if (!empty($this->request->post['product'])) {
				$this->request->post['product'] = $this->unserialize($this->request->post['product']);
			}
			
			if (($page_code == 'product') && (!empty($this->request->post['product']['product_id']))) {
				$product = $this->request->post['product'];
				$product_id = (int)$product['product_id'];
		
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if (isset($product['quantity'])) {
						$quantity = (int)$product['quantity'];
					} else {
						$quantity = 1;
					}

					if (isset($product['option'])) {
						$option = array_filter($product['option']);
					} else {
						$option = array();
					}

					$product_options = $this->model_catalog_product->getProductOptions($product_id);

					foreach ($product_options as $product_option) {
						if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
							$errors[] = sprintf($this->language->get('error_required'), $product_option['name']);
						}
					}
				
					if (isset($product['recurring_id'])) {
						$recurring_id = $product['recurring_id'];
					} else {
						$recurring_id = 0;
					}

					$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

					if ($recurrings) {
						$recurring_ids = array();

						foreach ($recurrings as $recurring) {
							$recurring_ids[] = $recurring['recurring_id'];
						}

						if (!in_array($recurring_id, $recurring_ids)) {
							$errors[] = $this->language->get('error_recurring_required');
						}
					}
					
					if (!$errors) {					
						if (!$this->model_extension_payment_paypal->hasProductInCart($product_id, $option, $recurring_id)) {
							$this->cart->add($product_id, $quantity, $option, $recurring_id);
						}
																
						// Unset all shipping and payment methods
						unset($this->session->data['shipping_method']);
						unset($this->session->data['shipping_methods']);
						unset($this->session->data['payment_method']);
						unset($this->session->data['payment_methods']);
					}					
				}
			}
						
			if ($page_code == 'checkout') {
				$this->load->model('checkout/order');
				
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				
				$shipping_info = array();

				if ($this->cart->hasShipping()) {
					$shipping_info['name']['full_name'] = $order_info['shipping_firstname'];
					$shipping_info['name']['full_name'] .= ($order_info['shipping_lastname'] ? (' ' . $order_info['shipping_lastname']) : '');			
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
				}
			}
		
			if (!$errors) {					
				$_config = new Config();
				$_config->load('paypal');
			
				$config_setting = $_config->get('paypal_setting');
		
				$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
		
				$client_id = $this->config->get('payment_paypal_client_id');
				$secret = $this->config->get('payment_paypal_secret');
				$merchant_id = $this->config->get('payment_paypal_merchant_id');
				$environment = $this->config->get('payment_paypal_environment');
				$partner_id = $setting['partner'][$environment]['partner_id'];
				$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
				$vault_status = $setting['general']['vault_status'];
				$transaction_method = $setting['general']['transaction_method'];		
										
				$currency_code = $this->session->data['currency'];
				$currency_value = $this->currency->getValue($this->session->data['currency']);
				
				if ((($payment_type == 'button') || ($payment_type == 'googlepay_button') || ($payment_type == 'applepay_button')) && empty($setting['currency'][$currency_code]['status'])) {
					$currency_code = $setting['general']['currency_code'];
					$currency_value = $setting['general']['currency_value'];
				}

				if (($payment_type == 'card') && empty($setting['currency'][$currency_code]['card_status'])) {
					$currency_code = $setting['general']['card_currency_code'];
					$currency_value = $setting['general']['card_currency_value'];
				}
				
				$decimal_place = $setting['currency'][$currency_code]['decimal_place'];
				
				require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
				$paypal_info = array(
					'partner_id' => $partner_id,
					'client_id' => $client_id,
					'secret' => $secret,
					'environment' => $environment,
					'partner_attribution_id' => $partner_attribution_id
				);
				
				if (isset($this->session->data['paypal_client_metadata_id'])) {
					$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
				}
		
				$paypal = new PayPal($paypal_info);
			
				$token_info = array(
					'grant_type' => 'client_credentials'
				);	
				
				$paypal->setAccessToken($token_info);
								
				$item_info = array();
			
				$item_total = 0;
				$tax_total = 0;
				
				foreach ($this->cart->getProducts() as $product) {
					$product_price = number_format($product['price'] * $currency_value, $decimal_place, '.', '');
				
					$product_info = array();
					
					$product_info['name'] = $product['name'];
					$product_info['quantity'] = $product['quantity'];
					$product_info['sku'] = $product['model'];
					$product_info['url'] = $this->url->link('product/product', 'product_id=' . $product['product_id'], true);
										
					$product_info['unit_amount'] = array(
						'currency_code' => $currency_code,
						'value' => $product_price
					);

					$item_info[] = $product_info;
				
					$item_total += $product_price * $product['quantity'];
				
					if ($product['tax_class_id']) {
						$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							$tax_total += ($tax_rate['amount'] * $product['quantity']);
						}
					}
				}
				
				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $voucher) {
						$voucher_info = array();
	
						$voucher_info['name'] = $voucher['description'];
						$voucher_info['quantity'] = 1;
						
						$voucher_info['unit_amount'] = array(
							'currency_code' => $currency_code,
							'value' => $voucher['amount']
						);
												
						$item_info[] = $voucher_info;
					
						$item_total += $voucher['amount'];
					}
				}
				
				$item_total = number_format($item_total, $decimal_place, '.', '');
				$tax_total = number_format($tax_total * $currency_value, $decimal_place, '.', '');
				$order_total = number_format($item_total + $tax_total, $decimal_place, '.', '');
				
				if ($page_code == 'checkout') {
					$discount_total = 0;
					$handling_total = 0;
					$shipping_total = 0;
		
					if (isset($this->session->data['shipping_method'])) {
						$shipping_total = $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'], true);
						$shipping_total = number_format($shipping_total * $currency_value, $decimal_place, '.', '');
					}
		
					$order_total = number_format($order_info['total'] * $currency_value, $decimal_place, '.', '');
		
					$rebate = number_format($item_total + $tax_total + $shipping_total - $order_total, $decimal_place, '.', '');
		
					if ($rebate > 0) {
						$discount_total = $rebate;
					} elseif ($rebate < 0) {
						$handling_total = -$rebate;
					}
				} 
						
				$amount_info = array();
				
				$amount_info['currency_code'] = $currency_code;
				$amount_info['value'] = $order_total;
								
				$amount_info['breakdown']['item_total'] = array(
					'currency_code' => $currency_code,
					'value' => $item_total
				);
				
				$amount_info['breakdown']['tax_total'] = array(
					'currency_code' => $currency_code,
					'value' => $tax_total
				);
				
				if ($page_code == 'checkout') {
					$amount_info['breakdown']['shipping'] = array(
						'currency_code' => $currency_code,
						'value' => $shipping_total
					);
					
					$amount_info['breakdown']['handling'] = array(
						'currency_code' => $currency_code,
						'value' => $handling_total
					);
					
					$amount_info['breakdown']['discount'] = array(
						'currency_code' => $currency_code,
						'value' => $discount_total
					);
				}
				
				$paypal_order_info = array();
				
				$paypal_order_info['intent'] = strtoupper($transaction_method);
				$paypal_order_info['purchase_units'][0]['reference_id'] = 'default';
				$paypal_order_info['purchase_units'][0]['items'] = $item_info;
				$paypal_order_info['purchase_units'][0]['amount'] = $amount_info;
				
				if ($page_code == 'checkout') {
					$paypal_order_info['purchase_units'][0]['description'] = 'Your order ' . $order_info['order_id'];
					
					if ($setting['general']['invoice_id_tokenization_status']) {
						$paypal_order_info['purchase_units'][0]['invoice_id'] = $order_info['order_id'] . '_' . date('Ymd_His');
					} else {
						$paypal_order_info['purchase_units'][0]['invoice_id'] = $order_info['order_id'];
					}
					
					if ($this->cart->hasShipping()) {
						$paypal_order_info['purchase_units'][0]['shipping'] = $shipping_info;
					}
				}
				
				if ($this->cart->hasShipping()) {			
					$shipping_preference = 'GET_FROM_FILE';
				} else {
					$shipping_preference = 'NO_SHIPPING';
				}
	
				$paypal_order_info['application_context']['shipping_preference'] = $shipping_preference;
				
				if ($setting['general']['vault_status'] && ($this->customer->isLogged() || $this->cart->hasRecurringProducts())) {
					if ($payment_method == 'paypal') {
						$paypal_customer_token = array();
						
						if ($this->customer->isLogged()) {
							$paypal_customer_token = $this->model_extension_payment_paypal->getPayPalCustomerMainToken($this->customer->getId(), $payment_method);
						}
				
						if (empty($paypal_customer_token['vault_id'])) {
							$paypal_order_info['payment_source'][$payment_method]['attributes']['vault'] = array(
								'permit_multiple_payment_tokens' => 'false',
								'store_in_vault' => 'ON_SUCCESS',
								'usage_type' => 'MERCHANT',
								'customer_type' => 'CONSUMER'
							);
						}
					}
					
					if ($payment_method == 'card') {
						if (isset($this->request->post['index'])) {
							$card_token_index = $this->request->post['index'];
							
							$card_customer_tokens = $this->model_extension_payment_paypal->getPayPalCustomerTokens($this->customer->getId(), $payment_method);
							
							if (!empty($card_customer_tokens[$card_token_index]['vault_id'])) {
								$paypal_order_info['payment_source'][$payment_method]['vault_id'] = $card_customer_tokens[$card_token_index]['vault_id'];							
								$paypal_order_info['payment_source'][$payment_method]['stored_credential']['payment_initiator'] = 'CUSTOMER';
								$paypal_order_info['payment_source'][$payment_method]['stored_credential']['payment_type'] = 'ONE_TIME';
								$paypal_order_info['payment_source'][$payment_method]['stored_credential']['usage'] = 'SUBSEQUENT';
							}
						} else {
							if (!empty($this->request->post['card_save']) || $this->cart->hasRecurringProducts()) {
								$paypal_order_info['payment_source'][$payment_method]['attributes']['vault']['store_in_vault'] = 'ON_SUCCESS';								
								$paypal_order_info['payment_source'][$payment_method]['stored_credential']['payment_initiator'] = 'CUSTOMER';
								$paypal_order_info['payment_source'][$payment_method]['stored_credential']['usage'] = 'FIRST';
								
								if ($this->cart->hasRecurringProducts()) {
									$paypal_order_info['payment_source'][$payment_method]['stored_credential']['payment_type'] = 'UNSCHEDULED';
								} else {
									$paypal_order_info['payment_source'][$payment_method]['stored_credential']['payment_type'] = 'ONE_TIME';
								}
							}
						}
					}
				}
				
				if ($payment_method) {
					$paypal_order_info['payment_source'][$payment_method]['attributes']['verification']['method'] = strtoupper($setting['card']['secure_method']);
					$paypal_order_info['payment_source'][$payment_method]['experience_context']['return_url'] = $this->url->link('extension/payment/paypal', 'callback_token=' . $setting['general']['callback_token'], true);
					$paypal_order_info['payment_source'][$payment_method]['experience_context']['cancel_url'] = $this->url->link('checkout/checkout', '', true);
				}
				
				$result = $paypal->createOrder($paypal_order_info);
				
				if ($paypal->hasErrors()) {
					$error_messages = array();
				
					$errors = $paypal->getErrors();
								
					foreach ($errors as $error) {
						if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
							$error['message'] = $this->language->get('error_timeout');
						}
				
						if (isset($error['details'][0]['description'])) {
							$error_messages[] = $error['details'][0]['description'];
						} elseif (isset($error['message'])) {
							$error_messages[] = $error['message'];
						}
					
						$this->model_extension_payment_paypal->log($error, $error['message']);
					}
				
					$this->error['warning'] = implode(' ', $error_messages);
				}
		
				if (!empty($this->error['warning'])) {
					$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
						
				if (isset($result['id']) && isset($result['status']) && !$this->error) {
					$this->model_extension_payment_paypal->log($result, 'Create Order');
								
					if ($result['status'] == 'VOIDED') {
						$this->error['warning'] = sprintf($this->language->get('error_order_voided'), $this->url->link('information/contact', '', true));
					}
								
					if (($result['status'] == 'COMPLETED') && empty($paypal_order_info['payment_source']['card']['vault_id'])) {
						$this->error['warning'] = sprintf($this->language->get('error_order_completed'), $this->url->link('information/contact', '', true));
					}
					
					if (($result['status'] == 'COMPLETED') && !empty($paypal_order_info['payment_source']['card']['vault_id'])) {
						$paypal_order_id = $result['id'];
						
						$vault_id = $card_customer_tokens[$card_token_index]['vault_id'];
						$vault_customer_id = $card_customer_tokens[$card_token_index]['vault_customer_id'];
						$card_type = $card_customer_tokens[$card_token_index]['card_type'];
						$card_nice_type = $card_customer_tokens[$card_token_index]['card_nice_type'];
						$card_last_digits = $card_customer_tokens[$card_token_index]['card_last_digits'];
						$card_expiry = $card_customer_tokens[$card_token_index]['card_expiry'];
					
						$paypal_order_info = $paypal->getOrder($paypal_order_id);
												
						if ($paypal->hasErrors()) {
							$error_messages = array();
				
							$errors = $paypal->getErrors();
								
							foreach ($errors as $error) {
								if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
									$error['message'] = $this->language->get('error_timeout');
								}
					
								if (isset($error['details'][0]['description'])) {
									$error_messages[] = $error['details'][0]['description'];
								} elseif (isset($error['message'])) {
									$error_messages[] = $error['message'];
								}
					
								$this->model_extension_payment_paypal->log($error, $error['message']);
							}
		
							$this->error['warning'] = implode(' ', $error_messages);
						}
							
						if (isset($paypal_order_info['payment_source']['card']) && !$this->error) {
							$this->model_extension_payment_paypal->log($paypal_order_info['payment_source']['card'], 'Card');
						
							$liability_shift = (isset($paypal_order_info['payment_source']['card']['authentication_result']['liability_shift']) ? $paypal_order_info['payment_source']['card']['authentication_result']['liability_shift'] : '');
							$enrollment_status = (isset($paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status']) ? $paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status'] : '');
							$authentication_status = (isset($paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status']) ? $paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status'] : '');
								
							if ($enrollment_status == 'Y') {
								if (($authentication_status == 'N') && !$setting['card']['secure_scenario']['failed_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['failed_authentication']['error']);
								}
						
								if (($authentication_status == 'R') && !$setting['card']['secure_scenario']['rejected_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['rejected_authentication']['error']);
								}
						
								if (($authentication_status == 'A') && !$setting['card']['secure_scenario']['attempted_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['attempted_authentication']['error']);
								}
						
								if (($authentication_status == 'U') && !$setting['card']['secure_scenario']['unable_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['unable_authentication']['error']);
								}
						
								if (($authentication_status == 'C') && !$setting['card']['secure_scenario']['challenge_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['challenge_authentication']['error']);
								}
							}
					
							if (($enrollment_status == 'N') && !$setting['card']['secure_scenario']['card_ineligible']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['card_ineligible']['error']);
							}
					
							if (($enrollment_status == 'U') && !$setting['card']['secure_scenario']['system_unavailable']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['system_unavailable']['error']);
							}
					
							if (($enrollment_status == 'B') && !$setting['card']['secure_scenario']['system_bypassed']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['system_bypassed']['error']);
							}
						}
		
						if (!empty($this->error['warning'])) {
							$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
						}
				
						if (!$this->error) {				
							$this->load->model('checkout/order');
				
							$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
						
							if ($transaction_method == 'authorize') {
								$this->model_extension_payment_paypal->log($result, 'Authorize Order');
					
								if (isset($result['purchase_units'][0]['payments']['authorizations'][0]['status']) && isset($result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'])) {
									$authorization_id = $result['purchase_units'][0]['payments']['authorizations'][0]['id'];
									$authorization_status = $result['purchase_units'][0]['payments']['authorizations'][0]['status'];
									$seller_protection_status = $result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'];							
									$order_status_id = 0;
									$transaction_status = '';
									$payment_method = 'card';
								
									if (!$this->cart->hasShipping()) {
										$seller_protection_status = 'NOT_ELIGIBLE';
									}
								
									if ($authorization_status == 'CREATED') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'created';
									}

									if ($authorization_status == 'CAPTURED') {
										$this->error['warning'] = sprintf($this->language->get('error_authorization_captured'), $this->url->link('information/contact', '', true));
									}
						
									if ($authorization_status == 'DENIED') {
										$transaction_status = 'denied';
							
										$this->error['warning'] = $this->language->get('error_authorization_denied');
									}
						
									if ($authorization_status == 'EXPIRED') {
										$this->error['warning'] = sprintf($this->language->get('error_authorization_expired'), $this->url->link('information/contact', '', true));
									}
						
									if ($authorization_status == 'PENDING') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'pending';
									}
														
									if (($authorization_status == 'CREATED') || ($authorization_status == 'DENIED') || ($authorization_status == 'PENDING')) {
										$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
										$paypal_order_data = array(
											'order_id' => $this->session->data['order_id'],
											'paypal_order_id' => $paypal_order_id,
											'transaction_id' => $authorization_id,
											'transaction_status' => $transaction_status,
											'payment_method' => $payment_method,
											'vault_id' => $vault_id,
											'vault_customer_id' => $vault_customer_id,
											'card_type' => $card_type,
											'card_nice_type' => $card_nice_type,
											'card_last_digits' => $card_last_digits,
											'card_expiry' => $card_expiry,
											'total' => $order_info['total'],
											'currency_code' => $order_info['currency_code'],
											'environment' => $environment
										);

										$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
										if ($vault_id && $this->customer->isLogged()) {
											$customer_id = $this->customer->getId();
										
											$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
											if (!$paypal_customer_token_info) {
												$paypal_customer_token_data = array(
													'customer_id' => $customer_id,
													'payment_method' => $payment_method,
													'vault_id' => $vault_id,
													'vault_customer_id' => $vault_customer_id,
													'card_type' => $card_type,
													'card_nice_type' => $card_nice_type,
													'card_last_digits' => $card_last_digits,
													'card_expiry' => $card_expiry
												);
					
												$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
											}
										
											$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
										}
									}
									
									if ($order_status_id) {
										$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
										$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
									}
								
									if (($authorization_status == 'CREATED') || ($authorization_status == 'PENDING')) {
										$recurring_products = $this->cart->getRecurringProducts();
					
										foreach ($recurring_products as $recurring_product) {
											$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_info, $paypal_order_data);
										} 
									}
													
									if (($authorization_status == 'CREATED') || ($authorization_status == 'PARTIALLY_CAPTURED') || ($authorization_status == 'PARTIALLY_CREATED') || ($authorization_status == 'PENDING')) {
										$data['url'] = $this->url->link('checkout/success', '', true);
									}
								}
							} else {
								$this->model_extension_payment_paypal->log($result, 'Capture Order');
					
								if (isset($result['purchase_units'][0]['payments']['captures'][0]['status']) && isset($result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'])) {
									$capture_id = $result['purchase_units'][0]['payments']['captures'][0]['id'];
									$capture_status = $result['purchase_units'][0]['payments']['captures'][0]['status'];
									$seller_protection_status = $result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'];
									
									$order_status_id = 0;
									$transaction_status = '';
									$payment_method = 'card';
																	
									if (!$this->cart->hasShipping()) {
										$seller_protection_status = 'NOT_ELIGIBLE';
									}
																						
									if ($capture_status == 'COMPLETED') {
										$order_status_id = $setting['order_status']['completed']['id'];
										$transaction_status = 'completed';
									}
						
									if ($capture_status == 'DECLINED') {
										$transaction_status = 'denied';
							
										$this->error['warning'] = $this->language->get('error_capture_declined');
									}
						
									if ($capture_status == 'FAILED') {
										$this->error['warning'] = sprintf($this->language->get('error_capture_failed'), $this->url->link('information/contact', '', true));
									}
						
									if ($capture_status == 'PENDING') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'pending';
									}
														
									if (($capture_status == 'COMPLETED') || ($capture_status == 'DECLINED') || ($capture_status == 'PENDING')) {
										$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
										$paypal_order_data = array(
											'order_id' => $this->session->data['order_id'],
											'paypal_order_id' => $paypal_order_id,
											'transaction_id' => $capture_id,
											'transaction_status' => $transaction_status,
											'payment_method' => $payment_method,
											'vault_id' => $vault_id,
											'vault_customer_id' => $vault_customer_id,
											'card_type' => $card_type,
											'card_nice_type' => $card_nice_type,
											'card_last_digits' => $card_last_digits,
											'card_expiry' => $card_expiry,
											'total' => $order_info['total'],
											'currency_code' => $order_info['currency_code'],
											'environment' => $environment
										);

										$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
										if ($vault_id && $this->customer->isLogged()) {
											$customer_id = $this->customer->getId();
										
											$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
											if (!$paypal_customer_token_info) {
												$paypal_customer_token_data = array(
													'customer_id' => $customer_id,
													'payment_method' => $payment_method,
													'vault_id' => $vault_id,
													'vault_customer_id' => $vault_customer_id,
													'card_type' => $card_type,
													'card_nice_type' => $card_nice_type,
													'card_last_digits' => $card_last_digits,
													'card_expiry' => $card_expiry
												);
					
												$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
											}
										
											$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
										}
									}
									
									if ($order_status_id) {
										$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
										$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
									}
								
									if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
										$recurring_products = $this->cart->getRecurringProducts();
					
										foreach ($recurring_products as $recurring_product) {
											$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_info, $paypal_order_data);
										} 
									}
						
									if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
										$data['url'] = $this->url->link('checkout/success', '', true);
									}
								}
							}
						}
					}
					
					if (($result['status'] == 'PAYER_ACTION_REQUIRED') && !empty($paypal_order_info['payment_source']['card']['vault_id'])) {
						foreach ($result['links'] as $link) {
							if ($link['rel'] == 'payer-action') {
								$data['url'] = $link['href'];
								
								$this->session->data['paypal_order_id'] = $result['id'];
								$this->session->data['paypal_card_token_index'] = $card_token_index;
							}
						}
					}
			
					if (!$this->error) {
						$data['paypal_order_id'] = $result['id'];
					}
				}
			} else {
				$this->error['warning'] = implode(' ', $errors);
			}
		}
					
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
			
	public function approveOrder() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		if (!empty($this->request->post['page_code']) && !empty($this->request->post['payment_type'])) {
			$page_code = $this->request->post['page_code'];
			$payment_type = $this->request->post['payment_type'];
			
			if ($page_code != 'checkout') {
				if (isset($this->request->post['paypal_order_id'])) {
					$this->session->data['paypal_order_id'] = $this->request->post['paypal_order_id'];
				} else {	
					$data['url'] = $this->url->link('checkout/cart', '', true);
			
					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode($data));
				}
		
				// check checkout can continue due to stock checks or vouchers
				if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
					$data['url'] = $this->url->link('checkout/cart', '', true);
			
					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode($data));
				}

				// if user not logged in check that the guest checkout is allowed
				if (!$this->customer->isLogged() && (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload() || $this->cart->hasRecurringProducts())) {
					$data['url'] = $this->url->link('checkout/cart', '', true);
			
					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode($data));
				}
			}
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
				
			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$vault_status = $setting['general']['vault_status'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
			
			if (isset($this->session->data['paypal_client_metadata_id'])) {
				$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
			}
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);
			
			if ($page_code != 'checkout') {
				$paypal_order_id = $this->session->data['paypal_order_id'];
				
				$paypal_order_info = $paypal->getOrder($paypal_order_id);
				
				if ($paypal->hasErrors()) {
					$error_messages = array();
				
					$errors = $paypal->getErrors();
								
					foreach ($errors as $error) {
						if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
							$error['message'] = $this->language->get('error_timeout');
						}
					
						if (isset($error['details'][0]['description'])) {
							$error_messages[] = $error['details'][0]['description'];
						} elseif (isset($error['message'])) {
							$error_messages[] = $error['message'];
						}
					
						$this->model_extension_payment_paypal->log($error, $error['message']);
					}
				
					$this->error['warning'] = implode(' ', $error_messages);
				}
		
				if (!empty($this->error['warning'])) {
					$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
		
				if ($paypal_order_info && !$this->error) {
					$this->load->model('account/customer');
					$this->load->model('account/address');
			
					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
								
					if ($this->customer->isLogged()) {
						$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

						$this->session->data['guest']['customer_id'] = $this->customer->getId();
						$this->session->data['guest']['customer_group_id'] = $customer_info['customer_group_id'];
						$this->session->data['guest']['firstname'] = $customer_info['firstname'];
						$this->session->data['guest']['lastname'] = $customer_info['lastname'];
						$this->session->data['guest']['email'] = $customer_info['email'];
						$this->session->data['guest']['telephone'] = $customer_info['telephone'];
						$this->session->data['guest']['custom_field'] = json_decode($customer_info['custom_field'], true);
					} else {
						$this->session->data['guest']['customer_id'] = 0;
						$this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
						$this->session->data['guest']['firstname'] = (isset($paypal_order_info['payer']['name']['given_name']) ? $paypal_order_info['payer']['name']['given_name'] : '');
						$this->session->data['guest']['lastname'] = (isset($paypal_order_info['payer']['name']['surname']) ? $paypal_order_info['payer']['name']['surname'] : '');
						$this->session->data['guest']['email'] = (isset($paypal_order_info['payer']['email_address']) ? $paypal_order_info['payer']['email_address'] : '');
						$this->session->data['guest']['telephone'] = (isset($paypal_order_info['payer']['phone']['phone_number']['national_number']) ? $paypal_order_info['payer']['phone']['phone_number']['national_number'] : '');
						$this->session->data['guest']['custom_field'] = array();
					}
								
					if ($this->customer->isLogged() && $this->customer->getAddressId()) {
						$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					} else {
						$this->session->data['payment_address']['firstname'] = (isset($paypal_order_info['payer']['name']['given_name']) ? $paypal_order_info['payer']['name']['given_name'] : '');
						$this->session->data['payment_address']['lastname'] = (isset($paypal_order_info['payer']['name']['surname']) ? $paypal_order_info['payer']['name']['surname'] : '');
						$this->session->data['payment_address']['company'] = '';
						$this->session->data['payment_address']['address_1'] = '';
						$this->session->data['payment_address']['address_2'] = '';
						$this->session->data['payment_address']['city'] = '';
						$this->session->data['payment_address']['postcode'] = '';
						$this->session->data['payment_address']['country'] = '';
						$this->session->data['payment_address']['country_id'] = 0;
						$this->session->data['payment_address']['address_format'] = '';
						$this->session->data['payment_address']['zone'] = '';
						$this->session->data['payment_address']['zone_id'] = 0;
						$this->session->data['payment_address']['custom_field'] = array();
			
						if (isset($paypal_order_info['payer']['address']['country_code'])) {
							$country_info = $this->model_extension_payment_paypal->getCountryByCode($paypal_order_info['payer']['address']['country_code']);
			
							if ($country_info) {
								$this->session->data['payment_address']['country'] = $country_info['name'];
								$this->session->data['payment_address']['country_id'] = $country_info['country_id'];
								$this->session->data['payment_address']['address_format'] = $country_info['address_format'];
							}
						}
					}
				
					if ($this->cart->hasShipping()) {
						if ($this->customer->isLogged() && $this->customer->getAddressId()) {
							$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
						} else {
							if (isset($paypal_order_info['purchase_units'][0]['shipping']['name']['full_name'])) {
								$shipping_name = explode(' ', $paypal_order_info['purchase_units'][0]['shipping']['name']['full_name']);
								$shipping_firstname = $shipping_name[0];
								unset($shipping_name[0]);
								$shipping_lastname = implode(' ', $shipping_name);
							}
					
							$this->session->data['shipping_address']['firstname'] = (isset($shipping_firstname) ? $shipping_firstname : '');
							$this->session->data['shipping_address']['lastname'] = (isset($shipping_lastname) ? $shipping_lastname : '');
							$this->session->data['shipping_address']['company'] = '';
							$this->session->data['shipping_address']['address_1'] = (isset($paypal_order_info['purchase_units'][0]['shipping']['address']['address_line_1']) ? $paypal_order_info['purchase_units'][0]['shipping']['address']['address_line_1'] : '');
							$this->session->data['shipping_address']['address_2'] = (isset($paypal_order_info['purchase_units'][0]['shipping']['address']['address_line_2']) ? $paypal_order_info['purchase_units'][0]['shipping']['address']['address_line_2'] : '');
							$this->session->data['shipping_address']['city'] = (isset($paypal_order_info['purchase_units'][0]['shipping']['address']['admin_area_2']) ? $paypal_order_info['purchase_units'][0]['shipping']['address']['admin_area_2'] : '');
							$this->session->data['shipping_address']['postcode'] = (isset($paypal_order_info['purchase_units'][0]['shipping']['address']['postal_code']) ? $paypal_order_info['purchase_units'][0]['shipping']['address']['postal_code'] : '');
							$this->session->data['shipping_address']['country'] = '';
							$this->session->data['shipping_address']['country_id'] = 0;
							$this->session->data['shipping_address']['address_format'] = '';
							$this->session->data['shipping_address']['zone'] = '';
							$this->session->data['shipping_address']['zone_id'] = 0;
							$this->session->data['shipping_address']['custom_field'] = array();
									
							if (isset($paypal_order_info['purchase_units'][0]['shipping']['address']['country_code'])) {
								$country_info = $this->model_extension_payment_paypal->getCountryByCode($paypal_order_info['purchase_units'][0]['shipping']['address']['country_code']);
			
								if ($country_info) {
									$this->session->data['shipping_address']['country_id'] = $country_info['country_id'];
									$this->session->data['shipping_address']['country'] = $country_info['name'];
									$this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
													
									if (isset($paypal_order_info['purchase_units'][0]['shipping']['address']['admin_area_1'])) {
										$zone_info = $this->model_extension_payment_paypal->getZoneByCode($country_info['country_id'], $paypal_order_info['purchase_units'][0]['shipping']['address']['admin_area_1']);
			
										if ($zone_info) {
											$this->session->data['shipping_address']['zone_id'] = $zone_info['zone_id'];
											$this->session->data['shipping_address']['zone'] = $zone_info['name'];
										}
									}
								}
							}
						}
					}
									
					if (($payment_type == 'googlepay_button') && !empty($this->request->post['payment_data'])) {
						$payment_data = json_decode(htmlspecialchars_decode($this->request->post['payment_data']), true);
						
						if (isset($payment_data['paymentMethodData']['info']['billingAddress']['name'])) {
							$payment_name = explode(' ', $payment_data['paymentMethodData']['info']['billingAddress']['name']);
							$payment_firstname = $payment_name[0];
							unset($payment_name[0]);
							$payment_lastname = implode(' ', $payment_name);
						}
							
						$this->session->data['guest']['firstname'] = (isset($payment_firstname) ? $payment_firstname : '');
						$this->session->data['guest']['lastname'] = (isset($payment_lastname) ? $payment_lastname : '');
						$this->session->data['guest']['email'] = (isset($payment_data['email']) ? $payment_data['email'] : '');
						$this->session->data['guest']['telephone'] = (isset($payment_data['paymentMethodData']['info']['billingAddress']['phoneNumber']) ? $payment_data['paymentMethodData']['info']['billingAddress']['phoneNumber'] : '');
						
						$this->session->data['payment_address']['firstname'] = (isset($shipping_firstname) ? $shipping_firstname : '');
						$this->session->data['payment_address']['lastname'] = (isset($shipping_lastname) ? $shipping_lastname : '');
						$this->session->data['payment_address']['address_1'] = (isset($payment_data['paymentMethodData']['info']['billingAddress']['address1']) ? $payment_data['paymentMethodData']['info']['billingAddress']['address1'] : '');
						$this->session->data['payment_address']['city'] = (isset($payment_data['paymentMethodData']['info']['billingAddress']['locality']) ? $payment_data['paymentMethodData']['info']['billingAddress']['locality'] : '');
						$this->session->data['payment_address']['postcode'] = (isset($payment_data['paymentMethodData']['info']['billingAddress']['postalCode']) ? $payment_data['paymentMethodData']['info']['billingAddress']['postalCode'] : '');
							
						if (isset($payment_data['paymentMethodData']['info']['billingAddress']['countryCode'])) {
							$country_info = $this->model_extension_payment_paypal->getCountryByCode($payment_data['paymentMethodData']['info']['billingAddress']['countryCode']);
			
							if ($country_info) {
								$this->session->data['payment_address']['country'] = $country_info['name'];
								$this->session->data['payment_address']['country_id'] = $country_info['country_id'];
							}
						}
							
						if ($this->cart->hasShipping()) {
							if (isset($payment_data['shippingAddress']['name'])) {
								$shipping_name = explode(' ', $payment_data['shippingAddress']['name']);
								$shipping_firstname = $shipping_name[0];
								unset($shipping_name[0]);
								$shipping_lastname = implode(' ', $shipping_name);
							}
						
							$this->session->data['shipping_address']['firstname'] = (isset($shipping_firstname) ? $shipping_firstname : '');
							$this->session->data['shipping_address']['lastname'] = (isset($shipping_lastname) ? $shipping_lastname : '');
							$this->session->data['shipping_address']['address_1'] = (isset($payment_data['shippingAddress']['address1']) ? $payment_data['shippingAddress']['address1'] : '');
							$this->session->data['shipping_address']['city'] = (isset($payment_data['shippingAddress']['locality']) ? $payment_data['shippingAddress']['locality'] : '');
							$this->session->data['shipping_address']['postcode'] = (isset($payment_data['shippingAddress']['postalCode']) ? $payment_data['shippingAddress']['postalCode'] : '');
							
							if (isset($payment_data['shippingAddress']['countryCode'])) {
								$country_info = $this->model_extension_payment_paypal->getCountryByCode($payment_data['shippingAddress']['countryCode']);
			
								if ($country_info) {
									$this->session->data['shipping_address']['country'] = $country_info['name'];
									$this->session->data['shipping_address']['country_id'] = $country_info['country_id'];
								}
							}
						}
					}
					
					if (($payment_type == 'applepay_button') && !empty($this->request->post['payment_data'])) {
						$payment_data = json_decode(htmlspecialchars_decode($this->request->post['payment_data']), true);
					
						$this->session->data['guest']['firstname'] = (isset($payment_data['billingContact']['givenName']) ? $payment_data['billingContact']['givenName'] : '');
						$this->session->data['guest']['lastname'] = (isset($payment_data['billingContact']['familyName']) ? $payment_data['billingContact']['familyName'] : '');
						$this->session->data['guest']['email'] = (isset($payment_data['shippingContact']['emailAddress']) ? $payment_data['shippingContact']['emailAddress'] : '');
						$this->session->data['guest']['telephone'] = (isset($payment_data['shippingContact']['phoneNumber']) ? $payment_data['shippingContact']['phoneNumber'] : '');
						
						$this->session->data['payment_address']['firstname'] = (isset($payment_data['billingContact']['givenName']) ? $payment_data['billingContact']['givenName'] : '');
						$this->session->data['payment_address']['lastname'] = (isset($payment_data['billingContact']['familyName']) ? $payment_data['billingContact']['familyName'] : '');
						$this->session->data['payment_address']['address_1'] = (isset($payment_data['billingContact']['addressLines']) ? implode(', ', $payment_data['billingContact']['addressLines']) : '');
						$this->session->data['payment_address']['city'] = (isset($payment_data['billingContact']['locality']) ? $payment_data['billingContact']['locality'] : '');
						$this->session->data['payment_address']['postcode'] = (isset($payment_data['billingContact']['postalCode']) ? $payment_data['billingContact']['postalCode'] : '');
							
						if (isset($payment_data['billingContact']['countryCode'])) {
							$country_info = $this->model_extension_payment_paypal->getCountryByCode($payment_data['billingContact']['countryCode']);
			
							if ($country_info) {
								$this->session->data['payment_address']['country'] = $country_info['name'];
								$this->session->data['payment_address']['country_id'] = $country_info['country_id'];
							}
						}
							
						if ($this->cart->hasShipping()) {						
							$this->session->data['shipping_address']['firstname'] = (isset($payment_data['shippingContact']['givenName']) ? $payment_data['shippingContact']['givenName'] : '');
							$this->session->data['shipping_address']['lastname'] = (isset($payment_data['shippingContact']['familyName']) ? $payment_data['shippingContact']['familyName'] : '');
							$this->session->data['shipping_address']['address_1'] = (isset($payment_data['shippingContact']['addressLines']) ? implode(', ', $payment_data['shippingContact']['addressLines']) : '');
							$this->session->data['shipping_address']['city'] = (isset($payment_data['shippingContact']['locality']) ? $payment_data['shippingContact']['locality'] : '');
							$this->session->data['shipping_address']['postcode'] = (isset($payment_data['shippingContact']['postalCode']) ? $payment_data['shippingContact']['postalCode'] : '');
							
							if (isset($payment_data['shippingContact']['countryCode'])) {
								$country_info = $this->model_extension_payment_paypal->getCountryByCode($payment_data['shippingContact']['countryCode']);
			
								if ($country_info) {
									$this->session->data['shipping_address']['country'] = $country_info['name'];
									$this->session->data['shipping_address']['country_id'] = $country_info['country_id'];
								}
							}
						}
					}

					if ($payment_type == 'button') {
						$this->session->data['payment_method'] = array(
							'code'       => 'paypal',
							'title'      => $this->language->get('text_paypal_title'),
							'terms'      => '',
							'sort_order' => $this->config->get('payment_paypal_sort_order')
						); 
					}
					
					if ($payment_type == 'googlepay_button') {
						$this->session->data['payment_method'] = array(
							'code'       => 'paypal_googlepay',
							'title'      => $this->language->get('text_paypal_googlepay_title'),
							'terms'      => '',
							'sort_order' => $this->config->get('payment_paypal_sort_order')
						); 
					}
					
					if ($payment_type == 'applepay_button') {
						$this->session->data['payment_method'] = array(
							'code'       => 'paypal_applepay',
							'title'      => $this->language->get('text_paypal_applepay_title'),
							'terms'      => '',
							'sort_order' => $this->config->get('payment_paypal_sort_order')
						); 
					}
										
					$data['url'] = $this->url->link('extension/payment/paypal/confirmOrder', '', true);			
				}
			} else {
				if (!empty($this->request->post['paypal_order_id'])) {
					$paypal_order_id = $this->request->post['paypal_order_id'];
				}
		
				if (($payment_type == 'card') && !empty($paypal_order_id)) {
					$paypal_order_info = $paypal->getOrder($paypal_order_id);
					
					if ($paypal->hasErrors()) {
						$error_messages = array();
				
						$errors = $paypal->getErrors();
								
						foreach ($errors as $error) {
							if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
								$error['message'] = $this->language->get('error_timeout');
							}
					
							if (isset($error['details'][0]['description'])) {
								$error_messages[] = $error['details'][0]['description'];
							} elseif (isset($error['message'])) {
								$error_messages[] = $error['message'];
							}
					
							$this->model_extension_payment_paypal->log($error, $error['message']);
						}
		
						$this->error['warning'] = implode(' ', $error_messages);
					}
							
					if (isset($paypal_order_info['payment_source']['card']) && !$this->error) {
						$this->model_extension_payment_paypal->log($paypal_order_info['payment_source']['card'], 'Card');
						
						$liability_shift = (isset($paypal_order_info['payment_source']['card']['authentication_result']['liability_shift']) ? $paypal_order_info['payment_source']['card']['authentication_result']['liability_shift'] : '');
						$enrollment_status = (isset($paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status']) ? $paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status'] : '');
						$authentication_status = (isset($paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status']) ? $paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status'] : '');
								
						if ($enrollment_status == 'Y') {
							if (($authentication_status == 'N') && !$setting['card']['secure_scenario']['failed_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['failed_authentication']['error']);
							}
						
							if (($authentication_status == 'R') && !$setting['card']['secure_scenario']['rejected_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['rejected_authentication']['error']);
							}
						
							if (($authentication_status == 'A') && !$setting['card']['secure_scenario']['attempted_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['attempted_authentication']['error']);
							}
						
							if (($authentication_status == 'U') && !$setting['card']['secure_scenario']['unable_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['unable_authentication']['error']);
							}
						
							if (($authentication_status == 'C') && !$setting['card']['secure_scenario']['challenge_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['challenge_authentication']['error']);
							}
						}
					
						if (($enrollment_status == 'N') && !$setting['card']['secure_scenario']['card_ineligible']) {
							$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['card_ineligible']['error']);
						}
					
						if (($enrollment_status == 'U') && !$setting['card']['secure_scenario']['system_unavailable']) {
							$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['system_unavailable']['error']);
						}
					
						if (($enrollment_status == 'B') && !$setting['card']['secure_scenario']['system_bypassed']) {
							$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['system_bypassed']['error']);
						}
					}
		
					if (!empty($this->error['warning'])) {
						$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
					}
				}

				if (!empty($paypal_order_id) && !$this->error) {				
					if ($transaction_method == 'authorize') {
						$result = $paypal->setOrderAuthorize($paypal_order_id);
					} else {
						$result = $paypal->setOrderCapture($paypal_order_id);
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
							} elseif (isset($error['message'])) {
								$error_messages[] = $error['message'];
							}
					
							$this->model_extension_payment_paypal->log($error, $error['message']);
						}
				
						$this->error['warning'] = implode(' ', $error_messages);
					}
				
					if (!empty($this->error['warning'])) {
						$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
					}
			
					if (!$this->error) {	
						$this->load->model('checkout/order');
				
						$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
						
						if ($transaction_method == 'authorize') {
							$this->model_extension_payment_paypal->log($result, 'Authorize Order');
					
							if (isset($result['purchase_units'][0]['payments']['authorizations'][0]['status']) && isset($result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'])) {
								$authorization_id = $result['purchase_units'][0]['payments']['authorizations'][0]['id'];
								$authorization_status = $result['purchase_units'][0]['payments']['authorizations'][0]['status'];
								$seller_protection_status = $result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'];							
								$order_status_id = 0;
								$transaction_status = '';
								$payment_method = '';
								$vault_id = '';
								$vault_customer_id = '';
								$card_type = (!empty($this->request->post['card_type']) ? $this->request->post['card_type'] : '');
								$card_nice_type = (!empty($this->request->post['card_nice_type']) ? $this->request->post['card_nice_type'] : '');
								$card_last_digits = '';
								$card_expiry = '';
																
								if (!$this->cart->hasShipping()) {
									$seller_protection_status = 'NOT_ELIGIBLE';
								}
								
								foreach ($result['payment_source'] as $payment_source_key => $payment_source) {
									$payment_method = $payment_source_key;
									$vault_id = (isset($payment_source['attributes']['vault']['id']) ? $payment_source['attributes']['vault']['id'] : '');
									$vault_customer_id = (isset($payment_source['attributes']['vault']['customer']['id']) ? $payment_source['attributes']['vault']['customer']['id'] : '');
									$card_last_digits = (isset($payment_source['last_digits']) ? $payment_source['last_digits'] : '');
									$card_expiry = (isset($payment_source['expiry']) ? $payment_source['expiry'] : '');
									
									break;
								}

								if ($authorization_status == 'CREATED') {
									$order_status_id = $setting['order_status']['pending']['id'];
									$transaction_status = 'created';
								}

								if ($authorization_status == 'CAPTURED') {
									$this->error['warning'] = sprintf($this->language->get('error_authorization_captured'), $this->url->link('information/contact', '', true));
								}
						
								if ($authorization_status == 'DENIED') {
									$transaction_status = 'denied';
							
									$this->error['warning'] = $this->language->get('error_authorization_denied');
								}
						
								if ($authorization_status == 'EXPIRED') {
									$this->error['warning'] = sprintf($this->language->get('error_authorization_expired'), $this->url->link('information/contact', '', true));
								}
																						
								if ($authorization_status == 'PENDING') {
									$order_status_id = $setting['order_status']['pending']['id'];
									$transaction_status = 'pending';
								}
														
								if (($authorization_status == 'CREATED') || ($authorization_status == 'DENIED') || ($authorization_status == 'PENDING')) {
									if ($payment_method == 'paypal') {
										$paypal_customer_token = array();
						
										if ($setting['general']['vault_status'] && $this->customer->isLogged()) {
											$paypal_customer_token = $this->model_extension_payment_paypal->getPayPalCustomerMainToken($this->customer->getId(), $payment_method);
										}
										
										if (!empty($paypal_customer_token['vault_id'])) {
											$vault_id = $paypal_customer_token['vault_id'];
											$vault_customer_id = $paypal_customer_token['vault_customer_id'];
										}
									}
									
									$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
									$paypal_order_data = array(
										'order_id' => $this->session->data['order_id'],
										'paypal_order_id' => $paypal_order_id,
										'transaction_id' => $authorization_id,
										'transaction_status' => $transaction_status,
										'payment_method' => $payment_method,
										'vault_id' => $vault_id,
										'vault_customer_id' => $vault_customer_id,
										'card_type' => $card_type,
										'card_nice_type' => $card_nice_type,
										'card_last_digits' => $card_last_digits,
										'card_expiry' => $card_expiry,
										'total' => $order_info['total'],
										'currency_code' => $order_info['currency_code'],
										'environment' => $environment
									);

									$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
									if ($vault_id && $this->customer->isLogged()) {
										$customer_id = $this->customer->getId();
										
										$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
										if (!$paypal_customer_token_info) {
											$paypal_customer_token_data = array(
												'customer_id' => $customer_id,
												'payment_method' => $payment_method,
												'vault_id' => $vault_id,
												'vault_customer_id' => $vault_customer_id,
												'card_type' => $card_type,
												'card_nice_type' => $card_nice_type,
												'card_last_digits' => $card_last_digits,
												'card_expiry' => $card_expiry
											);
					
											$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
										}
										
										$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
									}
								}
																	
								if ($order_status_id) {
									$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
									$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
								}
								
								if (($authorization_status == 'CREATED') || ($authorization_status == 'PENDING')) {
									$recurring_products = $this->cart->getRecurringProducts();
					
									foreach ($recurring_products as $recurring_product) {
										$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_info, $paypal_order_data);
									} 
								}
													
								if (($authorization_status == 'CREATED') || ($authorization_status == 'PARTIALLY_CAPTURED') || ($authorization_status == 'PARTIALLY_CREATED') || ($authorization_status == 'PENDING')) {
									$data['url'] = $this->url->link('checkout/success', '', true);
								}
							}
						} else {
							$this->model_extension_payment_paypal->log($result, 'Capture Order');
					
							if (isset($result['purchase_units'][0]['payments']['captures'][0]['status']) && isset($result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'])) {
								$capture_id = $result['purchase_units'][0]['payments']['captures'][0]['id'];
								$capture_status = $result['purchase_units'][0]['payments']['captures'][0]['status'];
								$seller_protection_status = $result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'];
								$order_status_id = 0;
								$transaction_status = '';
								$payment_method = '';
								$vault_id = '';
								$vault_customer_id = '';
								$card_type = (!empty($this->request->post['card_type']) ? $this->request->post['card_type'] : '');
								$card_nice_type = (!empty($this->request->post['card_nice_type']) ? $this->request->post['card_nice_type'] : '');
								$card_last_digits = '';
								$card_expiry = '';
								
								if (!$this->cart->hasShipping()) {
									$seller_protection_status = 'NOT_ELIGIBLE';
								}
								
								foreach ($result['payment_source'] as $payment_source_key => $payment_source) {
									$payment_method = $payment_source_key;
									$vault_id = (isset($payment_source['attributes']['vault']['id']) ? $payment_source['attributes']['vault']['id'] : '');
									$vault_customer_id = (isset($payment_source['attributes']['vault']['customer']['id']) ? $payment_source['attributes']['vault']['customer']['id'] : '');
									$card_last_digits = (isset($payment_source['last_digits']) ? $payment_source['last_digits'] : '');
									$card_expiry = (isset($payment_source['expiry']) ? $payment_source['expiry'] : '');
									
									break;
								}
														
								if ($capture_status == 'COMPLETED') {
									$order_status_id = $setting['order_status']['completed']['id'];
									$transaction_status = 'completed';
								}
						
								if ($capture_status == 'DECLINED') {
									$transaction_status = 'denied';
							
									$this->error['warning'] = $this->language->get('error_capture_declined');
								}
						
								if ($capture_status == 'FAILED') {
									$this->error['warning'] = sprintf($this->language->get('error_capture_failed'), $this->url->link('information/contact', '', true));
								}
														
								if ($capture_status == 'PENDING') {
									$order_status_id = $setting['order_status']['pending']['id'];
									$transaction_status = 'pending';
								}
														
								if (($capture_status == 'COMPLETED') || ($capture_status == 'DECLINED') || ($capture_status == 'PENDING')) {
									if ($payment_method == 'paypal') {
										$paypal_customer_token = array();
						
										if ($setting['general']['vault_status'] && $this->customer->isLogged()) {
											$paypal_customer_token = $this->model_extension_payment_paypal->getPayPalCustomerMainToken($this->customer->getId(), $payment_method);
										}
										
										if (!empty($paypal_customer_token['vault_id'])) {
											$vault_id = $paypal_customer_token['vault_id'];
											$vault_customer_id = $paypal_customer_token['vault_customer_id'];
										}
									}
									
									$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
									$paypal_order_data = array(
										'order_id' => $this->session->data['order_id'],
										'paypal_order_id' => $paypal_order_id,
										'transaction_id' => $capture_id,
										'transaction_status' => $transaction_status,
										'payment_method' => $payment_method,
										'vault_id' => $vault_id,
										'vault_customer_id' => $vault_customer_id,
										'card_type' => $card_type,
										'card_nice_type' => $card_nice_type,
										'card_last_digits' => $card_last_digits,
										'card_expiry' => $card_expiry,
										'total' => $order_info['total'],
										'currency_code' => $order_info['currency_code'],
										'environment' => $environment
									);

									$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
								
									if ($vault_id && $this->customer->isLogged()) {
										$customer_id = $this->customer->getId();
										
										$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
										if (!$paypal_customer_token_info) {
											$paypal_customer_token_data = array(
												'customer_id' => $customer_id,
												'payment_method' => $payment_method,
												'vault_id' => $vault_id,
												'vault_customer_id' => $vault_customer_id,
												'card_type' => $card_type,
												'card_nice_type' => $card_nice_type,
												'card_last_digits' => $card_last_digits,
												'card_expiry' => $card_expiry
											);
					
											$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
										}
										
										$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
									}
								}
									
								if ($order_status_id) {
									$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
									$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
								}
								
								if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
									$recurring_products = $this->cart->getRecurringProducts();
					
									foreach ($recurring_products as $recurring_product) {
										$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_info, $paypal_order_data);
									} 
								}
						
								if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
									$data['url'] = $this->url->link('checkout/success', '', true);
								}
							}
						}
					}
				}
			}
		}

		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));		
	}
		
	public function confirmOrder() {
		$this->load->language('extension/payment/paypal');
		$this->load->language('checkout/cart');

		$this->load->model('tool/image');
		
		if (!isset($this->session->data['paypal_order_id']) && !isset($this->session->data['paypal_payment_token'])) {
			$this->response->redirect($this->url->link('checkout/cart', '', true));
		}
					
		// Coupon
		if (isset($this->request->post['coupon']) && $this->validateCoupon()) {
			$this->session->data['coupon'] = $this->request->post['coupon'];

			$this->session->data['success'] = $this->language->get('text_coupon');

			$this->response->redirect($this->url->link('extension/payment/paypal/confirmOrder', '', true));
		}

		// Voucher
		if (isset($this->request->post['voucher']) && $this->validateVoucher()) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$this->session->data['success'] = $this->language->get('text_voucher');

			$this->response->redirect($this->url->link('extension/payment/paypal/confirmOrder', '', true));
		}

		// Reward
		if (isset($this->request->post['reward']) && $this->validateReward()) {
			$this->session->data['reward'] = abs($this->request->post['reward']);

			$this->session->data['success'] = $this->language->get('text_reward');

			$this->response->redirect($this->url->link('extension/payment/paypal/confirmOrder', '', true));
		}
		
		if (isset($this->session->data['paypal_order_id'])) {
			$text_title = $this->language->get('text_paypal');
		}
		
		if (isset($this->session->data['paypal_payment_token'])) {
			$text_title = $this->language->get('text_paypal_fastlane');
		}
		
		$this->document->setTitle($text_title);
		
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment/moment-with-locales.min.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		
		$theme = $this->config->get('theme_' . $this->config->get('config_theme') . '_directory');
				
		if (file_exists(DIR_TEMPLATE . $theme . '/stylesheet/paypal/paypal.css')) {
			$this->document->addStyle('catalog/view/theme/' . $theme . '/stylesheet/paypal/paypal.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/paypal/paypal.css');
		}

		$data['heading_title'] = $text_title;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_cart'),
			'href' => $this->url->link('checkout/cart', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $text_title,
			'href' => $this->url->link('extension/payment/paypal/confirmOrder', '', true)
		);
		
		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}
		
		if (isset($this->request->post['next'])) {
			$data['next'] = $this->request->post['next'];
		} else {
			$data['next'] = '';
		}

		$this->load->model('tool/upload');
		
		if (!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) {
			$this->response->redirect($this->url->link('checkout/cart', '', true));
		}
		
		$data['products'] = array();
		
		$products = $this->cart->getProducts();

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
			
			$recurring = '';

			if ($product['recurring']) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($product['recurring']['trial']) {
					$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
				}

				if ($product['recurring']['duration']) {
					$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				} else {
					$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				}
			}

			$data['products'][] = array(
				'cart_id'               => $product['cart_id'],
				'thumb'                 => $image,
				'name'                  => $product['name'],
				'model'                 => $product['model'],
				'option'                => $option_data,
				'recurring' 			=> $recurring,
				'quantity'              => $product['quantity'],
				'stock'                 => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
				'reward'                => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
				'price'                 => $price,
				'total'                 => $total,
				'href'                  => $this->url->link('product/product', 'product_id=' . $product['product_id'], true)
			);
		}

		// Gift Voucher
		$data['vouchers'] = array();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $key => $voucher) {
				$data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency']),
					'remove'      => $this->url->link('checkout/cart', 'remove=' . $key, true)
				);
			}
		}
		
		$this->load->model('setting/extension');
		
		if ($this->cart->hasShipping()) {
			$data['has_shipping'] = true;
			
			$data['shipping_address'] = isset($this->session->data['shipping_address']) ? $this->session->data['shipping_address'] : array();
			
			if (!empty($data['shipping_address'])) {
				// Shipping Methods
				$quote_data = array();

				$results = $this->model_setting_extension->getExtensions('shipping');

				if (!empty($results)) {
					foreach ($results as $result) {
						if ($this->config->get('shipping_' . $result['code'] . '_status')) {
							$this->load->model('extension/shipping/' . $result['code']);

							$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($data['shipping_address']);

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

						$data['shipping_method_code'] = $this->session->data['shipping_method']['code'];
						$data['action_shipping'] = $this->url->link('extension/payment/paypal/confirmShipping', '', true);
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
				
		$data['guest'] = isset($this->session->data['guest']) ? $this->session->data['guest'] : array();
		$data['payment_address'] = isset($this->session->data['payment_address']) ? $this->session->data['payment_address'] : array();	
		
		// Totals
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
		
		/**
		 * Payment methods
		 */
		$method_data = array();

		$results = $this->model_setting_extension->getExtensions('payment');

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$this->load->model('extension/payment/' . $result['code']);

				$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($data['payment_address'], $total);

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
		
		$this->session->data['payment_methods'] = $method_data;
		$data['payment_methods'] = $method_data;

		if (!isset($method_data['paypal'])) {
			$this->session->data['error_warning'] = $this->language->get('error_unavailable');
			
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}
		
		if (isset($this->session->data['payment_method']['code'])) {
			$data['payment_method_code'] = $this->session->data['payment_method']['code'];
		} else {
			$this->session->data['payment_method'] = $method_data['paypal'];
			
			$data['payment_method_code'] = $this->session->data['payment_method']['code'];
		}
		
		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

		// Totals
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

		$data['action_confirm'] = $this->url->link('extension/payment/paypal/completeOrder', '', true);

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

		$data['total_coupon'] = $this->load->controller('extension/total/coupon');
		$data['total_voucher'] = $this->load->controller('extension/total/voucher');
		$data['total_reward'] = $this->load->controller('extension/total/reward');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/payment/paypal/confirm', $data));
	}
	
	public function completeOrder() {	
		$this->load->language('extension/payment/paypal');
						
		$this->load->model('extension/payment/paypal');
				
		// Validate if payment address has been set.
		if (empty($this->session->data['payment_address'])) {
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}

		// Validate if payment method has been set.
		if (!isset($this->session->data['payment_method'])) {
			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}
		
		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			if (empty($this->session->data['shipping_address'])) {
				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			}
		} else {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart', '', true));
		}
		
		if (isset($this->session->data['paypal_order_id']) || isset($this->session->data['paypal_payment_token'])) {			
			$order_data = array();

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
		
			$order_data['totals'] = $totals;

			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');

			if ($order_data['store_id']) {
				$order_data['store_url'] = $this->config->get('config_url');
			} else {
				if ($this->request->server['HTTPS']) {
					$order_data['store_url'] = HTTPS_SERVER;
				} else {
					$order_data['store_url'] = HTTP_SERVER;
				}
			}
										
			$order_data['customer_id'] = $this->session->data['guest']['customer_id'];
			$order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
			$order_data['firstname'] = $this->session->data['guest']['firstname'];
			$order_data['lastname'] = $this->session->data['guest']['lastname'];
			$order_data['telephone'] = $this->session->data['guest']['telephone'];
			$order_data['custom_field'] = $this->session->data['guest']['custom_field'];
			
			if ($this->session->data['guest']['email']) {
				$order_data['email'] = $this->session->data['guest']['email'];
			} else {
				$order_data['email'] = $this->config->get('config_email');
			}
									
			$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
			$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
			$order_data['payment_company'] = $this->session->data['payment_address']['company'];
			$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
			$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
			$order_data['payment_city'] = $this->session->data['payment_address']['city'];
			$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
			$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
			$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
			$order_data['payment_country'] = $this->session->data['payment_address']['country'];
			$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
			$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
			$order_data['payment_custom_field'] = (isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : array());

			if (isset($this->session->data['payment_method']['title'])) {
				$order_data['payment_method'] = $this->session->data['payment_method']['title'];
			} else {
				$order_data['payment_method'] = '';
			}

			if (isset($this->session->data['payment_method']['code'])) {
				$order_data['payment_code'] = $this->session->data['payment_method']['code'];
			} else {
				$order_data['payment_code'] = '';
			}			

			if ($this->cart->hasShipping()) {
				$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
				$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
				$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
				$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
				$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
				$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
				$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
				$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
				$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
				$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = (isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : array());

				if (isset($this->session->data['shipping_method']['title'])) {
					$order_data['shipping_method'] = $this->session->data['shipping_method']['title'];
				} else {
					$order_data['shipping_method'] = '';
				}

				if (isset($this->session->data['shipping_method']['code'])) {
					$order_data['shipping_code'] = $this->session->data['shipping_method']['code'];
				} else {
					$order_data['shipping_code'] = '';
				}
			} else {
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = 0;
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = 0;
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = array();
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}

			$order_data['products'] = array();

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

				$order_data['products'][] = array(
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
			$order_data['vouchers'] = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$order_data['vouchers'][] = array(
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

			$order_data['comment'] = (isset($this->session->data['comment']) ? $this->session->data['comment'] : '');
			$order_data['total'] = $total_data['total'];

			if (isset($this->request->cookie['tracking'])) {
				$order_data['tracking'] = $this->request->cookie['tracking'];

				$sub_total = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('account/customer');
				
				$affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($sub_total / 100) * $affiliate_info['commission'];
				} else {
					$order_data['affiliate_id'] = 0;
					$order_data['commission'] = 0;
				}

				// Marketing
				$this->load->model('checkout/marketing');

				$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$order_data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$order_data['marketing_id'] = 0;
				}
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
				$order_data['marketing_id'] = 0;
				$order_data['tracking'] = '';
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$order_data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$order_data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$order_data['accept_language'] = '';
			}
			
			$this->load->model('checkout/order');

			$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);
			
			$order_data['order_id'] = $this->session->data['order_id'];
						
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
			
			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$vault_status = $setting['general']['vault_status'];
			$transaction_method = $setting['general']['transaction_method'];
			
			$currency_code = $this->session->data['currency'];
			$currency_value = $this->currency->getValue($this->session->data['currency']);
				
			if (empty($setting['currency'][$currency_code]['status'])) {
				$currency_code = $setting['general']['currency_code'];
				$currency_value = $setting['general']['currency_value'];
			}
			
			$decimal_place = $setting['currency'][$currency_code]['decimal_place'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
			
			if (isset($this->session->data['paypal_client_metadata_id'])) {
				$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
			}
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
				
			$paypal->setAccessToken($token_info);
			
			if (isset($this->session->data['paypal_order_id'])) {
				$paypal_order_id = $this->session->data['paypal_order_id'];
			
				$paypal_order_info = $paypal->getOrder($paypal_order_id);
			
				if ($paypal->hasErrors()) {
					$error_messages = array();
				
					$errors = $paypal->getErrors();
								
					foreach ($errors as $error) {
						if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
							$error['message'] = $this->language->get('error_timeout');
						}
					
						if (isset($error['details'][0]['description'])) {
							$error_messages[] = $error['details'][0]['description'];
						} elseif (isset($error['message'])) {
							$error_messages[] = $error['message'];
						}
					
						$this->model_extension_payment_paypal->log($error, $error['message']);
					}
			
					$this->error['warning'] = implode(' ', $error_messages);
				}
		
				if (!empty($this->error['warning'])) {
					$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
			
				$shipping_info_name = array();
				$shipping_info_address = array();
					
				if ($paypal_order_info && !$this->error) {
					$shipping_info_name = (isset($paypal_order_info['purchase_units'][0]['shipping']['name']) ? $paypal_order_info['purchase_units'][0]['shipping']['name'] : array()); 
					$shipping_info_address = (isset($paypal_order_info['purchase_units'][0]['shipping']['address']) ? $paypal_order_info['purchase_units'][0]['shipping']['address'] : array());
				}
			
				$paypal_order_info = array();
			
				$paypal_order_info[] = array(
					'op' => 'add',
					'path' => '/purchase_units/@reference_id==\'default\'/description',
					'value' => 'Your order ' . $this->session->data['order_id']
				);
			
				if ($setting['general']['invoice_id_tokenization_status']) {
					$paypal_order_info[] = array(
						'op' => 'add',
						'path' => '/purchase_units/@reference_id==\'default\'/invoice_id',
						'value' => $this->session->data['order_id'] . '_' . date('Ymd_His')
					);
				} else {
					$paypal_order_info[] = array(
						'op' => 'add',
						'path' => '/purchase_units/@reference_id==\'default\'/invoice_id',
						'value' => $this->session->data['order_id']
					);
				}
	
				$shipping_info = array();

				if ($this->cart->hasShipping()) {
					$shipping_info['name']['full_name'] = (isset($this->session->data['shipping_address']['firstname']) ? $this->session->data['shipping_address']['firstname'] : '');
					$shipping_info['name']['full_name'] .= (isset($this->session->data['shipping_address']['lastname']) ? (' ' . $this->session->data['shipping_address']['lastname']) : '');			
					$shipping_info['address']['address_line_1'] = (isset($this->session->data['shipping_address']['address_1']) ? $this->session->data['shipping_address']['address_1'] : '');
					$shipping_info['address']['address_line_2'] = (isset($this->session->data['shipping_address']['address_2']) ? $this->session->data['shipping_address']['address_2'] : '');			
					$shipping_info['address']['admin_area_1'] = (isset($this->session->data['shipping_address']['zone']) ? $this->session->data['shipping_address']['zone'] : '');
					$shipping_info['address']['admin_area_2'] = (isset($this->session->data['shipping_address']['city']) ? $this->session->data['shipping_address']['city'] : '');
					$shipping_info['address']['postal_code'] = (isset($this->session->data['shipping_address']['postcode']) ? $this->session->data['shipping_address']['postcode'] : '');
			
					if (isset($this->session->data['shipping_address']['country_id'])) {
						$this->load->model('localisation/country');
				
						$country_info = $this->model_localisation_country->getCountry($this->session->data['shipping_address']['country_id']);
			
						if ($country_info) {
							$shipping_info['address']['country_code'] = $country_info['iso_code_2'];
						}
					}
				
					if ($shipping_info_name) {
						$paypal_order_info[] = array(
							'op' => 'replace',
							'path' => '/purchase_units/@reference_id==\'default\'/shipping/name',
							'value' => $shipping_info['name']
						);
					} else {
						$paypal_order_info[] = array(
							'op' => 'add',
							'path' => '/purchase_units/@reference_id==\'default\'/shipping/name',
							'value' => $shipping_info['name']
						);
					}
				
					if ($shipping_info_address) {
						$paypal_order_info[] = array(
							'op' => 'replace',
							'path' => '/purchase_units/@reference_id==\'default\'/shipping/address',
							'value' => $shipping_info['address']
						);
					} else {
						$paypal_order_info[] = array(
							'op' => 'add',
							'path' => '/purchase_units/@reference_id==\'default\'/shipping/address',
							'value' => $shipping_info['address']
						);
					}
				}
												
				$item_total = 0;
				$tax_total = 0;
				
				foreach ($this->cart->getProducts() as $product) {
					$product_price = number_format($product['price'] * $currency_value, $decimal_place, '.', '');
				
					$item_total += $product_price * $product['quantity'];
				
					if ($product['tax_class_id']) {
						$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							$tax_total += ($tax_rate['amount'] * $product['quantity']);
						}
					}
				}
			
				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $voucher) {
						$item_total += $voucher['amount'];
					}
				}
												
				$item_total = number_format($item_total, $decimal_place, '.', '');
				$tax_total = number_format($tax_total * $currency_value, $decimal_place, '.', '');
						
				$discount_total = 0;
				$handling_total = 0;
				$shipping_total = 0;
		
				if (isset($this->session->data['shipping_method'])) {
					$shipping_total = $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'], true);
					$shipping_total = number_format($shipping_total * $currency_value, $decimal_place, '.', '');
				}
		
				$order_total = number_format($order_data['total'] * $currency_value, $decimal_place, '.', '');
		
				$rebate = number_format($item_total + $tax_total + $shipping_total - $order_total, $decimal_place, '.', '');
		
				if ($rebate > 0) {
					$discount_total = $rebate;
				} elseif ($rebate < 0) {
					$handling_total = -$rebate;
				}
			
				$amount_info = array();
				
				$amount_info['currency_code'] = $currency_code;
				$amount_info['value'] = $order_total;
								
				$amount_info['breakdown']['item_total'] = array(
					'currency_code' => $currency_code,
					'value' => $item_total
				);
				
				$amount_info['breakdown']['tax_total'] = array(
					'currency_code' => $currency_code,
					'value' => $tax_total
				);
				
				$amount_info['breakdown']['shipping'] = array(
					'currency_code' => $currency_code,
					'value' => $shipping_total
				);
					
				$amount_info['breakdown']['handling'] = array(
					'currency_code' => $currency_code,
					'value' => $handling_total
				);
					
				$amount_info['breakdown']['discount'] = array(
					'currency_code' => $currency_code,
					'value' => $discount_total
				);			
			
				$paypal_order_info[] = array(
					'op' => 'replace',
					'path' => '/purchase_units/@reference_id==\'default\'/amount',
					'value' => $amount_info
				);
					
				$result = $paypal->updateOrder($paypal_order_id, $paypal_order_info);
			
				if ($paypal->hasErrors()) {
					$error_messages = array();
				
					$errors = $paypal->getErrors();
							
					foreach ($errors as $error) {
						if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
							$error['message'] = $this->language->get('error_timeout');
						}
					
						if (isset($error['details'][0]['description'])) {
							$error_messages[] = $error['details'][0]['description'];
						} elseif (isset($error['message'])) {
							$error_messages[] = $error['message'];
						}
					
						$this->model_extension_payment_paypal->log($error, $error['message']);
					}
				
					$this->error['warning'] = implode(' ', $error_messages);
				}
			
				if (!empty($this->error['warning'])) {
					$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
						
				if ($paypal_order_id && !$this->error) {				
					if ($transaction_method == 'authorize') {
						$result = $paypal->setOrderAuthorize($paypal_order_id);
					} else {
						$result = $paypal->setOrderCapture($paypal_order_id);
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
							} elseif (isset($error['message'])) {
								$error_messages[] = $error['message'];
							}
					
							$this->model_extension_payment_paypal->log($error, $error['message']);
						}
				
						$this->error['warning'] = implode(' ', $error_messages);
					}
			
					if (!empty($this->error['warning'])) {
						$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
					}
			
					if (!$this->error) {				
						if ($transaction_method == 'authorize') {
							$this->model_extension_payment_paypal->log($result, 'Authorize Order');
			
							if (isset($result['purchase_units'][0]['payments']['authorizations'][0]['status']) && isset($result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'])) {
								$authorization_id = $result['purchase_units'][0]['payments']['authorizations'][0]['id'];
								$authorization_status = $result['purchase_units'][0]['payments']['authorizations'][0]['status'];
								$seller_protection_status = $result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'];							
								$order_status_id = 0;
								$transaction_status = '';
								$payment_method = '';
								$vault_id = '';
								$vault_customer_id = '';
								$card_type = '';
								$card_nice_type = '';
								$card_last_digits = '';
								$card_expiry = '';
								
								if (!$this->cart->hasShipping()) {
									$seller_protection_status = 'NOT_ELIGIBLE';
								}
								
								foreach ($result['payment_source'] as $payment_source_key => $payment_source) {
									$payment_method = $payment_source_key;
									$vault_id = (isset($payment_source['attributes']['vault']['id']) ? $payment_source['attributes']['vault']['id'] : '');
									$vault_customer_id = (isset($payment_source['attributes']['vault']['customer']['id']) ? $payment_source['attributes']['vault']['customer']['id'] : '');
									$card_last_digits = (isset($payment_source['last_digits']) ? $payment_source['last_digits'] : '');
									$card_expiry = (isset($payment_source['expiry']) ? $payment_source['expiry'] : '');
									
									break;
								}

								if ($authorization_status == 'CREATED') {
									$order_status_id = $setting['order_status']['pending']['id'];
									$transaction_status = 'created';
								}

								if ($authorization_status == 'CAPTURED') {
									$this->error['warning'] = sprintf($this->language->get('error_authorization_captured'), $this->url->link('information/contact', '', true));
								}
						
								if ($authorization_status == 'DENIED') {
									$transaction_status = 'denied';
							
									$this->error['warning'] = $this->language->get('error_authorization_denied');
								}
						
								if ($authorization_status == 'EXPIRED') {
									$this->error['warning'] = sprintf($this->language->get('error_authorization_expired'), $this->url->link('information/contact', '', true));
								}
						
								if ($authorization_status == 'PENDING') {
									$order_status_id = $setting['order_status']['pending']['id'];
									$transaction_status = 'pending';
								}
														
								if (($authorization_status == 'CREATED') || ($authorization_status == 'DENIED') || ($authorization_status == 'PENDING')) {
									if ($payment_method == 'paypal') {
										$paypal_customer_token = array();
						
										if ($setting['general']['vault_status'] && $this->customer->isLogged()) {
											$paypal_customer_token = $this->model_extension_payment_paypal->getPayPalCustomerMainToken($this->customer->getId(), $payment_method);
										}
										
										if (!empty($paypal_customer_token['vault_id'])) {
											$vault_id = $paypal_customer_token['vault_id'];
											$vault_customer_id = $paypal_customer_token['vault_customer_id'];
										}
									}
									
									$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
									$paypal_order_data = array(
										'order_id' => $this->session->data['order_id'],
										'paypal_order_id' => $paypal_order_id,
										'transaction_id' => $authorization_id,
										'transaction_status' => $transaction_status,
										'payment_method' => $payment_method,
										'vault_id' => $vault_id,
										'vault_customer_id' => $vault_customer_id,
										'card_type' => $card_type,
										'card_nice_type' => $card_nice_type,
										'card_last_digits' => $card_last_digits,
										'card_expiry' => $card_expiry,
										'total' => $order_data['total'],
										'currency_code' => $order_data['currency_code'],
										'environment' => $environment
									);

									$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
									if ($vault_id && $this->customer->isLogged()) {
										$customer_id = $this->customer->getId();
										
										$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
										if (!$paypal_customer_token_info) {
											$paypal_customer_token_data = array(
												'customer_id' => $customer_id,
												'payment_method' => $payment_method,
												'vault_id' => $vault_id,
												'vault_customer_id' => $vault_customer_id,
												'card_type' => $card_type,
												'card_nice_type' => $card_nice_type,
												'card_last_digits' => $card_last_digits,
												'card_expiry' => $card_expiry,
											);
					
											$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
										}
										
										$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
									}
								}
							
								if ($order_status_id) {
									$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
									$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
								}
								
								if (($authorization_status == 'CREATED') || ($authorization_status == 'PENDING')) {
									$recurring_products = $this->cart->getRecurringProducts();
					
									foreach ($recurring_products as $recurring_product) {
										$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_data, $paypal_order_data);
									} 
								}

								if (($authorization_status == 'CREATED') || ($authorization_status == 'PARTIALLY_CAPTURED') || ($authorization_status == 'PARTIALLY_CREATED') || ($authorization_status == 'PENDING')) {
									$this->response->redirect($this->url->link('checkout/success', '', true));
								}
							}
						} else {
							$this->model_extension_payment_paypal->log($result, 'Capture Order');
					
							if (isset($result['purchase_units'][0]['payments']['captures'][0]['status']) && isset($result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'])) {
								$capture_id = $result['purchase_units'][0]['payments']['captures'][0]['id'];
								$capture_status = $result['purchase_units'][0]['payments']['captures'][0]['status'];
								$seller_protection_status = $result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'];
								$order_status_id = 0;
								$transaction_status = '';
								$payment_method = '';
								$vault_id = '';
								$vault_customer_id = '';
								$card_type = '';
								$card_nice_type = '';
								$card_last_digits = '';
								$card_expiry = '';
								
								if (!$this->cart->hasShipping()) {
									$seller_protection_status = 'NOT_ELIGIBLE';
								}
								
								foreach ($result['payment_source'] as $payment_source_key => $payment_source) {
									$payment_method = $payment_source_key;
									$vault_id = (isset($payment_source['attributes']['vault']['id']) ? $payment_source['attributes']['vault']['id'] : '');
									$vault_customer_id = (isset($payment_source['attributes']['vault']['customer']['id']) ? $payment_source['attributes']['vault']['customer']['id'] : '');
									$card_last_digits = (isset($payment_source['last_digits']) ? $payment_source['last_digits'] : '');
									$card_expiry = (isset($payment_source['expiry']) ? $payment_source['expiry'] : '');
									
									break;
								}
														
								if ($capture_status == 'COMPLETED') {
									$order_status_id = $setting['order_status']['completed']['id'];
									$transaction_status = 'completed';
								}
						
								if ($capture_status == 'DECLINED') {
									$transaction_status = 'denied';
							
									$this->error['warning'] = $this->language->get('error_capture_declined');
								}
						
								if ($capture_status == 'FAILED') {
									$this->error['warning'] = sprintf($this->language->get('error_capture_failed'), $this->url->link('information/contact', '', true));
								}
						
								if ($capture_status == 'PENDING') {
									$order_status_id = $setting['order_status']['pending']['id'];
									$transaction_status = 'pending';
								}
													
								if (($capture_status == 'COMPLETED') || ($capture_status == 'DECLINED') || ($capture_status == 'PENDING')) {
									if ($payment_method == 'paypal') {
										$paypal_customer_token = array();
						
										if ($setting['general']['vault_status'] && $this->customer->isLogged()) {
											$paypal_customer_token = $this->model_extension_payment_paypal->getPayPalCustomerMainToken($this->customer->getId(), $payment_method);
										}
										
										if (!empty($paypal_customer_token['vault_id'])) {
											$vault_id = $paypal_customer_token['vault_id'];
											$vault_customer_id = $paypal_customer_token['vault_customer_id'];
										}
									}
									
									$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
									$paypal_order_data = array(
										'order_id' => $this->session->data['order_id'],
										'paypal_order_id' => $paypal_order_id,
										'transaction_id' => $capture_id,
										'transaction_status' => $transaction_status,
										'payment_method' => $payment_method,
										'vault_id' => $vault_id,
										'vault_customer_id' => $vault_customer_id,
										'card_type' => $card_type,
										'card_nice_type' => $card_nice_type,
										'card_last_digits' => $card_last_digits,
										'card_expiry' => $card_expiry,
										'total' => $order_data['total'],
										'currency_code' => $order_data['currency_code'],
										'environment' => $environment
									);

									$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
									if ($vault_id && $this->customer->isLogged()) {
										$customer_id = $this->customer->getId();
										
										$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
										if (!$paypal_customer_token_info) {
											$paypal_customer_token_data = array(
												'customer_id' => $customer_id,
												'payment_method' => $payment_method,
												'vault_id' => $vault_id,
												'vault_customer_id' => $vault_customer_id,
												'card_type' => $card_type,
												'card_nice_type' => $card_nice_type,
												'card_last_digits' => $card_last_digits,
												'card_expiry' => $card_expiry,
											);
					
											$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
										}
										
										$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
									}
								}
							
								if ($order_status_id) {
									$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
									$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
								}
								
								if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
									$recurring_products = $this->cart->getRecurringProducts();
					
									foreach ($recurring_products as $recurring_product) {
										$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_data, $paypal_order_data);
									} 
								}
														
								if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
									$this->response->redirect($this->url->link('checkout/success', '', true));
								}
							}
						}
					}
				}
			}
			
			if (isset($this->session->data['paypal_payment_token'])) {	
				$payment_token = $this->session->data['paypal_payment_token'];		
				$payment_method = 'card';
				
				$shipping_info = array();

				if ($this->cart->hasShipping()) {
					$shipping_info['name']['full_name'] = (isset($this->session->data['shipping_address']['firstname']) ? $this->session->data['shipping_address']['firstname'] : '');
					$shipping_info['name']['full_name'] .= (isset($this->session->data['shipping_address']['lastname']) ? (' ' . $this->session->data['shipping_address']['lastname']) : '');			
					$shipping_info['address']['address_line_1'] = (isset($this->session->data['shipping_address']['address_1']) ? $this->session->data['shipping_address']['address_1'] : '');
					$shipping_info['address']['address_line_2'] = (isset($this->session->data['shipping_address']['address_2']) ? $this->session->data['shipping_address']['address_2'] : '');			
					$shipping_info['address']['admin_area_1'] = (isset($this->session->data['shipping_address']['zone']) ? $this->session->data['shipping_address']['zone'] : '');
					$shipping_info['address']['admin_area_2'] = (isset($this->session->data['shipping_address']['city']) ? $this->session->data['shipping_address']['city'] : '');
					$shipping_info['address']['postal_code'] = (isset($this->session->data['shipping_address']['postcode']) ? $this->session->data['shipping_address']['postcode'] : '');
			
					if (isset($this->session->data['shipping_address']['country_id'])) {
						$this->load->model('localisation/country');
				
						$country_info = $this->model_localisation_country->getCountry($this->session->data['shipping_address']['country_id']);
			
						if ($country_info) {
							$shipping_info['address']['country_code'] = $country_info['iso_code_2'];
						}
					}
				}
				
				$item_info = array();
			
				$item_total = 0;
				$tax_total = 0;
								
				foreach ($this->cart->getProducts() as $product) {
					$product_price = number_format($product['price'] * $currency_value, $decimal_place, '.', '');
				
					$product_info = array();
					
					$product_info['name'] = $product['name'];
					$product_info['quantity'] = $product['quantity'];
					$product_info['sku'] = $product['model'];
					$product_info['url'] = $this->url->link('product/product', 'product_id=' . $product['product_id'], true);
										
					$product_info['unit_amount'] = array(
						'currency_code' => $currency_code,
						'value' => $product_price
					);

					$item_info[] = $product_info;
				
					$item_total += $product_price * $product['quantity'];
				
					if ($product['tax_class_id']) {
						$tax_rates = $this->tax->getRates($product['price'], $product['tax_class_id']);

						foreach ($tax_rates as $tax_rate) {
							$tax_total += ($tax_rate['amount'] * $product['quantity']);
						}
					}
				}
				
				if (!empty($this->session->data['vouchers'])) {
					foreach ($this->session->data['vouchers'] as $voucher) {
						$voucher_info = array();
	
						$voucher_info['name'] = $voucher['description'];
						$voucher_info['quantity'] = 1;
						
						$voucher_info['unit_amount'] = array(
							'currency_code' => $currency_code,
							'value' => $voucher['amount']
						);
												
						$item_info[] = $voucher_info;
					
						$item_total += $voucher['amount'];
					}
				}
				
				$item_total = number_format($item_total, $decimal_place, '.', '');
				$tax_total = number_format($tax_total * $currency_value, $decimal_place, '.', '');
				$order_total = number_format($item_total + $tax_total, $decimal_place, '.', '');
				
				$discount_total = 0;
				$handling_total = 0;
				$shipping_total = 0;
				
				if (isset($this->session->data['shipping_method'])) {
					$shipping_total = $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'], true);
					$shipping_total = number_format($shipping_total * $currency_value, $decimal_place, '.', '');
				}
		
				$order_total = number_format($order_data['total'] * $currency_value, $decimal_place, '.', '');
		
				$rebate = number_format($item_total + $tax_total + $shipping_total - $order_total, $decimal_place, '.', '');
		
				if ($rebate > 0) {
					$discount_total = $rebate;
				} elseif ($rebate < 0) {
					$handling_total = -$rebate;
				}
										
				$amount_info = array();
				
				$amount_info['currency_code'] = $currency_code;
				$amount_info['value'] = $order_total;
								
				$amount_info['breakdown']['item_total'] = array(
					'currency_code' => $currency_code,
					'value' => $item_total
				);
				
				$amount_info['breakdown']['tax_total'] = array(
					'currency_code' => $currency_code,
					'value' => $tax_total
				);
				
				$amount_info['breakdown']['shipping'] = array(
					'currency_code' => $currency_code,
					'value' => $shipping_total
				);
					
				$amount_info['breakdown']['handling'] = array(
					'currency_code' => $currency_code,
					'value' => $handling_total
				);
					
				$amount_info['breakdown']['discount'] = array(
					'currency_code' => $currency_code,
					'value' => $discount_total
				);
								
				$paypal_order_info = array();
				
				$paypal_order_info['intent'] = strtoupper($transaction_method);
				$paypal_order_info['purchase_units'][0]['reference_id'] = 'default';
				$paypal_order_info['purchase_units'][0]['items'] = $item_info;
				$paypal_order_info['purchase_units'][0]['amount'] = $amount_info;
				$paypal_order_info['purchase_units'][0]['description'] = 'Your order ' . $this->session->data['order_id'];
				
				if ($setting['general']['invoice_id_tokenization_status']) {
					$paypal_order_info['purchase_units'][0]['invoice_id'] = $this->session->data['order_id'] . '_' . date('Ymd_His');
				} else {
					$paypal_order_info['purchase_units'][0]['invoice_id'] = $this->session->data['order_id'];
				}
					
				if ($this->cart->hasShipping()) {
					$paypal_order_info['purchase_units'][0]['shipping'] = $shipping_info;
				}
								
				if ($this->cart->hasShipping()) {			
					$shipping_preference = 'GET_FROM_FILE';
				} else {
					$shipping_preference = 'NO_SHIPPING';
				}
	
				$paypal_order_info['application_context']['shipping_preference'] = $shipping_preference;
								
				if ($payment_method) {
					$paypal_order_info['payment_source'][$payment_method]['single_use_token'] = $payment_token;
					$paypal_order_info['payment_source'][$payment_method]['attributes']['verification']['method'] = strtoupper($setting['card']['secure_method']);
					$paypal_order_info['payment_source'][$payment_method]['experience_context']['return_url'] = $this->url->link('extension/payment/paypal', 'callback_token=' . $setting['general']['callback_token'], true);
					$paypal_order_info['payment_source'][$payment_method]['experience_context']['cancel_url'] = $this->url->link('checkout/checkout', '', true);
				}
				
				$result = $paypal->createOrder($paypal_order_info);
				
				if ($paypal->hasErrors()) {
					$error_messages = array();
				
					$errors = $paypal->getErrors();
								
					foreach ($errors as $error) {
						if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
							$error['message'] = $this->language->get('error_timeout');
						}
				
						if (isset($error['details'][0]['description'])) {
							$error_messages[] = $error['details'][0]['description'];
						} elseif (isset($error['message'])) {
							$error_messages[] = $error['message'];
						}
					
						$this->model_extension_payment_paypal->log($error, $error['message']);
					}
				
					$this->error['warning'] = implode(' ', $error_messages);
				}
		
				if (!empty($this->error['warning'])) {
					$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
						
				if (isset($result['id']) && isset($result['status']) && !$this->error) {
					$this->model_extension_payment_paypal->log($result, 'Create Order');
								
					if ($result['status'] == 'VOIDED') {
						$this->error['warning'] = sprintf($this->language->get('error_order_voided'), $this->url->link('information/contact', '', true));
					}
													
					if ($result['status'] == 'COMPLETED') {
						$paypal_order_id = $result['id'];
						
						$vault_id = '';
						$vault_customer_id = '';
						$card_type = '';
						$card_nice_type = '';
						$card_last_digits = '';
						$card_expiry = '';
					
						$paypal_order_info = $paypal->getOrder($paypal_order_id);
												
						if ($paypal->hasErrors()) {
							$error_messages = array();
				
							$errors = $paypal->getErrors();
								
							foreach ($errors as $error) {
								if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
									$error['message'] = $this->language->get('error_timeout');
								}
					
								if (isset($error['details'][0]['description'])) {
									$error_messages[] = $error['details'][0]['description'];
								} elseif (isset($error['message'])) {
									$error_messages[] = $error['message'];
								}
					
								$this->model_extension_payment_paypal->log($error, $error['message']);
							}
		
							$this->error['warning'] = implode(' ', $error_messages);
						}
							
						if (isset($paypal_order_info['payment_source']['card']) && !$this->error) {
							$this->model_extension_payment_paypal->log($paypal_order_info['payment_source']['card'], 'Card');
						
							$liability_shift = (isset($paypal_order_info['payment_source']['card']['authentication_result']['liability_shift']) ? $paypal_order_info['payment_source']['card']['authentication_result']['liability_shift'] : '');
							$enrollment_status = (isset($paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status']) ? $paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status'] : '');
							$authentication_status = (isset($paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status']) ? $paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status'] : '');
								
							if ($enrollment_status == 'Y') {
								if (($authentication_status == 'N') && !$setting['card']['secure_scenario']['failed_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['failed_authentication']['error']);
								}
						
								if (($authentication_status == 'R') && !$setting['card']['secure_scenario']['rejected_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['rejected_authentication']['error']);
								}
						
								if (($authentication_status == 'A') && !$setting['card']['secure_scenario']['attempted_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['attempted_authentication']['error']);
								}
						
								if (($authentication_status == 'U') && !$setting['card']['secure_scenario']['unable_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['unable_authentication']['error']);
								}
						
								if (($authentication_status == 'C') && !$setting['card']['secure_scenario']['challenge_authentication']) {
									$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['challenge_authentication']['error']);
								}
							}
					
							if (($enrollment_status == 'N') && !$setting['card']['secure_scenario']['card_ineligible']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['card_ineligible']['error']);
							}
					
							if (($enrollment_status == 'U') && !$setting['card']['secure_scenario']['system_unavailable']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['system_unavailable']['error']);
							}
					
							if (($enrollment_status == 'B') && !$setting['card']['secure_scenario']['system_bypassed']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['system_bypassed']['error']);
							}
						}
		
						if (!empty($this->error['warning'])) {
							$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
						}
				
						if (!$this->error) {				
							$this->load->model('checkout/order');
				
							$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
						
							if ($transaction_method == 'authorize') {
								$this->model_extension_payment_paypal->log($result, 'Authorize Order');
					
								if (isset($result['purchase_units'][0]['payments']['authorizations'][0]['status']) && isset($result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'])) {
									$authorization_id = $result['purchase_units'][0]['payments']['authorizations'][0]['id'];
									$authorization_status = $result['purchase_units'][0]['payments']['authorizations'][0]['status'];
									$seller_protection_status = $result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'];	  						
									$order_status_id = 0;
									$transaction_status = '';
									$payment_method = '';
								
									if (!$this->cart->hasShipping()) {
										$seller_protection_status = 'NOT_ELIGIBLE';
									}
								
									foreach ($result['payment_source'] as $payment_source_key => $payment_source) {
										$payment_method = $payment_source_key;
										$vault_id = (isset($payment_source['attributes']['vault']['id']) ? $payment_source['attributes']['vault']['id'] : '');
										$vault_customer_id = (isset($payment_source['attributes']['vault']['customer']['id']) ? $payment_source['attributes']['vault']['customer']['id'] : '');
										$card_last_digits = (isset($payment_source['last_digits']) ? $payment_source['last_digits'] : '');
										$card_expiry = (isset($payment_source['expiry']) ? $payment_source['expiry'] : '');
									
										break;
									}
								
									if ($authorization_status == 'CREATED') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'created';
									}

									if ($authorization_status == 'CAPTURED') {
										$this->error['warning'] = sprintf($this->language->get('error_authorization_captured'), $this->url->link('information/contact', '', true));
									}
						
									if ($authorization_status == 'DENIED') {
										$transaction_status = 'denied';
							
										$this->error['warning'] = $this->language->get('error_authorization_denied');
									}
						
									if ($authorization_status == 'EXPIRED') {
										$this->error['warning'] = sprintf($this->language->get('error_authorization_expired'), $this->url->link('information/contact', '', true));
									}
						
									if ($authorization_status == 'PENDING') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'pending';
									}
														
									if (($authorization_status == 'CREATED') || ($authorization_status == 'DENIED') || ($authorization_status == 'PENDING')) {
										$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
										$paypal_order_data = array(
											'order_id' => $this->session->data['order_id'],
											'paypal_order_id' => $paypal_order_id,
											'transaction_id' => $authorization_id,
											'transaction_status' => $transaction_status,
											'payment_method' => $payment_method,
											'vault_id' => $vault_id,
											'vault_customer_id' => $vault_customer_id,
											'card_type' => $card_type,
											'card_nice_type' => $card_nice_type,
											'card_last_digits' => $card_last_digits,
											'card_expiry' => $card_expiry,
											'total' => $order_info['total'],
											'currency_code' => $order_info['currency_code'],
											'environment' => $environment
										);

										$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
										if ($vault_id && $this->customer->isLogged()) {
											$customer_id = $this->customer->getId();
										
											$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
											if (!$paypal_customer_token_info) {
												$paypal_customer_token_data = array(
													'customer_id' => $customer_id,
													'payment_method' => $payment_method,
													'vault_id' => $vault_id,
													'vault_customer_id' => $vault_customer_id,
													'card_type' => $card_type,
													'card_nice_type' => $card_nice_type,
													'card_last_digits' => $card_last_digits,
													'card_expiry' => $card_expiry
												);
					
												$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
											}
										
											$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
										}
									}
									
									if ($order_status_id) {
										$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
										$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
									}
								
									if (($authorization_status == 'CREATED') || ($authorization_status == 'PENDING')) {
										$recurring_products = $this->cart->getRecurringProducts();
					
										foreach ($recurring_products as $recurring_product) {
											$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_info, $paypal_order_data);
										} 
									}
													
									if (($authorization_status == 'CREATED') || ($authorization_status == 'PARTIALLY_CAPTURED') || ($authorization_status == 'PARTIALLY_CREATED') || ($authorization_status == 'PENDING')) {
										$this->response->redirect($this->url->link('checkout/success', '', true));
									}
								}
							} else {
								$this->model_extension_payment_paypal->log($result, 'Capture Order');
					
								if (isset($result['purchase_units'][0]['payments']['captures'][0]['status']) && isset($result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'])) {
									$capture_id = $result['purchase_units'][0]['payments']['captures'][0]['id'];
									$capture_status = $result['purchase_units'][0]['payments']['captures'][0]['status'];
									$seller_protection_status = $result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'];
									
									$order_status_id = 0;
									$transaction_status = '';
									$payment_method = '';
							
									if (!$this->cart->hasShipping()) {
										$seller_protection_status = 'NOT_ELIGIBLE';
									}
									
									foreach ($result['payment_source'] as $payment_source_key => $payment_source) {
										$payment_method = $payment_source_key;
										$vault_id = (isset($payment_source['attributes']['vault']['id']) ? $payment_source['attributes']['vault']['id'] : '');
										$vault_customer_id = (isset($payment_source['attributes']['vault']['customer']['id']) ? $payment_source['attributes']['vault']['customer']['id'] : '');
										$card_last_digits = (isset($payment_source['last_digits']) ? $payment_source['last_digits'] : '');
										$card_expiry = (isset($payment_source['expiry']) ? $payment_source['expiry'] : '');
									
										break;
									}
																						
									if ($capture_status == 'COMPLETED') {
										$order_status_id = $setting['order_status']['completed']['id'];
										$transaction_status = 'completed';
									}
						
									if ($capture_status == 'DECLINED') {
										$transaction_status = 'denied';
							
										$this->error['warning'] = $this->language->get('error_capture_declined');
									}
						
									if ($capture_status == 'FAILED') {
										$this->error['warning'] = sprintf($this->language->get('error_capture_failed'), $this->url->link('information/contact', '', true));
									}
						
									if ($capture_status == 'PENDING') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'pending';
									}
														
									if (($capture_status == 'COMPLETED') || ($capture_status == 'DECLINED') || ($capture_status == 'PENDING')) {
										$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
										$paypal_order_data = array(
											'order_id' => $this->session->data['order_id'],
											'paypal_order_id' => $paypal_order_id,
											'transaction_id' => $capture_id,
											'transaction_status' => $transaction_status,
											'payment_method' => $payment_method,
											'vault_id' => $vault_id,
											'vault_customer_id' => $vault_customer_id,
											'card_type' => $card_type,
											'card_nice_type' => $card_nice_type,
											'card_last_digits' => $card_last_digits,
											'card_expiry' => $card_expiry,
											'total' => $order_info['total'],
											'currency_code' => $order_info['currency_code'],
											'environment' => $environment
										);

										$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
										if ($vault_id && $this->customer->isLogged()) {
											$customer_id = $this->customer->getId();
										
											$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
											if (!$paypal_customer_token_info) {
												$paypal_customer_token_data = array(
													'customer_id' => $customer_id,
													'payment_method' => $payment_method,
													'vault_id' => $vault_id,
													'vault_customer_id' => $vault_customer_id,
													'card_type' => $card_type,
													'card_nice_type' => $card_nice_type,
													'card_last_digits' => $card_last_digits,
													'card_expiry' => $card_expiry
												);
					
												$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
											}
										
											$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
										}
									}
									
									if ($order_status_id) {
										$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
										$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
									}
								
									if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
										$recurring_products = $this->cart->getRecurringProducts();
					
										foreach ($recurring_products as $recurring_product) {
											$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_info, $paypal_order_data);
										} 
									}
						
									if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
										$this->response->redirect($this->url->link('checkout/success', '', true));
									}
								}
							}
						}
					}
				}
			}
		
			unset($this->session->data['paypal_order_id']);
			unset($this->session->data['paypal_payment_token']);
			
			if ($this->error) {								
				$this->session->data['error'] = $this->error['warning'];
				
				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			}
		}	
		
		$this->response->redirect($this->url->link('checkout/cart', '', true));
	}
	
	public function paymentAddress() {
		$this->load->language('extension/payment/paypal');
		
		$data['guest'] = isset($this->session->data['guest']) ? $this->session->data['guest'] : array();
		$data['payment_address'] = isset($this->session->data['payment_address']) ? $this->session->data['payment_address'] : array();
		
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/payment_address', $data));
	}
	
	public function shippingAddress() {
		$this->load->language('extension/payment/paypal');
		
		$data['shipping_address'] = isset($this->session->data['shipping_address']) ? $this->session->data['shipping_address'] : array();
				
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/shipping_address', $data));
	}
	
	public function confirmShipping() {
		$this->validateShipping($this->request->post['shipping_method']);

		$this->response->redirect($this->url->link('extension/payment/paypal/confirmOrder', '', true));
	}
	
	public function confirmPaymentAddress() {
		$this->load->language('extension/payment/paypal');
		
		$data['url'] = '';
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validatePaymentAddress()) {			
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['email'] = $this->request->post['email'];
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];

			if (isset($this->request->post['custom_field']['account'])) {
				$this->session->data['guest']['custom_field'] = $this->request->post['custom_field']['account'];
			} else {
				$this->session->data['guest']['custom_field'] = array();
			}

			$this->session->data['payment_address']['firstname'] = $this->request->post['firstname'];
			$this->session->data['payment_address']['lastname'] = $this->request->post['lastname'];
			$this->session->data['payment_address']['company'] = $this->request->post['company'];
			$this->session->data['payment_address']['address_1'] = $this->request->post['address_1'];
			$this->session->data['payment_address']['address_2'] = $this->request->post['address_2'];
			$this->session->data['payment_address']['postcode'] = $this->request->post['postcode'];
			$this->session->data['payment_address']['city'] = $this->request->post['city'];
			$this->session->data['payment_address']['country_id'] = $this->request->post['country_id'];
			$this->session->data['payment_address']['zone_id'] = $this->request->post['zone_id'];

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info) {
				$this->session->data['payment_address']['country'] = $country_info['name'];
				$this->session->data['payment_address']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['payment_address']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['payment_address']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['payment_address']['country'] = '';
				$this->session->data['payment_address']['iso_code_2'] = '';
				$this->session->data['payment_address']['iso_code_3'] = '';
				$this->session->data['payment_address']['address_format'] = '';
			}

			if (isset($this->request->post['custom_field']['address'])) {
				$this->session->data['payment_address']['custom_field'] = $this->request->post['custom_field']['address'];
			} else {
				$this->session->data['payment_address']['custom_field'] = array();
			}

			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

			if ($zone_info) {
				$this->session->data['payment_address']['zone'] = $zone_info['name'];
				$this->session->data['payment_address']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['payment_address']['zone'] = '';
				$this->session->data['payment_address']['zone_code'] = '';
			}
			
			$data['url'] = $this->url->link('extension/payment/paypal/confirmOrder', '', true);
		}

		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function confirmShippingAddress() {
		$this->load->language('extension/payment/paypal');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateShippingAddress()) {			
			$this->session->data['shipping_address']['firstname'] = $this->request->post['firstname'];
			$this->session->data['shipping_address']['lastname'] = $this->request->post['lastname'];
			$this->session->data['shipping_address']['company'] = $this->request->post['company'];
			$this->session->data['shipping_address']['address_1'] = $this->request->post['address_1'];
			$this->session->data['shipping_address']['address_2'] = $this->request->post['address_2'];
			$this->session->data['shipping_address']['postcode'] = $this->request->post['postcode'];
			$this->session->data['shipping_address']['city'] = $this->request->post['city'];
			$this->session->data['shipping_address']['country_id'] = $this->request->post['country_id'];
			$this->session->data['shipping_address']['zone_id'] = $this->request->post['zone_id'];

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

			if ($country_info) {
				$this->session->data['shipping_address']['country'] = $country_info['name'];
				$this->session->data['shipping_address']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['shipping_address']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['shipping_address']['country'] = '';
				$this->session->data['shipping_address']['iso_code_2'] = '';
				$this->session->data['shipping_address']['iso_code_3'] = '';
				$this->session->data['shipping_address']['address_format'] = '';
			}

			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

			if ($zone_info) {
				$this->session->data['shipping_address']['zone'] = $zone_info['name'];
				$this->session->data['shipping_address']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['shipping_address']['zone'] = '';
				$this->session->data['shipping_address']['zone_code'] = '';
			}

			if (isset($this->request->post['custom_field'])) {
				$this->session->data['shipping_address']['custom_field'] = $this->request->post['custom_field']['address'];
			} else {
				$this->session->data['shipping_address']['custom_field'] = array();
			}
			
			$data['url'] = $this->url->link('extension/payment/paypal/confirmOrder', '', true);
		}
		
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function deleteCustomerToken() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');

		if ($this->customer->isLogged() && isset($this->request->post['index'])) {
			$card_token_index = $this->request->post['index'];
			
			$card_customer_tokens = $this->model_extension_payment_paypal->getPayPalCustomerTokens($this->customer->getId(), 'card');
			
			if (!empty($card_customer_tokens[$card_token_index]['vault_id'])) {
				$vault_id = $card_customer_tokens[$card_token_index]['vault_id'];
				
				$_config = new Config();
				$_config->load('paypal');
			
				$config_setting = $_config->get('paypal_setting');
		
				$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
		
				$client_id = $this->config->get('payment_paypal_client_id');
				$secret = $this->config->get('payment_paypal_secret');
				$merchant_id = $this->config->get('payment_paypal_merchant_id');
				$environment = $this->config->get('payment_paypal_environment');
				$partner_id = $setting['partner'][$environment]['partner_id'];
				$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
																	
				require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
				$paypal_info = array(
					'partner_id' => $partner_id,
					'client_id' => $client_id,
					'secret' => $secret,
					'environment' => $environment,
					'partner_attribution_id' => $partner_attribution_id
				);
				
				if (isset($this->session->data['paypal_client_metadata_id'])) {
					$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
				}
		
				$paypal = new PayPal($paypal_info);
			
				$token_info = array(
					'grant_type' => 'client_credentials'
				);	
				
				$result = $paypal->setAccessToken($token_info);
				
				$result = $paypal->deletePaymentToken($vault_id);
				
				if ($paypal->hasErrors()) {
					$error_messages = array();
				
					$errors = $paypal->getErrors();
								
					foreach ($errors as $error) {
						if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
							$error['message'] = $this->language->get('error_timeout');
						}
				
						if (isset($error['details'][0]['description'])) {
							$error_messages[] = $error['details'][0]['description'];
						} elseif (isset($error['message'])) {
							$error_messages[] = $error['message'];
						}
									
						$this->model_extension_payment_paypal->log($error, $error['message']);
					}
					
					$this->error['warning'] = implode(' ', $error_messages);
				}

				if (!empty($this->error['warning'])) {
					$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
				
				
				if (!empty($this->error['warning'])) {
					$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
				}
						
				if ($result && !$this->error) {
					$this->model_extension_payment_paypal->deletePayPalCustomerToken($this->customer->getId(), 'card', $vault_id);
					
					$data['success'] = true;
				}
			}
		}
					
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
		
	public function fastlaneModal() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$data['shipping_required'] = $this->cart->hasShipping();
				
		$this->response->setOutput($this->load->view('extension/payment/paypal/fastlane_modal', $data));
	}

	public function fastlaneShipping() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		if (!empty($this->request->post['authentication_state']) && ($this->request->post['authentication_state'] == 'succeeded') && !empty($this->request->post['profile_data'])) {
			$profile_data = $this->request->post['profile_data'];
			
			$data['guest']['telephone'] = (isset($profile_data['shippingAddress']['phoneNumber']['nationalNumber']) ? $profile_data['shippingAddress']['phoneNumber']['nationalNumber'] : '');
									
			$data['shipping_address']['firstname'] = (isset($profile_data['shippingAddress']['name']['firstName']) ? $profile_data['shippingAddress']['name']['firstName'] : '');
			$data['shipping_address']['lastname'] = (isset($profile_data['shippingAddress']['name']['lastName']) ? $profile_data['shippingAddress']['name']['lastName'] : '');
			$data['shipping_address']['address_1'] = (isset($profile_data['shippingAddress']['address']['addressLine1']) ? $profile_data['shippingAddress']['address']['addressLine1'] : '');
			$data['shipping_address']['address_2'] = (isset($profile_data['shippingAddress']['address']['addressLine2']) ? $profile_data['shippingAddress']['address']['addressLine2'] : '');
			$data['shipping_address']['city'] = (isset($profile_data['shippingAddress']['address']['adminArea2']) ? $profile_data['shippingAddress']['address']['adminArea2'] : '');
			$data['shipping_address']['postcode'] = (isset($profile_data['shippingAddress']['address']['postalCode']) ? $profile_data['shippingAddress']['address']['postalCode'] : '');
			$data['shipping_address']['country'] = (isset($profile_data['shippingAddress']['address']['countryCode']) ? $profile_data['shippingAddress']['address']['countryCode'] : '');
			$data['shipping_address']['zone'] = (isset($profile_data['shippingAddress']['address']['adminArea1']) ? $profile_data['shippingAddress']['address']['adminArea1'] : '');
			
			if (isset($profile_data['shippingAddress']['address']['countryCode'])) {
				$country_info = $this->model_extension_payment_paypal->getCountryByCode($profile_data['shippingAddress']['address']['countryCode']);
			
				if ($country_info) {
					$data['shipping_address']['country_id'] = $country_info['country_id'];
					$data['shipping_address']['country'] = $country_info['name'];
					
					if (isset($profile_data['shippingAddress']['address']['adminArea1'])) {
						$zone_info = $this->model_extension_payment_paypal->getZoneByCode($country_info['country_id'], $profile_data['shippingAddress']['address']['adminArea1']);
			
						if ($zone_info) {
							$data['shipping_address']['zone_id'] = $zone_info['zone_id'];
							$data['shipping_address']['zone'] = $zone_info['name'];
						}
					}
				}
			}
		}
		
		$data['error'] = $this->error;
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/fastlane_shipping', $data));
	}
	
	public function fastlanePayment() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		if (isset($this->request->post['payment_token'])) {
			$this->session->data['paypal_payment_token'] = $this->request->post['payment_token'];
			
			$data['url'] = $this->url->link('extension/payment/paypal/confirmOrder', '', true);
		} else {	
			$data['url'] = $this->url->link('checkout/cart', '', true);
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}
		
		// check checkout can continue due to stock checks or vouchers
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$data['url'] = $this->url->link('checkout/cart', '', true);
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}

		// if user not logged in check that the guest checkout is allowed
		if (!$this->customer->isLogged() && (!$this->config->get('config_checkout_guest') || $this->config->get('config_customer_price') || $this->cart->hasDownload() || $this->cart->hasRecurringProducts())) {
			$data['url'] = $this->url->link('checkout/cart', '', true);
			
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($data));
		}

		$data['error'] = $this->error;
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/fastlane_payment', $data));
	}
	
	public function confirmFastlaneCustomer() {
		$this->load->language('extension/payment/paypal');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateFastlaneCustomer()) {			
			$this->session->data['guest']['customer_id'] = 0;
			$this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
			$this->session->data['guest']['firstname'] = '';
			$this->session->data['guest']['lastname'] = '';
			$this->session->data['guest']['email'] = $this->request->post['email'];
			$this->session->data['guest']['telephone'] = '';
			$this->session->data['guest']['custom_field'] = [];
																		
			$this->session->data['shipping_address']['firstname'] = '';
			$this->session->data['shipping_address']['lastname'] = '';
			$this->session->data['shipping_address']['company'] = '';
			$this->session->data['shipping_address']['address_1'] = '';
			$this->session->data['shipping_address']['address_2'] = '';
			$this->session->data['shipping_address']['city'] = '';
			$this->session->data['shipping_address']['postcode'] = '';
			$this->session->data['shipping_address']['country'] = '';
			$this->session->data['shipping_address']['country_id'] = 0;
			$this->session->data['shipping_address']['address_format'] = '';
			$this->session->data['shipping_address']['zone'] = '';
			$this->session->data['shipping_address']['zone_id'] = 0;
			$this->session->data['shipping_address']['custom_field'] = array();
			
			$this->session->data['payment_address']['firstname'] = '';
			$this->session->data['payment_address']['lastname'] = '';
			$this->session->data['payment_address']['company'] = '';
			$this->session->data['payment_address']['address_1'] = '';
			$this->session->data['payment_address']['address_2'] = '';
			$this->session->data['payment_address']['city'] = '';
			$this->session->data['payment_address']['postcode'] = '';
			$this->session->data['payment_address']['country'] = '';
			$this->session->data['payment_address']['country_id'] = 0;
			$this->session->data['payment_address']['address_format'] = '';
			$this->session->data['payment_address']['zone'] = '';
			$this->session->data['payment_address']['zone_id'] = 0;
			$this->session->data['payment_address']['custom_field'] = array();
						
			$data['success'] = true;
		}

		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
			
	public function confirmFastlaneShipping() {
		$this->load->language('extension/payment/paypal');
						
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {			
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
																		
			$this->session->data['shipping_address']['firstname'] = $this->request->post['firstname'];
			$this->session->data['shipping_address']['lastname'] = $this->request->post['lastname'];
			$this->session->data['shipping_address']['company'] = '';
			$this->session->data['shipping_address']['address_1'] = $this->request->post['address_1'];
			$this->session->data['shipping_address']['address_2'] = $this->request->post['address_2'];
			$this->session->data['shipping_address']['city'] = $this->request->post['city'];
			$this->session->data['shipping_address']['postcode'] = $this->request->post['postcode'];
			$this->session->data['shipping_address']['country'] = '';
			$this->session->data['shipping_address']['country_id'] = 0;
			$this->session->data['shipping_address']['address_format'] = '';
			$this->session->data['shipping_address']['zone'] = '';
			$this->session->data['shipping_address']['zone_id'] = 0;
			$this->session->data['shipping_address']['custom_field'] = array();
			
			$data['shipping_address'] = array();

			$data['shipping_address']['name']['firstName'] = $this->request->post['firstname'];
			$data['shipping_address']['name']['lastName'] = $this->request->post['lastname'];
			$data['shipping_address']['address']['phone'] = $this->request->post['telephone'];
			$data['shipping_address']['address']['addressLine1'] = $this->request->post['address_1'];
			$data['shipping_address']['address']['addressLine2'] = $this->request->post['address_2'];
			$data['shipping_address']['address']['adminArea2'] = $this->request->post['city'];
			$data['shipping_address']['address']['postalCode'] = $this->request->post['postcode'];
									
			if (isset($this->request->post['country_id'])) {
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
				if ($country_info) {
					$this->session->data['payment_address']['country_id'] = $country_info['country_id'];
					$this->session->data['payment_address']['country'] = $country_info['name'];
										
					$this->session->data['shipping_address']['country_id'] = $country_info['country_id'];
					$this->session->data['shipping_address']['country'] = $country_info['name'];
					$this->session->data['shipping_address']['address_format'] = $country_info['address_format'];
					
					$data['shipping_address']['address']['countryCode'] = $country_info['iso_code_2'];
													
					if (isset($this->request->post['zone_id'])) {
						$this->load->model('localisation/zone');
						
						$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			
						if ($zone_info) {
							$this->session->data['payment_address']['zone_id'] = $zone_info['zone_id'];
							$this->session->data['payment_address']['zone'] = $zone_info['name'];
							
							$this->session->data['shipping_address']['zone_id'] = $zone_info['zone_id'];
							$this->session->data['shipping_address']['zone'] = $zone_info['name'];
							
							$data['shipping_address']['address']['adminArea1'] = $zone_info['name'];
						}
					}
				}
			}
				
			$data['success'] = true;
		}
		
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function confirmFastlanePayment() {
		$this->load->language('extension/payment/paypal');
				
		if (!empty($this->request->post['payment_token'])) {			
			$this->session->data['paypal_payment_token'] = $this->request->post['payment_token'];
			
			if (!empty($this->request->post['payment_source'])) {		
				$payment_source = $this->request->post['payment_source'];
				
				if (isset($payment_source['card']['name'])) {
					$payment_name = explode(' ', $payment_source['card']['name']);
					$payment_firstname = $payment_name[0];
					unset($payment_name[0]);
					$payment_lastname = implode(' ', $payment_name);
				}
							
				if (empty($this->session->data['guest']['firstname'])) {
					$this->session->data['guest']['firstname'] = (isset($payment_firstname) ? $payment_firstname : '');
				}
				
				if (empty($this->session->data['guest']['lastname'])) {
					$this->session->data['guest']['lastname'] = (isset($payment_lastname) ? $payment_lastname : '');
				}
				
				$this->session->data['payment_address']['firstname'] = (isset($payment_firstname) ? $payment_firstname : '');
				$this->session->data['payment_address']['lastname'] = (isset($payment_lastname) ? $payment_lastname : '');
				$this->session->data['payment_address']['company'] = '';
				$this->session->data['payment_address']['address_1'] = (isset($payment_source['card']['billingAddress']['streetAddress']) ? $payment_source['card']['billingAddress']['streetAddress'] : '');
				$this->session->data['payment_address']['address_2'] = (isset($payment_source['card']['billingAddress']['extendedAddress']) ? $payment_source['card']['billingAddress']['extendedAddress'] : '');
				$this->session->data['payment_address']['city'] = (isset($payment_source['card']['billingAddress']['locality']) ? $payment_source['card']['billingAddress']['locality'] : '');
				$this->session->data['payment_address']['postcode'] = (isset($payment_source['card']['billingAddress']['postalCode']) ? $payment_source['card']['billingAddress']['postalCode'] : '');
				$this->session->data['payment_address']['country'] = '';
				$this->session->data['payment_address']['country_id'] = 0;
				$this->session->data['payment_address']['address_format'] = '';
				$this->session->data['payment_address']['zone'] = '';
				$this->session->data['payment_address']['zone_id'] = 0;
				$this->session->data['payment_address']['custom_field'] = array();
			
				if (isset($payment_source['card']['billingAddress']['countryCodeAlpha2'])) {
					$this->load->model('extension/payment/paypal');
				
					$country_info = $this->model_extension_payment_paypal->getCountryByCode($payment_source['card']['billingAddress']['countryCodeAlpha2']);
			
					if ($country_info) {
						$this->session->data['payment_address']['country'] = $country_info['name'];
						$this->session->data['payment_address']['country_id'] = $country_info['country_id'];
						$this->session->data['payment_address']['address_format'] = $country_info['address_format'];
				
						if (isset($payment_source['card']['billingAddress']['region'])) {
							$zone_info = $this->model_extension_payment_paypal->getZoneByCode($country_info['country_id'], $payment_source['card']['billingAddress']['region']);
			
							if ($zone_info) {
								$this->session->data['payment_address']['zone_id'] = $zone_info['zone_id'];
								$this->session->data['payment_address']['zone'] = $zone_info['name'];
							}
						}
					}
				}
				
				$this->session->data['payment_method'] = array(
					'code'       => 'paypal_fastlane',
					'title'      => $this->language->get('text_paypal_fastlane_title'),
					'terms'      => '',
					'sort_order' => $this->config->get('payment_paypal_sort_order')
				); 
				
				$data['url'] = $this->url->link('extension/payment/paypal/confirmOrder', '', true);				
			} else {
				$data['url'] = $this->url->link('extension/payment/paypal/completeOrder', '', true);
			}
		}

		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function getFastlaneData() {
		$this->load->language('extension/payment/paypal');
						
		if ($this->cart->hasShipping()) {
			$data['shipping_address'] = array();
					
			$data['shipping_address']['name']['firstName'] = $this->session->data['shipping_address']['firstname'];
			$data['shipping_address']['name']['lastName'] = $this->session->data['shipping_address']['lastname'];
			$data['shipping_address']['address']['phone'] = $this->session->data['guest']['telephone'];
			$data['shipping_address']['address']['addressLine1'] = $this->session->data['shipping_address']['address_1'];
			$data['shipping_address']['address']['addressLine2'] = $this->session->data['shipping_address']['address_2'];
			$data['shipping_address']['address']['adminArea2'] = $this->session->data['shipping_address']['city'];
			$data['shipping_address']['address']['postalCode'] = $this->session->data['shipping_address']['postcode'];
								
			if (isset($this->session->data['shipping_address']['country_id'])) {
				$this->load->model('localisation/country');
				
				$country_info = $this->model_localisation_country->getCountry($this->session->data['shipping_address']['country_id']);
			
				if ($country_info) {
					$data['shipping_address']['address']['countryCode'] = $country_info['iso_code_2'];
					
					if (isset($this->session->data['shipping_address']['zone_id'])) {
						$this->load->model('localisation/zone');
						
						$zone_info = $this->model_localisation_zone->getZone($this->session->data['shipping_address']['zone_id']);
			
						if ($zone_info) {							
							$data['shipping_address']['address']['adminArea1'] = $zone_info['code'];
						}
					}
				}
			}
		}
		
		$data['billing_address'] = array();
										
		$data['billing_address']['addressLine1'] = $this->session->data['payment_address']['address_1'];
		$data['billing_address']['addressLine2'] = $this->session->data['payment_address']['address_2'];
		$data['billing_address']['adminArea2'] = $this->session->data['payment_address']['city'];
		$data['billing_address']['postalCode'] = $this->session->data['payment_address']['postcode'];
		
		if (isset($this->session->data['payment_address']['country_id'])) {
			$this->load->model('localisation/country');
				
			$country_info = $this->model_localisation_country->getCountry($this->session->data['payment_address']['country_id']);
			
			if ($country_info) {
				$data['billing_address']['countryCode'] = $country_info['iso_code_2'];
					
				if (isset($this->session->data['payment_address']['zone_id'])) {
					$this->load->model('localisation/zone');
						
					$zone_info = $this->model_localisation_zone->getZone($this->session->data['payment_address']['zone_id']);
			
					if ($zone_info) {							
						$data['billing_address']['adminArea1'] = $zone_info['code'];
					}
				}
			}
		}
		
		$data['cardholder_name'] = array();
		
		$data['cardholder_name']['firstName'] = $this->session->data['payment_address']['firstname'];
		$data['cardholder_name']['lastName'] = $this->session->data['payment_address']['lastname'];
		$data['cardholder_name']['fullName'] = $this->session->data['payment_address']['firstname'];
		$data['cardholder_name']['fullName'] .= ($this->session->data['payment_address']['lastname'] ? (' ' . $this->session->data['payment_address']['lastname']) : '');
					
		$data['success'] = true;
				
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
			
	public function addOrderHistory() {
		if (!empty($this->request->get['order_history_token']) && !empty($this->request->post['order_id']) && !empty($this->request->post['order_status_id'])) {
			$this->load->language('extension/payment/paypal');
		
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$order_status_id = $this->request->post['order_status_id'];
			
			if (!empty($this->request->post['comment'])) {
				$comment = $this->request->post['comment'];
			} else {
				$comment = '';
			}
			
			if (!empty($this->request->post['notify'])) {
				$notify = $this->request->post['notify'];
			} else {
				$notify = false;
			}
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
					
			if (hash_equals($setting['general']['order_history_token'], $this->request->get['order_history_token'])) {		
				$this->load->model('checkout/order');

				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, $comment, $notify);
			
				$data['success'] = $this->language->get('success_order');
			}	
		}
							
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function callback() {		
		if (!empty($this->request->get['callback_token'])) {
			$this->load->language('extension/payment/paypal');
		
			$this->load->model('extension/payment/paypal');
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
					
			if (hash_equals($setting['general']['callback_token'], $this->request->get['callback_token']) && !empty($this->session->data['order_id']) && !empty($this->session->data['paypal_order_id']) && isset($this->session->data['paypal_card_token_index'])) {
				$order_id = $this->session->data['order_id'];
				$paypal_order_id = $this->session->data['paypal_order_id'];
				$card_token_index = $this->session->data['paypal_card_token_index'];
				
				$card_customer_tokens = $this->model_extension_payment_paypal->getPayPalCustomerTokens($this->customer->getId(), 'card');

				if (!empty($card_customer_tokens[$card_token_index]['vault_id'])) {
					$vault_id = $card_customer_tokens[$card_token_index]['vault_id'];
					$vault_customer_id = $card_customer_tokens[$card_token_index]['vault_customer_id'];
					$card_type = $card_customer_tokens[$card_token_index]['card_type'];
					$card_nice_type = $card_customer_tokens[$card_token_index]['card_nice_type'];
					$card_last_digits = $card_customer_tokens[$card_token_index]['card_last_digits'];
					$card_expiry = $card_customer_tokens[$card_token_index]['card_expiry'];
				
					$client_id = $this->config->get('payment_paypal_client_id');
					$secret = $this->config->get('payment_paypal_secret');
					$environment = $this->config->get('payment_paypal_environment');
					$partner_id = $setting['partner'][$environment]['partner_id'];
					$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
					$vault_status = $setting['general']['vault_status'];
					$transaction_method = $setting['general']['transaction_method'];
			
					require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
					$paypal_info = array(
						'partner_id' => $partner_id,
						'client_id' => $client_id,
						'secret' => $secret,
						'environment' => $environment,
						'partner_attribution_id' => $partner_attribution_id
					);
					
					if (isset($this->session->data['paypal_client_metadata_id'])) {
						$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
					}
		
					$paypal = new PayPal($paypal_info);
		
					$token_info = array(
						'grant_type' => 'client_credentials'
					);	
						
					$paypal->setAccessToken($token_info);
					
					$paypal_order_info = $paypal->getOrder($paypal_order_id);
					
					if ($paypal->hasErrors()) {
						$error_messages = array();
				
						$errors = $paypal->getErrors();
								
						foreach ($errors as $error) {
							if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
								$error['message'] = $this->language->get('error_timeout');
							}
					
							if (isset($error['details'][0]['description'])) {
								$error_messages[] = $error['details'][0]['description'];
							} elseif (isset($error['message'])) {
								$error_messages[] = $error['message'];
							}
					
							$this->model_extension_payment_paypal->log($error, $error['message']);
						}
		
						$this->error['warning'] = implode(' ', $error_messages);
					}
							
					if (isset($paypal_order_info['payment_source']['card']) && !$this->error) {
						$this->model_extension_payment_paypal->log($paypal_order_info['payment_source']['card'], 'Card');
						
						$liability_shift = (isset($paypal_order_info['payment_source']['card']['authentication_result']['liability_shift']) ? $paypal_order_info['payment_source']['card']['authentication_result']['liability_shift'] : '');
						$enrollment_status = (isset($paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status']) ? $paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['enrollment_status'] : '');
						$authentication_status = (isset($paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status']) ? $paypal_order_info['payment_source']['card']['authentication_result']['three_d_secure']['authentication_status'] : '');
								
						if ($enrollment_status == 'Y') {
							if (($authentication_status == 'N') && !$setting['card']['secure_scenario']['failed_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['failed_authentication']['error']);
							}
						
							if (($authentication_status == 'R') && !$setting['card']['secure_scenario']['rejected_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['rejected_authentication']['error']);
							}
						
							if (($authentication_status == 'A') && !$setting['card']['secure_scenario']['attempted_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['attempted_authentication']['error']);
							}
						
							if (($authentication_status == 'U') && !$setting['card']['secure_scenario']['unable_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['unable_authentication']['error']);
							}
						
							if (($authentication_status == 'C') && !$setting['card']['secure_scenario']['challenge_authentication']) {
								$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['challenge_authentication']['error']);
							}
						}
					
						if (($enrollment_status == 'N') && !$setting['card']['secure_scenario']['card_ineligible']) {
							$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['card_ineligible']['error']);
						}
					
						if (($enrollment_status == 'U') && !$setting['card']['secure_scenario']['system_unavailable']) {
							$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['system_unavailable']['error']);
						}
					
						if (($enrollment_status == 'B') && !$setting['card']['secure_scenario']['system_bypassed']) {
							$this->error['warning'] = $this->language->get($setting['card_secure_scenario']['system_bypassed']['error']);
						}
					}
		
					if (!empty($this->error['warning'])) {
						$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
					}
				
					if (!$this->error) {				
						if ($transaction_method == 'authorize') {
							$result = $paypal->setOrderAuthorize($paypal_order_id);
						} else {
							$result = $paypal->setOrderCapture($paypal_order_id);
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
								} elseif (isset($error['message'])) {
									$error_messages[] = $error['message'];
								}
					
								$this->model_extension_payment_paypal->log($error, $error['message']);
							}
				
							$this->error['warning'] = implode(' ', $error_messages);
						}
			
						if (!empty($this->error['warning'])) {
							$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', '', true));
						}
			
						if (!$this->error) {	
							$this->load->model('checkout/order');
				
							$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
						
							if ($transaction_method == 'authorize') {
								$this->model_extension_payment_paypal->log($result, 'Authorize Order');
					
								if (isset($result['purchase_units'][0]['payments']['authorizations'][0]['status']) && isset($result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'])) {
									$authorization_id = $result['purchase_units'][0]['payments']['authorizations'][0]['id'];
									$authorization_status = $result['purchase_units'][0]['payments']['authorizations'][0]['status'];
									$seller_protection_status = $result['purchase_units'][0]['payments']['authorizations'][0]['seller_protection']['status'];							
									$order_status_id = 0;
									$transaction_status = '';
									$payment_method = 'card';
								
									if (!$this->cart->hasShipping()) {
										$seller_protection_status = 'NOT_ELIGIBLE';
									}
								
									if ($authorization_status == 'CREATED') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'created';
									}

									if ($authorization_status == 'CAPTURED') {
										$this->error['warning'] = sprintf($this->language->get('error_authorization_captured'), $this->url->link('information/contact', '', true));
									}
						
									if ($authorization_status == 'DENIED') {
										$transaction_status = 'denied';
							
										$this->error['warning'] = $this->language->get('error_authorization_denied');
									}
						
									if ($authorization_status == 'EXPIRED') {
										$this->error['warning'] = sprintf($this->language->get('error_authorization_expired'), $this->url->link('information/contact', '', true));
									}
						
									if ($authorization_status == 'PENDING') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'pending';
									}
														
									if (($authorization_status == 'CREATED') || ($authorization_status == 'DENIED') || ($authorization_status == 'PENDING')) {
										$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
										$paypal_order_data = array(
											'order_id' => $this->session->data['order_id'],
											'paypal_order_id' => $paypal_order_id,
											'transaction_id' => $authorization_id,
											'transaction_status' => $transaction_status,
											'payment_method' => $payment_method,
											'vault_id' => $vault_id,
											'vault_customer_id' => $vault_customer_id,
											'card_type' => $card_type,
											'card_nice_type' => $card_nice_type,
											'card_last_digits' => $card_last_digits,
											'card_expiry' => $card_expiry,
											'total' => $order_info['total'],
											'currency_code' => $order_info['currency_code'],
											'environment' => $environment
										);

										$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
										if ($vault_id && $this->customer->isLogged()) {
											$customer_id = $this->customer->getId();
										
											$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
											if (!$paypal_customer_token_info) {
												$paypal_customer_token_data = array(
													'customer_id' => $customer_id,
													'payment_method' => $payment_method,
													'vault_id' => $vault_id,
													'vault_customer_id' => $vault_customer_id,
													'card_type' => $card_type,
													'card_nice_type' => $card_nice_type,
													'card_last_digits' => $card_last_digits,
													'card_expiry' => $card_expiry
												);
					
												$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
											}
										
											$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
										}
									}
									
									if ($order_status_id) {
										$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
										$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
									}
								
									if (($authorization_status == 'CREATED') || ($authorization_status == 'PENDING')) {
										$recurring_products = $this->cart->getRecurringProducts();
					
										foreach ($recurring_products as $recurring_product) {
											$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_info, $paypal_order_data);
										} 
									}
													
									if (($authorization_status == 'CREATED') || ($authorization_status == 'PARTIALLY_CAPTURED') || ($authorization_status == 'PARTIALLY_CREATED') || ($authorization_status == 'PENDING')) {
										$this->response->redirect($this->url->link('checkout/success', '', true));
									}
								}
							} else {
								$this->model_extension_payment_paypal->log($result, 'Capture Order');
					
								if (isset($result['purchase_units'][0]['payments']['captures'][0]['status']) && isset($result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'])) {
									$capture_id = $result['purchase_units'][0]['payments']['captures'][0]['id'];
									$capture_status = $result['purchase_units'][0]['payments']['captures'][0]['status'];
									$seller_protection_status = $result['purchase_units'][0]['payments']['captures'][0]['seller_protection']['status'];
									
									$order_status_id = 0;
									$transaction_status = '';
									$payment_method = 'card';
																	
									if (!$this->cart->hasShipping()) {
										$seller_protection_status = 'NOT_ELIGIBLE';
									}
																						
									if ($capture_status == 'COMPLETED') {
										$order_status_id = $setting['order_status']['completed']['id'];
										$transaction_status = 'completed';
									}
						
									if ($capture_status == 'DECLINED') {
										$transaction_status = 'denied';
							
										$this->error['warning'] = $this->language->get('error_capture_declined');
									}
						
									if ($capture_status == 'FAILED') {
										$this->error['warning'] = sprintf($this->language->get('error_capture_failed'), $this->url->link('information/contact', '', true));
									}
						
									if ($capture_status == 'PENDING') {
										$order_status_id = $setting['order_status']['pending']['id'];
										$transaction_status = 'pending';
									}
														
									if (($capture_status == 'COMPLETED') || ($capture_status == 'DECLINED') || ($capture_status == 'PENDING')) {
										$this->model_extension_payment_paypal->deletePayPalOrder($this->session->data['order_id']);
									
										$paypal_order_data = array(
											'order_id' => $this->session->data['order_id'],
											'paypal_order_id' => $paypal_order_id,
											'transaction_id' => $capture_id,
											'transaction_status' => $transaction_status,
											'payment_method' => $payment_method,
											'vault_id' => $vault_id,
											'vault_customer_id' => $vault_customer_id,
											'card_type' => $card_type,
											'card_nice_type' => $card_nice_type,
											'card_last_digits' => $card_last_digits,
											'card_expiry' => $card_expiry,
											'total' => $order_info['total'],
											'currency_code' => $order_info['currency_code'],
											'environment' => $environment
										);

										$this->model_extension_payment_paypal->addPayPalOrder($paypal_order_data);
									
										if ($vault_id && $this->customer->isLogged()) {
											$customer_id = $this->customer->getId();
										
											$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
											if (!$paypal_customer_token_info) {
												$paypal_customer_token_data = array(
													'customer_id' => $customer_id,
													'payment_method' => $payment_method,
													'vault_id' => $vault_id,
													'vault_customer_id' => $vault_customer_id,
													'card_type' => $card_type,
													'card_nice_type' => $card_nice_type,
													'card_last_digits' => $card_last_digits,
													'card_expiry' => $card_expiry
												);
					
												$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
											}
										
											$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
										}
									}
									
									if ($order_status_id) {
										$message = sprintf($this->language->get('text_order_message'), $seller_protection_status);
								
										$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $message);
									}
								
									if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
										$recurring_products = $this->cart->getRecurringProducts();
					
										foreach ($recurring_products as $recurring_product) {
											$this->model_extension_payment_paypal->recurringPayment($recurring_product, $order_info, $paypal_order_data);
										} 
									}
						
									if (($capture_status == 'COMPLETED') || ($capture_status == 'PENDING')) {
										$this->response->redirect($this->url->link('checkout/success', '', true));
									}
								}
							}
						}
					}
				}
			
				$this->document->setTitle($this->language->get('text_failure_page_title'));
						
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/home', '', true)
				);
		
				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_cart'),
					'href' => $this->url->link('checkout/cart', '', true)
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_paypal'),
					'href' => $this->url->link('extension/payment/paypal/callback', '', true)
				);
		
				$data['text_title'] = $this->language->get('text_failure_page_title');
				$data['text_message'] = sprintf($this->language->get('text_failure_page_message'), $this->url->link('information/contact', '', true));
		
				if (!empty($this->error['warning'])) {
					$data['text_message'] = $this->error['warning'];
				}
								
				$data['continue'] = $this->url->link('common/home');
		
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['column_right'] = $this->load->controller('common/column_right');
				$data['content_top'] = $this->load->controller('common/content_top');
				$data['content_bottom'] = $this->load->controller('common/content_bottom');
				$data['footer'] = $this->load->controller('common/footer');
				$data['header'] = $this->load->controller('common/header');
		
				$this->response->setOutput($this->load->view('extension/payment/paypal/failure', $data));
			
				return true;
			}
		}
				
		return false;
	}
					
	public function webhook() {
		if (!empty($this->request->get['webhook_token'])) {
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
		
			$webhook_info = json_decode(html_entity_decode(file_get_contents('php://input')), true);
			
			if (hash_equals($setting['general']['webhook_token'], $this->request->get['webhook_token']) && !empty($webhook_info['id']) && !empty($webhook_info['event_type'])) {	
				$this->load->model('extension/payment/paypal');
				$this->load->model('checkout/order');
			
				$this->model_extension_payment_paypal->log($webhook_info, 'Webhook');
			
				$webhook_event_id = $webhook_info['id'];
		
				$client_id = $this->config->get('payment_paypal_client_id');
				$secret = $this->config->get('payment_paypal_secret');
				$environment = $this->config->get('payment_paypal_environment');
				$partner_id = $setting['partner'][$environment]['partner_id'];
				$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
				$vault_status = $setting['general']['vault_status'];
				$transaction_method = $setting['general']['transaction_method'];
			
				require_once DIR_SYSTEM .'library/paypal/paypal.php';
		
				$paypal_info = array(
					'partner_id' => $partner_id,
					'client_id' => $client_id,
					'secret' => $secret,
					'environment' => $environment,
					'partner_attribution_id' => $partner_attribution_id
				);
				
				if (isset($this->session->data['paypal_client_metadata_id'])) {
					$paypal_info['client_metadata_id'] = $this->session->data['paypal_client_metadata_id'];
				}
		
				$paypal = new PayPal($paypal_info);
			
				$token_info = array(
					'grant_type' => 'client_credentials'
				);	
		
				$paypal->setAccessToken($token_info);
			
				$webhook_repeat = 1;
								
				while ($webhook_repeat) {
					$webhook_event = $paypal->getWebhookEvent($webhook_event_id);

					$errors = array();
				
					$webhook_repeat = 0;
			
					if ($paypal->hasErrors()) {
						$error_messages = array();
				
						$errors = $paypal->getErrors();
							
						foreach ($errors as $error) {
							if (isset($error['name']) && ($error['name'] == 'CURLE_OPERATION_TIMEOUTED')) {
								$webhook_repeat = 1;
							}
						}
					}
				}
									
				if (!empty($webhook_event['resource']['invoice_id']) && !$errors) {
					$invoice_id = explode('_', $webhook_event['resource']['invoice_id']);
					$order_id = reset($invoice_id);
					
					$order_info = $this->model_checkout_order->getOrder($order_id);
					
					$paypal_order_info = $this->model_extension_payment_paypal->getPayPalOrder($order_id);

					if ($order_info && $paypal_order_info) {
						$order_status_id = 0;
						$transaction_id = $paypal_order_info['transaction_id'];
						$transaction_status = $paypal_order_info['transaction_status'];

						if ($webhook_event['event_type'] == 'PAYMENT.AUTHORIZATION.CREATED') {
							$order_status_id = $setting['order_status']['pending']['id'];
							$transaction_status = 'created';
						}
		
						if ($webhook_event['event_type'] == 'PAYMENT.AUTHORIZATION.VOIDED') {
							if ($order_info['order_status_id'] != 0) {
								$order_status_id = $setting['order_status']['voided']['id'];
							}
							
							$transaction_status = 'voided';
						}
			
						if ($webhook_event['event_type'] == 'PAYMENT.CAPTURE.COMPLETED') {
							if (!empty($webhook_event['resource']['final_capture'])) {
								$order_status_id = $setting['order_status']['completed']['id'];
								$transaction_status = 'completed';
							} else {
								$order_status_id = $setting['order_status']['partially_captured']['id'];
								$transaction_status = 'partially_captured';
							}
						}
		
						if ($webhook_event['event_type'] == 'PAYMENT.CAPTURE.DENIED') {
							if ($order_info['order_status_id'] != 0) {
								$order_status_id = $setting['order_status']['denied']['id'];
							}
							
							$transaction_status = 'denied';
						}
		
						if ($webhook_event['event_type'] == 'PAYMENT.CAPTURE.PENDING') {
							$order_status_id = $setting['order_status']['pending']['id'];
							$transaction_status = 'pending';
						}
		
						if ($webhook_event['event_type'] == 'PAYMENT.CAPTURE.REFUNDED') {
							$result = $paypal->getPaymentCapture($transaction_id);
							
							if (!empty($result['status'] == 'REFUNDED')) {
								if ($order_info['order_status_id'] != 0) {
									$order_status_id = $setting['order_status']['refunded']['id'];
								}
								
								$transaction_status = 'refunded';
							} elseif (!empty($result['status'] == 'PARTIALLY_REFUNDED')) {
								if ($order_info['order_status_id'] != 0) {
									$order_status_id = $setting['order_status']['partially_refunded']['id'];
								}
								
								$transaction_status = 'partially_refunded';
							}
						}
		
						if ($webhook_event['event_type'] == 'PAYMENT.CAPTURE.REVERSED') {
							if ($order_info['order_status_id'] != 0) {
								$order_status_id = $setting['order_status']['reversed']['id'];
							}
							
							$transaction_status = 'reversed';
						}
		
						if ($webhook_event['event_type'] == 'CHECKOUT.ORDER.COMPLETED') {
							$order_status_id = $setting['order_status']['completed']['id'];
						}
														
						if (isset($webhook_event['resource']['id']) && $transaction_status) {
							$transaction_id = $webhook_event['resource']['id'];
											
							$paypal_order_data = array();
							
							$paypal_order_data['order_id'] = $order_id;
							$paypal_order_data['transaction_status'] = $transaction_status;
												
							if (($transaction_status == 'created') && ($transaction_status == 'completed')) {
								$paypal_order_data['transaction_id'] = $transaction_id;
							}

							$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
						}
						
						if ($order_status_id && ($order_info['order_status_id'] != $order_status_id) && !in_array($order_info['order_status_id'], $setting['final_order_status'])) {					
							$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, '', true);
						}
					}
				}
				
				if (($webhook_event['event_type'] == 'VAULT.PAYMENT-TOKEN.CREATED') && !empty($webhook_info['resource']['id']) && !empty($webhook_info['resource']['customer']['id']) && !empty($webhook_event['resource']['metadata']['order_id']) && !$errors) {
					$paypal_order_id = $webhook_event['resource']['metadata']['order_id'];
					
					$paypal_order_info = $this->model_extension_payment_paypal->getPayPalOrderByPayPalOrderId($paypal_order_id);
										
					if ($paypal_order_info) {
						$order_id = $paypal_order_info['order_id'];
						$payment_method = $paypal_order_info['payment_method'];
						$vault_id = $webhook_event['resource']['id'];
						$vault_customer_id = $webhook_event['resource']['customer']['id'];
						$card_type = $paypal_order_info['card_type'];
						$card_nice_type = $paypal_order_info['card_nice_type'];
						$card_last_digits = (isset($webhook_event['resource']['payment_source']['card']['last_digits']) ? $webhook_event['resource']['payment_source']['card']['last_digits'] : '');
						$card_expiry = (isset($webhook_event['resource']['payment_source']['card']['expiry']) ? $webhook_event['resource']['payment_source']['card']['expiry'] : '');
					
						$paypal_order_data = array(
							'order_id' => $order_id,
							'vault_id' => $vault_id,
							'vault_customer_id' => $vault_customer_id,
							'card_last_digits' => $card_last_digits,
							'card_expiry' => $card_expiry
						);
						
						$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
						
						$order_info = $this->model_checkout_order->getOrder($order_id);
						
						if ($vault_id && !empty($order_info['customer_id'])) {
							$customer_id = $order_info['customer_id'];
										
							$paypal_customer_token_info = $this->model_extension_payment_paypal->getPayPalCustomerToken($customer_id, $payment_method, $vault_id);
								
							if (!$paypal_customer_token_info) {
								$paypal_customer_token_data = array(
									'customer_id' => $customer_id,
									'payment_method' => $payment_method,
									'vault_id' => $vault_id,
									'vault_customer_id' => $vault_customer_id,
									'card_type' => $card_type,
									'card_nice_type' => $card_nice_type,
									'card_last_digits' => $card_last_digits,
									'card_expiry' => $card_expiry
								);
								
								$this->model_extension_payment_paypal->addPayPalCustomerToken($paypal_customer_token_data);
							}
										
							$this->model_extension_payment_paypal->setPayPalCustomerMainToken($customer_id, $payment_method, $vault_id);
						}
					}
				}

				header('HTTP/1.1 200 OK');
				
				return true;
			}
		}
		
		return false;
	}
	
	public function cron() {
		if (!empty($this->request->get['cron_token'])) {
			$_config = new Config();
			$_config->load('paypal');
		
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
			
			if (hash_equals($setting['general']['cron_token'], $this->request->get['cron_token'])) {
				$this->load->model('extension/payment/paypal');
				
				$this->model_extension_payment_paypal->cronPayment();
			
				return true;
			}
		}
		
		return false;
	}
	
	public function update() {
		$this->load->model('extension/payment/paypal');
		
		$this->model_extension_payment_paypal->update();
	}
	
	public function content_top_before($route, &$data) {
		$this->load->model('extension/payment/paypal');
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if ($this->config->get('payment_paypal_status') && $this->config->get('payment_paypal_client_id') && $this->config->get('payment_paypal_secret') && $agree_status) {
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
			
			$currency_code = $this->session->data['currency'];
					
			if (empty($setting['currency'][$currency_code]['status'])) {
				$currency_code = $setting['general']['currency_code'];
			}
			
			if (isset($this->request->get['route'])) {
				$route = $this->request->get['route'];
			} else {
				$route = 'common/home';
			} 
			
			$params = array();
			
			if (($route == 'common/home') && ($setting['message']['home']['status'] && !empty($setting['paylater_country'][$setting['general']['country_code']]) && ($currency_code == $setting['general']['currency_code']))) {
				$params['page_code'] = 'home';
			}
			
			if (($route == 'product/product') && ($setting['button']['product']['status'] || $setting['googlepay_button']['product']['status'] || $setting['applepay_button']['product']['status'] || ($setting['message']['product']['status'] && !empty($setting['paylater_country'][$setting['general']['country_code']]) && ($currency_code == $setting['general']['currency_code'])))) {
				$params['page_code'] = 'product';
			}
			
			if (($route == 'checkout/cart') && ($setting['button']['cart']['status'] || $setting['googlepay_button']['cart']['status'] || $setting['applepay_button']['cart']['status'] || ($setting['message']['cart']['status'] && !empty($setting['paylater_country'][$setting['general']['country_code']]) && ($currency_code == $setting['general']['currency_code'])))) {
				$params['page_code'] = 'cart';
			}
			
			if (($route == $setting['general']['checkout_route']) && ($setting['button']['checkout']['status'] || $setting['googlepay_button']['checkout']['status'] || $setting['applepay_button']['checkout']['status'] || $setting['card']['status'] || ($setting['message']['checkout']['status'] && !empty($setting['paylater_country'][$setting['general']['country_code']]) && ($currency_code == $setting['general']['currency_code'])))) {
				$params['page_code'] = 'checkout';
			}
			
			if ($params) {
				$theme = $this->config->get('theme_' . $this->config->get('config_theme') . '_directory');
				
				if (file_exists(DIR_TEMPLATE . $theme . '/stylesheet/paypal/paypal.css')) {
					$this->document->addStyle('catalog/view/theme/' . $theme . '/stylesheet/paypal/paypal.css');
				} else {
					$this->document->addStyle('catalog/view/theme/default/stylesheet/paypal/paypal.css');
				}
				
				if (!empty($setting['googlepay_button'][$params['page_code']]['status'])) {
					$this->document->addScript('https://pay.google.com/gp/p/js/pay.js');
				}
				
				if (!empty($setting['applepay_button'][$params['page_code']]['status'])) {
					$this->document->addScript('https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js');
				}
				
				if ($params['page_code'] == 'checkout') {			
					if ($setting['card']['status']) {
						if (file_exists(DIR_TEMPLATE . $theme . '/stylesheet/paypal/card.css')) {
							$this->document->addStyle('catalog/view/theme/' . $theme . '/stylesheet/paypal/card.css');
						} else {
							$this->document->addStyle('catalog/view/theme/default/stylesheet/paypal/card.css');
						}
					}
				}
			
				$this->document->addScript('catalog/view/javascript/paypal/paypal.js?' . http_build_query($params));
			}
		}			
	}
	
	public function extension_get_extensions_after($route, $data, &$output) {
		if ($this->config->get('payment_paypal_status') && $this->config->get('payment_paypal_client_id') && $this->config->get('payment_paypal_secret')) {
			$type = $data[0];
			
			if ($type == 'payment') {			
				$_config = new Config();
				$_config->load('paypal');
			
				$config_setting = $_config->get('paypal_setting');
		
				$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
				
				$currency_code = $this->session->data['currency'];
				
				if (!empty($setting['paylater_country'][$setting['general']['country_code']]) && ($currency_code == $setting['general']['currency_code']) && ($setting['button']['checkout']['funding']['paylater'] != 2)) {
					$this->config->set('payment_paypal_paylater_status', 1);
					
					$output[] = array(
						'extension_id' => 0,
						'type' => 'payment',
						'code' => 'paypal_paylater'
					);
				}
				
				if ($setting['googlepay_button']['checkout']['status']) {
					$this->config->set('payment_paypal_googlepay_status', 1);
					
					$output[] = array(
						'extension_id' => 0,
						'type' => 'payment',
						'code' => 'paypal_googlepay'
					);
				}
				
				if ($setting['applepay_button']['checkout']['status'] && !empty($this->session->data['paypal']['applepay'])) {
					$this->config->set('payment_paypal_applepay_status', 1);
					
					$output[] = array(
						'extension_id' => 0,
						'type' => 'payment',
						'code' => 'paypal_applepay'
					);
				}
				
				if ($setting['fastlane']['status'] && ($setting['general']['country_code'] == 'US') && !$this->customer->isLogged()) {
					$this->config->set('payment_paypal_fastlane_status', 1);
					
					$output[] = array(
						'extension_id' => 0,
						'type' => 'payment',
						'code' => 'paypal_fastlane'
					);
				}
			}
		}			
	}
	
	public function order_delete_order_before($route, $data) {
		$this->load->model('extension/payment/paypal');

		$order_id = $data[0];

		$this->model_extension_payment_paypal->deleteOrderRecurring($order_id);		
		$this->model_extension_payment_paypal->deletePayPalOrder($order_id);
		$this->model_extension_payment_paypal->deletePayPalOrderRecurring($order_id);
	}
		
	private function validateShipping($code) {
		$this->load->language('checkout/cart');
		$this->load->language('extension/payment/paypal');

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
	
	private function validatePaymentAddress() {
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			$this->error['address_1'] = $this->language->get('error_address_1');
		}

		if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
			$this->error['city'] = $this->language->get('error_city');
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '') {
			$this->error['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
			$this->error['zone'] = $this->language->get('error_zone');
		}
		
		// Customer Group
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
				$this->error['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
                $this->error['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
		}
		
		return !$this->error;
	}
	
	private function validateShippingAddress() {
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			$this->error['address_1'] = $this->language->get('error_address_1');
		}

		if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
			$this->error['city'] = $this->language->get('error_city');
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		if ($this->request->post['country_id'] == '') {
			$this->error['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
			$this->error['zone'] = $this->language->get('error_zone');
		}
		
		// Customer Group
		if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->post['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'address') { 
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$this->error['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
					$this->error['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}
		}
		
		return !$this->error;
	}
		
	private function validateCoupon() {
		$this->load->model('extension/total/coupon');

		$coupon_info = $this->model_extension_total_coupon->getCoupon($this->request->post['coupon']);

		if ($coupon_info) {
			return true;
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_coupon');
			
			return false;
		}
	}

	private function validateVoucher() {
		$this->load->model('extension/total/voucher');

		$voucher_info = $this->model_extension_total_voucher->getVoucher($this->request->post['voucher']);

		if ($voucher_info) {
			return true;
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_voucher');
			
			return false;
		}
	}

	private function validateReward() {
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
	
	private function validateFastlaneCustomer() {
		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}
		
		return !$this->error;
	}
				
	private function unserialize($str) {
		$data = array();
				
		$str = str_replace('&amp;', '&', $str);
		
		parse_str($str, $data);
		
		return $data;
	}
}