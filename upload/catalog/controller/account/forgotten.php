<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->load->language('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			//Εδώ ξέρουμε πως ο χρήστης έχει καταχωρήσει ένα e-mail το οποίο υπάρχει ή έναν αριθμό κινητού τηλεφώνου που ήδη υπάρχει.
			
/* N */		if( isset($this->request->post['email']) && (utf8_strlen($this->request->post['email'])>0) ) {
				
				//Αν ο χρήστης έχει καταχωρήσει ένα e-mail τρέχουμε το παρακάτω κομμάτι κώδικα:
				$this->load->language('mail/forgotten');
				$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
	
				//$this->model_account_customer->editPassword($this->request->post['email'], $password);
/* N */			$this->model_account_customer->editPassword($this->request->post['email'], '', $password);
	
				$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
	
				$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
				$message .= $this->language->get('text_password') . "\n\n";
				$message .= $password;
	
				$mail = new Mail($this->config->get('config_mail'));		
				$mail->setTo($this->request->post['email']);
				$mail->setFrom($this->config->get('config_email'));
				$mail->setSender($this->config->get('config_name'));
				$mail->setSubject($subject);
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
	
				$this->session->data['success'] = $this->language->get('text_success');
			
				// Add to activity log
				$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);
		 		
/* N */		}else{
/* N */	
/* N */			//Αν ο χρήστης έχει καταχωρήσει έναν αριθμό κινητού τηλεφώνου τρέχουμε το παρακάτω κομμάτι κώδικα:
/* N */			$this->load->language('mobile/forgotten');
/* N */			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);
/* N */				
/* N */			$this->model_account_customer->editPassword('', $this->request->post['mobile'], $password);
/* N */
/* N */				
/* N */			$user = "amerlou";
/* N */			$password = "cNBHYcXEeVMdXQ";
/* N */			$api_id = "3482506";
/* N */			$baseurl ="http://api.clickatell.com";
/* N */				
/* N */			$text = "";
/* N */			$text .= $this->language->get('text_greeting');
/* N */			$text .= $this->language->get('text_password');
/* N */			$text .= $password;
/* N */				
/* N */			$text = urlencode($text);
/* N */				
/* N */			$to = $this->request->post['mobile'];
/* N */				
/* N */			//API CALL STRING
/* N */			$url = "$baseurl/http/sendmsg?user=".$user."&password=".$password."&api_id=".$api_id."&to=".$to."&text=".$text;
/* N */				
/* N */			//DO API CALL
/* N */			$resp = file($url);
/* N */			$resp_first_line = $resp[0];
/* N */			$line_divided = explode(":",$resp_first_line);
/* N */			
/* N */			if($line_divided[0]=='ERR'){
/* N */				//Υπήρξε πρόβλημα
/* N */				$this->error['warning'] = $this->language->get('error_api');
/* N */			}
/* N */			
/* N */			$this->session->data['success'] = $this->language->get('text_success');
/* N */				 
/* N */			// Add to activity log
/* N */			$customer_info = $this->model_account_customer->getCustomerByMobile($this->request->post['mobile']);
/* N */				
/* N */		}
			
			//Από τη στιγμή που έχεις όλες τις πληροφορίες του χρήστη, προχώρησε στην καταχώρηση του log
			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('account/forgotten', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		//$data['text_your_email'] = $this->language->get('text_your_email');
/* N */		$data['text_your_contact_data'] = $this->language->get('text_your_contact_data');
		//$data['text_email'] = $this->language->get('text_email');
/* N */		$data['text_contact_data'] = $this->language->get('text_contact_data');

		$data['entry_email'] = $this->language->get('entry_email');
/* N */		$data['entry_mobile'] = $this->language->get('entry_mobile');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('account/forgotten', '', 'SSL');

		$data['back'] = $this->url->link('account/login', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/forgotten.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/forgotten.tpl', $data));
		}		
	}

	protected function validate() {
		/*
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}
		*/
/* N */		//Ο παρακάτω κώδικας αντικαθιστά τον κώδικα που έχει γίνει comment-out παραπάνω		
/* N */		if (
			( (!isset($this->request->post['email'])) && (!isset($this->request->post['mobile'])) ) ||
			( (isset($this->request->post['email'])) && (isset($this->request->post['mobile'])) && (utf8_strlen($this->request->post['mobile'])<=0) && (utf8_strlen($this->request->post['mobile'])<=0) )
			) {
/* N */			$this->error['warning'] = $this->language->get('error_nothing_given');
/* N */		} else if( isset($this->request->post['email']) && (utf8_strlen($this->request->post['email'])>0) ) {
/* N */			//Κάνε check στο e-mail
/* N */			//Αν είναι και τα 2 συμπληρωμένα, θα τρέξει αυτό το κομμάτι κώδικα για τα e-mail και μετά θα σταματήσει εδώ.
/* N */			//Εδώ κάνουμε έλεγχο αν το e-mail είναι έγκυρο (υπάρχει σε έναν χρήστη)
/* N */			if (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
/* N */				$this->error['warning'] = $this->language->get('error_email');
/* N */			}		
/* N */		} else if( isset($this->request->post['mobile']) && (utf8_strlen($this->request->post['mobile'])>0) ) {
/* N */			//Κάνε check στο mobile
/* N */			//Εδώ κάνουμε έλεγχο αν το νούμερο είναι έγκυρο (υπάρχει σε έναν χρήστη)
/* N */			if (!$this->model_account_customer->getTotalCustomersByMobile($this->request->post['mobile'])) {
/* N */				$this->error['warning'] = $this->language->get('error_mobile');
/* N */			}
/* N */		}
		
		return !$this->error;
	}
}