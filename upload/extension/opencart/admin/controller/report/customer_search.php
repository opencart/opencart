<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
/**
 * Class Customer Search
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Report
 */
class CustomerSearch extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/report/customer_search');

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
			'href' => $this->url->link('extension/opencart/report/customer_search', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/customer_search.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_customer_search_status'] = $this->config->get('report_customer_search_status');
		$data['report_customer_search_sort_order'] = $this->config->get('report_customer_search_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_search_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/report/customer_search');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/customer_search')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_customer_search', $this->request->post);

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
		$this->load->language('extension/opencart/report/customer_search');

		$data['list'] = $this->getReport();

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/customer_search', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/opencart/report/customer_search');

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

		if (isset($this->request->get['filter_keyword'])) {
			$filter_keyword = $this->request->get['filter_keyword'];
		} else {
			$filter_keyword = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		// Search
		$data['searches'] = [];

		$filter_data = [
			'filter_date_start' => $filter_date_start,
			'filter_date_end'   => $filter_date_end,
			'filter_keyword'    => $filter_keyword,
			'filter_customer'   => $filter_customer,
			'filter_ip'         => $filter_ip,
			'start'             => ($page - 1) * $this->config->get('config_pagination'),
			'limit'             => $this->config->get('config_pagination')
		];

		// Extension
		$this->load->model('extension/opencart/report/customer');

		// Category
		$this->load->model('catalog/category');

		// Total Searches
		$search_total = $this->model_extension_opencart_report_customer->getTotalCustomerSearches($filter_data);

		$results = $this->model_extension_opencart_report_customer->getCustomerSearches($filter_data);

		foreach ($results as $result) {
			$category_info = $this->model_catalog_category->getCategory($result['category_id']);

			if ($category_info) {
				$category = ($category_info['path']) ? $category_info['path'] . ' &gt; ' . $category_info['name'] : $category_info['name'];
			} else {
				$category = '';
			}

			if ($result['customer_id'] > 0) {
				$customer = sprintf($this->language->get('text_customer'), $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id']), $result['customer']);
			} else {
				$customer = $this->language->get('text_guest');
			}

			$data['searches'][] = [
				'keyword'    => $result['keyword'],
				'products'   => $result['products'],
				'category'   => $category,
				'customer'   => $customer,
				'ip'         => $result['ip'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			];
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode($this->request->get['filter_keyword']);
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode($this->request->get['filter_customer']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $search_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/customer_search.report', 'user_token=' . $this->session->data['user_token'] . '&code=customer_search' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($search_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($search_total - $this->config->get('config_pagination'))) ? $search_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $search_total, ceil($search_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_keyword'] = $filter_keyword;
		$data['filter_customer'] = $filter_customer;
		$data['filter_ip'] = $filter_ip;

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/customer_search_list', $data);
	}
}
