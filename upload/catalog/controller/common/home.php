<?php  
class ControllerCommonHome extends Controller {
	public function index() {
		$this->language->load('common/home');
		
		$this->document->title = $this->config->get('config_title');
		$this->document->description = $this->config->get('config_meta_description');
		
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
		
		$this->load->model('setting/store');
		
		if (!$this->config->get('config_store_id')) {
			$this->data['welcome'] = html_entity_decode($this->config->get('config_description_' . $this->config->get('config_language_id')), ENT_QUOTES, 'UTF-8');
		} else {
			$store_info = $this->model_setting_store->getStore($this->config->get('config_store_id'));
			
			if ($store_info) {
				$this->data['welcome'] = html_entity_decode($store_info['description'], ENT_QUOTES, 'UTF-8');
			} else {
				$this->data['welcome'] = '';
			}
		}
						
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/home.tpl';
		} else {
			$this->template = 'default/template/common/home.tpl';
		}
		
		$this->children = array();
		
		$this->load->model('checkout/extension');
		
		$module_data = $this->model_checkout_extension->getExtensionsByPosition('module', 'home');
		
		$this->data['modules'] = $module_data;
		
		foreach ($module_data as $result) {
			$this->children[] = 'module/' . $result['code'];
		}
		
		$this->children[] = 'common/column_right';
		$this->children[] =	'common/column_left';
		$this->children[] =	'common/footer';
		$this->children[] =	'common/header';
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
}
?>