<?php
class ControllerDesignLayout extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/layout');

		$this->getList();
	}

	public function add() {
		$this->load->language('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/layout');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_layout->addLayout($this->request->post);

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

			$this->response->redirect($this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/layout');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_layout->editLayout($this->request->get['layout_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/layout');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $layout_id) {
				$this->model_design_layout->deleteLayout($layout_id);
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

			$this->response->redirect($this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			'href' => $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['insert'] = $this->url->link('design/layout/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('design/layout/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['layouts'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$layout_total = $this->model_design_layout->getTotalLayouts();

		$results = $this->model_design_layout->getLayouts($filter_data);

		foreach ($results as $result) {
			$data['layouts'][] = array(
				'layout_id' => $result['layout_id'],
				'name'      => $result['name'],
				'edit'      => $this->url->link('design/layout/edit', 'token=' . $this->session->data['token'] . '&layout_id=' . $result['layout_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_insert'] = $this->language->get('button_insert');
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

		$data['sort_name'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $layout_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($layout_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($layout_total - $this->config->get('config_limit_admin'))) ? $layout_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $layout_total, ceil($layout_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/layout_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['layout_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_route'] = $this->language->get('entry_route');
		$data['entry_module'] = $this->language->get('entry_module');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_route_add'] = $this->language->get('button_route_add');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
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
			'href' => $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['layout_id'])) {
			$data['action'] = $this->url->link('design/layout/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('design/layout/edit', 'token=' . $this->session->data['token'] . '&layout_id=' . $this->request->get['layout_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('design/layout', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['layout_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$layout_info = $this->model_design_layout->getLayout($this->request->get['layout_id']);
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($layout_info)) {
			$data['name'] = $layout_info['name'];
		} else {
			$data['name'] = '';
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['layout_route'])) {
			$data['layout_routes'] = $this->request->post['layout_route'];
		} elseif (isset($this->request->get['layout_id'])) {
			$data['layout_routes'] = $this->model_design_layout->getLayoutRoutes($this->request->get['layout_id']);
		} else {
			$data['layout_routes'] = array();
		}
		
		if (isset($this->request->post['layout_module'])) {
			$data['layout_modules'] = $this->request->post['layout_module'];
		} elseif (isset($this->request->get['layout_id'])) {
			$data['layout_modules'] = $this->model_design_layout->getLayoutModules($this->request->get['layout_id']);
		} else {
			$data['layout_modules'] = array();
		}
		
		$this->load->model('extension/extension');
		
		$data['modules'] = array();
		
		// Get a list of installed modules
		$extensions = $this->model_extension_extension->getInstalled('module');
				
		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $code) {
			$this->load->language('module/' . $code);
		
			$i = 1;
			
			$module_data = array();
			
			if ($this->config->has($code . '_module')) {
				$modules = $this->config->get($code . '_module');
				
				foreach (array_keys($modules) as $key) {
					$module_data[] = array(
						'name' => $this->language->get('heading_title') . ' ' . $i++,
						'code' => $code . '.' . $key
					);
				}
			} else {
				$module_data[] = array(
					'name' => $this->language->get('heading_title'),
					'code' => $code
				);
			}
			
			if ($module_data) {
				$data['modules'][] = array(
					'name'   => $this->language->get('heading_title'),
					'module' => $module_data
				);
			}
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/layout_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/layout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/layout')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('catalog/product');
		$this->load->model('catalog/category');
		$this->load->model('catalog/information');

		foreach ($this->request->post['selected'] as $layout_id) {
			if ($this->config->get('config_layout_id') == $layout_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByLayoutId($layout_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$product_total = $this->model_catalog_product->getTotalProductsByLayoutId($layout_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}

			$category_total = $this->model_catalog_category->getTotalCategoriesByLayoutId($layout_id);

			if ($category_total) {
				$this->error['warning'] = sprintf($this->language->get('error_category'), $category_total);
			}

			$information_total = $this->model_catalog_information->getTotalInformationsByLayoutId($layout_id);

			if ($information_total) {
				$this->error['warning'] = sprintf($this->language->get('error_information'), $information_total);
			}
		}

		return !$this->error;
	}
}