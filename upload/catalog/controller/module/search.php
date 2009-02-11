<?php  
class ControllerModuleSearch extends Controller {
	protected function index() { 	
		$this->load->language('module/search');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_keywords'] = $this->language->get('text_keywords');
    	$this->data['text_advanced'] = $this->language->get('text_advanced');
		
		$this->data['entry_search'] = $this->language->get('entry_search');
    	
		$this->data['button_search'] = $this->language->get('button_search');
    	
		$this->data['keyword'] = @$this->request->get['keyword'];
		
		$this->data['advanced'] = @$this->url->http('product/search');
		
		$this->id       = 'search';
		$this->template = 'module/search.tpl';

    	$this->render();
  	}
}
?>