<?php  
class ControllerModuleWelcome extends Controller {
	public function index($setting) {
		$this->load->language('module/welcome');
		
    	$data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
    	
		$data['message'] = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/welcome.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/module/welcome.tpl', $data);
		} else {
			return $this->load->view('default/template/module/welcome.tpl', $data);
		}
	}
}