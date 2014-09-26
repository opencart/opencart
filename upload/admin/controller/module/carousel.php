<?php
class ControllerModuleCarousel extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/carousel');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('carousel', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_banner'] = $this->language->get('entry_banner');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_scroll'] = $this->language->get('entry_scroll');
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
			'href' => $this->url->link('module/carousel', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/carousel', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['carousel_status'])) {
			$data['carousel_status'] = $this->request->post['carousel_status'];
		} else {
			$data['carousel_status'] = $this->config->get('carousel_status');
		}
		
		if (isset($this->request->post['carousel_module'])) {
			$modules = $this->request->post['carousel_module'];
		} elseif ($this->config->has('carousel_module')) {
			$modules = $this->config->get('carousel_module');
		} else {
			$modules = array();
		}
		
		$data['carousel_modules'] = array();
		
		foreach ($modules as $key => $module) {
			$data['carousel_modules'][] = array(
				'key'       => $key,
				'banner_id' => $module['banner_id'],
				'limit'     => $module['limit'],
				'scroll'    => $module['scroll'],
				'width'     => $module['width'],
				'height'    => $module['height']
			);
		}

		$this->load->model('design/banner');

		$data['banners'] = $this->model_design_banner->getBanners();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/carousel.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/carousel')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['carousel_module'])) {
			foreach ($this->request->post['carousel_module'] as $key => $value) {
				if (!$value['width'] || !$value['height']) {
					$this->error['image'][$key] = $this->language->get('error_image');
				}
			}
		}

		return !$this->error;
	}
}