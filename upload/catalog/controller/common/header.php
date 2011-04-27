<?php   
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = $this->config->get('config_ssl');
		} else {
			$this->data['base'] = $this->config->get('config_url');
		}
		
		$this->data['description'] 	= $this->document->getDescription();
		$this->data['keywords'] 	= $this->document->getKeywords();
		$this->data['links'] 		= $this->document->getLinks();	 
		$this->data['styles'] 		= $this->document->getStyles();
		$this->data['scripts'] 		= $this->document->getScripts();
		$this->data['lang'] 		= $this->language->get('code');
		$this->data['direction'] 	= $this->language->get('direction');

		$this->language->load('common/header');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_IMAGE;
		} else {
			$server = HTTP_IMAGE;
		}	
				
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		$this->data['name'] = $this->config->get('config_name');
				
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_bookmark'] = $this->language->get('text_bookmark');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_voucher'] = $this->language->get('text_voucher');
		$this->data['text_wishlist'] =  sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_contact'] = $this->language->get('text_contact');
		$this->data['text_sitemap'] = $this->language->get('text_sitemap');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_cart'] = $this->language->get('text_cart');
		$this->data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts(), $this->currency->format($this->cart->getTotal()));
		$this->data['text_login'] = $this->language->get('text_login');
    	$this->data['text_logout'] = $this->language->get('text_logout');
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_basket'] = $this->language->get('text_basket');
    	$this->data['text_checkout'] = $this->language->get('text_checkout');

		$this->data['entry_language'] = $this->language->get('entry_language');
		$this->data['entry_currency'] = $this->language->get('entry_currency');

		$this->data['home'] = $this->url->link('common/home');
		$this->data['special'] = $this->url->link('product/special');
		$this->data['voucher'] = $this->url->link('account/voucher');
		$this->data['wishlist'] = $this->url->link('account/wishlist');
		$this->data['contact'] = $this->url->link('information/contact');
    	$this->data['sitemap'] = $this->url->link('information/sitemap');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['login'] = $this->url->link('account/login', '', 'SSL');
		$this->data['logout'] = $this->url->link('account/logout');
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		
		if (isset($this->request->get['filter_name'])) {
			$this->data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$this->data['filter_name'] = '';
		}
		
		$this->data['action'] = $this->url->link('common/home');

		if (!isset($this->request->get['route'])) {
			$this->data['redirect'] = $this->url->link('common/home');
		} else {
			$data = $this->request->get;
			
			unset($data['_route_']);
			
			$route = $data['route'];
			
			unset($data['route']);
			
			$url = '';
			
			if ($data) {
				$url = '&' . urldecode(http_build_query($data));
			}			
			
			$this->data['redirect'] = $this->url->link($route, $url);
		}

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['language_code'])) {
			$this->session->data['language'] = $this->request->post['language_code'];
		
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
    	}		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['currency_code'])) {
      		$this->currency->set($this->request->post['currency_code']);
			
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
				
			if (isset($this->request->post['redirect'])) {
				$this->redirect($this->request->post['redirect']);
			} else {
				$this->redirect($this->url->link('common/home'));
			}
   		}
		
		$this->data['currency_code'] = $this->currency->getCode(); 
		
		$this->load->model('localisation/currency');
		 
		 $this->data['currencies'] = array();
		 
		$results = $this->model_localisation_currency->getCurrencies();	
		
		foreach ($results as $result) {
			if ($result['status']) {
   				$this->data['currencies'][] = array(
					'title' => $result['title'],
					'code'  => $result['code']
				);
			}
		}
						
		$this->data['language_code'] = $this->session->data['language'];
		
		$this->load->model('localisation/language');
		
		$this->data['languages'] = array();
		
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			if ($result['status']) {
				$this->data['languages'][] = array(
					'name'  => $result['name'],
					'code'  => $result['code'],
					'image' => $result['image']
				);	
			}
		}
		
		// Set the same session accross multiple domains.
		$this->data['domains'] = array();
		
		$this->data['domains'][] = HTTP_SERVER . 'setcookie.php?' . session_name() . '=' . session_id();
		
		if (HTTPS_SERVER) {
			$this->data['domains'][] = HTTPS_SERVER. 'setcookie.php?' . session_name() . '=' . session_id();	
		}
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "store");
		
		foreach ($query->rows as $result) {
			$this->data['domains'][] = $result['url']. 'setcookie.php?' . session_name() . '=' . session_id();
			
			if ($result['ssl']) {
				$this->data['domains'][] = $result['ssl']. 'setcookie.php?' . session_name() . '=' . session_id();
			}
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
    	$this->render();
	} 	
}
?>