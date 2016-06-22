<?php
class ControllerExtensionPaymentPilibaba extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('setting/setting');

		$this->load->model('extension/payment/pilibaba');

		$this->load->language('extension/payment/pilibaba');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pilibaba', $this->request->post);

			if ($this->request->post['pilibaba_status']) {
				$this->model_extension_payment_pilibaba->enablePiliExpress();
			} else {
				$this->model_extension_payment_pilibaba->disablePiliExpress();
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pilibaba', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title']         = $this->language->get('heading_title');

		$data['tab_register']          = $this->language->get('tab_register');
		$data['tab_settings']          = $this->language->get('tab_settings');

		$data['text_payment']          = $this->language->get('text_payment');
		$data['text_edit']             = $this->language->get('text_edit');
		$data['text_live']             = $this->language->get('text_live');
		$data['text_test']             = $this->language->get('text_test');
		$data['text_enabled']          = $this->language->get('text_enabled');
		$data['text_disabled']         = $this->language->get('text_disabled');
		$data['text_other']            = $this->language->get('text_other');

		$data['entry_email_address']   = $this->language->get('entry_email_address');
		$data['entry_password']        = $this->language->get('entry_password');
		$data['entry_currency']        = $this->language->get('entry_currency');
		$data['entry_warehouse']       = $this->language->get('entry_warehouse');
		$data['entry_country']         = $this->language->get('entry_country');
		$data['entry_merchant_number'] = $this->language->get('entry_merchant_number');
		$data['entry_secret_key']      = $this->language->get('entry_secret_key');
		$data['entry_environment']     = $this->language->get('entry_environment');
		$data['entry_shipping_fee']    = $this->language->get('entry_shipping_fee');
		$data['entry_order_status']    = $this->language->get('entry_order_status');
		$data['entry_status']          = $this->language->get('entry_status');
		$data['entry_logging']         = $this->language->get('entry_logging');
		$data['entry_sort_order']      = $this->language->get('entry_sort_order');

		$data['help_email_address']    = $this->language->get('help_email_address');
		$data['help_password']         = $this->language->get('help_password');
		$data['help_currency']         = $this->language->get('help_currency');
		$data['help_warehouse']        = $this->language->get('help_warehouse');
		$data['help_country']          = $this->language->get('help_country');
		$data['help_merchant_number']  = $this->language->get('help_merchant_number');
		$data['help_secret_key']       = $this->language->get('help_secret_key');
		$data['help_shipping_fee']     = $this->language->get('help_shipping_fee');
		$data['help_order_status']     = $this->language->get('help_order_status');
		$data['help_logging']          = $this->language->get('help_logging');

		$data['button_save']           = $this->language->get('button_save');
		$data['button_cancel']         = $this->language->get('button_cancel');
		$data['button_register']       = $this->language->get('button_register');

		$data['action'] = $this->url->link('extension/payment/pilibaba', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);

		if (isset($this->request->post['pilibaba_merchant_number'])) {
			$data['pilibaba_merchant_number'] = $this->request->post['pilibaba_merchant_number'];
		} else {
			$data['pilibaba_merchant_number'] = $this->config->get('pilibaba_merchant_number');
		}

		if (isset($this->request->post['pilibaba_secret_key'])) {
			$data['pilibaba_secret_key'] = $this->request->post['pilibaba_secret_key'];
		} else {
			$data['pilibaba_secret_key'] = $this->config->get('pilibaba_secret_key');
		}

		if (isset($this->request->post['pilibaba_environment'])) {
			$data['pilibaba_environment'] = $this->request->post['pilibaba_environment'];
		} else {
			$data['pilibaba_environment'] = $this->config->get('pilibaba_environment');
		}

		if (isset($this->request->post['pilibaba_shipping_fee'])) {
			$data['pilibaba_shipping_fee'] = $this->request->post['pilibaba_shipping_fee'];
		} else {
			$data['pilibaba_shipping_fee'] = $this->config->get('pilibaba_shipping_fee');
		}

		if (isset($this->request->post['pilibaba_order_status_id'])) {
			$data['pilibaba_order_status_id'] = $this->request->post['pilibaba_order_status_id'];
		} elseif ($this->config->has('pilibaba_order_status_id')) {
			$data['pilibaba_order_status_id'] = $this->config->get('pilibaba_order_status_id');
		} else {
			$data['pilibaba_order_status_id'] = '2';
		}

		if (isset($this->request->post['pilibaba_status'])) {
			$data['pilibaba_status'] = $this->request->post['pilibaba_status'];
		} else {
			$data['pilibaba_status'] = $this->config->get('pilibaba_status');
		}

		if (isset($this->request->post['pilibaba_logging'])) {
			$data['pilibaba_logging'] = $this->request->post['pilibaba_logging'];
		} else {
			$data['pilibaba_logging'] = $this->config->get('pilibaba_logging');
		}

		if (isset($this->request->post['pilibaba_sort_order'])) {
			$data['pilibaba_sort_order'] = $this->request->post['pilibaba_sort_order'];
		} else {
			$data['pilibaba_sort_order'] = $this->config->get('pilibaba_sort_order');
		}

		if (isset($this->request->post['pilibaba_email_address'])) {
			$data['pilibaba_email_address'] = $this->request->post['pilibaba_email_address'];
		} elseif ($this->config->has('pilibaba_email_address')) {
			$data['pilibaba_email_address'] = $this->config->get('pilibaba_email_address');
		} else {
			$data['pilibaba_email_address'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['pilibaba_merchant_number'])) {
			$data['error_pilibaba_merchant_number'] = $this->error['pilibaba_merchant_number'];
		} else {
			$data['error_pilibaba_merchant_number'] = '';
		}

		if (isset($this->error['pilibaba_secret_key'])) {
			$data['error_pilibaba_secret_key'] = $this->error['pilibaba_secret_key'];
		} else {
			$data['error_pilibaba_secret_key'] = '';
		}

		if (isset($this->error['pilibaba_shipping_fee'])) {
			$data['error_pilibaba_shipping_fee'] = $this->error['pilibaba_shipping_fee'];
		} else {
			$data['error_pilibaba_shipping_fee'] = '';
		}

		if ($data['pilibaba_merchant_number'] && $data['pilibaba_secret_key']) {
			$data['show_register'] = false;

			$data['currencies'] = $data['warehouses'] = $data['countries'] = array();
		} else {
			$data['show_register'] = true;

			$data['currencies'] = $this->model_extension_payment_pilibaba->getCurrencies();

			$data['warehouses'] = $this->model_extension_payment_pilibaba->getWarehouses();

			$this->load->model('localisation/country');

			$data['countries'] = $this->model_localisation_country->getCountries();
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if ($this->config->get('config_weight_class_id') != '2') {
			$data['error_weight'] = sprintf($this->language->get('error_weight'), $this->url->link('setting/setting', 'token=' . $this->session->data['token'], true));
		} else {
			$data['error_weight'] = '';
		}

		if ($this->config->has('pilibaba_email_address') && $this->config->get('pilibaba_email_address')) {
			$data['notice_email'] = sprintf($this->language->get('text_email'), $this->config->get('pilibaba_email_address'));
		} else {
			$data['notice_email'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pilibaba', $data));
	}

	public function install() {
		if ($this->user->hasPermission('modify', 'extension/extension')) {
			$this->load->model('extension/payment/pilibaba');

			$this->model_extension_payment_pilibaba->install();
		}
	}

	public function uninstall() {
		if ($this->user->hasPermission('modify', 'extension/extension')) {
			$this->load->model('extension/payment/pilibaba');

			$this->model_extension_payment_pilibaba->uninstall();
		}
	}

	public function register() {
		$this->load->language('extension/payment/pilibaba');

		$json = array();

		if (isset($this->request->post['email_address']) && isset($this->request->post['password']) && isset($this->request->post['currency']) && isset($this->request->post['warehouse']) && isset($this->request->post['country']) && isset($this->request->post['environment'])) {
			if (utf8_strlen($this->request->post['email_address']) < 1) {
				$json['error'] = $this->language->get('error_email_address');
			} else if (!filter_var($this->request->post['email_address'], FILTER_VALIDATE_EMAIL)) {
				$json['error'] = $this->language->get('error_email_invalid');
			} else if (utf8_strlen($this->request->post['password']) < 8) {
				$json['error'] = $this->language->get('error_password');
			} else if (utf8_strlen($this->request->post['currency']) < 1) {
				$json['error'] = $this->language->get('error_currency');
			} else if (utf8_strlen($this->request->post['warehouse']) < 1) {
				$json['error'] = $this->language->get('error_warehouse');
			} else if ($this->request->post['warehouse'] == 'other' && utf8_strlen($this->request->post['country']) < 1) {
				$json['error'] = $this->language->get('error_country');
			} else {
				$this->load->model('extension/payment/pilibaba');

				$response = $this->model_extension_payment_pilibaba->register($this->request->post['email_address'], $this->request->post['password'], $this->request->post['currency'], $this->request->post['warehouse'], $this->request->post['country'], $this->request->post['environment']);

				if (isset($response['code']) && isset($response['message'])) {
					if ($response['code'] == '0') {
						$this->load->model('setting/setting');

						$this->model_setting_setting->editSetting('pilibaba', array('pilibaba_merchant_number' => $response['data']['merchantNo'], 'pilibaba_secret_key' => $response['data']['privateKey'], 'pilibaba_email_address' => $this->request->post['email_address'], 'pilibaba_environment' => $this->request->post['environment']), 0);

						$this->session->data['success'] = $this->language->get('text_register_success');

						$json['redirect'] = $this->url->link('extension/payment/pilibaba', 'token=' . $this->session->data['token'], true);
					} else {
						$json['error'] = $response['message'];
					}
				} else {
					$json['error'] = $this->language->get('error_bad_response');
				}
			}
		} else {
			$json['error'] = $this->language->get('error_data_missing');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function order() {
		if ($this->config->get('pilibaba_status')) {
			$this->load->model('extension/payment/pilibaba');

			$order_id = $this->request->get['order_id'];

			$pilibaba_order = $this->model_extension_payment_pilibaba->getOrder($this->request->get['order_id']);

			if ($pilibaba_order) {
				$this->load->language('extension/payment/pilibaba');

				$order_info['order_id'] = $pilibaba_order['order_id'];

				$order_info['amount'] = '&yen;' . $pilibaba_order['amount'];

				$order_info['fee'] = '&yen;' . $pilibaba_order['fee'];

				$order_info['status'] = 'Success';

				$order_info['date_added'] = date($this->language->get('datetime_format'), strtotime($pilibaba_order['date_added']));

				$order_info['tracking'] = $pilibaba_order['tracking'];

				$data['pilibaba_order'] = $order_info;

				$data['text_payment_info'] = $this->language->get('text_payment_info');
				$data['text_order_id']     = $this->language->get('text_order_id');
				$data['text_amount']       = $this->language->get('text_amount');
				$data['text_fee']          = $this->language->get('text_fee');
				$data['text_date_added']   = $this->language->get('text_date_added');
				$data['text_tracking']     = $this->language->get('text_tracking');
				$data['text_barcode']      = $this->language->get('text_barcode');
				$data['text_barcode_info'] = $this->language->get('text_barcode_info');
				$data['text_confirm']      = $this->language->get('text_confirm');

				$data['button_tracking']   = $this->language->get('button_tracking');
				$data['button_barcode']    = $this->language->get('button_barcode');

				$data['barcode'] = $this->url->link('extension/payment/pilibaba/barcode', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], true);

				$data['order_id'] = $this->request->get['order_id'];

				$data['token'] = $this->request->get['token'];

				return $this->load->view('extension/payment/pilibaba_order', $data);
			}
		}
	}

	public function tracking() {
		$this->load->language('extension/payment/pilibaba');

		$json = array();

		if ($this->config->get('pilibaba_status')) {
			if (isset($this->request->post['order_id']) && isset($this->request->post['tracking'])) {
				if (utf8_strlen($this->request->post['tracking']) > 0 && utf8_strlen($this->request->post['tracking']) <= 50) {
					$this->load->model('extension/payment/pilibaba');

					$this->model_extension_payment_pilibaba->updateTrackingNumber($this->request->post['order_id'], $this->request->post['tracking'], $this->config->get('pilibaba_merchant_number'));

					$json['success'] = $this->language->get('text_tracking_success');
				} else {
					$json['error'] = $this->language->get('error_tracking_length');
				}
			} else {
				$json['error'] = $this->language->get('error_data_missing');
			}
		} else {
			$json['error'] = $this->language->get('error_not_enabled');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function barcode() {
		if ($this->config->get('pilibaba_status')) {
			if (isset($this->request->get['order_id'])) {
				if ($this->config->get('pilibaba_environment') == 'live') {
					$url = 'https://www.pilibaba.com/pilipay/barCode';
				} else {
					$url = 'http://pre.pilibaba.com/pilipay/barCode';
				}

				echo '<img src="' . $url . '?orderNo=' . $this->request->get['order_id'] . '&merchantNo=' . $this->config->get('pilibaba_merchant_number') . '">';
			}
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/pilibaba')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pilibaba_merchant_number']) {
			$this->error['pilibaba_merchant_number'] = $this->language->get('error_merchant_number');
		}

		if (!$this->request->post['pilibaba_secret_key']) {
			$this->error['pilibaba_secret_key'] = $this->language->get('error_secret_key');
		}

		if ($this->request->post['pilibaba_shipping_fee'] != '' && strpos($this->request->post['pilibaba_shipping_fee'], '.') === false) {
			$this->error['pilibaba_shipping_fee'] = $this->language->get('error_shipping_fee');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}