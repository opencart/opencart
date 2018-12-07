<?php
class ControllerCatalogProductOption extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/product_option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product_option');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/product_option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product_option');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product_option->addProductOption($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_option'])) {
				$url .= '&filter_option=' . urlencode(html_entity_decode($this->request->get['filter_option'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_sort'])) {
				$url .= '&filter_sort=' . $this->request->get['filter_sort'];
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

			$this->response->redirect($this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/product_option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product_option');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_product_option->editProductOption($this->request->get['product_option_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_option'])) {
				$url .= '&filter_option=' . urlencode(html_entity_decode($this->request->get['filter_option'], ENT_QUOTES, 'UTF-8'));
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

			$this->response->redirect($this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/product_option');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/product_option');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $option_id) {
				$this->model_catalog_product_option->deleteProductOption($option_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_product_id'])) {
				$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
			}

			if (isset($this->request->get['filter_product'])) {
				$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_option'])) {
				$url .= '&filter_option=' . urlencode(html_entity_decode($this->request->get['filter_option'], ENT_QUOTES, 'UTF-8'));
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

			$this->response->redirect($this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = '';
		}

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = '';
		}

		if (isset($this->request->get['filter_option'])) {
			$filter_option = $this->request->get['filter_option'];
		} else {
			$filter_option = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
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

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . urlencode(html_entity_decode($this->request->get['filter_option'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . $url)
		);

		$data['add'] = $this->url->link('catalog/product_option/add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/product_option/delete', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['product_options'] = array();

		$filter_data = array(
			'filter_product_id'	=> $filter_product_id,
			'filter_product'	=> $filter_product,
			'filter_option'	    => $filter_option,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$product_option_total = $this->model_catalog_product_option->getTotalProductOptions($filter_data);

		$results = $this->model_catalog_product_option->getProductOptions($filter_data);

		foreach ($results as $result) {
			$data['product_options'][] = array(
				'product_option_id' => $result['product_option_id'],
				'product'           => $result['product'],
				'option'            => $result['option'],
				'type'              => $this->language->get('text_' . $result['type']),
				'sort_order'        => $result['sort_order'],
				'edit'              => $this->url->link('catalog/product_option/edit', 'user_token=' . $this->session->data['user_token'] . '&product_option_id=' . $result['product_option_id'] . $url)
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_product'] = $this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url);
		$data['sort_option'] = $this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . '&sort=od.name' . $url);
		$data['sort_type'] = $this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . '&sort=o.type' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . '&sort=o.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . urlencode(html_entity_decode($this->request->get['filter_option'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $product_option_total,
			'page'  => $page,
			'limit' => $this->config->get('config_limit_admin'),
			'url'   => $this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		));

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_option_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_option_total - $this->config->get('config_limit_admin'))) ? $product_option_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_option_total, ceil($product_option_total / $this->config->get('config_limit_admin')));

		$data['filter_product'] = $filter_product;
		$data['filter_option'] = $filter_option;

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_option_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['product_option_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['product'])) {
			$data['error_product'] = $this->error['product'];
		} else {
			$data['error_product'] = '';
		}

		if (isset($this->error['option'])) {
			$data['error_option'] = $this->error['option'];
		} else {
			$data['error_option'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['filter_product'])) {
			$url .= '&filter_product=' . urlencode(html_entity_decode($this->request->get['filter_product'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_option'])) {
			$url .= '&filter_option=' . urlencode(html_entity_decode($this->request->get['filter_option'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . $url)
		);

		if (!isset($this->request->get['product_option_id'])) {
			$data['action'] = $this->url->link('catalog/product_option/add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('catalog/product_option/edit', 'user_token=' . $this->session->data['user_token'] . '&product_option_id=' . $this->request->get['product_option_id'] . $url);
		}

		$data['cancel'] = $this->url->link('catalog/product_option', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['product_option_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_option_info = $this->model_catalog_product_option->getProductOption($this->request->get['product_option_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['product_id'])) {
			$data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($product_option_info)) {
			$data['product_id'] = $product_option_info['product_id'];
		} else {
			$data['product_id'] = '';
		}

		if (isset($this->request->post['product'])) {
			$data['product'] = $this->request->post['product'];
		} elseif (!empty($product_option_info)) {
			$data['product'] = $product_option_info['product'];
		} else {
			$data['product'] = '';
		}

		if (isset($this->request->post['option_id'])) {
			$data['option_id'] = $this->request->post['option_id'];
		} elseif (!empty($product_option_info)) {
			$data['option_id'] = $product_option_info['option_id'];
		} else {
			$data['option_id'] = '';
		}

		if (isset($this->request->post['option'])) {
			$data['option'] = $this->request->post['option'];
		} elseif (!empty($product_option_info)) {
			$data['option'] = $product_option_info['option'];
		} else {
			$data['option'] = '';
		}

		if (isset($this->request->post['required'])) {
			$data['required'] = $this->request->post['required'];
		} elseif (!empty($product_option_info)) {
			$data['required'] = $product_option_info['required'];
		} else {
			$data['required'] = '';
		}

		$this->load->model('catalog/option');

		$option_info = $this->model_catalog_option->getOption($data['option_id']);

		if ($option_info) {
			$data['type'] = $option_info['type'];
		} else {
			$data['type'] = 'text';
		}

		$data['option_values'] = $this->model_catalog_option->getOptionValues($data['option_id']);

		if (isset($this->request->post['value'])) {
			$data['value'] = $this->request->post['value'];
		} elseif (!empty($product_option_info)) {
			$data['value'] = $product_option_info['value'];
		} else {
			$data['value'] = '';
		}

		// Options
		if (isset($this->request->post['product_option_value'])) {
			$product_option_values = $this->request->post['product_option_value'];
		} elseif (!empty($product_option_info)) {
			$product_option_values = $this->model_catalog_product_option->getProductOptionValues($this->request->get['product_option_id']);
		} else {
			$product_option_values = array();
		}

		$data['product_option_values'] = array();

		foreach ($product_option_values as $product_option_value) {
			$data['product_option_values'][] = array(
				'product_option_value_id' => $product_option_value['product_option_value_id'],
				'option_value_id'         => $product_option_value['option_value_id'],
				'quantity'                => $product_option_value['quantity'],
				'subtract'                => $product_option_value['subtract'],
				'price'                   => $product_option_value['price'],
				'price_prefix'            => $product_option_value['price_prefix'],
				'points'                  => $product_option_value['points'],
				'points_prefix'           => $product_option_value['points_prefix'],
				'weight'                  => $product_option_value['weight'],
				'weight_prefix'           => $product_option_value['weight_prefix']
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/product_option_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/product_option')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['product_id']) {
			$this->error['product'] = $this->language->get('error_product');
		}

		if (!$this->request->post['option_id']) {
			$this->error['option'] = $this->language->get('error_option');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/product_option')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}