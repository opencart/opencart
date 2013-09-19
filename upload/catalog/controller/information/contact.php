<?php 
class ControllerInformationContact extends Controller {
	private $error = array(); 
	    
  	public function index() {
		$this->language->load('information/contact');

    	$this->document->setTitle($this->language->get('heading_title'));  
	 
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->config->get('config_email'));
	  		$mail->setFrom($this->request->post['email']);
	  		$mail->setSender($this->request->post['name']);
	  		$mail->setSubject(sprintf($this->language->get('email_subject'), $this->request->post['name']));
	  		$mail->setText(strip_tags($this->request->post['enquiry']));
      		$mail->send();

	  		$this->redirect($this->url->link('information/contact/success'));
    	}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
      	);	
			
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_location'] = $this->language->get('text_location');
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_telephone'] = $this->language->get('text_telephone');
    	$this->data['text_fax'] = $this->language->get('text_fax');
        $this->data['text_open'] = $this->language->get('text_open');
		$this->data['text_comment'] = $this->language->get('text_comment');

    	$this->data['entry_name'] = $this->language->get('entry_name');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_enquiry'] = $this->language->get('entry_enquiry');
		$this->data['entry_captcha'] = $this->language->get('entry_captcha');

		$this->data['button_map'] = $this->language->get('button_map');

		if (isset($this->error['name'])) {
    		$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}
		
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}		
		
		if (isset($this->error['enquiry'])) {
			$this->data['error_enquiry'] = $this->error['enquiry'];
		} else {
			$this->data['error_enquiry'] = '';
		}		
		
 		if (isset($this->error['captcha'])) {
			$this->data['error_captcha'] = $this->error['captcha'];
		} else {
			$this->data['error_captcha'] = '';
		}	

    	$this->data['button_continue'] = $this->language->get('button_continue');
    
		$this->data['action'] = $this->url->link('information/contact');
		
        $this->data['locations'] = array();
		
		$this->load->model('localisation/location');
		
		$this->load->model('tool/image');
		
		$this->data['location_id'] = $this->config->get('config_location_id');
        
		$location_info = $this->model_localisation_location->getLocation($this->config->get('config_location_id'));
		
		if ($location_info) {
			if ($location_info['address_format']) {
				$format = $location_info['address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}			
			
			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);
	
			$replace = array(
				'firstname' => '',
				'lastname'  => '',		
				'company'   => '',				
				'address_1' => $location_info['address_1'],
				'address_2' => $location_info['address_2'],
				'city'      => $location_info['city'],
				'postcode'  => $location_info['postcode'],
				'zone'      => $location_info['zone'],
				'zone_code' => $location_info['zone_code'],
				'country'   => $location_info['country']  
			);				
			
			if ($location_info['image']) {
				$image = $this->model_tool_image->resize($location_info['image'], $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
			} else {
				$image = false;
			}
							
			$this->data['locations'][] = array(
				'location_id' => $location_info['location_id'],
				'name'        => $location_info['name'],
				'address'     => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
				'geocode'     => $location_info['geocode'],
				'telephone'   => $location_info['telephone'],
				'fax'         => $location_info['fax'],
				'image'       => $image,  
				'open'        => $location_info['open'],   
				'comment'     => $location_info['comment']   
			);		
		}
		
		$results = $this->model_localisation_location->getLocations();
		
        foreach($results as $result) {
			if ($this->config->get('config_location_id') != $result['location_id'] && in_array($result['location_id'], (array)$this->config->get('config_location'))) {
				if ($result['address_format']) {
					$format = $result['address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}
							
				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);
		
				$replace = array(
					'firstname' => '',
					'lastname'  => '',		
					'company'   => '',				
					'address_1' => $result['address_1'],
					'address_2' => $result['address_2'],
					'city'      => $result['city'],
					'postcode'  => $result['postcode'],
					'zone'      => $result['zone'],
					'zone_code' => $result['zone_code'],
					'country'   => $result['country']  
				);				

				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_location_width'), $this->config->get('config_image_location_height'));
				} else {
					$image = false;
				}
								
				$this->data['locations'][] = array(
					'location_id' => $result['location_id'],
					'name'        => $result['name'],
					'address'     => str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format)))),
					'geocode'     => $result['geocode'],
					'telephone'   => $result['telephone'],
					'fax'         => $result['fax'],
					'image'       => $image,  
					'open'        => $result['open'],   
					'comment'     => $result['comment']   
				);
			}
        }
		
		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = $this->customer->getFirstName();
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = $this->customer->getEmail();
		}
		
		if (isset($this->request->post['enquiry'])) {
			$this->data['enquiry'] = $this->request->post['enquiry'];
		} else {
			$this->data['enquiry'] = '';
		}
		
		if (isset($this->request->post['captcha'])) {
			$this->data['captcha'] = $this->request->post['captcha'];
		} else {
			$this->data['captcha'] = '';
		}		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/contact.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/contact.tpl';
		} else {
			$this->template = 'default/template/information/contact.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
 		$this->response->setOutput($this->render());		
  	}

  	public function success() {
		$this->language->load('information/contact');

		$this->document->setTitle($this->language->get('heading_title')); 

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
      	);

      	$this->data['breadcrumbs'][] = array(
        	'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('information/contact')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_message'] = $this->language->get('text_success');

    	$this->data['button_continue'] = $this->language->get('button_continue');

    	$this->data['continue'] = $this->url->link('common/home');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/success.tpl';
		} else {
			$this->template = 'default/template/common/success.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'
		);
				
 		$this->response->setOutput($this->render()); 
	}
	
  	protected function validate() {
    	if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
      		$this->error['name'] = $this->language->get('error_name');
    	}

    	if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((utf8_strlen($this->request->post['enquiry']) < 10) || (utf8_strlen($this->request->post['enquiry']) > 3000)) {
      		$this->error['enquiry'] = $this->language->get('error_enquiry');
    	}

    	if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
      		$this->error['captcha'] = $this->language->get('error_captcha');
    	}
		
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  	  
  	}

	public function captcha() {
		$this->session->data['captcha'] = substr(sha1(mt_rand()), 17, 6);
		
		$image = imagecreatetruecolor(150, 35);

		$width = imagesx($image); 
		$height = imagesy($image);

		$black = imagecolorallocate($image, 0, 0, 0); 
		$white = imagecolorallocate($image, 255, 255, 255); 
		$red = imagecolorallocatealpha($image, 255, 0, 0, 75); 
		$green = imagecolorallocatealpha($image, 0, 255, 0, 75); 
		$blue = imagecolorallocatealpha($image, 0, 0, 255, 75); 

		imagefilledrectangle($image, 0, 0, $width, $height, $white); 

		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $red); 
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $green); 
		imagefilledellipse($image, ceil(rand(5, 145)), ceil(rand(0, 35)), 30, 30, $blue); 

		imagefilledrectangle($image, 0, 0, $width, 0, $black); 
		imagefilledrectangle($image, $width - 1, 0, $width - 1, $height - 1, $black); 
		imagefilledrectangle($image, 0, 0, 0, $height - 1, $black); 
		imagefilledrectangle($image, 0, $height - 1, $width, $height - 1, $black); 

		imagestring($image, 10, intval(($width - (strlen($this->session->data['captcha']) * 9)) / 2),  intval(($height - 15) / 2), $this->session->data['captcha'], $black);

		header('Content-type: image/jpeg');

		imagejpeg($image);

		imagedestroy($image);
	}
}
?>