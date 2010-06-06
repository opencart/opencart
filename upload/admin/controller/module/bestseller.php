<?php
class ControllerModuleBestSeller extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/bestseller');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('bestseller', $this->request->post);		
			
			$this->cache->delete('product');
			
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		$this->data['text_home'] = $this->language->get('text_home');
		
		$this->data['entry_limit'] = $this->language->get('entry_limit');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_module'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=module/bestseller&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/bestseller&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];

		if (isset($this->request->post['bestseller_limit'])) {
			$this->data['bestseller_limit'] = $this->request->post['bestseller_limit'];
		} else {
			$this->data['bestseller_limit'] = $this->config->get('bestseller_limit');
		}	
		
		$this->data['positions'] = array();
		
		$this->data['positions'][] = array(
			'position' => 'left',
			'title'    => $this->language->get('text_left'),
		);
		
		$this->data['positions'][] = array(
			'position' => 'right',
			'title'    => $this->language->get('text_right'),
		);
		
		$this->data['positions'][] = array(
			'position' => 'home',
			'title'    => $this->language->get('text_home'),
		);
		
		if (isset($this->request->post['bestseller_position'])) {
			$this->data['bestseller_position'] = $this->request->post['bestseller_position'];
		} else {
			$this->data['bestseller_position'] = $this->config->get('bestseller_position');
		}
		
		if (isset($this->request->post['bestseller_status'])) {
			$this->data['bestseller_status'] = $this->request->post['bestseller_status'];
		} else {
			$this->data['bestseller_status'] = $this->config->get('bestseller_status');
		}
		
		if (isset($this->request->post['bestseller_sort_order'])) {
			$this->data['bestseller_sort_order'] = $this->request->post['bestseller_sort_order'];
		} else {
			$this->data['bestseller_sort_order'] = $this->config->get('bestseller_sort_order');
		}				
		
		$this->template = 'module/bestseller.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/bestseller')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>