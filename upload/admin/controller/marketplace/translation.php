<?php
class ControllerMarketplaceTranslation extends Controller {
	public $error = array();
	
	public function index() {
		$this->load->language('marketplace/translation');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/translation');
		
		$this->getList();
	}
	
	public function refresh() {
		$this->load->language('marketplace/translation');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/translation');
		
		if ($this->validate()) {
			$this->model_setting_translation->clear();
			
			// Make a CURL request
			$curl = curl_init('https://s3.amazonaws.com/opencart-language/' . VERSION . '.json');
	
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
			$response = curl_exec($curl);
	
			if ($response) {
				$translations = json_decode($response, true);
			} else {
				$translations = array();
			}
			
			curl_close($curl);
			
			if ($translations) {
				foreach ($translations as $translation) {
					$this->model_setting_translation->addTranslation($translation);
				}
			}
		}
		
		$this->getList();
	}	
	
	public function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';

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
			'href' => $this->url->link('marketplace/translation', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['refresh'] = $this->url->link('marketplace/translation/refresh', 'token=' . $this->session->data['token'] . $url, true);

		$data['translations'] = array();

		$translation_total = $this->model_setting_translation->getTotalTranslations();

		$results = $this->model_setting_translation->getTranslations(($page - 1) * $this->config->get('config_limit_admin'), $this->config->get('config_limit_admin'));
			
		foreach ($results as $result){
			if (is_dir(DIR_LANGUAGE . $result['code'])) {
				$installed = true;
			} else {
				$installed = false;
			}
			
			$data['translations'][] = array(
				'name'      => $result['name'],
				'image'     => ($this->request->server['HTTPS'] ? 'https://' : 'http://') . 's3.amazonaws.com/opencart-language/flags/' . strtolower($result['code']) . '.png',
				'code'      => $result['code'],
				'progress'  => $result['progress'],
				'installed' => $installed
			);
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
		
		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} elseif (!$translation_total) {
			$data['error_warning'] = $this->language->get('error_refresh');
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$pagination = new Pagination();
		$pagination->total = $translation_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('marketplace/translation', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($translation_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($translation_total - $this->config->get('config_limit_admin'))) ? $translation_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $translation_total, ceil($translation_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/translation', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'marketplace/translation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->language('marketplace/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('setting/translation');
			
		$translation_info = $this->model_setting_translation->getTranslationByCode($code);		
		
		if (!$translation_info) {
			$json['error'] = $this->language->get('error_code');
		}
				
		if (!$json) {		
			$json['text'] = $this->language->get('text_download');
					
			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/translation/download', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));				
	}
	
	public function uninstall() {
		$this->load->language('marketplace/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}		

		$this->load->model('setting/translation');
			
		$translation_info = $this->model_setting_translation->getTranslationByCode($code);		
		
		if (!$translation_info) {
			$json['error'] = $this->language->get('error_code');
		}
				
		if ($this->config->get('config_language') == $code) {
			$json['error'] = $this->language->get('error_default');
		}
		
		if ($this->config->get('config_admin_language') == $code) {
			$json['error'] = $this->language->get('error_admin');
		}
	
		if (!$json) {
			$directories = array();
					
			$directory = DIR_APPLICATION . 'language/';
	
			if (is_dir($directory . $code) && substr(str_replace('\\', '/', realpath($directory . $code)), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
				$directories[] = $directory . $code . '/';
			}
			
			$directory = DIR_CATALOG . 'language/';
			
			if (is_dir($directory . $code) && substr(str_replace('\\', '/', realpath($directory . $code)), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
				$directories[] = $directory . $code . '/';
			}
			
			$directory = substr(DIR_CATALOG, 0, strrpos(rtrim(DIR_CATALOG, '/'), '/')) . '/install/language/';
	
			if (is_dir($directory . $code) && substr(str_replace('\\', '/', realpath($directory . $code)), 0, strlen($directory)) == $directory) {
				$directories[] = $directory . $code . '/';
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
			
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguageByCode($code);	
			
			if ($language_info) {
				$this->model_localisation_language->deleteLanguage($language_info['language_id']);
			}				
			
			$json['success'] = $this->language->get('text_uninstalled');

		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
	
	public function download() {
		$this->load->language('marketplace/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('setting/translation');
			
		$translation_info = $this->model_setting_translation->getTranslationByCode($code);		
		
		if (!$translation_info) {
			$json['error'] = $this->language->get('error_code');
		}
						
		if (!$json) {
			$curl = curl_init('https://s3.amazonaws.com/opencart-language/' . VERSION . '/' . $code . '.zip');
			
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
			$response = curl_exec($curl);
		
			if (!$response || (substr($response, 0, 5) == '<?xml')) {
				$json['error'] = sprintf($this->language->get('error_api'), curl_error($curl), curl_errno($curl));
			} elseif ((substr($response, 0, 5) == '<?xml')) {
				$json['error'] = $this->language->get('error_s3');
			} else {
				$file = ini_get('upload_tmp_dir') . '/lng-' . $code . '.zip';
		
				$handle = fopen($file, 'w');
		
				flock($handle, LOCK_EX);
		
				fwrite($handle, $response);
		
				fflush($handle);
		
				flock($handle, LOCK_UN);
		
				fclose($handle);
				
				$json['text'] = $this->language->get('text_unzip');
				
				$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/translation/unzip', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
			}
			
			curl_close($curl);	
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function unzip() {
		$this->load->language('marketplace/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		$this->load->model('setting/translation');
			
		$translation_info = $this->model_setting_translation->getTranslationByCode($code);		
		
		if (!$translation_info) {
			$json['error'] = $this->language->get('error_code');
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
				
			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/translation/move', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}

	public function move() {
		$this->load->language('marketplace/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('setting/translation');
			
		$translation_info = $this->model_setting_translation->getTranslationByCode($code);		
		
		if (!$translation_info) {
			$json['error'] = $this->language->get('error_code');
		}
		
		$directory = ini_get('upload_tmp_dir');

		if (!is_dir($directory . '/lng-' . $code . '/') || substr(str_replace('\\', '/', realpath($directory . '/lng-' . $code . '/')), 0, strlen(ini_get('upload_tmp_dir'))) != str_replace('\\', '/', ini_get('upload_tmp_dir'))) {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory . '/lng-' . $code . '/*');

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
				$destination = substr($file, strlen($directory . '/lng-' . $code . '/'));
				
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
				} elseif (is_file($file)) {
					if (!rename($file, strtolower($destination))) {
						$json['error'] = sprintf($this->language->get('error_move'), $file);
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_db');
				
			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/translation/db', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function db() {
		$this->load->language('marketplace/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		$this->load->model('setting/translation');
			
		$translation_info = $this->model_setting_translation->getTranslationByCode($code);		
		
		if (!$translation_info) {
			$json['error'] = $this->language->get('error_code');
		}
				
		if (!$json) {
			$this->load->model('localisation/language');
			
			$language_info = $this->model_localisation_language->getLanguageByCode($code);
			
			if (!$language_info) {
				$language_data = array(
					'name'       => $translation_info['name'],
					'code'       => $code,
					'locale'     => $code,
					'sort_order' => 0,
					'status'     => 1
				);
				
				$this->model_localisation_language->addLanguage($language_data);
			}
				
			$json['text'] = $this->language->get('text_remove');
				
			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/translation/remove', 'token=' . $this->session->data['token'] . '&code=' . $code, true));		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function remove() {
		$this->load->language('marketplace/translation');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
		
		$this->load->model('setting/translation');
			
		$translation_info = $this->model_setting_translation->getTranslationByCode($code);		
		
		if (!$translation_info) {
			$json['error'] = $this->language->get('error_code');
		}
				
		$directory = ini_get('upload_tmp_dir');

		if (!is_dir($directory . '/lng-' . $code . '/') || substr(str_replace('\\', '/', realpath($directory . '/lng-' . $code . '/')), 0, strlen($directory)) != str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory . '/lng-' . $code . '/');

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

			if (is_dir($directory . '/lng-' . $code . '/')) {
				rmdir($directory . '/lng-' . $code . '/');
			}
						
			$json['success'] = $this->language->get('text_installed');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
}