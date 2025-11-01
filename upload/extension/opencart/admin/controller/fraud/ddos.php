<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Fraud;
/**
 * Class DDOS
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Fraud
 */
class Ddos extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/fraud/ddos');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_fraud'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/fraud/ddos', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/fraud/ddos.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud');

		// Order Status
		$this->load->model('localisation/order_status');

		$data['fraud_ddos_status'] = $this->config->get('fraud_ddos_status');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/fraud/ip', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/fraud/ddos');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/fraud/ddos')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('fraud_ddos', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/fraud')) {
			// Extension
			$this->load->model('extension/opencart/fraud/ddos');

			$this->model_extension_opencart_fraud_ddos->install();
		}
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/fraud')) {
			// Extension
			$this->load->model('extension/opencart/fraud/ddos');

			$this->model_extension_opencart_fraud_ddos->uninstall();
		}
	}

	/**
	 * Ip
	 *
	 * @return void
	 */
	public function report(): void {
		$this->load->language('extension/opencart/fraud/ddos');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['ips'] = [];

		// Extension
		$this->load->model('extension/opencart/fraud/ddos');

		// Customer
		$this->load->model('customer/customer');

		$results = $this->model_extension_opencart_fraud_ddos->getDdos(($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['ips'][] = [
				'ip'         => $result['ip'],
				'total'      => $this->model_customer_customer->getTotalCustomersByIp($result['ip']),
				'date_added' => date('d/m/y', strtotime($result['date_added'])),
				'filter_ip'  => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&filter_ip=' . $result['ip'])
			];
		}

		$ip_total = $this->model_extension_opencart_fraud_ddos->getTotalDdos();

		// Pagination
		$data['total'] = $ip_total;
		$data['page'] = $page;
		$data['limit'] = $limit;
		$data['pagination'] = $this->url->link('extension/opencart/fraud/ip.ip', 'user_token=' . $this->session->data['user_token'] . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($ip_total - $limit)) ? $ip_total : ((($page - 1) * $limit) + $limit), $ip_total, ceil($ip_total / $limit));

		$this->response->setOutput($this->load->view('extension/opencart/fraud/ddos_ip', $data));
	}
}
