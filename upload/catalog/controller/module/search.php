<?php  
class ControllerModuleSearch extends Controller {
	protected function index() {
		$this->language->load('module/search');
		
		$this->data['text_search'] = $this->language->get('text_search');
		
		// Search		
		if (isset($this->request->get['search'])) {
			$this->data['search'] = $this->request->get['search'];
		} else {
			$this->data['search'] = '';
		}
						
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/search.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/search.tpl';
		} else {
			$this->template = 'default/template/module/search.tpl';
		}
		
		$this->render();
	}
}
?>