<?php
class ControllerExtensionPaymentPayPal extends Controller {
	private $error = array();
	
	public function __construct($registry) {
		parent::__construct($registry);
		
		if (empty($this->config->get('paypal_version')) || (!empty($this->config->get('paypal_version')) && ($this->config->get('paypal_version') < '3.0.0'))) {
			$this->uninstall();
			$this->install();
		}
	}
			
	public function index() {				
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_SERVER;
			$catalog = HTTPS_CATALOG;
		} else {
			$server = HTTP_SERVER;
			$catalog = HTTP_CATALOG;
		}
			
		$_config = new Config();
		$_config->load('paypal');
		
		$config_setting = $_config->get('paypal_setting');
		
		$cache_data = $this->cache->get('paypal');
		
		$this->cache->delete('paypal');
		
		if (!empty($cache_data['environment']) && !empty($cache_data['authorization_code']) && !empty($cache_data['shared_id']) && !empty($cache_data['seller_nonce']) && !empty($this->request->get['merchantIdInPayPal'])) {							
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$environment = $cache_data['environment'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
			
			$paypal_info = array(
				'client_id' => $cache_data['shared_id'],
				'environment' => $environment,
				'partner_attribution_id' => $config_setting['partner'][$environment]['partner_attribution_id']
			);
					
			$paypal = new PayPal($paypal_info);
			
			$token_info = array(
				'grant_type' => 'authorization_code',
				'code' => $cache_data['authorization_code'],
				'code_verifier' => $cache_data['seller_nonce']
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
			
			$callback_token = sha1(uniqid(mt_rand(), 1));
			$webhook_token = sha1(uniqid(mt_rand(), 1));
			$cron_token = sha1(uniqid(mt_rand(), 1));
			
			$webhook_info = array(
				'url' => $catalog . 'index.php?route=extension/payment/paypal&webhook_token=' . $webhook_token,
				'event_types' => array(
					array('name' => 'PAYMENT.AUTHORIZATION.CREATED'),
					array('name' => 'PAYMENT.AUTHORIZATION.VOIDED'),
					array('name' => 'PAYMENT.CAPTURE.COMPLETED'),
					array('name' => 'PAYMENT.CAPTURE.DENIED'),
					array('name' => 'PAYMENT.CAPTURE.PENDING'),
					array('name' => 'PAYMENT.CAPTURE.REFUNDED'),
					array('name' => 'PAYMENT.CAPTURE.REVERSED'),
					array('name' => 'CHECKOUT.ORDER.COMPLETED'),
					array('name' => 'VAULT.PAYMENT-TOKEN.CREATED')
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
			$setting['payment_paypal_setting']['general']['callback_token'] = $callback_token;
			$setting['payment_paypal_setting']['general']['webhook_token'] = $webhook_token;
			$setting['payment_paypal_setting']['general']['cron_token'] = $cron_token;
									
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
		}
		
		if (!empty($this->request->get['merchantIdInPayPal']) && !$this->error) {
			sleep(3);
			
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
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
		$data['connect_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/connect', 'user_token=' . $this->session->data['user_token'], true));
		$data['agree_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/agree', 'user_token=' . $this->session->data['user_token'], true));
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$data['server'] = HTTPS_SERVER;
			$data['catalog'] = HTTPS_CATALOG;
		} else {
			$data['server'] = HTTP_SERVER;
			$data['catalog'] = HTTP_CATALOG;
		}
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['authorization_type'] = 'automatic';				
		$data['environment'] = 'production';
					
		$data['seller_nonce'] = $this->token(50);
		
		$data['configure_url'] = array(
			'production' => array(
				'ppcp' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=PPCP,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=EXPRESS_CHECKOUT,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			),
			'sandbox' => array(
				'ppcp' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=PPCP,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=EXPRESS_CHECKOUT,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
		
		if ($data['setting']['googlepay_button']['product']['status'] || $data['setting']['googlepay_button']['cart']['status'] || $data['setting']['googlepay_button']['checkout']['status']) {
			$data['googlepay_button_status'] = 1;
		} else {
			$data['googlepay_button_status'] = 0;
		}
		
		if ($data['setting']['applepay_button']['product']['status'] || $data['setting']['applepay_button']['cart']['status'] || $data['setting']['applepay_button']['checkout']['status']) {
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
				
		$data['text_connect'] = sprintf($this->language->get('text_connect'), $data['merchant_id'], $data['client_id'], $data['secret'], $data['webhook_id'], $data['environment']);

		$data['total'] = $this->config->get('payment_paypal_total');
		$data['geo_zone_id'] = $this->config->get('payment_paypal_geo_zone_id');
		$data['sort_order'] = $this->config->get('payment_paypal_sort_order');
						
		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
										
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();
		
		$data['cron_url'] = $data['catalog'] . 'index.php?route=extension/payment/paypal&cron_token=' . $data['setting']['general']['cron_token'];
		
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
	
	public function googlepay_button() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');
		$this->document->addScript('https://pay.google.com/gp/p/js/pay.js');

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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/googlepay_button', $data));
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
		
	public function message_configurator() {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/payment/paypal', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->load->language('extension/payment/paypal');
		
		$this->load->model('extension/payment/paypal');
		
		$this->document->addStyle('view/stylesheet/paypal/paypal.css');
		$this->document->addStyle('view/stylesheet/paypal/bootstrap-switch.css');
		
		$this->document->addScript('view/javascript/paypal/paypal.js');
		$this->document->addScript('view/javascript/paypal/bootstrap-switch.js');
		$this->document->addScript('https://www.paypalobjects.com/merchant-library/merchant-configurator.js');
				
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_client_id'] = $data['setting']['partner'][$data['environment']]['client_id'];
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
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/message_configurator', $data));
	}
	
	public function message_setting() {
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
		
		$_config = new Config();
		$_config->load('paypal');
		
		$data['setting'] = $_config->get('paypal_setting');
		
		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));
		
		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_client_id'] = $data['setting']['partner'][$data['environment']]['client_id'];
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
		
		$this->response->setOutput($this->load->view('extension/payment/paypal/message_setting', $data));
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
		$data['href_googlepay_button'] = $this->url->link('extension/payment/paypal/googlepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_applepay_button'] = $this->url->link('extension/payment/paypal/applepay_button', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_card'] = $this->url->link('extension/payment/paypal/card', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_configurator'] = $this->url->link('extension/payment/paypal/message_configurator', 'user_token=' . $this->session->data['user_token'], true);
		$data['href_message_setting'] = $this->url->link('extension/payment/paypal/message_setting', 'user_token=' . $this->session->data['user_token'], true);
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
	
	public function connect() {
		$this->load->language('extension/payment/paypal');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_SERVER;
			$catalog = HTTPS_CATALOG;
		} else {
			$server = HTTP_SERVER;
			$catalog = HTTP_CATALOG;
		}
			
		$_config = new Config();
		$_config->load('paypal');
		
		$config_setting = $_config->get('paypal_setting');
				
		if (!empty($this->request->post['environment']) && !empty($this->request->post['client_id']) && !empty($this->request->post['client_secret']) && !empty($this->request->post['merchant_id'])) {										
			$this->load->model('extension/payment/paypal');
			
			$environment = $this->request->post['environment'];
			$client_id = $this->request->post['client_id'];
			$secret = $this->request->post['client_secret'];
			$merchant_id = $this->request->post['merchant_id'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
									
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
		
			$result = $paypal->setAccessToken($token_info);
			
			if ($result) {
				$callback_token = sha1(uniqid(mt_rand(), 1));
				$webhook_token = sha1(uniqid(mt_rand(), 1));
				$cron_token = sha1(uniqid(mt_rand(), 1));
			
				$webhook_info = array(
					'url' => $catalog . 'index.php?route=extension/payment/paypal&webhook_token=' . $webhook_token,
					'event_types' => array(
						array('name' => 'PAYMENT.AUTHORIZATION.CREATED'),
						array('name' => 'PAYMENT.AUTHORIZATION.VOIDED'),
						array('name' => 'PAYMENT.CAPTURE.COMPLETED'),
						array('name' => 'PAYMENT.CAPTURE.DENIED'),
						array('name' => 'PAYMENT.CAPTURE.PENDING'),
						array('name' => 'PAYMENT.CAPTURE.REFUNDED'),
						array('name' => 'PAYMENT.CAPTURE.REVERSED'),
						array('name' => 'CHECKOUT.ORDER.COMPLETED'),
						array('name' => 'VAULT.PAYMENT-TOKEN.CREATED')
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
										   						
				if (!$this->error) {
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
					$setting['payment_paypal_setting']['general']['callback_token'] = $callback_token;
					$setting['payment_paypal_setting']['general']['webhook_token'] = $webhook_token;
					$setting['payment_paypal_setting']['general']['cron_token'] = $cron_token;
									
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
				}
			} else {
				$this->error['warning'] = $this->language->get('error_connect');
			}
		} else {
			 $this->error['warning'] = $this->language->get('error_connect');
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
			$cache_data['environment'] = $this->request->post['environment'];
			$cache_data['authorization_code'] = $this->request->post['authorization_code'];
			$cache_data['shared_id'] = $this->request->post['shared_id'];
			$cache_data['seller_nonce'] = $this->request->post['seller_nonce'];
			
			$this->cache->set('paypal', $cache_data, 30);
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
		$this->load->model('extension/payment/paypal');
		
		$this->model_extension_payment_paypal->install();
		
		$this->load->model('setting/event');
		
		$this->model_setting_event->deleteEventByCode('paypal_order_info');
		$this->model_setting_event->deleteEventByCode('paypal_header');
		$this->model_setting_event->deleteEventByCode('paypal_extension_get_extensions');
		$this->model_setting_event->deleteEventByCode('paypal_order_delete_order');
		$this->model_setting_event->deleteEventByCode('paypal_customer_delete_customer');
		
		$this->model_setting_event->addEvent('paypal_order_info', 'admin/view/sale/order_info/before', 'extension/payment/paypal/order_info_before');
		$this->model_setting_event->addEvent('paypal_header', 'catalog/controller/common/header/before', 'extension/payment/paypal/header_before');
		$this->model_setting_event->addEvent('paypal_extension_get_extensions', 'catalog/model/setting/extension/getExtensions/after', 'extension/payment/paypal/extension_get_extensions_after');
		$this->model_setting_event->addEvent('paypal_order_delete_order', 'catalog/model/checkout/order/deleteOrder/before', 'extension/payment/paypal/order_delete_order_before');
		$this->model_setting_event->addEvent('paypal_customer_delete_customer', 'admin/model/customer/customer/deleteCustomer/before', 'extension/payment/paypal/customer_delete_customer_before');
		
		$_config = new Config();
		$_config->load('paypal');
			
		$config_setting = $_config->get('paypal_setting');
				
		$setting['paypal_version'] = $config_setting['version'];
		
		$this->load->model('setting/setting');
		
		$this->model_setting_setting->editSetting('paypal_version', $setting);
	}
	
	public function uninstall() {
		$this->load->model('extension/payment/paypal');
		
		$this->model_extension_payment_paypal->uninstall();
		
		$this->load->model('setting/event');
		
		$this->model_setting_event->deleteEventByCode('paypal_order_info');
		$this->model_setting_event->deleteEventByCode('paypal_header');
		$this->model_setting_event->deleteEventByCode('paypal_extension_get_extensions');
		$this->model_setting_event->deleteEventByCode('paypal_order_delete_order');
		$this->model_setting_event->deleteEventByCode('paypal_customer_delete_customer');
		
		$this->load->model('setting/setting');
		
		$this->model_setting_setting->deleteSetting('paypal_version');
	}
	
	public function customer_delete_customer_before($route, &$data) {
		$this->load->model('extension/payment/paypal');

		$customer_id = $data[0];

		$this->model_extension_payment_paypal->deletePayPalCustomerTokens($customer_id);
	}
	
	public function order_info_before($route, &$data) {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->get['order_id'])) {
			$this->load->language('extension/payment/paypal','extension_payment_paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$data['order_id'] = (int)$this->request->get['order_id'];
			
			$paypal_order_info = $this->model_extension_payment_paypal->getPayPalOrder($data['order_id']);
				
			if ($paypal_order_info) {
				$data['transaction_id'] = $paypal_order_info['transaction_id'];
				$data['transaction_status'] = $paypal_order_info['transaction_status'];
				
				if ($paypal_order_info['environment'] == 'production') {
					$data['transaction_url'] = 'https://www.paypal.com/activity/payment/' . $data['transaction_id'];
				} else {
					$data['transaction_url'] = 'https://www.sandbox.paypal.com/activity/payment/' . $data['transaction_id'];
				}
				
				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/getPaymentInfo', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $data['order_id'], true));
				$data['capture_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/capturePayment', 'user_token=' . $this->session->data['user_token'], true));
				$data['reauthorize_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/reauthorizePayment', 'user_token=' . $this->session->data['user_token'], true));
				$data['void_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/voidPayment', 'user_token=' . $this->session->data['user_token'], true));
				$data['refund_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/refundPayment', 'user_token=' . $this->session->data['user_token'], true));
								
				$data['tabs'][] = array(
					'code'    => 'paypal',
					'title'   => $this->language->get('extension_payment_paypal')->get('heading_title_main'),
					'content' => $this->load->view('extension/payment/paypal/order', $data)
				);
			}
		}
	}
		
	public function getPaymentInfo() {
		$content = '';
		
		if ($this->config->get('payment_paypal_status') && !empty($this->request->get['order_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$data['order_id'] = (int)$this->request->get['order_id'];
			
			$paypal_order_info = $this->model_extension_payment_paypal->getPayPalOrder($data['order_id']);
				
			if ($paypal_order_info) {
				$data['transaction_id'] = $paypal_order_info['transaction_id'];
				$data['transaction_status'] = $paypal_order_info['transaction_status'];
				
				if ($paypal_order_info['environment'] == 'production') {
					$data['transaction_url'] = 'https://www.paypal.com/activity/payment/' . $data['transaction_id'];
				} else {
					$data['transaction_url'] = 'https://www.sandbox.paypal.com/activity/payment/' . $data['transaction_id'];
				}
				
				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/getPaymentInfo', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $data['order_id'], true));
				$data['capture_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/capturePayment', 'user_token=' . $this->session->data['user_token'], true));
				$data['reauthorize_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/reauthorizePayment', 'user_token=' . $this->session->data['user_token'], true));
				$data['void_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/voidPayment', 'user_token=' . $this->session->data['user_token'], true));
				$data['refund_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/refundPayment', 'user_token=' . $this->session->data['user_token'], true));
								
				$content = $this->load->view('extension/payment/paypal/order', $data);
			}
		}
		
		$this->response->setOutput($content);
	}
	
