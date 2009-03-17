<?php 
class ControllerCustomerMail extends Controller {
	private $error = array();
	 
	public function index() {
		$this->load->language('customer/mail');
 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$email = array();
			
			switch ($this->request->post['to']) {
				case 'newsletter':
					$results = $this->model_customer_customer->getCustomersByNewsletter();
			
					foreach ($results as $result) {
						$email[] = $result['email'];
					}	 			
					break;
				case 'customer':
					$results = $this->model_customer_customer->getCustomers();
			
					foreach ($results as $result) {
						$email[] = $result['email'];
					}						
					break;
				default: 
					$result = $this->model_customer_customer->getCustomer($this->request->post['to']);

					$email = $result['email'];
					break;
			}
			
			if ($email) {
				$message  = '<html dir="ltr" lang="en">' . "\n";
				$message .= '<head>' . "\n";
				$message .= '<title>' . $this->request->post['subject'] . '</title>' . "\n";
				$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '</head>' . "\n";
				$message .= '<body>' . htmlspecialchars_decode($this->request->post['message']) . '</body>' . "\n";
				$message .= '</html>' . "\n";
				
				$mail = new Mail();	
				$mail->setTo($email);
				$mail->setFrom($this->config->get('config_email'));
	    		$mail->setSender($this->config->get('config_store'));
	    		$mail->setSubject($this->request->post['subject']);
				$mail->setHtml($message);
	    		$mail->send();
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
					 
			$this->redirect($this->url->https('customer/mail'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_customer'] = $this->language->get('text_customer');
		
		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_message'] = $this->language->get('entry_message');
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_to'] = @$this->error['to'];
		$this->data['error_subject'] = @$this->error['subject'];
		$this->data['error_message'] = @$this->error['message'];

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('customer/mail'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);
				
		$this->data['action'] = $this->url->https('customer/mail');
    	$this->data['cancel'] = $this->url->https('customer/mail');
		
		$this->data['customers'] = array();
		
		$results = $this->model_customer_customer->getCustomers();
		
		foreach ($results as $result) {
			$this->data['customers'][] = array(
				'customer_id' => $result['customer_id'],
				'name'        => $result['firstname'] . ' ' . $result['lastname'] . ' (' . $result['email'] . ')'
			);
		}	
		
		$this->data['to'] = @$this->request->post['to'];
		$this->data['subject'] = @$this->request->post['subject'];
		$this->data['message'] = @$this->request->post['message'];

		$this->id       = 'content';
		$this->template = 'customer/mail.tpl';
		$this->layout   = 'module/layout';
				
		$this->render();
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'customer/mail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
				
		if (!$this->request->post['subject']) {
			$this->error['subject'] = $this->language->get('error_subject');
		}

		if (!$this->request->post['message']) {
			$this->error['message'] = $this->language->get('error_message');
		}
						
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}	
}
?>