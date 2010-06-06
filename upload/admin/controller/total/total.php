<?php 
class ControllerTotalTotal extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/total');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('total', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
 
		$this->data['tab_general'] = $this->language->get('tab_general');

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
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_total'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=total/total&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=total/total&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token'];

		if (isset($this->request->post['total_status'])) {
			$this->data['total_status'] = $this->request->post['total_status'];
		} else {
			$this->data['total_status'] = $this->config->get('total_status');
		}

		if (isset($this->request->post['total_sort_order'])) {
			$this->data['total_sort_order'] = $this->request->post['total_sort_order'];
		} else {
			$this->data['total_sort_order'] = $this->config->get('total_sort_order');
		}
																				
		$this->template = 'total/total.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/total')) {
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