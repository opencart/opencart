<?php
namespace Opencart\Application\Controller\Extension\Opencart\Dashboard;
class Chart extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/opencart/dashboard/chart');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_chart', $this->request->post);

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
			'href' => $this->url->link('extension/opencart/dashboard/chart', 'user_token=' . $this->session->data['user_token'])
		];

		$data['action'] = $this->url->link('extension/opencart/dashboard/chart', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		if (isset($this->request->post['dashboard_chart_width'])) {
			$data['dashboard_chart_width'] = $this->request->post['dashboard_chart_width'];
		} else {
			$data['dashboard_chart_width'] = $this->config->get('dashboard_chart_width');
		}
	
		$data['columns'] = [];
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_chart_status'])) {
			$data['dashboard_chart_status'] = $this->request->post['dashboard_chart_status'];
		} else {
			$data['dashboard_chart_status'] = $this->config->get('dashboard_chart_status');
		}

		if (isset($this->request->post['dashboard_chart_sort_order'])) {
			$data['dashboard_chart_sort_order'] = $this->request->post['dashboard_chart_sort_order'];
		} else {
			$data['dashboard_chart_sort_order'] = $this->config->get('dashboard_chart_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/chart_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/chart')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}	
	
	public function dashboard() {
		$this->load->language('extension/opencart/dashboard/chart');

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/dashboard/chart_info', $data);
	}

	public function chart() {
		$this->load->language('extension/opencart/dashboard/chart');

		$json = [];

		$this->load->model('extension/opencart/dashboard/chart');

		$json['order'] = [];
		$json['customer'] = [];
		$json['xaxis'] = [];

		$json['order']['label'] = $this->language->get('text_order');
		$json['customer']['label'] = $this->language->get('text_customer');
		$json['order']['data'] = [];
		$json['customer']['data'] = [];

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'day';
		}

		switch ($range) {
			default:
			case 'day':
				$results = $this->model_extension_opencart_dashboard_chart->getTotalOrdersByDay();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = [$key, $value['total']];
				}

				$results = $this->model_extension_opencart_dashboard_chart->getTotalCustomersByDay();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = [$key, $value['total']];
				}

				for ($i = 0; $i < 24; $i++) {
					$json['xaxis'][] = [$i, $i];
				}
				break;
			case 'week':
				$results = $this->model_extension_opencart_dashboard_chart->getTotalOrdersByWeek();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = [$key, $value['total']];
				}

				$results = $this->model_extension_opencart_dashboard_chart->getTotalCustomersByWeek();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = [$key, $value['total']];
				}

				$date_start = strtotime('-' . date('w') . ' days');

				for ($i = 0; $i < 7; $i++) {
					$date = date('Y-m-d', $date_start + ($i * 86400));

					$json['xaxis'][] = [date('w', strtotime($date)), date('D', strtotime($date))];
				}
				break;
			case 'month':
				$results = $this->model_extension_opencart_dashboard_chart->getTotalOrdersByMonth();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = [$key, $value['total']];
				}

				$results = $this->model_extension_opencart_dashboard_chart->getTotalCustomersByMonth();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = [$key, $value['total']];
				}

				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$json['xaxis'][] = [date('j', strtotime($date)), date('d', strtotime($date))];
				}
				break;
			case 'year':
				$results = $this->model_extension_opencart_dashboard_chart->getTotalOrdersByYear();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = [$key, $value['total']];
				}

				$results = $this->model_extension_opencart_dashboard_chart->getTotalCustomersByYear();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = [$key, $value['total']];
				}

				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = [$i, date('M', mktime(0, 0, 0, $i))];
				}
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}