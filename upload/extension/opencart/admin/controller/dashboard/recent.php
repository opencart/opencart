<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Dashboard;
class Recent extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/dashboard/recent');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/dashboard/recent', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/dashboard/recent.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		$data['dashboard_recent_width'] = $this->config->get('dashboard_recent_width');

		$data['columns'] = [];
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		$data['dashboard_recent_status'] = $this->config->get('dashboard_recent_status');
		$data['dashboard_recent_sort_order'] = $this->config->get('dashboard_recent_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/recent_form', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/dashboard/recent');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/recent')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('dashboard_recent', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function dashboard(): string {
		$this->load->language('extension/opencart/dashboard/recent');

		// Last 5 Orders
		$data['orders'] = [];

		$filter_data = [
			'sort'  => 'o.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		];

		$this->load->model('sale/order');
		
		$results = $this->model_sale_order->getOrders($filter_data);

		foreach ($results as $result) {
			$data['orders'][] = [
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'status'     => $result['order_status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'view'       => $this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'])
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/dashboard/recent_info', $data);
	}
}
