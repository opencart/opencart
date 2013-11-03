<?php
class ControllerAccountCustomField extends Controller {
	private $error = array();
	      
  	private function index($type, $customer_group_id) {
		$this->language->load('account/custom_field');
		
		$this->data['text_select'] = $this->language->get('text_select');
		
		$this->load->model('account/custom_field');
		
		$this->data['custom_fields'] = $this->model_account_custom_field->getCustomFields('registration', $customer_group_id);
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/custom_field.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/custom_field.tpl';
		} else {
			$this->template = 'default/template/account/custom_field.tpl';
		}
				
		$this->render();		
	}
}
?>