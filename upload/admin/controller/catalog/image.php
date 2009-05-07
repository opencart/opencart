<?php
class ControllerCatalogImage extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('catalog/image');
		
		$data = array();
		
		if ($this->validate()) {
			$filename = basename($this->request->files['image']['name']);
			
			if (@move_uploaded_file($this->request->files['image']['tmp_name'], DIR_IMAGE . $filename)) {
				@unlink($this->request->files['image']['tmp_name']);
	  		
				$this->load->helper('image');
			
				$data['file'] = $filename;
				
				if (isset($this->request->get['no_resize'])) {
					if (@$this->request->server['HTTPS'] != 'on') {
						$this->data['src'] = HTTP_IMAGE . $filename;
					} else {
						$this->data['src'] = HTTPS_IMAGE . $filename;
					}					
				} else {
					$data['src'] = HelperImage::resize($filename, '100', '100');
				}
			}
		} else {
			$data['error'] = $this->error['message'];
		}
		
		$this->load->helper('json');
		
		$this->response->setOutput(Json::encode($data));
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'catalog/image')) {
			$this->error['message'] = $this->language->get('error_permission');
		}
		
		if (isset($this->request->files['image'])) {
			if (is_uploaded_file($this->request->files['image']['tmp_name'])) {
	  			if ((strlen(utf8_decode($this->request->files['image']['name'])) < 3) || (strlen(utf8_decode($this->request->files['image']['name'])) > 255)) {
        			$this->error['message'] = $this->language->get('error_filename');
	  			}

		    	$allowed = array(
		      		'image/jpeg',
		      		'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif'
		    	);
				
				if (!in_array($this->request->files['image']['type'], $allowed)) {
          			$this->error['message'] = $this->language->get('error_filetype');
        		}
										
				if ($this->request->files['image']['error'] != UPLOAD_ERR_OK) {
					$this->error['message'] = $this->language->get('error_upload_' . $this->request->files['image']['error']);
				}
			}
		} else {
			$this->error['message'] = $this->language->get('error_required');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>