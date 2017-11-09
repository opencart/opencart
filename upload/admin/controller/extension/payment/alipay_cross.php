<?php
class ControllerExtensionPaymentAlipayCross extends Controller {
	private $error = array();
	private $currencies = array('GBP', 'HKD', 'USD', 'CHF', 'SGD', 'SEK', 'DKK', 'NOK', 'JPY', 'CAD', 'AUD', 'EUR', 'NZD', 'KRW', 'THB');

	public function index() {
		$this->load->language('extension/payment/alipay_cross');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_alipay_cross', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['app_id'])) {
			$data['error_app_id'] = $this->error['app_id'];
		} else {
			$data['error_app_id'] = '';
		}

		if (isset($this->error['merchant_private_key'])) {
			$data['error_merchant_private_key'] = $this->error['merchant_private_key'];
		} else {
			$data['error_merchant_private_key'] = '';
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
			'href' => $this->url->link('extension/payment/alipay_cross', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/alipay_cross', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_alipay_cross_app_id'])) {
			$data['payment_alipay_cross_app_id'] = $this->request->post['payment_alipay_cross_app_id'];
		} else {
			$data['payment_alipay_cross_app_id'] = $this->config->get('payment_alipay_cross_app_id');
		}

		if (isset($this->request->post['payment_alipay_cross_merchant_private_key'])) {
			$data['payment_alipay_cross_merchant_private_key'] = $this->request->post['payment_alipay_cross_merchant_private_key'];
		} else {
			$data['payment_alipay_cross_merchant_private_key'] = $this->config->get('payment_alipay_cross_merchant_private_key');
		}

		if (isset($this->request->post['payment_alipay_cross_currency'])) {
			$data['payment_alipay_cross_currency'] = $this->request->post['payment_alipay_cross_currency'];
		} else {
			$data['payment_alipay_cross_currency'] = $this->config->get('payment_alipay_cross_currency');
		}

		$this->load->model('localisation/currency');

		$currencies = $this->model_localisation_currency->getCurrencies();
		$data['currencies'] = array();
		foreach ($currencies as $currency) {
			if (in_array($currency['code'], $this->currencies)) {
				$data['currencies'][] = array(
					'code'   => $currency['code'],
					'title'  => $currency['title']
				);
			}
		}

		if (isset($this->request->post['payment_alipay_cross_test'])) {
			$data['payment_alipay_cross_test'] = $this->request->post['payment_alipay_cross_test'];
		} else {
			$data['payment_alipay_cross_test'] = $this->config->get('payment_alipay_cross_test');
		}

		if (isset($this->request->post['payment_alipay_cross_total'])) {
			$data['payment_alipay_cross_total'] = $this->request->post['payment_alipay_cross_total'];
		} else {
			$data['payment_alipay_cross_total'] = $this->config->get('payment_alipay_cross_total');
		}

		if (isset($this->request->post['payment_alipay_cross_order_status_id'])) {
			$data['payment_alipay_cross_order_status_id'] = $this->request->post['payment_alipay_cross_order_status_id'];
		} else {
			$data['payment_alipay_cross_order_status_id'] = $this->config->get('payment_alipay_cross_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_alipay_cross_geo_zone_id'])) {
			$data['payment_alipay_cross_geo_zone_id'] = $this->request->post['payment_alipay_cross_geo_zone_id'];
		} else {
			$data['payment_alipay_cross_geo_zone_id'] = $this->config->get('payment_alipay_cross_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_alipay_cross_test'])) {
			$data['payment_alipay_cross_test'] = $this->request->post['payment_alipay_cross_test'];
		} else {
			$data['payment_alipay_cross_test'] = $this->config->get('payment_alipay_cross_test');
		}

		if (isset($this->request->post['payment_alipay_cross_status'])) {
			$data['payment_alipay_cross_status'] = $this->request->post['payment_alipay_cross_status'];
		} else {
			$data['payment_alipay_cross_status'] = $this->config->get('payment_alipay_cross_status');
		}

		if (isset($this->request->post['payment_alipay_cross_sort_order'])) {
			$data['payment_alipay_cross_sort_order'] = $this->request->post['payment_alipay_cross_sort_order'];
		} else {
			$data['payment_alipay_cross_sort_order'] = $this->config->get('payment_alipay_cross_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/alipay_cross', $data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/alipay_cross')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_alipay_cross_app_id']) {
			$this->error['app_id'] = $this->language->get('error_app_id');
		}

		if (!$this->request->post['payment_alipay_cross_merchant_private_key']) {
			$this->error['merchant_private_key'] = $this->language->get('error_merchant_private_key');
		}

		return !$this->error;
	}
}