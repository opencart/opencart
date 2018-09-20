<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerStartupSeoPro extends Controller {
	private $cache_data = null;
	private $languages = array();
	private $config_language;
	private $valid_server = false;
	private $url_sheme = 'http';
	private $ssl_routes = array(
	'checkout/',
	'account/',
	'affiliate/',
	);

	// Добавлять нужные роуты для исключений здесь!
	private $valide_routes = array(
		'tracking',
		'utm_source',
		'utm_campaign',
		'utm_medium',
		'type',
		'source',
		'block',
		'position',
		'keyword',
		'yclid',
		'gclid'
	);
	

	
	public function __construct($registry) {
		parent::__construct($registry);
		$this->valid_server = (bool)(parse_url(HTTPS_SERVER, PHP_URL_SCHEME)=='https');
			if (!$this->valid_server && $this->request->server['HTTPS']) {
				$r = isset($this->request->get['route'])?$this->request->get['route']:'';
				$this->response->redirect(str_replace('&amp;', '&', $this->url->link($r, $this->getQueryString(array('route')))), 301);
			}
		$ssl_mode = (bool)$this->config->get('config_secure') + $this->valid_server;
			switch ($ssl_mode) {
				case '2':
				$this->url_sheme = 'https';
				break;
				case '1':
				$this->url_sheme = ($this->request->server['HTTPS'])?'https':'http';
				break;
				case '0':
				default:
				$this->url_sheme = 'http';
				break;
			}
		$this->cache_data = $this->cache->get('seo_pro');
		if (!$this->cache_data) {
			$query = $this->db->query("SELECT LOWER(`keyword`) as 'keyword', `query` FROM " . DB_PREFIX . "url_alias");
			$this->cache_data = array();
			foreach ($query->rows as $row) {
				$this->cache_data['keywords'][$row['keyword']] = $row['query'];
				$this->cache_data['queries'][$row['query']] = $row['keyword'];
			} 
			if(!isset($this->cache_data['queries']['common/home'])) {
				$this->cache_data['queries']['common/home'] = '';
				$this->cache_data['keywords'][''] = 'common/home';
			}
			$this->cache->set('seo_pro', $this->cache_data);
		}
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `key` = 'config_language'");
		$this->config_language = $query->row['value'];
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'");
		foreach ($query->rows as $result) {
			$this->languages[$result['code']] = $result;
		}
	}
	
	
	public function index() {

		// Add rewrite to url class
		if ($this->config->get('config_seo_url')) {
			$this->url->addRewrite($this);
		} else {
			return;
		}
		
		// Для cron скриптов, типа YML feed
		if (php_sapi_name() === 'cli') return;
		
		// Decode URL
		if (!isset($this->request->get['_route_'])) {
			$this->validate();
		} else {
			$route_ = $this->request->get['_route_'];
			unset($this->request->get['_route_']);
			$parts = explode('/', trim(utf8_strtolower($route_), '/'));
			list($last_part) = explode('.', array_pop($parts));
			array_push($parts, $last_part);
			$rows = array();
			foreach ($parts as $keyword) {
				if (isset($this->cache_data['keywords'][$keyword])) {
					$rows[] = array('keyword' => $keyword, 'query' => $this->cache_data['keywords'][$keyword]);
				}
			}
						
			if (count($rows) == sizeof($parts)) {
				$queries = array();
				foreach ($rows as $row) {
					$queries[utf8_strtolower($row['keyword'])] = $row['query'];
				}
				reset($parts);
			
				foreach ($parts as $part) {
					$url = explode('=', $queries[$part], 2);
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
			} else {
				$this->request->get['route'] = 'error/not_found';
			}
			
			if (isset($this->request->get['product_id'])) {
				$this->request->get['route'] = 'product/product';
				if (!isset($this->request->get['path'])) {
					$path = $this->getPathByProduct($this->request->get['product_id']);
					if ($path) $this->request->get['path'] = $path;
				}
			} elseif (isset($this->request->get['path'])) {
				$this->request->get['route'] = 'product/category';
				//blog
			} elseif (isset($this->request->get['article_id'])) {
				$this->request->get['route'] = 'blog/article';
				if (!isset($this->request->get['blog_category_id'])) {
					$blog_category_id = $this->getPathByArticle($this->request->get['article_id']);
					if ($blog_category_id) $this->request->get['blog_category_id'] = $blog_category_id;
				}
			} elseif (isset($this->request->get['blog_category_id'])) {
				$this->request->get['route'] = 'blog/category';
			//blog
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$this->request->get['route'] = 'product/manufacturer/info';
			} elseif (isset($this->request->get['information_id'])) {
				$this->request->get['route'] = 'information/information';
			} elseif(isset($this->cache_data['queries'][$route_])) {
					$this->response->redirect($this->cache_data['queries'][$route_], 301);
			} else {
				if (isset($queries[$parts[0]])) {
					$this->request->get['route'] = $queries[$parts[0]];
				}
			}
			$this->validate();
			if (isset($this->request->get['route'])) {
				return new Action($this->request->get['route']);
			}
		}
	}
	public function rewrite($link) {
		if (!$this->config->get('config_seo_url')) return $link;
		$seo_url = '';
		$url_info = parse_url(str_replace('&amp;', '&', $link));
		$data = array();
		parse_str($url_info['query'], $data);
		$route = $data['route'];
		unset($data['route']);
		$url_info['scheme'] = $this->url_sheme;
			if (!$this->valid_server) {
				$url_info['scheme'] = 'http';
			} else {
				foreach ($this->ssl_routes as $ssl_route) {
					if (stripos($route, $ssl_route) === 0) {
						$url_info['scheme'] = 'https';
					}
				}
			}
		switch ($route) {
			case 'product/product':
				if (isset($data['product_id'])) {
					$tmp = $data;
					$data = array();
					if ($this->config->get('config_seo_url_include_path')) {
						$data['path'] = $this->getPathByProduct($tmp['product_id']);
						if (!$data['path']) return $link;
					}
					$data['product_id'] = $tmp['product_id'];
					// --- add valide routes
					foreach($this->valide_routes as $valide_route) {
						if (isset($tmp[$valide_route])) {
							$data[$valide_route] = $tmp[$valide_route];
						}
					}
					// --- add valide routes
				}
				break;
			case 'product/category':
				if (isset($data['path'])) {
					$category = explode('_', $data['path']);
					$category = end($category);
					$data['path'] = $this->getPathByCategory($category);
					if (!$data['path']) return $link;
				}
				break;			
			//blog	
			case 'blog/article':
				if (isset($data['article_id'])) {
					$tmp = $data;
					$data = array();
					if ($this->config->get('config_seo_url_include_path')) {
						$data['blog_category_id'] = $this->getPathByArticle($tmp['article_id']);
						if (!$data['blog_category_id']) return $link;
					}
					$data['article_id'] = $tmp['article_id'];
				}
				break;	
			case 'blog/category':
				if (isset($data['blog_category_id'])) {
					$blog_category_id = explode('_', $data['blog_category_id']);
					$blog_category_id = end($blog_category_id);
					$data['blog_category_id'] = $this->getPathByBlogCategory($blog_category_id);
					if (!$data['blog_category_id']) return $link;
				}
				break;
			//blog	
			case 'product/product/review':
			case 'information/information/info':
			case 'information/information/agree':
				return $link;
				break;
			default:
				break;
		}
		
		
		$link = $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '');
		
		//fix subfolder ($url_info['path'])
		$link .= $url_info['path'] . '?route=' . $route . (count($data) ? '&amp;' . urldecode(http_build_query($data, '', '&amp;')) : '');			
		
		$queries = array();
		foreach ($data as $key => $value) {
			switch ($key) {
				case 'product_id':
				case 'article_id':
				case 'manufacturer_id':
				case 'category_id':
				case 'information_id':
				case 'order_id':
				case 'download_id':
				case 'search':
				case 'sub_category':
				case 'description':
					$queries[] = $key . '=' . $value;
					unset($data[$key]);
					$postfix = 1;
					break;
				case 'path':
					$categories = explode('_', $value);
					foreach ($categories as $category) {
						$queries[] = 'category_id=' . $category;
					}
					unset($data[$key]);
					break;			
				//blog
				case 'blog_category_id':
					$blog_categories = explode('_', $value);
					foreach ($blog_categories as $blog_category) {
						$queries[] = 'blog_category_id=' . $blog_category;
					}
					unset($data[$key]);
					break;
				default:
					break;
				//blog	
			}
		}
		if(empty($queries)) {
			$queries[] = $route;
		}
		$rows = array();
		foreach($queries as $query) {
			if(isset($this->cache_data['queries'][$query])) {
				$rows[] = array('query' => $query, 'keyword' => $this->cache_data['queries'][$query]);
			}
		}
		if(count($rows) == count($queries)) {
			$aliases = array();
			foreach($rows as $row) {
				$aliases[$row['query']] = $row['keyword'];
			}
			foreach($queries as $query) {
				$seo_url .= '/' . rawurlencode($aliases[$query]);
			}
		}
		
		if ($seo_url == '') return $link;
		$seo_url = trim($seo_url, '/');
		//fix subfolder
		$path = rtrim($url_info['path'], '/index.php');

	
		$seo_url = $url_info['scheme'] . '://' . $url_info['host'] . (isset($url_info['port']) ? ':' . $url_info['port'] : '') . $path . '/' . $seo_url;
		if (isset($postfix)) {
			$seo_url .= trim($this->config->get('config_seo_url_postfix'));
		} else {
			$seo_url .= '/';
		}
		//fix subfolder

		
		if(substr($seo_url, -2) == '//') {
			$seo_url = substr($seo_url, 0, -1);
		}
		if (count($data)) {
			$seo_url .= '?' . urldecode(http_build_query($data, '', '&amp;'));
		}
		
		return $seo_url;
	}
	private function getPathByProduct($product_id) {
		$product_id = (int)$product_id;
		if ($product_id < 1) return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('product.seopath');
			if (!is_array($path)) $path = array();
		}
		if (!isset($path[$product_id])) {
			$query = $this->db->query("SELECT category_id FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . $product_id . "' ORDER BY main_category DESC LIMIT 1");
			$path[$product_id] = $this->getPathByCategory($query->num_rows ? (int)$query->row['category_id'] : 0);
			$this->cache->set('product.seopath', $path);
		}
		return $path[$product_id];
	}
	private function getPathByCategory($category_id) {
		$category_id = (int)$category_id;
		if ($category_id < 1) return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('category.seopath');
			if (!is_array($path)) $path = array();
		}
		if (!isset($path[$category_id])) {
			$max_level = 10;
			$sql = "SELECT CONCAT_WS('_'";
			for ($i = $max_level-1; $i >= 0; --$i) {
				$sql .= ",t$i.category_id";
			}
			$sql .= ") AS path FROM " . DB_PREFIX . "category t0";
			for ($i = 1; $i < $max_level; ++$i) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "category t$i ON (t$i.category_id = t" . ($i-1) . ".parent_id)";
			}
			$sql .= " WHERE t0.category_id = '" . $category_id . "'";
			$query = $this->db->query($sql);
			$path[$category_id] = $query->num_rows ? $query->row['path'] : false;
			$this->cache->set('category.seopath', $path);
		}
		return $path[$category_id];
	}	
	//blog	
	private function getPathByBlogCategory($blog_category_id) {
		$blog_category_id = (int)$blog_category_id;
		if ($blog_category_id < 1) return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('blog_category.seopath');
			if (!is_array($path)) $path = array();
		}
		if (!isset($path[$blog_category_id])) {
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
			$path[$blog_category_id] = $query->num_rows ? $query->row['path'] : false;
			$this->cache->set('blog_category.seopath', $path);
		}
		return $path[$blog_category_id];
	}
	
	private function getPathByArticle($article_id) {
		$article_id = (int)$article_id;
		if ($article_id < 1) return false;
		static $path = null;
		if (!is_array($path)) {
			$path = $this->cache->get('article.seopath');
			if (!is_array($path)) $path = array();
		}
		if (!isset($path[$article_id])) {
			$query = $this->db->query("SELECT blog_category_id FROM " . DB_PREFIX . "article_to_blog_category WHERE article_id = '" . $article_id . "' ORDER BY main_blog_category DESC LIMIT 1");
			$path[$article_id] = $this->getPathByBlogCategory($query->num_rows ? (int)$query->row['blog_category_id'] : 0);
			$this->cache->set('article.seopath', $path);
		}
		return $path[$article_id];
	}
	
	//blog
	private function validate() {
		//fix flat link for xml feed
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
		
		//Remove negative page count
		
		if (isset($this->request->get['page'])  ) {
			if((float)$this->request->get['page'] < 1) {
				unset($this->request->get['page']);
			};
		};
		
		if(empty($this->request->get['route'])) {
			$this->request->get['route'] = 'common/home';
		}
		
		
		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return;
		}


		if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == '1' || $_SERVER['HTTPS'])) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on'))) {		
			$url = str_replace('&amp;', '&', $this->config->get('config_ssl') . ltrim($this->request->server['REQUEST_URI'], '/'));
			$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route')), 'SSL'));
		} else {
			$url = str_replace('&amp;', '&',
				substr($this->config->get('config_url'), 0, strpos($this->config->get('config_url'), '/', 10)) // leave only domain
				. $this->request->server['REQUEST_URI']);
			$seo = str_replace('&amp;', '&', $this->url->link($this->request->get['route'], $this->getQueryString(array('route'))));
		}

		if (rawurldecode($url) != rawurldecode($seo)) {
			$this->response->redirect($seo, 301);
		}
	}
	private function getQueryString($exclude = array()) {
		if (!is_array($exclude)) {
			$exclude = array();
			}
		return urldecode(
			http_build_query(
				array_diff_key($this->request->get, array_flip($exclude))
				)
			);
		}
	}