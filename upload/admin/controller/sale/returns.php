<?php
namespace Opencart\Admin\Controller\Sale;
/**
 * Class Returns
 *
 * @package Opencart\Admin\Controller\Sale
 */
class Returns extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('sale/returns');

		if (isset($this->request->get['filter_return_id'])) {
			$filter_return_id = (int)$this->request->get['filter_return_id'];
		} else {
			$filter_return_id = '';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = (int)$this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = '';
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$filter_return_status_id = (int)$this->request->get['filter_return_status_id'];
		} else {
			$filter_return_status_id = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$allowed = [
			'filter_return_id',
			'filter_order_id',
			'filter_customer',
			'filter_product',
			'filter_model',
			'filter_return_status_id',
			'filter_date_from',
			'filter_date_to',
			'sort',
			'order',
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
			'href' => $this->url->link('sale/returns', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('sale/returns.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('sale/returns.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		// Return Statuses
		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$data['filter_return_id'] = $filter_return_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_product'] = $filter_product;
		$data['filter_model'] = $filter_model;
		$data['filter_return_status_id'] = $filter_return_status_id;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/returns', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('sale/returns');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_return_id'])) {
			$filter_return_id = (int)$this->request->get['filter_return_id'];
		} else {
			$filter_return_id = '';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = (int)$this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = '';
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = '';
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$filter_return_status_id = (int)$this->request->get['filter_return_status_id'];
		} else {
			$filter_return_status_id = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'return_id';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$allowed = [
			'filter_return_id',
			'filter_order_id',
			'filter_customer',
			'filter_product',
			'filter_model',
			'filter_return_status_id',
			'filter_date_from',
			'filter_date_to',
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('sale/returns.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Returns
		$data['returns'] = [];

		$filter_data = [
			'filter_return_id'        => $filter_return_id,
			'filter_order_id'         => $filter_order_id,
			'filter_customer'         => $filter_customer,
			'filter_product'          => $filter_product,
			'filter_model'            => $filter_model,
			'filter_return_status_id' => $filter_return_status_id,
			'filter_date_from'        => $filter_date_from,
			'filter_date_to'          => $filter_date_to,
			'sort'                    => $sort,
			'order'                   => $order,
			'start'                   => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'                   => $this->config->get('config_pagination_admin')
		];

		$this->load->model('sale/returns');

		$results = $this->model_sale_returns->getReturns($filter_data);

		foreach ($results as $result) {
			$data['returns'][] = ['edit' => $this->url->link('sale/returns.form', 'user_token=' . $this->session->data['user_token'] . '&return_id=' . $result['return_id'] . $url)] + $result;
		}

		$allowed = [
			'filter_return_id',
			'filter_order_id',
			'filter_customer',
			'filter_product',
			'filter_model',
			'filter_return_status_id',
			'filter_date_from',
			'filter_date_to'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_return_id'] = $this->url->link('sale/returns.list', 'user_token=' . $this->session->data['user_token'] . '&sort=return_id' . $url);
		$data['sort_order_id'] = $this->url->link('sale/returns.list', 'user_token=' . $this->session->data['user_token'] . '&sort=order_id' . $url);
		$data['sort_customer'] = $this->url->link('sale/returns.list', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url);
		$data['sort_product'] = $this->url->link('sale/returns.list', 'user_token=' . $this->session->data['user_token'] . '&sort=product' . $url);
		$data['sort_model'] = $this->url->link('sale/returns.list', 'user_token=' . $this->session->data['user_token'] . '&sort=model' . $url);
		$data['sort_status'] = $this->url->link('sale/returns.list', 'user_token=' . $this->session->data['user_token'] . '&sort=return_status' . $url);
		$data['sort_date_added'] = $this->url->link('sale/returns.list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url);

		$allowed = [
			'filter_return_id',
			'filter_order_id',
			'filter_customer',
			'filter_product',
			'filter_model',
			'filter_return_status_id',
			'filter_date_from',
			'filter_date_to',
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Returns
		$return_total = $this->model_sale_returns->getTotalReturns($filter_data);

		// Pagination
		$data['total'] = $return_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('sale/returns', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($return_total - $this->config->get('config_pagination_admin'))) ? $return_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $return_total, ceil($return_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('sale/returns_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('sale/returns');

		if (isset($this->request->get['return_id'])) {
			$return_id = (int)$this->request->get['return_id'];
		} else {
			$return_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !$return_id ? $this->language->get('text_add') : sprintf($this->language->get('text_edit'), $return_id);

		$allowed = [
			'filter_return_id',
			'filter_order_id',
			'filter_customer',
			'filter_product',
			'filter_model',
			'filter_return_status_id',
			'filter_date_from',
			'filter_date_to',
			'sort',
			'order',
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
			'href' => $this->url->link('sale/returns', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['config_telephone_required'] = $this->config->get('config_telephone_required');

		$data['save'] = $this->url->link('sale/returns.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('sale/returns', 'user_token=' . $this->session->data['user_token'] . $url);

		// Return
		if (isset($this->request->get['return_id'])) {
			$this->load->model('sale/returns');

			$return_info = $this->model_sale_returns->getReturn($this->request->get['return_id']);
		}

		if (!empty($return_info)) {
			$data['return_id'] = $return_info['return_id'];
		} else {
			$data['return_id'] = 0;
		}

		// Order
		if (!empty($return_info)) {
			$data['order_id'] = $return_info['order_id'];
			$data['date_ordered'] = $return_info['date_ordered'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($return_info['date_added']));
			$data['order_edit'] = $this->url->link('sale/order.form', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $return_info['order_id']);
		} else {
			$data['order_id'] = '';
			$data['date_ordered'] = '';
			$data['date_added'] = date($this->language->get('date_format_short'));
			$data['order_edit'] = '';
		}

		// Customer
		if (!empty($return_info)) {
			$data['customer'] = $return_info['customer'];
			$data['customer_id'] = $return_info['customer_id'];
			$data['firstname'] = $return_info['firstname'];
			$data['lastname'] = $return_info['lastname'];
			$data['email'] = $return_info['email'];
			$data['telephone'] = $return_info['telephone'];
			$data['customer_edit'] = $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $return_info['customer_id']);
		} else {
			$data['customer'] = '';
			$data['customer_id'] = 0;
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['customer_edit'] = '';
		}

		// Product
		if (!empty($return_info)) {
			$data['product_id'] = $return_info['product_id'];
			$data['product'] = $return_info['product'];
			$data['model'] = $return_info['model'];
			$data['quantity'] = $return_info['quantity'];
			$data['product_edit'] = $this->url->link('catalog/product.form', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $return_info['product_id']);
		} else {
			$data['product_id'] = 0;
			$data['product'] = '';
			$data['model'] = '';
			$data['quantity'] = 1;
			$data['product_edit'] = '';
		}

		// Return
		if (!empty($return_info)) {
			$data['return_reason_id'] = $return_info['return_reason_id'];
			$data['return_action_id'] = $return_info['return_action_id'];
			$data['opened'] = $return_info['opened'];
			$data['comment'] = $return_info['comment'];
		} else {
			$data['return_reason_id'] = 0;
			$data['return_action_id'] = 0;
			$data['opened'] = 0;
			$data['comment'] = '';
		}

		// Return Reasons
		$this->load->model('localisation/return_reason');

		$data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

		// Return Actions
		$this->load->model('localisation/return_action');

		$data['return_actions'] = $this->model_localisation_return_action->getReturnActions();

		// Return Statuses
		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		if (!empty($return_info)) {
			$data['return_status_id'] = $return_info['return_status_id'];
		} else {
			$data['return_status_id'] = '';
		}

		$data['history'] = $this->getHistory();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/returns_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('sale/returns');

		$json = [];

		if (!$this->user->hasPermission('modify', 'sale/returns')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'order_id'         => 0,
			'product_id'       => 0,
			'customer_id'      => 0,
			'firstname'        => '',
			'lastname'         => '',
			'email'            => '',
			'telephone'        => '',
			'product'          => '',
			'model'            => '',
			'quantity'         => 0,
			'opened'           => 0,
			'return_reason_id' => 0,
			'return_action_id' => 0,
			'comment'          => '',
			'date_ordered'     => ''
		];

		$post_info = $this->request->post + $required;

		// Order
		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($post_info['order_id']);

		if (!$order_info) {
			$json['error']['order'] = $this->language->get('error_order_id');
		}

		// Customer
		if ($post_info['customer_id']) {
			$this->load->model('customer/customer');

			$customer_info = $this->model_customer_customer->getCustomer($post_info['customer_id']);

			if (!$customer_info) {
				$json['error']['customer'] = $this->language->get('error_customer');
			}
		}

		if (!oc_validate_length($post_info['firstname'], 1, 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if (!oc_validate_length($post_info['lastname'], 1, 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if (!oc_validate_email($post_info['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if ($this->config->get('config_telephone_required') && !oc_validate_length($post_info['telephone'], 3, 32)) {
			$json['error']['telephone'] = $this->language->get('error_telephone');
		}

		// Product
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($post_info['product_id']);

		if (!$product_info) {
			$json['error']['product'] = $this->language->get('error_product');
		}

		if (!oc_validate_length($post_info['product'], 1, 255)) {
			$json['error']['product'] = $this->language->get('error_name');
		}

		if (!oc_validate_length($post_info['model'], 1, 64)) {
			$json['error']['model'] = $this->language->get('error_model');
		}

		if ((int)$post_info['quantity'] < 1) {
			$json['error']['quantity'] = $this->language->get('error_quantity');
		}

		// Return Reason
		$this->load->model('localisation/return_reason');

		$return_reason_info = $this->model_localisation_return_reason->getReturnReason($post_info['return_reason_id']);

		if (!$return_reason_info) {
			$json['error']['reason'] = $this->language->get('error_reason');
		}

		// Return Action
		$this->load->model('localisation/return_action');

		$return_action_info = $this->model_localisation_return_action->getReturnAction($post_info['return_action_id']);

		if (!$return_action_info) {
			$json['error']['action'] = $this->language->get('error_action');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Returns
			$this->load->model('sale/returns');

			if (!$post_info['return_id']) {
				$json['return_id'] = $this->model_sale_returns->addReturn($this->request->post);

				$this->model_sale_returns->addHistory($json['return_id'], $post_info['return_status_id']);
			} else {
				$this->model_sale_returns->editReturn($post_info['return_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('sale/returns');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'sale/returns')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Returns
			$this->load->model('sale/returns');

			foreach ($selected as $return_id) {
				$this->model_sale_returns->deleteReturn($return_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * History
	 *
	 * @return void
	 */
	public function history(): void {
		$this->load->language('sale/returns');

		$this->response->setOutput($this->getHistory());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	public function getHistory(): string {
		if (isset($this->request->get['return_id'])) {
			$return_id = (int)$this->request->get['return_id'];
		} else {
			$return_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'sale/returns.history') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		// Histories
		$data['histories'] = [];

		$this->load->model('sale/returns');

		$results = $this->model_sale_returns->getHistories($return_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = [
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			] + $result;
		}

		// Total Histories
		$history_total = $this->model_sale_returns->getTotalHistories($return_id);

		// Pagination
		$data['total'] = $history_total;
		$data['page'] = $page;
		$data['limit'] = $limit;
		$data['pagination'] = $this->url->link('sale/returns.history', 'user_token=' . $this->session->data['user_token'] . '&return_id=' . $return_id . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($history_total - $limit)) ? $history_total : ((($page - 1) * $limit) + $limit), $history_total, ceil($history_total / $limit));

		return $this->load->view('sale/returns_history', $data);
	}

	/**
	 * Add History
	 *
	 * @return void
	 */
	public function addHistory(): void {
		$this->load->language('sale/returns');

		$json = [];

		if (isset($this->request->get['return_id'])) {
			$return_id = (int)$this->request->get['return_id'];
		} else {
			$return_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/returns')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$required = [
			'return_status_id' => 0,
			'comment'          => '',
			'notify'           => 0
		];

		$post_info = $this->request->post + $required;

		// Return
		$this->load->model('sale/returns');

		$return_info = $this->model_sale_returns->getReturn($return_id);

		if (!$return_info) {
			$json['error'] = $this->language->get('error_return');
		}

		if (!$json) {
			$this->model_sale_returns->addHistory($return_id, $post_info['return_status_id'], $post_info['comment'], $post_info['notify']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
