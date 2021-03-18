<?php
namespace Opencart\Admin\Controller\Sale;
use mysql_xdevapi\DatabaseObject;

class Order extends \Opencart\System\Engine\Controller {
	private array $error = [];

	public function index(): void {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getList();
	}

	public function add(): void {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/order');

		$this->getForm();
	}

	public function delete(): void {
		$this->load->language('sale/order');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->session->data['success'] = $this->language->get('text_success');

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

		$this->response->redirect($this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url));
	}

	protected function getList(): void {
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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['invoice'] = $this->url->link('sale/order|invoice', 'user_token=' . $this->session->data['user_token']);
		$data['shipping'] = $this->url->link('sale/order|shipping', 'user_token=' . $this->session->data['user_token']);
		$data['add'] = $this->url->link('sale/order|add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = str_replace('&amp;', '&', $this->url->link('sale/order|delete', 'user_token=' . $this->session->data['user_token'] . $url));

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
				'view'          => $this->url->link('sale/order|info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url),
				'edit'          => $this->url->link('sale/order|edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . $url)
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.order_id' . $url);
		$data['sort_store_name'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.store_name' . $url);
		$data['sort_customer'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url);
		$data['sort_status'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=order_status' . $url);
		$data['sort_total'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.total' . $url);
		$data['sort_date_added'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_added' . $url);
		$data['sort_date_modified'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&sort=o.date_modified' . $url);

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
			'url'   => $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
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

		$this->load->model('setting/store');

		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		// API login
		$data['catalog'] = HTTP_CATALOG;

		// API login
		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
			$session = new \Opencart\System\Library\Session($this->config->get('session_engine'), $this->registry);

			$session->start();

			$this->model_user_api->deleteSessionBySessionId($session->getId());

			$this->model_user_api->addSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

			$session->data['api_id'] = $api_info['api_id'];

			$data['api_token'] = $session->getId();
		} else {
			$data['api_token'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/order_list', $data));
	}

	public function info(): object|null {
		$this->load->model('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			$this->load->language('sale/order');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['text_form'] = !isset($this->request->get['order_id']) ? $this->language->get('text_add') : sprintf($this->language->get('text_edit'), $order_id);

			$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

			// For uploading files
			$this->load->model('tool/upload');

			$data['config_file_max_size'] = $this->config->get('config_file_max_size');

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
			$data['edit'] = $this->url->link('sale/order|edit', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id);
			$data['cancel'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . $url);

			$data['user_token'] = $this->session->data['user_token'];

			$data['order_id'] = $order_id;

			// Invoice
			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			// Store
			$data['store_id'] = $order_info['store_id'];
			$data['store_name'] = $order_info['store_name'];

			if ($order_info['store_id'] == 0) {
				$data['store_url'] = HTTP_CATALOG;
			} else {
				$data['store_url'] = $order_info['store_url'];
			}

			$this->load->model('setting/store');

			$data['stores'] = [];

			$data['stores'][] = [
				'store_id' => 0,
				'name'     => $this->language->get('text_default')
			];

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = [
					'store_id' => $result['store_id'],
					'name'     => $result['name']
				];
			}

			// Date Added
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			// Customer
			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer|edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['customer_id']);
			} else {
				$data['customer'] = '';
			}

			$data['customer_id'] = $order_info['customer_id'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			$data['account_custom_field'] = $order_info['custom_field'];
			
			// Customer Groups
			$data['customer_group_id'] = $order_info['customer_group_id'];

			$this->load->model('customer/customer_group');

			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$data['customer_group'] = $customer_group_info['name'];
			} else {
				$data['customer_group'] = '';
			}

			$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

			// E-Mail
			$data['email'] = $order_info['email'];

			// Telephone
			$data['telephone'] = $order_info['telephone'];

			// Payment method
			$data['payment_method'] = $order_info['payment_method'];
			$data['payment_code'] = $order_info['payment_code'];

			// Shipping method
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_code'] = $order_info['shipping_code'];

			// Coupon, Voucher, Reward
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$order_totals = $this->model_sale_order->getTotals($order_id);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}

			// Vouchers
			$data['order_vouchers'] = $this->model_sale_order->getVouchers($order_id);

			$data['voucher_min'] = $this->config->get('config_voucher_min');

			$this->load->model('sale/voucher_theme');

			$data['voucher_themes'] = $this->model_sale_voucher_theme->getVoucherThemes();

			// Currency
			$data['currency'] = $order_info['currency_code'];
			$data['currency_code'] = $order_info['currency_code'];

			$this->load->model('localisation/currency');

			$data['currencies'] = $this->model_localisation_currency->getCurrencies();

			// Total
			$data['total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);

			// Reward Points
			$data['reward'] = $order_info['reward'];
			$data['reward_total'] = $this->model_customer_customer->getTotalRewardsByOrderId($order_id);

			// Affiliate
			if ($order_info['affiliate_id']) {
				$data['affiliate'] = $this->url->link('customer/customer|edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $order_info['affiliate_id']);
			} else {
				$data['affiliate'] = '';
			}

			$data['affiliate_id'] = $order_info['affiliate_id'];
			$data['affiliate_firstname'] = $order_info['affiliate_firstname'];
			$data['affiliate_lastname'] = $order_info['affiliate_lastname'];

			// Commission
			$data['commission'] = $this->currency->format($order_info['commission'], $order_info['currency_code'], $order_info['currency_value']);
			$data['commission_total'] = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);

			// Addresses
			$this->load->model('customer/customer');

			$data['addresses'] = $this->model_customer_customer->getAddresses($order_info['customer_id']);

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

			$data['payment_address'] = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));

			$data['payment_firstname'] = $order_info['payment_firstname'];
			$data['payment_lastname'] = $order_info['payment_lastname'];
			$data['payment_company'] = $order_info['payment_company'];
			$data['payment_address_1'] = $order_info['payment_address_1'];
			$data['payment_address_2'] = $order_info['payment_address_2'];
			$data['payment_city'] = $order_info['payment_city'];
			$data['payment_postcode'] = $order_info['payment_postcode'];
			$data['payment_country_id'] = $order_info['payment_country_id'];
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
			$data['payment_custom_field'] = $order_info['payment_custom_field'];

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

			$data['shipping_address'] = str_replace(["\r\n", "\r", "\n"], '<br />', preg_replace(["/\s\s+/", "/\r\r+/", "/\n\n+/"], '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_firstname'] = $order_info['shipping_firstname'];
			$data['shipping_lastname'] = $order_info['shipping_lastname'];
			$data['shipping_company'] = $order_info['shipping_company'];
			$data['shipping_address_1'] = $order_info['shipping_address_1'];
			$data['shipping_address_2'] = $order_info['shipping_address_2'];
			$data['shipping_city'] = $order_info['shipping_city'];
			$data['shipping_postcode'] = $order_info['shipping_postcode'];
			$data['shipping_country_id'] = $order_info['shipping_country_id'];
			$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $order_info['shipping_custom_field'];

			$this->load->model('localisation/country');

			$data['countries'] = $this->model_localisation_country->getCountries();


			// Custom Fields
			$this->load->model('customer/custom_field');

			$data['account_custom_fields'] = [];

			$filter_data = [
				'sort'  => 'cf.sort_order',
				'order' => 'ASC'
			];

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			// Payment Custom Fields
			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'account' && isset($order_info['custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getValue($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['account_custom_fields'][] = [
								'name'  => $custom_field['name'],
								'value' => $custom_field_value_info['name']
							];
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['account_custom_fields'][] = [
									'name'  => $custom_field['name'],
									'value' => $custom_field_value_info['name']
								];
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['account_custom_fields'][] = [
							'name'  => $custom_field['name'],
							'value' => $order_info['custom_field'][$custom_field['custom_field_id']]
						];
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['account_custom_fields'][] = [
								'name'  => $custom_field['name'],
								'value' => $upload_info['name']
							];
						}
					}
				}
			}

			// Payment Custom Fields
			$data['payment_custom_fields'] = [];

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getValue($order_info['payment_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['payment_custom_fields'][] = [
								'name'       => $custom_field['name'],
								'value'      => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							];
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['payment_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['payment_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['payment_custom_fields'][] = [
									'name'       => $custom_field['name'],
									'value'      => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								];
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['payment_custom_fields'][] = [
							'name'       => $custom_field['name'],
							'value'      => $order_info['payment_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						];
					}

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
				}
			}

			// Shipping Custom Fields
			$data['shipping_custom_fields'] = [];

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'address' && isset($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
					if ($custom_field['type'] == 'select' || $custom_field['type'] == 'radio') {
						$custom_field_value_info = $this->model_customer_custom_field->getValue($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($custom_field_value_info) {
							$data['shipping_custom_fields'][] = [
								'name'       => $custom_field['name'],
								'value'      => $custom_field_value_info['name'],
								'sort_order' => $custom_field['sort_order']
							];
						}
					}

					if ($custom_field['type'] == 'checkbox' && is_array($order_info['shipping_custom_field'][$custom_field['custom_field_id']])) {
						foreach ($order_info['shipping_custom_field'][$custom_field['custom_field_id']] as $custom_field_value_id) {
							$custom_field_value_info = $this->model_customer_custom_field->getValue($custom_field_value_id);

							if ($custom_field_value_info) {
								$data['shipping_custom_fields'][] = [
									'name'       => $custom_field['name'],
									'value'      => $custom_field_value_info['name'],
									'sort_order' => $custom_field['sort_order']
								];
							}
						}
					}

					if ($custom_field['type'] == 'text' || $custom_field['type'] == 'textarea' || $custom_field['type'] == 'file' || $custom_field['type'] == 'date' || $custom_field['type'] == 'datetime' || $custom_field['type'] == 'time') {
						$data['shipping_custom_fields'][] = [
							'name'       => $custom_field['name'],
							'value'      => $order_info['shipping_custom_field'][$custom_field['custom_field_id']],
							'sort_order' => $custom_field['sort_order']
						];
					}

					if ($custom_field['type'] == 'file') {
						$upload_info = $this->model_tool_upload->getUploadByCode($order_info['shipping_custom_field'][$custom_field['custom_field_id']]);

						if ($upload_info) {
							$data['shipping_custom_fields'][] = [
								'name'       => $custom_field['name'],
								'value'      => $upload_info['name'],
								'sort_order' => $custom_field['sort_order']
							];
						}
					}
				}
			}

			// Custom Fields
			$this->load->model('customer/custom_field');

			$data['custom_fields'] = [];

			$filter_data = [
				'filter_status' => 1,
				'sort'          => 'cf.sort_order',
				'order'         => 'ASC'
			];

			$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['status']) {
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
			}


			// Products
			$data['order_products'] = [];

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
					'href'             => $this->url->link('catalog/product|edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'])
				];
			}

			// Vouchers
			$data['order_vouchers'] = [];

			$vouchers = $this->model_sale_order->getVouchers($order_id);

			foreach ($vouchers as $voucher) {
				$data['order_vouchers'][] = [
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher|edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $voucher['voucher_id'])
				];
			}

			// Totals
			$data['order_totals'] = [];

			$totals = $this->model_sale_order->getTotals($order_id);

			foreach ($totals as $total) {
				$data['order_totals'][] = [
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
				];
			}



			$data['comment'] = $order_info['comment'];
			$data['comment'] = nl2br($order_info['comment']);

			// Order Status
			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info) {
				$data['order_status'] = $order_status_info['name'];
			} else {
				$data['order_status'] = '';
			}

			// Order History
			$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			$data['order_status_id'] = $order_info['order_status_id'];

			// Additional information
			$data['ip'] = $order_info['ip'];
			$data['forwarded_ip'] = $order_info['forwarded_ip'];
			$data['user_agent'] = $order_info['user_agent'];
			$data['accept_language'] = $order_info['accept_language'];

			// Additional Tabs
			$data['tabs'] = [];

			if ($this->user->hasPermission('access', 'extension/payment/' . $order_info['payment_code'])) {

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


			/*
			// The URL we send API requests to
			$data['catalog'] = HTTP_CATALOG;

			// API login
			$this->load->model('user/api');
.
			$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

			if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
				$session = new \Opencart\System\Library\Session($this->config->get('session_engine'), $this->registry);

				$session->start();

				$this->model_user_api->deleteSessionBySessionId($session->getId());

				$this->model_user_api->addSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

				$session->data['api_id'] = $api_info['api_id'];

				$data['api_token'] = $session->getId();
			} else {
				$data['api_token'] = '';
			}
			*/

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/order_info', $data));

			return null;
		} else {
			$data['order_id'] = 0;
			$data['store_id'] = 0;
			$data['store_url'] = HTTP_CATALOG;

			$data['customer'] = '';
			$data['customer_id'] = '';
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$data['firstname'] = '';
			$data['lastname'] = '';
			$data['email'] = '';
			$data['telephone'] = '';
			$data['customer_custom_field'] = [];

			$data['addresses'] = [];

			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = '';
			$data['payment_zone_id'] = '';
			$data['payment_custom_field'] = [];
			$data['payment_method'] = '';
			$data['payment_code'] = '';

			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = '';
			$data['shipping_zone_id'] = '';
			$data['shipping_custom_field'] = [];
			$data['shipping_method'] = '';
			$data['shipping_code'] = '';

			$data['order_products'] = [];
			$data['order_vouchers'] = [];
			$data['order_totals'] = [];

			$data['order_status_id'] = $this->config->get('config_order_status_id');
			$data['comment'] = '';
			$data['affiliate_id'] = 0;
			$data['affiliate'] = '';
			$data['currency_code'] = $this->config->get('config_currency');

			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			return new \Opencart\System\Engine\Action('error/not_found');
		}
	}

	public function edit(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		// Orders
		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_id && !$order_info)  {
			$json['error']['warning'] = $this->langage->get('error_order');
		}

		$order = array();

		// 1. We set some defaults so there are no undefined indexes.
		$defaults = [
			'store_id'              => 0,

			'customer_id'           => 0,
			'customer_group_id'     => (int)$this->config->get('config_customer_group_id'),
			'firstname'             => '',
			'lastname'              => '',
			'email'                 => '',
			'telephone'             => '',
			'custom_field'          => [],

			'payment_firstname'     => '',
			'payment_lastname'      => '',
			'payment_company'       => '',
			'payment_address_1'     => '',
			'payment_address_2'     => '',
			'payment_city'          => '',
			'payment_postcode'      => '',
			'payment_country_id'    => 0,
			'payment_zone_id'       => 0,
			'payment_custom_field'  => [],
			'payment_method'        => '',
			'payment_code'          => '',

			'shipping_firstname'    => '',
			'shipping_lastname'     => '',
			'shipping_company'      => '',
			'shipping_address_1'    => '',
			'shipping_address_2'    => '',
			'shipping_city'         => '',
			'shipping_postcode'     => '',
			'shipping_country_id'   => 0,
			'shipping_zone_id'      => 0,
			'shipping_custom_field' => [],
			'shipping_method'       => '',
			'shipping_code'         => '',

			'order_status_id'       => 0,
			'comment'               => '',

			'affiliate_id'          => 0,
			'currency_id'           => 0,
			'currency_code'         => (string)$this->config->get('config_currency'),
			'currency_value'        => (string)$this->config->get('config_currency'),

			'coupon'                => '',
			'voucher'               => '',
			'reward'                => '',
		];

		// 2. Merge the old order data with the new data
		foreach ($defaults as $key => $value) {
			if (isset($this->request->post[$key])) {
				$order[$key] = $this->request->post[$key];
			} elseif (isset($order_data[$key])) {
				$order[$key] = $order_info[$key];
			} else {
				$order[$key] = $value;
			}
		}

		// Store
		if ($this->request->post['action'] == 'store') {
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($order['store_id']);

			if ($order['store_id'] && !$store_info) {
				$json['error']['warning'] = $this->langage->get('error_store');
			}
		}

		// Customer
		if ($this->request->post['action'] == 'customer') {
			if ($order['customer_id']) {
				$this->load->model('account/customer');

				$customer_info = $this->model_account_customer->getCustomer($order['customer_id']);

				if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
					$json['error']['customer']['warning'] = $this->language->get('error_customer');
				}
			}

			$this->load->model('customer/customer_group');

			$customer_group_info = $this->model_customer_customer_group->getCustomerGroup($order['customer_group_id']);

			if (!$customer_group_info)  {
				$json['error']['customer']['warning'] = $this->langage->get('error_customer_group');
			}

			if ((utf8_strlen(trim($order['firstname'])) < 1) || (utf8_strlen(trim($order['firstname'])) > 32)) {
				$json['error']['customer']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($order['lastname'])) < 1) || (utf8_strlen(trim($order['lastname'])) > 32)) {
				$json['error']['customer']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen($order['email']) > 96) || (!filter_var($order['email'], FILTER_VALIDATE_EMAIL))) {
				$json['error']['customer']['email'] = $this->language->get('error_email');
			}

			if ((utf8_strlen($order['telephone']) < 3) || (utf8_strlen($order['telephone']) > 32)) {
				$json['error']['customer']['telephone'] = $this->language->get('error_telephone');
			}

			if (!$json) {
				$this->session->data['customer'] = [
					'customer_id'       => $order['customer_id'],
					'customer_group_id' => $order['customer_group_id'],
					'firstname'         => $order['firstname'],
					'lastname'          => $order['lastname'],
					'email'             => $order['email'],
					'telephone'         => $order['telephone'],
					'custom_field'      => $order['custom_field']
				];
			}
		}

		// Currency
		if ($this->request->post['action'] == 'currency') {
			$this->load->model('localisation/currency');

			$currency_info = $this->model_localisation_currency->getCurrency($this->request->post['currency_id']);

			if (!$currency_info) {
				$json['error']['warning'] = $this->langage->get('error_currency');
			}
		}

		// Payment Address
		if ($this->request->post['action'] == 'payment_address') {
			if ((utf8_strlen(trim($order['payment_firstname'])) < 1) || (utf8_strlen(trim($order['payment_firstname'])) > 32)) {
				$json['error']['payment_address']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($order['payment_lastname'])) < 1) || (utf8_strlen(trim($order['payment_lastname'])) > 32)) {
				$json['error']['payment_address']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen(trim($order['payment_address_1'])) < 3) || (utf8_strlen(trim($order['payment_address_1'])) > 128)) {
				$json['error']['payment_address']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen($order['payment_city']) < 2) || (utf8_strlen($order['payment_city']) > 32)) {
				$json['error']['payment_address']['city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($order['payment_country_id']);

			if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($order['payment_postcode'])) < 2 || utf8_strlen(trim($order['payment_postcode'])) > 10)) {
				$json['error']['payment_address']['postcode'] = $this->language->get('error_postcode');
			}

			if ($order['payment_country_id'] == '') {
				$json['error']['payment_address']['country'] = $this->language->get('error_country');
			}

			if (!isset($order['payment_zone_id']) || $order['payment_zone_id'] == '') {
				$json['error']['payment_address']['zone'] = $this->language->get('error_zone');
			}

			if (!$json) {
				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($order['country_id']);

				if ($country_info) {
					$country = $country_info['name'];
					$iso_code_2 = $country_info['iso_code_2'];
					$iso_code_3 = $country_info['iso_code_3'];
					$address_format = $country_info['address_format'];
				} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';
					$address_format = '';
				}

				$this->load->model('localisation/zone');

				$zone_info = $this->model_localisation_zone->getZone($order['zone_id']);

				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
				} else {
					$zone = '';
					$zone_code = '';
				}

				$this->session->data['payment_address'] = [
					'firstname'      => $order['payment_firstname'],
					'lastname'       => $order['payment_lastname'],
					'company'        => $order['payment_company'],
					'address_1'      => $order['payment_address_1'],
					'address_2'      => $order['payment_address_2'],
					'postcode'       => $order['payment_postcode'],
					'city'           => $order['payment_city'],
					'zone_id'        => $order['payment_zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => $order['payment_country_id'],
					'country'        => $country,
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format,
					'custom_field'   => $order['payment_custom_field']
				];
			}
		}

		// Shipping Address
		if ($this->request->post['action'] == 'shipping_address') {
			if ((utf8_strlen(trim($this->request->post['shipping_firstname'])) < 1) || (utf8_strlen(trim($this->request->post['shipping_firstname'])) > 32)) {
				$json['error']['shipping_address']['firstname'] = $this->language->get('error_firstname');
			}

			if ((utf8_strlen(trim($this->request->post['shipping_lastname'])) < 1) || (utf8_strlen(trim($this->request->post['shipping_lastname'])) > 32)) {
				$json['error']['shipping_address']['lastname'] = $this->language->get('error_lastname');
			}

			if ((utf8_strlen(trim($this->request->post['shipping_address_1'])) < 3) || (utf8_strlen(trim($this->request->post['shipping_address_1'])) > 128)) {
				$json['error']['shipping_address']['address_1'] = $this->language->get('error_address_1');
			}

			if ((utf8_strlen($this->request->post['shipping_city']) < 2) || (utf8_strlen($this->request->post['shipping_city']) > 32)) {
				$json['error']['shipping_address']['city'] = $this->language->get('error_city');
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);

			if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['shipping_postcode'])) < 2 || utf8_strlen(trim($this->request->post['shipping_postcode'])) > 10)) {
				$json['error']['shipping_address']['postcode'] = $this->language->get('error_postcode');
			}

			if ($this->request->post['shipping_country_id'] == '') {
				$json['error']['shipping_address']['country'] = $this->language->get('error_country');
			}

			if (!isset($this->request->post['shipping_zone_id']) || $this->request->post['shipping_zone_id'] == '') {
				$json['error']['shipping_address']['zone'] = $this->language->get('error_zone');
			}
		}

		// Payment Method
		if ($this->request->post['action'] == 'payment_method') {
			// Delete old payment method so not to cause any issues if there is an error
			unset($this->session->data['payment_method']);

			$json = [];

			if (!isset($this->session->data['api_id'])) {
				$json['error'] = $this->language->get('error_permission');
			} else {
				// Payment Address
				if (!isset($this->session->data['payment_address'])) {
					$json['error'] = $this->language->get('error_address');
				}

				// Payment Method
				if (empty($this->session->data['payment_methods'])) {
					$json['error'] = $this->language->get('error_no_payment');
				} elseif (!isset($this->request->post['payment_method'])) {
					$json['error'] = $this->language->get('error_method');
				} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
					$json['error'] = $this->language->get('error_method');
				}

				if (!$json) {
					$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

					$json['success'] = $this->language->get('text_method');
				}
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}

		// Shipping Method
		if ($this->request->post['action'] == 'shipping_method') {

		}

		if ($this->request->post['action'] == 'shipping_method') {

		}





		if ($this->request->post['product_id']) {


		}


		if ($this->request->post['product_id'] == 'remove') {

		}


		if (!$json) {

			// Customer
			if ($this->request->post['action'] == 'customer') {
				$customer_id = $this->request->post['customer_id'];
				$firstname = $this->request->post['firstname'];
				$lastname = $this->request->post['lastname'];
				$custom_field =  isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : [];
			} elseif ($order_info) {
				$customer_id = $order_info['customer_id'];
				$firstname = $order_info['firstname'];
				$lastname = $order_info['lastname'];
				$custom_field = $order_info['custom_field'];
			}

			// Customer Group
			if ($this->request->post['action'] == 'customer_group') {
				$customer_group_id = $this->request->post['customer_id'];
			} elseif ($order_info) {
				$customer_group_id = $order_info['customer_group_id'];
			}

			// E-Mail
			if ($this->request->post['action'] == 'email') {
				$email = $this->request->post['email'];
			} elseif ($order_info) {
				$email = $order_info['email'];
			}

			// Telephone
			if ($this->request->post['action'] == 'telephone') {
				$telephone = $this->request->post['telephone'];
			} elseif ($order_info) {
				$telephone = $order_info['telephone'];
			}



			if ($this->request->post['action'] == 'payment_address') {

				$payment_firstname = $this->request->post['payment_firstname'];
				$payment_lastname = $this->request->post['payment_lastname'];


				$custom_field =  isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : [];

			} elseif ($order_info) {

				$payment_firstname = $order_info['payment_firstname'];
				$payment_lastname = $order_info['payment_lastname'];



				$custom_field = $order_info['custom_field'];
			}















				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

				if ($country_info) {
					$country = $country_info['name'];
					$iso_code_2 = $country_info['iso_code_2'];
					$iso_code_3 = $country_info['iso_code_3'];
					$address_format = $country_info['address_format'];
				} else {
					$country = '';
					$iso_code_2 = '';
					$iso_code_3 = '';
					$address_format = '';
				}

				$this->load->model('localisation/zone');

				$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);

				if ($zone_info) {
					$zone = $zone_info['name'];
					$zone_code = $zone_info['code'];
				} else {
					$zone = '';
					$zone_code = '';
				}

				$this->session->data['shipping_address'] = [
					'firstname'      => $this->request->post['firstname'],
					'lastname'       => $this->request->post['lastname'],
					'company'        => $this->request->post['company'],
					'address_1'      => $this->request->post['address_1'],
					'address_2'      => $this->request->post['address_2'],
					'postcode'       => $this->request->post['postcode'],
					'city'           => $this->request->post['city'],
					'zone_id'        => $this->request->post['zone_id'],
					'zone'           => $zone,
					'zone_code'      => $zone_code,
					'country_id'     => $this->request->post['country_id'],
					'country'        => $country,
					'iso_code_2'     => $iso_code_2,
					'iso_code_3'     => $iso_code_3,
					'address_format' => $address_format,
					'custom_field'   => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : []
				];








				/*
				 * Create a store instance using loader class to call controllers, models, views.
				 */

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
			$request->get = '';
			$request->post = '';

			// Request
			$registry->set('request', $request);

			// Response
			$response = new \Opencart\System\Library\Response();
			$registry->set('response', $response);

			// Database
			$registry->set('db', $this->db);

			// Cache
			$registry->set('cache', $this->cache);




			$api_id = (int)$this->config->get('config_api_id');

			// Session
			$session = new \Opencart\System\Library\Session($config->get('session_engine'), $registry);
			$registry->set('session', $session);

			if (isset($request->cookie[$config->get('session_name')])) {
				$session_id = $request->cookie[$config->get('session_name')];
			} else {
				$session_id = '';
			}

			$session->start($session_id);

			$this->model_account_api->addSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);

			$session->data['api_id'] = $api_id;






			// Template
			$template = new \Opencart\System\Library\Template($config->get('template_engine'));
			$template->addPath(DIR_CATALOG . 'view/template/');
			$registry->set('template', $template);

			// Language
			$language = new \Opencart\System\Library\Language($config->get('language_code'));
			$language->addPath(DIR_LANGUAGE);
			$language->load($config->get('language_code'));
			$registry->set('language', $language);

			// Store
			if (isset($this->request->post['store_id'])) {
				$config->set('config_store_id', $this->request->post['store_id']);
			} else {
				$config->set('config_store_id', 0);
			}

			// Url
			$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

			// Document
			$registry->set('document', new \Opencart\System\Library\Document());

			// Event
			$loader->model('setting/event');

			$results = $this->model_setting_event->getEvents();

			$registry->set('event', $event);

			$pre_actions = [
				'startup/setting',
				'startup/extension',
				'startup/startup',
				'startup/event'
			];

			// Pre Actions
			foreach ($pre_actions as $pre_action) {
				$loader->controller($pre_action);
			}


			// Customer
			$customer = new \Opencart\System\Library\Cart\Customer($this->registry);
			$this->registry->set('customer', $customer);

			// Customer Group
			if (isset($this->session->data['customer']) && isset($this->session->data['customer']['customer_group_id'])) {
				// For API calls
				$this->config->set('config_customer_group_id', $this->session->data['customer']['customer_group_id']);
			} elseif ($this->customer->isLogged()) {
				// Logged in customers
				$this->config->set('config_customer_group_id', $this->customer->getGroupId());
			} elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['customer_group_id'])) {
				$this->config->set('config_customer_group_id', $this->session->data['guest']['customer_group_id']);
			}



			$data['order_id'] = $order_id;

			// Customer
			$data['customer_id'] = $order_info['customer_id'];
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];

			$data['account_custom_field'] = $order_info['custom_field'];

			// Customer Groups
			$data['customer_group_id'] = $order_info['customer_group_id'];

			// E-Mail
			$data['email'] = $order_info['email'];

			// Telephone
			$data['telephone'] = $order_info['telephone'];

			// Payment method
			$data['payment_method'] = $order_info['payment_method'];
			$data['payment_code'] = $order_info['payment_code'];

			// Shipping method
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_code'] = $order_info['shipping_code'];






			// Coupon, Voucher, Reward
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$order_totals = $this->model_sale_order->getTotals($order_id);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}

			// Vouchers
			$data['order_vouchers'] = $this->model_sale_order->getVouchers($order_id);







			// Currency
			$data['currency'] = $order_info['currency_code'];
			$data['currency_code'] = $order_info['currency_code'];

			// Reward Points
			$data['reward'] = $order_info['reward'];

			// Affiliate
			$data['affiliate_id'] = $order_info['affiliate_id'];



			// Addresses
			$this->load->model('customer/customer');

			// Payment Address
			$data['payment_firstname'] = $order_info['payment_firstname'];
			$data['payment_lastname'] = $order_info['payment_lastname'];
			$data['payment_company'] = $order_info['payment_company'];
			$data['payment_address_1'] = $order_info['payment_address_1'];
			$data['payment_address_2'] = $order_info['payment_address_2'];
			$data['payment_city'] = $order_info['payment_city'];
			$data['payment_postcode'] = $order_info['payment_postcode'];
			$data['payment_country_id'] = $order_info['payment_country_id'];
			$data['payment_zone_id'] = $order_info['payment_zone_id'];
			$data['payment_custom_field'] = $order_info['payment_custom_field'];

			// Shipping Address
			$data['shipping_firstname'] = $order_info['shipping_firstname'];
			$data['shipping_lastname'] = $order_info['shipping_lastname'];
			$data['shipping_company'] = $order_info['shipping_company'];
			$data['shipping_address_1'] = $order_info['shipping_address_1'];
			$data['shipping_address_2'] = $order_info['shipping_address_2'];
			$data['shipping_city'] = $order_info['shipping_city'];
			$data['shipping_postcode'] = $order_info['shipping_postcode'];
			$data['shipping_country_id'] = $order_info['shipping_country_id'];
			$data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$data['shipping_custom_field'] = $order_info['shipping_custom_field'];

			$this->load->model('localisation/country');

			$data['countries'] = $this->model_localisation_country->getCountries();


			}


			// Products
			$data['order_products'] = [];

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
					'href'             => $this->url->link('catalog/product|edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'])
				];
			}

			// Vouchers
			$data['order_vouchers'] = [];

			$vouchers = $this->model_sale_order->getVouchers($order_id);

			foreach ($vouchers as $voucher) {
				$data['order_vouchers'][] = [
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher|edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $voucher['voucher_id'])
				];
			}

			// Totals
			$data['order_totals'] = [];

			$totals = $this->model_sale_order->getTotals($order_id);

			foreach ($totals as $total) {
				$data['order_totals'][] = [
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
				];
			}

			// Order History
			$data['comment'] = $order_info['comment'];

			$data['order_status_id'] = $order_info['order_status_id'];

			if (isset($this->request->get['order_id'])) {
				$order_id = (int)$this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			if (isset($this->request->post['customer_id'])) {
				$order_data['customer_id'] = $this->request->post['customer_id'];
			}

			if (isset($this->request->post['customer_id'])) {
				$order_data['customer_id'] = $this->request->post['customer_id'];
			}

			if (isset($this->request->get['action'])) {
				//	$loader->controller($this->request->get['action']);
			}

			$loader->controller('api/login');

			$loader->controller('api/login');

			echo $response->getOutput();

			$response->addHeader('Content-Type: application/json');
			$response->setOutput($response->getOutput());
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getShippingMethods(): array {
		$this->load->language('sale/order');

		$json = [];

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getPaymentMethods(): array {
		$this->load->language('sale/order');

		$json = [];

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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
		} else {
			$this->load->model('sale/order');

			$invoice_no = $this->model_sale_order->createInvoiceNo($order_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
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
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info && $order_info['customer_id'] && ($order_info['reward'] > 0)) {
				$this->load->model('customer/customer');

				$reward_total = $this->model_customer_customer->getTotalRewardsByOrderId($order_id);

				if (!$reward_total) {
					$this->model_customer_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['reward'], $order_id);
				}
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
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteReward($order_id);
			}

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
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$affiliate_total = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);

				if (!$affiliate_total) {
					$this->model_customer_customer->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
				}
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
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteTransactionByOrderId($order_id);
			}

			$json['success'] = $this->language->get('text_commission_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
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
		} elseif (isset($this->request->get['order_id'])) {
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
		} elseif (isset($this->request->get['order_id'])) {
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
}
