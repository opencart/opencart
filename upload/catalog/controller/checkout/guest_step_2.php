<?php 
class ControllerCheckoutGuestStep2 extends Controller {
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
		
		if (!isset($this->session->data['guest'])) {
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_1');
    	} 
		
    	if (!$this->cart->hasShipping()) {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

			$this->tax->setZone($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
    	}		
		
		$this->load->model('checkout/extension');
		
		// Shipping Methods
		if ($this->cart->hasShipping() && (!isset($this->session->data['shipping_methods']) || !$this->config->get('config_shipping_session'))) {
			$quote_data = array();
			
			$results = $this->model_checkout_extension->getExtensions('shipping');
			
			foreach ($results as $result) {
				$this->load->model('shipping/' . $result['key']);
				
				$quote = $this->{'model_shipping_' . $result['key']}->getQuote($this->session->data['guest']); 
	
				if ($quote) {
					$quote_data[$result['key']] = array(
						'title'      => $quote['title'],
						'quote'      => $quote['quote'], 
						'sort_order' => $quote['sort_order'],
						'error'      => $quote['error']
					);
				}
			}
	
			$sort_order = array();
		  
			foreach ($quote_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $quote_data);
			
			$this->session->data['shipping_methods'] = $quote_data;
		}
		
		// Payment Methods
		$method_data = array();
		
		$results = $this->model_checkout_extension->getExtensions('payment');

		foreach ($results as $result) {
			$this->load->model('payment/' . $result['key']);
			
			$method = $this->{'model_payment_' . $result['key']}->getMethod($this->session->data['guest']); 
			 
			if ($method) {
				$method_data[$result['key']] = $method;
			}
		}
					 
		$sort_order = array(); 
	  
		foreach ($method_data as $key => $value) {
      		$sort_order[$key] = $value['sort_order'];
    	}

    	array_multisort($sort_order, SORT_ASC, $method_data);			
		
		$this->session->data['payment_methods'] = $method_data;
		
		$this->language->load('checkout/guest_step_2');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (isset($this->request->post['shipping_method'])) {
				$shipping = explode('.', $this->request->post['shipping_method']);
			
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
			}
			
			$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];
		
			$this->session->data['comment'] = $this->request->post['comment'];
			
	  		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/guest_step_3');
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
		
      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTPS_SERVER . 'index.php?route=checkout/guest_step_2',
        	'text'      => $this->language->get('text_guest_step_2'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_shipping_methods'] = $this->language->get('text_shipping_methods');
		$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_payment_methods'] = $this->language->get('text_payment_methods');
		$this->data['text_comments'] = $this->language->get('text_comments');
		
		$this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_payment'] = $this->language->get('entry_payment');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			
			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
    		$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['shipping_methods']) && !$this->session->data['shipping_methods']) {
			$this->data['error_warning'] = $this->language->get('error_no_shipping');
		}			
		
    	$this->data['action'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';

		if (isset($this->session->data['shipping_methods'])) {
			$this->data['shipping_methods'] = $this->session->data['shipping_methods']; 
		} else {
			$this->data['shipping_methods'] = array();
		}
	  
   		if (isset($this->request->post['shipping_method'])) {
      		$this->data['shipping'] = $this->request->post['shipping_method'];
		} elseif (isset($this->session->data['shipping_method'])) {
			$this->data['shipping'] = $this->session->data['shipping_method']['id'];			
    	} else {
      		$this->data['shipping'] = '';
    	}

		if (isset($this->session->data['payment_methods'])) {
        	$this->data['payment_methods'] = $this->session->data['payment_methods']; 
      	} else {
        	$this->data['payment_methods'] = array();
		}
		
   		if (isset($this->request->post['payment_method'])) {
      		$this->data['payment'] = $this->request->post['payment_method'];
		} elseif (isset($this->session->data['payment_method'])) {
			$this->data['payment'] = $this->session->data['payment_method']['id'];			
    	} else {
      		$this->data['payment'] = '';
    	}
		
		$this->load->model('localisation/country');
		
    	$this->data['countries'] = $this->model_localisation_country->getCountries();

		if (isset($this->session->data['comment'])) {
    		$this->data['comment'] = $this->session->data['comment'];
		} else {
			$this->data['comment'] = '';
		}

		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
			
			if ($information_info) {
				$this->data['text_agree'] = sprintf($this->language->get('text_agree'), HTTP_SERVER . 'index.php?route=information/information&information_id=' . $this->config->get('config_checkout_id'), $information_info['title']);
			} else {
				$this->data['text_agree'] = '';
			}
		} else {
			$this->data['text_agree'] = '';
		}
		
		if (isset($this->request->post['agree'])) { 
      		$this->data['agree'] = $this->request->post['agree'];
		} else {
			$this->data['agree'] = '';
		}
		
		$this->data['back'] = HTTP_SERVER . 'index.php?route=checkout/guest_step_1';
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/guest_step_2.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/checkout/guest_step_2.tpl';
		} else {
			$this->template = 'default/template/checkout/guest_step_2.tpl';
		}
		
		$this->children = array(
			'common/header',
			'common/footer',
			'common/column_left',
			'common/column_right'
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}
	
  	private function validate() {
		if ($this->cart->hasShipping()) {
    		if (!isset($this->request->post['shipping_method']) || !$this->request->post['shipping_method']) {
		  		$this->error['warning'] = $this->language->get('error_shipping');
			} else {
				$shipping = explode('.', $this->request->post['shipping_method']);
				
				if (!isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {			
					$this->error['warning'] = $this->language->get('error_shipping');
				}
			}
		}
		
    	if (!isset($this->request->post['payment_method'])) {
	  		$this->error['warning'] = $this->language->get('error_payment');
		} else {
			if (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$this->error['warning'] = $this->language->get('error_payment');
			}
		}
		
		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));
			
			if ($information_info) {
    			if (!isset($this->request->post['agree'])) {
      				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
    			}
			}
		}
		
    	if (!$this->error) {
      		return TRUE;
    	} else {
      		return FALSE;
    	}
  	}
}
?>