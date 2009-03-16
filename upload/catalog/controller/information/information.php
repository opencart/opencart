<?php 
class ControllerInformationInformation extends Controller {
	public function index() {  
    	$this->load->language('information/information');
		
		$this->load->model('catalog/information');
		
		$this->document->breadcrumbs = array();
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);
		
		$information_info = $this->model_catalog_information->getInformation(@$this->request->get['information_id']);
   		
		if ($information_info) {
	  		$this->document->title = $information_info['title']; 

      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('information/information&information_id=' . $this->request->get['information_id']),
        		'text'      => $information_info['title'],
        		'separator' => $this->language->get('text_separator')
      		);		
						
      		$this->data['heading_title'] = $information_info['title'];
      		
      		$this->data['button_continue'] = $this->language->get('button_continue');
			
			$this->data['description'] = html_entity_decode($information_info['description']);
      		
			$this->data['continue'] = $this->url->http('common/home');

			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'information/information.tpl';
			$this->layout   = 'module/layout';
				  
	  		$this->render();
    	} else {
      		$this->document->breadcrumbs[] = array(
        		'href'      => $this->url->http('information/information&information_id=' . $this->request->get['information_id']),
        		'text'      => $this->language->get('text_error'),
        		'separator' => $this->language->get('text_separator')
      		);
				
	  		$this->document->title = $this->language->get('text_error');
			
      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->http('common/home');

			$this->id       = 'content';
			$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
			$this->layout   = 'module/layout';
			  
	  		$this->render();
    	}
  	}
}
?>