<?php    
class ControllerErrorNotFound extends Controller {    
	public function index() { 
    	$this->load->language('error/not_found');
 
    	$this->document->title = $this->language->get('heading_title');

    	$this->data['heading_title'] = $this->language->get('heading_title');

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('error/not_found'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->id       = 'content'; 
		$this->template = 'error/not_found.tpl';
		$this->layout   = 'module/layout';
		    
		$this->render();	
  	}
}
?>