<?php
class ControllerExtensionManager extends Controller {
	public function index() {
		$this->language->load('extension/manager');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/manager', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['button_upload'] = $this->language->get('button_upload');

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'extension/manager.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function upload() {
		/*
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
								echo 'made directory ' . $destination . '<br />';
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

		$this->response->setOutput(json_encode($json));
		*/
	}			
}
?>