<?php
namespace Opencart\Admin\Controller\Sale;
/**
 * Class Order
 *
 * @package Opencart\Admin\Controller\Sale
 */
class Order extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('sale/order');

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = (int)$this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = (int)$this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = (int)$this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = '';
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = '';
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

		if (isset($this->request->get['filter_date_modified_from'])) {
			$filter_date_modified_from = $this->request->get['filter_date_modified_from'];
		} else {
			$filter_date_modified_from = '';
		}

		if (isset($this->request->get['filter_date_modified_to'])) {
			$filter_date_modified_to = $this->request->get['filter_date_modified_to'];
		} else {
			$filter_date_modified_to = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . (int)$this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . (int)$this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . (int)$this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_date_modified_from'])) {
			$url .= '&filter_date_modified_from=' . $this->request->get['filter_date_modified_from'];
		}

		if (isset($this->request->get['filter_date_modified_to'])) {
			$url .= '&filter_date_modified_to=' . $this->request->get['filter_date_modified_to'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('sale/order.delete', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['invoice'] = $this->url->link('sale/order.invoice', 'user_token=' . $this->session->data['user_token']);
		$data['shipping'] = $this->url->link('sale/order.shipping', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		// Stores
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($stores, $this->model_setting_store->getStores());

		// Order Statuses
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer_id'] = $filter_customer_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_order_status_id'] = $filter_order_status_id;
		$data['filter_total'] = $filter_total;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;
		$data['filter_date_modified_from'] = $filter_date_modified_from;
		$data['filter_date_modified_to'] = $filter_date_modified_to;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('sale/order');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = (int)$this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$filter_customer_id = $this->request->get['filter_customer_id'];
		} else {
			$filter_customer_id = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = (int)$this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_order_status'])) {
			$filter_order_status = $this->request->get['filter_order_status'];
		} else {
			$filter_order_status = '';
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = (int)$this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = '';
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = '';
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

		if (isset($this->request->get['filter_date_modified_from'])) {
			$filter_date_modified_from = $this->request->get['filter_date_modified_from'];
		} else {
			$filter_date_modified_from = '';
		}

		if (isset($this->request->get['filter_date_modified_to'])) {
			$filter_date_modified_to = $this->request->get['filter_date_modified_to'];
		} else {
			$filter_date_modified_to = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
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

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . (int)$this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . (int)$this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . (int)$this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_date_modified_from'])) {
			$url .= '&filter_date_modified_from=' . $this->request->get['filter_date_modified_from'];
		}

		if (isset($this->request->get['filter_date_modified_to'])) {
			$url .= '&filter_date_modified_to=' . $this->request->get['filter_date_modified_to'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Orders
		$data['orders'] = [];

		$filter_data = [
			'filter_order_id'           => $filter_order_id,
			'filter_customer_id'        => $filter_customer_id,
			'filter_customer'           => $filter_customer,
			'filter_store_id'           => $filter_store_id,
			'filter_order_status'       => $filter_order_status,
			'filter_order_status_id'    => $filter_order_status_id,
			'filter_total'              => $filter_total,
			'filter_date_from'          => $filter_date_from,
			'filter_date_to'            => $filter_date_to,
			'filter_date_modified_from' => $filter_date_modified_from,
			'filter_date_modified_to'   => $filter_date_modified_to,
			'sort'                      => $sort,
			'order'                     => $order,
			'start'                     => ($page - 1) * (int)$this->config->get('config_pagination_admin'),
			'limit'                     => (int)$this->config->get('config_pagination_admin')
		];

		$this->load->model('sale/order');

		$results = $this->model_sale_order->getOrders($filter_data);

		foreach ($results as $result) {
			if (isset($result['shipping_method']['name'])) {
				$shipping_method = $result['shipping_method']['name'];
			} else {
				$shipping_method = '';
			}

			$data['orders'][] = [
				'order_status'    => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'total'           => $result['total'],
				'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified'   => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_method' => $shipping_method,
				'view'            => $this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url)
			] + $result;
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . (int)$this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . (int)$this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . (int)$this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_date_modified_from'])) {
			$url .= '&filter_date_modified_from=' . $this->request->get['filter_date_modified_from'];
		}

		if (isset($this->request->get['filter_date_modified_to'])) {
			$url .= '&filter_date_modified_to=' . $this->request->get['filter_date_modified_to'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_order'] = $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.order_id' . $url);
		$data['sort_store_name'] = $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.store_name' . $url);
		$data['sort_customer'] = $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url);
		$data['sort_status'] = $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . '&sort=order_status' . $url);
		$data['sort_total'] = $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.total' . $url);
		$data['sort_date_added'] = $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url);
		$data['sort_date_modified'] = $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_modified' . $url);

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . (int)$this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . (int)$this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . (int)$this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_date_modified_from'])) {
			$url .= '&filter_date_modified_from=' . $this->request->get['filter_date_modified_from'];
		}

		if (isset($this->request->get['filter_date_modified_to'])) {
			$url .= '&filter_date_modified_to=' . $this->request->get['filter_date_modified_to'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Total Orders
		$order_total = $this->model_sale_order->getTotalOrders($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $order_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('sale/order.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($order_total - $this->config->get('config_pagination_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $order_total, ceil($order_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('sale/order_list', $data);
	}

	/**
	 * Info
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function info(): void {
		$this->load->language('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !$order_id ? $this->language->get('text_add') : sprintf($this->language->get('text_edit'), $order_id);

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);
		$data['config_telephone_required'] = $this->config->get('config_telephone_required');

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer_id'])) {
			$url .= '&filter_customer_id=' . (int)$this->request->get['filter_customer_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . (int)$this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_order_status'])) {
			$url .= '&filter_order_status=' . $this->request->get['filter_order_status'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . (int)$this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['shipping'] = $this->url->link('sale/order.shipping', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id);
		$data['invoice'] = $this->url->link('sale/order.invoice', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id);
		$data['back'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['upload'] = $this->url->link('tool/upload.upload', 'user_token=' . $this->session->data['user_token']);
		$data['customer_add'] = $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token']);

		// Order
		if ($order_id) {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);
		}

		if (!empty($order_info)) {
			$data['order_id'] = $order_info['order_id'];
		} else {
			$data['order_id'] = '';
		}

		// Invoice
		if (!empty($order_info)) {
			$data['invoice_no'] = $order_info['invoice_no'];
		} else {
			$data['invoice_no'] = '';
		}

		if (!empty($order_info)) {
			$data['invoice_prefix'] = $order_info['invoice_prefix'];
		} else {
			$data['invoice_prefix'] = '';
		}

		// Customer
		if (!empty($order_info)) {
			$data['customer_id'] = $order_info['customer_id'];
			$data['customer_edit'] = $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['customer_id']);
		} else {
			$data['customer_id'] = 0;
			$data['customer_edit'] = '';
		}

		// Customer Group
		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (!empty($order_info)) {
			$data['customer_group_id'] = $order_info['customer_group_id'];
		} else {
			$data['customer_group_id'] = (int)$this->config->get('config_customer_group_id');
		}

		if (!empty($order_info)) {
			$data['firstname'] = $order_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (!empty($order_info)) {
			$data['lastname'] = $order_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (!empty($order_info)) {
			$data['email'] = $order_info['email'];
		} else {
			$data['email'] = '';
		}

		if (!empty($order_info)) {
			$data['telephone'] = $order_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (!empty($order_info)) {
			$data['account_custom_field'] = $order_info['custom_field'];
		} else {
			$data['account_custom_field'] = [];
		}

		// Custom Fields
		$data['custom_fields'] = [];

		$filter_data = [
			'filter_status' => 1,
			'sort'          => 'cf.sort_order',
			'order'         => 'ASC'
		];

		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = ['custom_field_value' => $this->model_customer_custom_field->getValues($custom_field['custom_field_id'])] + $custom_field;
		}

		// Stores
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($stores, $this->model_setting_store->getStores());

		if (!empty($order_info)) {
			$data['store_id'] = $order_info['store_id'];
		} else {
			$data['store_id'] = (int)$this->config->get('config_store_id');
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($order_info)) {
			$data['language_code'] = $order_info['language_code'];
		} else {
			$data['language_code'] = $this->config->get('config_language');
		}

		// Currencies
		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (!empty($order_info)) {
			$data['currency_code'] = $order_info['currency_code'];
			$data['currency_value'] = $order_info['currency_value'];
		} else {
			$data['currency_code'] = $this->config->get('config_currency');
			$data['currency_value'] = 1;
		}

		// Products
		$data['order_products'] = [];

		// Order
		$this->load->model('sale/order');

		// Subscription
		$this->load->model('sale/subscription');

		// Upload
		$this->load->model('tool/upload');

		$products = $this->model_sale_order->getProducts($order_id);

		foreach ($products as $product) {
			$option_data = [];

			$options = $this->model_sale_order->getOptions($order_id, $product['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = $option;
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$option_data[] = [
							'filename' => $upload_info['name'],
							'href'     => $this->url->link('tool/upload.download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $upload_info['code'])
						] + $option;
					}
				}
			}

			$subscription_plan = '';

			$subscription_info = $this->model_sale_order->getSubscription($order_id, $product['order_product_id']);

			if ($subscription_info) {
				if ($subscription_info['trial_status']) {
					$trial_price = $subscription_info['trial_price'] + ($this->config->get('config_tax') ? $subscription_info['trial_tax'] : 0);
					$trial_cycle = $subscription_info['trial_cycle'];
					$trial_frequency = $this->language->get('text_' . $subscription_info['trial_frequency']);
					$trial_duration = $subscription_info['trial_duration'];

					$subscription_plan .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
				}

				$price = $subscription_info['price'] + ($this->config->get('config_tax') ? $subscription_info['tax'] : 0);
				$cycle = $subscription_info['cycle'];
				$frequency = $this->language->get('text_' . $subscription_info['frequency']);
				$duration = $subscription_info['duration'];

				if ($subscription_info['duration']) {
					$subscription_plan .= sprintf($this->language->get('text_subscription_duration'), $data['currency_code'], $price, $data['currency_value'], $cycle, $frequency, $duration);
				} else {
					$subscription_plan .= sprintf($this->language->get('text_subscription_cancel'), $data['currency_code'], $price, $data['currency_value'], $cycle, $frequency);
				}

				$subscription_plan_id = $subscription_info['subscription_plan_id'];
			} else {
				$subscription_plan_id = 0;
			}

			$subscription_info = $this->model_sale_subscription->getSubscriptionByOrderProductId($order_id, $product['order_product_id']);

			if ($subscription_info) {
				$subscription_edit = $this->url->link('sale/subscription.info', 'user_token=' . $this->session->data['user_token'] . '&subscription_id=' . $subscription_info['subscription_id']);
			} else {
				$subscription_edit = '';
			}

			$data['order_products'][] = [
				'option'               => $option_data,
				'subscription_plan'    => $subscription_plan,
				'subscription_plan_id' => $subscription_plan_id,
				'subscription_edit'    => $subscription_edit,
				'price'                => $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0),
				'total'                => $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0),
				'product_edit'         => $this->url->link('catalog/product.form', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'])
			] + $product;
		}

		// Totals
		$data['order_totals'] = $this->model_sale_order->getTotals($order_id);

		// Customers
		if (!empty($order_info)) {
			$this->load->model('customer/customer');

			$data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);
		} else {
			$data['addresses'] = [];
		}

		// Payment Address
		if (!empty($order_info)) {
			$data['payment_address_id'] = $order_info['payment_address_id'];
			$data['payment_firstname'] = $order_info['payment_firstname'];
			$data['payment_lastname'] = $order_info['payment_lastname'];
			$data['payment_company'] = $order_info['payment_company'];
			$data['payment_address_1'] = $order_info['payment_address_1'];
			$data['payment_address_2'] = $order_info['payment_address_2'];
			$data['payment_city'] = $order_info['payment_city'];
			$data['payment_postcode'] = $order_info['payment_postcode'];
			$data['payment_country_id'] = $order_info['payment_country_id'];
			$data['payment_country'] = $order_info['payment_country'];
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
			$data['payment_zone'] = $order_info['payment_zone'];
			$data['payment_custom_field'] = $order_info['payment_custom_field'];
		} else {
			$data['payment_address_id'] = 0;
			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = 0;
			$data['payment_country'] = '';
			$data['payment_zone_id'] = 0;
			$data['payment_zone'] = '';
			$data['payment_custom_field'] = [];
		}

		// Payment Method
		if (!empty($order_info['payment_method'])) {
			$data['payment_method_name'] = $order_info['payment_method']['name'];
			$data['payment_method_code'] = $order_info['payment_method']['code'];
		} else {
			$data['payment_method_name'] = '';
			$data['payment_method_code'] = '';
		}

		// Shipping Address
		if (!empty($order_info)) {
			$data['shipping_address_id'] = $order_info['shipping_address_id'];
			$data['shipping_firstname'] = $order_info['shipping_firstname'];
			$data['shipping_lastname'] = $order_info['shipping_lastname'];
			$data['shipping_company'] = $order_info['shipping_company'];
			$data['shipping_address_1'] = $order_info['shipping_address_1'];
			$data['shipping_address_2'] = $order_info['shipping_address_2'];
			$data['shipping_city'] = $order_info['shipping_city'];
			$data['shipping_postcode'] = $order_info['shipping_postcode'];
			$data['shipping_country_id'] = $order_info['shipping_country_id'];
			$data['shipping_country'] = $order_info['shipping_country'];
			$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$data['shipping_zone'] = $order_info['shipping_zone'];
			$data['shipping_custom_field'] = $order_info['shipping_custom_field'];
		} else {
			$data['shipping_address_id'] = 0;
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = 0;
			$data['shipping_country'] = '';
			$data['shipping_zone_id'] = 0;
			$data['shipping_zone'] = '';
			$data['shipping_custom_field'] = [];
		}

		// Shipping Method
		if (!empty($order_info['shipping_method'])) {
			$data['shipping_method_name'] = $order_info['shipping_method']['name'];
			$data['shipping_method_code'] = $order_info['shipping_method']['code'];
			$data['shipping_method_cost'] = $order_info['shipping_method']['cost'];
			$data['shipping_method_tax_class_id'] = $order_info['shipping_method']['tax_class_id'];
		} else {
			$data['shipping_method_name'] = '';
			$data['shipping_method_code'] = '';
			$data['shipping_method_cost'] = '';
			$data['shipping_method_tax_class_id'] = 0;
		}

		// Reward Points
		if (!empty($order_info)) {
			$data['points'] = $this->model_sale_order->getRewardTotal($order_id);
		} else {
			$data['points'] = 0;
		}

		// Reward Points
		if (!empty($order_info)) {
			$data['reward_total'] = $this->model_customer_customer->getTotalRewardsByOrderId($order_id);
		} else {
			$data['reward_total'] = 0;
		}

		// Affiliate
		if (!empty($order_info)) {
			$data['affiliate_id'] = $order_info['affiliate_id'];
			$data['affiliate_edit'] = $this->url->link('marketing/affiliate.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['customer_id']);
		} else {
			$data['affiliate_id'] = 0;
			$data['affiliate_edit'] = '';
		}

		if (!empty($order_info)) {
			$data['affiliate'] = $order_info['affiliate'];
		} else {
			$data['affiliate'] = '';
		}

		// Commission
		if (!empty($order_info) && (float)$order_info['commission']) {
			$data['commission'] = $order_info['commission'];
		} else {
			$data['commission'] = '';
		}

		if (!empty($order_info)) {
			$data['commission_total'] = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);
		} else {
			$data['commission_total'] = '';
		}

		// Extension Order Tabs can be called here.
		$data['extensions'] = [];

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			if ($this->config->get('total_' . $extension['code'] . '_status')) {
				$output = $this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);

				if (!$output instanceof \Exception) {
					$data['extensions'][] = $output;
				}
			}
		}

		// Comment
		if (!empty($order_info)) {
			$data['comment'] = nl2br($order_info['comment']);
		} else {
			$data['comment'] = '';
		}

		// Totals
		if (!empty($order_info)) {
			$data['order_totals'] = $this->model_sale_order->getTotals($order_id);
		} else {
			$data['order_totals'] = [];
		}

		// Order Statuses
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (!empty($order_info)) {
			$data['order_status_id'] = $order_info['order_status_id'];
		} else {
			$data['order_status_id'] = (int)$this->config->get('config_order_status_id');
		}

		$data['complete_status'] = in_array($data['order_status_id'], (array)$this->config->get('config_complete_status'));

		// Additional tabs that are payment gateway specific
		$data['tabs'] = [];

		// Extension Order Tabs can be called here.
		$this->load->model('setting/extension');

		if (!empty($order_info['payment_method']['code'])) {
			if (isset($order_info['payment_method']['code'])) {
				$code = oc_substr($order_info['payment_method']['code'], 0, strpos($order_info['payment_method']['code'], '.'));
			} else {
				$code = '';
			}

			$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $code);

			if ($extension_info && $this->user->hasPermission('access', 'extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code'])) {
				$output = $this->load->controller('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code'] . '.order');

				if (!$output instanceof \Exception) {
					$this->load->language('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code'], 'extension');

					$data['tabs'][] = [
						'code'    => $extension_info['code'],
						'title'   => $this->language->get('extension_heading_title'),
						'content' => $output
					];
				}
			}
		}

		// Extension Order Tabs can be called here.
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('fraud');

		foreach ($extensions as $extension) {
			if ($this->config->get('fraud_' . $extension['code'] . '_status')) {
				$this->load->language('extension/' . $extension['extension'] . '/fraud/' . $extension['code'], 'extension');

				$output = $this->load->controller('extension/' . $extension['extension'] . '/fraud/' . $extension['code'] . '.order');

				if (!$output instanceof \Exception) {
					$data['tabs'][] = [
						'code'    => $extension['extension'],
						'title'   => $this->language->get('extension_heading_title'),
						'content' => $output
					];
				}
			}
		}

		// Additional information
		if (!empty($order_info)) {
			$data['ip'] = $order_info['ip'];
			$data['forwarded_ip'] = $order_info['forwarded_ip'];
			$data['user_agent'] = $order_info['user_agent'];
			$data['accept_language'] = $order_info['accept_language'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			$data['date_modified'] = date($this->language->get('date_format_short'), strtotime($order_info['date_modified']));
		} else {
			$data['ip'] = '';
			$data['forwarded_ip'] = '';
			$data['user_agent'] = '';
			$data['accept_language'] = '';
			$data['date_added'] = date($this->language->get('date_format_short'), time());
			$data['date_modified'] = date($this->language->get('date_format_short'), time());
		}

		$data['user_token'] = $this->session->data['user_token'];

		// Histories
		$data['history'] = $this->getHistory();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_info', $data));
	}

	/**
	 * Call
	 *
	 * Method to call the storefront API and return a response.
	 *
	 * @Example
	 *
	 * We create a hash from the data in a similar method to how amazon does things.
	 *
	 * $call     = 'order';
	 * $username = 'API username';
	 * $key      = 'API Key';
	 * $domain   = 'www.yourdomain.com';
	 * $path     = '/';
	 * $store_id = 0;
	 * $language = 'en-gb';
	 * $time     = time();
	 *
	 * // Build hash string
	 * $string  = $call . "\n";
	 * $string .= $username . "\n";
	 * $string .= $domain . "\n";
	 * $string .= $path . "\n";
	 * $string .= $store_id . "\n";
	 * $string .= $language . "\n";
	 * $string .= $currency . "\n";
	 * $string .= json_encode($_POST) . "\n";
	 * $string .= $time . "\n";
	 *
	 * $signature = base64_encode(hash_hmac('sha1', $string, $key, true));
	 *
	 * // Make remote call
	 * $url  = '&call=' . $call;
	 * $url  = '&username=' . urlencode($username);
	 * $url .= '&store_id=' . $store_id;
	 * $url .= '&language=' . $language;
	 * $url .= '&currency=' . $currency;
	 * $url .= '&time=' . $time;
	 * $url .= '&signature=' . rawurlencode($signature);
	 *
	 * $curl = curl_init();
	 *
	 * curl_setopt($curl, CURLOPT_URL, 'https://' . $domain . $path . 'index.php?route=api/api' . $url);
	 * curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	 * curl_setopt($curl, CURLOPT_HEADER, false);
	 * curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
	 * curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	 * curl_setopt($curl, CURLOPT_POST, 1);
	 * curl_setopt($curl, CURLOPT_POSTFIELDS, $_POST);
	 *
	 * $response = curl_exec($curl);
	 *
	 * $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	 *
	 * curl_close($curl);
	 *
	 * if ($status == 200) {
	 *      $response_info = json_decode($response, true);
	 * } else {
	 *      $response_info = [];
	 * }
	 *
	 * @return void
	 */
	public function call(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['call'])) {
			$call = (string)$this->request->get['call'];
		} else {
			$call = '';
		}

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['language'])) {
			$language = (string)$this->request->get['language'];
		} else {
			$language = (string)$this->config->get('config_language');
		}

		if (isset($this->request->get['currency'])) {
			$currency = (string)$this->request->get['currency'];
		} else {
			$currency = (string)$this->config->get('config_currency');
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Api
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi((int)$this->config->get('config_api_id'));

		if (!$api_info) {
			$json['error'] = $this->language->get('error_api');
		}

		if (!$json) {
			// 1. Create a store instance using loader class to call controllers, models, views, libraries.
			$this->load->model('setting/store');

			$store = $this->model_setting_store->createStoreInstance($store_id, $language, $currency);

			// 2. Remove the unneeded keys.
			$request_data = $this->request->get;

			unset($request_data['user_token']);

			// 3. Add the request GET vars.
			$store->request->get = $request_data;

			$store->request->get['route'] = 'api/order';

			// 4. Add the request POST var
			$store->request->post = $this->request->post;

			// 5. Call the required API controller.
			$store->load->controller($store->request->get['route']);

			// 6. Call the required API controller and get the output.
			$output = $store->response->getOutput();

			// 7. Clean up data by clearing cart.
			$store->cart->clear();

			// 8. Deleting the current session, so we are not creating infinite sessions.
			$store->session->destroy();
		} else {
			$output = json_encode($json);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($output);
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Order
			$this->load->model('sale/order');

			foreach ($selected as $order_id) {
				$this->model_sale_order->deleteOrder($order_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Invoice
	 *
	 * @return void
	 */
	public function invoice(): void {
		$this->load->language('sale/order');

		$data['title'] = $this->language->get('text_invoice');

		$data['base'] = HTTP_SERVER;
		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		// Hard coding css paths so that they can be replaced via the event's system.
		$data['stylesheet'] = 'view/stylesheet/stylesheet.css';

		// Order
		$this->load->model('sale/order');

		// Subscription
		$this->load->model('sale/subscription');

		// Setting
		$this->load->model('setting/setting');

		// Upload
		$this->load->model('tool/upload');

		$data['orders'] = [];

		$orders = [];

		if (isset($this->request->post['selected'])) {
			$orders = (array)$this->request->post['selected'];
		}

		if (isset($this->request->get['order_id'])) {
			$orders[] = (int)$this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				// Payment Address
				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = [
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				];

				$replace = [
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				];

				$pattern_1 = [
					"\r\n",
					"\r",
					"\n"
				];

				$pattern_2 = [
					"/\\s\\s+/",
					"/\r\r+/",
					"/\n\n+/"
				];

				$payment_address = str_replace($pattern_1, '<br/>', preg_replace($pattern_2, '<br/>', trim(str_replace($find, $replace, $format))));

				// Shipping Address
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = [
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				];

				$replace = [
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				];

				$shipping_address = str_replace($pattern_1, '<br/>', preg_replace($pattern_2, '<br/>', trim(str_replace($find, $replace, $format))));

				$product_data = [];

				$products = $this->model_sale_order->getProducts($order_id);

				foreach ($products as $product) {
					$option_data = [];

					$options = $this->model_sale_order->getOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = ['value' => $value] + $option;
					}

					// Subscription
					$description = '';

					$subscription_info = $this->model_sale_order->getSubscription($order_id, $product['order_product_id']);

					if ($subscription_info) {
						if ($subscription_info['trial_status']) {
							$trial_price = $subscription_info['trial_price'];
							$trial_cycle = $subscription_info['trial_cycle'];
							$trial_frequency = $this->language->get('text_' . $subscription_info['trial_frequency']);
							$trial_duration = $subscription_info['trial_duration'];

							$description .= sprintf($this->language->get('text_subscription_trial'), $order_info['currency_code'], $trial_price, $order_info['currency_value'], $trial_cycle, $trial_frequency, $trial_duration);
						}

						$price = $subscription_info['price'];
						$cycle = $subscription_info['cycle'];
						$frequency = $this->language->get('text_' . $subscription_info['frequency']);
						$duration = $subscription_info['duration'];

						if ($subscription_info['duration']) {
							$description .= sprintf($this->language->get('text_subscription_duration'), $order_info['currency_code'], $price, $order_info['currency_value'], $cycle, $frequency, $duration);
						} else {
							$description .= sprintf($this->language->get('text_subscription_cancel'), $order_info['currency_code'], $price, $order_info['currency_value'], $cycle, $frequency);
						}
					}

					$product_data[] = [
						'name'         => $product['name'],
						'model'        => $product['model'],
						'option'       => $option_data,
						'subscription' => $description,
						'quantity'     => $product['quantity'],
						'price'        => $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0),
						'total'        => $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0)
					];
				}

				$data['orders'][] = [
					'order_id'         => $order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'shipping_address' => $shipping_address,
					'shipping_method'  => ($order_info['shipping_method'] ? $order_info['shipping_method']['name'] : ''),
					'payment_address'  => $payment_address,
					'payment_method'   => $order_info['payment_method']['name'],
					'product'          => $product_data,
					'total'            => $this->model_sale_order->getTotals($order_id),
					'comment'          => nl2br($order_info['comment'])
				] + $order_info;


			}
		}

		$this->response->setOutput($this->load->view('sale/order_invoice', $data));
	}

	/**
	 * Shipping
	 *
	 * @return void
	 */
	public function shipping(): void {
		$this->load->language('sale/order');

		$data['title'] = $this->language->get('text_shipping');

		$data['base'] = HTTP_SERVER;
		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		// Hard coding CSS so they can be replaced via the event's system.
		$data['stylesheet'] = 'view/stylesheet/stylesheet.css';

		// Order
		$this->load->model('sale/order');

		// Product
		$this->load->model('catalog/product');

		// Setting
		$this->load->model('setting/setting');

		// Upload
		$this->load->model('tool/upload');

		// Subscription
		$this->load->model('sale/subscription');

		$data['orders'] = [];

		$orders = [];

		if (isset($this->request->post['selected'])) {
			$orders = (array)$this->request->post['selected'];
		}

		if (isset($this->request->get['order_id'])) {
			$orders[] = (int)$this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			// Make sure there is a shipping method
			if ($order_info && $order_info['shipping_method']) {
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info) {
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
				}

				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}

				// Shipping Address
				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = [
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				];

				$replace = [
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				];

				$pattern_1 = [
					"\r\n",
					"\r",
					"\n"
				];

				$pattern_2 = [
					"/\\s\\s+/",
					"/\r\r+/",
					"/\n\n+/"
				];

				$shipping_address = str_replace($pattern_1, '<br/>', preg_replace($pattern_2, '<br/>', trim(str_replace($find, $replace, $format))));

				$product_data = [];

				$products = $this->model_sale_order->getProducts($order_id);

				foreach ($products as $product) {
					$option_weight = 0;

					$product_info = $this->model_catalog_product->getProduct($product['product_id']);

					if ($product_info) {
						$option_data = [];

						$options = $this->model_sale_order->getOptions($order_id, $product['order_product_id']);

						foreach ($options as $option) {
							if ($option['type'] != 'file') {
								$value = $option['value'];
							} else {
								$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

								if ($upload_info) {
									$value = $upload_info['name'];
								} else {
									$value = '';
								}
							}

							$option_data[] = ['value' => $value] + $option;

							$product_option_value_info = $this->model_catalog_product->getOptionValue($product['product_id'], $option['product_option_value_id']);

							if (!empty($product_option_value_info['weight'])) {
								if ($product_option_value_info['weight_prefix'] == '+') {
									$option_weight += $product_option_value_info['weight'];
								} elseif ($product_option_value_info['weight_prefix'] == '-') {
									$option_weight -= $product_option_value_info['weight'];
								}
							}
						}

						$product_data[] = [
							'option'   => $option_data,
							'quantity' => $product['quantity'],
							'weight'   => $this->weight->format(($product_info['weight'] + (float)$option_weight) * $product['quantity'], $product_info['weight_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point'))
						] + $product_info;
					}
				}

				$data['orders'][] = [
					'order_id'         => $order_id,
					'invoice_no'       => $invoice_no,
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       => $order_info['store_name'],
					'store_url'        => rtrim($order_info['store_url'], '/'),
					'store_address'    => nl2br($store_address),
					'store_email'      => $store_email,
					'store_telephone'  => $store_telephone,
					'email'            => $order_info['email'],
					'telephone'        => $order_info['telephone'],
					'shipping_address' => $shipping_address,
					'shipping_method'  => $order_info['shipping_method']['name'],
					'product'          => $product_data,
					'comment'          => nl2br($order_info['comment'])
				];
			}
		}

		$this->response->setOutput($this->load->view('sale/order_shipping', $data));
	}

	/**
	 * History
	 *
	 * @return void
	 */
	public function history(): void {
		$this->load->language('sale/order');

		$this->response->setOutput($this->getHistory());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	public function getHistory(): string {
		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'sale/order.history') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		// Histories
		$data['histories'] = [];

		$this->load->model('sale/order');

		$results = $this->model_sale_order->getHistories($order_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = [
				'comment'    => nl2br($result['comment']),
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			] + $result;
		}

		// Total Histories
		$history_total = $this->model_sale_order->getTotalHistories($order_id);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $history_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('sale/order.history', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($history_total - $limit)) ? $history_total : ((($page - 1) * $limit) + $limit), $history_total, ceil($history_total / $limit));

		return $this->load->view('sale/order_history', $data);
	}

	/**
	 * Create Invoice No
	 *
	 * @return void
	 */
	public function createInvoiceNo(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Order
		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			if ($order_info['invoice_no']) {
				$json['error'] = $this->language->get('error_invoice_no');
			}
		} else {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			// Order
			$this->load->model('sale/order');

			$json['invoice_no'] = $this->model_sale_order->createInvoiceNo($order_id);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Add Reward
	 *
	 * @return void
	 */
	public function addReward(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Order
		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			if (!$order_info['customer_id']) {
				$json['error'] = $this->language->get('error_reward_guest');
			}
		} else {
			$json['error'] = $this->language->get('error_order');
		}

		// Customer
		$this->load->model('customer/customer');

		// Total Rewards
		$reward_total = $this->model_customer_customer->getTotalRewardsByOrderId($order_id);

		if ($reward_total) {
			$json['error'] = $this->language->get('error_reward_add');
		}

		if (!$json) {
			$this->model_customer_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['reward'], $order_id);

			$json['success'] = $this->language->get('text_reward_add');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Remove Reward
	 *
	 * @return void
	 */
	public function removeReward(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Order
		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			// Customer
			$this->load->model('customer/customer');

			$this->model_customer_customer->deleteRewardsByOrderId($order_id);

			$json['success'] = $this->language->get('text_reward_remove');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Add Commission
	 *
	 * @return void
	 */
	public function addCommission(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Order
		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			// Customer
			$this->load->model('customer/customer');

			$customer_info = $this->model_customer_customer->getCustomer($order_info['affiliate_id']);

			if (!$customer_info) {
				$json['error'] = $this->language->get('error_affiliate');
			}

			// Total Transactions
			$affiliate_total = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);

			if ($affiliate_total) {
				$json['error'] = $this->language->get('error_commission_add');
			}
		} else {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$this->model_customer_customer->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);

			$json['success'] = $this->language->get('text_commission_add');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Remove Commission
	 *
	 * @return void
	 */
	public function removeCommission(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Order
		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			// Customer
			$this->load->model('customer/customer');

			$this->model_customer_customer->deleteTransactionsByOrderId($order_id);

			$json['success'] = $this->language->get('text_commission_remove');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Autocomplete
	 *
	 * @return void
	 */
	public function autocomplete(): void {
		$this->load->language('sale/order');

		$json = [];

		// Order
		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$json = $order_info;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
