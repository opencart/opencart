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

		//$sdsd = $this->load->controller('');

		//$extensions = $this->model_setting_extension->getDownloaded('analytics');
		/*
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

		if (!$extension_install_info || !is_file(DIR_STORAGE . 'marketplace/' . $extension_install_info['filename'])) {
			$json['error'] = $this->language->get('error_file');
		}

		if (is_dir(DIR_EXTENSION . basename($extension_install_info['filename'], '.ocmod.zip'))) {
			//$json['error'] = $this->language->get('error_installed');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			//admin/config-dist.php
			//C:/xampp/htdocs/opencart-master/upload/admin/config-dist.php

			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['filename'];

			if ($zip->open($file)) {
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$destination = $zip->getNameIndex($i);

					echo $destination . "\n";




					// Check if the copy location exists or not
					if (substr($destination, 0, 5) == 'admin') {
						$destination = DIR_EXTENSION . $destination;
					}

					if (substr($destination, 0, 7) == 'catalog') {
						$destination = DIR_EXTENSION . $destination;
					}

					if (substr($destination, 0, 5) == 'image') {
						$destination = DIR_IMAGE . substr($destination, 6);
					}

					if (substr($destination, 0, 6) == 'system') {
						$destination = DIR_SYSTEM . substr($destination, 7);
					}

					if (substr($destination, 0, 7) == 'storage') {
						$destination = DIR_STORAGE . substr($destination, 8);
					}

					echo $destination . "\n\n";

					//echo $destination . "\n";

					//$this->model_setting_extension->addPath($extension_install_id, $destination);


					if (is_file($destination)) {
						$json['error'] = $this->language->get('error_unzip');
					}

					$this->model_setting_extension->getPathByPath($result['extension_path_id']);
				}

			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			/*
			if (is_dir($directory)) {
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
			}
			*/
			//$json['text'] = $this->language->get('text_unzip');

			//$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/unzip', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unzip() {

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

		if (!$extension_install_info || !is_file(DIR_STORAGE . 'marketplace/' . $extension_install_info['filename'])) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {


			/*
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

			if (substr($destination, 0, 6) == 'system') {
				$destination = DIR_SYSTEM . substr($destination, 7);
			}

			if (substr($destination, 0, 7) == 'storage') {
				$destination = DIR_STORAGE . substr($destination, 8);
			}
			*/

			// Move Catalog and Admin to the extension folder
			$extract = array(
				'upload/catalog',
				'upload/admin'
			);

			$zip->extractTo(DIR_EXTENSION . substr($extension_install_info['filename'], 0, 6) . '/', $extract);

			// Move Images
			$zip->extractTo(DIR_IMAGE, array('upload/image'));

			// Move System folders
			$extract = array(
				'upload/system/config',
				'upload/system/helper',
				'upload/system/library'
			);

			$zip->extractTo(DIR_EXTENSION . substr($extension_install_info['filename'], 0, 6) . '/', $extract);


			// Move System folders
			$extract = array(
				'upload/system/storage/download',
				'upload/system/storage/upload',
				'upload/system/storage/vendor',
			);

			$zip->extractTo(DIR_EXTENSION . substr($extension_install_info['filename'], 0, 6) . '/', $extract);


			$zip->close();







			$directory = DIR_EXTENSION . $extension_install_info['code'] . '/';

			if (is_dir($directory)) {
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



				// A list of allowed directories to be written to
				$allowed = array(
					'admin/view/image/',
					'admin/view/javascript/',
					'admin/view/stylesheet/',
					'admin/view/template/',

					'catalog/view/javascript/',
					'catalog/view/theme/',

					'system/config/',
					'system/library/',

					'image/catalog/',
					'image/payment/'
				);

				// First we need to do some checks
				foreach ($files as $file) {
					$destination = str_replace('\\', '/', substr($file, strlen($directory . 'upload/')));

					$safe = false;

					foreach ($allowed as $value) {
						if (substr($value, 0, strlen($destination)) == $destination) {
							$safe = true;

							break;
						}

						if (substr($destination, 0, strlen($value)) == $value) {
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

						if (substr($destination, 0, 6) == 'system') {
							$destination = DIR_SYSTEM . substr($destination, 7);
						}

						if (substr($destination, 0, 7) == 'storage') {
							$destination = DIR_STORAGE . substr($destination, 8);
						}
					} else {
						$json['error'] = sprintf($this->language->get('error_allowed'), $destination);

						break;
					}
				}

				if (!$json) {
					$this->load->model('setting/extension');

					foreach ($files as $file) {
						$destination = str_replace('\\', '/', substr($file, strlen($directory . 'upload/')));

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

						if (substr($destination, 0, 6) == 'system') {
							$path = DIR_SYSTEM . substr($destination, 7);
						}

						// Added storage location
						if (substr($destination, 0, 7) == 'storage') {
							$path = DIR_STORAGE . substr($destination, 8);
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
				}
			}
		}

		if (!$json) {

			$json['success'] = str_replace('&amp;', '&', $this->url->link('marketplace/installer/xml', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id));
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

		if (!$extension_install_info || !is_dir(DIR_STORAGE . 'marketplace/' . $extension_install_info['filename'])) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {


			$results = $this->model_setting_extension->getPathsByExtensionInstallId($extension_install_id);

			rsort($results);

			foreach ($results as $result) {
				$source = '';

				// Check if the copy location exists or not
				if (substr($result['path'], 0, 5) == 'admin') {
					$source = DIR_EXTENSION . $result['code'];
				}

				if (substr($result['path'], 0, 7) == 'catalog') {
					$source = DIR_EXTENSION . substr($result['path'], 8);
				}

				if (substr($result['path'], 0, 5) == 'image') {
					$source = DIR_IMAGE . substr($result['path'], 6);
				}

				$allowed = array(
					'system/config',
					'system/helper',
					'system/library',
				);

				if (substr($result['path'], 0, 14) == 'system/library') {
					$source = DIR_SYSTEM . 'library/' . substr($result['path'], 15);
				}

				$allowed = array(
					'upload/system/storage/download',
					'upload/system/storage/upload',
					'upload/system/storage/vendor',
				);

				if (substr($result['path'], 0, 7) == 'storage') {
					$source = DIR_STORAGE . substr($result['path'], 8);
				}

				if (is_file($source)) {
					unlink($source);
				} elseif (is_dir($source)) {
					$files = glob($source . '/*');

					if (!count($files)) {
						rmdir($source);
					}
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

		if (!$extension_install_info || !is_file(DIR_STORAGE . 'marketplace/' . $extension_install_info['filename'])) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['filename'];

			if (is_file($file)) {
				unlink($file);
			}

			$this->model_setting_extension->deleteInstall($extension_install_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}