<?php
namespace Opencart\Admin\Controller\Report;
/**
 * Class Online
 *
 * @package Opencart\Admin\Controller\Report
 */
class Online extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('report/online');

		$this->document->setTitle($this->language->get('heading_title'));

		$allowed = [
			'filter_customer',
			'filter_ip',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/online', 'user_token=' . $this->session->data['user_token'])
		];

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/online', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('report/online');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
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

		// Customer
		$data['customers'] = [];

		$filter_data = [
			'filter_customer' => $filter_customer,
			'filter_ip'       => $filter_ip,
			'start'           => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'           => $this->config->get('config_pagination_admin')
		];

		// Online
		$this->load->model('report/online');

		$this->load->model('customer/customer');

		$results = $this->model_report_online->getOnline($filter_data);

		foreach ($results as $result) {
			$customer_info = $this->model_customer_customer->getCustomer($result['customer_id']);

			if ($customer_info) {
				$customer = $customer_info['firstname'] . ' ' . $customer_info['lastname'];
			} else {
				$customer = $this->language->get('text_guest');
			}

			$data['customers'][] = [
				'customer'   => $customer,
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'])
			] + $result;
		}


		$allowed = [
			'filter_customer',
			'filter_ip'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Online
		$customer_total = $this->model_report_online->getTotalOnline($filter_data);

		// Pagination
		$data['total'] = $customer_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('report/online.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($customer_total - $this->config->get('config_pagination_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $customer_total, ceil($customer_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('report/online_list', $data);
	}
}
