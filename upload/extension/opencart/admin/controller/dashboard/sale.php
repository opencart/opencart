<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Dashboard;
class Sale extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/dashboard/sale');

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
			'href' => $this->url->link('extension/opencart/dashboard/sale', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/dashboard/sale.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		$data['dashboard_sale_width'] = $this->config->get('dashboard_sale_width');

		$data['columns'] = [];

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		$data['dashboard_sale_status'] = $this->config->get('dashboard_sale_status');
		$data['dashboard_sale_sort_order'] = $this->config->get('dashboard_sale_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/sale_form', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/dashboard/sale');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/sale')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('dashboard_sale', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function dashboard(): string {
		$this->load->language('extension/opencart/dashboard/sale');

		$this->load->model('extension/opencart/report/sale');

		$today = $this->model_extension_opencart_report_sale->getTotalSales(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);

		$yesterday = $this->model_extension_opencart_report_sale->getTotalSales(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);

		$difference = $today - $yesterday;

		if ($difference && (int)$today) {
			$data['percentage'] = round(($difference / $today) * 100);
		} else {
			$data['percentage'] = 0;
		}

		$sale_total = $this->model_extension_opencart_report_sale->getTotalSales();

		if ($sale_total > 1000000000000) {
			$data['total'] = round($sale_total / 1000000000000, 1) . 'T';
		} elseif ($sale_total > 1000000000) {
			$data['total'] = round($sale_total / 1000000000, 1) . 'B';
		} elseif ($sale_total > 1000000) {
			$data['total'] = round($sale_total / 1000000, 1) . 'M';
		} elseif ($sale_total > 1000) {
			$data['total'] = round($sale_total / 1000, 1) . 'K';
		} else {
			$data['total'] = round($sale_total);
		}

		$data['sale'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token']);

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/dashboard/sale_info', $data);
	}
}
