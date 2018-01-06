<?php
/**
 * @package		SeoPro
 * @author		Oclabs
 * @copyright	Copyright (c) 2017, Oclabs (https://www.oclabs.pro/)
 * @license		https://opensource.org/licenses/GPL-3.0
*/

// ALTER TABLE `oc_product_to_category` ADD `main_category_id` TINYINT(1) NOT NULL DEFAULT '0' AFTER `category_id`;

/** ToDo
	
+	Добавить в карточку товара главную категорию
---------------------------
	Настройки  (перевод в нижний регистр)
	+ Ссылки с категорией или без		
---------------------------
	+ Ограничение роутов с обрезкой всех get-параметров 
	  Набор глобальных и индвидуальных разрешенных параметров.
---------------------------
	+ Набор роутов, в которых используется суффикс	
--------------------------
	Произвольные префиксы для роутов - типа catalog/brand etc....
---------------------------
	+ Очистка повторяющихся слешей
---------------------------
	Проверить работу в подпапке 
---------------------------
	+ Кеш
---------------------------
	+ Построение полного пути к категории через глобальный массив, содержащий полный путь к категории
---------------------------
	Языковой префикс для страниц без урл-алиасов 
---------------------------
	+ Построение ссылок для товаров через категорию/товар/последнюю категорию
---------------------------
	?page=1 ====== /page1
---------------------------
	Пользовательские методы
---------------------------
	Принудительная перепись всех ссылок на HTTPS
---------------------------
	+ Проверку ajax-request	
---------------------------
	+ Common home
---------------------------
	Настройка слешей в конце
----------------------------
	Переделать single_route
*/

class SeoPro {
	
	private $config;
	private $request;
	private $registry;
	private $response;
	private $url;
	private $session;
	private $db;
	private $cat_tree = [];
	private $keywords = [];
	private $queries = [];

	public function __construct($registry) {	
		$this->registry = $registry;
		$this->config = $registry->get('config');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->response = $registry->get('response');
		$this->url = $registry->get('url');
		$this->db = $registry->get('db');
		$this->cache = $registry->get('cache');
		$this->detectpostfix();
		$this->detectLanguage();	
		$this->initHelpers();	
		
	}
	
	protected function getPath($categories, $category_id, $current_path = array()) {
	
		if(!$current_path)
			$current_path = [(int)$category_id];
			
		$path = $current_path;
		
		$parent_id = 0;
		
		if(isset($categories[$category_id]['parent_id'])) 
			$parent_id = (int)$categories[$category_id]['parent_id'];

					
		if($parent_id > 0) {
			$new_path =  array_merge ([$parent_id] , $current_path);
			$path =  $this->getPath($categories, $parent_id, $new_path);
		}
			

		return $path;
	}
	
	
	public function initHelpers() {
		//category_tree
		
		if(!$this->config->get('config_seo_pro')) 
			return;
		
		if($this->config->get('config_seo_url_cache')){		
			$this->cat_tree = $this->cache->get('seopro.cat_tree');
		}

		$this->cat_tree = [];

		if(!$this->cat_tree || empty($this->cat_tree)) {
			
		
			$this->cat_tree = [];
			
			$all_cat_query = $this->db->query("SELECT category_id, parent_id FROM " . DB_PREFIX . "category ORDER BY parent_id");
				
			$allcat = array();
			$categories = array();
			
			if($all_cat_query->num_rows) {
				$allcats = $all_cat_query->rows;
			};
			
			foreach ($allcats as $row) {
				$categories[$row['category_id']]['parent_id'] = $row['parent_id'];
			};
			unset ($allcats);
			
			foreach ($categories as $category_id => $row) {
				$path = $this->getPath($categories, $category_id);
				$this->cat_tree[$category_id]['path'] = $path;
					
			};
	
			if($this->config->get('config_seo_url_cache')){		
				$this->cache->set('seopro.cat_tree', $this->cat_tree);
			};
		}
		
		
		//end_category_tree
		
		//keyword_data
		if($this->config->get('config_seo_url_cache') ){		
			$cache_keyword = 'seopro.keywords';
			$cache_queries = 'seopro.queries';
			$this->keywords = $this->cache->get($cache_keyword);
			$this->queries = $this->cache->get($cache_queries);

			if((!$this->keywords || empty($this->keywords) || !$this->queries || empty($this->queries))) {
				
				$sql = "SELECT * FROM " . DB_PREFIX . "seo_url WHERE 1";
				$query = $this->db->query($sql);
				if($query->num_rows) {
					foreach($query->rows as $row) {
						$this->keywords[$row['query']][$row['store_id']][$row['language_id']] = $row['keyword'];
						$this->queries[$row['keyword']][$row['store_id']][$row['language_id']] = $row['query'];
						
					}
				}
			
				$this->cache->set($cache_keyword, $this->keywords);
				$this->cache->set($cache_queries, $this->queries);

			}

		}
		
		//end_keyword_data		
				
	}
	
