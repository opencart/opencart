<?php
class ControllerMarketplaceInstaller extends Controller {
	public function index() {
		$this->load->language('marketplace/installer');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/installer', 'user_token=' . $this->session->data['user_token'])
		);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['filter_extension_download_id'])) {
			$data['filter_extension_download_id'] = $this->request->get['filter_extension_download_id'];
		} else {
			$data['filter_extension_download_id'] = '';
		}

		/*
		// Code to grab pre installed extensions
		//$extensions = $this->model_setting_extension->getDownloaded('analytics');

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/core&version=' . VERSION);

		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		$response_info = json_decode($response, true);

		//foreach ($response_info['extension'] as $extension) {
		//	$this->model_setting_extension->addExtension($extension, '');
		//}

		//echo VERSION . "\n";
		//echo $response;
		*/

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}

	public function extension() {
		$this->load->language('marketplace/installer');

		if (isset($this->request->get['filter_extension_download_id'])) {
			$filter_extension_download_id = $this->request->get['filter_extension_download_id'];
		} else {
			$filter_extension_download_id = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['extensions'] = array();
		
		$this->load->model('setting/extension');

		$filter_data = array(
			'filter_extension_download_id' => $filter_extension_download_id,
			'sort'                         => $sort,
			'order'                        => $order,
			'start'                        => ($page - 1) * $this->config->get('config_pagination'),
			'limit'                        => $this->config->get('config_pagination')
		);

		$extension_total = $this->model_setting_extension->getTotalInstalls($filter_data);

		$results = $this->model_setting_extension->getInstalls($filter_data);
		
		foreach ($results as $result) {
			$data['extensions'][] = array(
				'name'       => $result['name'],
				'version'    => $result['version'],
				'image'      => $result['image'],
				'author'     => $result['author'],
				'status'     => $result['status'],
				'link'       => $this->url->link('marketplace/marketplace/info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'install'    => $this->url->link('marketplace/installer/install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'uninstall'  => $this->url->link('marketplace/installer/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'delete'     => $this->url->link('marketplace/installer/delete', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id'])
			);
		}

		$data['results'] = sprintf($this->language->get('text_pagination'), ($extension_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($extension_total - 10)) ? $extension_total : ((($page - 1) * 10) + 10), $extension_total, ceil($extension_total / 10));

		$url = '';

		if (isset($this->request->get['filter_extension_id'])) {
			$url .= '&filter_extension_id=' . $this->request->get['filter_extension_id'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('marketplace/installer/extension', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_version'] = $this->url->link('marketplace/installer/extension', 'user_token=' . $this->session->data['user_token'] . '&sort=version' . $url);
		$data['sort_date_added'] = $this->url->link('marketplace/installer/extension', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_date_added' . $url);

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $extension_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('marketplace/installer/extension', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('marketplace/installer_extension', $data));
	}

	public function upload() {
		$this->load->language('marketplace/installer');

		$json = array();

		// Check for any install directories
		if (isset($this->request->files['file']['name'])) {



			$filename = $this->request->files['file']['name'];

			if (substr($filename, -10) != '.ocmod.zip') {
				$json['error'] = $this->language->get('error_filetype');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}



		} else {
			$json['error'] = $this->language->get('error_upload');
		}




		if (!$json) {




			$file = DIR_STORAGE . 'marketplace/' . basename($filename, '.ocmod.zip');

			move_uploaded_file($this->request->files['file']['tmp_name'], $file);

			if (is_file($file)) {

				// Unzip the files
				$zip = new ZipArchive();

				if ($zip->open($file)) {
					$zip->extractTo(DIR_STORAGE . 'marketplace/' . basename($extension_install_info['filename'], '.ocmod.zip') . '/');
					$zip->close();
				}


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
								'filename'             => $filename,
								'link'                 => $link,
								'status'               => 1
							);

							$this->model_setting_modification->addModification($extension_data);
						}
					} catch(Exception $exception) {
						$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
					}

					$this->load->model('setting/extension');

					$this->model_setting_extension->addInstall($extension_data);
				}

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = $this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if (!$extension_install_info) {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_unzip');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/installer/unzip', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unzip() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = $this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['filename'];

			if (!is_file($file)) {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo( DIR_STORAGE . 'marketplace/' . basename($extension_install_info['filename'], '.ocmod.zip') . '/');
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			$json['text'] = $this->language->get('text_move');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/installer/move', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function move() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = $this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$directory = DIR_STORAGE . 'marketplace/' . basename($extension_install_info['filename'], '.ocmod.zip') . '/';

			if (!is_dir($directory)) {
				$json['error'] = $this->language->get('error_directory');
			}
		} else {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
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

			$this->load->model('setting/extension');

			foreach ($files as $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($directory . 'upload/')));

				$path = '';

				// Admin
				if (substr($destination, 0, 5) == 'admin') {
					$path = DIR_EXTENSION . $destination;
				}

				// Catalog
				if (substr($destination, 0, 7) == 'catalog') {
					$path = DIR_EXTENSION . $destination;
				}

				// Image
				if (substr($destination, 0, 5) == 'image') {
					$path = DIR_IMAGE . $destination;
				}

				// Config
				if (substr($destination, 0, 13) == 'system/config') {
					$path = DIR_SYSTEM . $destination;
				}

				// Helper
				if (substr($destination, 0, 13) == 'system/helper') {
					$path = DIR_EXTENSION . $destination;
				}

				// Library
				if (substr($destination, 0, 14) == 'system/library') {
					$path = DIR_EXTENSION . $destination;
				}

				// Storage
				if (substr($destination, 0, 13) == 'storage/vendor') {
					$path = DIR_STORAGE . $destination;
				}

				if (is_dir($file) && !is_dir($path)) {
					if (mkdir($path, 0777)) {
						$this->model_setting_extension->addPath($extension_install_id, $destination);
					}
				}

				if (is_file($file)) {
					if (rename($file, $path)) {
						$this->model_setting_extension->addPath($extension_install_id, $destination);
					}
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

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function uninstall() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = $this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$directory = DIR_EXTENSION . basename($extension_install_info['filename'], '.ocmod.zip') . '/';

			if (!is_dir($directory)) {
				$json['error'] = $this->language->get('error_directory');
			}
		} else {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			$results = $this->model_setting_extension->getPathsByExtensionInstallId($extension_install_id);

			rsort($results);

			foreach ($results as $result) {
				$path = '';

				// Admin
				if (substr($result['path'], 0, 5) == 'admin') {
					$path = DIR_EXTENSION . $result['path'];
				}

				// Catalog
				if (substr($result['path'], 0, 7) == 'catalog') {
					$path = DIR_EXTENSION . $result['path'];
				}

				// Image
				if (substr($result['path'], 0, 5) == 'image') {
					$path = DIR_IMAGE . $result['path'];
				}

				// Config
				if (substr($result['path'], 0, 13) == 'system/config') {
					$path = DIR_SYSTEM . $result['path'];
				}

				// Helper
				if (substr($result['path'], 0, 13) == 'system/helper') {
					$path = DIR_EXTENSION . $result['path'];
				}

				// Library
				if (substr($result['path'], 0, 14) == 'system/library') {
					$path = DIR_EXTENSION . $result['path'];
				}

				// Storage
				if (substr($result['path'], 0, 13) == 'storage/vendor') {
					$path = DIR_STORAGE . $result['path'];
				}

				// Check if the location exists or not
				if (is_file($path)) {
					unlink($path);
				} elseif (is_dir($path)) {
					rmdir($path);
				}

				$this->model_setting_extension->deletePath($result['extension_path_id']);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('marketplace/installer');

		$json = array();

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = $this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['filename'];

			if (!is_file($file)) {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			// Remove file
			unlink($file);

			$this->model_setting_extension->deleteInstall($extension_install_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}