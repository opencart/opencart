<?php
class ControllerCommonDeveloper extends Controller {
	public function index() {
		$this->load->language('common/developer');
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['developer_theme'] = $this->config->get('developer_theme');
		$data['developer_sass'] = $this->config->get('developer_sass');	
				
		if (!function_exists('eval')) {
			$data['error_eval'] = $this->language->get('error_eval');
			
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('developer', array('developer_theme' => 1), 0);
			
			$data['developer_theme'] = 1;
		} else {
			$data['error_eval'] = '';
		}
	
		$this->response->setOutput($this->load->view('common/developer', $data));
	}
	
	public function edit() {
		$this->load->language('common/developer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('developer', $this->request->post, 0);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
		
	public function theme() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$directories = glob(DIR_CACHE . '*', GLOB_ONLYDIR);

			if ($directories) {
				foreach ($directories as $directory) {
					$files = glob($directory . '/*');
					
					foreach ($files as $file) { 
						if (is_file($file)) {
							unlink($file);
						}
					}
					
					if (is_dir($directory)) {
						rmdir($directory);
					}
				}
			}
						
			$json['success'] = sprintf($this->language->get('text_cache'), $this->language->get('text_theme'));
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
		
	public function sass() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$file = DIR_APPLICATION  . 'view/stylesheet/bootstrap.css';
			
			if (is_file($file)) {
				unlink($file);
			}
			
			$file = DIR_CATALOG  . 'view/stylesheet/bootstrap.css';
			
			if (is_file($file)) {
				unlink($file);
			}
						
			$json['success'] = sprintf($this->language->get('text_cache'), $this->language->get('text_sass'));
		}	
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));					
	}
}