	public function detectpostfix() {
		if(!$this->config->get('config_seo_pro')) 
			return;
		
		if($this->config->get('config_page_postfix') && isset($this->request->get['_route_'])) {
			$this->request->get['_route_'] = rtrim($this->request->get['_route_'], $this->config->get('config_page_postfix'));
		}
	}	
	
	public function addpostfix($url) {
		
		if($this->config->get('config_page_postfix')) {
			$url = rtrim($url, "/") . $this->config->get('config_page_postfix');		

		}
		
		return $url;
	}
	
	public function prepareRoute($parts) {

		if (!empty($parts) && is_array($parts)) {

			foreach($parts as $id => $part) {
				
				$parts[$id] = utf8_strtolower($part);
				
				if($parts[$id]) {
				
					$query = $this->getQueryByKeyword($parts[$id]);
					
					$url = explode('=', $query);

					if ($url[0] == 'category_id') {
						if (!isset($this->request->get['path'])) {
							$this->request->get['path'] = $url[1];
						} else {
							$this->request->get['path'] .= '_' . $url[1];
						}
					} elseif (count($url) > 1) {
						$this->request->get[$url[0]] = $url[1];
					}
	
				}
					
					unset($parts[$id]);
			}	
			
	
			
			if(!$query) {
				$this->request->get['route'] = 'error/not_found';
				return [];
			}
				
		}
		
		
		if (isset($this->request->get['product_id'])) {
			if(isset($this->request->get['path'])) {
				unset($this->request->get['path']);
			};
			$path = $this->getPathByProduct($this->request->get['product_id']);
		
		if ($path) $this->request->get['path'] = $path;
			$this->request->get['route'] = 'product/product';									
		} elseif (isset($this->request->get['path'])) {
			$this->request->get['route'] = 'product/category';
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$this->request->get['route'] = 'product/manufacturer/info';
		} elseif (isset($this->request->get['information_id'])) {
			$this->request->get['route'] = 'information/information';
		} 
	
		return $parts;
	}
	
	public function baseRewrite($data, $language_id) {
		
		
		$url = null;
		$postfix = null;
		
		$language_id = (int)$this->config->get('config_language_id');

			switch ($data['route']) {
				case 'product/product':
				$postfix = true;
	
				if (isset($data['product_id'])) {
					$route = 'product/product';
					$path = '';
					$product_id = $data['product_id'];
					if (isset($data['path'])) {
						$path = $this->getPathByProduct($product_id);
					}
					unset($data);
					$data['route'] = $route;

					if ($path && $this->config->get('config_seo_url_include_path')) {
						$data['path'] = $path;
					}	
					
					$data['product_id'] = $product_id;
				}

					break;
				case 'product/category':
					if (isset($data['path'])) {
						$category = explode('_', $data['path']);
						$category = end($category);
						$data['path'] = $this->getPathByCategory($category);
						//if (!$data['path']) return $url;
					}
					break;

				case 'product/product/review':
				case 'information/information/info':
				case 'product/manufacturer/info':
					break;

				default:
					break;
			}
			

	$queries = array();
		foreach ($data as $key => $value) {
				$query_ = $this->getKeywordByQuery($value);
				//single_url
				if($query_ !== null) {
					unset($data[$key]);
					//common/home
					if($query_  == '')  return [$query_, $data ,  $postfix];
					//common/home
					return ['/' . $query_, $data ,  $postfix];
				}
				//single_url
			
			
			switch ($key) {
				case 'product_id':
					$postfix = true;
				case 'manufacturer_id':
				 	$postfix = true;
				case 'category_id':
				case 'information_id':
					$queries[] = $key . '=' . $value;
					unset($data[$key]);
					$postfix = true;
					break;

				case 'path':
					$categories = explode('_', $value);
					foreach ($categories as $category) {
						$queries[] = 'category_id=' . $category;
					}
					unset($data[$key]);
					break;

				default:
					break;
			}
		}


		$rows = array();
		foreach($queries as $query) {
			$keyword = $this->getKeywordByQuery($query);
			if ($keyword) $rows[] = $keyword;
		}

	
		if(count($rows) == count($queries)) {

			foreach($rows as $row) {
				$url .= '/' . rawurlencode($row);
			}
						
		}		

		return [$url, $data, $postfix];
			
	}
	
	public function getQueryByKeyword($keyword, $language_id = '') {
		
		$store_id = (int)$this->config->get('config_store_id');
				
		if(!$language_id) {
			$language_id = $this->config->get('config_language_id');
		}
		
		if($this->config->get('config_seo_url_cache')){
			if(isset($this->queries[$keyword][$store_id][$language_id]))
				return($this->queries[$keyword][$store_id][$language_id]);

		}
		
		$sql = "SELECT query FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($keyword) . "' AND store_id = '" . (int)$store_id . "' AND language_id = '" . (int)$language_id . "' LIMIT 1";
	
		$query = $this->db->query($sql);	
				if ($query->num_rows) {
					return  $query->row['query'];
				}
				
		return;
	}
		
