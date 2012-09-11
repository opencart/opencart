<?php
class ControllerCommonSeoUrl extends Controller {
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

		if (isset($data['route'])) {

			$query_base = "SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '%s=%d'";

			$searches = array(
				'product_id'      => array('product/product'),
				'category_id'     => array('product/category'),
				'manufacturer_id' => array('product/product', 'product/manufacturer/info'),
				'information_id'  => array('information/information'),
			);

			if ($data['route'] == 'common/home') {
				$url .= '/';

			} elseif (isset($data['path'])) {
				$data['category_id'] = explode('_', $data['path']);
				unset($data['path']);
			}

			foreach ($searches as $key => $routes) {
				if (isset($data[$key]) && in_array($data['route'], $routes)) {

					$value = (is_array($data[$key]) ? $data[$key] : array($data[$key]));

					foreach ($value as $i => $id) {
						$query = $this->db->query(sprintf($query_base, $this->db->escape($key), (int)$id));

						if ($query->num_rows) {
							$url .= '/' . $query->row['keyword'];

							unset($value[$i]);
						}
						else { break; }
					}
					if (empty($value)) { unset($data[$key]); }
					else               { $url = ''; }

					break;
				}
			}
		}
		
		if ($url) {
			unset($data['route']);

			$url_info['path'] = str_replace('/index.php', $url, $url_info['path']);
			$url_info['query'] = http_build_query($data);

			return $this->url->build($url_info);
		} else {
			return $link;
		}		
	}	
}
?>