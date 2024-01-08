<?php
namespace Opencart\Admin\Controller\Extension\PayPal\Payment;
class PayPal extends \Opencart\System\Engine\Controller {
	private $error = [];
	private $separator = '';

	public function __construct($registry) {
		parent::__construct($registry);

		if (VERSION >= '4.0.2.0') {
			$this->separator = '.';
		} else {
			$this->separator = '|';
		}

		if (empty($this->config->get('paypal_version')) || (!empty($this->config->get('paypal_version')) && ($this->config->get('paypal_version') < '2.2.0'))) {
			$this->uninstall();
			$this->install();
		}
	}

	public function index(): void {
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$config_setting = $_config->get('paypal_setting');

		$cache_data = $this->cache->get('paypal');

		$this->cache->delete('paypal');

		if (!empty($cache_data['environment']) && !empty($cache_data['authorization_code']) && !empty($cache_data['shared_id']) && !empty($cache_data['seller_nonce']) && !empty($this->request->get['merchantIdInPayPal'])) {
			$this->load->language('extension/paypal/payment/paypal');

			$this->load->model('extension/paypal/payment/paypal');

			$environment = $cache_data['environment'];

			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'client_id'              => $cache_data['shared_id'],
				'environment'            => $environment,
				'partner_attribution_id' => $config_setting['partner'][$environment]['partner_attribution_id']
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type'    => 'authorization_code',
				'code'          => $cache_data['authorization_code'],
				'code_verifier' => $cache_data['seller_nonce']
			];

			$paypal->setAccessToken($token_info);

			$result = $paypal->getSellerCredentials($config_setting['partner'][$environment]['partner_id']);

			$client_id = '';
			$secret = '';

			if (isset($result['client_id']) && isset($result['client_secret'])) {
				$client_id = $result['client_id'];
				$secret = $result['client_secret'];
			}

			$paypal_info = [
				'partner_id'             => $config_setting['partner'][$environment]['partner_id'],
				'client_id'              => $client_id,
				'secret'                 => $secret,
				'environment'            => $environment,
				'partner_attribution_id' => $config_setting['partner'][$environment]['partner_attribution_id']
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$webhook_token = sha1(uniqid(mt_rand(), 1));
			$cron_token = sha1(uniqid(mt_rand(), 1));

			$webhook_info = [
				'url'         => HTTP_CATALOG . 'index.php?route=extension/paypal/payment/paypal&webhook_token=' . $webhook_token,
				'event_types' => [
					['name' => 'PAYMENT.AUTHORIZATION.CREATED'],
					['name' => 'PAYMENT.AUTHORIZATION.VOIDED'],
					['name' => 'PAYMENT.CAPTURE.COMPLETED'],
					['name' => 'PAYMENT.CAPTURE.DENIED'],
					['name' => 'PAYMENT.CAPTURE.PENDING'],
					['name' => 'PAYMENT.CAPTURE.REFUNDED'],
					['name' => 'PAYMENT.CAPTURE.REVERSED'],
					['name' => 'CHECKOUT.ORDER.COMPLETED']
				]
			];

			$result = $paypal->createWebhook($webhook_info);

			$webhook_id = '';

			if (isset($result['id'])) {
				$webhook_id = $result['id'];
			}

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}

			$merchant_id = $this->request->get['merchantIdInPayPal'];

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

			if (!$this->error) {
				$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
			}
		}

