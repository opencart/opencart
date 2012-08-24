<?php
class ControllerExtensionManage extends Controller {
	public function index() {
		$this->load->language('extension/manage');
		 
		$this->document->setTitle($this->language->get('heading_title')); 

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/manage', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_upload'] = $this->language->get('button_upload');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->template = 'extension/manage.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function upload() {
		$this->language->load('extension/manage');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'extension/manage')) {
      		$json['error'] = $this->language->get('error_permission') . "\n";
    	}		
		
		if (!empty($this->request->files['file']['name'])) {
			if (strrchr($this->request->files['file']['name'], '.') != '.zip') {
				$json['error'] = $this->language->get('error_filetype');
       		}
					
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
	
		if (!isset($json['error']) && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
			// Unzip the files
			$file = $this->request->files['file']['tmp_name'];
			$directory = dirname($this->request->files['file']['tmp_name']) . '/' . basename($this->request->files['file']['name'], '.zip') . '/';
	
			$zip = new ZipArchive();
			$zip->open($file);
			$zip->extractTo($directory);
			$zip->close();
			
			// Remove Zip
			unlink($file);
			
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
			
			sort($files);
					
			// Connect to the site via FTP
			$connection = ftp_connect($this->config->get('config_ftp_host'), $this->config->get('config_ftp_port'));
	
			if (!$connection) {
				exit($this->language->get('error_ftp_connection') . $this->config->get('config_ftp_host') . ':' . $this->config->get('config_ftp_port')) ;
			}
			
			$login = ftp_login($connection, $this->config->get('config_ftp_username'), $this->config->get('config_ftp_password'));
			
			if (!$login) {
				exit('Couldn\'t connect as ' . $this->config->get('config_ftp_username'));
			}
			
			foreach ($files as $file) {
				$destination = substr($file, strlen($directory));
				
				// Upload everything in the upload directory
				if (substr($destination, 0, 7) == 'upload/') {
					echo $file . '<br />';
					
					//$destination = substr($destination, 7);
					
					if (is_dir($file)) {
						echo $this->config->get('config_ftp_root') . substr($destination, 0, strrpos($destination, '/')) . '<br>';
						
						$list = ftp_nlist($connection, $this->config->get('config_ftp_root') . substr($destination, 0, strrpos($destination, '/')));
						
						if ($list) {
							if (!in_array('/' . $destination, $list)) {
								if (ftp_mkdir($connection, $this->config->get('config_ftp_root') . $destination)) {
									echo 'made directory ' . $destination . '<br />';
								}
							}
						}
					}	
					
					if (is_file($file)) {
						echo $this->config->get('config_ftp_root') . $destination . '<br>';
						
						if (ftp_put($connection, $this->config->get('config_ftp_root') . $destination, $file, FTP_ASCII)) {		
							echo 'Successfully uploaded ' . $file . '<br />';
						}
					}
				} elseif (strrchr($file, '.') != '.sql') {
					
				} elseif (strrchr($file, '.') != '.xml') {
					
				}
			}
			
			ftp_close($connection);
			
			rsort($files);
						
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				} elseif (is_dir($file)) {
					rmdir($file);	
				}
			}
			
			if (file_exists($directory)) {
				rmdir($directory);
			}
						
			$json['success'] = $this->language->get('text_success');
		}	
		
		$this->response->setOutput(json_encode($json));
	}			
}
?>