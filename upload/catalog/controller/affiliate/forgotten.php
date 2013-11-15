<?php
class ControllerAffiliateForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->affiliate->isLogged()) {
			$this->redirect($this->url->link('affiliate/account', '', 'SSL'));
		}

		$this->language->load('affiliate/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('affiliate/affiliate');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->language->load('mail/forgotten');
			
			$password = substr(md5(mt_rand()), 0, 10);
			
			$this->model_affiliate_affiliate->editPassword($this->request->post['email'], $password);
			
			$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			
			$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
			$message .= $this->language->get('text_password') . "\n\n";
			$message .= $password;

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->request->post['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($this->config->get('config_name'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('affiliate/login', '', 'SSL'));
		}

      	$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
      	); 

      	$data['breadcrumbs'][] = array(
        	'text' => $this->language->get('text_account'),
			'href' => $this->url->link('affiliate/account', '', 'SSL')
      	);
		
      	$data['breadcrumbs'][] = array(
        	'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('affiliate/forgotten', '', 'SSL')
      	);
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_email'] = $this->language->get('text_your_email');
		$data['text_email'] = $this->language->get('text_email');

		$data['entry_email'] = $this->language->get('entry_email');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['action'] = $this->url->link('affiliate/forgotten', '', 'SSL');
 
		$data['back'] = $this->url->link('affiliate/login', '', 'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
						
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/affiliate/forgotten.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/affiliate/forgotten.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/affiliate/forgotten.tpl', $data));
		}		
	}

	protected function validate() {
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_affiliate_affiliate->getTotalAffiliatesByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>