<?php 
class ControllerInformationContact extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->load->language('information/contact');

    	$this->document->title = $this->language->get('heading_title');  
	 
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$mail = new Mail();
			$mail->setTo($this->config->get('config_email'));
	  		$mail->setFrom($this->request->post['email']);
	  		$mail->setSender($this->request->post['name']);
	  		$mail->setSubject(sprintf($this->language->get('email_subject'), $this->request->post['name']));
	  		$mail->setText(strip_tags(html_entity_decode($this->request->post['enquiry'])));
      		$mail->send();

	  		$this->redirect($this->url->https('information/contact/success'));
    	}

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('information/contact'),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);	
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_telephone'] = $this->language->get('text_telephone');
    	$this->data['text_fax'] = $this->language->get('text_fax');

    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$this->data['entry_verification'] = $this->language->get('entry_verification');

    	$this->data['error_name'] = @$this->error['name'];
    	$this->data['error_email'] = @$this->error['email'];
    	$this->data['error_enquiry'] = @$this->error['enquiry'];
		$this->data['error_verification'] = @$this->error['verification'];

    	$this->data['button_continue'] = $this->language->get('button_continue');
    
		$this->data['action'] = $this->url->http('information/contact');
		$this->data['store'] = $this->config->get('config_store');
    	$this->data['address'] = nl2br($this->config->get('config_address'));
    	$this->data['telephone'] = $this->config->get('config_telephone');
    	$this->data['fax'] = $this->config->get('config_fax');
    	$this->data['name'] = @$this->request->post['name'];
    	$this->data['email'] = @$this->request->post['email'];
    	$this->data['enquiry'] = @$this->request->post['enquiry'];
		$this->data['verification'] = @$this->request->post['verification'];
	
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'information/contact.tpl';
		$this->layout   = 'module/layout';
		
 		$this->render();		
  	}

  	public function success() {
		$this->load->language('information/contact');

		$this->document->title = $this->language->get('heading_title'); 

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('information/contact'),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_message');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->http('common/home');

		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'common/success.tpl';
		$this->layout   = 'module/layout';
		
 		$this->render();
	}

	public function verification() {
		$this->load->library('verification');
		
		$verification = new Verification();
		
		$this->session->data['verification'] = $verification->getCode();
		
		$verification->showImage();
	}
	
  	private function validate() {
    	if ((strlen($this->request->post['name']) < 3) || (strlen($this->request->post['name']) > 32)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((strlen($this->request->post['enquiry']) < 10) || (strlen($this->request->post['enquiry']) > 1000)) {
      		$this->error['enquiry'] = $this->language->get('error_enquiry');
    	}

    	if (@$this->session->data['verification'] != $this->request->post['verification']) {
      		$this->error['verification'] = $this->language->get('error_verification');
    	}
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}  	  
  	}
}
?>
