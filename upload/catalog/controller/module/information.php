<?php  
class ControllerModuleInformation extends Controller {
	protected function index() {
		$this->language->load('module/information');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_contact'] = $this->language->get('text_contact');
    	$this->data['text_sitemap'] = $this->language->get('text_sitemap');
		
		$this->load->model('catalog/information');
		$this->load->model('tool/seo_url');
		
		$this->data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
      		$this->data['informations'][] = array(
        		'title' => $result['title'],
	    		'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/information&information_id=' . $result['information_id'])
      		);
    	}

		$this->data['contact'] = HTTP_SERVER . 'index.php?route=information/contact';
    	$this->data['sitemap'] = HTTP_SERVER . 'index.php?route=information/sitemap';
		
		$this->id = 'information';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/information.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/information.tpl';
		} else {
			$this->template = 'default/template/module/information.tpl';
		}
		
		$this->render();
	}
}
?>