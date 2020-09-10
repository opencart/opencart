<?php
namespace Opencart\Application\Controller\Extension\Opencart\Dashboard;
class Map extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/opencart/dashboard/map');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_map', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

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

		$data['action'] = $this->url->link('extension/opencart/dashboard/map', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		if (isset($this->request->post['dashboard_map_width'])) {
			$data['dashboard_map_width'] = $this->request->post['dashboard_map_width'];
		} else {
			$data['dashboard_map_width'] = $this->config->get('dashboard_map_width');
		}

		$data['columns'] = [];
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_map_status'])) {
			$data['dashboard_map_status'] = $this->request->post['dashboard_map_status'];
		} else {
			$data['dashboard_map_status'] = $this->config->get('dashboard_map_status');
		}

		if (isset($this->request->post['dashboard_map_sort_order'])) {
			$data['dashboard_map_sort_order'] = $this->request->post['dashboard_map_sort_order'];
		} else {
			$data['dashboard_map_sort_order'] = $this->config->get('dashboard_map_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/map_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/map')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
		
	public function dashboard() {
		$this->load->language('extension/opencart/dashboard/map');

		$data['user_token'] = $this->session->data['user_token'];
		
		return $this->load->view('extension/opencart/dashboard/map_info', $data);
	}

	public function map() {
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
