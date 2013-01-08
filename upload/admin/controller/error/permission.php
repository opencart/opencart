<?php    
class ControllerErrorPermission extends Controller {    
	public function index() { 
    	$this->language->load('error/permission');
  
    	$this->document->setTitle($this->language->get('heading_title'));
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_permission'] = $this->language->get('text_permission');
													
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('error/permission', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->template = 'error/permission.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
  	}
}
?>