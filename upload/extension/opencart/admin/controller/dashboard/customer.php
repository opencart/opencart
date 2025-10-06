<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Dashboard;
/**
 * Class Customer
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Dashboard
 */
class Customer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/dashboard/customer');

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
			'href' => $this->url->link('extension/opencart/dashboard/customer', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/dashboard/customer.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard');

		$data['dashboard_customer_width'] = $this->config->get('dashboard_customer_width');

		$data['columns'] = [];

		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}

		$data['dashboard_customer_status'] = $this->config->get('dashboard_customer_status');
		$data['dashboard_customer_sort_order'] = $this->config->get('dashboard_customer_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/dashboard/customer_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/dashboard/customer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/dashboard/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('dashboard_customer', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Dashboard
	 *
	 * @return string
	 */
	public function dashboard(): string {
		$this->load->language('extension/opencart/dashboard/customer');

		$data['user_token'] = $this->session->data['user_token'];

		// Total Customers
		$this->load->model('customer/customer');

		$today = $this->model_customer_customer->getTotalCustomers(['filter_date_added' => date('Y-m-d', strtotime('-1 day'))]);
		$yesterday = $this->model_customer_customer->getTotalCustomers(['filter_date_added' => date('Y-m-d', strtotime('-2 day'))]);
		$difference = $today - $yesterday;

		if ($difference && $today) {
			$data['percentage'] = round(($difference / $today) * 100);
		} else {
			$data['percentage'] = 0;
		}

		// Total Customers
		$customer_total = $this->model_customer_customer->getTotalCustomers();

		if ($customer_total > 1000000000000) {
			$data['total'] = round($customer_total / 1000000000000, 1) . 'T';
		} elseif ($customer_total > 1000000000) {
			$data['total'] = round($customer_total / 1000000000, 1) . 'B';
		} elseif ($customer_total > 1000000) {
			$data['total'] = round($customer_total / 1000000, 1) . 'M';
		} elseif ($customer_total > 1000) {
			$data['total'] = round($customer_total / 1000, 1) . 'K';
		} else {
			$data['total'] = $customer_total;
		}

		$data['customer'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token']);

		return $this->load->view('extension/opencart/dashboard/customer_info', $data);
	}
}