	public function capturePayment() {						
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
				
			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);

			$result = $paypal->setPaymentCapture($transaction_id);
							
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
						
			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_id = $result['id'];
				$transaction_status = 'completed';
														
				$paypal_order_data = array(
					'order_id' => $order_id,
					'transaction_id' => $transaction_id,
					'transaction_status' => $transaction_status
				);

				$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
								
				$data['success'] = $this->language->get('success_capture_payment');
			}
		}
				
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function reauthorizePayment() {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
				
			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);
		
			$result = $paypal->setPaymentReauthorize($transaction_id);
								
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
							
			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_id = $result['id'];
				$transaction_status = 'created';
				
				$this->model_extension_payment_paypal->deletePayPalOrder($order_id);
										
				$paypal_order_data = array(
					'order_id' => $order_id,
					'transaction_id' => $transaction_id,
					'transaction_status' => $transaction_status
				);

				$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
								
				$data['success'] = $this->language->get('success_reauthorize_payment');
			}
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function voidPayment() {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
				
			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);
		
			$result = $paypal->setPaymentVoid($transaction_id);
				
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
			
			if (!$this->error) {
				$transaction_status = 'voided';
				
				$this->model_extension_payment_paypal->deletePayPalOrder($order_id);
										
				$paypal_order_data = array(
					'order_id' => $order_id,
					'transaction_status' => $transaction_status
				);

				$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
								
				$data['success'] = $this->language->get('success_void_payment');
			}
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function refundPayment() {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];
			
			$_config = new Config();
			$_config->load('paypal');
			
			$config_setting = $_config->get('paypal_setting');
		
			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));
				
			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];
			
			require_once DIR_SYSTEM . 'library/paypal/paypal.php';
		
			$paypal_info = array(
				'partner_id' => $partner_id,
				'client_id' => $client_id,
				'secret' => $secret,
				'environment' => $environment,
				'partner_attribution_id' => $partner_attribution_id
			);
		
			$paypal = new PayPal($paypal_info);
		
			$token_info = array(
				'grant_type' => 'client_credentials'
			);	
						
			$paypal->setAccessToken($token_info);

			$result = $paypal->setPaymentRefund($transaction_id);
							
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
		
			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_status = 'refunded';
				
				$paypal_order_data = array(
					'order_id' => $order_id,
					'transaction_status' => $transaction_status
				);

				$this->model_extension_payment_paypal->editPayPalOrder($paypal_order_data);
								
				$data['success'] = $this->language->get('success_refund_payment');
			}
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function recurringButtons() {
		$content = '';
		
		if ($this->config->get('payment_paypal_status') && !empty($this->request->get['order_recurring_id'])) {
			$this->load->language('extension/payment/paypal');
		
			$this->load->model('sale/recurring');
			
			$data['order_recurring_id'] = $this->request->get['order_recurring_id'];

			$order_recurring_info = $this->model_sale_recurring->getRecurring($data['order_recurring_id']);
			
			if ($order_recurring_info) {
				$data['recurring_status'] = $order_recurring_info['status'];
				
				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/getRecurringInfo', 'user_token=' . $this->session->data['user_token'] . '&order_recurring_id=' . $data['order_recurring_id'], true));
				$data['enable_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/enableRecurring', 'user_token=' . $this->session->data['user_token'], true));
				$data['disable_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/paypal/disableRecurring', 'user_token=' . $this->session->data['user_token'], true));
				
				$content = $this->load->view('extension/payment/paypal/recurring', $data);
			}
		}
		
		return $content;
	}
		
	public function getRecurringInfo() {
		$this->response->setOutput($this->recurringButtons());
	}
	
	public function enableRecurring() {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_recurring_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_recurring_id = $this->request->post['order_recurring_id'];
			
			$this->model_extension_payment_paypal->editOrderRecurringStatus($order_recurring_id, 1);
			
			$data['success'] = $this->language->get('success_enable_recurring');	
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function disableRecurring() {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_recurring_id'])) {
			$this->load->language('extension/payment/paypal');
			
			$this->load->model('extension/payment/paypal');
			
			$order_recurring_id = $this->request->post['order_recurring_id'];
			
			$this->model_extension_payment_paypal->editOrderRecurringStatus($order_recurring_id, 2);
			
			$data['success'] = $this->language->get('success_disable_recurring');	
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
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