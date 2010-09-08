<?php 
class ControllerCheckoutGuestStep1 extends Controller {
	private $error = array();
	      
  	public function index() {
    	if (!$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/cart');
    	}
		
		if ($this->customer->isLogged()) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/shipping');
    	} 

		if (!$this->config->get('config_guest_checkout') || $this->cart->hasDownload()) {
			$this->session->data['redirect'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';

	  		$this->redirect(HTTPS_SERVER . 'index.php?route=account/login');
    	} 
		
		$this->language->load('checkout/guest_step_1');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->session->data['guest']['firstname'] = $this->request->post['firstname'];
			$this->session->data['guest']['lastname'] = $this->request->post['lastname'];
			$this->session->data['guest']['email'] = $this->request->post['email'];
			$this->session->data['guest']['telephone'] = $this->request->post['telephone'];
			$this->session->data['guest']['fax'] = $this->request->post['fax'];
			$this->session->data['guest']['company'] = $this->request->post['company'];
			$this->session->data['guest']['address_1'] = $this->request->post['address_1'];
			$this->session->data['guest']['address_2'] = $this->request->post['address_2'];
			$this->session->data['guest']['postcode'] = $this->request->post['postcode'];
			$this->session->data['guest']['city'] = $this->request->post['city'];
			$this->session->data['guest']['country_id'] = $this->request->post['country_id'];
			$this->session->data['guest']['zone_id'] = $this->request->post['zone_id'];
						
			if ($this->cart->hasShipping()) {
				$this->tax->setZone($this->request->post['country_id'], $this->request->post['zone_id']);
			}
			
			$this->load->model('localisation/country');
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
			
			if ($country_info) {
				$this->session->data['guest']['country'] = $country_info['name'];	
				$this->session->data['guest']['iso_code_2'] = $country_info['iso_code_2'];
				$this->session->data['guest']['iso_code_3'] = $country_info['iso_code_3'];
				$this->session->data['guest']['address_format'] = $country_info['address_format'];
			} else {
				$this->session->data['guest']['country'] = '';	
				$this->session->data['guest']['iso_code_2'] = '';
				$this->session->data['guest']['iso_code_3'] = '';
				$this->session->data['guest']['address_format'] = '';
			}
						
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
			
			if ($zone_info) {
				$this->session->data['guest']['zone'] = $zone_info['name'];
				$this->session->data['guest']['zone_code'] = $zone_info['code'];
			} else {
				$this->session->data['guest']['zone'] = '';
				$this->session->data['guest']['zone_code'] = '';
			}
			
			if (isset($this->request->post['shipping_indicator'])) {
				$this->session->data['guest']['shipping']['firstname'] = $this->request->post['shipping_firstname'];
				$this->session->data['guest']['shipping']['lastname'] = $this->request->post['shipping_lastname'];
				$this->session->data['guest']['shipping']['company'] = $this->request->post['shipping_company'];
				$this->session->data['guest']['shipping']['address_1'] = $this->request->post['shipping_address_1'];
				$this->session->data['guest']['shipping']['address_2'] = $this->request->post['shipping_address_2'];
				$this->session->data['guest']['shipping']['postcode'] = $this->request->post['shipping_postcode'];
				$this->session->data['guest']['shipping']['city'] = $this->request->post['shipping_city'];
				$this->session->data['guest']['shipping']['country_id'] = $this->request->post['shipping_country_id'];
				$this->session->data['guest']['shipping']['zone_id'] = $this->request->post['shipping_zone_id'];
			
				if ($this->cart->hasShipping()) {
					$this->tax->setZone($this->request->post['shipping_country_id'], $this->request->post['shipping_zone_id']);
				}
			
				$shipping_country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
				
				if ($shipping_country_info) {
					$this->session->data['guest']['shipping']['country'] = $shipping_country_info['name'];	
					$this->session->data['guest']['shipping']['iso_code_2'] = $shipping_country_info['iso_code_2'];
					$this->session->data['guest']['shipping']['iso_code_3'] = $shipping_country_info['iso_code_3'];
					$this->session->data['guest']['shipping']['address_format'] = $shipping_country_info['address_format'];
				} else {
					$this->session->data['guest']['shipping']['country'] = '';	
					$this->session->data['guest']['shipping']['iso_code_2'] = '';
					$this->session->data['guest']['shipping']['iso_code_3'] = '';
					$this->session->data['guest']['shipping']['address_format'] = '';
				}
				
				$shipping_zone_info = $this->model_localisation_zone->getZone($this->request->post['shipping_zone_id']);
			
				if ($zone_info) {
					$this->session->data['guest']['shipping']['zone'] = $shipping_zone_info['name'];
					$this->session->data['guest']['shipping']['zone_code'] = $shipping_zone_info['code'];
				} else {
					$this->session->data['guest']['shipping']['zone'] = '';
					$this->session->data['guest']['shipping']['zone_code'] = '';
				}
				
			} else {
				unset($this->session->data['guest']['shipping']);
			}
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
    	} 

