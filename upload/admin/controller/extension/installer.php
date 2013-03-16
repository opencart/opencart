<?php
class ControllerExtensionInstaller extends Controller {
	private $error = array();
   
  	public function index() {
		$this->language->load('extension/installer');
	
    	$this->document->setTitle($this->language->get('heading_title'));
		
     	$this->data['heading_title'] = $this->language->get('heading_title');

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
	
		if (!isset($json['error']) && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
			// If xml file just put it straight into the DB
			if (strrchr($this->request->files['file']['name'], '.') == '.xml') {
				$json['next'] = $this->request->files['file']['name']; 
			} 
			
			if (strrchr($this->request->files['file']['name'], '.') == '.zip') {
				// if no temp directory exsits create it
				
				//rmdir(DIR_DOWNLOAD . 'temp');
				
				
				$file = $this->request->files['file']['tmp_name'];
				$directory = dirname($this->request->files['file']['tmp_name']) . '/' . basename($this->request->files['file']['name'], '.zip') . '/';
				

				
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
			
			$json['success'] = $this->language->get('text_success');
		}
					
		$this->response->setOutput(json_encode($json));
	}
	
	public function ftp() {
		$this->language->load('extension/modification');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
      		$json['error'] = $this->language->get('error_permission');
    	}
		
		
		
		if (!isset($json['error'])) {
			
			// Connect to the site via FTP
			$connection = ftp_connect($this->config->get('config_ftp_host'), $this->config->get('config_ftp_port'));
	
			if (!$connection) {
				exit($this->language->get('error_ftp_connection') . $this->config->get('config_ftp_host') . ':' . $this->config->get('config_ftp_port')) ;
			}
			
			$login = ftp_login($connection, $this->config->get('config_ftp_username'), $this->config->get('config_ftp_password'));
			
			if (!$login) {
				exit('Couldn\'t connect as ' . $this->config->get('config_ftp_username'));
			}
			
			if ($this->config->get('config_ftp_root')) {
				$root = ftp_chdir($connection, $this->config->get('config_ftp_root'));
				
				if (!$root) {
					exit('Couldn\'t change to directory ' . $this->config->get('config_ftp_root'));
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
								echo 'Made directory ' . $destination . '<br />';
							}
						}
					}	
					
					if (is_file($file)) {
						if (ftp_put($connection, $destination, $file, FTP_ASCII)) {		
							echo 'Successfully uploaded ' . $file . '<br />';
						}
					}
				}
				
				// SQL
				if (strrchr(basename($file), '.') == '.sql') {
					$json['sql'] = $file;								
				}
				
				// XML
				if (strrchr(basename($file), '.') == '.xml') {
					$json['xml'] = $file;
				}
			}
			
			ftp_close($connection);
		}
		
		
		
		
		$this->response->setOutput(json_encode($json));		
	}
	
	public function unzip() {
		// Unzip the files
		$zip = new ZipArchive();
		$zip->open($file);
		$zip->extractTo($directory);
		$zip->close();
		
		// Remove Zip
		unlink($file);
		
		
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
  	}	
}
?>