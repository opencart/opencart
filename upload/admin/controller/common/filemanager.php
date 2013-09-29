<?php
class ControllerCommonFileManager extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('common/filemanager');
		
		$this->data['title'] = $this->language->get('heading_title');
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['text_selected'] = $this->language->get('text_selected');
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['entry_rename'] = $this->language->get('entry_rename');
		
		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_folder'] = $this->language->get('button_folder');
		$this->data['button_selected'] = $this->language->get('button_selected');
		$this->data['button_move'] = $this->language->get('button_move');
		$this->data['button_copy'] = $this->language->get('button_copy');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->data['directory'] = HTTP_CATALOG . 'image/catalog/';
				
		$this->load->model('tool/image');

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 50, 50);
		
		if (isset($this->request->get['field'])) {
			$this->data['field'] = $this->request->get['field'];
		} else {
			$this->data['field'] = '';
		}
		
		if (isset($this->request->get['CKEditorFuncNum'])) {
			$this->data['fckeditor'] = $this->request->get['CKEditorFuncNum'];
		} else {
			$this->data['fckeditor'] = false;
		}
		
		$this->template = 'common/filemanager.tpl';
		
		$this->response->setOutput($this->render());
	}	
	
	public function directory() {	
		$json = array();

		$json['directory'] = array();
		/*
		if () {
			$json['directory'][] = array(
				'name' => '..',
				'path' => utf8_substr($directory, strlen(DIR_IMAGE . 'catalog/')),
				'date' => date('Y-m-d G:i:s', filemtime($directory))
			);				
		}
			*/		
		$directories = glob(DIR_IMAGE . rtrim('catalog/' . str_replace(array('../', '..\\', '..'), '', $this->request->post['directory']), '/') . '/*', GLOB_ONLYDIR); 
				
		if ($directories) {
			foreach ($directories as $directory) {
				$json['directory'][] = array(
					'name' => basename($directory),
					'path' => utf8_substr($directory, strlen(DIR_IMAGE . 'catalog/')),
					'date' => date('Y-m-d G:i:s', filemtime($directory))
				);
			}
		}
	
		$json['file'] = array();
		
		$files = glob(DIR_IMAGE . rtrim('catalog/' . str_replace(array('../', '..\\', '..'), '', $this->request->post['directory']), '/') . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
		
		if ($files) {
			foreach ($files as $file) {
				$allowed = array(
					'.jpg',
					'.jpeg',
					'.png',
					'.gif'
				);				
			
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
					
					$time = filemtime($file);
					
					if ($time > (time() - $time)) {
						$date = date('Y-m-d G:i:s', $time);
					} elseif ($time > (time() - $time)) {
						$date = date($this->language->get('time_format'), $time);
					} else {
						$date = date('d m Y', $time);
					}
						
					$json['file'][] = array(
						'name' => basename($file),
						'path' => utf8_substr($file, utf8_strlen(DIR_IMAGE . 'catalog/')),
						'size' => round(utf8_substr($size, 0, utf8_strpos($size, '.') + 4), 2) . $suffix[$i],
						'date' => $date
					);
				}
			}
		}
		
		$this->response->setOutput(json_encode($json));		
	}

	public function image() {
		$this->load->model('tool/image');
		
		if (isset($this->request->post['image'])) {
			$this->response->setOutput($this->model_tool_image->resize(html_entity_decode($this->request->post['image'], ENT_QUOTES, 'UTF-8'), 100, 100));
		}
	}
	
	public function upload() {
		$this->language->load('common/filemanager');
		
		$json = array();
		
		if (isset($this->request->post['directory'])) {
			if (isset($this->request->files['image']) && $this->request->files['image']['tmp_name']) {
				$filename = basename(html_entity_decode($this->request->files['image']['name'], ENT_QUOTES, 'UTF-8'));
				
				if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
					$json['error'] = $this->language->get('error_filename');
				}
					
				$directory = rtrim(DIR_IMAGE . 'data/' . str_replace(array('../', '..\\', '..'), '', $this->request->post['directory']), '/');
				
				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				}
				
				if ($this->request->files['image']['size'] > $this->config->get('config_image_file_size')) {
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
						
				if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
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
			if (@move_uploaded_file($this->request->files['image']['tmp_name'], $directory . '/' . $filename)) {		
				$json['success'] = $this->language->get('text_uploaded');
			} else {
				$json['error'] = $this->language->get('error_uploaded');
			}
		}
		
		$this->response->setOutput(json_encode($json));
	}
		
	public function folder() {
		$this->language->load('common/filemanager');
				
		$json = array();
		
		if (isset($this->request->post['directory'])) {
			if (isset($this->request->post['name']) || $this->request->post['name']) {
				$directory = rtrim(DIR_IMAGE . 'data/' . str_replace(array('../', '..\\', '..'), '', $this->request->post['directory']), '/');							   
				
				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				}
				
				if (file_exists($directory . '/' . str_replace(array('../', '..\\', '..'), '', $this->request->post['name']))) {
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
			mkdir($directory . '/' . str_replace(array('../', '..\\', '..'), '', $this->request->post['name']), 0777);
			
			$json['success'] = $this->language->get('text_create');
		}	
		
		$this->response->setOutput(json_encode($json));
	}

	public function move() {
		$this->language->load('common/filemanager');
		
		$json = array();
		
		/*
		if (isset($this->request->post['from']) && isset($this->request->post['to'])) {
			$from = rtrim(DIR_IMAGE . 'data/' . str_replace(array('../', '..\\', '..'), '', html_entity_decode($this->request->post['from'], ENT_QUOTES, 'UTF-8')), '/');
			
			if (!file_exists($from)) {
				$json['error'] = $this->language->get('error_missing');
			}
			
			if ($from == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_default');
			}
			
			$to = rtrim(DIR_IMAGE . 'data/' . str_replace(array('../', '..\\', '..'), '', html_entity_decode($this->request->post['to'], ENT_QUOTES, 'UTF-8')), '/');

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
		*/
		
		$this->response->setOutput(json_encode($json));
	}	
	
	public function copy() {
		$this->language->load('common/filemanager');
		
		$json = array();
		
		/*
		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
				$json['error'] = $this->language->get('error_filename');
			}
				
			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace(array('../', '..\\', '..'), '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');
			
			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_copy');
			}
			
			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}		
			
			$new_name = dirname($old_name) . '/' . str_replace(array('../', '..\\', '..'), '', html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8') . $ext);
																			   
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
				// Get a list of files ready to upload
				$files = array();
				
				$path = array($directory . '*');
				
				while(count($path) != 0) {
					$next = array_shift($path);
			
					foreach(glob($next) as $file) {
						if (is_dir($file)) {
							$path[] = $file . '/*';
						}
							
						$files[] = $file;
					}
				}				
				
				
				foreach ($files as $file) {
					//copy($old_name, $new_name);
				}
				
				
				
				
				$this->recursiveCopy($old_name, $new_name);
			}
			
			$json['success'] = $this->language->get('text_copy');
		}
		*/
		
		$this->response->setOutput(json_encode($json));	
	}
	
	function recursiveCopy($source, $destination) { 
		$directory = opendir($source); 
		
		@mkdir($destination); 
		
		while (false !== ($file = readdir($directory))) {
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
	
	public function delete() {
		$this->language->load('common/filemanager');
		
		$json = array();
		
		if (isset($this->request->post['path'])) {
			$path = rtrim(DIR_IMAGE . 'data/' . str_replace(array('../', '..\\', '..'), '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');
			 
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
				$files = array();
				
				$path = array($path . '*');
				
				while(count($path) != 0) {
					$next = array_shift($path);
			
					foreach(glob($next) as $file) {
						if (is_dir($file)) {
							$path[] = $file . '/*';
						}
						
						$files[] = $file;
					}
				}
				
				rsort($files);
				
				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif(is_dir($file)) {
						rmdir($file);	
					} 
				}				
			}
			
			$json['success'] = $this->language->get('text_delete');
		}				
		
		$this->response->setOutput(json_encode($json));
	}
		
	public function rename() {
		$this->language->load('common/filemanager');
		
		$json = array();
		
		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
				$json['error'] = $this->language->get('error_filename');
			}
				
			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace(array('../', '..\\', '..'), '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');
			
			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_rename');
			}
			
			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}		
			
			$new_name = dirname($old_name) . '/' . str_replace(array('../', '..\\', '..'), '', html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8') . $ext);
																			   
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
		
		$this->response->setOutput(json_encode($json));
	}	
} 
?>