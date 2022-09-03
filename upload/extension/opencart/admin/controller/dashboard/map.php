<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Dashboard;
class Map extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/dashboard/map');

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
			'href' => $this->url->link('extension/opencart/dashboard/map', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/dashboard/map.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		$data['dashboard_map_width'] = $this->config->get('dashboard_map_width');

		$data['columns'] = [];

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		$data['dashboard_map_status'] = $this->config->get('dashboard_map_status');
		$data['dashboard_map_sort_order'] = $this->config->get('dashboard_map_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/map_form', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/dashboard/map');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/map')) {
			$json['error']  = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('dashboard_map', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function dashboard(): string {
		$this->load->language('extension/opencart/dashboard/map');

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/dashboard/map_info', $data);
	}

	public function map(): void {
		$json = [];

		$this->load->model('extension/opencart/dashboard/map');

		$results = $this->model_extension_opencart_dashboard_map->getTotalOrdersByCountry();

		foreach ($results as $result) {
			$json[strtolower($result['iso_code_2'])] = [
				'total'  => $result['total'],
				'amount' => $this->currency->format($result['amount'], $this->config->get('config_currency'))
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
