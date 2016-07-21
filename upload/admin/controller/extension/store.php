<?php
class ControllerExtensionStore extends Controller {
	public function index() {
		$this->load->language('extension/store');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/store', 'token=' . $this->session->data['token'], true)
		);
		
		$data['heading_title'] = $this->language->get('heading_title');
        
		$data['text_list'] = $this->language->get('text_list');

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/store_list', $data));
	}
	
	public function store() {
		$this->load->language('extension/store');

		$url = '?api_key=' . $this->config->get('config_api_key'); 
		$url .= '&store=' . $this->request->server['HTTP_HOST'];
		$url .= '&language=' . $this->config->get('config_language');
		$url .= '&currency=' . $this->config->get('config_currency');
		$url .= '&ssl=true';
		
		if (isset($this->request->get['call'])) {
			$url .= '&route=' . $this->request->get['call'];
		} else {
			$url .= '&route=extension/extension';
		}
				
		if (isset($this->request->get['extension_id'])) {
			$url .= '&extension_id=' . $this->request->get['extension_id'];
		}
				
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . $this->request->get['search'];
		}		
		
		if (isset($this->request->get['tags'])) {
			$url .= '&tags=' . $this->request->get['tags'];
		}		
		
		//http://localhost/opencart-dev/upload/admin/index.php?route=extension/store&token=KGcMIrIZDOqsV1ux6g3qGbzEkEXenM8v&call=extension/extension/info&extension_id=26943
		
		echo $url;
		
		$curl = curl_init(HTTP_TEST . 'index.php' . $url);
				
		curl_setopt($curl, CURLOPT_PORT, 443);
		//curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_PORT, 80);
			
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		$response = str_replace('{{ store }}', !$this->request->server['HTTPS'] ? HTTP_SERVER : HTTPS_SERVER, $response);
		$response = str_replace('{{ token }}', $this->session->data['token'], $response);
		
		$this->response->setOutput($response);				
	}
	
	public function download() {
		$this->load->language('extension/store');

		$json = array();
				
		if (!$this->user->hasPermission('modify', 'extension/store')) {
			$json['error'] = $this->language->get('error_permission');
		}		
				
		$url = '';
		
		$curl = curl_init('https://extension.opencart.com');
		
		$request  = '?api_key=' . $this->config->get('config_api_key'); 
		
		if (isset($this->request->get['sort'])) {
			$request .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$request .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$request .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['search'])) {
			$request .= '&search=' . $this->request->get['search'];
		}		
		
		if (isset($this->request->get['tags'])) {
			$request .= '&tags=' . $this->request->get['tags'];
		}		
				
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
				
		$response = curl_exec($curl);

		if (!$response) {
			$json['error'] = curl_error($curl) . '(' . curl_errno($curl) . ')';
		} else {
			$json = json_decode($response);
		}
		
		curl_close($curl);
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function unzip() {
		$this->load->language('extension/store');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Sanitize the filename
		$file = DIR_UPLOAD . $this->request->post['path'] . '/upload.zip';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(DIR_UPLOAD . $this->request->post['path']);
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

		$files = array();

		$directory = DIR_UPLOAD . $this->request->post['path'] . '/upload/';

		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_directory');
		} else {
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
