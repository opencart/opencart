<?php
class ControllerExtensionPaymentPayPal extends Controller {
	private $error = array();
			
	public function index() {				
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_SERVER;
			$catalog = HTTPS_CATALOG;
		} else {
			$server = HTTP_SERVER;
			$catalog = HTTP_CATALOG;
		}
		
		// Setting 		
		$_config = new Config();
		$_config->load('paypal');
		
		$config_setting = $_config->get('paypal_setting');
		
		if (!empty($this->session->data['environment']) && !empty($this->session->data['authorization_code']) && !empty($this->session->data['shared_id']) && !empty($this->session->data['seller_nonce']) && !empty($this->request->get['merchantIdInPayPal'])) {							
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$environment = $this->session->data['environment'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $this->session->data['shared_id'],
				'environment' => $environment,
				'partner_attribution_id' => $config_setting['partner'][$environment]['partner_attribution_id']
			);
					
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'authorization_code',
				'code' => $this->session->data['authorization_code'],
				'code_verifier' => $this->session->data['seller_nonce']
			);
			
			$paypal->setAccessToken($token_info);
							
			$result = $paypal->getSellerCredentials($config_setting['partner'][$environment]['partner_id']);
									
			$client_id = '';
			$secret = '';
			
			if (isset($result['client_id']) && isset($result['client_secret'])) {
				$client_id = $result['client_id'];
				$secret = $result['client_secret'];
			}
			
			$paypal_info = array(
				'partner_id' => $config_setting['partner'][$environment]['partner_id'],
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $config_setting['partner'][$environment]['partner_attribution_id']
			);
		
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
		
			$paypal->setAccessToken($token_info);
							
			$webhook_info = array(
				'url' => $catalog . 'index.php?route=extension/payment/paypal',
				'event_types' => array(
					array('name' => 'PAYMENT.AUTHORIZATION.CREATED'),
					array('name' => 'PAYMENT.AUTHORIZATION.VOIDED'),
					array('name' => 'PAYMENT.CAPTURE.COMPLETED'),
					array('name' => 'PAYMENT.CAPTURE.DENIED'),
					array('name' => 'PAYMENT.CAPTURE.PENDING'),
					array('name' => 'PAYMENT.CAPTURE.REFUNDED'),
					array('name' => 'PAYMENT.CAPTURE.REVERSED'),
					array('name' => 'CHECKOUT.ORDER.COMPLETED')
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
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
   			
			$merchant_id = $this->request->get['merchantIdInPayPal'];
			
			$this->load->model('setting/setting');
			
			$setting = $this->model_setting_setting->getSetting('payment_paypal');
						
			$setting['payment_paypal_environment'] = $environment;
			$setting['payment_paypal_client_id'] = $client_id;
			$setting['payment_paypal_secret'] = $secret;
			$setting['payment_paypal_merchant_id'] = $merchant_id;
			$setting['payment_paypal_webhook_id'] = $webhook_id;
			$setting['payment_paypal_status'] = 1;
			$setting['payment_paypal_total'] = 0;
			$setting['payment_paypal_geo_zone_id'] = 0;
			$setting['payment_paypal_sort_order'] = 0;
			
			$this->load->model('localisation/country');
		
			$country = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));
			
			$setting['payment_paypal_setting']['general']['country_code'] = $country['iso_code_2'];
									
			$currency_code = $this->config->get('config_currency');
			$currency_value = $this->currency->getValue($this->config->get('config_currency'));
		
			if (!empty($config_setting['currency'][$currency_code]['status'])) {
				$setting['payment_paypal_setting']['general']['currency_code'] = $currency_code;
				$setting['payment_paypal_setting']['general']['currency_value'] = $currency_value;
			}
			
			if (!empty($config_setting['currency'][$currency_code]['card_status'])) {
				$setting['payment_paypal_setting']['general']['card_currency_code'] = $currency_code;
				$setting['payment_paypal_setting']['general']['card_currency_value'] = $currency_value;
			}
			
			$this->model_setting_setting->editSetting('payment_paypal', $setting);
									
			unset($this->session->data['authorization_code']);
			unset($this->session->data['shared_id']);
			unset($this->session->data['seller_nonce']);
			
