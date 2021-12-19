<?php
namespace Opencart\Admin\Controller\Sale;
class Order extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('sale/order');

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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

		$data['add'] = $this->url->link('sale/order|info', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('sale/order|delete', 'user_token=' . $this->session->data['user_token']);
		$data['invoice'] = $this->url->link('sale/order|invoice', 'user_token=' . $this->session->data['user_token']);
		$data['shipping'] = $this->url->link('sale/order|shipping', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order', $data));
	}

	public function list(): void {
		$this->load->language('sale/order');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
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

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

		$data['orders'] = [];

		$filter_data = [
			'filter_order_id'        => $filter_order_id,
			'filter_customer_id'     => $filter_customer_id,
			'filter_customer'        => $filter_customer,
			'filter_store_id'        => $filter_store_id,
			'filter_order_status'    => $filter_order_status,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_total'           => $filter_total,
			'filter_date_added'      => $filter_date_added,
			'filter_date_modified'   => $filter_date_modified,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'                  => $this->config->get('config_pagination_admin')
		];

		$this->load->model('sale/order');

		$order_total = $this->model_sale_order->getTotalOrders($filter_data);

		$results = $this->model_sale_order->getOrders($filter_data);

		foreach ($results as $result) {
			$data['orders'][] = [
				'order_id'      => $result['order_id'],
				'store_name'    => $result['store_name'],
				'customer'      => $result['customer'],
				'order_status'  => $result['order_status'] ? $result['order_status'] : $this->language->get('text_missing'),
				'total'         => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'shipping_code' => $result['shipping_code'],
				'view'          => $this->url->link('sale/order|info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url)
			];
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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_order'] = $this->url->link('sale/order|list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.order_id' . $url);
		$data['sort_store_name'] = $this->url->link('sale/order|list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.store_name' . $url);
		$data['sort_customer'] = $this->url->link('sale/order|list', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url);
		$data['sort_status'] = $this->url->link('sale/order|list', 'user_token=' . $this->session->data['user_token'] . '&sort=order_status' . $url);
		$data['sort_total'] = $this->url->link('sale/order|list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.total' . $url);
		$data['sort_date_added'] = $this->url->link('sale/order|list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url);
		$data['sort_date_modified'] = $this->url->link('sale/order|list', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_modified' . $url);

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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $order_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('sale/order|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($order_total - $this->config->get('config_pagination_admin'))) ? $order_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $order_total, ceil($order_total / $this->config->get('config_pagination_admin')));

		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer_id'] = $filter_customer_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_order_status'] = $filter_order_status;
		$data['filter_order_status_id'] = $filter_order_status_id;
		$data['filter_total'] = $filter_total;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('sale/order_list', $data);
	}

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

		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1000);

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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$url .= '&filter_date_modified=' . $this->request->get['filter_date_modified'];
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

		$data['shipping'] = $this->url->link('sale/order|shipping', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id);
		$data['invoice'] = $this->url->link('sale/order|invoice', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id);
		$data['back'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['upload'] = $this->url->link('tool/upload|upload', 'user_token=' . $this->session->data['user_token']);

		$data['customer'] = $this->url->link('customer/customer|form', 'user_token=' . $this->session->data['user_token']);
		$data['customer_add'] = $this->url->link('customer/customer|form', 'user_token=' . $this->session->data['user_token']);

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
			$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
		} else {
			$data['invoice_no'] = '';
		}

		// Store
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => sprintf($this->language->get('text_default'), $this->config->get('config_name'))
		];

		$this->load->model('setting/store');

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = [
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			];
		}

		if (!empty($order_info)) {
			$data['store_id'] = $order_info['store_id'];
		} else {
			$data['store_id'] = $this->config->get('config_store_id');
		}

		if (!empty($order_info)) {
			$data['store_name'] = $order_info['store_name'];
		} else {
			$data['store_name'] = $this->config->get('config_name');
		}

		if (!empty($order_info)) {
			$data['store_url'] = $order_info['store_url'];
		} else {
			$data['store_url'] = $this->config->get('config_url');
		}

		// Customer
		if (!empty($order_info) && $order_info['customer_id']) {
			$data['customer'] = $order_info['customer'];
		} else {
			$data['customer'] = '';
		}

		if (!empty($order_info)) {
			$data['customer_id'] = $order_info['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (!empty($order_info)) {
			$data['customer_group_id'] = $order_info['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($data['customer_group_id']);

		if ($customer_group_info) {
			$data['customer_group'] = $customer_group_info['name'];
		} else {
			$data['customer_group'] = '';
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
			$data['account_custom_field'] = '';
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
			$data['custom_fields'][] = [
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			];
		}

		/*
		// Custom Fields
		$this->load->model('tool/upload');

		if ($custom_field['type'] == 'file') {
			$upload_info = $this->model_tool_upload->getUploadByCode($order_info['account_custom_field'][$custom_field['custom_field_id']]);

			if ($upload_info) {
				$data['account_custom_field'][] = [
					'name'       => $custom_field['name'],
					'value'      => $upload_info['name'],
					'sort_order' => $custom_field['sort_order']
				];
			}
		}
		*/



		// Addresses
		if (!empty($order_info)) {
			$this->load->model('customer/customer');

			$data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);
		} else {
			$data['addresses'] = [];
		}

		// Payment Address
		if (!empty($order_info)) {
			$data['payment_firstname'] = $order_info['payment_firstname'];
		} else {
			$data['payment_firstname'] = '';
		}

		if (!empty($order_info)) {
			$data['payment_lastname'] = $order_info['payment_lastname'];
		} else {
			$data['payment_lastname'] = '';
		}

		if (!empty($order_info)) {
			$data['payment_company'] = $order_info['payment_company'];
		} else {
			$data['payment_company'] = '';
		}

		if (!empty($order_info)) {
			$data['payment_address_1'] = $order_info['payment_address_1'];
		} else {
			$data['payment_address_1'] = '';
		}

		if (!empty($order_info)) {
			$data['payment_address_2'] = $order_info['payment_address_2'];
		} else {
			$data['payment_address_2'] = '';
		}

		if (!empty($order_info)) {
			$data['payment_city'] = $order_info['payment_city'];
		} else {
			$data['payment_city'] = '';
		}

		if (!empty($order_info)) {
			$data['payment_postcode'] = $order_info['payment_postcode'];
		} else {
			$data['payment_postcode'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (!empty($order_info)) {
			$data['payment_country_id'] = $order_info['payment_country_id'];
		} else {
			$data['payment_country_id'] = 0;
		}

		if (!empty($order_info)) {
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
		} else {
			$data['payment_zone_id'] = 0;
		}

		if (!empty($order_info)) {
			$data['payment_custom_field'] = $order_info['payment_custom_field'];
		} else {
			$data['payment_custom_field'] = [];
		}

		/*
		if ($custom_field['type'] == 'file') {
			$upload_info = $this->model_tool_upload->getUploadByCode($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

			if ($upload_info) {
				$data['payment_custom_fields'][] = [
					'name'       => $custom_field['name'],
					'value'      => $upload_info['name'],
					'sort_order' => $custom_field['sort_order']
				];
			}
		}
		*/

		if (!empty($order_info)) {
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

			$data['payment_address'] = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));
		} else {
			$data['payment_address'] = '';
		}

		// Payment Method
		if (!empty($order_info)) {
			$data['payment_method'] = $order_info['payment_method'];
		} else {
			$data['payment_method'] = '';
		}

		if (!empty($order_info)) {
			$data['payment_code'] = $order_info['payment_code'];
		} else {
			$data['payment_code'] = '';
		}


		// Shipping Address
		if (!empty($order_info)) {
			$data['shipping_firstname'] = $order_info['shipping_firstname'];
		} else {
			$data['shipping_firstname'] = '';
		}

		if (!empty($order_info)) {
			$data['shipping_lastname'] = $order_info['shipping_lastname'];
		} else {
			$data['shipping_lastname'] = '';
		}

		if (!empty($order_info)) {
			$data['shipping_company'] = $order_info['shipping_company'];
		} else {
			$data['shipping_company'] = '';
		}

		if (!empty($order_info)) {
			$data['shipping_address_1'] = $order_info['shipping_address_1'];
		} else {
			$data['shipping_address_1'] = '';
		}

		if (!empty($order_info)) {
			$data['shipping_address_2'] = $order_info['shipping_address_2'];
		} else {
			$data['shipping_address_2'] = '';
		}

		if (!empty($order_info)) {
			$data['shipping_city'] = $order_info['shipping_city'];
		} else {
			$data['shipping_city'] = '';
		}

		if (!empty($order_info)) {
			$data['shipping_postcode'] = $order_info['shipping_postcode'];
		} else {
			$data['shipping_postcode'] = '';
		}

		if (!empty($order_info)) {
			$data['shipping_country_id'] = $order_info['shipping_country_id'];
		} else {
			$data['shipping_country_id'] = 0;
		}

		if (!empty($order_info)) {
			$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
		} else {
			$data['shipping_zone_id'] = 0;
		}

		if (!empty($order_info)) {
			$data['shipping_custom_field'] = $order_info['shipping_custom_field'];
		} else {
			$data['shipping_custom_field'] = [];
		}

		if (!empty($order_info)) {
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

			$data['shipping_address'] = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));
		} else {
			$data['shipping_address'] = '';
		}

		// Shipping method
		if (!empty($order_info)) {
			$data['shipping_method'] = $order_info['shipping_method'];
		} else {
			$data['shipping_method'] = '';
		}

		if (!empty($order_info)) {
			$data['shipping_code'] = $order_info['shipping_code'];
		} else {
			$data['shipping_code'] = '';
		}

		// Products
		$data['order_products'] = [];

		$this->load->model('sale/order');

		$products = $this->model_sale_order->getProducts($order_id);

		foreach ($products as $product) {
			$option_data = [];

			$options = $this->model_sale_order->getOptions($order_id, $product['order_product_id']);

			foreach ($options as $option) {
				if ($option['type'] != 'file') {
					$option_data[] = [
						'name'  => $option['name'],
						'value' => $option['value'],
						'type'  => $option['type']
					];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$option_data[] = [
							'name'  => $option['name'],
							'value' => $upload_info['name'],
							'type'  => $option['type'],
							'href'  => $this->url->link('tool/upload|download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $upload_info['code'])
						];
					}
				}
			}

			$data['order_products'][] = [
				'order_product_id' => $product['order_product_id'],
				'product_id'       => $product['product_id'],
				'name'             => $product['name'],
				'model'            => $product['model'],
				'option'           => $option_data,
				'quantity'         => $product['quantity'],
				'price'            => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
				'total'            => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				'reward'           => $product['reward'],
				'href'             => $this->url->link('catalog/product|form', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'])
			];
		}

		// Vouchers
		$data['order_vouchers'] = [];

		$vouchers = $this->model_sale_order->getVouchers($order_id);

		foreach ($vouchers as $voucher) {
			$data['order_vouchers'][] = [
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
				'href'        => $this->url->link('sale/voucher|form', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $voucher['voucher_id'])
			];
		}

		// Voucher themes
		$this->load->model('sale/voucher_theme');

		$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

		// Totals
		$data['order_totals'] = [];

		$totals = $this->model_sale_order->getTotals($order_id);

		foreach ($totals as $total) {
			$data['order_totals'][] = [
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
			];
		}

		// Comment
		if (!empty($order_info)) {
			$data['comment'] = nl2br($order_info['comment']);
		} else {
			$data['comment'] = '';
		}

		// Total
		if (!empty($order_info)) {
			$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
		} else {
			$data['total'] = $this->currency->format(0, $this->config->get('config_currency'));
		}

		// Order Status
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (!empty($order_info)) {
			$data['order_status_id'] = $order_info['order_status_id'];
		} else {
			$data['order_status_id'] = $this->config->get('config_order_status_id');
		}

		$order_status_info = $this->model_localisation_order_status->getOrderStatus($data['order_status_id']);

		if ($order_status_info) {
			$data['order_status'] = $order_status_info['name'];
		} else {
			$data['order_status'] = '';
		}

		// Affiliate
		if (!empty($order_info)) {
			$data['affiliate_id'] = $order_info['affiliate_id'];
		} else {
			$data['affiliate_id'] = 0;
		}

		if (!empty($order_info)) {
			$data['affiliate'] = $order_info['affiliate'];
		} else {
			$data['affiliate'] = '';
		}

		// Commission
		if (!empty($order_info)) {
			$data['commission'] = $this->currency->format($order_info['commission'], $this->config->get('config_currency'), $order_info['currency_value']);
		} else {
			$data['commission'] = $this->currency->format(0, $this->config->get('config_currency'));
		}

		if (!empty($order_info)) {
			$data['commission_total'] = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);
		} else {
			$data['commission_total'] = '';
		}

		// Language
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($order_info)) {
			$language_info = $this->model_localisation_language->getLanguage($order_info['language_id']);
		} else {
			$language_info = $this->model_localisation_language->getLanguageByCode($this->config->get('config_language'));
		}

		if ($language_info) {
			$data['language_id'] = $language_info['language_id'];
			$data['language_name'] = $language_info['name'];
			$data['language_code'] = $language_info['code'];
		} else {
			$data['language_id'] = 0;
			$data['language_name'] = '';
			$data['language_code'] = '';
		}

		// Currency
		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (!empty($order_info)) {
			$currency_info = $this->model_localisation_currency->getCurrency($order_info['currency_id']);
		} else {
			$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->config->get('config_currency'));
		}

		if ($currency_info) {
			$data['currency_id'] = $currency_info['currency_id'];
			$data['currency_title'] = $currency_info['title'];
			$data['currency_code'] = $currency_info['code'];
		} else {
			$data['currency_id'] = 0;
			$data['currency_title'] = '';
			$data['currency_code'] = '';
		}

		// Coupon, Voucher, Reward
		$data['coupon'] = '';
		$data['voucher'] = '';
		$data['reward'] = 0;

		if ($order_id) {
			$order_totals = $this->model_sale_order->getTotals($order_id);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}
		}

		// Reward Points
		if (!empty($order_info)) {
			$data['reward_total'] = $this->model_customer_customer->getTotalRewardsByOrderId($order_id);
		} else {
			$data['reward_total'] = 0;
		}

		// Additional tabs that are payment gateway specific
		$data['tabs'] = [];

		if (!empty($order_info) && $order_info['payment_code'] && $this->user->hasPermission('access', 'extension/payment/' . $order_info['payment_code'])) {
			if (is_file(DIR_CATALOG . 'controller/extension/payment/' . $order_info['payment_code'] . '.php')) {
				$content = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '/order');
			} else {
				$content = '';
			}

			if ($content) {
				$this->load->language('extension/payment/' . $order_info['payment_code']);

				$data['tabs'][] = [
					'code'    => $order_info['payment_code'],
					'title'   => $this->language->get('heading_title'),
					'content' => $content
				];
			}
		}

		// Extension Order Tabs can are called here.
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('fraud');

		foreach ($extensions as $extension) {
			if ($this->config->get('fraud_' . $extension['code'] . '_status')) {
				$this->load->language('extension/fraud/' . $extension['code'], 'extension');

				$content = $this->load->controller('extension/fraud/' . $extension['code'] . '/order');

				if ($content) {
					$data['tabs'][] = [
						'code'    => $extension,
						'title'   => $this->language->get('extension_heading_title'),
						'content' => $content
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

		unset($this->session->data['api_session']);

		if (!empty($order_info)) {
			$this->request->get['action'] = 'sale/order|load';

			$this->tcall();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_info', $data));
	}

	// Method to call the store front API and return a response.
	public function call(): void {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->tcall());
	}

	private function tcall(): string {
		// 1. Create a store instance using loader class to call controllers, models, views, libraries

		// Autoloader
		$autoloader = new \Opencart\System\Engine\Autoloader();
		$autoloader->register('Opencart\Catalog', DIR_CATALOG);
		$autoloader->register('Opencart\Extension', DIR_EXTENSION);
		$autoloader->register('Opencart\System', DIR_SYSTEM);

		// Registry
		$registry = new \Opencart\System\Engine\Registry();
		$registry->set('autoloader', $autoloader);

		// Config
		$config = new \Opencart\System\Engine\Config();
		$config->addPath(DIR_CONFIG);
		$registry->set('config', $config);

		// Load the default config
		$config->load('default');
		$config->load('catalog');
		$config->set('application', 'Catalog');

		// Logging
		$registry->set('log', $this->log);

		// Event
		$event = new \Opencart\System\Engine\Event($registry);
		$registry->set('event', $event);

		// Event Register
		if ($config->has('action_event')) {
			foreach ($config->get('action_event') as $key => $value) {
				foreach ($value as $priority => $action) {
					$event->register($key, new \Opencart\System\Engine\Action($action), $priority);
				}
			}
		}

		// Loader
		$loader = new \Opencart\System\Engine\Loader($registry);
		$registry->set('load', $loader);

		// Create a dummy request class so we can feed the data to the order editor
		$request = new \stdClass();
		$request->get = $this->request->get;
		$request->post = $this->request->post;
		$request->server = $this->request->server;
		$request->cookie = [];

		// Request
		$registry->set('request', $request);

		// Response
		$response = new \Opencart\System\Library\Response();
		$registry->set('response', $response);

		// Database
		$registry->set('db', $this->db);

		// Cache
		$registry->set('cache', $this->cache);

		// Session
		$session = new \Opencart\System\Library\Session($config->get('session_engine'), $registry);
		$registry->set('session', $session);

		if (isset($this->session->data['api_session'])) {
			$session_id = $this->session->data['api_session'];
		} else {
			$session_id = '';
		}

		$session->start($session_id);

		$this->session->data['api_session'] = $session->getId();

		// To use the order API it requires an API ID.
		$session->data['api_id'] = (int)$this->config->get('config_api_id');

		// Template
		$template = new \Opencart\System\Library\Template($config->get('template_engine'));
		$template->addPath(DIR_CATALOG . 'view/template/');
		$registry->set('template', $template);

		// Language
		if (isset($session->data['language'])) {
			$language_code = $session->data['language'];
		} else {
			$language_code = $this->config->get('config_language');
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($language_code);

		if ($language_info) {
			$config->set('config_language_id', $language_info['language_id']);
			$config->set('config_language', $language_info['code']);
		} else {
			$config->set('config_language_id', $this->config->get('config_language_id'));
			$config->set('config_language', $language_code);
		}

		$language = new \Opencart\System\Library\Language($language_code);
		$language->addPath(DIR_CATALOG . 'language/');
		$language->load($language_code);
		$registry->set('language', $language);

		if (!isset($session->data['currency'])) {
			$session->data['currency'] = $this->config->get('config_currency');
		}

		// Store
		if (isset($session->data['store_id'])) {
			$config->set('config_store_id', $session->data['store_id']);
		} else {
			$config->set('config_store_id', 0);
		}

		// Url
		$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

		// Document
		$registry->set('document', new \Opencart\System\Library\Document());

		// 2. Add the request vars and remove the unneeded ones
		//$request->get = $this->request->get;

		unset($request->get['route']);
		unset($request->get['user_token']);

		$request->get['route'] = 'api/' . $this->request->get['action'];

		// 3. Add the default API ID otherwise will not get a response.
		$session->data['api_id'] = $this->config->get('config_api_id');

		// 4. Run pre actions to load key settings and classes.
		$pre_actions = [
			'startup/setting',
			'startup/extension',
			'startup/customer',
			'startup/tax',
			'startup/currency',
			'startup/application',
			'startup/startup',
			'startup/event'
		];

		// Pre Actions
		foreach ($pre_actions as $pre_action) {
			$loader->controller($pre_action);
		}

		// Customer
		$customer = new \Opencart\System\Library\Cart\Customer($this->registry);
		$registry->set('customer', $customer);

		// Call the required api controller
		if (isset($request->get['route'])) {
			$loader->controller($request->get['route']);
		}

		return $response->getOutput();
	}

	public function invoice(): void {
		$this->load->language('sale/order');

		$data['title'] = $this->language->get('text_invoice');

		$data['base'] = HTTP_SERVER;
		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		// Hard coding css so they can be replaced via the events system.
		$data['bootstrap_css'] = 'view/stylesheet/bootstrap.css';
		$data['icons'] = 'view/stylesheet/icon/fontawesome/css/all.css';
		$data['stylesheet'] = 'view/stylesheet/stylesheet.css';

		// Hard coding scripts so they can be replaced via the events system.
		$data['jquery'] = 'view/javascript/jquery/jquery-3.5.1.min.js';
		$data['bootstrap_js'] = 'view/javascript/bootstrap/js/bootstrap.bundle.min.js';

		$this->load->model('sale/order');

		$this->load->model('setting/setting');

		$data['orders'] = [];

		$orders = [];

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
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

				$payment_address = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));

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

				$shipping_address = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

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

						$option_data[] = [
							'name' => $option['name'],
							'value' => $value
						];
					}

					$product_data[] = [
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					];
				}

				$voucher_data = [];

				$vouchers = $this->model_sale_order->getVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = [
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					];
				}

				$total_data = [];

				$totals = $this->model_sale_order->getTotals($order_id);

				foreach ($totals as $total) {
					$total_data[] = [
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
					];
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
					'shipping_method'  => $order_info['shipping_method'],
					'payment_address'  => $payment_address,
					'payment_method'   => $order_info['payment_method'],
					'product'          => $product_data,
					'voucher'          => $voucher_data,
					'total'            => $total_data,
					'comment'          => nl2br($order_info['comment'])
				];
			}
		}

		$this->response->setOutput($this->load->view('sale/order_invoice', $data));
	}

	public function shipping(): void {
		$this->load->language('sale/order');

		$data['title'] = $this->language->get('text_shipping');

		$data['base'] = HTTP_SERVER;
		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		// Hard coding css so they can be replaced via the events system.
		$data['bootstrap_css'] = 'view/stylesheet/bootstrap.css';
		$data['icons'] = 'view/stylesheet/icon/fontawesome/css/all.css';
		$data['stylesheet'] = 'view/stylesheet/stylesheet.css';

		// Hard coding scripts so they can be replaced via the events system.
		$data['jquery'] = 'view/javascript/jquery/jquery-3.5.1.min.js';
		$data['bootstrap_js'] = 'view/javascript/bootstrap/js/bootstrap.bundle.min.js';

		$this->load->model('sale/order');

		$this->load->model('catalog/product');

		$this->load->model('setting/setting');

		$data['orders'] = [];

		$orders = [];

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		}

		if (isset($this->request->get['order_id'])) {
			$orders[] = (int)$this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			// Make sure there is a shipping method
			if ($order_info && $order_info['shipping_code']) {
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

				$shipping_address = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));

				$this->load->model('tool/upload');

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

							$option_data[] = [
								'name'  => $option['name'],
								'value' => $value
							];

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
							'name'     => $product_info['name'],
							'model'    => $product_info['model'],
							'option'   => $option_data,
							'quantity' => $product['quantity'],
							'location' => $product_info['location'],
							'sku'      => $product_info['sku'],
							'upc'      => $product_info['upc'],
							'ean'      => $product_info['ean'],
							'jan'      => $product_info['jan'],
							'isbn'     => $product_info['isbn'],
							'mpn'      => $product_info['mpn'],
							'weight'   => $this->weight->format(($product_info['weight'] + (float)$option_weight) * $product['quantity'], $product_info['weight_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point'))
						];
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
					'shipping_method'  => $order_info['shipping_method'],
					'product'          => $product_data,
					'comment'          => nl2br($order_info['comment'])
				];
			}
		}

		$this->response->setOutput($this->load->view('sale/order_shipping', $data));
	}

	public function history(): void {
		$this->load->language('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = [];

		$this->load->model('sale/order');

		$results = $this->model_sale_order->getHistories($order_id, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = [
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$history_total = $this->model_sale_order->getTotalHistories($order_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $history_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('sale/order|history', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/order_history', $data));
	}

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

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info || $order_info['invoice_no']) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$this->load->model('sale/order');

			$invoice_no = $this->model_sale_order->createInvoiceNo($order_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_invoice_no');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info && $order_info['customer_id'] && ($order_info['reward'] > 0)) {

		}

		if (!$json) {
			$this->load->model('customer/customer');

			$reward_total = $this->model_customer_customer->getTotalRewardsByOrderId($order_id);

			if (!$reward_total) {
				$this->model_customer_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['reward'], $order_id);
			}

			$json['success'] = $this->language->get('text_reward_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			$this->model_customer_customer->deleteReward($order_id);

			$json['success'] = $this->language->get('text_reward_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			$affiliate_total = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);

			if (!$affiliate_total) {
				$this->model_customer_customer->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
			}

			$json['success'] = $this->language->get('text_commission_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if (!$order_info) {
			$json['error'] = $this->language->get('error_order');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			$this->model_customer_customer->deleteTransactionByOrderId($order_id);

			$json['success'] = $this->language->get('text_commission_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
