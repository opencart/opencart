<?php
class ControllerDesignTranslation extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/theme', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_language'] = $this->language->get('text_language');
		$data['text_translation'] = $this->language->get('text_translation');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_begin'] = $this->language->get('text_begin');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_reset'] = $this->language->get('button_reset');
		
		$data['token'] = $this->session->data['token'];
		
		$data['stores'] = array();
		
		$this->load->model('setting/store');
					
		$results = $this->model_setting_store->getStores();
		
		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}

		$data['languages'] = array();
		
		$this->load->model('localisation/language');
					
		$results = $this->model_localisation_language->getLanguages();
		
		foreach ($results as $result) {
			$data['languages'][] = array(
				'language_id' => $result['language_id'],
				'name'        => $result['name']
			);
		}
		
		$data['language_id'] = $this->config->get('config_language_id');
				
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation', $data));
	}
	
	public function path() {
		$this->load->language('design/theme');
		
		$json = array();
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}	
		
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];			
		} else {
			$language_id = 0;
		}
						
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}

		$this->load->model('design/translation');

		$this->load->model('localisation/language');
					
		$language_info = $this->model_localisation_language->getLanguage($language_id);
		
		if ($language_info && (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'language/' . $language_info['code'] . '/' . $path)), 0, strlen(DIR_CATALOG . 'language')) == DIR_CATALOG . 'language')) {
			$path_data = array();
			
			// We grab the files from the default theme directory first as the custom themes drops back to the default theme if selected theme files can not be found.
			$files = glob(rtrim(DIR_CATALOG . 'language/{default,' . $language_info['code'] . '}/' . $path, '/') . '/*', GLOB_BRACE);
			
			if ($files) {
				foreach($files as $file) {
					if (!in_array(basename($file), $path_data))  {
						if (is_dir($file)) {
							$json['directory'][] = array(
								'name' => basename($file),
								'path' => trim($path . '/' . basename($file), '/')
							);
						}
						
						if (is_file($file) && (substr($file, -4) == '.php')) {
							$translation_total = $this->model_design_translation->getTotalTranslations($store_id, $language_id, $path . '/' . trim(basename($file, '.php'), '/'));
							
							$json['file'][] = array(
								'name' => basename($file, '.php') . ' (' . $translation_total . ')',
								'path' => trim($path . '/' . basename($file, '.php'), '/')
							);
						}
						
						$path_data[] = basename($file);
					}
				}
			}
		}

		if (!empty($this->request->get['path'])) {
			$json['back'] = array(
				'name' => $this->language->get('button_back'),
				'path' => urlencode(substr($path, 0, strrpos($path, '/')))
			);
		}		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function translation() {
		$this->load->language('design/theme');
		
		$json = array();
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}	
		
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];			
		} else {
			$language_id = 0;
		}
		
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}	
		
		$json['translations'] = array();
		
		$this->load->model('localisation/language');
					
		$language_info = $this->model_localisation_language->getLanguage($language_id);
		
		if ($language_info && (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'language/' . $language_info['code'] . '/' . $path . '.php')), 0, strlen(DIR_CATALOG . 'language')) == DIR_CATALOG . 'language')) {
			$this->load->model('design/translation');
						
			$results = $this->model_design_translation->getTranslationsByRoute($store_id, $language_id, $path);
	
			foreach ($results as $result) { 
				$data['translations'][] = $result;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}		
	
	public function save() {
		$this->load->language('design/theme');
		
		$json = array();
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}
			
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];			
		} else {
			$language_id = 0;
		}		
		
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}		
			
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('design/theme');
			
			$pos = strpos($path, '.');
			
			$this->model_design_theme->editTheme($store_id, $theme, ($pos !== false) ? substr($path, 0, $pos) : $path, $this->request->post['code']);
			
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	
	
	
	/*
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function translation() {
		$json = array();
			
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}
					
		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = 0;
		}	
		
		if (isset($this->request->get['key'])) {
			$key = $this->request->get['key'];
		} else {
			$key = '';
		}	
					
		$this->load->model('localisation/language');
		
		$language_info = $this->model_localisation_language->getLanguage($language_id);
		
		if ($language_info) {
			$directory = DIR_CATALOG . 'language/';
			
			if (is_file($directory . $language_info['code'] . '/' . $code . '.php') && substr(str_replace('\\', '/', realpath($directory . $language_info['code'] . '/' . $code . '.php')), 0, strlen($directory)) == $directory) {
				$_ = array();
						
				include($directory . $language_info['code'] . '/' . $code . '.php');	
				
				if (isset($_[$key])) {
					$json = $_[$key];
				}
			}
		}
			
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
	*/
}
