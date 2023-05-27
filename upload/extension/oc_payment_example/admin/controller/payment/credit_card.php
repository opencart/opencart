<?php
namespace Opencart\Admin\Controller\Extension\OcPaymentExample\Payment;
class CreditCard extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/oc_payment_example/payment/credit_card', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/oc_payment_example/payment/credit_card.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		$data['payment_credit_card_response'] = $this->config->get('payment_credit_card_response');

		$data['payment_credit_card_approved_status_id'] = $this->config->get('payment_credit_card_approved_status_id');
		$data['payment_credit_card_failed_status_id'] = $this->config->get('payment_credit_card_failed_status_id');
		$data['payment_credit_card_order_status_id'] = $this->config->get('payment_credit_card_order_status_id');

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['payment_credit_card_geo_zone_id'] = $this->config->get('payment_credit_card_geo_zone_id');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['payment_credit_card_status'] = $this->config->get('payment_credit_card_status');
		$data['payment_credit_card_sort_order'] = $this->config->get('payment_credit_card_sort_order');

		$data['report'] = $this->getReport();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/oc_payment_example/payment/credit_card', $data));
	}

	public function save(): void {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/oc_payment_example/payment/credit_card')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('payment_credit_card', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/payment')) {
			$this->load->model('extension/oc_payment_example/payment/credit_card');

			$this->model_extension_oc_payment_example_payment_credit_card->install();
		}
	}

	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/payment')) {
			$this->load->model('extension/oc_payment_example/payment/credit_card');

			$this->model_extension_oc_payment_example_payment_credit_card->uninstall();
		}
	}

	public function report(): void {
		$this->load->language('extension/oc_payment_example/payment/credit_card');

		$this->response->setOutput($this->getReport());
	}

	public function getReport(): string {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reports'] = [];

		$this->load->model('extension/oc_payment_example/payment/credit_card');

		$results = $this->model_extension_oc_payment_example_payment_credit_card->getReports(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['reports'][] = [
				'order_id'   => $result['order_id'],
				'card'       => $result['card'],
				'amount'     => $this->curency->format($result['amount'], $this->config->get('config_currency')),
				'response'   => $result['response'],
				'status'     => $result['order_status'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'order'      => $this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'])
			];
		}

		$report_total = $this->model_extension_oc_payment_example_payment_credit_card->getTotalReports();

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $report_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('extension/oc_payment_example/payment/credit_card.report', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($report_total - 10)) ? $report_total : ((($page - 1) * 10) + 10), $report_total, ceil($report_total / 10));

		return $this->load->view('extension/oc_payment_example/payment/credit_card_report', $data);
	}
}