		$this->document->title = $this->language->get('heading_title');
      	
		$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/cart',
        	'text'      => $this->language->get('text_cart'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=checkout/guest_step_1',
        	'text'      => $this->language->get('text_guest_step_1'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
    	$this->data['text_your_details'] = $this->language->get('text_your_details');
		$this->data['text_your_address'] = $this->language->get('text_your_address');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$this->data['text_indicator'] = $this->language->get('text_indicator');
		$this->data['text_select'] = $this->language->get('text_select');
		
    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
		$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_company'] = $this->language->get('entry_company');
    	$this->data['entry_address_1'] = $this->language->get('entry_address_1');
    	$this->data['entry_address_2'] = $this->language->get('entry_address_2');
    	$this->data['entry_postcode'] = $this->language->get('entry_postcode');
    	$this->data['entry_city'] = $this->language->get('entry_city');
    	$this->data['entry_country'] = $this->language->get('entry_country');
    	$this->data['entry_zone'] = $this->language->get('entry_zone');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');
		
		if (isset($this->error['firstname'])) {
			$this->data['error_firstname'] = $this->error['firstname'];
		} else {
			$this->data['error_firstname'] = '';
		}	
		
		if (isset($this->error['lastname'])) {
			$this->data['error_lastname'] = $this->error['lastname'];
		} else {
			$this->data['error_lastname'] = '';
		}
	
		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$this->data['error_telephone'] = $this->error['telephone'];
		} else {
			$this->data['error_telephone'] = '';
		}

		if (isset($this->error['address_1'])) {
			$this->data['error_address_1'] = $this->error['address_1'];
		} else {
			$this->data['error_address_1'] = '';
		}
		
		if (isset($this->error['city'])) {
			$this->data['error_city'] = $this->error['city'];
		} else {
			$this->data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$this->data['error_postcode'] = $this->error['postcode'];
		} else {
			$this->data['error_postcode'] = '';
		}
		
		if (isset($this->error['country'])) {
			$this->data['error_country'] = $this->error['country'];
		} else {
			$this->data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$this->data['error_zone'] = $this->error['zone'];
		} else {
			$this->data['error_zone'] = '';
		}
		
		if (isset($this->error['shipping_firstname'])) {
			$this->data['error_shipping_firstname'] = $this->error['shipping_firstname'];
		} else {
			$this->data['error_shipping_firstname'] = '';
		}	
		
		if (isset($this->error['shipping_lastname'])) {
			$this->data['error_shipping_lastname'] = $this->error['shipping_lastname'];
		} else {
			$this->data['error_shipping_lastname'] = '';
		}
		
		if (isset($this->error['shipping_address_1'])) {
			$this->data['error_shipping_address_1'] = $this->error['shipping_address_1'];
		} else {
			$this->data['error_shipping_address_1'] = '';
		}
		
		if (isset($this->error['shipping_city'])) {
			$this->data['error_shipping_city'] = $this->error['shipping_city'];
		} else {
			$this->data['error_shipping_city'] = '';
		}
		
		if (isset($this->error['shipping_postcode'])) {
			$this->data['error_shipping_postcode'] = $this->error['shipping_postcode'];
		} else {
			$this->data['error_shipping_postcode'] = '';
		}
		
		if (isset($this->error['shipping_country'])) {
			$this->data['error_shipping_country'] = $this->error['shipping_country'];
		} else {
			$this->data['error_shipping_country'] = '';
		}

		if (isset($this->error['shipping_zone'])) {
			$this->data['error_shipping_zone'] = $this->error['shipping_zone'];
		} else {
			$this->data['error_shipping_zone'] = '';
		}
		
    	$this->data['action'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_1';

		if (isset($this->request->post['firstname'])) {
    		$this->data['firstname'] = $this->request->post['firstname'];
		} elseif (isset($this->session->data['guest']['firstname'])) {
			$this->data['firstname'] = $this->session->data['guest']['firstname'];
		} else {
			$this->data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
    		$this->data['lastname'] = $this->request->post['lastname'];
		} elseif (isset($this->session->data['guest']['lastname'])) {
			$this->data['lastname'] = $this->session->data['guest']['lastname'];
		} else {
			$this->data['lastname'] = '';
		}
		
		if (isset($this->request->post['email'])) {
    		$this->data['email'] = $this->request->post['email'];
		} elseif (isset($this->session->data['guest']['email'])) {
			$this->data['email'] = $this->session->data['guest']['email'];
		} else {
			$this->data['email'] = '';
		}
		
		if (isset($this->request->post['telephone'])) {
    		$this->data['telephone'] = $this->request->post['telephone'];
		} elseif (isset($this->session->data['guest']['telephone'])) {
			$this->data['telephone'] = $this->session->data['guest']['telephone'];		
		} else {
			$this->data['telephone'] = '';
		}

		if (isset($this->request->post['fax'])) {
    		$this->data['fax'] = $this->request->post['fax'];
		} elseif (isset($this->session->data['guest']['fax'])) {
			$this->data['fax'] = $this->session->data['guest']['fax'];				
		} else {
			$this->data['fax'] = '';
		}

		if (isset($this->request->post['company'])) {
    		$this->data['company'] = $this->request->post['company'];
		} elseif (isset($this->session->data['guest']['company'])) {
			$this->data['company'] = $this->session->data['guest']['company'];			
		} else {
			$this->data['company'] = '';
		}
		
		if (isset($this->request->post['address_1'])) {
    		$this->data['address_1'] = $this->request->post['address_1'];
		} elseif (isset($this->session->data['guest']['address_1'])) {
			$this->data['address_1'] = $this->session->data['guest']['address_1'];			
		} else {
			$this->data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
    		$this->data['address_2'] = $this->request->post['address_2'];
		} elseif (isset($this->session->data['guest']['address_2'])) {
			$this->data['address_2'] = $this->session->data['guest']['address_2'];			
		} else {
			$this->data['address_2'] = '';
		}

		if (isset($this->request->post['postcode'])) {
    		$this->data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($this->session->data['guest']['postcode'])) {
			$this->data['postcode'] = $this->session->data['guest']['postcode'];					
		} else {
			$this->data['postcode'] = '';
		}
		
		if (isset($this->request->post['city'])) {
    		$this->data['city'] = $this->request->post['city'];
		} elseif (isset($this->session->data['guest']['city'])) {
			$this->data['city'] = $this->session->data['guest']['city'];			
		} else {
			$this->data['city'] = '';
		}

    	if (isset($this->request->post['country_id'])) {
      		$this->data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($this->session->data['guest']['country_id'])) {
			$this->data['country_id'] = $this->session->data['guest']['country_id'];			  	
		} else {
      		$this->data['country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['zone_id'])) {
      		$this->data['zone_id'] = $this->request->post['zone_id'];
		} elseif (isset($this->session->data['guest']['zone_id'])) {
			$this->data['zone_id'] = $this->session->data['guest']['zone_id'];			
    	} else {
      		$this->data['zone_id'] = 'FALSE';
    	}
    	
    	if (isset($this->request->post['shipping_firstname'])) {
    		$this->data['shipping_firstname'] = $this->request->post['shipping_firstname'];
		} elseif (isset($this->session->data['guest']['shipping']['firstname'])) {
			$this->data['shipping_firstname'] = $this->session->data['guest']['shipping']['firstname'];
		} else {
			$this->data['shipping_firstname'] = '';
		}

		if (isset($this->request->post['shipping_lastname'])) {
    		$this->data['shipping_lastname'] = $this->request->post['shipping_lastname'];
		} elseif (isset($this->session->data['guest']['shipping']['lastname'])) {
			$this->data['shipping_lastname'] = $this->session->data['guest']['shipping']['lastname'];
		} else {
			$this->data['shipping_lastname'] = '';
		}
		
		if (isset($this->request->post['shipping_company'])) {
    		$this->data['shipping_company'] = $this->request->post['shipping_company'];
		} elseif (isset($this->session->data['guest']['shipping']['company'])) {
			$this->data['shipping_company'] = $this->session->data['guest']['shipping']['company'];			
		} else {
			$this->data['shipping_company'] = '';
		}
		
		if (isset($this->request->post['shipping_address_1'])) {
    		$this->data['shipping_address_1'] = $this->request->post['shipping_address_1'];
		} elseif (isset($this->session->data['guest']['shipping']['address_1'])) {
			$this->data['shipping_address_1'] = $this->session->data['guest']['shipping']['address_1'];			
		} else {
			$this->data['shipping_address_1'] = '';
		}

		if (isset($this->request->post['shipping_address_2'])) {
    		$this->data['shipping_address_2'] = $this->request->post['shipping_address_2'];
		} elseif (isset($this->session->data['guest']['shipping']['address_2'])) {
			$this->data['shipping_address_2'] = $this->session->data['guest']['shipping']['address_2'];			
		} else {
			$this->data['shipping_address_2'] = '';
		}

		if (isset($this->request->post['shipping_postcode'])) {
    		$this->data['shipping_postcode'] = $this->request->post['shipping_postcode'];
		} elseif (isset($this->session->data['guest']['shipping']['postcode'])) {
			$this->data['shipping_postcode'] = $this->session->data['guest']['shipping']['postcode'];					
		} else {
			$this->data['shipping_postcode'] = '';
		}
		
		if (isset($this->request->post['shipping_city'])) {
    		$this->data['shipping_city'] = $this->request->post['shipping_city'];
		} elseif (isset($this->session->data['guest']['shipping']['city'])) {
			$this->data['shipping_city'] = $this->session->data['guest']['shipping']['city'];			
		} else {
			$this->data['shipping_city'] = '';
		}

    	if (isset($this->request->post['shipping_country_id'])) {
      		$this->data['shipping_country_id'] = $this->request->post['shipping_country_id'];
		} elseif (isset($this->session->data['guest']['shipping']['country_id'])) {
			$this->data['shipping_country_id'] = $this->session->data['guest']['shipping']['country_id'];			  	
		} else {
      		$this->data['shipping_country_id'] = $this->config->get('config_country_id');
    	}

    	if (isset($this->request->post['shipping_zone_id'])) {
      		$this->data['shipping_zone_id'] = $this->request->post['shipping_zone_id'];
		} elseif (isset($this->session->data['guest']['shipping']['zone_id'])) {
			$this->data['shipping_zone_id'] = $this->session->data['guest']['shipping']['zone_id'];			
    	} else {
      		$this->data['shipping_zone_id'] = 'FALSE';
    	}
    	
    	if (isset($this->request->post['shipping_indicator'])) {
      		$this->data['shipping_indicator'] = TRUE;
      	} elseif (isset($this->session->data['guest']['shipping'])) {
			$this->data['shipping_indicator'] = TRUE;
    	} else {
      		$this->data['shipping_indicator'] = FALSE;
    	}

		$this->data['shipping'] = $this->cart->hasShipping();
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();
		
		$this->data['back'] = HTTP_SERVER . 'index.php?route=checkout/cart';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest_step_1.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/guest_step_1.tpl';
		} else {
			$this->template = 'default/template/checkout/guest_step_1.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/footer',
			'common/header'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
	
  	private function validate() {
    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

		$pattern = '/^[A-Z0-9._%-+]+@[A-Z0-9][A-Z0-9.-]{0,61}[A-Z0-9]\.[A-Z]{2,6}$/i';
		
    	if (!preg_match($pattern, $this->request->post['email'])) {
      		$this->error['email'] = $this->language->get('error_email');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
		
    	if ((strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128)) {
      		$this->error['address_1'] = $this->language->get('error_address_1');
    	}

    	if ((strlen(utf8_decode($this->request->post['city'])) < 3) || (strlen(utf8_decode($this->request->post['city'])) > 128)) {
      		$this->error['city'] = $this->language->get('error_city');
    	}
		
		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		
		if ($country_info && $country_info['postcode_required']) {
			if ((strlen(utf8_decode($this->request->post['postcode'])) < 2) || (strlen(utf8_decode($this->request->post['postcode'])) > 10)) {
				$this->error['postcode'] = $this->language->get('error_postcode');
			}
		}

    	if ($this->request->post['country_id'] == 'FALSE') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if ($this->request->post['zone_id'] == 'FALSE') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}
		
		if (isset($this->request->post['shipping_indicator'])) {
			
			if ((strlen(utf8_decode($this->request->post['shipping_firstname'])) < 3) || (strlen(utf8_decode($this->request->post['shipping_firstname'])) > 32)) {
      		$this->error['shipping_firstname'] = $this->language->get('error_firstname');
    		}

    		if ((strlen(utf8_decode($this->request->post['shipping_lastname'])) < 3) || (strlen(utf8_decode($this->request->post['shipping_lastname'])) > 32)) {
      			$this->error['shipping_lastname'] = $this->language->get('error_lastname');
    		}
			
			if ((strlen(utf8_decode($this->request->post['shipping_address_1'])) < 3) || (strlen(utf8_decode($this->request->post['shipping_address_1'])) > 128)) {
      		$this->error['shipping_address_1'] = $this->language->get('error_address_1');
    		}

    		if ((strlen(utf8_decode($this->request->post['shipping_city'])) < 3) || (strlen(utf8_decode($this->request->post['shipping_city'])) > 128)) {
      			$this->error['shipping_city'] = $this->language->get('error_city');
    		}
			
			$country_info = $this->model_localisation_country->getCountry($this->request->post['shipping_country_id']);
		
			if ($country_info && $country_info['postcode_required']) {
				if ((strlen(utf8_decode($this->request->post['shipping_postcode'])) < 2) || (strlen(utf8_decode($this->request->post['shipping_postcode'])) > 10)) {
					$this->error['shipping_postcode'] = $this->language->get('error_postcode');
				}
			}
			
    		if ($this->request->post['shipping_country_id'] == 'FALSE') {
      			$this->error['shipping_country'] = $this->language->get('error_country');
    		}
			
    		if ($this->request->post['shipping_zone_id'] == 'FALSE') {
      			$this->error['shipping_zone'] = $this->language->get('error_zone');
    		}
    		
		}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
	
  	public function zone() {
		$output = '<option value="FALSE">' . $this->language->get('text_select') . '</option>';
		
		$this->load->model('localisation/zone');

    	$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
        
      	foreach ($results as $result) {
        	$output .= '<option value="' . $result['zone_id'] . '"';
	
	    	if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
	      		$output .= ' selected="selected"';
	    	}
	
	    	$output .= '>' . $result['name'] . '</option>';
    	} 
		
		if (!$results) {
			if (!$this->request->get['zone_id']) {
		  		$output .= '<option value="0" selected="selected">' . $this->language->get('text_none') . '</option>';
			} else {
				$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
			}
		}
	
		$this->response->setOutput($output, $this->config->get('config_compression'));
  	}
	
	public function postcode() {

  		$this->language->load('checkout/guest_step_1');

  		$this->load->model('localisation/country');

    	$result = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		$output = '';

      	if ($result['postcode_required']) {
        	$output = '<span class="required">*</span> ' . $this->language->get('entry_postcode');
		} else {
			$output = $this->language->get('entry_postcode');
		}

		$this->response->setOutput($output, $this->config->get('config_compression'));
	}
}
?>