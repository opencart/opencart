<?php
class ControllerExtensionPaymentPayPal extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/payment/paypal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/payment/paypal');
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_paypal', $this->request->post);
														
			$this->session->data['success'] = $this->language->get('success_save');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}
			
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
						
		$data['action'] = $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['partner_url'] = str_replace('&amp;', '%26', $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		$data['callback_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/callback', 'user_token=' . $this->session->data['user_token'], true));
		$data['configure_smart_button_url'] = $this->url->link('extension/payment/paypal/configureSmartButton', 'user_token=' . $this->session->data['user_token'], true);
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		// Setting 		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
					
		if (isset($this->session->data['environment']) && isset($this->session->data['authorization_code']) && isset($this->session->data['shared_id']) && isset($this->session->data['seller_nonce']) && isset($this->request->get['merchantIdInPayPal'])) {						
			$environment = $this->session->data['environment'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $this->session->data['shared_id'],
				'environment' => $environment
			);
					
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'authorization_code',
				'code' => $this->session->data['authorization_code'],
				'code_verifier' => $this->session->data['seller_nonce']
			);
			
			$paypal->setAccessToken($token_info);
											
			$result = $paypal->getSellerCredentials($data['setting']['partner'][$environment]['partner_id']);
			
			if (isset($result['client_id']) && isset($result['client_secret'])) {
				$client_id = $result['client_id'];
				$secret = $result['client_secret'];
			}
						
			$webhook_info = array(
				'url' => $data['catalog'] . 'index.php?route=extension/payment/paypal/webhook',
				'event_types' => array(
					array('name' => 'PAYMENT.AUTHORIZATION.CREATED'),
					array('name' => 'PAYMENT.AUTHORIZATION.VOIDED'),
					array('name' => 'PAYMENT.CAPTURE.COMPLETED'),
					array('name' => 'PAYMENT.CAPTURE.DENIED'),
					array('name' => 'PAYMENT.CAPTURE.PENDING'),
					array('name' => 'PAYMENT.CAPTURE.REFUNDED'),
					array('name' => 'PAYMENT.CAPTURE.REVERSED'),
					array('name' => 'CHECKOUT.ORDER.COMPLETED'),
					array('name' => 'CHECKOUT.ORDER.APPROVED')
				)
			);
			
			$result = $paypal->createWebhook($webhook_info);
			
			$webhook_id = '';
		
			if (isset($result['id'])) {
				$webhook_id = $result['id'];
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
   			
			$merchant_id = $this->request->get['merchantIdInPayPal'];
						
			unset($this->session->data['authorization_code']);
			unset($this->session->data['shared_id']);
			unset($this->session->data['seller_nonce']);
		}
		
		if (isset($environment)) {
			$data['environment'] = $environment;
		} elseif (isset($this->request->post['payment_paypal_environment'])) {
			$data['environment'] = $this->request->post['payment_paypal_environment'];
		} elseif ($this->config->get('payment_paypal_environment')) {
			$data['environment'] = $this->config->get('payment_paypal_environment');
		} else {
			$data['environment'] = 'production';
		}
				
		$data['seller_nonce'] = $this->token(50);
		
		$data['configure_url'] = array(
			'production' => array(
				'ppcp' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND&product=ppcp&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND&product=EXPRESS_CHECKOUT&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			),
			'sandbox' => array(
				'ppcp' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND&product=ppcp&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND&product=EXPRESS_CHECKOUT&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			)
		);
		
		$data['help_checkout_express'] = sprintf($this->language->get('help_checkout_express'), $data['configure_url'][$data['environment']]['express_checkout']);
		
		if (isset($client_id)) {
			$data['client_id'] = $client_id;
		} elseif (isset($this->request->post['payment_paypal_client_id'])) {
			$data['client_id'] = $this->request->post['payment_paypal_client_id'];
		} else {
			$data['client_id'] = $this->config->get('payment_paypal_client_id');
		}

		if (isset($secret)) {
			$data['secret'] = $secret;
		} elseif (isset($this->request->post['payment_paypal_secret'])) {
			$data['secret'] = $this->request->post['payment_paypal_secret'];
		} else {
			$data['secret'] = $this->config->get('payment_paypal_secret');
		}
		
		if (isset($merchant_id)) {
			$data['merchant_id'] = $merchant_id;
		} elseif (isset($this->request->post['payment_paypal_merchant_id'])) {
			$data['merchant_id'] = $this->request->post['payment_paypal_merchant_id'];
		} else {
			$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		}
		
		$data['text_connect'] = sprintf($this->language->get('text_connect'), $data['client_id'], $data['secret'], $data['merchant_id']);
		
		if (isset($webhook_id)) {
			$data['webhook_id'] = $webhook_id;
		} elseif (isset($this->request->post['payment_paypal_webhook_id'])) {
			$data['webhook_id'] = $this->request->post['payment_paypal_webhook_id'];
		} else {
			$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		}

		if (isset($this->request->post['payment_paypal_debug'])) {
			$data['debug'] = $this->request->post['payment_paypal_debug'];
		} else {
			$data['debug'] = $this->config->get('payment_paypal_debug');
		}
								
		if (isset($this->request->post['payment_paypal_transaction_method'])) {
			$data['transaction_method'] = $this->request->post['payment_paypal_transaction_method'];
		} else {
			$data['transaction_method'] = $this->config->get('payment_paypal_transaction_method');
		}

		if (isset($this->request->post['payment_paypal_total'])) {
			$data['total'] = $this->request->post['payment_paypal_total'];
		} else {
			$data['total'] = $this->config->get('payment_paypal_total');
		}
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_paypal_geo_zone_id'])) {
			$data['geo_zone_id'] = $this->request->post['payment_paypal_geo_zone_id'];
		} else {
			$data['geo_zone_id'] = $this->config->get('payment_paypal_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_paypal_status'])) {
			$data['status'] = $this->request->post['payment_paypal_status'];
		} else {
			$data['status'] = $this->config->get('payment_paypal_status');
		}

		if (isset($this->request->post['payment_paypal_sort_order'])) {
			$data['sort_order'] = $this->request->post['payment_paypal_sort_order'];
		} else {
			$data['sort_order'] = $this->config->get('payment_paypal_sort_order');
		}
		
		if (isset($this->request->post['payment_paypal_currency_code'])) {
			$data['currency_code'] = $this->request->post['payment_paypal_currency_code'];
		} else {
			$data['currency_code'] = $this->config->get('payment_paypal_currency_code');
		}
		
		if (isset($this->request->post['payment_paypal_currency_value'])) {
			$data['currency_value'] = $this->request->post['payment_paypal_currency_value'];
		} elseif ($this->config->get('payment_paypal_currency_value')) {
			$data['currency_value'] = $this->config->get('payment_paypal_currency_value');
		} else {
			$data['currency_value'] = '1';
		}
						
		if (isset($this->request->post['payment_paypal_setting'])) {
			$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->request->post['payment_paypal_setting']);
		} else {
			$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		}
		
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
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
		}
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
					
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paypal', $data));
	}
		
	public function callback() {
		if (isset($this->request->post['environment']) && isset($this->request->post['authorization_code']) && isset($this->request->post['shared_id']) && isset($this->request->post['seller_nonce'])) {
			$this->session->data['environment'] = $this->request->post['environment'];
			$this->session->data['authorization_code'] = $this->request->post['authorization_code'];
			$this->session->data['shared_id'] = $this->request->post['shared_id'];
			$this->session->data['seller_nonce'] = $this->request->post['seller_nonce'];
		}
		
		$data['error'] = $this->error;
				
		$this->response->setOutput(json_encode($data));
    }
	
	public function configureSmartButton() {
		$this->load->model('extension/payment/paypal');
		
		$this->model_extension_payment_paypal->configureSmartButton();
		
		$this->response->redirect($this->url->link('extension/module/paypal_smart_button', 'user_token=' . $this->session->data['user_token'], true));
	}
				
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paypal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
		$paypal_info = array(
			'client_id' => $this->request->post['payment_paypal_client_id'],
			'secret' => $this->request->post['payment_paypal_secret'],
			'environment' => $this->request->post['payment_paypal_environment']
		);
		
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
				} else {
					$error_messages[] = $error['message'];
				}
					
				$this->model_extension_payment_paypal->log($error, $error['message']);
			}
				
			$this->error['warning'] = implode(' ', $error_messages);
		}
		
		return !$this->error;
	}
	
	private function token($length = 32) {
		// Create random token
		$string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
	
		$max = strlen($string) - 1;
	
		$token = '';
	
		for ($i = 0; $i < $length; $i++) {
			$token .= $string[mt_rand(0, $max)];
		}	
	
		return $token;
	}
}