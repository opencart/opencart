<?php
class ControllerModuleEbay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/ebay');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$this->load->model('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('ebay', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->cache->delete('ebay');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_about'] = $this->language->get('text_about');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_latest'] = $this->language->get('text_latest');
		$data['text_random'] = $this->language->get('text_random');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_enabled'] = $this->language->get('text_enabled');

		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_keywords'] = $this->language->get('entry_keywords');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_site'] = $this->language->get('entry_site');
		$data['entry_sort'] = $this->language->get('entry_sort');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}
		
		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/ebay', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/ebay', 'token=' . $this->session->data['token'], 'SSL');
				
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
					
		if (isset($this->request->post['ebay_username'])) {
			$data['ebay_username'] = $this->request->post['ebay_username'];
		} else {
			$data['ebay_username'] = $this->config->get('ebay_username');
		}
		
		if (isset($this->request->post['ebay_keywords'])) {
			$data['ebay_keywords'] = $this->request->post['ebay_keywords'];
		} else {
			$data['ebay_keywords'] = '';
		}
		
		if (isset($this->request->post['ebay_description'])) {
			$data['ebay_description'] = $this->request->post['ebay_description'];
		} else {
			$data['ebay_description'] = '';
		}
		
		if (isset($this->request->post['ebay_limit'])) {
			$data['ebay_limit'] = $this->request->post['ebay_limit'];
		
		} else {
			$data['ebay_limit'] = 5;
		}
			
		if (isset($this->request->post['ebay_width'])) {
			$data['ebay_width'] = $this->request->post['width'];
		} else {
			$data['ebay_width'] = 200;
		}	
			
		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} else {
			$data['height'] = 200;
		}	
		
		if (isset($this->request->post['ebay_sort'])) {
			$data['ebay_sort'] = $this->request->post['ebay_sort'];
		} else {
			$data['ebay_sort'] = 'StartTimeNewest';
		}	

		if (isset($this->request->post['ebay_site'])) {
			$data['ebay_site'] = $this->request->post['ebay_site'];
		} else {
			$data['ebay_site'] = '';
		}
		
		$data['sites'] = array();
		
		$data['sites'][] = array(
			'text'  => 'USA',
			'value' => 0
		);

		$data['ebay_sites'][] = array(
			'text'  => 'UK',
			'value' => 3
		);
		$data['sites'][] = array(
			'text'  => 'Australia',
			'value' => 15
		);
		
		$data['sites'][] = array(
			'text'  => 'Canada (English)',
			'value' => 2
		);
		
		$data['ebay_sites'][] = array(
			'text'  => 'France',
			'value' => 71
		);
		$data['sites'][] = array(
			'text'  => 'Germany',
			'value' => 77
		);
		$data['sites'][] = array(
			'text'  => 'Italy',
			'value' => 101
		);
		$data['sites'][] = array(
			'text'  => 'Spain',
			'value' => 186
		);
		$data['ebay_sites'][] = array(
			'text'  => 'Ireland',
			'value' => 205
		);
		
		$data['sites'][] = array(
			'text'  => 'Austria',
			'value' => 16
		);
		
		$data['sites'][] = array(
			'text'  => 'Netherlands',
			'value' => 146
		);	
		
		$data['sites'][] = array(
			'text'  => 'Belgium (French)',
			'value' => 23
		);	
		
		$data['sites'][] = array(
			'text'  => 'Belgium (Dutch)',
			'value' => 123
		);	
		
		if (isset($this->request->post['ebay_status'])) {
			$data['ebay_status'] = $this->request->post['ebay_status'];
		} else {
			$data['ebay_status'] = $this->config->get('ebay_status');
		}		

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/ebay.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/ebay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->request->post['ebay_width']) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if (!$this->request->post['ebay_height']) {
			$this->error['height'] = $this->language->get('error_height');
		}		

		return !$this->error;
	}
}