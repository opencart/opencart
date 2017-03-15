<?php
class ControllerMarketplaceInstall extends Controller {
	public function install() {
		$this->load->language('marketplace/install');

		$json = array();
			
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
			
		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!is_file(ini_get('upload_tmp_dir') . '/install.tmp')) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_unzip');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/unzip', 'user_token=' . $this->session->data['user_token'] . '&extension_download_id=' . $extension_download_id, true));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unzip() {
		$this->load->language('marketplace/install');

		$json = array();

		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}
		
		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		$file = ini_get('upload_tmp_dir') . '/install.tmp';
		
		if (!is_file($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		// Sanitize the filename
		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(ini_get('upload_tmp_dir') . '/install');
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($file);

			$json['text'] = $this->language->get('text_move');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/move', 'user_token=' . $this->session->data['user_token'] . '&extension_download_id=' . $extension_download_id, true));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function move() {
		$this->load->language('marketplace/install');

		$json = array();

		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = ini_get('upload_tmp_dir') . '/install/';

		if (is_dir($directory . 'upload/')) {
			$files = array();

			// Get a list of files ready to upload
			$path = array($directory . 'upload/*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					$files[] = $file;
				}
			}

			// First we need to do some checks
			foreach ($files as $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($directory . '/upload')));

				echo $destination . "\n";

				// Check if the file is not going into an allowed directory
				$allowed = array(
					'admin/controller/extension/',
					'admin/model/extension/',
					'admin/view/template/extension/',
					'catalog/controller/extension/',
					'catalog/model/extension/',
					'catalog/view/theme/',
					'system/vendor/',
					'image/',
					'system/vendor'
				);

				// Language Admin
				$data['languages'] = array();

				$folders = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);

				foreach ($folders as $folder) {
					$allowed[] = 'admin/language/' . basename($folder) . '/extension/';
				}

				// Language Catalog
				$folders = glob(DIR_CATALOG . 'language/*', GLOB_ONLYDIR);

				foreach ($folders as $folder) {
					$allowed[] = 'catalog/language/' . basename($folder) . '/extension/';
				}

				$safe = false;

				foreach ($allowed as $value) {
					if (strlen($destination) < strlen($value) && substr($value, 0, strlen($destination)) == $destination) {
						$safe = true;

						break;
					}

					if (strlen($destination) > strlen($value) && substr($destination, 0, strlen($value)) == $value) {
						$safe = true;

						break;
					}
				}
				
				if ($safe) {
					// Check if the copy location exists or not
					if (substr($destination, 0, 5) == 'admin') {
						$destination = DIR_APPLICATION . substr($destination, 6);
					}

					if (substr($destination, 0, 7) == 'catalog') {
						$destination = DIR_CATALOG . substr($destination, 8);
					}

					if (substr($destination, 0, 5) == 'image') {
						$destination = DIR_IMAGE . substr($destination, 6);
					}

					if (substr($destination, 0, 13) == substr('system/vendor', 0, strlen($destination))) {
						$destination = DIR_SYSTEM . 'vendor/' . substr($destination, 14);
					}

					if (is_file($destination)) {
						$json['error'] = sprintf($this->language->get('error_exists'), $destination);

						break;
					}
				} else {
					$json['error'] = sprintf($this->language->get('error_allowed'), $destination);

					break;
				}
			}

			if (!$json) {
				$this->load->model('setting/extension');

				foreach ($files as $file) {
					$destination = str_replace('\\', '/', substr($file, strlen($directory . '/install/upload/')));

					$path = '';

					if (substr($destination, 0, 5) == 'admin') {
						$path = DIR_APPLICATION . substr($destination, 6);
					}

					if (substr($destination, 0, 7) == 'catalog') {
						$path = DIR_CATALOG . substr($destination, 8);
					}

					if (substr($destination, 0, 5) == 'image') {
						$path = DIR_IMAGE . substr($destination, 6);
					}

					if (substr($destination, 0, 13) == 'system/vendor') {
						$path = DIR_SYSTEM . substr($destination, 14);
					}

					if (is_dir($file) && !is_dir($path)) {
						if (mkdir($path, 0777)) {
							$this->model_setting_extension->addPath($extension_download_id, $destination);
						} else {
							$json['error'] = sprintf($this->language->get('error_move'), $path);
						}
					}

					if (is_file($file)) {
						if (rename($file, $path)) {
							$this->model_setting_extension->addPath($extension_download_id, $destination);
						} else {
							$json['error'] = sprintf($this->language->get('error_move'), $file);
						}
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_xml');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/xml', 'user_token=' . $this->session->data['user_token'] . '&extension_download_id=' . $extension_download_id . '&download=' . $download, true));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function xml() {
		$this->load->language('marketplace/install');

		$json = array();
		
		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = ini_get('upload_tmp_dir') . '/install/install.xml';

		if (is_file($file)) {
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
							$json['error'] = sprintf($this->language->get('error_xml'), $modification_info['name']);
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

					if (!$json) {
						$modification_data = array(
							'extension_download_id' => $extension_download_id,
							'name'                  => $name,
							'code'                  => $code,
							'author'                => $author,
							'version'               => $version,
							'link'                  => $link,
							'xml'                   => $xml
						);

						$this->load->model('setting/modification');

						$this->model_setting_modification->addModification($modification_data);
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_remove');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/remove', 'user_token=' . $this->session->data['user_token'] . '&download=' . $download, true));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('marketplace/install');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = ini_get('upload_tmp_dir') . '/install/';

		if (!is_dir($directory)) {
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

			if (is_file(ini_get('upload_tmp_dir') . '/install.tmp')) {
				unlink(ini_get('upload_tmp_dir') . '/install.tmp');
			}

			if (is_dir($directory)) {
				rmdir($directory);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function uninstall() {
		$this->load->language('marketplace/install');

		$json = array();

		if (isset($this->request->get['extension_download_id'])) {
			$extension_download_id = $this->request->get['extension_download_id'];
		} else {
			$extension_download_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getPathsByExtensionDownloadId($extension_download_id);

			rsort($results);

			foreach ($results as $result) {
				$source = '';

				// Check if the copy location exists or not
				if (substr($result['path'], 0, 5) == 'admin') {
					$source = DIR_APPLICATION . substr($result['path'], 6);
				}

				if (substr($result['path'], 0, 7) == 'catalog') {
					$source = DIR_CATALOG . substr($result['path'], 8);
				}

				if (substr($result['path'], 0, 5) == 'image') {
					$source = DIR_IMAGE . substr($result['path'], 6);
				}

				if (substr($result['path'], 0, 13) == 'system/vendor') {
					$source = DIR_SYSTEM . substr($result['path'], 14);
				}

				if (is_file($source)) {
					unlink($source);
				}

				if (is_dir($source)) {
					// Get a list of files ready to upload
					$files = array();

					$path = array($source);

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

					unlink($source);
				}

				$this->model_setting_extension->deletePath($result['extension_install_id']);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
