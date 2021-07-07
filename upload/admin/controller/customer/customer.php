<?php
class ControllerCustomerCustomer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		$this->getList();
	}

	public function add() {
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_customer->addCustomer($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->response->redirect($this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_customer->editCustomer($this->request->get['customer_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->response->redirect($this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_customer_customer->deleteCustomer($customer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->response->redirect($this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	public function unlock() {
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		if (isset($this->request->get['email']) && $this->validateUnlock()) {
			$this->model_customer_customer->deleteLoginAttempts($this->request->get['email']);

			$this->session->data['success'] = $this->language->get('text_success');

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

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$this->response->redirect($this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
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
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('customer/customer/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('customer/customer/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		$data['customers'] = array();

		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_email,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_status'            => $filter_status,
			'filter_date_added'        => $filter_date_added,
			'filter_ip'                => $filter_ip,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);

		$results = $this->model_customer_customer->getCustomers($filter_data);

		foreach ($results as $result) {
			$login_info = $this->model_customer_customer->getTotalLoginAttempts($result['email']);

			if ($login_info && $login_info['total'] >= $this->config->get('config_login_attempts')) {
				$unlock = $this->url->link('customer/customer/unlock', 'user_token=' . $this->session->data['user_token'] . '&email=' . $result['email'] . $url, true);
			} else {
				$unlock = '';
			}

			$store_data = array();

			$store_data[] = array(
				'name' => $this->config->get('config_name'),
				'href' => $this->url->link('customer/customer/login', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . '&store_id=0', true)
			);

			foreach ($stores as $store) {
				$store_data[] = array(
					'name' => $store['name'],
					'href' => $this->url->link('customer/customer/login', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . '&store_id=' . $store['store_id'], true)
				);
			}

			$data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'name'           => $result['name'],
				'email'          => $result['email'],
				'customer_group' => $result['customer_group'],
				'status'         => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'ip'             => $result['ip'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'unlock'         => $unlock,
				'store'          => $store_data,
				'edit'           => $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id'] . $url, true)
			);
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
			$data['selected'] = array();
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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
		$data['sort_email'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&sort=c.email' . $url, true);
		$data['sort_customer_group'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&sort=customer_group' . $url, true);
		$data['sort_status'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&sort=c.status' . $url, true);
		$data['sort_ip'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&sort=c.ip' . $url, true);
		$data['sort_date_added'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&sort=c.date_added' . $url, true);

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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		$data['filter_ip'] = $filter_ip;
		$data['filter_date_added'] = $filter_date_added;

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['customer_id'])) {
			$data['customer_id'] = (int)$this->request->get['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['tracking'])) {
			$data['error_tracking'] = $this->error['tracking'];
		} else {
			$data['error_tracking'] = '';
		}

		if (isset($this->error['cheque'])) {
			$data['error_cheque'] = $this->error['cheque'];
		} else {
			$data['error_cheque'] = '';
		}

		if (isset($this->error['paypal'])) {
			$data['error_paypal'] = $this->error['paypal'];
		} else {
			$data['error_paypal'] = '';
		}

		if (isset($this->error['bank_account_name'])) {
			$data['error_bank_account_name'] = $this->error['bank_account_name'];
		} else {
			$data['error_bank_account_name'] = '';
		}

		if (isset($this->error['bank_account_number'])) {
			$data['error_bank_account_number'] = $this->error['bank_account_number'];
		} else {
			$data['error_bank_account_number'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

		if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
		} else {
			$data['error_address'] = array();
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

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['customer_id'])) {
			$data['action'] = $this->url->link('customer/customer/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('customer/customer/edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$customer_info = $this->model_customer_customer->getCustomer($this->request->get['customer_id']);
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} elseif (!empty($customer_info)) {
			$data['customer_group_id'] = $customer_info['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($customer_info)) {
			$data['firstname'] = $customer_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($customer_info)) {
			$data['lastname'] = $customer_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($customer_info)) {
			$data['email'] = $customer_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($customer_info)) {
			$data['telephone'] = $customer_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['custom_field'])) {
			$data['account_custom_field'] = $this->request->post['custom_field'];
		} elseif (!empty($customer_info)) {
			$data['account_custom_field'] = json_decode($customer_info['custom_field'], true);
		} else {
			$data['account_custom_field'] = array();
		}

		if (isset($this->request->post['address'])) {
			$data['addresses'] = $this->request->post['address'];
		} elseif (isset($this->request->get['customer_id'])) {
			$data['addresses'] = $this->model_customer_customer->getAddresses($this->request->get['customer_id']);
		} else {
			$data['addresses'] = array();
		}

		// Custom Fields
		$this->load->model('customer/custom_field');
		$this->load->model('tool/upload');

		$data['custom_fields'] = array();

		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);

			if($custom_field['type'] == 'file') {
				if(isset($data['account_custom_field'][$custom_field['custom_field_id']])) {
					$code = $data['account_custom_field'][$custom_field['custom_field_id']];

					$upload_result = $this->model_tool_upload->getUploadByCode($code);

					$data['account_custom_field'][$custom_field['custom_field_id']] = array();
					if($upload_result) {
						$data['account_custom_field'][$custom_field['custom_field_id']]['name'] = $upload_result['name'];
						$data['account_custom_field'][$custom_field['custom_field_id']]['code'] = $upload_result['code'];
					} else {
						$data['account_custom_field'][$custom_field['custom_field_id']]['name'] = "";
						$data['account_custom_field'][$custom_field['custom_field_id']]['code'] = $code;
					}
				}

				foreach($data['addresses'] as $address_id => $address) {
					if(isset($address['custom_field'][$custom_field['custom_field_id']])) {
						$code = $address['custom_field'][$custom_field['custom_field_id']];

						$upload_result = $this->model_tool_upload->getUploadByCode($code);
						
						$data['addresses'][$address_id]['custom_field'][$custom_field['custom_field_id']] = array();
						if($upload_result) {
							$data['addresses'][$address_id]['custom_field'][$custom_field['custom_field_id']]['name'] = $upload_result['name'];
							$data['addresses'][$address_id]['custom_field'][$custom_field['custom_field_id']]['code'] = $upload_result['code'];
						} else {
							$data['addresses'][$address_id]['custom_field'][$custom_field['custom_field_id']]['name'] = "";
							$data['addresses'][$address_id]['custom_field'][$custom_field['custom_field_id']]['code'] = $code;
						}
					}
				}
			}
		}

		if (isset($this->request->post['newsletter'])) {
			$data['newsletter'] = $this->request->post['newsletter'];
		} elseif (!empty($customer_info)) {
			$data['newsletter'] = $customer_info['newsletter'];
		} else {
			$data['newsletter'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($customer_info)) {
			$data['status'] = $customer_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['safe'])) {
			$data['safe'] = $this->request->post['safe'];
		} elseif (!empty($customer_info)) {
			$data['safe'] = $customer_info['safe'];
		} else {
			$data['safe'] = 0;
		}

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->request->post['address_id'])) {
			$data['address_id'] = $this->request->post['address_id'];
		} elseif (!empty($customer_info)) {
			$data['address_id'] = $customer_info['address_id'];
		} else {
			$data['address_id'] = '';
		}

		// Affliate
		if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$affiliate_info = $this->model_customer_customer->getAffiliate($this->request->get['customer_id']);
		}

		if (isset($this->request->post['affiliate'])) {
			$data['affiliate'] = $this->request->post['affiliate'];
		} elseif (!empty($affiliate_info)) {
			$data['affiliate'] = $affiliate_info['status'];
		} else {
			$data['affiliate'] = '';
		}

		if (isset($this->request->post['company'])) {
			$data['company'] = $this->request->post['company'];
		} elseif (!empty($affiliate_info)) {
			$data['company'] = $affiliate_info['company'];
		} else {
			$data['company'] = '';
		}

		if (isset($this->request->post['website'])) {
			$data['website'] = $this->request->post['website'];
		} elseif (!empty($affiliate_info)) {
			$data['website'] = $affiliate_info['website'];
		} else {
			$data['website'] = '';
		}

		if (isset($this->request->post['tracking'])) {
			$data['tracking'] = $this->request->post['tracking'];
		} elseif (!empty($affiliate_info)) {
			$data['tracking'] = $affiliate_info['tracking'];
		} else {
			$data['tracking'] = '';
		}

		if (isset($this->request->post['commission'])) {
			$data['commission'] = $this->request->post['commission'];
		} elseif (!empty($affiliate_info)) {
			$data['commission'] = $affiliate_info['commission'];
		} else {
			$data['commission'] = $this->config->get('config_affiliate_commission');
		}

		if (isset($this->request->post['tax'])) {
			$data['tax'] = $this->request->post['tax'];
		} elseif (!empty($affiliate_info)) {
			$data['tax'] = $affiliate_info['tax'];
		} else {
			$data['tax'] = '';
		}

		if (isset($this->request->post['payment'])) {
			$data['payment'] = $this->request->post['payment'];
		} elseif (!empty($affiliate_info)) {
			$data['payment'] = $affiliate_info['payment'];
		} else {
			$data['payment'] = 'cheque';
		}

		if (isset($this->request->post['cheque'])) {
			$data['cheque'] = $this->request->post['cheque'];
		} elseif (!empty($affiliate_info)) {
			$data['cheque'] = $affiliate_info['cheque'];
		} else {
			$data['cheque'] = '';
		}

		if (isset($this->request->post['paypal'])) {
			$data['paypal'] = $this->request->post['paypal'];
		} elseif (!empty($affiliate_info)) {
			$data['paypal'] = $affiliate_info['paypal'];
		} else {
			$data['paypal'] = '';
		}

		if (isset($this->request->post['bank_name'])) {
			$data['bank_name'] = $this->request->post['bank_name'];
		} elseif (!empty($affiliate_info)) {
			$data['bank_name'] = $affiliate_info['bank_name'];
		} else {
			$data['bank_name'] = '';
		}

		if (isset($this->request->post['bank_branch_number'])) {
			$data['bank_branch_number'] = $this->request->post['bank_branch_number'];
		} elseif (!empty($affiliate_info)) {
			$data['bank_branch_number'] = $affiliate_info['bank_branch_number'];
		} else {
			$data['bank_branch_number'] = '';
		}

		if (isset($this->request->post['bank_swift_code'])) {
			$data['bank_swift_code'] = $this->request->post['bank_swift_code'];
		} elseif (!empty($affiliate_info)) {
			$data['bank_swift_code'] = $affiliate_info['bank_swift_code'];
		} else {
			$data['bank_swift_code'] = '';
		}

		if (isset($this->request->post['bank_account_name'])) {
			$data['bank_account_name'] = $this->request->post['bank_account_name'];
		} elseif (!empty($affiliate_info)) {
			$data['bank_account_name'] = $affiliate_info['bank_account_name'];
		} else {
			$data['bank_account_name'] = '';
		}

		if (isset($this->request->post['bank_account_number'])) {
			$data['bank_account_number'] = $this->request->post['bank_account_number'];
		} elseif (!empty($affiliate_info)) {
			$data['bank_account_number'] = $affiliate_info['bank_account_number'];
		} else {
			$data['bank_account_number'] = '';
		}

		if (isset($this->request->post['custom_field'])) {
			$data['affiliate_custom_field'] = $this->request->post['custom_field'];
		} elseif (!empty($affiliate_info)) {
			$data['affiliate_custom_field'] = json_decode($affiliate_info['custom_field'], true);
		} else {
			$data['affiliate_custom_field'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		$customer_info = $this->model_customer_customer->getCustomerByEmail($this->request->post['email']);

		if (!isset($this->request->get['customer_id'])) {
			if ($customer_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($customer_info && ($this->request->get['customer_id'] != $customer_info['customer_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		// Custom field validation
		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields(array('filter_customer_group_id' => $this->request->post['customer_group_id']));

		foreach ($custom_fields as $custom_field) {
			if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
				$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			} elseif (($custom_field['location'] == 'account') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
				$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			}
		}

		if ($this->request->post['password'] || (!isset($this->request->get['customer_id']))) {
			if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (isset($this->request->post['address'])) {
			foreach ($this->request->post['address'] as $key => $value) {
				if ((utf8_strlen($value['firstname']) < 1) || (utf8_strlen($value['firstname']) > 32)) {
					$this->error['address'][$key]['firstname'] = $this->language->get('error_firstname');
				}

				if ((utf8_strlen($value['lastname']) < 1) || (utf8_strlen($value['lastname']) > 32)) {
					$this->error['address'][$key]['lastname'] = $this->language->get('error_lastname');
				}

				if ((utf8_strlen($value['address_1']) < 3) || (utf8_strlen($value['address_1']) > 128)) {
					$this->error['address'][$key]['address_1'] = $this->language->get('error_address_1');
				}

				if ((utf8_strlen($value['city']) < 2) || (utf8_strlen($value['city']) > 128)) {
					$this->error['address'][$key]['city'] = $this->language->get('error_city');
				}

				$this->load->model('localisation/country');

				$country_info = $this->model_localisation_country->getCountry($value['country_id']);

				if ($country_info && $country_info['postcode_required'] && (utf8_strlen($value['postcode']) < 2 || utf8_strlen($value['postcode']) > 10)) {
					$this->error['address'][$key]['postcode'] = $this->language->get('error_postcode');
				}

				if ($value['country_id'] == '') {
					$this->error['address'][$key]['country'] = $this->language->get('error_country');
				}

				if (!isset($value['zone_id']) || $value['zone_id'] == '') {
					$this->error['address'][$key]['zone'] = $this->language->get('error_zone');
				}

				foreach ($custom_fields as $custom_field) {
					if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($value['custom_field'][$custom_field['custom_field_id']])) {
						$this->error['address'][$key]['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['location'] == 'address') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($value['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
						$this->error['address'][$key]['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                    }
				}
			}
		}

		if ($this->request->post['affiliate']) {
			if ($this->request->post['payment'] == 'cheque') {
				if ($this->request->post['cheque'] == '') {
					$this->error['cheque'] = $this->language->get('error_cheque');
				}
			} elseif ($this->request->post['payment'] == 'paypal') {
				if ((utf8_strlen($this->request->post['paypal']) > 96) || !filter_var($this->request->post['paypal'], FILTER_VALIDATE_EMAIL)) {
					$this->error['paypal'] = $this->language->get('error_paypal');
				}
			} elseif ($this->request->post['payment'] == 'bank') {
				if ($this->request->post['bank_account_name'] == '') {
					$this->error['bank_account_name'] = $this->language->get('error_bank_account_name');
				}

				if ($this->request->post['bank_account_number'] == '') {
					$this->error['bank_account_number'] = $this->language->get('error_bank_account_number');
				}
			}

			if (!$this->request->post['tracking']) {
				$this->error['tracking'] = $this->language->get('error_tracking');
			}

			$affiliate_info = $this->model_customer_customer->getAffiliateByTracking($this->request->post['tracking']);

			if (!isset($this->request->get['customer_id'])) {
				if ($affiliate_info) {
					$this->error['tracking'] = $this->language->get('error_tracking_exists');
				}
			} else {
				if ($affiliate_info && ($this->request->get['customer_id'] != $affiliate_info['customer_id'])) {
					$this->error['tracking'] = $this->language->get('error_tracking_exists');
				}
			}

			foreach ($custom_fields as $custom_field) {
				if (($custom_field['location'] == 'affiliate') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} elseif (($custom_field['location'] == 'affiliate') && ($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !filter_var($this->request->post['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateUnlock() {
		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function login() {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if ($customer_info) {
			// Create token to login with
			$token = token(64);

			$this->model_customer_customer->editToken($customer_id, $token);

			if (isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id'];
			} else {
				$store_id = 0;
			}

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($store_id);

			if ($store_info) {
				$this->response->redirect($store_info['url'] . 'index.php?route=account/login&token=' . $token);
			} else {
				$this->response->redirect(HTTP_CATALOG . 'index.php?route=account/login&token=' . $token);
			}
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'user_token=' . $this->session->data['user_token'], true)
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function history() {
		$this->load->language('customer/customer');

		$this->load->model('customer/customer');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('config_limit_admin');

		$data['histories'] = array();

		$results = $this->model_customer_customer->getHistories($this->request->get['customer_id'], ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'comment'    => $result['comment'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_customer_customer->getTotalHistories($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('customer/customer/history', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($history_total - $limit)) ? $history_total : ((($page - 1) * $limit) + $limit), $history_total, ceil($history_total / $limit));

		$this->response->setOutput($this->load->view('customer/customer_history', $data));
	}

	public function addHistory() {
		$this->load->language('customer/customer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('customer/customer');

			$this->model_customer_customer->addHistory($this->request->get['customer_id'], $this->request->post['comment']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function transaction() {
		$this->load->language('customer/customer');

		$this->load->model('customer/customer');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('config_limit_admin');

		$data['transactions'] = array();

		$results = $this->model_customer_customer->getTransactions($this->request->get['customer_id'], ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['transactions'][] = array(
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$data['balance'] = $this->currency->format($this->model_customer_customer->getTransactionTotal($this->request->get['customer_id']), $this->config->get('config_currency'));

		$transaction_total = $this->model_customer_customer->getTotalTransactions($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('customer/customer/transaction', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($transaction_total - $limit)) ? $transaction_total : ((($page - 1) * $limit) + $limit), $transaction_total, ceil($transaction_total / $limit));

		$this->response->setOutput($this->load->view('customer/customer_transaction', $data));
	}

	public function addTransaction() {
		$this->load->language('customer/customer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('customer/customer');

			$this->model_customer_customer->addTransaction($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['amount']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function reward() {
		$this->load->language('customer/customer');

		$this->load->model('customer/customer');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('config_limit_admin');

		$data['rewards'] = array();

		$results = $this->model_customer_customer->getRewards($this->request->get['customer_id'], ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['rewards'][] = array(
				'points'      => $result['points'],
				'description' => $result['description'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$data['balance'] = $this->model_customer_customer->getRewardTotal($this->request->get['customer_id']);

		$reward_total = $this->model_customer_customer->getTotalRewards($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $reward_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('customer/customer/reward', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($reward_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($reward_total - $limit)) ? $reward_total : ((($page - 1) * $limit) + $limit), $reward_total, ceil($reward_total / $limit));

		$this->response->setOutput($this->load->view('customer/customer_reward', $data));
	}

	public function addReward() {
		$this->load->language('customer/customer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('customer/customer');

			$this->model_customer_customer->addReward($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['points']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function ip() {
		$this->load->language('customer/customer');

		$this->load->model('customer/customer');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('config_limit_admin');

		$data['ips'] = array();

		$results = $this->model_customer_customer->getIps($this->request->get['customer_id'], ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['ips'][] = array(
				'ip'         => $result['ip'],
				'total'      => $this->model_customer_customer->getTotalCustomersByIp($result['ip']),
				'date_added' => date('d/m/y', strtotime($result['date_added'])),
				'filter_ip'  => $this->url->link('customer/customer', 'user_token=' . $this->session->data['user_token'] . '&filter_ip=' . $result['ip'], true)
			);
		}

		$ip_total = $this->model_customer_customer->getTotalIps($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $ip_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('customer/customer/ip', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($ip_total - $limit)) ? $ip_total : ((($page - 1) * $limit) + $limit), $ip_total, ceil($ip_total / $limit));

		$this->response->setOutput($this->load->view('customer/customer_ip', $data));
	}

	public function autocomplete() {
		$json = array();

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

			if (isset($this->request->get['filter_affiliate'])) {
				$filter_affiliate = $this->request->get['filter_affiliate'];
			} else {
				$filter_affiliate = '';
			}

			$this->load->model('customer/customer');

			$filter_data = array(
				'filter_name'      => $filter_name,
				'filter_email'     => $filter_email,
				'filter_affiliate' => $filter_affiliate,
				'start'            => 0,
				'limit'            => 5
			);

			$results = $this->model_customer_customer->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'customer_group_id' => $result['customer_group_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'customer_group'    => $result['customer_group'],
					'firstname'         => $result['firstname'],
					'lastname'          => $result['lastname'],
					'email'             => $result['email'],
					'telephone'         => $result['telephone'],
					'custom_field'      => json_decode($result['custom_field'], true),
					'address'           => $this->model_customer_customer->getAddresses($result['customer_id'])
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customfield() {
		$json = array();

		$this->load->model('customer/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id'])) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_customer_custom_field->getCustomFields(array('filter_customer_group_id' => $customer_group_id));

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function address() {
		$json = array();

		if (!empty($this->request->get['address_id'])) {
			$this->load->model('customer/customer');

			$json = $this->model_customer_customer->getAddress($this->request->get['address_id']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