		if (!$this->config->get('payment_paypal_client_id')) {
			$this->auth();
		} else {
			$this->dashboard();
		}
	}

	public function auth(): void {
		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['partner_url'] = str_replace('&amp;', '%26', $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		$data['callback_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'callback', 'user_token=' . $this->session->data['user_token']));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$data['setting'] = $_config->get('paypal_setting');

		if (isset($this->session->data['environment'])) {
			$data['environment'] = $this->session->data['environment'];
		} else {
			$data['environment'] = 'production';
		}

		$data['seller_nonce'] = $this->token(50);

		$data['configure_url'] = [
			'production' => [
				'ppcp'             => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=PPCP,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['production']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['production']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=EXPRESS_CHECKOUT,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			],
			'sandbox' => [
				'ppcp'             => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=PPCP,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce'],
				'express_checkout' => 'https://www.sandbox.paypal.com/bizsignup/partner/entry?partnerId=' . $data['setting']['partner']['sandbox']['partner_id'] . '&partnerClientId=' . $data['setting']['partner']['sandbox']['client_id'] . '&features=PAYMENT,REFUND,ACCESS_MERCHANT_INFORMATION,VAULT,BILLING_AGREEMENT&product=EXPRESS_CHECKOUT,ADVANCED_VAULTING&capabilities=PAYPAL_WALLET_VAULTING_ADVANCED&integrationType=FO&returnToPartnerUrl=' . $data['partner_url'] . '&displayMode=minibrowser&sellerNonce=' . $data['seller_nonce']
			]
		];

		$data['text_checkout_express'] = sprintf($this->language->get('text_checkout_express'), $data['configure_url'][$data['environment']]['express_checkout']);
		$data['text_support'] = sprintf($this->language->get('text_support'), $this->request->server['HTTP_HOST']);

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/auth', $data));
	}

	public function dashboard(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');
		$this->load->model('setting/setting');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');
		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/bootstrap-switch.css');

		$this->document->addScript('../extension/paypal/admin/view/javascript/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['sale_analytics_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'getSaleAnalytics', 'user_token=' . $this->session->data['user_token']));
		$data['agree_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
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

		if ($data['setting']['googlepay_button']['status']) {
			$data['googlepay_button_status'] = 1;
		} else {
			$data['googlepay_button_status'] = 0;
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

		$paypal_sale_total = $this->model_extension_paypal_payment_paypal->getTotalSales();

		$data['paypal_sale_total'] = $this->currency->format($paypal_sale_total, $this->config->get('config_currency'));

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/dashboard', $data));
	}

	public function general(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');
		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/bootstrap-switch.css');

		$this->document->addScript('../extension/paypal/admin/view/javascript/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		// Action
		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['disconnect_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'disconnect', 'user_token=' . $this->session->data['user_token']));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
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

		$data['geo_zone_id'] = $this->config->get('payment_paypal_geo_zone_id');
		$data['sort_order'] = $this->config->get('payment_paypal_sort_order');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$data['cron_url'] = HTTP_CATALOG . 'index.php?route=extension/paypal/payment/paypal&cron_token=' . $data['setting']['general']['cron_token'];

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/general', $data));
	}

	public function button(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');
		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/bootstrap-switch.css');

		$this->document->addScript('../extension/paypal/admin/view/javascript/paypal.js');
		$this->document->addScript('../extension/paypal/admin/view/javascript/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		// Action
		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$data['setting'] = $_config->get('paypal_setting');

		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));

		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_paypal_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);

		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];

		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];

		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];

		if ($data['client_id'] && $data['secret']) {
			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'client_id'              => $data['client_id'],
				'secret'                 => $data['secret'],
				'environment'            => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$data['client_token'] = $paypal->getClientToken();

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}
		}

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/button', $data));
	}

	public function googlepay_button(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');
		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/bootstrap-switch.css');

		$this->document->addScript('../extension/paypal/admin/view/javascript/paypal.js');
		$this->document->addScript('../extension/paypal/admin/view/javascript/bootstrap-switch.js');
		$this->document->addScript('https://pay.google.com/gp/p/js/pay.js');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		// Action
		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$data['setting'] = $_config->get('paypal_setting');

		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));

		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_paypal_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);

		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];

		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];

		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];

		if ($data['client_id'] && $data['secret']) {
			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'client_id'              => $data['client_id'],
				'secret'                 => $data['secret'],
				'environment'            => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$data['client_token'] = $paypal->getClientToken();

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}
		}

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/googlepay_button', $data));
	}

	public function applepay_button(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');
		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/bootstrap-switch.css');

		$this->document->addScript('../extension/paypal/admin/view/javascript/paypal.js');
		$this->document->addScript('../extension/paypal/admin/view/javascript/bootstrap-switch.js');
		$this->document->addScript('https://applepay.cdn-apple.com/jsapi/v1/apple-pay-sdk.js');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		// Action
		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['applepay_download_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'downloadAssociationFile', 'user_token=' . $this->session->data['user_token']));
		$data['applepay_download_host_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'downloadHostAssociationFile', 'user_token=' . $this->session->data['user_token']));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$data['setting'] = $_config->get('paypal_setting');

		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));

		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_paypal_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);

		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];

		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];

		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];

		if ($data['client_id'] && $data['secret']) {
			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'client_id'              => $data['client_id'],
				'secret'                 => $data['secret'],
				'environment'            => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$data['client_token'] = $paypal->getClientToken();

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}
		}

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/applepay_button', $data));
	}

	public function card(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');
		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/card.css');
		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/bootstrap-switch.css');

		$this->document->addScript('../extension/paypal/admin/view/javascript/paypal.js');
		$this->document->addScript('../extension/paypal/admin/view/javascript/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		// Action
		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$data['setting'] = $_config->get('paypal_setting');

		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));

		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_paypal_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);

		$data['locale'] = preg_replace('/-(.+?)+/', '', $this->config->get('config_language')) . '_' . $country['iso_code_2'];

		$data['currency_code'] = $data['setting']['general']['currency_code'];
		$data['currency_value'] = $data['setting']['general']['currency_value'];

		$data['decimal_place'] = $data['setting']['currency'][$data['currency_code']]['decimal_place'];

		if ($data['client_id'] && $data['secret']) {
			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'client_id'              => $data['client_id'],
				'secret'                 => $data['secret'],
				'environment'            => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$data['client_token'] = $paypal->getClientToken();

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}
		}

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/card', $data));
	}

	public function message(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');
		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/bootstrap-switch.css');

		$this->document->addScript('../extension/paypal/admin/view/javascript/paypal.js');
		$this->document->addScript('../extension/paypal/admin/view/javascript/bootstrap-switch.js');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		// Action
		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$data['setting'] = $_config->get('paypal_setting');

		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));

		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['webhook_id'] = $this->config->get('payment_paypal_webhook_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_attribution_id'] = $data['setting']['partner'][$data['environment']]['partner_attribution_id'];

		$country = $this->model_extension_paypal_payment_paypal->getCountryByCode($data['setting']['general']['country_code']);

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
			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'client_id'              => $data['client_id'],
				'secret'                 => $data['secret'],
				'environment'            => $data['environment'],
				'partner_attribution_id' => $data['setting']['partner'][$data['environment']]['partner_attribution_id']
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$data['client_token'] = $paypal->getClientToken();

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}
		}

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/message', $data));
	}

	public function order_status(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		// Action
		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$data['setting'] = $_config->get('paypal_setting');

		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/order_status', $data));
	}

	public function contact(): void {
		if (!$this->config->get('payment_paypal_client_id')) {
			$this->response->redirect($this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token']));
		}

		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->document->addStyle('../extension/paypal/admin/view/stylesheet/paypal.css');

		$this->document->setTitle($this->language->get('heading_title_main'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/paypal/payment/paypal', 'user_token=' . $this->session->data['user_token'])
		];

		// Action
		$data['href_dashboard'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'dashboard', 'user_token=' . $this->session->data['user_token']);
		$data['href_general'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'general', 'user_token=' . $this->session->data['user_token']);
		$data['href_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'button', 'user_token=' . $this->session->data['user_token']);
		$data['href_googlepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'googlepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_applepay_button'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'applepay_button', 'user_token=' . $this->session->data['user_token']);
		$data['href_card'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'card', 'user_token=' . $this->session->data['user_token']);
		$data['href_message'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'message', 'user_token=' . $this->session->data['user_token']);
		$data['href_order_status'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'order_status', 'user_token=' . $this->session->data['user_token']);
		$data['href_contact'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'contact', 'user_token=' . $this->session->data['user_token']);

		$data['save'] = $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');
		$data['contact_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'sendContact', 'user_token=' . $this->session->data['user_token']));
		$data['agree_url'] =  str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'agree', 'user_token=' . $this->session->data['user_token']));

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$data['setting'] = $_config->get('paypal_setting');

		$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_paypal_setting'));

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$result = $this->model_extension_paypal_payment_paypal->checkVersion(VERSION, $data['setting']['version']);

		if (!empty($result['href'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), $result['href']);
		} else {
			$data['text_version'] = '';
		}

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

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

		$this->response->setOutput($this->load->view('extension/paypal/payment/contact', $data));
	}

	public function save(): void {
		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

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

	public function disconnect(): void {
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

	public function callback(): void {
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

	public function getSaleAnalytics(): void {
		$this->load->language('extension/paypal/payment/paypal');

		$data = [];

		$this->load->model('extension/paypal/payment/paypal');

		$data['all_sale'] = [];
		$data['paypal_sale'] = [];
		$data['xaxis'] = [];

		$data['all_sale']['label'] = $this->language->get('text_all_sales');
		$data['paypal_sale']['label'] = $this->language->get('text_paypal_sales');
		$data['all_sale']['data'] = [];
		$data['paypal_sale']['data'] = [];

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}

		switch ($range) {
			default:
			case 'day':
				$results = $this->model_extension_paypal_payment_paypal->getTotalSalesByDay();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = [$key, $value['total']];
					$data['paypal_sale']['data'][] = [$key, $value['paypal_total']];
				}

				for ($i = 0; $i < 24; $i++) {
					$data['xaxis'][] = [$i, $i];
				}

				break;
			case 'week':
				$results = $this->model_extension_paypal_payment_paypal->getTotalSalesByWeek();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = [$key, $value['total']];
					$data['paypal_sale']['data'][] = [$key, $value['paypal_total']];
				}

				$date_start = strtotime('-' . date('w') . ' days');

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$data['xaxis'][] = [date('w', strtotime($date)), date('D', strtotime($date))];
				}

				break;
			case 'month':
				$results = $this->model_extension_paypal_payment_paypal->getTotalSalesByMonth();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = [$key, $value['total']];
					$data['paypal_sale']['data'][] = [$key, $value['paypal_total']];
				}

				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$data['xaxis'][] = [date('j', strtotime($date)), date('d', strtotime($date))];
				}

				break;
			case 'year':
				$results = $this->model_extension_paypal_payment_paypal->getTotalSalesByYear();

				foreach ($results as $key => $value) {
					$data['all_sale']['data'][] = [$key, $value['total']];
					$data['paypal_sale']['data'][] = [$key, $value['paypal_total']];
				}

				for ($i = 1; $i <= 12; $i++) {
					$data['xaxis'][] = [$i, date('M', mktime(0, 0, 0, $i))];
				}

				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function downloadAssociationFile(): void {
		$environment = $this->config->get('payment_paypal_environment');

		if ($environment == 'production') {
			$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association';

			$file_headers = @get_headers($file);

			if (str_contains($file_headers[0], '404')) {
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

	public function downloadHostAssociationFile(): void {
		$this->load->language('extension/paypal/payment/paypal');

		$environment = $this->config->get('payment_paypal_environment');

		if ($environment == 'production') {
			$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association';

			$file_headers = @get_headers($file);

			if (str_contains($file_headers[0], '404')) {
				$file = 'https://www.paypalobjects.com/.well-known/apple-developer-merchantid-domain-association.txt';
			}
		} else {
			$file = 'https://www.paypalobjects.com/sandbox/apple-developer-merchantid-domain-association';
		}

		$content = file_get_contents($file);

		if ($content) {
			$dir = str_replace('admin/', '.well-known/', DIR_APPLICATION);

			if (!file_exists($dir)) {
				mkdir($dir, 0o777, true);
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

	public function sendContact(): void {
		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		if (isset($this->request->post['payment_paypal_setting']['contact'])) {
			$this->model_extension_paypal_payment_paypal->sendContact($this->request->post['payment_paypal_setting']['contact']);

			$data['success'] = $this->language->get('success_send');
		}

		$data['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function agree(): void {
		$this->load->language('extension/paypal/payment/paypal');

		$this->load->model('extension/paypal/payment/paypal');

		$this->model_extension_paypal_payment_paypal->setAgreeStatus();

		$data['success'] = $this->language->get('success_agree');

		$data['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function install(): void {
		$this->load->model('extension/paypal/payment/paypal');

		$this->model_extension_paypal_payment_paypal->install();

		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('paypal_order_info');
		$this->model_setting_event->deleteEventByCode('paypal_header');
		$this->model_setting_event->deleteEventByCode('paypal_extension_get_extensions_by_type');
		$this->model_setting_event->deleteEventByCode('paypal_extension_get_extension_by_code');
		$this->model_setting_event->deleteEventByCode('paypal_order_delete_order');

		if (VERSION >= '4.0.2.0') {
			$this->model_setting_event->addEvent(['code' => 'paypal_order_info', 'description' => '', 'trigger' => 'admin/view/sale/order_info/before', 'action' => 'extension/paypal/payment/paypal.order_info_before', 'status' => true, 'sort_order' => 1]);
			$this->model_setting_event->addEvent(['code' => 'paypal_header', 'description' => '', 'trigger' => 'catalog/controller/common/header/before', 'action' => 'extension/paypal/payment/paypal.header_before', 'status' => true, 'sort_order' => 2]);
			$this->model_setting_event->addEvent(['code' => 'paypal_order_delete_order', 'description' => '', 'trigger' => 'catalog/model/checkout/order/deleteOrder/before', 'action' => 'extension/paypal/payment/paypal.order_delete_order_before', 'status' => true, 'sort_order' => 3]);
		} elseif (VERSION >= '4.0.1.0') {
			$this->model_setting_event->addEvent(['code' => 'paypal_order_info', 'description' => '', 'trigger' => 'admin/view/sale/order_info/before', 'action' => 'extension/paypal/payment/paypal|order_info_before', 'status' => true, 'sort_order' => 1]);
			$this->model_setting_event->addEvent(['code' => 'paypal_header', 'description' => '', 'trigger' => 'catalog/controller/common/header/before', 'action' => 'extension/paypal/payment/paypal|header_before', 'status' => true, 'sort_order' => 2]);
			$this->model_setting_event->addEvent(['code' => 'paypal_extension_get_extensions_by_type', 'description' => '', 'trigger' => 'catalog/model/setting/extension/getExtensionsByType/after', 'action' => 'extension/paypal/payment/paypal|extension_get_extensions_by_type_after', 'status' => true, 'sort_order' => 3]);
			$this->model_setting_event->addEvent(['code' => 'paypal_extension_get_extension_by_code', 'description' => '', 'trigger' => 'catalog/model/setting/extension/getExtensionByCode/after', 'action' => 'extension/paypal/payment/paypal|extension_get_extension_by_code_after', 'status' => true, 'sort_order' => 4]);
			$this->model_setting_event->addEvent(['code' => 'paypal_order_delete_order', 'description' => '', 'trigger' => 'catalog/model/checkout/order/deleteOrder/before', 'action' => 'extension/paypal/payment/paypal|order_delete_order_before', 'status' => true, 'sort_order' => 6]);
		} else {
			$this->model_setting_event->addEvent('paypal_order_info', '', 'admin/view/sale/order_info/before', 'extension/paypal/payment/paypal|order_info_before', true, 1);
			$this->model_setting_event->addEvent('paypal_header', '', 'catalog/controller/common/header/before', 'extension/paypal/payment/paypal|header_before', true, 2);
			$this->model_setting_event->addEvent('paypal_extension_get_extensions_by_type', '', 'catalog/model/setting/extension/getExtensionsByType/after', 'extension/paypal/payment/paypal|extension_get_extensions_by_type_after', true, 3);
			$this->model_setting_event->addEvent('paypal_extension_get_extension_by_code', '', 'catalog/model/setting/extension/getExtensionByCode/after', 'extension/paypal/payment/paypal|extension_get_extension_by_code_after', true, 4);
			$this->model_setting_event->addEvent('paypal_order_delete_order', '', 'catalog/model/checkout/order/deleteOrder/before', 'extension/paypal/payment/paypal|order_delete_order_before', true, 5);
		}

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$config_setting = $_config->get('paypal_setting');

		$setting['paypal_version'] = $config_setting['version'];

		$this->load->model('setting/setting');

		$this->model_setting_setting->editSetting('paypal_version', $setting);
		$this->model_setting_setting->editValue('config', 'config_session_samesite', 'Lax');
	}

	public function uninstall(): void {
		$this->load->model('extension/paypal/payment/paypal');

		$this->model_extension_paypal_payment_paypal->uninstall();

		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('paypal_order_info');
		$this->model_setting_event->deleteEventByCode('paypal_header');
		$this->model_setting_event->deleteEventByCode('paypal_extension_get_extensions_by_type');
		$this->model_setting_event->deleteEventByCode('paypal_extension_get_extension_by_code');
		$this->model_setting_event->deleteEventByCode('paypal_order_delete_order');

		$this->load->model('setting/setting');

		$this->model_setting_setting->deleteSetting('paypal_version');
	}

	public function order_info_before(string $route, array &$data): void {
		if ($data['tabs']) {
			foreach ($data['tabs'] as $tab_key => $tab) {
				if ($tab['code'] == 'paypal') {
					unset($data['tabs'][$tab_key]);
				}
			}
		}

		if ($this->config->get('payment_paypal_status') && !empty($this->request->get['order_id'])) {
			$this->load->language('extension/paypal/payment/paypal');

			$this->load->model('extension/paypal/payment/paypal');

			$data['order_id'] = $this->request->get['order_id'];

			$paypal_order_info = $this->model_extension_paypal_payment_paypal->getPayPalOrder($data['order_id']);

			if ($paypal_order_info) {
				$data['transaction_id'] = $paypal_order_info['transaction_id'];
				$data['transaction_status'] = $paypal_order_info['transaction_status'];

				if ($paypal_order_info['environment'] == 'production') {
					$data['transaction_url'] = 'https://www.paypal.com/activity/payment/' . $data['transaction_id'];
				} else {
					$data['transaction_url'] = 'https://www.sandbox.paypal.com/activity/payment/' . $data['transaction_id'];
				}

				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'getPaymentInfo', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $data['order_id']));
				$data['capture_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'capturePayment', 'user_token=' . $this->session->data['user_token']));
				$data['reauthorize_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'reauthorizePayment', 'user_token=' . $this->session->data['user_token']));
				$data['void_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'voidPayment', 'user_token=' . $this->session->data['user_token']));
				$data['refund_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'refundPayment', 'user_token=' . $this->session->data['user_token']));

				$data['tabs'][] = [
					'code'    => 'paypal',
					'title'   => $this->language->get('heading_title_main'),
					'content' => $this->load->view('extension/paypal/payment/order', $data)
				];
			}

			$this->load->language('sale/order');
		}
	}

	public function getPaymentInfo(): void {
		$content = '';

		if ($this->config->get('payment_paypal_status') && !empty($this->request->get['order_id'])) {
			$this->load->language('extension/paypal/payment/paypal');

			$this->load->model('extension/paypal/payment/paypal');

			$data['order_id'] = $this->request->get['order_id'];

			$paypal_order_info = $this->model_extension_paypal_payment_paypal->getPayPalOrder($data['order_id']);

			if ($paypal_order_info) {
				$data['transaction_id'] = $paypal_order_info['transaction_id'];
				$data['transaction_status'] = $paypal_order_info['transaction_status'];

				if ($paypal_order_info['environment'] == 'production') {
					$data['transaction_url'] = 'https://www.paypal.com/activity/payment/' . $data['transaction_id'];
				} else {
					$data['transaction_url'] = 'https://www.sandbox.paypal.com/activity/payment/' . $data['transaction_id'];
				}

				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'getPaymentInfo', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $data['order_id']));
				$data['capture_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'capturePayment', 'user_token=' . $this->session->data['user_token']));
				$data['reauthorize_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'reauthorizePayment', 'user_token=' . $this->session->data['user_token']));
				$data['void_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'voidPayment', 'user_token=' . $this->session->data['user_token']));
				$data['refund_url'] = str_replace('&amp;', '&', $this->url->link('extension/paypal/payment/paypal' . $this->separator . 'refundPayment', 'user_token=' . $this->session->data['user_token']));

				$content = $this->load->view('extension/paypal/payment/order', $data);
			}
		}

		$this->response->setOutput($content);
	}

	public function capturePayment(): void {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/paypal/payment/paypal');

			$this->load->model('extension/paypal/payment/paypal');

			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];

			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
			$_config->load('paypal');

			$config_setting = $_config->get('paypal_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];

			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'partner_id'             => $partner_id,
				'client_id'              => $client_id,
				'secret'                 => $secret,
				'environment'            => $environment,
				'partner_attribution_id' => $partner_attribution_id
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$result = $paypal->setPaymentCapture($transaction_id);

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}

			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_id = $result['id'];
				$transaction_status = 'completed';

				$paypal_order_data = [
					'order_id'           => $order_id,
					'transaction_id'     => $transaction_id,
					'transaction_status' => $transaction_status
				];

				$this->model_extension_paypal_payment_paypal->editPayPalOrder($paypal_order_data);

				$data['success'] = $this->language->get('success_capture_payment');
			}
		}

		$data['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function reauthorizePayment(): void {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/paypal/payment/paypal');

			$this->load->model('extension/paypal/payment/paypal');

			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];

			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
			$_config->load('paypal');

			$config_setting = $_config->get('paypal_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];

			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'partner_id'             => $partner_id,
				'client_id'              => $client_id,
				'secret'                 => $secret,
				'environment'            => $environment,
				'partner_attribution_id' => $partner_attribution_id
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$result = $paypal->setPaymentReauthorize($transaction_id);

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}

			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_id = $result['id'];
				$transaction_status = 'created';

				$paypal_order_data = [
					'order_id'           => $order_id,
					'transaction_id'     => $transaction_id,
					'transaction_status' => $transaction_status
				];

				$this->model_extension_paypal_payment_paypal->editPayPalOrder($paypal_order_data);

				$data['success'] = $this->language->get('success_reauthorize_payment');
			}
		}

		$data['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function voidPayment(): void {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/paypal/payment/paypal');

			$this->load->model('extension/paypal/payment/paypal');

			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];

			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
			$_config->load('paypal');

			$config_setting = $_config->get('paypal_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];

			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'partner_id'             => $partner_id,
				'client_id'              => $client_id,
				'secret'                 => $secret,
				'environment'            => $environment,
				'partner_attribution_id' => $partner_attribution_id
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$result = $paypal->setPaymentVoid($transaction_id);

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}

			if (!$this->error) {
				$transaction_status = 'voided';

				$paypal_order_data = [
					'order_id'           => $order_id,
					'transaction_status' => $transaction_status
				];

				$this->model_extension_paypal_payment_paypal->editPayPalOrder($paypal_order_data);

				$data['success'] = $this->language->get('success_void_payment');
			}
		}

		$data['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function refundPayment(): void {
		if ($this->config->get('payment_paypal_status') && !empty($this->request->post['order_id']) && !empty($this->request->post['transaction_id'])) {
			$this->load->language('extension/paypal/payment/paypal');

			$this->load->model('extension/paypal/payment/paypal');

			$order_id = $this->request->post['order_id'];
			$transaction_id = $this->request->post['transaction_id'];

			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
			$_config->load('paypal');

			$config_setting = $_config->get('paypal_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

			$client_id = $this->config->get('payment_paypal_client_id');
			$secret = $this->config->get('payment_paypal_secret');
			$environment = $this->config->get('payment_paypal_environment');
			$partner_id = $setting['partner'][$environment]['partner_id'];
			$partner_attribution_id = $setting['partner'][$environment]['partner_attribution_id'];
			$transaction_method = $setting['general']['transaction_method'];

			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'partner_id'             => $partner_id,
				'client_id'              => $client_id,
				'secret'                 => $secret,
				'environment'            => $environment,
				'partner_attribution_id' => $partner_attribution_id
			];

			$paypal = new \Opencart\System\Library\PayPal($paypal_info);

			$token_info = [
				'grant_type' => 'client_credentials'
			];

			$paypal->setAccessToken($token_info);

			$result = $paypal->setPaymentRefund($transaction_id);

			if ($paypal->hasErrors()) {
				$error_messages = [];

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

					$this->model_extension_paypal_payment_paypal->log($error, $error['message']);
				}

				$this->error['warning'] = implode(' ', $error_messages);
			}

			if (isset($result['id']) && isset($result['status']) && !$this->error) {
				$transaction_status = 'refunded';

				$paypal_order_data = [
					'order_id'           => $order_id,
					'transaction_status' => $transaction_status
				];

				$this->model_extension_paypal_payment_paypal->editPayPalOrder($paypal_order_data);

				$data['success'] = $this->language->get('success_refund_payment');
			}
		}

		$data['error'] = $this->error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	private function validate(): array|bool {
		if (!$this->user->hasPermission('modify', 'extension/paypal/payment/paypal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	private function token($length = 32): string {
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
