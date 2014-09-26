<?php
class ControllerModuleFeatured extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/featured');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('featured', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_product'] = $this->language->get('help_product');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['image'])) {
			$data['error_image'] = $this->error['image'];
		} else {
			$data['error_image'] = array();
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/featured', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/featured', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->post['featured_product'])) {
			$data['featured_product'] = $this->request->post['featured_product'];
		} else {
			$data['featured_product'] = $this->config->get('featured_product');
		}

		$this->load->model('catalog/product');

		if (isset($this->request->post['featured_product'])) {
			$products = explode(',', $this->request->post['featured_product']);
		} else {
			$products = explode(',', $this->config->get('featured_product'));
		}

		$data['products'] = array();

		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				);
			}
		}
		
		if (isset($this->request->post['featured_status'])) {
			$data['featured_status'] = $this->request->post['featured_status'];
		} else {
			$data['featured_status'] = $this->config->get('featured_status');
		}
				
		if (isset($this->request->post['featured_module'])) {
			$modules = $this->request->post['featured_module'];
		} elseif ($this->config->has('featured_module')) {
			$modules = $this->config->get('featured_module');
		} else {
			$modules = array();
		}
		
		$data['featured_modules'] = array();
		
		foreach ($modules as $key => $module) {
			$data['featured_modules'][] = array(
				'key'    => $key,
				'limit'  => $module['limit'],
				'width'  => $module['width'],
				'height' => $module['height']
			);
		}
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/featured.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/featured')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['featured_module'])) {
			foreach ($this->request->post['featured_module'] as $key => $value) {
				if (!$value['width'] || !$value['height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
				}
			}
		}

		return !$this->error;
	}
}
