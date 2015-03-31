<?php
class ControllerSaleReturn extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/return');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/return');

		$this->getList();
	}

	public function add() {
		$this->load->language('sale/return');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/return');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_return->addReturn($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_return_id'])) {
				$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
			}

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_status_id'])) {
				$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

			$this->response->redirect($this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('sale/return');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/return');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_sale_return->editReturn($this->request->get['return_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_return_id'])) {
				$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
			}

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_status_id'])) {
				$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

			$this->response->redirect($this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('sale/return');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/return');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $return_id) {
				$this->model_sale_return->deleteReturn($return_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_return_id'])) {
				$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
			}

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_model'])) {
				$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_return_status_id'])) {
				$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

			$this->response->redirect($this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_return_id'])) {
			$filter_return_id = $this->request->get['filter_return_id'];
		} else {
			$filter_return_id = null;
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = null;
		}

		if (isset($this->request->get['filter_model'])) {
			$filter_model = $this->request->get['filter_model'];
		} else {
			$filter_model = null;
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$filter_return_status_id = $this->request->get['filter_return_status_id'];
		} else {
			$filter_return_status_id = null;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = null;
		}

		if (isset($this->request->get['filter_date_modified'])) {
			$filter_date_modified = $this->request->get['filter_date_modified'];
		} else {
			$filter_date_modified = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'r.return_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_return_id'])) {
			$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('sale/return/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('sale/return/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['returns'] = array();

		$filter_data = array(
			'filter_return_id'        => $filter_return_id,
			'filter_order_id'         => $filter_order_id,
			'filter_customer'         => $filter_customer,
			'filter_product'          => $filter_product,
			'filter_model'            => $filter_model,
			'filter_return_status_id' => $filter_return_status_id,
			'filter_date_added'       => $filter_date_added,
			'filter_date_modified'    => $filter_date_modified,
			'sort'                    => $sort,
			'order'                   => $order,
			'start'                   => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                   => $this->config->get('config_limit_admin')
		);

		$return_total = $this->model_sale_return->getTotalReturns($filter_data);

		$results = $this->model_sale_return->getReturns($filter_data);

		foreach ($results as $result) {
			$data['returns'][] = array(
				'return_id'     => $result['return_id'],
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'product'       => $result['product'],
				'model'         => $result['model'],
				'status'        => $result['status'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'edit'          => $this->url->link('sale/return/edit', 'token=' . $this->session->data['token'] . '&return_id=' . $result['return_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_return_id'] = $this->language->get('column_return_id');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_status'] = $this->language->get('column_status');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_date_modified'] = $this->language->get('column_date_modified');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_return_id'] = $this->language->get('entry_return_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_return_status'] = $this->language->get('entry_return_status');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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

		if (isset($this->request->get['filter_return_id'])) {
			$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

		$data['sort_return_id'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=r.return_id' . $url, 'SSL');
		$data['sort_order_id'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=r.order_id' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_product'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=product' . $url, 'SSL');
		$data['sort_model'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=model' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');
		$data['sort_date_modified'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=r.date_modified' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_return_id'])) {
			$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

		$pagination = new Pagination();
		$pagination->total = $return_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($return_total - $this->config->get('config_limit_admin'))) ? $return_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $return_total, ceil($return_total / $this->config->get('config_limit_admin')));

		$data['filter_return_id'] = $filter_return_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_product'] = $filter_product;
		$data['filter_model'] = $filter_model;
		$data['filter_return_status_id'] = $filter_return_status_id;
		$data['filter_date_added'] = $filter_date_added;
		$data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/return_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['return_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_opened'] = $this->language->get('text_opened');
		$data['text_unopened'] = $this->language->get('text_unopened');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_history'] = $this->language->get('text_history');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_date_ordered'] = $this->language->get('entry_date_ordered');
		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_model'] = $this->language->get('entry_model');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_opened'] = $this->language->get('entry_opened');
		$data['entry_return_reason'] = $this->language->get('entry_return_reason');
		$data['entry_return_action'] = $this->language->get('entry_return_action');
		$data['entry_return_status'] = $this->language->get('entry_return_status');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_notify'] = $this->language->get('entry_notify');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_history_add'] = $this->language->get('button_history_add');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_history'] = $this->language->get('tab_history');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['return_id'])) {
			$data['return_id'] = $this->request->get['return_id'];
		} else {
			$data['return_id'] = 0;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['order_id'])) {
			$data['error_order_id'] = $this->error['order_id'];
		} else {
			$data['error_order_id'] = '';
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

		if (isset($this->error['product'])) {
			$data['error_product'] = $this->error['product'];
		} else {
			$data['error_product'] = '';
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_return_id'])) {
			$url .= '&filter_return_id=' . $this->request->get['filter_return_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['return_id'])) {
			$data['action'] = $this->url->link('sale/return/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('sale/return/edit', 'token=' . $this->session->data['token'] . '&return_id=' . $this->request->get['return_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['return_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$return_info = $this->model_sale_return->getReturn($this->request->get['return_id']);
		}

		if (isset($this->request->post['order_id'])) {
			$data['order_id'] = $this->request->post['order_id'];
		} elseif (!empty($return_info)) {
			$data['order_id'] = $return_info['order_id'];
		} else {
			$data['order_id'] = '';
		}

		if (isset($this->request->post['date_ordered'])) {
			$data['date_ordered'] = $this->request->post['date_ordered'];
		} elseif (!empty($return_info)) {
			$data['date_ordered'] = ($return_info['date_ordered'] != '0000-00-00' ? $return_info['date_ordered'] : '');
		} else {
			$data['date_ordered'] = '';
		}

		if (isset($this->request->post['customer'])) {
			$data['customer'] = $this->request->post['customer'];
		} elseif (!empty($return_info)) {
			$data['customer'] = $return_info['customer'];
		} else {
			$data['customer'] = '';
		}

		if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($return_info)) {
			$data['customer_id'] = $return_info['customer_id'];
		} else {
			$data['customer_id'] = '';
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($return_info)) {
			$data['firstname'] = $return_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($return_info)) {
			$data['lastname'] = $return_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($return_info)) {
			$data['email'] = $return_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($return_info)) {
			$data['telephone'] = $return_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['product'])) {
			$data['product'] = $this->request->post['product'];
		} elseif (!empty($return_info)) {
			$data['product'] = $return_info['product'];
		} else {
			$data['product'] = '';
		}

		if (isset($this->request->post['product_id'])) {
			$data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($return_info)) {
			$data['product_id'] = $return_info['product_id'];
		} else {
			$data['product_id'] = '';
		}

		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($return_info)) {
			$data['model'] = $return_info['model'];
		} else {
			$data['model'] = '';
		}

		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($return_info)) {
			$data['quantity'] = $return_info['quantity'];
		} else {
			$data['quantity'] = '';
		}

		if (isset($this->request->post['opened'])) {
			$data['opened'] = $this->request->post['opened'];
		} elseif (!empty($return_info)) {
			$data['opened'] = $return_info['opened'];
		} else {
			$data['opened'] = '';
		}

		if (isset($this->request->post['return_reason_id'])) {
			$data['return_reason_id'] = $this->request->post['return_reason_id'];
		} elseif (!empty($return_info)) {
			$data['return_reason_id'] = $return_info['return_reason_id'];
		} else {
			$data['return_reason_id'] = '';
		}

		$this->load->model('localisation/return_reason');

		$data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

		if (isset($this->request->post['return_action_id'])) {
			$data['return_action_id'] = $this->request->post['return_action_id'];
		} elseif (!empty($return_info)) {
			$data['return_action_id'] = $return_info['return_action_id'];
		} else {
			$data['return_action_id'] = '';
		}

		$this->load->model('localisation/return_action');

		$data['return_actions'] = $this->model_localisation_return_action->getReturnActions();

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (!empty($return_info)) {
			$data['comment'] = $return_info['comment'];
		} else {
			$data['comment'] = '';
		}

		if (isset($this->request->post['return_status_id'])) {
			$data['return_status_id'] = $this->request->post['return_status_id'];
		} elseif (!empty($return_info)) {
			$data['return_status_id'] = $return_info['return_status_id'];
		} else {
			$data['return_status_id'] = '';
		}

		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/return_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/return')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['order_id'])) {
			$this->error['order_id'] = $this->language->get('error_order_id');
		}

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((utf8_strlen($this->request->post['product']) < 1) || (utf8_strlen($this->request->post['product']) > 255)) {
			$this->error['product'] = $this->language->get('error_product');
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (empty($this->request->post['return_reason_id'])) {
			$this->error['reason'] = $this->language->get('error_reason');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/return')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function history() {
		$this->load->language('sale/return');

		$data['error'] = '';
		$data['success'] = '';

		$this->load->model('sale/return');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/return')) {
				$data['error'] = $this->language->get('error_permission');
			}

			if (!$data['error']) {
				$this->model_sale_return->addReturnHistory($this->request->get['return_id'], $this->request->post);

				$data['success'] = $this->language->get('text_success');
			}
		}

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_notify'] = $this->language->get('column_notify');
		$data['column_comment'] = $this->language->get('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$results = $this->model_sale_return->getReturnHistories($this->request->get['return_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = array(
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$history_total = $this->model_sale_return->getTotalReturnHistories($this->request->get['return_id']);

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('sale/return/history', 'token=' . $this->session->data['token'] . '&return_id=' . $this->request->get['return_id'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/return_history.tpl', $data));
	}
}