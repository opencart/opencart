<?php   
class ControllerErrorNotFound extends Controller {
	public function index() {		
		$this->load->language('error/not_found');
		
		$this->document->title = $this->language->get('heading_title');
		
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);		
			 
       	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http($this->request->get['route']),
        	'text'      => $this->language->get('text_error'),
        	'separator' => $this->language->get('text_separator')
      	);	   	
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_error'] = $this->language->get('text_error');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		$this->data['continue'] = $this->url->http('common/home');
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'error/not_found.tpl';
		$this->layout   = 'module/layout';
		
		$this->render();		
  	}
}
?>