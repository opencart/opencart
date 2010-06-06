<?php
class ControllerCommonFileManager extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('common/filemanager');
		
		$this->data['title'] = $this->language->get('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['entry_folder'] = $this->language->get('entry_folder');
		$this->data['entry_move'] = $this->language->get('entry_move');
		$this->data['entry_copy'] = $this->language->get('entry_copy');
		$this->data['entry_rename'] = $this->language->get('entry_rename');
		
		$this->data['button_folder'] = $this->language->get('button_folder');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_move'] = $this->language->get('button_move');
		$this->data['button_copy'] = $this->language->get('button_copy');
		$this->data['button_rename'] = $this->language->get('button_rename');
		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_refresh'] = $this->language->get('button_refresh'); 
		
		$this->data['error_select'] = $this->language->get('error_select');
		$this->data['error_directory'] = $this->language->get('error_directory');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['directory'] = HTTP_IMAGE . 'data/';
		
		if (isset($this->request->get['field'])) {
			$this->data['field'] = $this->request->get['field'];
		} else {
			$this->data['field'] = '';
		}
		
		if (isset($this->request->get['CKEditorFuncNum'])) {
			$this->data['fckeditor'] = TRUE;
		} else {
			$this->data['fckeditor'] = FALSE;
		}
		
		$this->template = 'common/filemanager.tpl';
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}	
	
	public function image() {
		$this->load->model('tool/image');
		
		if (isset($this->request->post['image'])) {
			$this->response->setOutput($this->model_tool_image->resize($this->request->post['image'], 100, 100));
		}
	}
	
	public function directory() {	
		$json = array();
		
		if (isset($this->request->post['directory'])) {
			$directories = glob(rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['directory']), '/') . '/*', GLOB_ONLYDIR); 
			
			if ($directories) {
				$i = 0;
			
				foreach ($directories as $directory) {
					$json[$i]['data'] = basename($directory);
					$json[$i]['attributes']['directory'] = substr($directory, strlen(DIR_IMAGE . 'data/'));
					
					$children = glob(rtrim($directory, '/') . '/*', GLOB_ONLYDIR);
					
					if ($children)  {
						$json[$i]['children'] = ' ';
					}
					
					$i++;
				}
			}		
		}

		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));		
	}
	
	public function files() {
		$json = array();
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['directory']) && $this->request->post['directory']) {
			$directory = DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['directory']);
		} else {
			$directory = DIR_IMAGE . 'data/';
		}
		
		$allowed = array(
			'.jpg',
			'.jpeg',
			'.png',
			'.gif'
		);
		
		$files = glob(rtrim($directory, '/') . '/*');
		
		foreach ($files as $file) {
			if (is_file($file)) {
				$ext = strrchr($file, '.');
			} else {
				$ext = '';
			}	
			
			if (in_array(strtolower($ext), $allowed)) {
				$size = filesize($file);
	
				$i = 0;
	
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);
	
				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}
					
				$json[] = array(
					'file'     => substr($file, strlen(DIR_IMAGE . 'data/')),
					'filename' => basename($file),
					'size'     => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
					'thumb'    => $this->model_tool_image->resize(substr($file, strlen(DIR_IMAGE)), 100, 100)
				);
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));	
	}	
	
	public function create() {
		$this->load->language('common/filemanager');
				
		$json = array();
		
		if (isset($this->request->post['directory'])) {
			if (isset($this->request->post['name']) || $this->request->post['name']) {
				$directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['directory']), '/');							   
				
				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				}
				
				if (file_exists($directory . '/' . str_replace('../', '', $this->request->post['name']))) {
					$json['error'] = $this->language->get('error_exists');
				}
			} else {
				$json['error'] = $this->language->get('error_name');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
      		$json['error'] = $this->language->get('error_permission');  
    	}
		
		if (!isset($json['error'])) {	
			mkdir($directory . '/' . str_replace('../', '', $this->request->post['name']), 0777);
			
			$json['success'] = $this->language->get('text_create');
		}	
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
	
	public function delete() {
		$this->load->language('common/filemanager');
		
		$json = array();
		
		if (isset($this->request->post['path'])) {
			$path = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['path']), '/');
			 
			if (!file_exists($path)) {
				$json['error'] = $this->language->get('error_select');
			}
			
			if ($path == rtrim(DIR_IMAGE . 'data/', '/')) {
				$json['error'] = $this->language->get('error_delete');
			}
		} else {
			$json['error'] = $this->language->get('error_select');
		}
		
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
      		$json['error'] = $this->language->get('error_permission');  
    	}
		
		if (!isset($json['error'])) {
			if (is_file($path)) {
				unlink($path);
			} elseif (is_dir($path)) {
				$this->recursiveDelete($path);
			}
			
			$json['success'] = $this->language->get('text_delete');
		}				
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}

	protected function recursiveDelete($directory) {
		if (is_dir($directory)) {
			$handle = opendir($directory);
		}
		
		if (!$handle) {
			return FALSE;
		}
		
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				if (!is_dir($directory . '/' . $file)) {
					unlink($directory . '/' . $file);
				} else {
					$this->recursiveDelete($directory . '/' . $file);
				}
			}
		}
		
		closedir($handle);
		
		rmdir($directory);
		
		return TRUE;
	}

	public function move() {
		$this->load->language('common/filemanager');
		
		$json = array();
		
		if (isset($this->request->post['from']) && isset($this->request->post['to'])) {
			$from = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['from']), '/');
			
			if (!file_exists($from)) {
				$json['error'] = $this->language->get('error_missing');
			}
			
			if ($from == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_default');
			}
			
			$to = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['to']), '/');

			if (!file_exists($to)) {
				$json['error'] = $this->language->get('error_move');
			}	
			
			if (file_exists($to . '/' . basename($from))) {
				$json['error'] = $this->language->get('error_exists');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
      		$json['error'] = $this->language->get('error_permission');  
    	}
		
		if (!isset($json['error'])) {
			rename($from, $to . '/' . basename($from));
			
			$json['success'] = $this->language->get('text_move');
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}	
	
	public function copy() {
		$this->load->language('common/filemanager');
		
		$json = array();
		
		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 255)) {
				$json['error'] = $this->language->get('error_filename');
			}
				
			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['path']), '/');
			
			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_copy');
			}
			
			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}		
			
			$new_name = dirname($old_name) . '/' . str_replace('../', '', $this->request->post['name'] . $ext);
																			   
			if (file_exists($new_name)) {
				$json['error'] = $this->language->get('error_exists');
			}			
		} else {
			$json['error'] = $this->language->get('error_select');
		}
		
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
      		$json['error'] = $this->language->get('error_permission');  
    	}	
		
		if (!isset($json['error'])) {
			if (is_file($old_name)) {
				copy($old_name, $new_name);
			} else {
				$this->recursiveCopy($old_name, $new_name);
			}
			
			$json['success'] = $this->language->get('text_copy');
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));	
	}

	function recursiveCopy($source, $destination) { 
		$directory = opendir($source); 
		
		@mkdir($destination); 
		
		while (false !== ($file = readdir($handle))) {
			if (($file != '.') && ($file != '..')) { 
				if (is_dir($source . '/' . $file)) { 
					$this->recursiveCopy($source . '/' . $file, $destination . '/' . $file); 
				} else { 
					copy($source . '/' . $file, $destination . '/' . $file); 
				} 
			} 
		} 
		
		closedir($directory); 
	} 

	public function folders() {
		$this->response->setOutput($this->recursiveFolders(DIR_IMAGE . 'data/'));	
	}
	
	protected function recursiveFolders($directory) {
		$output = '';
		
		$output .= '<option value="' . substr($directory, strlen(DIR_IMAGE . 'data/')) . '">' . substr($directory, strlen(DIR_IMAGE . 'data/')) . '</option>';
		
		$directories = glob(rtrim(str_replace('../', '', $directory), '/') . '/*', GLOB_ONLYDIR);
		
		foreach ($directories  as $directory) {
			$output .= $this->recursiveFolders($directory);
		}
		
		return $output;
	}
	
	public function rename() {
		$this->load->language('common/filemanager');
		
		$json = array();
		
		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			if ((strlen(utf8_decode($this->request->post['name'])) < 3) || (strlen(utf8_decode($this->request->post['name'])) > 255)) {
				$json['error'] = $this->language->get('error_filename');
			}
				
			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['path']), '/');
			
			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_rename');
			}
			
			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}		
			
			$new_name = dirname($old_name) . '/' . str_replace('../', '', $this->request->post['name'] . $ext);
																			   
			if (file_exists($new_name)) {
				$json['error'] = $this->language->get('error_exists');
			}			
		}
		
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
      		$json['error'] = $this->language->get('error_permission');  
    	}
		
		if (!isset($json['error'])) {
			rename($old_name, $new_name);
			
			$json['success'] = $this->language->get('text_rename');
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
	
	public function upload() {
		$this->load->language('common/filemanager');
		
		$json = array();
		
		if (isset($this->request->post['directory'])) {
			if (isset($this->request->files['image']) && $this->request->files['image']['tmp_name']) {
				if ((strlen(utf8_decode($this->request->files['image']['name'])) < 3) || (strlen(utf8_decode($this->request->files['image']['name'])) > 255)) {
					$json['error'] = $this->language->get('error_filename');
				}
					
				$directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['directory']), '/');
				
				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				}
				
				if ($this->request->files['image']['size'] > 300000) {
					$json['error'] = $this->language->get('error_file_size');
				}
				
				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif',
					'application/x-shockwave-flash'
				);
						
				if (!in_array($this->request->files['image']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_file_type');
				}
				
				$allowed = array(
					'.jpg',
					'.jpeg',
					'.gif',
					'.png',
					'.flv'
				);
						
				if (!in_array(strtolower(strrchr($this->request->files['image']['name'], '.')), $allowed)) {
					$json['error'] = $this->language->get('error_file_type');
				}

				
				if ($this->request->files['image']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = 'error_upload_' . $this->request->files['image']['error'];
				}			
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
      		$json['error'] = $this->language->get('error_permission');  
    	}
		
		if (!isset($json['error'])) {	
			if (@move_uploaded_file($this->request->files['image']['tmp_name'], $directory . '/' . basename($this->request->files['image']['name']))) {		
				$json['success'] = $this->language->get('text_uploaded');
			} else {
				$json['error'] = $this->language->get('error_uploaded');
			}
		}
		
		$this->load->library('json');
		
		$this->response->setOutput(Json::encode($json));
	}
} 
?>