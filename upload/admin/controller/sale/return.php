<?php    
class ControllerSaleReturn extends Controller { 
	private $error = array();

	public function index() {
		$this->language->load('sale/return');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/return');

		$this->getList();
	}

	public function insert() {
		$this->language->load('sale/return');

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

			$this->redirect($this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('sale/return');

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

			$this->redirect($this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('sale/return');

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

			$this->redirect($this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$this->data['insert'] = $this->url->link('sale/return/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('sale/return/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['returns'] = array();

		$data = array(
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
			'start'                   => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                   => $this->config->get('config_admin_limit')
		);

		$return_total = $this->model_sale_return->getTotalReturns($data);

		$results = $this->model_sale_return->getReturns($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/return/info', 'token=' . $this->session->data['token'] . '&return_id=' . $result['return_id'] . $url, 'SSL')
			);

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('sale/return/update', 'token=' . $this->session->data['token'] . '&return_id=' . $result['return_id'] . $url, 'SSL')
			);

			$this->data['returns'][] = array(
				'return_id'     => $result['return_id'],
				'order_id'      => $result['order_id'],
				'customer'      => $result['customer'],
				'product'       => $result['product'],
				'model'         => $result['model'],
				'status'        => $result['status'],
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),	
				'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),				
				'selected'      => isset($this->request->post['selected']) && in_array($result['return_id'], $this->request->post['selected']),
				'action'        => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_return_id'] = $this->language->get('column_return_id');
		$this->data['column_order_id'] = $this->language->get('column_order_id');
		$this->data['column_customer'] = $this->language->get('column_customer');
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_modified'] = $this->language->get('column_date_modified');
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
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

		$this->data['sort_return_id'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=r.return_id' . $url, 'SSL');
		$this->data['sort_order_id'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=r.order_id' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$this->data['sort_product'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=product' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=model' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$this->data['sort_date_added'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=r.date_added' . $url, 'SSL');
		$this->data['sort_date_modified'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . '&sort=r.date_modified' . $url, 'SSL');

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
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_return_id'] = $filter_return_id;
		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_product'] = $filter_product;
		$this->data['filter_model'] = $filter_model;
		$this->data['filter_return_status_id'] = $filter_return_status_id;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_date_modified'] = $filter_date_modified;

		$this->load->model('localisation/return_status');

		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/return_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_opened'] = $this->language->get('text_opened');
		$this->data['text_unopened'] = $this->language->get('text_unopened');	

		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_order_id'] = $this->language->get('entry_order_id');
		$this->data['entry_date_ordered'] = $this->language->get('entry_date_ordered');
		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_return_status'] = $this->language->get('entry_return_status');
		$this->data['entry_comment'] = $this->language->get('entry_comment');	
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_model'] = $this->language->get('entry_model');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_reason'] = $this->language->get('entry_reason');
		$this->data['entry_opened'] = $this->language->get('entry_opened');
		$this->data['entry_action'] = $this->language->get('entry_action');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_return'] = $this->language->get('tab_return');
		$this->data['tab_product'] = $this->language->get('tab_product');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['order_id'])) {
			$this->data['error_order_id'] = $this->error['order_id'];
		} else {
			$this->data['error_order_id'] = '';
		}		

		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}

		if (isset($this->error['product'])) {
			$this->data['error_product'] = $this->error['product'];
		} else {
			$this->data['error_product'] = '';
		}

		if (isset($this->error['model'])) {
			$this->data['error_model'] = $this->error['model'];
		} else {
			$this->data['error_model'] = '';
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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['return_id'])) {
			$this->data['action'] = $this->url->link('sale/return/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('sale/return/update', 'token=' . $this->session->data['token'] . '&return_id=' . $this->request->get['return_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['return_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$return_info = $this->model_sale_return->getReturn($this->request->get['return_id']);
		}

		if (isset($this->request->post['order_id'])) {
			$this->data['order_id'] = $this->request->post['order_id'];
		} elseif (!empty($return_info)) { 
			$this->data['order_id'] = $return_info['order_id'];
		} else {
			$this->data['order_id'] = '';
		}

		if (isset($this->request->post['date_ordered'])) {
			$this->data['date_ordered'] = $this->request->post['date_ordered'];
		} elseif (!empty($return_info)) { 
			$this->data['date_ordered'] = $return_info['date_ordered'];
		} else {
			$this->data['date_ordered'] = '';
		}

		if (isset($this->request->post['customer'])) {
			$this->data['customer'] = $this->request->post['customer'];
		} elseif (!empty($return_info)) {
			$this->data['customer'] = $return_info['customer'];
		} else {
			$this->data['customer'] = '';
		}

		if (isset($this->request->post['customer_id'])) {
			$this->data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($return_info)) {
			$this->data['customer_id'] = $return_info['customer_id'];
		} else {
			$this->data['customer_id'] = '';
		}

		if (isset($this->request->post['firstname'])) {
			$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($return_info)) { 
			$this->data['firstname'] = $return_info['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($return_info)) { 
			$this->data['lastname'] = $return_info['lastname'];
		} else {
			$this->data['lastname'] = '';
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} elseif (!empty($return_info)) { 
			$this->data['email'] = $return_info['email'];
		} else {
			$this->data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($return_info)) { 
			$this->data['telephone'] = $return_info['telephone'];
		} else {
			$this->data['telephone'] = '';
		}

		if (isset($this->request->post['product'])) {
			$this->data['product'] = $this->request->post['product'];
		} elseif (!empty($return_info)) {
			$this->data['product'] = $return_info['product'];
		} else {
			$this->data['product'] = '';
		}	

		if (isset($this->request->post['product_id'])) {
			$this->data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($return_info)) {
			$this->data['product_id'] = $return_info['product_id'];
		} else {
			$this->data['product_id'] = '';
		}	

		if (isset($this->request->post['model'])) {
			$this->data['model'] = $this->request->post['model'];
		} elseif (!empty($return_info)) {
			$this->data['model'] = $return_info['model'];
		} else {
			$this->data['model'] = '';
		}

		if (isset($this->request->post['quantity'])) {
			$this->data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($return_info)) {
			$this->data['quantity'] = $return_info['quantity'];
		} else {
			$this->data['quantity'] = '';
		}

		if (isset($this->request->post['opened'])) {
			$this->data['opened'] = $this->request->post['opened'];
		} elseif (!empty($return_info)) {
			$this->data['opened'] = $return_info['opened'];
		} else {
			$this->data['opened'] = '';
		}

		if (isset($this->request->post['return_reason_id'])) {
			$this->data['return_reason_id'] = $this->request->post['return_reason_id'];
		} elseif (!empty($return_info)) {
			$this->data['return_reason_id'] = $return_info['return_reason_id'];
		} else {
			$this->data['return_reason_id'] = '';
		}

		$this->load->model('localisation/return_reason');

		$this->data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

		if (isset($this->request->post['return_action_id'])) {
			$this->data['return_action_id'] = $this->request->post['return_action_id'];
		} elseif (!empty($return_info)) {
			$this->data['return_action_id'] = $return_info['return_action_id'];
		} else {
			$this->data['return_action_id'] = '';
		}				

		$this->load->model('localisation/return_action');

		$this->data['return_actions'] = $this->model_localisation_return_action->getReturnActions();

		if (isset($this->request->post['comment'])) {
			$this->data['comment'] = $this->request->post['comment'];
		} elseif (!empty($return_info)) {
			$this->data['comment'] = $return_info['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if (isset($this->request->post['return_status_id'])) {
			$this->data['return_status_id'] = $this->request->post['return_status_id'];
		} elseif (!empty($return_info)) {
			$this->data['return_status_id'] = $return_info['return_status_id'];
		} else {
			$this->data['return_status_id'] = '';
		}

		$this->load->model('localisation/return_status');

		$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$this->template = 'sale/return_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function info() {
		$this->load->model('sale/return');

		if (isset($this->request->get['return_id'])) {
			$return_id = $this->request->get['return_id'];
		} else {
			$return_id = 0;
		}

		$return_info = $this->model_sale_return->getReturn($return_id);

		if ($return_info) {
			$this->language->load('sale/return');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_wait'] = $this->language->get('text_wait');	
			$this->data['text_return_id'] = $this->language->get('text_return_id');
			$this->data['text_order_id'] = $this->language->get('text_order_id');
			$this->data['text_date_ordered'] = $this->language->get('text_date_ordered');
			$this->data['text_customer'] = $this->language->get('text_customer');
			$this->data['text_email'] = $this->language->get('text_email');
			$this->data['text_telephone'] = $this->language->get('text_telephone');
			$this->data['text_return_status'] = $this->language->get('text_return_status');
			$this->data['text_date_added'] = $this->language->get('text_date_added');	
			$this->data['text_date_modified'] = $this->language->get('text_date_modified');				
			$this->data['text_product'] = $this->language->get('text_product');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_quantity'] = $this->language->get('text_quantity');
			$this->data['text_return_reason'] = $this->language->get('text_return_reason');
			$this->data['text_opened'] = $this->language->get('text_opened');		
			$this->data['text_comment'] = $this->language->get('text_comment');			
			$this->data['text_return_action'] = $this->language->get('text_return_action');

			$this->data['entry_return_status'] = $this->language->get('entry_return_status');
			$this->data['entry_notify'] = $this->language->get('entry_notify');
			$this->data['entry_comment'] = $this->language->get('entry_comment');

			$this->data['button_save'] = $this->language->get('button_save');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
			$this->data['button_add_history'] = $this->language->get('button_add_history');				

			$this->data['tab_return'] = $this->language->get('tab_return');
			$this->data['tab_product'] = $this->language->get('tab_product');
			$this->data['tab_history'] = $this->language->get('tab_history');


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

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
			);

			$this->data['cancel'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'] . $url, 'SSL');			

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($return_info['order_id']);

			$this->data['token'] = $this->session->data['token'];

			$this->data['return_id'] = $return_info['return_id'];
			$this->data['order_id'] = $return_info['order_id'];

			if ($return_info['order_id'] && $order_info) {
				$this->data['order'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $return_info['order_id'], 'SSL');
			} else {
				$this->data['order'] = '';
			}

			$this->data['date_ordered'] = date($this->language->get('date_format_short'), strtotime($return_info['date_ordered']));
			$this->data['firstname'] = $return_info['firstname'];
			$this->data['lastname'] = $return_info['lastname'];

			if ($return_info['customer_id']) {
				$this->data['customer'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $return_info['customer_id'], 'SSL');
			} else {
				$this->data['customer'] = '';
			}

			$this->data['email'] = $return_info['email'];
			$this->data['telephone'] = $return_info['telephone'];

			$this->load->model('localisation/return_status');

			$return_status_info = $this->model_localisation_return_status->getReturnStatus($return_info['return_status_id']);

			if ($return_status_info) {
				$this->data['return_status'] = $return_status_info['name'];
			} else {
				$this->data['return_status'] = '';
			}		

			$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($return_info['date_added']));
			$this->data['date_modified'] = date($this->language->get('date_format_short'), strtotime($return_info['date_modified']));
			$this->data['product'] = $return_info['product'];
			$this->data['model'] = $return_info['model'];
			$this->data['quantity'] = $return_info['quantity'];

			$this->load->model('localisation/return_reason');

			$return_reason_info = $this->model_localisation_return_reason->getReturnReason($return_info['return_reason_id']);

			if ($return_reason_info) {
				$this->data['return_reason'] = $return_reason_info['name'];
			} else {
				$this->data['return_reason'] = '';
			}			

			$this->data['opened'] = $return_info['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no');
			$this->data['comment'] = nl2br($return_info['comment']);

			$this->load->model('localisation/return_action');

			$this->data['return_actions'] = $this->model_localisation_return_action->getReturnActions(); 

			$this->data['return_action_id'] = $return_info['return_action_id'];

			$this->data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();				

			$this->data['return_status_id'] = $return_info['return_status_id'];

			$this->template = 'sale/return_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());		
		} else {
			$this->language->load('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_not_found'] = $this->language->get('text_not_found');

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => ' :: '
			);

			$this->template = 'error/not_found.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());			
		}
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/return')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen($this->request->post['firstname']) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen($this->request->post['lastname']) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
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

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/return')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}  
	}

	public function action() {
		$this->language->load('sale/return');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/return')) {
				$json['error'] = $this->language->get('error_permission');
			}

			if (!$json) { 
				$this->load->model('sale/return');

				$json['success'] = $this->language->get('text_success');

				$this->model_sale_return->editReturnAction($this->request->get['return_id'], $this->request->post['return_action_id']);
			}
		}

		$this->response->setOutput(json_encode($json));	
	}

	public function history() {
		$this->language->load('sale/return');

		$this->data['error'] = '';
		$this->data['success'] = '';

		$this->load->model('sale/return');

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'sale/return')) { 
				$this->data['error'] = $this->language->get('error_permission');
			}

			if (!$this->data['error']) { 
				$this->model_sale_return->addReturnHistory($this->request->get['return_id'], $this->request->post);

				$this->data['success'] = $this->language->get('text_success');
			}
		}

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_notify'] = $this->language->get('column_notify');
		$this->data['column_comment'] = $this->language->get('column_comment');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}  

		$this->data['histories'] = array();

		$results = $this->model_sale_return->getReturnHistories($this->request->get['return_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$this->data['histories'][] = array(
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
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/return/history', 'token=' . $this->session->data['token'] . '&return_id=' . $this->request->get['return_id'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'sale/return_history.tpl';		

		$this->response->setOutput($this->render());
	}
}
?>