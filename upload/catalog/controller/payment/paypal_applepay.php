<?php
namespace Opencart\Catalog\Controller\Extension\PayPal\Payment;
class PayPalApplePay extends \Opencart\System\Engine\Controller {
	private $error = [];
	private $separator = '';

	public function __construct($registry) {
		parent::__construct($registry);

		if (VERSION >= '4.0.2.0') {
			$this->separator = '.';
		} else {
			$this->separator = '|';
		}
	}

	public function index(): string {
		$this->load->model('extension/paypal/payment/paypal');

		$agree_status = $this->model_extension_paypal_payment_paypal->getAgreeStatus();

		if ($this->config->get('payment_paypal_status') && $this->config->get('payment_paypal_client_id') && $this->config->get('payment_paypal_secret') && $agree_status) {
			$this->load->language('extension/paypal/payment/paypal');

			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
			$_config->load('paypal');

			$config_setting = $_config->get('paypal_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

			$data['client_id'] = $this->config->get('payment_paypal_client_id');
			$data['secret'] = $this->config->get('payment_paypal_secret');
			$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
			$data['environment'] = $this->config->get('payment_paypal_environment');
			$data['partner_id'] = $setting['partner'][$data['environment']]['partner_id'];
			$data['partner_attribution_id'] = $setting['partner'][$data['environment']]['partner_attribution_id'];
			$data['checkout_mode'] = $setting['general']['checkout_mode'];
			$data['transaction_method'] = $setting['general']['transaction_method'];

			if ($setting['applepay_button']['status']) {
				$data['applepay_button_status'] = $setting['applepay_button']['status'];
			}

			require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

			$paypal_info = [
				'partner_id'             => $data['partner_id'],
				'client_id'              => $data['client_id'],
				'secret'                 => $data['secret'],
				'environment'            => $data['environment'],
				'partner_attribution_id' => $data['partner_attribution_id']
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

			if (!empty($this->error['warning'])) {
				$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
			}

			$data['separator'] = $this->separator;

			$data['language'] = $this->config->get('config_language');

			$data['error'] = $this->error;

			return $this->load->view('extension/paypal/payment/paypal_applepay', $data);
		}

		return '';
	}

	public function modal(): void {
		$this->load->language('extension/paypal/payment/paypal');

		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'paypal/system/config/');
		$_config->load('paypal');

		$config_setting = $_config->get('paypal_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_paypal_setting'));

		$data['client_id'] = $this->config->get('payment_paypal_client_id');
		$data['secret'] = $this->config->get('payment_paypal_secret');
		$data['merchant_id'] = $this->config->get('payment_paypal_merchant_id');
		$data['environment'] = $this->config->get('payment_paypal_environment');
		$data['partner_id'] = $setting['partner'][$data['environment']]['partner_id'];
		$data['partner_attribution_id'] = $setting['partner'][$data['environment']]['partner_attribution_id'];
		$data['transaction_method'] = $setting['general']['transaction_method'];

		if ($setting['applepay_button']['status']) {
			$data['applepay_button_status'] = $setting['applepay_button']['status'];
		}

		require_once DIR_EXTENSION . 'paypal/system/library/paypal.php';

		$paypal_info = [
			'partner_id'             => $data['partner_id'],
			'client_id'              => $data['client_id'],
			'secret'                 => $data['secret'],
			'environment'            => $data['environment'],
			'partner_attribution_id' => $data['partner_attribution_id']
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

		if (!empty($this->error['warning'])) {
			$this->error['warning'] .= ' ' . sprintf($this->language->get('error_payment'), $this->url->link('information/contact', 'language=' . $this->config->get('config_language')));
		}

		$data['language'] = $this->config->get('config_language');

		$data['error'] = $this->error;

		$this->response->setOutput($this->load->view('extension/paypal/payment/paypal_applepay_modal', $data));
	}
}
