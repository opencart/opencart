<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Dashboard;
/**
 * Class Chart
 *
 * @package
 */
class Chart extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/dashboard/chart');

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
			'href' => $this->url->link('extension/opencart/dashboard/chart', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/dashboard/chart.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		$data['dashboard_chart_width'] = $this->config->get('dashboard_chart_width');

		$data['columns'] = [];

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		$data['dashboard_chart_status'] = $this->config->get('dashboard_chart_status');
		$data['dashboard_chart_sort_order'] = $this->config->get('dashboard_chart_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/chart_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/dashboard/chart');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/chart')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('dashboard_chart', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return string
	 */
	public function dashboard(): string {
		$this->load->language('extension/opencart/dashboard/chart');

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/dashboard/chart_info', $data);
	}

	/**
	 * @return void
	 */
	public function chart(): void {
		$this->load->language('extension/opencart/dashboard/chart');

		$json = [];

		$this->load->model('extension/opencart/report/customer');
		$this->load->model('extension/opencart/report/sale');

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
				$results = $this->model_extension_opencart_report_sale->getTotalOrdersByDay();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = [$key, $value['total']];
				}

				$results = $this->model_extension_opencart_report_customer->getTotalCustomersByDay();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = [$key, $value['total']];
				}

				for ($i = 0; $i < 24; $i++) {
					$json['xaxis'][] = [$i, $i];
				}
				break;
			case 'week':
				$results = $this->model_extension_opencart_report_sale->getTotalOrdersByWeek();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = [$key, $value['total']];
				}

				$results = $this->model_extension_opencart_report_customer->getTotalCustomersByWeek();

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
				$results = $this->model_extension_opencart_report_sale->getTotalOrdersByMonth();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = [$key, $value['total']];
				}

				$results = $this->model_extension_opencart_report_customer->getTotalCustomersByMonth();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = [$key, $value['total']];
				}

				for ($i = 1; $i <= date('t'); $i++) {
					$date = date('Y') . '-' . date('m') . '-' . $i;

					$json['xaxis'][] = [date('j', strtotime($date)), date('d', strtotime($date))];
				}
				break;
			case 'year':
				$results = $this->model_extension_opencart_report_sale->getTotalOrdersByYear();

				foreach ($results as $key => $value) {
					$json['order']['data'][] = [$key, $value['total']];
				}

				$results = $this->model_extension_opencart_report_customer->getTotalCustomersByYear();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = [$key, $value['total']];
				}

				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = [$i, date('M', mktime(0, 0, 0, $i, 1))];
				}
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
