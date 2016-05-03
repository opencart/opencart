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

		$data['button_save'] = $this->language->get('button_save');
		$data['button_reset'] = $this->language->get('button_reset');
		
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/theme', $data));
	}

	public function store() {
		$this->load->language('design/theme');
		
		$json = array();	
		
		$json['heading'] = $this->language->get('text_store');
		
		$json['directory'] = array();
		
		$json['directory'][] = array(
			'name' => $this->language->get('text_default'),
			'href' => $this->url->link('design/theme/theme', 'token=' . $this->session->data['token'] . '&store_id=0', true)
		);		
		
		$this->load->model('setting/store');
					
		$results = $this->model_setting_store->getStores();
		
		foreach ($results as $result) {
			$json['directory'][] = array(
				'name' => $result['name'],
				'href' => $this->url->link('design/theme/theme', 'token=' . $this->session->data['token'] . '&store_id=' . $result['store_id'], true)
			);
		}		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));				
	}
	
	public function theme() {
		$this->load->language('design/theme');
		
		$json = array();
		
		$json['heading'] = $this->language->get('text_theme');
				
		$json['directory'] = array();
				
		$this->load->model('extension/extension');
					
		$extensions = $this->model_extension_extension->getInstalled('theme');
		
		foreach ($extensions as $code) {
			$this->load->language('theme/' . $code);
			
			$json['directory'][] = array(
				'name' => $this->language->get('heading_title'),
				'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'] . '&theme=' . $code, true)
			);
		}		
				
		$json['back'] = array(
			'name' => $this->language->get('button_back'),
			'href' => $this->url->link('design/theme/store', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'], true)
		);		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));						
	}
	
	public function directory() {
		$this->load->language('design/theme');
		
		$json = array();
		
		if ($this->request->get['theme'] == 'theme_default') {
			$this->load->model('setting/setting');
						
			$theme = $this->model_setting_setting->getSettingValue('theme_default_directory', $this->request->get['store_id']);			
		} else {
			$theme = $this->request->get['theme'];
		}
		
		if (isset($this->request->get['directory'])) {
			$directory = $this->request->get['directory'];
		} else {
			$directory = '';
		}
			
		$json['heading'] = $this->language->get('text_directory');
				
		$json['directory'] = array();
		
		if (substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'view/theme/' . $theme . '/' . $directory)), 0, strlen(DIR_CATALOG . 'view')) == DIR_CATALOG . 'view') {
			$files = glob(DIR_CATALOG . 'view/theme/' . $theme . '/' . $directory . '/*');
			
			if ($files) {
				foreach($files as $file) {
					if (is_dir($file)) {
						$json['directory'][] = array(
							'name' => basename($file),
							'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'] . '&theme=' . $this->request->get['theme'] . '&directory=' . trim($directory . '/' . basename($file), '/'), true)
						);
					}
					
					if (is_file($file)) {
						$json['file'][] = array(
							'name' => basename($file),
							'href' => $this->url->link('design/theme/code', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'] . '&theme=' . $this->request->get['theme'] . '&directory=' . trim($directory . '/' . basename($file), '/'), true)
						);
					}			
				}
			}
		}

		$pos = strrpos($directory, '/');

		if ($pos !== false) {
			$json['back'] = array(
				'name' => $this->language->get('button_back'),
				'href' => $this->url->link('design/theme/directory', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'] . '&theme=' . $this->request->get['theme'] . '&directory=' . urlencode(substr($directory, 0, $pos)), true)
			);					
		} else {
			$json['back'] = array(
				'name' => $this->language->get('button_back'),
				'href' => $this->url->link('design/theme/theme', 'token=' . $this->session->data['token'] . '&store_id=' . $this->request->get['store_id'] . '&theme=' . $this->request->get['theme'], true)
			);		
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function code() {
		$this->load->language('design/theme');

		$json = array();
		
		if ($this->request->get['theme'] == 'theme_default') {
			$this->load->model('setting/setting');
						
			$theme = $this->model_setting_setting->getSettingValue('theme_default_directory', $this->request->get['store_id']);			
		} else {
			$theme = $this->request->get['theme'];
		}
							
		if (isset($this->request->get['directory'])) {
			$directory = $this->request->get['directory'];
		} else {
			$directory = '';
		}
		
		$this->load->model('design/theme');
		
		$theme_info = $this->model_design_theme->getTheme($this->request->get['store_id'], $this->request->get['theme'], $this->request->get['code']);

		if ($theme_info) {
			$json['code'] = $theme_info['code'];
		} elseif (is_file(DIR_CATALOG . 'view/theme/' . $theme . '/' . $directory) && substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'view/theme/' . $theme . '/' . $directory)), 0, strlen(DIR_CATALOG . 'view')) == DIR_CATALOG . 'view') {
			$json['code'] = file_get_contents(DIR_CATALOG . 'view/theme/' . $theme . '/' . $directory);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
	
	public function save() {
		$this->load->language('design/theme');
		
		$json = array();
		
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'design/theme')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('design/theme');
		
			$this->model_design_theme->editTheme($this->request->get['store_id'], $this->request->get['theme'], $this->request->get['route'], $this->request->post['code']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function reset() {
		$this->load->language('design/theme');

		$json = array();
		
		$this->load->model('design/theme');

		if (is_file(DIR_CATALOG . 'view/' . $directory . '/' . $file) && substr(str_replace('\\', '/', realpath(DIR_CATALOG . 'view/' . $directory . '/' . $file)), 0, strlen(DIR_CATALOG . 'view')) == DIR_CATALOG . 'view') {
			if (isset($this->request->get['directory'])) {
				$directory = $this->request->get['directory'];
			} else {
				$directory = '';
			}
			
			if (isset($this->request->get['theme'])) {
				$theme = $this->request->get['theme'];
			} else {
				$theme = '';
			}
									
			if (isset($this->request->get['file'])) {
				$file = $this->request->get['file'];
			} else {
				$file = '';
			}			
			
			$json['code'] = file_get_contents(DIR_CATALOG . 'view/' . $directory . '/' . $file);
		}
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
}