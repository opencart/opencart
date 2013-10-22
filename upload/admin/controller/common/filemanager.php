<?php
class ControllerCommonFileManager extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('common/filemanager');
		
		// Make sure we have the correct directory	
		if (isset($this->request->get['directory'])) {
			$directory = rtrim(DIR_IMAGE .  'catalog/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
		} else {
			$directory = DIR_IMAGE . 'catalog';
		}
				
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}
						
		$this->load->model('tool/image');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$this->data['images'] = array();
		
		// Get all directories		
		$directories = glob($directory . '/*', GLOB_ONLYDIR); 
		
		// Get all files
		$files = glob($directory . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE); 
		
		// Merge arrays
		$images = array_merge($directories, $files);
		
		// Get total number of files and directories
		$image_total = count($images);
		
		// Split the array based on current page number and max number of items per page of 10
		$images = array_splice($images, ($page - 1) * 12, 12);
		
		foreach ($images as $image) {
			if (is_dir($image)) {
				$type = 'directory';
			} elseif (is_file($image)) {
				$type = 'image';
			}
			
			$thumbnail = '';
			
			if ($type == 'image') {
				if (is_file($image)) {
					$thumbnail = $this->model_tool_image->resize(utf8_substr($image, strlen(DIR_IMAGE)), 120, 120);
				} else {
					$thumbnail = $this->model_tool_image->resize('no_image.jpg', 120, 120);
				}
			}
			
			$this->data['images'][] = array(
            	'name'  => basename($image),
				'type'  => $type,
				'path'  => utf8_substr($image, strlen(DIR_IMAGE . 'catalog/')),
				'image' => $thumbnail
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['entry_search'] = $this->language->get('entry_search');
		$this->data['entry_folder'] = $this->language->get('entry_folder');
		
		$this->data['button_parent'] = $this->language->get('button_parent');
		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_folder'] = $this->language->get('button_folder');
		$this->data['button_delete'] = $this->language->get('button_delete');
		
		$this->data['token'] = $this->session->data['token'];
		
		$url = '';
		
		if (isset($this->request->get['directory'])) {
			$url .= '&directory=' . urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8'));
		}
								
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}
					
		$pagination = new Pagination();
		$pagination->total = $image_total;
		$pagination->page = $page;
		$pagination->limit = 12;
		$pagination->url = $this->url->link('common/filemanager', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$this->data['pagination'] = $pagination->render();
		
		$this->template = 'common/filemanager.tpl';
		
		$this->response->setOutput($this->render());
	}
	
	public function upload() {
		$this->language->load('common/filemanager');
		
		$json = array();
		
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
      		$json['error'] = $this->language->get('error_permission');
    	}
		
		// Make sure we have the correct directory	
		if (isset($this->request->get['directory'])) {
			$directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $this->request->post['directory']), '/');
		} else {
			$directory = DIR_IMAGE . 'catalog';
		}
		
		// Check its a directory
		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}
										
		if (!$json) {	
			if (is_uploaded_file($this->request->files['image']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['image']['name'], ENT_QUOTES, 'UTF-8'));
				
				// Validate the filename length
				if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
					$json['error'] = $this->language->get('error_filename');
				}
				
				// Allowed file extension types	
				$allowed = array(
					'jpg',
					'jpeg',
					'gif',
					'png'
				);
		
				if (!in_array(strtolower(strrchr($filename, '.'), 1), $allowed)) {
					$json['error'] = $this->language->get('error_file_type');
				}
								
				// Allowed file mime types
				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif'
				);
		
				if (!in_array($this->request->files['image']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_file_type');
				}
				
				// Return any upload error	
				if ($this->request->files['image']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['image']['error']);
				}                        
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}
		
		if (!$json) {
			move_uploaded_file($this->request->files['image']['tmp_name'], $directory . '/' . $filename);     
			
			$json['success'] = $this->language->get('text_uploaded');
		}
			
		$this->response->setOutput(json_encode($json));		
	}
	
	public function folder() {
		$this->language->load('common/filemanager');
		
		$json = array();
		
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
      		$json['error'] = $this->language->get('error_permission');
    	}
		
		// Make sure we have the correct directory	
		if (isset($this->request->get['directory'])) {
			$directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $this->request->post['directory']), '/');
		} else {
			$directory = DIR_IMAGE . 'catalog';
		}		
		
		// Check its a directory
		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$json) {
			$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8')));
			
			// Validate the filename length
			if ((utf8_strlen($folder) < 3) || (utf8_strlen($folder) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			}
			
			// check if directory already exists or not
			if (is_dir($directory . '/' . $folder)) {
				$json['error'] = $this->language->get('error_exists');
			}			
		}
		
		if (!$json) { 
			mkdir($directory . '/' . $folder, 0777);
		
			$json['success'] = $this->language->get('text_directory');
		}        
		
		$this->response->setOutput(json_encode($json));		
	}
	
	public function delete() {
		$this->language->load('common/filemanager');
		
		$json = array();
		
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');  
		}
					
		if (isset($this->request->post['path'])) {
			$path = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');
			
			if (!file_exists($path)) {
				$json['error'] = $this->language->get('error_select');
			}
			
			if ($path == rtrim(DIR_IMAGE . 'catalog/', '/')) {
				$json['error'] = $this->language->get('error_delete');
			}
		} else {
			$json['error'] = $this->language->get('error_select');
		}
		
		if (!$json) {
			
			
			
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
} 
?>