			if (!$this->error) {
				$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
			}
		}
		
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->auth();
		} else {
			$this->dashboard();
		}
	}
	
	public function auth() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		
		$this->document->setTitle($this->language->get('heading_title_main'));
				
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
								
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['partner_url'] = str_replace('&amp;', '%26', $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		$data['callback_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/callback', 'user_token=' . $this->session->data['user_token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
		
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
				
		if (isset($this->session->data['environment'])) {
			$data['environment'] = $this->session->data['environment'];
		} else {
			$data['environment'] = 'production';
		}
								
		$data['seller_nonce'] = $this->token(50);
		
		$data['configure_url'] = array(
			'production' => array(
				'ppcp' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION&product=ppcp&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION&product=EXPRESS_CHECKOUT&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			),
			'sandbox' => array(
				'ppcp' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION&product=ppcp&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION&product=EXPRESS_CHECKOUT&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			)
		);
		
		$data['text_checkout_express'] = sprintf($this->language->get('text_checkout_express'), $data['configure_url'][$data['environment']]['express_checkout']);
		$data['text_support'] = sprintf($this->language->get('text_support'), $this->request->server['HTTP_HOST']);
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}
																
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
					
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paypal/auth', $data));
	}
		
	public function dashboard() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		$this->load->model('setting/setting');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));

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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message'] = $this->url->link('extension/payment/paypal/message', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'user_token=' . $this->session->data['user_token'], true);
						
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['sale_analytics_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/getSaleAnalytics', 'user_token=' . $this->session->data['user_token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
		
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
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		if ($this->config->get('payment_paypal_status') != null) {
			$data['status'] = $this->config->get('payment_paypal_status');
		} else {
			$data['status'] = 1;
		}
									
		if ($data['setting']['button']['product']['status'] || $data['setting']['button']['cart']['status'] || $data['setting']['button']['checkout']['status']) {
			$data['button_status'] = 1;
		} else {
			$data['button_status'] = 0;
		}
		
		if ($data['setting']['applepay_button']['status']) {
			$data['applepay_button_status'] = 1;
		} else {
			$data['applepay_button_status'] = 0;
		}
		
		if ($data['setting']['card']['status']) {
			$data['card_status'] = 1;
		} else {
			$data['card_status'] = 0;
		}
		
		if ($data['setting']['message']['home']['status'] || $data['setting']['message']['product']['status'] || $data['setting']['message']['cart']['status'] || $data['setting']['message']['checkout']['status']) {
			$data['message_status'] = 1;
		} else {
			$data['message_status'] = 0;
		}
		
		$paypal_sale_total = $this->model_extension_payment_paypal->getTotalSales();
		
		$data['paypal_sale_total'] = $this->currency->format($paypal_sale_total, $this->config->get('config_currency'));
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
					
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paypal/dashboard', $data));
	}
	
	public function general() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
						
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message'] = $this->url->link('extension/payment/paypal/message', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['disconnect_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/disconnect', 'user_token=' . $this->session->data['user_token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
				
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
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		if ($this->config->get('payment_paypal_status') != null) {
			$data['status'] = $this->config->get('payment_paypal_status');
		} else {
			$data['status'] = 1;
		}
					
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
				
		$data['text_connect'] = sprintf($this->language->get('text_connect'), $data['client_id'], $data['secret'], $data['merchant_id'], $data['webhook_id'], $data['environment']);

		$data['total'] = $this->config->get('payment_paypal_total');
		$data['geo_zone_id'] = $this->config->get('payment_paypal_geo_zone_id');
		$data['sort_order'] = $this->config->get('payment_paypal_sort_order');
						
		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
										
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
							
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/general', $data));
	}
	
	public function button() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
			
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message'] = $this->url->link('extension/payment/paypal/message', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
						
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
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
				
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
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
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/button', $data));
	}
	
	public function applepay_button() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');
		$this->document->addScript('https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
				
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message'] = $this->url->link('extension/payment/paypal/message', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['applepay_download_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/downloadAssociationFile', 'user_token=' . $this->session->data['user_token'], true));
		$data['applepay_download_host_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/downloadHostAssociationFile', 'user_token=' . $this->session->data['user_token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
		
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
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
				
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
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
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/applepay_button', $data));
	}
	
	public function card() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/card.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
				
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message'] = $this->url->link('extension/payment/paypal/message', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
		
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
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
				
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
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
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/card', $data));
	}
	
	public function message() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));
				
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message'] = $this->url->link('extension/payment/paypal/message', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
		
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
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];
		
		$country = $this->model_extension_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);
		
		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];
			
		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];
						
		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];
		
		if ($country['iso_code_2'] == 'GB') {
			$data['text_message_alert'] = $this->language->get('text_message_alert_uk');
			$data['text_message_footnote'] = $this->language->get('text_message_footnote_uk');
		} elseif ($country['iso_code_2'] == 'US') {
			$data['text_message_alert'] = $this->language->get('text_message_alert_us');
			$data['text_message_footnote'] = $this->language->get('text_message_footnote_us');
		}
						
		if ($data['client_id'] && $data['secret']) {										
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $data['client_id'],
				'secret' => $data['secret'],
				'environment' => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
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
					} elseif (isset($error['message'])) {
						$error_messages[] = $error['message'];
					}
					
					$this->model_extension_payment_paypal->log($error, $error['message']);
				}
				
				$this->error['warning'] = implode(' ', $error_messages);
			}
		}
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/message', $data));
	}
	
	public function order_status() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');

		$this->document->setTitle($this->language->get('heading_title_main'));
				
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message'] = $this->url->link('extension/payment/paypal/message', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
		
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
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}		
									
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/order_status', $data));
	}
	
	public function contact() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');

		$this->document->setTitle($this->language->get('heading_title_main'));
			
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
			'text' => $this->language->get('heading_title_main'),
			'href' => $this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		// Action
		$data['href_dashboard'] = $this->url->link('extension/payment/paypal/dashboard', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_general'] = $this->url->link('extension/payment/paypal/general', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_button'] = $this->url->link('extension/payment/paypal/button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message'] = $this->url->link('extension/payment/paypal/message', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_order_status'] = $this->url->link('extension/payment/paypal/order_status', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_contact'] = $this->url->link('extension/payment/paypal/contact', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['action'] = $this->url->link('extension/payment/paypal/save', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		$data['contact_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/sendContact', 'user_token=' . $this->session->data['user_token'], true));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
		
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
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$result = $this->model_extension_payment_paypal->checkVersion(VERSION, $data['setting']['version']);
		
		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}
		
		$agree_status = $this->model_extension_payment_paypal->getAgreeStatus();
		
		if (!$agree_status) {
			$this->error['warning'] = $this->language->get('error_agree');
		}		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
											
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/contact', $data));
	}
	
	public function save() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('setting/setting');
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$setting = $this->model_setting_setting->getSetting('payment_paypal');
			
			$setting = array_replace_recursive($setting, $this->request->post);
						
			$this->model_setting_setting->editSetting('payment_paypal', $setting);
														
			$data['success'] = $this->language->get('success_save');
		}
		
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));	
	}
	
	public function disconnect() {
		$this->load->model('setting/setting');
		
		$setting = $this->model_setting_setting->getSetting('payment_paypal');
						
		$setting['payment_paypal_client_id'] = '';
		$setting['payment_paypal_secret'] = '';
		$setting['payment_paypal_merchant_id'] = '';
		$setting['payment_paypal_webhook_id'] = '';
		
		$this->model_setting_setting->editSetting('payment_paypal', $setting);
		
		$data['error'] = $this->error;
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
		
	public function callback() {
		if (isset($this->request->post['environment']) && isset($this->request->post['authorization_code']) && isset($this->request->post['shared_id']) && isset($this->request->post['seller_nonce'])) {
			$this->session->data['environment'] = $this->request->post['environment'];
			$this->session->data['authorization_code'] = $this->request->post['authorization_code'];
			$this->session->data['shared_id'] = $this->request->post['shared_id'];
			$this->session->data['seller_nonce'] = $this->request->post['seller_nonce'];
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
    }
	
	public function getSaleAnalytics() {
		$this->load->language('extension/payment/paypal');

		$data = array();

		$this->load->model('extension/payment/paypal');

		$data['all_sale'] = array();
		$data['paypal_sale'] = array();
		$data['xaxis'] = array();
		
		$data['all_sale']['label'] = $this->language->get('text_all_sales');
		$data['paypal_sale']['label'] = $this->language->get('text_paypal_sales');
		$data['all_sale']['data'] = array();
		$data['paypal_sale']['data'] = array();

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}

		switch ($range) {
			default:
			case 'day':
				$results = $this->model_extension_payment_paypal->getTotalSalesByDay();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = array($key, $value['total']);
					$data['paypal_sale']['data'][] = array($key, $value['paypal_total']);
				}

				for ($i = 0; $i < 24; $i++) {
					$data['xaxis'][] = array($i, $i);
				}
				
				break;
			case 'week':
				$results = $this->model_extension_payment_paypal->getTotalSalesByWeek();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = array($key, $value['total']);
					$data['paypal_sale']['data'][] = array($key, $value['paypal_total']);
				}

				$date_start = strtotime('-' . date('w') . ' days');

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$data['xaxis'][] = array(date('w', strtotime($date)), date('D', strtotime($date)));
				}
				
				break;
			case 'month':
				$results = $this->model_extension_payment_paypal->getTotalSalesByMonth();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = array($key, $value['total']);
					$data['paypal_sale']['data'][] = array($key, $value['paypal_total']);
				}

				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$data['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));
				}
				
				break;
			case 'year':
				$results = $this->model_extension_payment_paypal->getTotalSalesByYear();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = array($key, $value['total']);
					$data['paypal_sale']['data'][] = array($key, $value['paypal_total']);
				}

				for ($i = 1; $i <= 12; $i++) {
					$data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
				}
				
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function downloadAssociationFile() {
		$environment = $this->config->get('payment_paypal_environment');
		
		if ($environment == 'production') {
			$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association';
		
			$file_headers = @get_headers($file);
				
			if (strpos($file_headers[0], '404') !== false) {
				$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association.txt';
			}
		} else {
			$file = 'https://www.paypalobjects.com/sandbox/apple-developer-merchantid-domain-association';
		}
		
		header('Content-Description: File Transfer');
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename="' . basename($file, '.txt') . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
				
		readfile($file);
	}
	
	public function downloadHostAssociationFile() {
		$this->load->language('extension/payment/paypal');
		
		$environment = $this->config->get('payment_paypal_environment');
		
		if ($environment == 'production') {
			$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association';
		
			$file_headers = @get_headers($file);
				
			if (strpos($file_headers[0], '404') !== false) {
				$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association.txt';
			}
		} else {
			$file = 'https://www.paypalobjects.com/sandbox/apple-developer-merchantid-domain-association';
		}
		
		$content = file_get_contents($file);
				
		if ($content) {
			$dir = str_replace('admin/', '.well-known/', DIR_APPLICATION);
			
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			
			if (file_exists($dir)) {
				$fh = fopen($dir . basename($file, '.txt'), 'w');
				fwrite($fh, $content);
				fclose($fh);
			}
			
			$data['success'] = $this->language->get('success_download_host');
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
				
	public function sendContact() {
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		if (isset($this->request->post['payment_paypal_setting']['contact'])) {
			$this->model_extension_payment_paypal->sendContact($this->request->post['payment_paypal_setting']['contact']);
			
			$data['success'] = $this->language->get('success_send');
		}
		
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function agree() {		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->model_extension_payment_paypal->setAgreeStatus();
			
		$data['success'] = $this->language->get('success_agree');
				
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
					
	public function install() {
		$this->load->model('setting/event');
		
		$this->model_setting_event->deleteEventByCode('paypal_header');
		$this->model_setting_event->deleteEventByCode('paypal_extension_get_extensions');
		
		$this->model_setting_event->addEvent('paypal_header', 'catalog/controller/common/header/before', 'extension/payment/paypal/header_before');
		$this->model_setting_event->addEvent('paypal_extension_get_extensions', 'catalog/model/setting/extension/getExtensions/after', 'extension/payment/paypal/extension_get_extensions_after');
	}
	
	public function uninstall() {
		$this->load->model('setting/event');
		
		$this->model_setting_event->deleteEventByCode('paypal_header');
		$this->model_setting_event->deleteEventByCode('paypal_extension_get_extensions');
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paypal')) {
			$this->error['warning'] = $this->language->get('error_permission');
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