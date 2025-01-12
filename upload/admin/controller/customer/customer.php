<?php
namespace Opencart\Admin\Controller\Customer;
/**
 * Class Customer
 *
 * @package Opencart\Admin\Controller\Customer
 */
class Customer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = (string)$this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = (int)$this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = (bool)$this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = (string)$this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = (string)$this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = (string)$this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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
			'href' => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('customer/customer.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		$data['filter_ip'] = $filter_ip;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('customer/customer');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = (int)$this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = (bool)$this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = (string)$this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = (string)$this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = (string)$this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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

		$data['action'] = $this->url->link('customer/customer.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$data['customers'] = [];

		$filter_data = [
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_email,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_status'            => $filter_status,
			'filter_ip'                => $filter_ip,
			'filter_date_from'         => $filter_date_from,
			'filter_date_to'           => $filter_date_to,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'                    => $this->config->get('config_pagination_admin')
		];

		$this->load->model('customer/customer');

		$results = $this->model_customer_customer->getCustomers($filter_data);

		foreach ($results as $result) {
			$login_info = $this->model_customer_customer->getTotalLoginAttempts($result['email']);

			if ($login_info && $login_info['total'] >= $this->config->get('config_login_attempts')) {
				$unlock = $this->url->link('customer/customer.unlock', 'user_token=' . $this->session->data['user_token'] . '&email=' . $result['email'] . $url);
			} else {
				$unlock = '';
			}

			$store_data = [];

			$store_data[] = [
				'store_id' => 0,
				'name'     => $this->config->get('config_name'),
				'href'     => $this->url->link('customer/customer.login', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . '&store_id=0')
			];

			foreach ($stores as $store) {
				$store_data[] = [
					'store_id' => $store['store_id'],
					'name'     => $store['name'],
					'href'     => $this->url->link('customer/customer.login', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . '&store_id=' . $store['store_id'])
				];
			}

			$data['customers'][] = [
				'customer_id'    => $result['customer_id'],
				'name'           => $result['name'],
				'email'          => $result['email'],
				'store_id'       => $result['store_id'],
				'customer_group' => $result['customer_group'],
				'status'         => $result['status'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'unlock'         => $unlock,
				'store'          => $store_data,
				'edit'           => $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('customer/customer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_email'] = $this->url->link('customer/customer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=c.email' . $url);
		$data['sort_customer_group'] = $this->url->link('customer/customer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=customer_group' . $url);
		$data['sort_status'] = $this->url->link('customer/customer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=c.status' . $url);
		$data['sort_date_added'] = $this->url->link('customer/customer.list', 'user_token=' . $this->session->data['user_token'] . '&sort=c.date_added' . $url);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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

		$customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $customer_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('customer/customer.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($customer_total - $this->config->get('config_pagination_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $customer_total, ceil($customer_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('customer/customer_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);
		$data['config_telephone_required'] = $this->config->get('config_telephone_required');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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
			'href' => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('customer/customer.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['upload'] = $this->url->link('tool/upload.upload', 'user_token=' . $this->session->data['user_token']);

		if (isset($this->request->get['customer_id'])) {
			$data['orders'] = $this->url->link('sale/order', 'user_token=' . $this->session->data['user_token'] . '&filter_customer_id=' . $this->request->get['customer_id']);
		} else {
			$data['orders'] = '';
		}

		if (isset($this->request->get['customer_id'])) {
			$this->load->model('customer/customer');

			$customer_info = $this->model_customer_customer->getCustomer((int)$this->request->get['customer_id']);
		}

		if (isset($this->request->get['customer_id'])) {
			$data['customer_id'] = (int)$this->request->get['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$data['stores'] = $data['stores'] + $this->model_setting_store->getStores();

		if (!empty($customer_info)) {
			$data['store_id'] = $customer_info['store_id'];
		} else {
			$data['store_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($customer_info)) {
			$data['language_id'] = $customer_info['language_id'];
		} else {
			$data['language_id'] = 0;
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (!empty($customer_info)) {
			$data['customer_group_id'] = $customer_info['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		if (!empty($customer_info)) {
			$data['firstname'] = $customer_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (!empty($customer_info)) {
			$data['lastname'] = $customer_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (!empty($customer_info)) {
			$data['email'] = $customer_info['email'];
		} else {
			$data['email'] = '';
		}

		if (!empty($customer_info)) {
			$data['telephone'] = $customer_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		// Custom Fields
		$data['custom_fields'] = [];

		$filter_data = [
			'filter_location' => 'account',
			'sort'            => 'cf.sort_order',
			'order'           => 'ASC'
		];

		$this->load->model('customer/custom_field');

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

		if (!empty($customer_info)) {
			$data['account_custom_field'] = $customer_info['custom_field'];
		} else {
			$data['account_custom_field'] = [];
		}

		$data['password'] = '';
		$data['confirm'] = '';

		if (!empty($customer_info)) {
			$data['newsletter'] = $customer_info['newsletter'];
		} else {
			$data['newsletter'] = 0;
		}

		if (!empty($customer_info)) {
			$data['status'] = $customer_info['status'];
		} else {
			$data['status'] = 1;
		}

		if (!empty($customer_info)) {
			$data['safe'] = $customer_info['safe'];
		} else {
			$data['safe'] = 0;
		}

		if (!empty($customer_info)) {
			$data['commenter'] = $customer_info['commenter'];
		} else {
			$data['commenter'] = 0;
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$data['address'] = $this->load->controller('customer/address.getAddress');
		$data['history'] = $this->getHistory();
		$data['transaction'] = $this->getTransaction();
		$data['reward'] = $this->getReward();
		$data['ip'] = $this->getIp();
		$data['authorize'] = $this->getAuthorize();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!oc_validate_length($this->request->post['firstname'], 1, 32)) {
			$json['error']['firstname'] = $this->language->get('error_firstname');
		}

		if (!oc_validate_length($this->request->post['lastname'], 1, 32)) {
			$json['error']['lastname'] = $this->language->get('error_lastname');
		}

		if (!oc_validate_email($this->request->post['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomerByEmail($this->request->post['email']);

		if (!$this->request->post['customer_id']) {
			if ($customer_info) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($customer_info && ($this->request->post['customer_id'] != $customer_info['customer_id'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}
		}

		if ($this->config->get('config_telephone_required') && !oc_validate_length($this->request->post['telephone'], 3, 32)) {
			$json['error']['telephone'] = $this->language->get('error_telephone');
		}

		// Custom field validation
		$filter_data = [
			'filter_location'          => 'account',
			'filter_customer_group_id' => $this->request->post['customer_group_id'],
			'filter_status'            => 1
		];

		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
				$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			} elseif ($custom_field['type'] == 'text' && !empty($custom_field['validation']) && !oc_validate_regex($this->request->post['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
				$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
			}
		}

		if ($this->request->post['password'] || (!isset($this->request->post['customer_id']))) {
			if (!oc_validate_length(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8'), 6, 40)) {
				$json['error']['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			if (!$this->request->post['customer_id']) {
				$json['customer_id'] = $this->model_customer_customer->addCustomer($this->request->post);
			} else {
				$this->model_customer_customer->editCustomer($this->request->post['customer_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Unlock
	 *
	 * @return void
	 */
	public function unlock(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (empty($this->request->get['email'])) {
			$json['error'] = $this->language->get('error_email');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			$this->model_customer_customer->deleteLoginAttempts($this->request->get['email']);

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
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			foreach ($selected as $customer_id) {
				$this->model_customer_customer->deleteCustomer($customer_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Login
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function login(): ?\Opencart\System\Engine\Action {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if ($customer_info) {
			// Create token to login with
			$token = oc_token(64);

			$this->model_customer_customer->editToken($customer_id, $token);

			if (isset($this->request->get['store_id'])) {
				$store_id = (int)$this->request->get['store_id'];
			} else {
				$store_id = 0;
			}

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($store_id);

			if ($store_info) {
				$this->response->redirect($store_info['url'] . 'index.php?route=account/login.token&email=' . urlencode($customer_info['email']) . '&login_token=' . $token);
			} else {
				$this->response->redirect(HTTP_CATALOG . 'index.php?route=account/login.token&email=' . urlencode($customer_info['email']) . '&login_token=' . $token);
			}

			return null;
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}
	}

	/**
	 * Payment
	 *
	 * @return void
	 */
	public function payment(): void {
		$this->load->language('customer/customer');

		$this->response->setOutput($this->getPayment());
	}

	/**
	 * Get Payment
	 *
	 * @return string
	 */
	private function getPayment(): string {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'customer/customer.payment') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['payment_methods'] = [];

		$this->load->model('sale/subscription');

		$results = $this->model_sale_subscription->getSubscriptions(['filter_customer_id' => $customer_id]);

		foreach ($results as $result) {
			if (isset($result['image'])) {
				$image = DIR_IMAGE . 'payment/' . $result['image'];
			} else {
				$image = '';
			}

			$data['payment_methods'][] = [
				'customer_payment_id' => $result['customer_payment_id'],
				'name'                => $result['name'],
				'image'               => $image,
				'type'                => $result['type'],
				'status'              => $result['status'],
				'date_expire'         => date($this->language->get('date_format_short'), strtotime($result['date_expire'])),
				'delete'              => $this->url->link('customer/customer.deletePayment', 'user_token=' . $this->session->data['user_token'] . '&customer_payment_id=' . $result['customer_payment_id'])
			];
		}

		$payment_total = $this->model_sale_subscription->getTotalSubscriptions(['filter_customer_id' => $customer_id]);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $payment_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('customer/customer.payment', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payment_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($payment_total - $limit)) ? $payment_total : ((($page - 1) * $limit) + $limit), $payment_total, ceil($payment_total / $limit));

		return $this->load->view('customer/customer_payment', $data);
	}

	/**
	 * Delete Payment
	 *
	 * @return void
	 */
	public function deletePayment(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->get['customer_payment_id'])) {
			$customer_payment_id = (int)$this->request->get['customer_payment_id'];
		} else {
			$customer_payment_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('sale/subscription');

			$this->model_sale_subscription->deleteSubscriptionByCustomerPaymentId($customer_payment_id);

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
		$this->load->language('customer/customer');

		$this->response->setOutput($this->getHistory());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	public function getHistory(): string {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'customer/customer.history') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['histories'] = [];

		$this->load->model('customer/customer');

		$results = $this->model_customer_customer->getHistories($customer_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = [
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$history_total = $this->model_customer_customer->getTotalHistories($customer_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $history_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('customer/customer.history', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($history_total - $limit)) ? $history_total : ((($page - 1) * $limit) + $limit), $history_total, ceil($history_total / $limit));

		return $this->load->view('customer/customer_history', $data);
	}

	/**
	 * Add History
	 *
	 * @return void
	 */
	public function addHistory(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if (!$customer_info) {
			$json['error'] = $this->language->get('error_customer');
		}

		if (!$json) {
			$this->model_customer_customer->addHistory($customer_id, $this->request->post['comment']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Transaction
	 *
	 * @return void
	 */
	public function transaction(): void {
		$this->load->language('customer/customer');

		$this->response->setOutput($this->getTransaction());
	}

	/**
	 * Get Transaction
	 *
	 * @return string
	 */
	public function getTransaction(): string {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'customer/customer.transaction') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['transactions'] = [];

		$this->load->model('customer/customer');

		$results = $this->model_customer_customer->getTransactions($customer_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['transactions'][] = [
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$data['balance'] = $this->currency->format($this->model_customer_customer->getTransactionTotal($customer_id), $this->config->get('config_currency'));

		$transaction_total = $this->model_customer_customer->getTotalTransactions($customer_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $transaction_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('customer/customer.transaction', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($transaction_total - $limit)) ? $transaction_total : ((($page - 1) * $limit) + $limit), $transaction_total, ceil($transaction_total / $limit));

		return $this->load->view('customer/customer_transaction', $data);
	}

	/**
	 * Add Transaction
	 *
	 * @return void
	 */
	public function addTransaction(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if (!$customer_info) {
			$json['error'] = $this->language->get('error_customer');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			$this->model_customer_customer->addTransaction($customer_id, (string)$this->request->post['description'], (float)$this->request->post['amount']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Reward
	 *
	 * @return void
	 */
	public function reward(): void {
		$this->load->language('customer/customer');

		$this->response->setOutput($this->getReward());
	}

	/**
	 * Get Reward
	 *
	 * @return string
	 */
	public function getReward(): string {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'customer/customer.reward') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['rewards'] = [];

		$this->load->model('customer/customer');

		$results = $this->model_customer_customer->getRewards($customer_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['rewards'][] = [
				'points'      => $result['points'],
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$data['balance'] = $this->model_customer_customer->getRewardTotal($customer_id);

		$reward_total = $this->model_customer_customer->getTotalRewards($customer_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $reward_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('customer/customer.reward', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($reward_total - $limit)) ? $reward_total : ((($page - 1) * $limit) + $limit), $reward_total, ceil($reward_total / $limit));

		return $this->load->view('customer/customer_reward', $data);
	}

	/**
	 * Add Reward
	 *
	 * @return void
	 */
	public function addReward(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if (!$customer_info) {
			$json['error'] = $this->language->get('error_customer');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			$this->model_customer_customer->addReward($customer_id, (string)$this->request->post['description'], (int)$this->request->post['points']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Ip
	 *
	 * @return void
	 */
	public function ip(): void {
		$this->load->language('customer/customer');

		$this->response->setOutput($this->getIp());
	}

	/**
	 * Get Ip
	 *
	 * @return string
	 */
	public function getIp(): string {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'customer/customer.ip') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['ips'] = [];

		// Customer
		$this->load->model('customer/customer');

		// Store
		$this->load->model('setting/store');

		$results = $this->model_customer_customer->getIps($customer_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$store_info = $this->model_setting_store->getStore($result['store_id']);

			if ($store_info) {
				$store = $store_info['name'];
			} elseif (!$result['store_id']) {
				$store = $this->config->get('config_name');
			} else {
				$store = '';
			}

			$data['ips'][] = [
				'ip'         => $result['ip'],
				'account'    => $this->model_customer_customer->getTotalCustomersByIp($result['ip']),
				'store'      => $store,
				'country'    => $result['country'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'filter_ip'  => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&filter_ip=' . $result['ip'])
			];
		}

		$ip_total = $this->model_customer_customer->getTotalIps($customer_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $ip_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('customer/customer.ip', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($ip_total - $limit)) ? $ip_total : ((($page - 1) * $limit) + $limit), $ip_total, ceil($ip_total / $limit));

		return $this->load->view('customer/customer_ip', $data);
	}

	/**
	 * Authorize
	 *
	 * @return void
	 */
	public function authorize(): void {
		$this->load->language('customer/customer');

		$this->response->setOutput($this->getAuthorize());
	}

	/**
	 * Get Authorize
	 *
	 * @return string
	 */
	public function getAuthorize(): string {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = (int)$this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'customer/customer.login') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['authorizes'] = [];

		$this->load->model('customer/customer');

		$results = $this->model_customer_customer->getAuthorizes($customer_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['authorizes'][] = [
				'token'      => $result['token'],
				'ip'         => $result['ip'],
				'user_agent' => $result['user_agent'],
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'total'      => $result['total'],
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'delete'     => $this->url->link('customer/customer.deleteAuthorize', 'user_token=' . $this->session->data['user_token'] . '&user_authorize_id=' . $result['user_authorize_id'])
			];
		}

		$authorize_total = $this->model_customer_customer->getTotalAuthorizes($customer_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $authorize_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('customer/customer.authorize', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($authorize_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($authorize_total - $limit)) ? $authorize_total : ((($page - 1) * $limit) + $limit), $authorize_total, ceil($authorize_total / $limit));

		return $this->load->view('customer/customer_authorize', $data);
	}

	/**
	 * Delete Authorize
	 *
	 * @return void
	 */
	public function deleteAuthorize(): void {
		$this->load->language('customer/customer');

		$json = [];

		if (isset($this->request->get['customer_authorize_id'])) {
			$customer_authorize_id = (int)$this->request->get['customer_authorize_id'];
		} else {
			$customer_authorize_id = 0;
		}

		if (isset($this->request->cookie['authorize'])) {
			$token = $this->request->cookie['authorize'];
		} else {
			$token = '';
		}

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('user/user');

		$authorize_info = $this->model_user_user->getAuthorize($customer_authorize_id);

		if (!$authorize_info) {
			$json['error'] = $this->language->get('error_authorize');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			$this->model_customer_customer->deleteAuthorizes($authorize_info['customer_id'], $customer_authorize_id);

			$json['success'] = $this->language->get('text_success');
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
		$json = [];

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = '';
			}

			$filter_data = [
				'filter_name'  => $filter_name,
				'filter_email' => $filter_email,
				'start'        => 0,
				'limit'        => $this->config->get('config_autocomplete_limit')
			];

			$this->load->model('customer/customer');

			$results = $this->model_customer_customer->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'customer_id'       => $result['customer_id'],
					'customer_group_id' => $result['customer_group_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'customer_group'    => $result['customer_group'],
					'firstname'         => $result['firstname'],
					'lastname'          => $result['lastname'],
					'email'             => $result['email'],
					'telephone'         => $result['telephone'],
					'custom_field'      => $result['custom_field'],
					'address'           => $this->model_customer_customer->getAddresses($result['customer_id'])
				];
			}
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Customfield
	 *
	 * @return void
	 */
	public function customfield(): void {
		$json = [];

		// Customer Group
		if (isset($this->request->get['customer_group_id'])) {
			$customer_group_id = (int)$this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields(['filter_customer_group_id' => $customer_group_id]);

		foreach ($custom_fields as $custom_field) {
			$json[] = [
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
