<?php
class ControllerLocalisationOrderStatus extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/order_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/order_status');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/order_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/order_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_order_status->addOrderStatus($this->request->post);

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

			$this->response->redirect($this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/order_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/order_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_order_status->editOrderStatus($this->request->get['order_status_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/order_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/order_status');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $order_status_id) {
				$this->model_localisation_order_status->deleteOrderStatus($order_status_id);
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

			$this->response->redirect($this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
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
			'href' => $this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/order_status/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/order_status/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['order_statuses'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$order_status_total = $this->model_localisation_order_status->getTotalOrderStatuses();

		$results = $this->model_localisation_order_status->getOrderStatuses($filter_data);

		foreach ($results as $result) {
			$data['order_statuses'][] = array(
				'order_status_id' => $result['order_status_id'],
				'name'            => $result['name'] . (($result['order_status_id'] == $this->config->get('config_order_status_id')) ? $this->language->get('text_default') : null),
				'edit'            => $this->url->link('localisation/order_status/edit', 'user_token=' . $this->session->data['user_token'] . '&order_status_id=' . $result['order_status_id'] . $url, true)
			);
		}

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

		$data['sort_name'] = $this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_status_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_status_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($order_status_total - $this->config->get('config_limit_admin'))) ? $order_status_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $order_status_total, ceil($order_status_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/order_status_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['order_status_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['order_status_id'])) {
			$data['action'] = $this->url->link('localisation/order_status/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/order_status/edit', 'user_token=' . $this->session->data['user_token'] . '&order_status_id=' . $this->request->get['order_status_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('localisation/order_status', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['order_status'])) {
			$data['order_status'] = $this->request->post['order_status'];
		} elseif (isset($this->request->get['order_status_id'])) {
			$data['order_status'] = $this->model_localisation_order_status->getOrderStatusDescriptions($this->request->get['order_status_id']);
		} else {
			$data['order_status'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/order_status_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/order_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['order_status'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/order_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('sale/order');

		foreach ($this->request->post['selected'] as $order_status_id) {
			if ($this->config->get('config_order_status_id') == $order_status_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			if ($this->config->get('config_download_status_id') == $order_status_id) {
				$this->error['warning'] = $this->language->get('error_download');
			}

			$store_total = $this->model_setting_store->getTotalStoresByOrderStatusId($order_status_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$order_total = $this->model_sale_order->getTotalOrdersByOrderStatusId($order_status_id);

			if ($order_total) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
			}

			$order_total = $this->model_sale_order->getTotalOrderHistoriesByOrderStatusId($order_status_id);

			if ($order_total) {
				$this->error['warning'] = sprintf($this->language->get('error_order'), $order_total);
			}
		}

		return !$this->error;
	}
}
