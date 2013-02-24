<?php
class ControllerExtensionModification extends Controller {
	public function index() {
		$this->language->load('extension/modification');
		 
		$this->document->setTitle($this->language->get('heading_title')); 

  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/modification', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => $this->language->get('breadcrumb_separator')
   		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['button_upload'] = $this->language->get('button_upload');
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
		
			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}
		
		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getInstalled('modification');
		
		foreach ($extensions as $key => $value) {
			if (!file_exists(DIR_APPLICATION . 'controller/modification/' . $value . '.php')) {
				$this->model_setting_extension->uninstall('modification', $value);
				
				unset($extensions[$key]);
			}
		}
		
		$this->data['extensions'] = array();
						
		$files = glob(DIR_APPLICATION . 'controller/modification/*.php');
		
		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->language->load('modification/' . $extension);
	
				$action = array();
				
				if (!in_array($extension, $extensions)) {
					$action[] = array(
						'text' => $this->language->get('text_install'),
						'href' => $this->url->link('extension/modification/install', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
					);
				} else {
					$action[] = array(
						'text' => $this->language->get('text_edit'),
						'href' => $this->url->link('modification/' . $extension . '', 'token=' . $this->session->data['token'], 'SSL')
					);
								
					$action[] = array(
						'text' => $this->language->get('text_uninstall'),
						'href' => $this->url->link('extension/modification/uninstall', 'token=' . $this->session->data['token'] . '&extension=' . $extension, 'SSL')
					);
				}
				
				$this->data['extensions'][] = array(
					'name'       => $this->language->get('heading_title'),
					'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get($extension . '_sort_order'),
					'action'     => $action
				);
			}
		}		
		
		$this->template = 'extension/modification.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	
	public function install() {
		$this->language->load('extension/payment');
		
		if (!$this->user->hasPermission('modify', 'extension/payment')) {
			$this->session->data['error'] = $this->language->get('error_permission'); 
			
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$this->load->model('setting/extension');
		
			$this->model_setting_extension->install('payment', $this->request->get['extension']);

			$this->load->model('user/user_group');
		
			$this->model_user_user_group->addPermission($this->user->getId(), 'access', 'payment/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getId(), 'modify', 'payment/' . $this->request->get['extension']);

			require_once(DIR_APPLICATION . 'controller/payment/' . $this->request->get['extension'] . '.php');
			
			$class = 'ControllerPayment' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'install')) {
				$class->install();
			}
			
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}
	}
	
	public function uninstall() {
		$this->language->load('extension/payment');
		
		if (!$this->user->hasPermission('modify', 'extension/payment')) {
			$this->session->data['error'] = $this->language->get('error_permission'); 
			
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		} else {		
			$this->load->model('setting/extension');
			$this->load->model('setting/setting');
				
			$this->model_setting_extension->uninstall('payment', $this->request->get['extension']);
		
			$this->model_setting_setting->deleteSetting($this->request->get['extension']);
		
			require_once(DIR_APPLICATION . 'controller/payment/' . $this->request->get['extension'] . '.php');
			
			$class = 'ControllerPayment' . str_replace('_', '', $this->request->get['extension']);
			$class = new $class($this->registry);
			
			if (method_exists($class, 'uninstall')) {
				$class->uninstall();
			}
		
			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));	
		}			
	}
		
	public function upload() {
/* 
New XML Modifcation Standard 

<modification>
	<id><![CDATA[Test]]></id>
	<version><![CDATA[1.0]]></version>
	<author><![CDATA[http://www.opencart.com]]></author>
	<file name="catalog/controller/product/product.php" error="log|skip|abort">
		<operation>
			<search index="1" error="log|skip|abort"><![CDATA[
			
			code
			
			]]></search>
			
			<add position="replace|before|after"><![CDATA[
			
			code
			
			]]></add>
		</operation>
	</file>	
</modification>
*/	
		
		$this->language->load('extension/manager');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'extension/manager')) {
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
				} elseif (strrchr(basename($file), '.') == '.sql') {
					//file_get_contents($file);
				} elseif (strrchr(basename($file), '.') == '.xml') {
					//file_get_contents($file);
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
		
		
		if (file_exists($file)) { 
			$xml = file_get_contents($file);
			
			$this->parse($xml);
		} else {
			trigger_error('Error: Could not load modification ' . $file . '!');
			exit();
		}	
		

					
		$this->response->setOutput(json_encode($json));
	}
	
	public function sql() {
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
	
	public function xml() {
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->loadXml($xml);
		
		$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');		
		
		foreach ($files as $file) {
			glob($file);
			
			$filename = $file->getAttribute('name');
		
			if (!isset($this->data[$filename])) {
				if (file_exists($filename)) {
					$content = file_get_contents($filename);
				} else {
					trigger_error('Error: Could not load language ' . $filename . '!');
					exit();
				}			
			} else {
				$content = $this->data[$filename];
			}

			$operations = $file->getElementsByTagName('operation');
		
			foreach ($operations as $operation) {
				$search = $operation->getElementsByTagName('search')->item(0)->nodeValue;
				$index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');
				$add = $operation->getElementsByTagName('add')->item(0)->nodeValue;
				$position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
					
				if (!$index) {
					$index = 1;
				}
				
				switch ($position) {
					default:
					case 'replace':
						$replace = $add;
						break;
					case 'before':
						$replace = $add . $search;
						break;					
					case 'after':
						$replace = $search . $add;
						break;
				}

				$i = 0;
				$pos = -1;
				$result = array();

				while (($pos = strpos($content, $search, $pos + 1)) !== false) {
					$result[$i++] = $pos; 
				}
				
				// Only replace the occurance of the string that is equal to the index					
				if (isset($result[$index - 1])) {
					$content = substr_replace($content, $replace, $result[$index - 1], strlen($search));
				}
			}

			echo '<pre>';
			print_r($result);
			echo $content;
			echo '</pre>';
		}
	}			
}
?>