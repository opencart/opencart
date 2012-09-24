<?php
class ControllerCommonSeoUrl extends Controller {
	/* SEO Custom URL */
	private $url_list = array (
		'common/home'       	=> 'home',
		'checkout/cart'     	=> 'cart',
		'account/register'  	=> 'register',
		'account/wishlist'  	=> 'wishlist',
		'checkout/checkout' 	=> 'checkout',
		'account/login'     	=> 'login',
		'product/special'   	=> 'special',
		'affiliate/account' 	=> 'affiliate',
		'checkout/voucher'  	=> 'voucher',
		'product/manufacturer' => 'brand',
		'account/newsletter'   => 'newsletter',
		'account/order'        => 'order',
		'account/account'      => 'account',
		'information/contact'  => 'contact',
		'account/return/insert' => 'return/insert',
		'information/sitemap'   => 'sitemap',
	);
	/* SEO Custom URL */

	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}
		
		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$parts = explode('/', $this->request->get['_route_']);
			
			foreach ($parts as $part) {
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($part) . "'");
				
				if ($query->num_rows) {
					$url = explode('=', $query->row['query']);
					
					if ($url[0] == 'product_id') {
						$this->request->get['product_id'] = $url[1];
					}
					
					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					}	
					
					if ($url[0] == 'manufacturer_id') {
						$this->request->get['manufacturer_id'] = $url[1];
					}
					
					if ($url[0] == 'information_id') {
						$this->request->get['information_id'] = $url[1];
					}	
				} else {
					$this->request->get['route'] = 'error/not_found';	
				}
			}

			/* SEO Custom URL */
			if ( $_s = $this->setURL($this->request->get['_route_']) ) {
			    $this->request->get['route'] = $_s;
			}/* SEO Custom URL */

			if (isset($this->request->get['product_id'])) {
				$this->request->get['route'] = 'product/product';
			} elseif (isset($this->request->get['path'])) {
				$this->request->get['route'] = 'product/category';
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$this->request->get['route'] = 'product/manufacturer/info';
			} elseif (isset($this->request->get['information_id'])) {
				$this->request->get['route'] = 'information/information';
			}
			
			if (isset($this->request->get['route'])) {
				return $this->forward($this->request->get['route']);
			}
		}
	}
	
	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));
	
		$url = ''; 
		
		$data = array();
		
		parse_str($url_info['query'], $data);
		
		foreach ($data as $key => $value) {
			if (isset($data['route'])) {
				if (($data['route'] == 'product/product' && $key == 'product_id') || (($data['route'] == 'product/manufacturer/info' || $data['route'] == 'product/product') && $key == 'manufacturer_id') || ($data['route'] == 'information/information' && $key == 'information_id')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '" . $this->db->escape($key . '=' . (int)$value) . "'");
				
					if ($query->num_rows) {
						$url .= '/' . $query->row['keyword'];
						
						unset($data[$key]);
					}					
				} elseif ($key == 'path') {
					$categories = explode('_', $value);
					
					foreach ($categories as $category) {
						$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = 'category_id=" . (int)$category . "'");
				
						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];
						}							
					}
					
					unset($data[$key]);
				}

				/* SEO Custom URL */
			    	if( $_u = $this->getURL($data['route']) ){
		        		$url .= $_u;
		        		unset($data[$key]);
			    	}/* SEO Custom URL */        

			}
		}
	
		if ($url) {
			unset($data['route']);
		
			$query = '';
		
			if ($data) {
				foreach ($data as $key => $value) {
					$query .= '&' . $key . '=' . $value;
				}
				
				if ($query) {
					$query = '?' . trim($query, '&');
				}
			}

			return $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . str_replace('/index.php', '', $url_info['path']) . $url . $query;
		} else {
			return $link;
		}
	}	

	/* SEO Custom URL */
	public function getURL($route) {
		if( count($this->url_list) > 0) {
			foreach ($this->url_list as $key => $value) {
				if($route == $key) {
					return '/'.$value;
				}
			}
		}
		return false;
	}

	public function setURL($_route) {
		if( count($this->url_list) > 0 ){
			foreach ($this->url_list as $key => $value) {
				if($_route == $value) {
					return $key;
				}
			}
		}
		return false;
	}/* SEO Custom URL */

}
?>