<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
/**
 * Class Customer Reward
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Report
 */
class CustomerReward extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/report/customer_reward');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/report/customer_reward', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/customer_reward.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_customer_reward_status'] = $this->config->get('report_customer_reward_status');
		$data['report_customer_reward_sort_order'] = $this->config->get('report_customer_reward_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_reward_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/report/customer_reward');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/customer_reward')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_customer_reward', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Report
	 *
	 * @return void
	 */
	public function report(): void {
		$this->load->language('extension/opencart/report/customer_reward');

		$data['list'] = $this->getReport();

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_reward', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/opencart/report/customer_reward');

		$this->response->setOutput($this->getReport());
	}

	/**
	 * Get Report
	 *
	 * @return string
	 */
	public function getReport(): string {
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		// Customers
		$data['customers'] = [];

		$filter_data = [
			'filter_date_start' => $filter_date_start,
			'filter_date_end'   => $filter_date_end,
			'filter_customer'   => $filter_customer,
			'start'             => ($page - 1) * $this->config->get('config_pagination'),
			'limit'             => $this->config->get('config_pagination')
		];

		// Extension
		$this->load->model('extension/opencart/report/customer');

		// Total Customers
		$customer_total = $this->model_extension_opencart_report_customer->getTotalRewardPoints($filter_data);

		$results = $this->model_extension_opencart_report_customer->getRewardPoints($filter_data);

		foreach ($results as $result) {
			$data['customers'][] = [
				'customer'       => $result['customer'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'points'         => $result['points'],
				'orders'         => $result['orders'],
				'total'          => (float)$result['total'],
				'edit'           => $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'])
			];
		}

		$remove = [
			'route',
			'user_token',
			'code',
			'page'
		];

		$url = http_build_query(array_diff_key($this->request->get, array_flip($remove)));

		// Pagination
		$data['total'] = $customer_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('extension/opencart/report/customer_reward.list', 'user_token=' . $this->session->data['user_token'] . '&code=customer_reward' . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($customer_total - $this->config->get('config_pagination'))) ? $customer_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $customer_total, ceil($customer_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_customer'] = $filter_customer;

		$data['currency'] = $this->config->get('config_currency');

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/customer_reward_list', $data);
	}
}
