<?php
class ControllerSaleCustomerBanIp extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/customer_ban_ip');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer_ban_ip');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/customer_ban_ip');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer_ban_ip');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_ban_ip->addCustomerBanIp($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/customer_ban_ip');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer_ban_ip');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_customer_ban_ip->editCustomerBanIp($this->request->get['customer_ban_ip_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/customer_ban_ip');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/customer_ban_ip');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_ban_ip_id) {
				$this->model_sale_customer_ban_ip->deleteCustomerBanIp($customer_ban_ip_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ip';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('sale/customer_ban_ip/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('sale/customer_ban_ip/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['customer_ban_ips'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$customer_ban_ip_total = $this->model_sale_customer_ban_ip->getTotalCustomerBanIps($filter_data);

		$results = $this->model_sale_customer_ban_ip->getCustomerBanIps($filter_data);

		foreach ($results as $result) {
			$data['customer_ban_ips'][] = array(
				'customer_ban_ip_id' => $result['customer_ban_ip_id'],
				'ip'                 => $result['ip'],
				'total'              => $result['total'],
				'customer'           => $this->url->link('sale/customer', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], 'SSL'),
				'edit'               => $this->url->link('sale/customer_ban_ip/edit', 'token=' . $this->session->data['token'] . '&customer_ban_ip_id=' . $result['customer_ban_ip_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_ip'] = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'] . '&sort=ip' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_ban_ip_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_ban_ip_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_ban_ip_total - $this->config->get('config_limit_admin'))) ? $customer_ban_ip_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_ban_ip_total, ceil($customer_ban_ip_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/customer_ban_ip_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['customer_ban_ip_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['entry_ip'] = $this->language->get('entry_ip');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['ip'])) {
			$data['error_ip'] = $this->error['ip'];
		} else {
			$data['error_ip'] = '';
		}

		$url = '';

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['customer_ban_ip_id'])) {
			$data['action'] = $this->url->link('sale/customer_ban_ip/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('sale/customer_ban_ip/edit', 'token=' . $this->session->data['token'] . '&customer_ban_ip_id=' . $this->request->get['customer_ban_ip_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('sale/customer_ban_ip', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['customer_ban_ip_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$customer_ban_ip_info = $this->model_sale_customer_ban_ip->getCustomerBanIp($this->request->get['customer_ban_ip_id']);
		}

		if (isset($this->request->post['ip'])) {
			$data['ip'] = $this->request->post['ip'];
		} elseif (!empty($customer_ban_ip_info)) {
			$data['ip'] = $customer_ban_ip_info['ip'];
		} else {
			$data['ip'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/customer_ban_ip_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/customer_ban_ip')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['ip']) < 1) || (utf8_strlen($this->request->post['ip']) > 40)) {
			$this->error['ip'] = $this->language->get('error_ip');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/customer_ban_ip')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}