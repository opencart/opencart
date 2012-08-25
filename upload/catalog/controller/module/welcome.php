<?php  
class ControllerModuleWelcome extends Controller {
	protected function index($setting) {
		
		$this->data['box_status'] = $setting['box_status'];
		
    	$this->data['heading'] = html_entity_decode($setting['heading'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
    	$this->data['message'] = html_entity_decode($setting['description'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/welcome.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/welcome.tpl';
		} else {
			$this->template = 'default/template/module/welcome.tpl';
		}
		
		$this->render();
	}
}
?>