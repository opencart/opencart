<?php
class ControllerModuleBestSeller extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/bestseller');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('bestseller', $this->request->post['bestseller_status']);

			$this->cache->delete('product');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_status'] = $this->language->get('entry_status');

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
			'href' => $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/bestseller', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['bestseller_status'])) {
			$data['bestseller_status'] = $this->request->post['bestseller_status'];
		} else {
			$data['bestseller_status'] = $this->config->get('bestseller_status');
		}
		
		if (isset($this->request->post['bestseller_module'])) {
			$modules = $this->request->post['bestseller_module'];
		} elseif ($this->config->has('bestseller_module')) {
			$modules = $this->config->get('bestseller_module');
		} else {
			$modules = array();
		}
		
		$data['bestseller_modules'] = array();
		
		foreach ($modules as $key => $module) {
			$data['bestseller_modules'][] = array(
				'key'    => $key,
				'limit'  => $module['limit'],
				'width'  => $module['width'],
				'height' => $module['height']
			);
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/bestseller.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/bestseller')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['bestseller_module'])) {
			foreach ($this->request->post['bestseller_module'] as $key => $value) {
				if (!$value['image_width'] || !$value['image_height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
				}
			}
		}

		return !$this->error;
	}
}