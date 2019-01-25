<?php
/**
 * @package		SeoPro
 * @author		Oclabs
 * @copyright	Copyright (c) 2017, Oclabs (https://www.oclabs.pro/)
 * @license		https://opensource.org/licenses/GPL-3.0
*/

// ALTER TABLE `oc_product_to_category` ADD `main_category_id` TINYINT(1) NOT NULL DEFAULT '0' AFTER `category_id`;

class SeoPro {
	
	private $config;
	private $ajax = false;
	private $request;
	private $registry;
	private $response;
	private $url;
	private $session;
	private $db;
	private $cat_tree = [];
	private $keywords = [];
	private $queries = [];
	private $product_categories = [];
	private $valide_get_param;

	public function __construct($registry) {	
		$this->detectAjax();
		$this->registry = $registry;
		$this->config = $registry->get('config');
		
		if(!$this->config->get('config_seo_pro')) 
			return;
		
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
		$this->response = $registry->get('response');
		$this->url = $registry->get('url');
		$this->db = $registry->get('db');
		$this->cache = $registry->get('cache');
		$this->detectPostfix();
		$this->detectLanguage();	
		$this->initHelpers();	
		if ($this->config->get('config_valide_param_flag')) {
			$params = explode ("\r\n", $this->config->get('config_valide_params'));
			if(!empty($params)) {
				$this->valide_get_param = $params;
			}
		}
	}
	
