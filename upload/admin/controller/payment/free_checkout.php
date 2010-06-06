<?php 
class ControllerPaymentFreeCheckout extends Controller {
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('payment/free_checkout');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('setting/setting');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('free_checkout', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
				
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {$this->data['error_warning'] = $this->error['warning'];}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      =>  HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);
		
   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/free_checkout&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/free_checkout&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];	
		
		if (isset($this->request->post['free_checkout_order_status_id'])) {
			$this->data['free_checkout_order_status_id'] = $this->request->post['free_checkout_order_status_id'];
		} else {
			$this->data['free_checkout_order_status_id'] = $this->config->get('free_checkout_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
				
		if (isset($this->request->post['free_checkout_status'])) {
			$this->data['free_checkout_status'] = $this->request->post['free_checkout_status'];
		} else {
			$this->data['free_checkout_status'] = $this->config->get('free_checkout_status');
		}
		
		if (isset($this->request->post['free_checkout_sort_order'])) {
			$this->data['free_checkout_sort_order'] = $this->request->post['free_checkout_sort_order'];
		} else {
			$this->data['free_checkout_sort_order'] = $this->config->get('free_checkout_sort_order');
		}
								
		$this->id       = 'content';
		$this->template = 'payment/free_checkout.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);
		
		$this->response->setOutput($this->render(TRUE));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/free_checkout')) {
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