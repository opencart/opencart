<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCommonDeveloper extends Controller {
	public function index() {
		$this->load->language('common/developer');
		
		$data['user_token'] = $this->session->data['user_token'];
		
		$data['developer_theme'] = $this->config->get('developer_theme');
		$data['developer_sass'] = $this->config->get('developer_sass');	
				
		$eval = false;
		
		$eval = '$eval = true;';

		eval($eval);		
		
		if ($eval === true) {
			$data['eval'] = true;
		} else {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('developer', array('developer_theme' => 1), 0);
		
			$data['eval'] = false;			
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
			// Before we delete we need to make sure there is a sass file to regenerate the css
			$file = DIR_APPLICATION  . 'view/stylesheet/bootstrap.css';
			
			if (is_file($file) && is_file(DIR_APPLICATION . 'view/stylesheet/sass/_bootstrap.scss')) {
				unlink($file);
			}
			 
			$files = glob(DIR_CATALOG  . 'view/theme/*/stylesheet/sass/_bootstrap.scss');
			 
			foreach ($files as $file) {
				$file = substr($file, 0, -21) . '/bootstrap.css';
				
				if (is_file($file)) {
					unlink($file);
				}
			}
			
			$json['success'] = sprintf($this->language->get('text_cache'), $this->language->get('text_sass'));
		}	
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));					
	}
	
	public function systemcache() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
		$files = glob(DIR_CACHE . 'cache.*');
		
		if (!empty($files)) {
			foreach($files as $file){
				$this->deldir($file);
			}
		}
						
			$json['success'] = sprintf($this->language->get('text_cache'), $this->language->get('text_systemcache'));
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
	
	public function imgcache() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
		$imgfiles = glob(DIR_IMAGE . 'cache/*');
		
		if (!empty($imgfiles)) {
			foreach($imgfiles as $imgfile){
				$this->deldir($imgfile);
			}
		}
						
			$json['success'] = sprintf($this->language->get('text_img_cache'), $this->language->get('text_imgcache'));
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
	
	public function allcache() {
		$this->load->language('common/developer');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'common/developer')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
		$files = glob(DIR_CACHE . 'cache.*');
		
		if (!empty($files)) {
			foreach($files as $file){
				$this->deldir($file);
			}
		}
		
		$imgfiles = glob(DIR_IMAGE . 'cache/*');
		
		if (!empty($imgfiles)) {
			foreach($imgfiles as $imgfile){
				$this->deldir($imgfile);
			}
		}
		
		// Before we delete we need to make sure there is a sass file to regenerate the css
		$file = DIR_APPLICATION  . 'view/stylesheet/bootstrap.css';
			
		if (is_file($file) && is_file(DIR_APPLICATION . 'view/stylesheet/sass/_bootstrap.scss')) {
			unlink($file);
		}
			 
		$files = glob(DIR_CATALOG  . 'view/theme/*/stylesheet/sass/_bootstrap.scss');
			 
		foreach ($files as $file) {
			$file = substr($file, 0, -21) . '/bootstrap.css';
				
			if (is_file($file)) {
				unlink($file);
			}
		}
		
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
		
		
						
			$json['success'] = sprintf($this->language->get('text_cache'), $this->language->get('text_allcache'));
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));			
	}
	
	public function deldir($dirname){
		if(file_exists($dirname)) {
			if(is_dir($dirname)){
				$dir=opendir($dirname);
				while(($filename=readdir($dir)) !== false){
					if($filename!="." && $filename!=".."){
						$file=$dirname."/".$filename;
						$this->deldir($file); 
					}
				}
				closedir($dir);
				rmdir($dirname);
			} else {
				@unlink($dirname);
			}
		}
	}
}