	public function prepareRoute($parts) {
		
		if (!empty($parts) && is_array($parts)) {

			foreach($parts as $id => $part) {
				
				if($this->config->get('config_seopro_lowercase')) 
					$parts[$id] = utf8_strtolower($part);
				
				if($parts[$id]) {
				
					$query = $this->getQueryByKeyword($parts[$id]);
					
					$url = explode('=', $query);
					
					if(!empty($url[0])) {
						
						if(!in_array($url[0], ['category_id', 'product_id', 'manufacturer_id', 'information_id', 'article_id', 'blog_category_id'])) {
							return $parts;
						}						
						
						if ($url[0] == 'category_id') {
							if (!isset($this->request->get['path'])) {
								$this->request->get['path'] = $url[1];
							} else {
								$this->request->get['path'] .= '_' . $url[1];
							}
						} elseif ($url[0] == 'blog_category_id') {
							if (!isset($this->request->get['blog_category_id'])) {
								$this->request->get['blog_category_id'] = $url[1];
							} else {
								$this->request->get['blog_category_id'] .= '_' . $url[1];
							}
						} elseif (count($url) > 1) {
							$this->request->get[$url[0]] = $url[1];
						}
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
			$path = $this->getCategoryByProduct($this->request->get['product_id']);
			if ($path) $this->request->get['path'] = $path;
			$this->request->get['route'] = 'product/product';									
		} elseif (isset($this->request->get['path'])) {
			$this->request->get['route'] = 'product/category';
		} elseif (isset($this->request->get['manufacturer_id'])) {
			$this->request->get['route'] = 'product/manufacturer/info';
		} elseif (isset($this->request->get['information_id'])) {
			$this->request->get['route'] = 'information/information';
		} 
		
		//blog
		if (isset($this->request->get['article_id'])) {
			if(isset($this->request->get['blog_category_id'])) {
				unset($this->request->get['blog_category_id']);
			};
			$blog_category_path = $this->getBlogPathByArticle($this->request->get['article_id']);
		if ($blog_category_path) $this->request->get['blog_category_id'] = $blog_category_path;
			$this->request->get['route'] = 'blog/article';									
		} elseif (isset($this->request->get['blog_category_id'])) {
			$this->request->get['route'] = 'blog/category';
		} 
		//end blog
		return $parts;
	}
	
	public function baseRewrite($data, $language_id) {
	
		$url = null;
		$postfix = null;
		$language_id = (int)$this->config->get('config_language_id');
	
		switch ($data['route']) {
			case 'product/product':
			if (isset($data['product_id'])) {
				$route = 'product/product';
				$path = '';
				$product_id = $data['product_id'];
				if (isset($data['path'])) {
					$path = $this->getCategoryByProduct($product_id);
				}
				
				//start add valide get-param
				if ($this->valide_get_param) {
					$valide_get_param_data = [];
					foreach($this->valide_get_param as $get_param) {
						if (isset($data[$get_param])) {
							$valide_get_param_data[$get_param] = $data[$get_param];
							$this->response->addHeader('X-Robots-Tag: noindex');
						}
					};
				}
				//end add valide get-param
				
				unset($data);
				$data['route'] = $route;
				
				if ($path && $this->config->get('config_seo_url_include_path')) {
					$data['path'] = $path;
				}	
				
				$data['product_id'] = $product_id;
				//start add valide get-param
				if ($this->valide_get_param) {
					$data = array_merge($data, $valide_get_param_data);
				}
				//end add valide get-param
			}
				break;				
			//blog	
			
			case 'blog/article':	
			if (isset($data['article_id'])) {
				$route = 'blog/article';
				$blog_path = '';
				$article_id = $data['article_id'];
				
				if (isset($data['blog_category_id'])) {
					$blog_path = $this->getBlogPathByArticle($article_id);
				}
				
				//start add valide get-param
				if ($this->valide_get_param) {
					$valide_get_param_data = [];
					foreach($this->valide_get_param as $get_param) {
							if (isset($data[$get_param])) {
							$valide_get_param_data[$get_param] = $data[$get_param];
						   /*
							* add x-robot-tag noindex
							* https://developers.google.com/search/reference/robots_meta_tag?hl=en
							*/
							$this->response->addHeader('X-Robots-Tag: noindex');
						}
					};
				}
				//end add valide get-param
				unset($data);
				$data['route'] = $route;

				if ($blog_path && $this->config->get('config_seo_url_include_path')) {
					$data['blog_category_id'] = $blog_path;
				}	
				
				$data['article_id'] = $article_id;
				
				if ($this->valide_get_param) {
					$data = array_merge($data, $valide_get_param_data);
				}
			}
				break;

			//blog
			case 'product/category':
				if (isset($data['path'])) {
					$category = explode('_', $data['path']);
					$category = end($category);
					$data['path'] = $this->getPathByCategory($category);
				}
				break;

			case 'blog/article/review':
				return [$url, $data, $postfix];
				break;				
			case 'product/product/review':
				return [$url, $data, $postfix];
				break;		
			case 'product/product/review':
				return [$url, $data, $postfix];
				break;				
			case 'information/information/info':
			case 'product/manufacturer/info':
				break;				
			case 'information/information/agree': 
				return [$url, $data, $postfix];
				break;
			default:
				break;
		}
	
		$queries = [];
		
		$route = '';
		if (isset($data['route'])) {
			$route = $data['route'];
			unset($data['route']);
		}
			
		foreach ($data as $key => $value) {

			switch ($key) {
				case 'product_id':
					$product_id = (int)$value;
					$queries[] = 'product_id=' . $product_id;
					$postfix = true;
					unset($data[$key]);
					break;
				case 'manufacturer_id':
				 	$manufacturer_id = (int)$value;
					$queries[] = 'manufacturer_id=' . $manufacturer_id;
					$postfix = true;
					unset($data[$key]);
					break;
				case 'category_id':
				case 'information_id':
					$information_id = (int)$value;
					$queries[] = 'information_id=' . $information_id;
					$postfix = true;
					unset($data[$key]);					
					break;
				//blog	
				case 'blog_category_id':
					$blog_categories = explode('_', $value);
					foreach ($blog_categories as $blog_category_id) {
						$queries[] = 'blog_category_id=' . (int)$blog_category_id;
					}
				unset($data[$key]);
				break;
				case 'article_id':
					$article_id = (int)$value;
					$queries[] = 'article_id=' . $article_id;
					$postfix = true;
					unset($data[$key]);
					break;
				
				//blog	
				case 'path':
					$categories = explode('_', $value);
					foreach ($categories as $category_id) {
						$queries[] = 'category_id=' . (int)$category_id;
					}
					unset($data[$key]);
					break;
				default:
					break;
			}
		}
		
		if (empty($queries) && $route) {
			
			$keyword = $this->getKeywordByQuery($route);
			//check url for route
			if($keyword !== null) {
				//common/home
				$url = '';
				if($keyword  !== '') 
					$url = '/' . rawurlencode($keyword);
			}
			//if not exist keyword for route & empty any keyword return route-param for native seo_url class
			$data['route']  = $route;
			
		} else {
		
			$rows = [];
				
			foreach ($queries as $query) {
				$keyword = $this->getKeywordByQuery($query);
				if ($keyword) 
					$rows[] = $keyword;
			}

			if (!empty($rows) && (count($rows) == count($queries))) {
				foreach($rows as $row) {
					$url .= '/' . rawurlencode($row);
				}
			}	
		}
		
		return [$url, $data, $postfix];
	}
	
		private function getPath($categories, $category_id, $current_path = []) {
	
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
	
	
	private function initHelpers() {
		// start category_tree
		if($this->config->get('config_seo_url_cache')){		
			$this->cat_tree = $this->cache->get('seopro.cat_tree');
		}
		
		if(!$this->cat_tree || empty($this->cat_tree)) {
		
			$this->cat_tree = [];
			
			$all_cat_query = $this->db->query("SELECT category_id, parent_id FROM " . DB_PREFIX . "category ORDER BY parent_id");
				
			$allcats = [];
			$categories = [];
			
			if($all_cat_query->num_rows) {
				$allcats = $all_cat_query->rows;
			};
			
			foreach ($allcats as $category) {
				$categories[$category['category_id']]['parent_id'] = $category['parent_id'];
			};
			unset ($allcats);
			
			foreach ($categories as $category_id => $category) {
				$path = $this->getPath($categories, $category_id);
				$this->cat_tree[$category_id]['path'] = $path;
					
			};
			
		}
		//end_category_tree
		
		//keyword_data
		if ($this->config->get('config_seo_url_cache')) {		

			$this->keywords = $this->cache->get('seopro.keywords');
			$this->queries = $this->cache->get('seopro.queries');

			if ((!$this->keywords || empty($this->keywords) || !$this->queries || empty($this->queries))) {
				
				$sql_keyword = 'keyword';
				if ($this->config->get('config_seopro_lowercase')) 
					$sql_keyword = 'LCASE(keyword) as '. $sql_keyword;		

				$sql = "SELECT " . $sql_keyword . ", query, store_id, language_id FROM " . DB_PREFIX . "seo_url WHERE 1";	
			
				$query = $this->db->query($sql);
				if($query->num_rows) {
					foreach($query->rows as $row) {
						$this->keywords[$row['query']][$row['store_id']][$row['language_id']] = $row['keyword'];
						$this->queries[$row['keyword']][$row['store_id']][$row['language_id']] = $row['query'];
					}
				}
			}
		}
		//end_keyword_data		
	}
	
	private function detectPostfix() {
		if($this->config->get('config_page_postfix') && isset($this->request->get['_route_'])) {
			$this->request->get['_route_'] = preg_replace('/' . $this->config->get('config_page_postfix') . '$/', '', (string)$this->request->get['_route_']);
		}
	}	
	
	private function addpostfix($url) {
		if($this->config->get('config_page_postfix')) {
			$url = rtrim($url, "/") . $this->config->get('config_page_postfix');		
		}
		return $url;
	}
	
	private function getQueryByKeyword($keyword, $language_id = null) {
		$query = null;
		$store_id = (int)$this->config->get('config_store_id');
				
		if (!$language_id) 
			$language_id = (int)$this->config->get('config_language_id');
		
		if ($this->config->get('config_seo_url_cache')){
			if (isset($this->queries[$keyword][$store_id][$language_id])) 
				$query = $this->queries[$keyword][$store_id][$language_id];
		} else {
			$_query = $this->db->query("SELECT query FROM " . DB_PREFIX . "seo_url WHERE keyword = '" . $this->db->escape($keyword) . "' AND store_id = '" . $store_id . "' AND language_id = '" . $language_id . "' LIMIT 1");	
			$query = !empty($_query->row) ? (string)$_query->row['query'] : null;
		}
		
		return $query;
	}
		
	private function getKeywordByQuery($query, $language_id = null) {
		
		$keyword = null;
		$store_id = (int)$this->config->get('config_store_id');
				
		if (!$language_id) 
			$language_id = $this->config->get('config_language_id');
		
		
		if ($this->config->get('config_seo_url_cache')) {
			if (isset($this->keywords[$query][$store_id][$language_id])) 
				$keyword = $this->keywords[$query][$store_id][$language_id];
		} else {
			
			$sql_keyword = 'keyword';
			if ($this->config->get('config_seopro_lowercase')) 
				$sql_keyword = 'LCASE(keyword) as '. $sql_keyword;
		
			
			$query = $this->db->query("SELECT " . $sql_keyword . " FROM " . DB_PREFIX . "seo_url WHERE query = '" . $this->db->escape($query) . "' AND store_id = '" . $store_id . "' AND language_id = '" . (int)$language_id . "' LIMIT 1");	
			$keyword =  !empty($query->row) ? (string)$query->row['keyword'] : null;
		
		}
		
		return $keyword;
	}
	
	public function validate() {
		
		// break redirect for php-cli-script
		if (php_sapi_name() === 'cli') 
			return;
		
		// fix flat link for xml feed
		if (isset($this->request->get['route'])) {
			$break_routes = [
				'error/not_found',
				'extension/feed/google_sitemap',
				'extension/feed/google_base',
				'extension/feed/sitemap_pro',
				'extension/feed/yandex_feed'
			];
			
			if (in_array($this->request->get['route'], $break_routes)) 
				return;
		}	

		if (!empty($this->request->post)) 
			return;
		
		if ($this->ajax) {
			$this->response->addHeader('X-Robots-Tag: noindex');
			return;
		}
		
		if (empty($this->request->get['route'])) 	
			$this->request->get['route'] = 'common/home';
		

		$uri = $this->request->server['REQUEST_URI'];
		$route = $this->request->get['route'];
		
		// remove negative page number
		if (isset($this->request->get['page'])) {
			if((float)$this->request->get['page'] < 1) {
				unset($this->request->get['page']);
			};
		};
		
		if ($_SERVER['HTTPS'] == true) {
			$host = substr($this->config->get('config_ssl'), 0, $this->strpos_offset('/', $this->config->get('config_ssl'), 3) + 1);	
		} else {
			$host = substr($this->config->get('config_url'), 0, $this->strpos_offset('/', $this->config->get('config_url'), 3) + 1);			
		}
							
		if (!$this->config->get('config_seopro_addslash')) {
			if ($uri == '/') {
				$host = rtrim($host, '/');
			}
		}

		$url = str_replace('&amp;', '&', $host . ltrim($uri, '/'));
		$seo = str_replace('&amp;', '&', $this->url->link($route, $this->getQueryString(array('_route_', 'route')), $_SERVER['HTTPS']));
	

		if (rawurldecode($url) != rawurldecode($seo)) {
			$this->response->redirect($seo, 301);
		}
	}
	
	private function detectAjax () {
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
			$this->ajax = true;
	}

	private function detectLanguage() {

		if ($this->ajax) 
			return;

		$request_language_id = null;
		$request_language_code = '';
		$active_language_id = $this->config->get('config_language_id');
	
		if (isset($this->request->get['_route_'])) {
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
	
	private function getCategoryByProduct($product_id) {

		if ((int)$product_id < 1) 
			return false;
				
		if ($this->config->get('config_seo_url_cache')) {	
			$this->product_categories = $this->cache->get('seopro.product_categories');
			if(isset($this->product_categories[$product_id])) 
				return $this->product_categories[$product_id];
		}

		$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "' ORDER BY main_category DESC LIMIT 1");
		$category_id = $this->getPathByCategory($query->num_rows ? (int)$query->row['category_id'] : 0);
		
		if ($this->config->get('config_seo_url_cache')) {
			$this->product_categories[$product_id] = $category_id;
		}	
	
		return $category_id;
	}

	private function getPathByCategory($category_id) {
		
		$path = '';

		if ((int)$category_id < 1 && !isset($this->cat_tree[$category_id])) 
			return false;
		
		if (!empty($this->cat_tree[$category_id]['path']) && is_array($this->cat_tree[$category_id]['path'])) {
			$path = implode('_', $this->cat_tree[$category_id]['path']);
		}

		return $path;
			
	}
	
	private function getBlogPathByArticle($article_id) {

		if ($article_id < 1) 
			return false;

		$query = $this->db->query("SELECT blog_category_id FROM " . DB_PREFIX . "article_to_blog_category WHERE article_id = '" . (int)$article_id . "' ORDER BY main_blog_category DESC LIMIT 1");
		$blog_category_path = $this->getBlogPathByCategory($query->num_rows ? (int)$query->row['blog_category_id'] : 0);

		return $blog_category_path;
	}
	
	private function getBlogPathByCategory($blog_category_id) {
		$blog_category_id = (int)$blog_category_id;
		if ($blog_category_id < 1) 
			return false;
		
		static $blog_path = [];
		$cache = 'seopro.blog_category.seopath';
		
		if (!is_array($blog_path)) {
			if ($this->config->get('config_seo_url_cache'))	
				$blog_path = $this->cache->get($cache);
			if (!is_array($blog_path)) 
				$blog_path = [];
		}
		
		if (!isset($blog_path[$blog_category_id])) {
			$max_level = 10;
			$sql = "SELECT CONCAT_WS('_'";
			for ($i = $max_level-1; $i >= 0; --$i) {
				$sql .= ",t$i.blog_category_id";
			}
			$sql .= ") AS path FROM " . DB_PREFIX . "blog_category t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "blog_category t$i ON (t$i.blog_category_id = t" . ($i-1) . ".parent_id)";
			}
			$sql .= " WHERE t0.blog_category_id = '" . $blog_category_id . "'";
			$query = $this->db->query($sql);
			$blog_path[$blog_category_id] = $query->num_rows ? $query->row['path'] : false;
			
			if ($this->config->get('config_seo_url_cache'))	
				$this->cache->set($cache, $blog_path);
		}
		
		return $blog_path[$blog_category_id];
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

	private function getQueryString($exclude = []) {
		if (!is_array($exclude))
			$exclude = [];
			
		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}	
	
	public function __destruct() {	
	
		if(!$this->config->get('config_seo_pro')) 
			return;
	
		if ($this->config->get('config_seo_url_cache')){	
			$this->cache->set('seopro.keywords', $this->keywords);
			$this->cache->set('seopro.queries', $this->queries);
			$this->cache->set('seopro.cat_tree', $this->cat_tree);
			$this->cache->set('seopro.product_categories', $this->product_categories);
		}
	}
}