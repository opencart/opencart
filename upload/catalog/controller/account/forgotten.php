<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->redirect($this->url->https('account/forgotten'));
		}

		$this->language->load('account/forgotten');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('account/customer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$password = substr(md5(rand()), 0, 7);
			
			$subject = sprintf($this->language->get('mail_subject'), $this->config->get('config_store'));
			
			$message  = sprintf($this->language->get('mail_greeting'), $this->config->get('config_store')) . "\n\n";
			$message .= $this->language->get('mail_password') . "\n\n";
			$message .= $password;

			$mail = new Mail();
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_store'));
			$mail->setSubject($subject);
			$mail->setText($message);
			$mail->send();
			
			$this->model_account_customer->editPassword($this->request->post['email'], $password);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->https('account/login'));
		}

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/account'),
        	'text'      => $this->language->get('text_account'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('account/forgotten'),
        	'text'      => $this->language->get('text_forgotten'),
        	'separator' => $this->language->get('text_separator')
      	);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_your_email'] = $this->language->get('text_your_email');
		$this->data['text_email'] = $this->language->get('text_email');

		$this->data['entry_email'] = $this->language->get('entry_email');

		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->data['error'] = @$this->error['message'];

		$this->data['action'] = $this->url->https('account/forgotten');
 
		$this->data['back'] = $this->url->https('account/account');
		
		$this->id       = 'content';
		$this->template = $this->config->get('config_template') . 'account/forgotten.tpl';
		$this->layout   = 'common/layout';
		
		$this->render();		
	}

	private function validate() {
		if (!isset($this->request->post['email'])) {
			$this->error['message'] = $this->language->get('error_email');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['message'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>