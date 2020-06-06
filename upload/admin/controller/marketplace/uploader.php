<?php
class ControllerMarketplaceInstaller extends Controller {
	public function upload() {





		// Check if there is a install zip already there and delete
		$files = glob(DIR_STORAGE . 'marketplace/*.tmp');

		foreach ($files as $file) {
			if (is_file($file) && (filectime($file) < (time() - 5))) {
				unlink($file);
			}

			if (is_file($file)) {
				$json['error'] = $this->language->get('error_install');

				break;
			}
		}





		// Check for any install directories
		$directories = glob(DIR_STORAGE . 'marketplace/tmp-*');

		foreach ($directories as $directory) {
			if (is_dir($directory) && (filectime($directory) < (time() - 5))) {
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

				rmdir($directory);
			}

			if (is_dir($directory)) {
				$json['error'] = $this->language->get('error_install');

				break;
			}
		}



		if (isset($this->request->files['file']['name'])) {
			if (substr($this->request->files['file']['name'], -10) != '.ocmod.zip') {
				$json['error'] = $this->language->get('error_filetype');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}




		if (!$json) {
			$this->session->data['install'] = token(10);

			$file = DIR_STORAGE . 'marketplace/' . $this->session->data['install'] . '.tmp';

			move_uploaded_file($this->request->files['file']['tmp_name'], $file);

			if (is_file($file)) {
				$this->load->model('setting/extension');

				$extension_install_id = $this->model_setting_extension->addInstall($this->request->files['file']['name']);

				$json['text'] = $this->language->get('text_install');

				$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id));
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function xml() {
		$this->load->language('marketplace/install');

		$json = array();

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = $this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($this->session->data['install'])) {
			$json['error'] = $this->language->get('error_directory');
		} elseif (!is_dir(DIR_STORAGE . 'marketplace/' . 'tmp-' . $this->session->data['install'] . '/')) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			$file = DIR_STORAGE . 'marketplace/' . 'tmp-' . $this->session->data['install'] . '/install.xml';

			if (is_file($file)) {
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

						$version = $dom->getElementsByTagName('version')->item(0);

						if ($version) {
							$version = $version->nodeValue;
						} else {
							$version = '';
						}

						$author = $dom->getElementsByTagName('author')->item(0);

						if ($author) {
							$author = $author->nodeValue;
						} else {
							$author = '';
						}

						$link = $dom->getElementsByTagName('link')->item(0);

						if ($link) {
							$link = $link->nodeValue;
						} else {
							$link = '';
						}

						if (!$json) {
							$extension_data = array(
								'extension_install_id' => $extension_install_id,
								'name'                 => $name,
								'author'               => $author,
								'version'              => $version,
								'link'                 => $link,
								'xml'                  => $xml,
								'status'               => 1
							);

							$this->model_setting_modification->addModification($extension_data);
						}
					} catch(Exception $exception) {
						$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_clear');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/clear', 'user_token=' . $this->session->data['user_token']));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}