	public function getKeywordByQuery($query, $language_id = '') {
		
		$store_id = (int)$this->config->get('config_store_id');
				
		if(!$language_id) {
			$language_id = $this->config->get('config_language_id');
		}
		
		if($this->config->get('config_seo_url_cache')){

			if(isset($this->keywords[$query][$store_id][$language_id])) 
				return($this->keywords[$query][$store_id][$language_id]);
			
			
		}
		
		
		$sql = "SELECT keyword FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($query) . "' AND store_id = '" . $store_id . "' AND language_id = '" . (int)$language_id . "' LIMIT 1";
		$query = $this->db->query($sql);	

				if ($query->num_rows) {
					return  $query->row['keyword'];
				}
		return;
	}
	
	public function validate() {
		
	
		if (isset($this->request->get['route']) && $this->request->get['route'] == 'error/not_found') {
			return;
		}


		if (!empty($this->request->post)) {
			return;
		}
		
		
		if(empty($this->request->get['route'])) {	
			$this->request->get['route'] = 'common/home';
		}
	
	
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return;
		}

		$uri = $this->request->server['REQUEST_URI'];
		$route = $this->request->get['route'];
		

		
		if ($_SERVER['HTTPS'] == true) {
			$host = substr($this->config->get('config_ssl'), 0, $this->strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);	
		} else {
			$host = substr($this->config->get('config_url'), 0, $this->strpos_offset('/', $this->config->get('config_url'), 3) + 1);			
		}
							
		
		if ($uri == '/') {
			$host = rtrim($host, '/');
		}
		

		$url = str_replace('&amp;', '&', $host . ltrim($uri, '/'));
		$seo = str_replace('&amp;', '&', $this->url->link($route, $this->getQueryString(array('_route_', 'route')), $_SERVER['HTTPS']));
		
	
		
		if (rawurldecode($url) != rawurldecode($seo)) {
			$this->response->redirect($seo, 301);
		}
		
	
		
	}

	private function detectLanguage() {
		
		if(!$this->config->get('config_seo_pro')) 
			return;

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return;
		}
		
			
		$request_language_id = null;
		$request_language_code = '';
		$active_language_id = $this->config->get('config_language_id');
		

		
		if(isset($this->request->get['_route_'])) {
			$parts = $parts = explode('/', $this->request->get['_route_']);
			$keyword = end($parts);
		} 	else {
			$keyword = '';
		}
		

		if ($keyword || $this->request->server['REQUEST_URI'] == '/') {
			$query = $this->db->query("SELECT language_id  FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($keyword) . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "' LIMIT 1");
			if ($query->row) {
				$request_language_id = (int)$query->row['language_id'];				
		
				$query = $this->db->query("SELECT code FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$request_language_id . "' AND status = '1' LIMIT 1");
				
				if ($query->row) {
					$request_language_code = $query->row['code'];
					$this->session->data['language'] = $request_language_code;
				}

			} 
		
		}

			
		if (isset($this->session->data['language'])) {
				$query = $this->db->query("SELECT language_id FROM " . DB_PREFIX . "language WHERE code = '" . (int)$this->session->data['language'] . "' AND status = '1' LIMIT 1");		
				if ($query->num_rows) {
					$active_language_id = (int)$query->row['language_id'];
				}

		}
		


		if($request_language_id  && $request_language_code && $active_language_id != $request_language_id) {
			
			$language = new Language($request_language_code);
			$language->load($request_language_code);
			$this->registry->set('language', $language);
			$this->config->set('config_language_id', $request_language_id);	
			$this->registry->set('language', $language);
			
		}

	}
	
	
	private function strpos_offset($needle, $haystack, $occurrence) {
		// explode the haystack
		$arr = explode($needle, $haystack);
		// check the needle is not out of bounds
		switch($occurrence) {
			case $occurrence == 0:
				return false;
			case $occurrence > max(array_keys($arr)):
				return false;
			default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
		}
	}

	private function getQueryString($exclude = array()) {
		if (!is_array($exclude)) {
			$exclude = array();
			}

		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}	
	
	
	private function getPathByProduct($product_id) {

		if ($product_id < 1) return false;

		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY main_category DESC LIMIT 1");
		$path = $this->getPathByCategory($query->num_rows ? (int)$query->row['category_id'] : 0);

		return $path;
	}

	private function getPathByCategory($category_id) {
		
		$path = '';

		if ($category_id < 1 && !isset($this->cat_tree[$category_id])) return false;
		
		if(!empty($this->cat_tree[$category_id]['path']) && is_array($this->cat_tree[$category_id]['path'])) {
			$path = implode('_', $this->cat_tree[$category_id]['path']);
		}

		return $path;
			
	}
	
}