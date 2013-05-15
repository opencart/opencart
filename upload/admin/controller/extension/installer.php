<?php
class ControllerExtensionInstaller extends Controller {
	private $error = array();
   
  	public function index() {
		$this->language->load('extension/installer');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
     	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['entry_upload'] = $this->language->get('entry_upload');
		$this->data['entry_progress'] = $this->language->get('entry_progress');

		$this->data['help_upload'] = $this->language->get('help_upload');

		$this->data['button_upload'] = $this->language->get('button_upload');
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], 'SSL')
   		);
		
		$this->data['token'] = $this->session->data['token'];
		
		if (is_dir(DIR_DOWNLOAD . 'temp/')) {
			$this->data['error_warning'] = $this->language->get('error_directory');
		} else {
			$this->data['error_warning'] = '';
		}
		
		$this->template = 'extension/installer.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());	
  	}
	
	public function upload() {		
		$this->language->load('extension/modification');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
      		$json['error'] = $this->language->get('error_permission') . "\n";
    	}
		
		if (!empty($this->request->files['file']['name'])) {
			if (strrchr($this->request->files['file']['name'], '.') != '.zip' && strrchr($this->request->files['file']['name'], '.') != '.xml') {
				$json['error'] = $this->language->get('error_filetype');
       		}
					
			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}
	
		if (!isset($json['error']) && is_uploaded_file($this->request->files['file']['tmp_name'])) {
			// if no temp directory exists create it
			if (!is_dir(DIR_DOWNLOAD . 'temp')) {
				mkdir(DIR_DOWNLOAD . 'temp', 0777);
			}
			
			// Sanitize the filename	
			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
				
			// If xml file copy it to the temp directory
			if (strrchr($this->request->files['file']['name'], '.') == '.xml') {
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . 'temp/' . $filename);
				
				$json['xml'] = DIR_DOWNLOAD . 'temp/' . $filename;
			}
			
			// If zip file copy it to the temp directory
			if (strrchr($filename, '.') == '.zip') {
				$json['overwrite'] = array();
				
				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . 'temp/' . $filename);
				
				if (is_file(DIR_DOWNLOAD . 'temp/' . $filename)) {					
					$zip = zip_open(DIR_DOWNLOAD . 'temp/' . $filename);
					
					if ($zip) {
						while ($entry = zip_read($zip)) {
							// Compare admin files
							$file = DIR_APPLICATION . substr(zip_entry_name($entry), 13);
							
							if (is_file($file) && substr(zip_entry_name($entry), 0, 13) == 'upload/admin/') {
								$json['overwrite'][] = substr(zip_entry_name($entry), 7);
							}
							
							// Compare catalog files
							$file = DIR_CATALOG . substr(zip_entry_name($entry), 7);
							
							if (is_file($file) && substr(zip_entry_name($entry), 0, 15) == 'upload/catalog/') {
								$json['overwrite'][] = substr(zip_entry_name($entry), 7);
							}
							
							// Compare image files
							$file = DIR_IMAGE . substr(zip_entry_name($entry), 13);
							
							if (is_file($file) && substr(zip_entry_name($entry), 0, 13) == 'upload/image/') {
								$json['overwrite'][] = substr(zip_entry_name($entry), 7);
							}
							
							// Compare system files
							$file = DIR_SYSTEM . substr(zip_entry_name($entry), 14);													
							
							if (is_file($file) && substr(zip_entry_name($entry), 0, 14) == 'upload/system/') {
								$json['overwrite'][] = substr(zip_entry_name($entry), 7);
							}
							
							// SQL
							if (substr(zip_entry_name($entry), 0, 11) == 'install.sql') {
								$json['sql'] = zip_entry_name($entry);							
							}		
							
							// XML					
							if (substr(zip_entry_name($entry), 0, 11) == 'install.xml') {
								$json['xml'] = zip_entry_name($entry);
							}

							// PHP
							if (substr(zip_entry_name($entry), 0, 11) == 'install.php') {
								$json['php'] = zip_entry_name($entry);
							}
							
							//$json[] = zip_entry_name($entry);
						}
						
						echo '<pre>';
						print_r($json);
						echo '</pre>';
							
						zip_close($zip);
					} else {
						$json['error'] = $this->language->get('error_zip');
					}			
				} else {
					$json['error'] = $this->language->get('error_upload');
				}				
			}	
			
			//unset($this->request->files['file']['tmp_name']);
			
			//$json['success'] = $this->language->get('text_upload');
		}
					
		$this->response->setOutput(json_encode($json));
	}
	
	public function unzip() {
		$this->language->load('extension/modification');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
      		$json['error'] = $this->language->get('error_permission');
    	}

		//if () {
		//	$this->request->post['file'];
		//}

		if (!is_file()) {
			$json['error'] = $this->language->get('error_zip_mime');
		}
				
		if (!mime_content_type()) {
			$json['error'] = $this->language->get('error_zip_mime');
		}
		
		// Unzip the files
		$zip = new ZipArchive();
		
		if ($zip->open($file)) {
			$zip->extractTo($directory);
			$zip->close();				
		} else {
			$json['error'] = $this->language->get('error_zip_open');			
		}
		
		// Remove Zip
		unlink($file);
		
		$this->response->setOutput(json_encode($json));
	}
		
	public function ftp() {
		$this->language->load('extension/modification');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
      		$json['error'] = $this->language->get('error_permission');
    	}
		
		
		
		if (!isset($json['error'])) {
			
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
		
					
			// Connect to the site via FTP
			$connection = ftp_connect($this->config->get('config_ftp_host'), $this->config->get('config_ftp_port'));
	
			if (!$connection) {
				$json['error'] = $this->language->get('error_ftp_connection') . $this->config->get('config_ftp_host') . ':' . $this->config->get('config_ftp_port');
			}
			
			$login = ftp_login($connection, $this->config->get('config_ftp_username'), $this->config->get('config_ftp_password'));
			
			if (!$login) {
				$json['error'] = 'Couldn\'t connect as ' . $this->config->get('config_ftp_username');
			}
			
			if ($this->config->get('config_ftp_root')) {
				$root = ftp_chdir($connection, $this->config->get('config_ftp_root'));
				
				if (!$root) {
					$json['error'] = 'Couldn\'t change to directory ' . $this->config->get('config_ftp_root');
				}
			}
		
			foreach ($files as $file) {
				// Upload everything in the upload directory
				if (substr(substr($file, strlen($directory)), 0, 7) == 'upload/') {
					$destination = substr(substr($file, strlen($directory)), 7);
					
					if (is_dir($file)) {
						$list = ftp_nlist($connection, substr($destination, 0, strrpos($destination, '/')));
						
						if (!in_array($destination, $list)) {
							if (ftp_mkdir($connection, $destination)) {
								$json['error'] = 'Made directory ' . $destination;
								
								break;
							}
						}
					}	
					
					if (is_file($file)) {
						if (!ftp_put($connection, $destination, $file, FTP_ASCII)) {		
							$json['error'] = '';
							
							break;
						}
					}
				}
				

			}
			
			ftp_close($connection);
		}
		
		
		
		
		$this->response->setOutput(json_encode($json));		
	}
	
	public function sql() {
		$this->language->load('extension/modification');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
      		$json['error'] = $this->language->get('error_permission');
    	}
		
		if (!$json) {		
			// SQL
			if (strrchr(basename($file), '.') == '.sql') {
				$sql = file_get_contents($file);
				
				$lines = explode($sql);
				
				$query = '';
		
				foreach($lines as $line) {
					if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
						$query .= $line;
		
						if (preg_match('/;\s*$/', $line)) {
							$query = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . $data['db_prefix'], $query);
							$query = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . $data['db_prefix'], $query);
							$query = str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $query);
							
							$result = mysql_query($query, $connection); 
		
							if (!$result) {
								die(mysql_error());
							}
		
							$query = '';
						}
					}
				}	
			}
		}
					
		$this->response->setOutput(json_encode($json));							
	}
	
	public function xml() {
		$this->language->load('extension/modification');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
      		$json['error'] = $this->language->get('error_permission');
    	}
		
		if (isset($this->request->post['file']) && is_file($this->request->post['file'])) {
			
		}
		
		// If xml file just put it straight into the DB
		if (strrchr($this->request->files['file']['name'], '.') == '.xml') {

		} 
			
		if (!$json) {	
			$this->load->model('setting/modification');
			
			// If xml file just put it straight into the DB
			if (strrchr($this->request->files['file']['name'], '.') == '.xml') {
				$xml = file_get_contents($this->request->files['file']['tmp_name']);
				
				if ($xml) {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->validateOnParse = true;
					$dom->loadXml($xml);

					if (!$dom->validate(DIR_SYSTEM . 'modification.xsd')) {
						//echo '<b>DOMDocument::schemaValidate() Generated Errors!</b>';
						
						//libxml_display_errors();
					}
									
					$data = array(
						'name'    => $dom->getElementsByTagName('name')->item(0)->nodeValue,
						'version' => $dom->getElementsByTagName('version')->item(0)->nodeValue,
						'author'  => $dom->getElementsByTagName('author')->item(0)->nodeValue,
						'code'    => $xml
					);
					
					$this->model_setting_modification->addModification($data);
				}
				
				unset($this->request->files['file']['tmp_name']);
			} 
		}
			
		$this->response->setOutput(json_encode($json));
	}
	
  	public function clear() {
		$this->language->load('extension/modification');
		
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
      		$json['error'] = $this->language->get('error_permission');
    	}
		
		if (!is_dir($json['error'])) {
			
		}
		
		if (!isset($directory)) {
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
		}
		
		$this->response->setOutput(json_encode($json));
  	}	
}
?>