<?php
class ControllerMarketplaceInstaller extends Controller {
	public function index() {
		$this->load->language('marketplace/installer');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/installer', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['heading_title'] = $this->language->get('heading_title');
        
		$data['text_upload'] = $this->language->get('text_upload');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_progress'] = $this->language->get('text_progress');
			
		$data['entry_upload'] = $this->language->get('entry_upload');
		$data['entry_overwrite'] = $this->language->get('entry_overwrite');
		$data['entry_progress'] = $this->language->get('entry_progress');
	
		$data['help_upload'] = $this->language->get('help_upload');
		
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_continue'] = $this->language->get('button_continue');

		$data['user_token'] = $this->session->data['user_token'];
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}
	
	public function upload() {
		$this->load->language('marketplace/installer');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name'])) {
				if (substr($this->request->files['file']['name'], -10) != '.ocmod.zip' && substr($this->request->files['file']['name'], -10) != '.ocmod.xml') {
					$json['error'] = $this->language->get('error_filetype');
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
			// If no temp directory exists create it
			$path = 'temp-' . token(32);

			if (!is_dir(DIR_UPLOAD . $path)) {
				mkdir(DIR_UPLOAD . $path, 0777);
			}

			// Set the steps required for installation
			$json['step'] = array();
			$json['overwrite'] = array();

			if (strrchr($this->request->files['file']['name'], '.') == '.xml') {
				$file = DIR_UPLOAD . $path . '/install.xml';

				// If xml file copy it to the temporary directory
				move_uploaded_file($this->request->files['file']['tmp_name'], $file);

				if (file_exists($file)) {
					$json['step'][] = array(
						'text' => $this->language->get('text_xml'),
						'href' => str_replace('&amp;', '&', $this->url->link('marketplace/installer/xml', 'user_token=' . $this->session->data['user_token'], true)),
						'path' => $path
					);

					// Clear temporary files
					$json['step'][] = array(
						'text' => $this->language->get('text_remove'),
						'href' => str_replace('&amp;', '&', $this->url->link('marketplace/installer/remove', 'user_token=' . $this->session->data['user_token'], true)),
						'path' => $path
					);
				} else {
					$json['error'] = $this->language->get('error_file');
				}
			}

			// If zip file copy it to the temp directory
			if (strrchr($this->request->files['file']['name'], '.') == '.zip') {
				$file = DIR_UPLOAD . $path . '/upload.zip';

				move_uploaded_file($this->request->files['file']['tmp_name'], $file);

				if (file_exists($file)) {
					$zip = zip_open($file);

					if ($zip) {
						// Zip
						$json['step'][] = array(
							'text' => $this->language->get('text_unzip'),
							'href' => str_replace('&amp;', '&', $this->url->link('marketplace/installer/unzip', 'user_token=' . $this->session->data['user_token'], true)),
							'path' => $path
						);

						// FTP
						$json['step'][] = array(
							'text' => $this->language->get('text_ftp'),
							'href' => str_replace('&amp;', '&', $this->url->link('marketplace/installer/ftp', 'user_token=' . $this->session->data['user_token'], true)),
							'path' => $path
						);

						// Send make and array of actions to carry out
						while ($entry = zip_read($zip)) {
							$zip_name = zip_entry_name($entry);

							// SQL
							if (substr($zip_name, 0, 11) == 'install.sql') {
								$json['step'][] = array(
									'text' => $this->language->get('text_sql'),
									'href' => str_replace('&amp;', '&', $this->url->link('marketplace/installer/sql', 'user_token=' . $this->session->data['user_token'], true)),
									'path' => $path
								);
							}

							// XML
							if (substr($zip_name, 0, 11) == 'install.xml') {
								$json['step'][] = array(
									'text' => $this->language->get('text_xml'),
									'href' => str_replace('&amp;', '&', $this->url->link('marketplace/installer/xml', 'user_token=' . $this->session->data['user_token'], true)),
									'path' => $path
								);
							}

							// PHP
							if (substr($zip_name, 0, 11) == 'install.php') {
								$json['step'][] = array(
									'text' => $this->language->get('text_php'),
									'href' => str_replace('&amp;', '&', $this->url->link('marketplace/installer/php', 'user_token=' . $this->session->data['user_token'], true)),
									'path' => $path
								);
							}

							// Compare admin files
							$file = DIR_APPLICATION . substr($zip_name, 13);

							if (is_file($file) && substr($zip_name, 0, 13) == 'upload/admin/') {
								$json['overwrite'][] = substr($zip_name, 7);
							}

							// Compare catalog files
							$file = DIR_CATALOG . substr($zip_name, 15);

							if (is_file($file) && substr($zip_name, 0, 15) == 'upload/catalog/') {
								$json['overwrite'][] = substr($zip_name, 7);
							}

							// Compare image files
							$file = DIR_IMAGE . substr($zip_name, 13);

							if (is_file($file) && substr($zip_name, 0, 13) == 'upload/image/') {
								$json['overwrite'][] = substr($zip_name, 7);
							}

							// Compare system files
							$file = DIR_SYSTEM . substr($zip_name, 14);

							if (is_file($file) && substr($zip_name, 0, 14) == 'upload/system/') {
								$json['overwrite'][] = substr($zip_name, 7);
							}
						}

						// Clear temporary files
						$json['step'][] = array(
							'text' => $this->language->get('text_remove'),
							'href' => str_replace('&amp;', '&', $this->url->link('marketplace/installer/remove', 'user_token=' . $this->session->data['user_token'], true)),
							'path' => $path
						);

						zip_close($zip);
					} else {
						$json['error'] = $this->language->get('error_unzip');
					}
				} else {
					$json['error'] = $this->language->get('error_file');
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
 
	public function unzip() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Sanitize the filename
		$file = DIR_UPLOAD . $this->request->post['path'] . '/upload.zip';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(DIR_UPLOAD . $this->request->post['path']);
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($file);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function xml() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . $this->request->post['path'] . '/install.xml';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$this->load->model('setting/modification');

			// If xml file just put it straight into the DB
			$xml = file_get_contents($file);

			if ($xml) {
				try {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($xml);

					$name = $dom->getElementsByTagName('name')->item(0);

					if ($name) {
						$name = $name->nodeValue;
					} else {
						$name = '';
					}

					$code = $dom->getElementsByTagName('code')->item(0);

					if ($code) {
						$code = $code->nodeValue;

						// Check to see if the modification is already installed or not.
						$modification_info = $this->model_setting_modification->getModificationByCode($code);

						if ($modification_info) {
							$json['error'] = sprintf($this->language->get('error_exists'), $modification_info['name']);
						}
					} else {
						$json['error'] = $this->language->get('error_code');
					}

					$author = $dom->getElementsByTagName('author')->item(0);

					if ($author) {
						$author = $author->nodeValue;
					} else {
						$author = '';
					}

					$version = $dom->getElementsByTagName('version')->item(0);

					if ($version) {
						$version = $version->nodeValue;
					} else {
						$version = '';
					}

					$link = $dom->getElementsByTagName('link')->item(0);

					if ($link) {
						$link = $link->nodeValue;
					} else {
						$link = '';
					}

					$modification_data = array(
						'extension_download_id' => 0,
						'name'                  => $name,
						'code'                  => $code,
						'author'                => $author,
						'version'               => $version,
						'link'                  => $link,
						'xml'                   => $xml,
						'status'                => 1
					);

					if (!$json) {
						$this->model_setting_modification->addModification($modification_data);
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_UPLOAD . $this->request->post['path'];
		
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory);

			while (count($path) != 0) {
				$next = array_shift($path);

				// We have to use scandir function because glob will not pick up dot files.
				foreach (array_diff(scandir($next), array('.', '..')) as $file) {
					$file = $next . '/' . $file;

					if (is_dir($file)) {
						$path[] = $file;
					}

					$files[] = $file;
				}
			}

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

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$directories = glob(DIR_UPLOAD . 'temp-*', GLOB_ONLYDIR);

			if ($directories) {
				foreach ($directories as $directory) {
					// Get a list of files ready to upload
					$files = array();

					$path = array($directory);

					while (count($path) != 0) {
						$next = array_shift($path);

						// We have to use scandir function because glob will not pick up dot files.
						foreach (array_diff(scandir($next), array('.', '..')) as $file) {
							$file = $next . '/' . $file;

							if (is_dir($file)) {
								$path[] = $file;
							}

							$files[] = $file;
						}
					}

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
			}

			$json['success'] = $this->language->get('text_clear');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}