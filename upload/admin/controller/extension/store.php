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
		
		//echo $url;
		//echo $response;
		
		curl_close($curl);
		
		$response_info = json_decode($response, true);
		
		//print_r($response_info);
		
		if ($response_info) {
			$this->load->language('extension/store');
	
			$this->document->setTitle($this->language->get('heading_title'));			
							
			$data['heading_title'] = $this->language->get('heading_title');
			
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
			$data['date_added'] = date('j F Y', strtotime($response_info['date_added']));
			$data['date_modified'] = date('j F Y', strtotime($response_info['date_modified']));
			$data['comment_total'] = $response_info['comment_total'];
			
			$data['downloads'] = array();
			
			if ($response_info['downloads']) {
				foreach ($response_info['downloads'] as $result) {
					$data['downloads'][] = array(
						'name'          => $result['name'],
						'compatibility' => $result['compatibility'],
						'date_added'    => date('d/m/Y', strtotime($result['date_added'])),
						'href'          => $this->url->link('extension/store/download', 'token=' . $this->session->data['token'] . '&extension_download_id=' . $result['extension_download_id'], true)
					);				
				}
			}
			
			$data['cancel'] = $this->url->link('extension/store', 'token=' . $this->session->data['token'] . $url, true);
			
			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');
			
			$this->response->setOutput($this->load->view('extension/store_info', $data));			
		} else {
			return new Action('error/not_found');
		}	
	}
	
	public function comment() {
	
	}
	
	public function download() {
		$this->load->language('extension/store');

		$json = array();
				
		if ($this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if ($json) {
			if (isset($this->request->get['extension_download_id'])) {
				$extension_download_id = $this->request->get['extension_download_id'];
			} else {
				$extension_download_id = 0;
			}
			
			$curl = curl_init(HTTP_TEST . 'index.php?route=extension/extension/download&api_key=' . $this->config->get('config_api_key') .  '&extension_download_id=' . $extension_download_id);
			
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_PORT, 80);
					
			$response = curl_exec($curl);
		
			curl_close($curl);
		
			$json = json_decode($response);
		
			if ($json['download']) {
				$curl = curl_init($json['download']);
				
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($curl, CURLOPT_PORT, 80);
						
				$response = curl_exec($curl);
			
				curl_close($curl);
			
				$filename = tempnam(ini_get('upload_tmp_dir'), 'ext');
			
				$handle = fopen($filename, 'w');
				
				fwrite($handle, json_encode($value));
		
				fflush($handle);
		
				fclose($handle);
				
				$json['next'] = $this->url->link('extension/store/unzip', 'token=' . $this->session->data['token'] . '&download=' . $filename, true);			
			} else {
				$json['error'] = '';
			}
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
		$file = ini_get('upload_tmp_dir') . '/' . $download;

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(ini_get('upload_tmp_dir'))) != ini_get('upload_tmp_dir')) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(ini_get('upload_tmp_dir') . $download);
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($file);
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
		
		// Sanitize the filename
		$file = ini_get('upload_tmp_dir') . '/' . $download;

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(ini_get('upload_tmp_dir'))) != ini_get('upload_tmp_dir')) {
			$json['error'] = $this->language->get('error_file');
		}
		
		$directory = ini_get('upload_tmp_dir') . '/' . $download;

		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(ini_get('upload_tmp_dir'))) != ini_get('upload_tmp_dir')) {
			$json['error'] = $this->language->get('error_directory');
		}
		
		$files = array();
		
		if (!$json) {
			// Get a list of files ready to upload
			$path = array($directory . '*');

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
			
		foreach ($files as $file) {
			$destination = substr($file, strlen($directory));
			
			// Upload everything in the upload directory
			// Many people rename their admin folder for security purposes which I believe should be an option during installation just like setting the db prefix.
			// the following code would allow you to change the name of the following directories and any extensions installed will still go to the right directory.
			if (substr($destination, 0, 5) == 'admin') {
				$destination = basename(DIR_APPLICATION) . substr($destination, 5);
			}

			if (substr($destination, 0, 7) == 'catalog') {
				$destination = basename(DIR_CATALOG) . substr($destination, 7);
			}

			if (substr($destination, 0, 5) == 'image') {
				$destination = basename(DIR_IMAGE) . substr($destination, 5);
			}

			if (substr($destination, 0, 6) == 'system') {
				$destination = basename(DIR_SYSTEM) . substr($destination, 6);
			}
			
			// Check if the file exists or not.	
 			$allowed = array(
				DIR_APPLICATION . 'controller/extension/',
				DIR_APPLICATION . 'model/extension/',
				DIR_APPLICATION . 'view/template/extension/',
				DIR_CATALOG . 'controller/extension/',
				DIR_CATALOG . 'model/extension/',
				DIR_CATALOG . 'view/theme/'
			);
			
			if (is_file($destination)) {
				$json['error'] = sprintf($this->language->get('error_file'), $destination);
				
				break;
			}
			
			// Check if the copy location exists or not				
			if (!in_array(substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD)), $allowed)) {
				$json['error'] = $this->language->get('error_file_exists');
				
				break;
			}
			
			// Check if the file exsits or not.				
			if (is_dir($destination)) {
				$json['error'] = sprintf($this->language->get('error_directory'), $destination);
				
				break;
			}
			
			// Check if the copy location exists or not				
			if (is_dir($destination)) {
				$json['error'] = sprintf($this->language->get('error_directory_exists'), $destination);
				
				break;
			}								
		}
	
		if (!$json) {
			foreach ($files as $file) {
				$destination = substr($file, strlen($directory));
	
				// Upload everything in the upload directory
				// Many people rename their admin folder for security purposes which I believe should be an option during installation just like setting the db prefix.
				// the following code would allow you to change the name of the following directories and any extensions installed will still go to the right directory.
				if (substr($destination, 0, 5) == 'admin') {
					$destination = basename(DIR_APPLICATION) . substr($destination, 5);
				}
	
				if (substr($destination, 0, 7) == 'catalog') {
					$destination = basename(DIR_CATALOG) . substr($destination, 7);
				}
	
				if (substr($destination, 0, 5) == 'image') {
					$destination = basename(DIR_IMAGE) . substr($destination, 5);
				}
	
				if (substr($destination, 0, 6) == 'system') {
					$destination = basename(DIR_SYSTEM) . substr($destination, 6);
				}

				if (is_dir($file) && !is_dir($destination)) {
					if (!mkdir($destination, 0777)) {
						$json['error'] = sprintf($this->language->get('error_directory'), $destination);
					}
				}
			
				if (is_file($file)) {
					if (!rename($file, $destination)) {
						$json['error'] = sprintf($this->language->get('error_file'), $file);
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function sql() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . $this->request->post['path'] . '/install.sql';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$lines = file($file);

			if ($lines) {
				try {
					$sql = '';

					foreach ($lines as $line) {
						if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
							$sql .= $line;

							if (preg_match('/;\s*$/', $line)) {
								$sql = str_replace(" `oc_", " `" . DB_PREFIX, $sql);

								$this->db->query($sql);

								$sql = '';
							}
						}
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function xml() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . $this->request->post['path'] . '/install.xml';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$this->load->model('extension/modification');

			// If xml file just put it straight into the DB
			$xml = file_get_contents($file);

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
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
	
	public function remove() {
		$this->load->language('extension/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_UPLOAD . $this->request->post['path'];
		
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory);

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

			if (file_exists($directory)) {
				rmdir($directory);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}
