<?php 
class ControllerSaleContact extends Controller {
	private $error = array();
	 
	public function index() {
		$this->load->language('sale/contact');
 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/customer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$emails = array();
			
			if (isset($this->request->post['group'])) {
				switch ($this->request->post['group']) {
					case 'newsletter':
						$results = $this->model_sale_customer->getCustomersByNewsletter();
					
						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}
						break;
					case 'customer':
						$results = $this->model_sale_customer->getCustomers();
				
						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}						
						break;
				}
			}
			
			if (isset($this->request->post['to']) && $this->request->post['to']) {					
				foreach ($this->request->post['to'] as $customer_id) {
					$customer_info = $this->model_sale_customer->getCustomer($customer_id);
					
					if ($customer_info) {
						$emails[] = $customer_info['email'];
					}
				}
			}	
			
			if ($emails) {
				$message  = '<html dir="ltr" lang="en">' . "\n";
				$message .= '<head>' . "\n";
				$message .= '<title>' . $this->request->post['subject'] . '</title>' . "\n";
				$message .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '</head>' . "\n";
				$message .= '<body>' . html_entity_decode($this->request->post['message']) . '</body>' . "\n";
				$message .= '</html>' . "\n";

				$attachments = array();

				$pattern = '#(src="([^"]*)")#mis';
				
				if (preg_match_all($pattern, $message, $matches)) {
					foreach ($matches[2] as $key => $value) { 
						$attachments[] = str_replace('/image/', DIR_IMAGE, $value);
						$message = str_replace($value, 'cid:' . basename($value), $message);
					}
				}	
				
				foreach ($emails as $email) {
					$mail = new Mail($this->config->get('config_mail_protocol'), $this->config->get('config_smtp_host'), $this->config->get('config_smtp_username'), html_entity_decode($this->config->get('config_smtp_password')), $this->config->get('config_smtp_port'), $this->config->get('config_smtp_timeout'));	
					$mail->setTo($email);
					$mail->setFrom($this->config->get('config_email'));
	    			$mail->setSender($this->config->get('config_store'));
	    			$mail->setSubject($this->request->post['subject']);					
					$mail->addAttachment($attachments);
					$mail->setHtml($message);
	    			$mail->send();
				}
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_customer'] = $this->language->get('text_customer');
		$this->data['text_search'] = $this->language->get('text_search');
		
		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_subject'] = $this->language->get('entry_subject');
		$this->data['entry_message'] = $this->language->get('entry_message');
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
 		if (isset($this->error['subject'])) {
			$this->data['error_subject'] = $this->error['subject'];
		} else {
			$this->data['error_subject'] = '';
		}
	 	
		if (isset($this->error['message'])) {
			$this->data['error_message'] = $this->error['message'];
		} else {
			$this->data['error_message'] = '';
		}	

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('sale/contact'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
				
		$this->data['action'] = $this->url->https('sale/contact');
    	$this->data['cancel'] = $this->url->https('sale/contact');
		
		$this->data['customers'] = array();
		
		if (isset($this->request->post['to']) && $this->request->post['to']) {					
			foreach ($this->request->post['to'] as $customer_id) {
				$customer_info = $this->model_sale_customer->getCustomer($customer_id);
					
				if ($customer_info) {
					$this->data['customers'][] = array(
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname'] . ' (' . $customer_info['email'] . ')'
					);
				}
			}
		}

		if (isset($this->request->post['group'])) {
			$this->data['group'] = $this->request->post['group'];
		} else {
			$this->data['group'] = '';
		}
		
		if (isset($this->request->post['subject'])) {
			$this->data['subject'] = $this->request->post['subject'];
		} else {
			$this->data['subject'] = '';
		}
		
		if (isset($this->request->post['message'])) {
			$this->data['message'] = $this->request->post['message'];
		} else {
			$this->data['message'] = '';
		}

		$this->template = 'sale/contact.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function customer() {
		$this->load->model('sale/customer');
			
		$customer_data = array();
		
		if (isset($this->request->get['keyword']) && $this->request->get['keyword']) {
			$results = $this->model_sale_customer->getCustomersByKeyword($this->request->get['keyword']);
		
			foreach ($results as $result) {
				$customer_data[] = array(
					'customer_id' => $result['customer_id'],
					'name'        => $result['firstname'] . ' ' . $result['lastname'] . ' (' . $result['email'] . ')'
				);
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($customer_data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'sale/contact')) {
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