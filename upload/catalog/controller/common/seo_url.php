<?php
class ControllerCommonSeoUrl extends Controller {

	private $route_map = array(
		'product_id'      => array('product/product'),
		'category_id'     => array('product/category'),
		'manufacturer_id' => array('product/manufacturer/info', 'product/product'),
		'information_id'  => array('information/information'),
	);

	public function index() {
		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		}
		
		// Decode URL
		if (isset($this->request->get['_route_'])) {
			$this->request->get['route'] = 'error/not_found';	

			$route = '';
			$db_parts = array();
			$parts = explode('/', $this->db->escape($this->request->get['_route_']));
			$query = $this->db->query("SELECT keyword, query FROM " . DB_PREFIX . "url_alias WHERE keyword IN ('" . implode("', '", $parts) . "')");

			foreach ($query->rows as $row) {
				$db_parts[$row['keyword']] = array_combine(array('key', 'value'), explode('=', $row['query']));
			}
			$parts = array_replace(array_flip($parts), $db_parts);

			foreach ($parts as $field) {
				if (!$route && isset($this->route_map[$field['key']])) {
					$route = $this->route_map[$field['key']][0];
				}
				if ($field['key'] == 'category_id') {
					$field['key'] = 'path';
					if (isset($this->request->get['path'])) {
						$field['value'] = $this->request->get['path'] . '_' . $field['value'];
					}
				}
				$this->request->get[$field['key']] = $field['value'];
			}
			if ($route) { $this->request->get['route'] = $route; }

			return $this->forward($this->request->get['route']);
		}
	}
	
	public function rewrite($link) {
		$url_info = parse_url(str_replace('&amp;', '&', $link));
	
		$url = ''; 
		
		$data = array();
		
		parse_str($url_info['query'], $data);

		if (isset($data['route'])) {

			$query_base = "SELECT * FROM " . DB_PREFIX . "url_alias WHERE `query` = '%s=%d'";

			if ($data['route'] == 'common/home') {
				$url .= '/';

			} elseif (isset($data['path'])) {
				$data['category_id'] = explode('_', $data['path']);
				unset($data['path']);
			}

			foreach ($this->route_map as $key => $routes) {
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
			unset($data['category_id']);

			$url_info['path'] = str_replace('/index.php', $url, $url_info['path']);
			$url_info['query'] = http_build_query($data);

			return $this->url->build($url_info);
		} else {
			return $link;
		}		
	}	
}
?>