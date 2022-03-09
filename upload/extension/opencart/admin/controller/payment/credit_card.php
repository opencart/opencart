<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Payment;
class CreditCard extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/payment/credit_card');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/opencart/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/payment/credit_card', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/payment/credit_card|save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		$data['payment_credit_card_response'] = $this->config->get('payment_credit_card_response');
		$data['payment_credit_card_order_status_id'] = $this->config->get('payment_credit_card_order_status_id');

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['payment_credit_card_geo_zone_id'] = $this->config->get('payment_credit_card_geo_zone_id');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['payment_credit_card_status'] = $this->config->get('payment_credit_card_status');
		$data['payment_credit_card_sort_order'] = $this->config->get('payment_credit_card_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/payment/credit_card', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/payment/credit_card');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/payment/credit_card')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_credit_card', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
