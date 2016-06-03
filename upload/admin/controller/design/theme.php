<?php
class ControllerDesignTheme extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/theme');

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
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_template'] = $this->language->get('text_template');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_empty'] = $this->language->get('text_empty');

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
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/theme', $data));
	}
	
	public function path() {
		$this->load->language('design/theme');
		
		$json = array();
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}	
		
		$this->load->model('setting/setting');
			
		$theme = $this->model_setting_setting->getSettingValue('config_theme', $store_id);
		
		// This is only here for compatibility with old themes.
		if ($theme == 'theme_default') {
			$theme = $this->model_setting_setting->getSettingValue('theme_default_directory', $store_id);			
		}
		
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}
				
		if (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $path)), 0, strlen(DIR_CATALOG . 'view')) == DIR_CATALOG . 'view') {
			$files = glob(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $path . '/*');
			
			if ($files) {
				foreach($files as $file) {
					if (is_dir($file)) {
						$json['directory'][] = array(
							'name' => basename($file),
							'path' => trim($path . '/' . basename($file), '/')
						);
					}
					
					if (is_file($file)) {
						$json['file'][] = array(
							'name' => basename($file),
							'path' => trim($path . '/' . basename($file), '/')
						);
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
	
	public function template() {
		$this->load->language('design/theme');
		
		$json = array();
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}	
		
		$this->load->model('setting/setting');
			
		$theme = $this->model_setting_setting->getSettingValue('config_theme', $store_id);
		
		// This is only here for compatibility with old themes.
		if ($theme == 'theme_default') {
			$theme = $this->model_setting_setting->getSettingValue('theme_default_directory', $store_id);			
		}
				
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}		
		
		$this->load->model('design/theme');
		
		$theme_info = $this->model_design_theme->getTheme($store_id, $theme, $path);
		
		if ($theme_info) {
			$json['code'] = $theme_info['code'];
		} elseif (is_file(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $path) && (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $path)), 0, strlen(DIR_CATALOG . 'view')) == DIR_CATALOG . 'view')) {
			$json['code'] = file_get_contents(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $path);
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
		
		$this->load->model('setting/setting');
			
		$theme = $this->model_setting_setting->getSettingValue('config_theme', $store_id);
		
		// This is only here for compatibility with old themes.
		if ($theme == 'theme_default') {
			$theme = $this->model_setting_setting->getSettingValue('theme_default_directory', $store_id);			
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
		
			$this->model_design_theme->editTheme($store_id, $theme, $path, $this->request->post['code']);
			
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function reset() {
		$this->load->language('design/theme');
		
		$json = array();
		
		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];			
		} else {
			$store_id = 0;
		}	
		
		$this->load->model('setting/setting');
			
		$theme = $this->model_setting_setting->getSettingValue('config_theme', $store_id);
		
		// This is only here for compatibility with old themes.
		if ($theme == 'theme_default') {
			$theme = $this->model_setting_setting->getSettingValue('theme_default_directory', $store_id);			
		}
				
		if (isset($this->request->get['path'])) {
			$path = $this->request->get['path'];
		} else {
			$path = '';
		}		
				
		if (is_file(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $path) && (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $path)), 0, strlen(DIR_CATALOG . 'view')) == DIR_CATALOG . 'view')) {
			$json['code'] = file_get_contents(DIR_CATALOG . 'view/theme/' . $theme . '/template/' . $path);
		}		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}