<?php
class ControllerExtensionStore extends Controller {
	public function index() {
		$this->load->language('extension/store');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['extension_category_id'])) {
			$extension_category_id = $this->request->get['extension_category_id'];
		} else {
			$extension_category_id = null;
		}

		if (isset($this->request->get['filter_search'])) {
			$filter_search = $this->request->get['filter_search'];
		} else {
			$filter_search = null;
		}

		if (isset($this->request->get['filter_license'])) {
			$filter_license = $this->request->get['filter_license'];
		} else {
			$filter_license = null;
		}

		if (isset($this->request->get['filter_download_id'])) {
			$filter_download_id = $this->request->get['filter_download_id'];
		} else {
			$filter_download_id = null;
		}

		if (isset($this->request->get['filter_username'])) {
			$filter_username = $this->request->get['filter_username'];
		} else {
			$filter_username = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'e.date_added';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}
				
		$url = '';

		if (isset($this->request->get['extension_category_id'])) {
			$url .= '&extension_category_id=' . $this->request->get['extension_category_id'];
		}

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_download_id'])) {
			$url .= '&filter_download_id=' . $this->request->get['filter_download_id'];
		}

		if (isset($this->request->get['filter_username'])) {
			$url .= '&filter_username=' . $this->request->get['filter_username'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/store', 'token=' . $this->session->data['token'], true)
		);
		
		$data['token'] = $this->session->data['token'];

		$data['extensions'] = array();
		
		//$url .= '&api_key=' . $this->config->get('config_api_key'); 
		//$url .= '&store=' . $this->request->server['HTTP_HOST'];
		//$url .= '&language=' . $this->config->get('config_language');
		//$url .= '&currency=' . $this->config->get('config_currency');
		//$url .= '&version=' . $this->config->get('config_currency');
		
		$curl = curl_init(HTTP_TEST . 'index.php?route=extension/extension' . $url);
		
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_PORT, 80);
			
		$response = curl_exec($curl);
		
		curl_close($curl);

		$response_info = json_decode($response, true);

		if ($response_info) {
			$extension_total = $response_info['total'];
			
			$results = $response_info['extensions'];
		} else {
			$extension_total = 0;
			
			$results = array();
		}

		foreach ($results as $result) {
			$data['extensions'][] = array(
				'name'          => $result['name'],
				'image'         => $result['image'],
				'license'       => $result['license'],
				'price'         => $result['price'],
				'rating'        => $result['rating'],
				'comment_total' => $result['comment_total'],
				'href'          => $this->url->link('extension/store/info', 'token=' . $this->session->data['token'] . '&extension_id=' . $result['extension_id'] . $url, true)
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
        
		$data['text_list'] = $this->language->get('text_list');
		$data['text_sort'] = $this->language->get('text_sort');

		$url = '';

		if (isset($this->request->get['extension_category_id'])) {
			$url .= '&extension_category_id=' . $this->request->get['extension_category_id'];
		}

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_download_id'])) {
			$url .= '&filter_download_id=' . $this->request->get['filter_download_id'];
		}

		if (isset($this->request->get['filter_username'])) {
			$url .= '&filter_username=' . $this->request->get['filter_username'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sorts'] = array();
		
		$data['sorts'][] = array(
			'text' => $this->language->get('text_sort_date_added'),
			'href' => $this->url->link('extension/store', 'sort=e.date_added' . $url)
		);

		$data['sorts'][] = array(
			'text' => $this->language->get('text_sort_date_modified'),
			'href' => $this->url->link('extension/store', 'sort=e.date_modified' . $url)
		);
				
		$data['sorts'][] = array(
			'text' => $this->language->get('text_sort_name'),
			'href' => $this->url->link('extension/store', 'sort=e.name' . $url)
		);
		
		$data['sorts'][] = array(
			'text' => $this->language->get('text_sort_rating'),
			'href' => $this->url->link('extension/store', 'sort=rating' . $url)
		);		
		
		$data['sorts'][] = array(
			'text' => $this->language->get('text_sort_downloaded'),
			'href' => $this->url->link('extension/store', 'sort=e.downloaded' . $url)
		);	
			
		$data['sorts'][] = array(
			'text' => $this->language->get('text_sort_price'),
			'href' => $this->url->link('extension/store', 'sort=e.price' . $url)
		);	

		$url = '';

		if (isset($this->request->get['extension_category_id'])) {
			$url .= '&extension_category_id=' . $this->request->get['extension_category_id'];
		}

		if (isset($this->request->get['filter_search'])) {
			$url .= '&filter_search=' . $this->request->get['filter_search'];
		}

		if (isset($this->request->get['filter_license'])) {
			$url .= '&filter_license=' . $this->request->get['filter_license'];
		}

		if (isset($this->request->get['filter_download_id'])) {
			$url .= '&filter_download_id=' . $this->request->get['filter_download_id'];
		}

		if (isset($this->request->get['filter_username'])) {
			$url .= '&filter_username=' . $this->request->get['filter_username'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		$pagination = new Pagination();
		$pagination->total = $extension_total;
		$pagination->page = $page;
		$pagination->limit = 12;
		$pagination->url = $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();
		
		$data['sort'] = $sort;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/store_list', $data));
	}
	
	public function info() {
		$url  = '&api_key=' . $this->config->get('config_api_key'); 
		$url .= '&store=' . $this->request->server['HTTP_HOST'];
		$url .= '&language=' . $this->config->get('config_language');
		$url .= '&currency=' . $this->config->get('config_currency');

		$curl = curl_init(HTTP_TEST . 'index.php?route=extension/extension/info&extension_id=' . $this->request->get['extension_id'] . $url);
				
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_PORT, 80);
			
		$response = curl_exec($curl);
		
		if (!$response) {
			$json['error'] = sprintf($this->language->get('error_api'), curl_error($curl), curl_errno($curl));
		}
		
		curl_close($curl);
		
		$response_info = json_decode($response, true);
		
		if ($response_info) {
			$this->load->language('extension/store');
	
			$this->document->setTitle($this->language->get('heading_title'));
							
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_info'] = $this->language->get('text_info');
			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_price'] = $this->language->get('text_price');
			$data['text_rating'] = $this->language->get('text_rating');
			$data['text_developed'] = $this->language->get('text_developed');
			$data['text_support'] = $this->language->get('text_support');
			$data['text_documentation'] = $this->language->get('text_documentation');
			$data['text_compatibility'] = $this->language->get('text_compatibility');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_date_modified'] = $this->language->get('text_date_modified');

			$data['button_buy'] = $this->language->get('button_buy');
			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['token'] = $this->session->data['token'];
			
			$url = '';
			
			if (isset($this->request->get['extension_category_id'])) {
				$url .= '&extension_category_id=' . $this->request->get['extension_category_id'];
			}
	
			if (isset($this->request->get['filter_search'])) {
				$url .= '&filter_search=' . $this->request->get['filter_search'];
			}
	
			if (isset($this->request->get['filter_license'])) {
				$url .= '&filter_license=' . $this->request->get['filter_license'];
			}
	
			if (isset($this->request->get['filter_download_id'])) {
				$url .= '&filter_download_id=' . $this->request->get['filter_download_id'];
			}
	
			if (isset($this->request->get['filter_username'])) {
				$url .= '&filter_username=' . $this->request->get['filter_username'];
			}
	
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}
			
			$data['cancel'] = $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['breadcrumbs'] = array();
	
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);
	
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/store', 'token=' . $this->session->data['token'], true)
			);

			$this->load->helper('bbcode');
			
			$data['banner'] = $response_info['banner'];
			
			$data['extension_id'] = (int)$this->request->get['extension_id'];
			$data['name'] = $response_info['name'];
			$data['description'] = nl2br(bbcode_decode($response_info['description']));
			$data['price'] = $this->currency->format($response_info['price'], $this->config->get('config_currency'));
			$data['license'] = $response_info['license'];
			$data['member'] = $response_info['member'];
			$data['filter_username'] = $this->url->link('extension/store', 'token=' . $this->session->data['token'] . '&filter_username=' . $response_info['member']);
			$data['rating'] = $response_info['rating'];
			$data['downloaded'] = $response_info['downloaded'];
			$data['sales'] = $response_info['sales'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($response_info['date_added']));
			$data['date_modified'] = date($this->language->get('date_format_short'), strtotime($response_info['date_modified']));
			$data['comment_total'] = $response_info['comment_total'];
			
			$compatibility = array();
			
			$data['downloads'] = array();
			
			if ($response_info['downloads']) {
				//print_r($response_info['downloads']);
				
				foreach ($response_info['downloads'] as $result) {
					$data['downloads'][] = array(
						'extension_download_id' => $result['extension_download_id'],
						'name'                  => $result['name'],
						'date_added'            => date($this->language->get('short_date'), strtotime($result['date_added'])),
					);	
					
					$compatibility = array_merge($compatibility, $result['compatibility']);
				}
			}
			
			$data['compatibility'] = implode(', ', $compatibility);
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('extension/store_info', $data));			
		} else {
			return new Action('error/not_found');
		}	
	}
	
	public function install() {
		$this->load->language('extension/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
				
		if (!$json) {		
			$json['text'] = $this->language->get('text_download');
					
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/download', 'token=' . $this->session->data['token'] . '&extension_download_id=' . $extension_download_id, true));		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));				
	}
	
	public function uninstall() {
		$this->load->language('extension/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (!empty(trim($this->request->get['code']))) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}		
	
		if (!$json) {
			$directories = array();
					
			$directory = DIR_APPLICATION . 'language';
	
			if (is_dir($directory . '/' . $code) && substr(str_replace('\\', '/', realpath($directory . '/' . $code)), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
				$directories[] = $directory . '/' . $code . '/';
			}
			
			$directory = DIR_CATALOG . 'language';
			
			if (is_dir($directory . '/' . $code) && substr(str_replace('\\', '/', realpath($directory . '/' . $code)), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
				$directories[] = $directory . '/' . $code . '/';
			}
			
			$directory = substr(DIR_CATALOG, 0, strrpos(rtrim(DIR_CATALOG, '/'), '/')) . '/install/language/';
	
			if (is_dir($directory . '/' . $code) && substr(str_replace('\\', '/', realpath($directory . '/' . $code)), 0, strlen($directory)) == $directory) {
				$directories[] = $directory . '/' . $code . '/';
			}			
			
			foreach ($directories as $directory) {
				// Get a list of files ready to upload
				$files = array();

				$path = array($directory . '/');

				while (count($path) != 0) {
					$next = array_shift($path);

					// We have to use scandir function because glob will not pick up dot files.
					foreach (array_diff(scandir($next), array('.', '..')) as $file) {
						$file = $next . '/' . $file;

						if (is_dir($file)) {
							$path[] = $file;
						}

						$files[] = $file;
					}
				}

				rsort($files);

				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}

				if (is_dir($directory)) {
					rmdir($directory);
				}
			}
			
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguageByCode($code);	
			
			if ($language_info) {
				$this->model_localisation_language->deleteLanguage($language_info['language_id']);
			}				
			
			$json['text'] = $this->language->get('text_uninstalled');

		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
			
	public function download() {
		$this->load->language('extension/store');

		$json = array();
				
		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (!$json) {
			if (isset($this->request->get['extension_download_id'])) {
				$extension_download_id = $this->request->get['extension_download_id'];
			} else {
				$extension_download_id = 0;
			}
			
			/*
			$curl = curl_init(HTTP_TEST . 'index.php?route=extension/extension/download&api_key=' . $this->config->get('config_api_key') .  '&extension_download_id=' . $extension_download_id);
			//$curl = curl_init('http://localhost/test/test.ocmod.zip');
			
			
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_PORT, 80);
					
			$response = curl_exec($curl);
			
			curl_close($curl);
		
			$response_info = json_decode($response, true);
			*/
			
			//if (isset($response_info['download'])) {
				$curl = curl_init('http://localhost/test/test.ocmod.zip');
				
				//$curl = curl_init($response_info['download']);
				
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($curl, CURLOPT_PORT, 80);
						
				$response = curl_exec($curl);
			
				curl_close($curl);
			
				$file = tempnam(ini_get('upload_tmp_dir'), 'ext');
			
				$handle = fopen($file, 'w');
				
				fwrite($handle, $response);
		
				fclose($handle);
				
				$json['text'] = $this->language->get('text_download');
				
				$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/unzip', 'token=' . $this->session->data['token'] . '&download=' . basename($file, '.tmp'), true));		
			//} else {
			//	$json['error'] = $this->language->get('error_download');
			//}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
		
	public function unzip() {
		$this->load->language('extension/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if ($this->request->get['download']) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}		

		// Sanitize the filename
		$directory = ini_get('upload_tmp_dir');

		if (!is_file($directory . '/' . $download . '.tmp') || substr(str_replace('\\', '/', realpath($directory . '/' . $download . '.tmp')), 0, strlen($directory)) != str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($directory . '/' . $download . '.tmp')) {
				$zip->extractTo($directory . '/' . $download);
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($directory . '/' . $download . '.tmp');
			
			$json['text'] = $this->language->get('text_unzip');
			
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/xml', 'token=' . $this->session->data['token'] . '&download=' . $download, true));		
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function xml() {
		$this->load->language('extension/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if ($this->request->get['download']) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}		
				
		$directory = ini_get('upload_tmp_dir');

		if (!is_file($directory . '/' . $download . '/install.xml') || substr(str_replace('\\', '/', realpath($directory . '/' . $download . '/install.xml')), 0, strlen($directory)) != str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_xml');
		}

		if (!$json) {
			$this->load->model('extension/modification');

			// If xml file just put it straight into the DB
			$xml = file_get_contents($directory . '/' . $download . '/install.xml');

			if ($xml) {
				try {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($xml);

					$name = $dom->getElementsByTagName('name')->item(0);

					if ($name) {
						$name = $name->nodeValue;
					} else {
						$name = '';
					}

					$code = $dom->getElementsByTagName('code')->item(0);

					if ($code) {
						$code = $code->nodeValue;

						// Check to see if the modification is already installed or not.
						$modification_info = $this->model_extension_modification->getModificationByCode($code);

						if ($modification_info) {
							$json['error'] = sprintf($this->language->get('error_exists'), $modification_info['name']);
						}
					} else {
						$json['error'] = $this->language->get('error_code');
					}

					$author = $dom->getElementsByTagName('author')->item(0);

					if ($author) {
						$author = $author->nodeValue;
					} else {
						$author = '';
					}

					$version = $dom->getElementsByTagName('version')->item(0);

					if ($version) {
						$version = $version->nodeValue;
					} else {
						$version = '';
					}

					$link = $dom->getElementsByTagName('link')->item(0);

					if ($link) {
						$link = $link->nodeValue;
					} else {
						$link = '';
					}

					$modification_data = array(
						'name'    => $name,
						'code'    => $code,
						'author'  => $author,
						'version' => $version,
						'link'    => $link,
						'xml'     => $xml,
						'status'  => 1
					);

					if (!$json) {
						$this->model_extension_modification->addModification($modification_data);
						
						$json['text'] = $this->language->get('text_xml');
						
						$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/move', 'token=' . $this->session->data['token'] . '&download=' . $download, true));		
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
	
	public function move() {
		$this->load->language('extension/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['download'])) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}
			
		$directory = ini_get('upload_tmp_dir');

		if (!is_dir($directory . '/' . $download . '/upload/') || substr(str_replace('\\', '/', realpath($directory . '/' . $download . '/upload/')), 0, strlen($directory)) != str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_directory');
		}
		
		$files = array();
		
		if (!$json) {
			// Get a list of files ready to upload
			$path = array($directory . '/' . $download . '/upload/*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					$files[] = $file;
				}
			}
		}
		
		// First we need to do some checks
		foreach ($files as $file) {
			$destination = substr($file, strlen($directory . '/' . $download . '/upload/'));
			
			// Check if the file is not going into an allowed directory
 			$allowed = array(
				'admin/controller/extension/',
				'admin/language/extension/',
				'admin/model/extension/',
				'admin/view/template/extension/',
				'catalog/controller/extension/',
				'catalog/language/extension/',
				'catalog/model/extension/',
				'catalog/view/theme/'
			);
			
			for ($i = 0; $i < count($allowed); $i++) {
				if (substr(str_replace('\\', '/', realpath($destination)), 0, strlen($allowed[$i])) != $path) {
					$json['error'] = sprintf($this->language->get('error_allowed'), $destination);
					
					break;
				}
			}
						
			// Check if the copy location exists or not	
			if (substr($destination, 0, 5) == 'admin') {
				$destination = DIR_APPLICATION . substr($destination, 6);
			}

			if (substr($destination, 0, 7) == 'catalog') {
				$destination = DIR_CATALOG . substr($destination, 8);
			}

			if (substr($destination, 0, 5) == 'image') {
				$destination = DIR_IMAGE . substr($destination, 6);
			}

			if (substr($destination, 0, 6) == 'system') {
				$destination = DIR_SYSTEM . substr($destination, 7);
			}
			
			if (is_file($destination)) {
				$json['error'] = sprintf($this->language->get('error_exists'), $destination);
				
				break;
			}
		}

		if (!$json) {
			foreach ($files as $file) {
				$destination = substr($file, strlen($directory));
	
				if (substr($destination, 0, 5) == 'admin') {
					$destination = DIR_APPLICATION . substr($destination, 5);
				}
	
				if (substr($destination, 0, 7) == 'catalog') {
					$destination = DIR_CATALOG . substr($destination, 7);
				}
	
				if (substr($destination, 0, 5) == 'image') {
					$destination = DIR_IMAGE . substr($destination, 5);
				}
	
				if (substr($destination, 0, 6) == 'system') {
					$destination = DIR_SYSTEM . substr($destination, 6);
				}

				if (!is_dir($destination)) {
					if (!mkdir($destination, 0777)) {
						$json['error'] = sprintf($this->language->get('error_directory'), $destination);
					} else {
						$this->model_extension_install->addPath($code, $destination);
					}
				}
			
				if (!is_file($file)) {
					if (!rename($file, $destination)) {
						$json['error'] = sprintf($this->language->get('error_file'), $file);
					} else {
						$this->model_extension_install->addPath($code, $destination);
					}
				}
			}
		}
		
		if (!$json) {
			$json['text'] = $this->language->get('text_db');
				
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/db', 'token=' . $this->session->data['token'] . '&download=' . $download, true));		
		}
			
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
		
	public function sql() {
		$this->load->language('extension/store');

		$json = array();
		
		if ($this->request->get['download']) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}
				
		$directory = ini_get('upload_tmp_dir');
		
		if (is_file($directory . '/' . $download . '/install.sql') && substr(str_replace('\\', '/', realpath($directory . '/' . $download . '/install.sql')), 0, strlen($directory)) != str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_file');
		}
		
		if (!$json) {
			$lines = file($file);

			if ($lines) {
				try {
					foreach ($lines as $line) {
						if (substr($line, 0, 14) == 'CREATE TABLE' || substr($line, 0, 11) == 'INSERT INTO') {
							$sql = '';
							
							$start = true;
						}
						
						if ($start) {
							$sql .= $line;
						}
						
						if ($start && substr($line, -2) == ";\n") {
							$this->db->query(str_replace(" `oc_", " `" . DB_PREFIX, substr($sql, 0, strlen($sql) -2)));
							
							$start = false;
						}
							
						$i++;
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
			
			$json['text'] = $this->language->get('text_sql');
			
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/store/remove', 'token=' . $this->session->data['token'] . '&download=' . $download, true));		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function remove() {
		$this->load->language('extension/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if ($this->request->get['download']) {
			$download = $this->request->get['download'];
		} else {
			$download = '';
		}
		
		$directory = ini_get('upload_tmp_dir');
		
		if (!is_dir($directory . '/' . $download) || substr(str_replace('\\', '/', realpath($directory . '/' . $download)), 0, strlen($directory)) != str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory . '/' . $download . '/');

			while (count($path) != 0) {
				$next = array_shift($path);

				// We have to use scandir function because glob will not pick up dot files.
				foreach (array_diff(scandir($next), array('.', '..')) as $file) {
					$file = $next . '/' . $file;

					if (is_dir($file)) {
						$path[] = $file;
					}

					$files[] = $file;
				}
			}

			rsort($files);

			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				} elseif (is_dir($file)) {
					rmdir($file);
				}
			}
			
			if (is_file($directory . '/' . $download . '.tmp')) {
				unlink($directory . '/' . $download . '.tmp');
			}

			if (is_dir($directory . '/' . $download)) {
				rmdir($directory . '/' . $download);
			}
			
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}
