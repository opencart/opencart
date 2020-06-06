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

		if (isset($this->request->get['filter_extension_id'])) {
			$filter_extension_id = $this->request->get['filter_extension_id'];
		} else {
			$filter_extension_id = '';
		}

		$data['filter_extension_id'] = $filter_extension_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}

	public function extension() {
		$this->load->language('marketplace/installer');

		if (isset($this->request->get['filter_extension_id'])) {
			$filter_extension_id = $this->request->get['filter_extension_id'];
		} else {
			$filter_extension_id = '';
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
			'filter_extension_id' => $filter_extension_id,
			'sort'                => $sort,
			'order'               => $order,
			'start'               => ($page - 1) * $this->config->get('config_pagination'),
			'limit'               => $this->config->get('config_pagination')
		);

		$extension_total = $this->model_setting_extension->getTotalInstalls($filter_data);

		$results = $this->model_setting_extension->getInstalls($filter_data);
		
		foreach ($results as $result) {
			$data['extensions'][] = array(
				'name'                 => $result['name'],
				'version'              => $result['version'],
				'image'                => $result['image'],
				'author'               => $result['author'],
				'status'               => $result['status'],
				'link'                 => $this->url->link('marketplace/marketplace/info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id']),
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'install'     => $this->url->link('marketplace/installer/install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'uninstall'   => $this->url->link('marketplace/installer/uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'delete'               => $this->url->link('marketplace/installer/delete', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id'])
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

		// Make sure the file name is stored in the session.
		if (!isset($this->session->data['install'])) {
			$json['error'] = $this->language->get('error_file');
		} elseif (!is_file(DIR_STORAGE . 'marketplace/' . $this->session->data['install'] . '.tmp')) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_unzip');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/unzip', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unzip() {
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
			$json['error'] = $this->language->get('error_file');
		} elseif (!is_file(DIR_STORAGE . 'marketplace/' . $this->session->data['install'] . '.tmp')) {
			$json['error'] = $this->language->get('error_file');
		}

		// Sanitize the filename
		if (!$json) {
			$file = DIR_STORAGE . 'marketplace/' . $this->session->data['install'] . '.zip';

			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(DIR_STORAGE . 'extension/' . 'tmp-' . $this->session->data['install']);
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($file);

			$json['text'] = $this->language->get('text_move');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/move', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function move() {
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
		} elseif (!is_dir(DIR_STORAGE . 'marketplace/tmp-' . $this->session->data['install'] . '/')) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			$directory = DIR_STORAGE . 'marketplace/tmp-' . $this->session->data['install'] . '/';

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
			$json['text'] = $this->language->get('text_xml');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('marketplace/install/xml', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $extension_install_id));
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

		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getPathsByExtensionInstallId($extension_install_id);

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

				if (substr($result['path'], 0, 14) == 'system/library') {
					$source = DIR_SYSTEM . 'library/' . substr($result['path'], 15);
				}

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

			// Remove the install
			$this->model_setting_extension->deleteInstall($extension_install_id);

			// Remove any xml modifications
			$this->load->model('setting/modification');

			$this->model_setting_modification->deleteModificationsByExtensionInstallId($extension_install_id);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('marketplace/install');

		$json = array();

		if (!$this->user->hasPermission('modify', 'marketplace/install')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($this->session->data['install'])) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getPathsByExtensionInstallId($extension_install_id);

			$directory = DIR_STORAGE . 'marketplace/tmp-' . $this->session->data['install'] . '/';

			if (is_dir($directory)) {
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

				if (is_dir($directory)) {
					rmdir($directory);
				}
			}

			$file = DIR_STORAGE . 'marketplace/' . $this->session->data['install'] . '.tmp';

			if (is_file($file)) {
				unlink($file);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}