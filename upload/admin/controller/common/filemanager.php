<?php
class ControllerCommonFileManager extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('common/filemanager');
		
		// Make sure we have the correct directory	
		if (isset($this->request->get['directory'])) {
			$directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
		} else {
			$directory = DIR_IMAGE . 'catalog';
		}
				
		if (isset($this->request->get['filter_name'])) {
			$filter_name = rtrim(str_replace(array('../', '..\\', '..', '*'), '', $this->request->get['filter_name']), '/');
		} else {
			$filter_name = null;
		}
						
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$this->data['images'] = array();
		
		$this->load->model('tool/image');
		
		// Get all directories and files and merge them
		$images = array_merge(glob($directory . '/' . $filter_name . '*', GLOB_ONLYDIR), glob($directory . '/' . $filter_name . '*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE));
		
		// Get total number of files and directories
		$image_total = count($images);
		
		// Split the array based on current page number and max number of items per page of 10
		$images = array_splice($images, ($page - 1) * 12, 12);
		
		foreach ($images as $image) {
			
			
			if (is_dir($image)) {
				$thumbnail = '';
				$type = 'directory';
			} elseif (is_file($image)) {
				$thumbnail = $this->model_tool_image->resize(utf8_substr($image, utf8_strlen(DIR_IMAGE)), 100, 100);
				$type = 'image';
			}
			
			$path = utf8_substr($image, utf8_strlen(DIR_IMAGE . 'catalog/'));
			
			$this->data['images'][] = array(
            	'image' => $thumbnail,
				'name'  => basename($image),
				'type'  => $type,
				'path'  => $path,
				'href'  => $this->url->link('common/filemanager', 'token=' . $this->session->data['token'] . '&directory=' . $path, 'SSL')
			);
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');
		
		$this->data['entry_search'] = $this->language->get('entry_search');
		$this->data['entry_folder'] = $this->language->get('entry_folder');
		
		$this->data['button_parent'] = $this->language->get('button_parent');
		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_folder'] = $this->language->get('button_folder');
		$this->data['button_delete'] = $this->language->get('button_delete');
		
		$this->data['token'] = $this->session->data['token'];
		
		if (isset($this->request->get['directory'])) {
			$this->data['directory'] = $this->request->get['directory'];
		} else {
			$this->data['directory'] = '';
		}
				
		if (isset($this->request->get['filter_name'])) {
			$this->data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$this->data['filter_name'] = '';
		}
		
		$url = '';
		
		if (isset($this->request->get['directory'])) {
			$url .= '&directory=' . urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8'));
		}
		
		//echo strrchr($directory, '\\');

		$this->data['parent'] = $this->url->link('common/filemanager', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
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
			$directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
		} else {
			$directory = DIR_IMAGE . 'catalog';
		}
		
		// Check its a directory
		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}
										
		if (!$json) {	
			if (!empty($this->request->files['file']['name'])) {
				// Sanitize the filename
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));
				
				// Validate the filename length
				if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 255)) {
					$json['error'] = $this->language->get('error_filename');
				}
				
				// Allowed file extension types	
				$allowed = array(
					'jpg',
					'jpeg',
					'gif',
					'png'
				);
				
				if (!in_array(utf8_strtolower(utf8_substr(strrchr($filename, '.'), 1)), $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
				
				// Allowed file mime types
				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif'
				);
		
				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_filetype');
				}
				
				// Return any upload error	
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}                        
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}
		
		if (!$json) {
			move_uploaded_file($this->request->files['file']['tmp_name'], $directory . '/' . $filename);     
			
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
			$directory = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $this->request->get['directory']), '/');
		} else {
			$directory = DIR_IMAGE . 'catalog';
		}		
		
		// Check its a directory
		if (!is_dir($directory)) {
			$json['error'] = $this->language->get('error_directory');
		}
		
		if (!$json) {
			// Sanitize the folder name
			$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));
			
			// Validate the filename length
			if ((utf8_strlen($folder) < 3) || (utf8_strlen($folder) > 128)) {
				$json['error'] = $this->language->get('error_folder');
			}
			
			// Check if directory already exists or not
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
		
		if (isset($this->request->post['delete'])) {
			$paths = $this->request->post['delete'];
		} else {
			$paths = array();	
		}
		
		// Loop through each path to run validations
		foreach ($paths as $path) {			
			$path = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $path), '/');
			
			// Check path exsists
			if (!file_exists($path)) {
				$json['error'] = $this->language->get('error_missing');
				
				break;
			}
			
			// Check path exsists
			if ($path == DIR_IMAGE . 'catalog') {
				$json['error'] = $this->language->get('error_delete');
				
				break;
			}
		}
			
		if (!$json) {
			// Loop through each path
			foreach ($paths as $path) {		
				$path = rtrim(DIR_IMAGE . 'catalog/' . str_replace(array('../', '..\\', '..'), '', $path), '/');
				
				// If path is just a file delete it
				if (is_file($path)) {
					unlink($path);
				
				// If path is a directory beging deleting each file and sub folder	
				} elseif (is_dir($path)) {
					$files = array();
					
					// Make path into an array
					$path = array($path . '*');
					
					// While the path array is still populated keep looping through
					while(count($path) != 0) {
						$next = array_shift($path);
						
						foreach(glob($next) as $file) {
							// If directory add to path array 
							if (is_dir($file)) {
								$path[] = $file . '/*';
							}
							
							// Add the file to the files to be deleted array
							$files[] = $file;
						}
					}
					
					// Reverse sort the file array
					rsort($files);
				
					foreach ($files as $file) {
						// If file just delete
						if (is_file($file)) {
							unlink($file);
						
						// If directory use the remove directory function
						} elseif(is_dir($file)) {
							rmdir($file);        
						} 
					}                                
				}
			}
			
			$json['success'] = $this->language->get('text_delete');
		}                                
		
		$this->response->setOutput(json_encode($json));		
	}
} 
?>