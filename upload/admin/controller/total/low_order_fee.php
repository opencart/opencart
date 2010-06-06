<?php 
class ControllerTotalLowOrderFee extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/low_order_fee');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('low_order_fee', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_fee'] = $this->language->get('entry_fee');
		$this->data['entry_tax'] = $this->language->get('entry_tax');
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
       		'href'      => HTTPS_SERVER . 'index.php?route=total/low_order_fee&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=total/low_order_fee&token=' . $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/total&token=' . $this->session->data['token'];

		if (isset($this->request->post['low_order_fee_total'])) {
			$this->data['low_order_fee_total'] = $this->request->post['low_order_fee_total'];
		} else {
			$this->data['low_order_fee_total'] = $this->config->get('low_order_fee_total');
		}
		
		if (isset($this->request->post['low_order_fee_fee'])) {
			$this->data['low_order_fee_fee'] = $this->request->post['low_order_fee_fee'];
		} else {
			$this->data['low_order_fee_fee'] = $this->config->get('low_order_fee_fee');
		}

		if (isset($this->request->post['low_order_fee_tax_class_id'])) {
			$this->data['low_order_fee_tax_class_id'] = $this->request->post['low_order_fee_tax_class_id'];
		} else {
			$this->data['low_order_fee_tax_class_id'] = $this->config->get('low_order_fee_tax_class_id');
		}
		
		if (isset($this->request->post['low_order_fee_status'])) {
			$this->data['low_order_fee_status'] = $this->request->post['low_order_fee_status'];
		} else {
			$this->data['low_order_fee_status'] = $this->config->get('low_order_fee_status');
		}

		if (isset($this->request->post['low_order_fee_sort_order'])) {
			$this->data['low_order_fee_sort_order'] = $this->request->post['low_order_fee_sort_order'];
		} else {
			$this->data['low_order_fee_sort_order'] = $this->config->get('low_order_fee_sort_order');
		}
		
		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		
		$this->template = 'total/low_order_fee.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/low_order_fee')) {
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