<?php
class ControllerModuleGoogleTalk extends Controller {
	private $error = array(); 
	
	public function index() {   
		$this->load->language('module/google_talk');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('google_talk', $this->request->post);		
					
			$this->session->data['success'] = $this->language->get('text_success');
						
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token']);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_left'] = $this->language->get('text_left');
		$this->data['text_right'] = $this->language->get('text_right');
		
		$this->data['entry_code'] = $this->language->get('entry_code');
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
		
 		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
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
       		'href'      => HTTPS_SERVER . 'index.php?route=module/google_talk&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=module/google_talk&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/module&token=' . $this->session->data['token'];

		if (isset($this->request->post['google_talk_code'])) {
			$this->data['google_talk_code'] = $this->request->post['google_talk_code'];
		} else {
			$this->data['google_talk_code'] = $this->config->get('google_talk_code');
		}	
		
		if (isset($this->request->post['google_talk_position'])) {
			$this->data['google_talk_position'] = $this->request->post['google_talk_position'];
		} else {
			$this->data['google_talk_position'] = $this->config->get('google_talk_position');
		}
		
		if (isset($this->request->post['google_talk_status'])) {
			$this->data['google_talk_status'] = $this->request->post['google_talk_status'];
		} else {
			$this->data['google_talk_status'] = $this->config->get('google_talk_status');
		}
		
		if (isset($this->request->post['google_talk_sort_order'])) {
			$this->data['google_talk_sort_order'] = $this->request->post['google_talk_sort_order'];
		} else {
			$this->data['google_talk_sort_order'] = $this->config->get('google_talk_sort_order');
		}				
		
		$this->template = 'module/google_talk.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/google_talk')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['google_talk_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>