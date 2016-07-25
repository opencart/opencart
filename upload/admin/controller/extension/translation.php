<?php
class ControllerExtensionTranslation extends Controller {
	public function index() {
		$this->load->language('extension/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/translation', 'token=' . $this->session->data['token'], true)
		);

		$data['translations'] = array();
		
		// Make a CURL request
		$curl = curl_init('https://s3.amazonaws.com/opencart-language/' . VERSION . '.json');

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curl);

		if (!$response) {
			$data['warning'] = sprintf($this->language->get('error_api'), curl_error($curl), curl_errno($curl));
		} else {
			$translations = json_decode($response, true);
		}
		
		curl_close($curl);		
		
		$translation_total = count($translations);

		if ($translations) {
			$translations = array_splice($translations, ($page - 1) * $this->config->get('config_limit_admin'), $this->config->get('config_limit_admin'));
			
			foreach ($translations as $translation){
				if (is_dir(DIR_LANGUAGE . strtolower($translation['code']))) {
					$installed = true;
				} else {
					$installed = false;
				}
				
				$data['translations'][] = array(
					'name'      => $translation['name'],
					'image'     => (!$this->request->server['HTTPS'] ? HTTP_CATALOG : HTTPS_CATALOG) . 'image/flags/' . strtolower($translation['code']) . '.png',
					'code'      => $translation['code'],
					'progress'  => $translation['translated_progress'],
					'installed' => $installed
				);
			}
		}
		
		$data['heading_title'] = $this->language->get('heading_title');

        $data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_progress'] = $this->language->get('text_progress');
		$data['text_available'] = $this->language->get('text_available');
		$data['text_crowdin'] = $this->language->get('text_crowdin');
		$data['text_loading'] = $this->language->get('text_loading');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_flag'] = $this->language->get('column_flag');
		$data['column_code'] = $this->language->get('column_code');
		$data['column_progress'] = $this->language->get('column_progress');
        $data['column_action'] = $this->language->get('column_action');

		$data['entry_progress'] = $this->language->get('entry_progress');
		
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		$data['token'] = $this->session->data['token'];

		$pagination = new Pagination();
		$pagination->total = $translation_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/translation', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($translation_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($translation_total - $this->config->get('config_limit_admin'))) ? $translation_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $translation_total, ceil($translation_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/translation', $data));
	}

	public function install() {
		$this->load->language('extension/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		if (!$json) {		
			$json['text'] = $this->language->get('text_download');
					
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/translation/download', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
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
		
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		if (!$json) {
			$directories = array();
					
			$directory = DIR_APPLICATION . 'language/';
	
			if (is_dir($directory . $code) && substr(str_replace('\\', '/', realpath($directory . $code)), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
				$files = glob($directory . '*');
				
				if ($files) {
					$directories = array_merge($directories, $files);
				}			
			}
			
			$directory = DIR_CATALOG . 'language/';
			
			if (is_dir($directory . $code) && substr(str_replace('\\', '/', realpath($directory . $code)), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
				$files = glob($directory . '*');
				
				if ($files) {
					$directories = array_merge($directories, $files);
				}			
			}
			
			$directory = substr(DIR_CATALOG, 0, strrpos(rtrim(DIR_CATALOG, '/'), '/')) . '/install/language/';
	
			if (is_dir($directory . $code) && substr(str_replace('\\', '/', realpath($directory . $code)), 0, strlen($directory)) == $directory) {
				$files = glob($directory . '*');
				
				if ($files) {
					$directories = array_merge($directories, $files);
				}		
			}			
			
			foreach ($directories as $directory) {
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

				if (is_dir($directory)) {
					rmdir($directory);
				}
			}
			
			$json['success'] = $this->language->get('text_uninstalled');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
	
	public function download() {
		$this->load->language('extension/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
						
		if (!$json) {
			$curl = curl_init('https://s3.amazonaws.com/opencart-language/' . VERSION . '/' . $code . '.zip');
			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
			$response = curl_exec($curl);
	
			$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
			
			$header = substr($response, 0, $header_size);
	
	$this->log->write($header);
	
			if (!$response) {
				$json['error'] = sprintf($this->language->get('error_api'), curl_error($curl), curl_errno($curl));
			} else {
				$file = ini_get('upload_tmp_dir') . '/lng-' . $code . '.zip';
		
				$handle = fopen($file, 'w');
		
				flock($handle, LOCK_EX);
		
				fwrite($handle, $response);
		
				fflush($handle);
		
				flock($handle, LOCK_UN);
		
				fclose($handle);
				
				$json['text'] = $this->language->get('text_unzip');
				
				$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/translation/unzip', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
			}
			
			curl_close($curl);	
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function unzip() {
		$this->load->language('extension/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$file = ini_get('upload_tmp_dir') . '/lng-' . $code . '.zip';
		
		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(ini_get('upload_tmp_dir'))) != str_replace('\\', '/', ini_get('upload_tmp_dir'))) {
			$json['error'] = $this->language->get('error_file');
		}
			
		if (!$json) {	
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(ini_get('upload_tmp_dir') . '/lng-' . $code . '/');
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($file);
			
			$json['text'] = $this->language->get('text_move');
				
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/translation/move', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}

	public function move() {
		$this->load->language('extension/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		$directory = ini_get('upload_tmp_dir') . '/lng-' . $code . '/' . VERSION . '/';

		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(ini_get('upload_tmp_dir'))) != str_replace('\\', '/', ini_get('upload_tmp_dir'))) {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

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

			foreach ($files as $file) {
				$destination = substr($file, strlen($directory));
				
				if (substr($destination, 0, 5) == 'admin') {
					$destination = DIR_APPLICATION . substr($destination, 6);
				}

				if (substr($destination, 0, 7) == 'catalog') {
					$destination = DIR_CATALOG . substr($destination, 8);
				}
				
				if (substr($destination, 0, 7) == 'install') {
					$destination = substr(DIR_CATALOG, 0, strrpos(rtrim(DIR_CATALOG, '/'), '/')) . '/install/' . substr($destination, 8);
				}
												
				if (is_dir($file) && !is_dir($destination)) {
					if (!mkdir($destination, 0777)) {
						$json['error'] = sprintf($this->language->get('error_move'), $destination);
					}
				}
				
				if (is_file($file)) {
					if (!rename($file, $destination)) {
						$json['error'] = sprintf($this->language->get('error_move'), $file);
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_db');
				
			$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/translation/db', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function db() {
		$this->load->language('extension/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		if (!$json) {
			$response = file_get_contents('https://s3.amazonaws.com/opencart-language/2.0.0.x.json');
			
			if ($response) {
				$results = json_decode($response, true);
				
				foreach ($results as $result) {
					if ($result['code'] == $code) {  
						$this->load->model('localisation/language');
						
						$language_info = $this->model_localisation_language->getLanguageByCode($code);
						
						if (!$language_info) {
							$language_data = array(
								'name'       => $result['name'],
								'code'       => $code,
								'locale'     => '',
								'sort_order' => 0,
								'status'     => 1
							);
							
							$this->model_localisation_language->addLanguage($language_data);
						}
					}
				}
				
				$json['text'] = $this->language->get('text_remove');
				
				$json['next'] = str_replace('&amp;', '&', $this->url->link('extension/translation/remove', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
			} else {
				$json['error'] = $this->language->get('error_db');
			}	
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function remove() {
		$this->load->language('extension/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		$directory = ini_get('upload_tmp_dir') . '/lng-' . $code;

		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(ini_get('upload_tmp_dir'))) != str_replace('\\', '/', ini_get('upload_tmp_dir'))) {
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

			if (is_dir($directory)) {
				rmdir($directory);
			}
						
			$json['success'] = $this->language->get('text_installed');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
}