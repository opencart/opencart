<?php
namespace Opencart\Application\Controller\Marketplace;
class Installer extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('marketplace/installer');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/installer', 'user_token=' . $this->session->data['user_token'])
		];

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = $this->config->get('config_file_max_size');

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['filter_extension_id'])) {
			$data['filter_extension_download_id'] = (int)$this->request->get['filter_extension_download_id'];
		} else {
			$data['filter_extension_download_id'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}

	public function extension() {
		$this->load->language('marketplace/installer');

		if (isset($this->request->get['filter_extension_download_id'])) {
			$filter_extension_download_id = (int)$this->request->get['filter_extension_download_id'];
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
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['extensions'] = [];
		
		$this->load->model('setting/extension');

		$filter_data = [
			'filter_extension_download_id' => $filter_extension_download_id,
			'sort'                         => $sort,
			'order'                        => $order,
			'start'                        => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'                        => $this->config->get('config_pagination_admin')
		];

		$extension_total = $this->model_setting_extension->getTotalInstalls($filter_data);

		$results = $this->model_setting_extension->getInstalls($filter_data);
		
		foreach ($results as $result) {
			if ($result['extension_id']) {
				$link = $this->url->link('marketplace/marketplace|info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id']);
			} elseif ($result['link']) {
				$link = $result['link'];
			} else {
				$link = '';
			}

			$data['extensions'][] = [
				'name'       => $result['name'],
				'version'    => $result['version'],
				'author'     => $result['author'],
				'status'     => $result['status'],
				'link'       => $link,
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'install'    => $this->url->link('marketplace/installer|install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'uninstall'  => $this->url->link('marketplace/installer|uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'delete'     => $this->url->link('marketplace/installer|delete', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id'])
			];
		}

		$data['results'] = sprintf($this->language->get('text_pagination'), ($extension_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($extension_total - $this->config->get('config_pagination_admin'))) ? $extension_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $extension_total, ceil($extension_total / $this->config->get('config_pagination_admin')));

		$url = '';

		if (isset($this->request->get['filter_extension_id'])) {
			$url .= '&filter_extension_id=' . $this->request->get['filter_extension_id'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('marketplace/installer|extension', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_version'] = $this->url->link('marketplace/installer|extension', 'user_token=' . $this->session->data['user_token'] . '&sort=version' . $url);
		$data['sort_date_added'] = $this->url->link('marketplace/installer|extension', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $extension_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketplace/installer|extension', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('marketplace/installer_extension', $data));
	}

	public function upload() {
		$this->load->language('marketplace/installer');

		$json = [];

		// Check for any install directories
		if (isset($this->request->files['file']['name'])) {
			$filename = basename($this->request->files['file']['name']);

			if ((utf8_strlen($filename) < 1) || (utf8_strlen($filename) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			}

			if (substr($filename, -10) != '.ocmod.zip') {
				$json['error'] = $this->language->get('error_filetype');
			}

			if (is_file($filename)) {
				$json['error'] = $this->language->get('error_exists');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$file = DIR_STORAGE . 'marketplace/' . $filename;

			move_uploaded_file($this->request->files['file']['tmp_name'], $file);

			if (is_file($file)) {
				// Unzip the files
				$zip = new \ZipArchive();

				if ($zip->open($file)) {
					$xml = $zip->getFromName('install.xml');

					$zip->close();
				}

				// If xml file just put it straight into the DB
				if ($xml) {
					try {
						$dom = new \DOMDocument('1.0', 'UTF-8');
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
					} catch(\Exception $exception) {
						$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
					}

					if (!$json) {
						$extension_data = [
							'extension_id'          => 0,
							'extension_download_id' => 0,
							'name'                  => $name,
							'code'              	=> basename($filename, '.ocmod.zip'),
							'version'               => $version,
							'author'                => $author,
							'link'                  => $link
						];

						$this->load->model('setting/extension');

						$this->model_setting_extension->addInstall($extension_data);
					}
				}

				$json['success'] = $this->language->get('text_upload');
			} else {
				$json['error'] = sprintf($this->language->get('error_file'), $filename);
			}
		} else {
			unset($this->request->files['file']['tmp_name']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		$this->load->language('marketplace/installer');

		$json = [];

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = (int)$this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['code'] . '.ocmod.zip';

			if (!is_file($file)) {
				$json['error'] = sprintf($this->language->get('error_file'), $extension_install_info['code'] . '.ocmod.zip');
			}

			if (is_dir(DIR_EXTENSION . $extension_install_info['code'] . '/')) {
				$json['error'] = sprintf($this->language->get('error_exists'), $extension_install_info['code'] . '/');
			}
		} else {
			$json['error'] = $this->language->get('error_install');
		}

		$extract = [];

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file)) {
				// Check if any of the files already exist.
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$source = $zip->getNameIndex($i);

					// Only extract the contents of the upload folder
					if (substr($source, 0, strlen($extension_install_info['code'])) == $extension_install_info['code']) {
						$remove = strlen($extension_install_info['code'] . '/upload/');
					} else {
						$remove = strlen('upload/');
					}

					$destination = str_replace('\\', '/', substr($source, $remove));

					$path = '';
					$base = '';

					// admin > extension/{directory}/admin
					if (substr($destination, 0, 6) == 'admin/') {
						$path = $extension_install_info['code'] . '/' . $destination;
						$base = DIR_EXTENSION;
					}

					// catalog > extension/{directory}/catalog
					if (substr($destination, 0, 8) == 'catalog/') {
						$path = $extension_install_info['code'] . '/' . $destination;
						$base = DIR_EXTENSION;
					}

					// image > image
					if (substr($destination, 0, 6) == 'image/') {
						$path = $destination;
						$base = substr(DIR_IMAGE, 0, -6);
					}

					// Add the system directory if it doesn't exist.
					if ($destination == 'system/') {
						$path = $extension_install_info['code'] . '/' . $destination;
						$base = DIR_EXTENSION;
					}

					// Config
					if (substr($destination, 0, 14) == 'system/config/') {
						$path = $extension_install_info['code'] . '/' . $destination;
						$base = DIR_EXTENSION;
					}

					// Helper
					if (substr($destination, 0, 14) == 'system/helper/') {
						$path = $extension_install_info['code'] . '/' . $destination;
						$base = DIR_EXTENSION;
					}

					// Library
					if (substr($destination, 0, 15) == 'system/library/') {
						$path = $extension_install_info['code'] . '/' . $destination;
						$base = DIR_EXTENSION;
					}

					// We need to store the path differently for vendor folders.
					if (substr($destination, 0, 22) == 'system/storage/vendor/') {
						$path = substr($destination, 15);
						$base = DIR_STORAGE;
					}

					if ($path) {
						if (substr($path, -1) != '/' && is_file($base . $path)) {
							$json['error'] = sprintf($this->language->get('error_exists'), $destination);

							break;
						}

						if (!is_dir($base . $path)) {
							$extract[] = [
								'source'      => $source,
								'destination' => $destination,
								'base'        => $base,
								'path'        => $path
							];
						}
					}
				}

				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			// Add extension directory
			mkdir(DIR_EXTENSION . $extension_install_info['code'], 0777);

			foreach ($extract as $copy) {
				// Must not have a path before files and directories can be moved
				if (substr($copy['path'], -1) == '/' && mkdir($copy['base'] . $copy['path'], 0777)) {
					$this->model_setting_extension->addPath($extension_install_id, $copy['path']);
				}

				// If check if the path is not directory and check there is no existing file
				if (substr($copy['path'], -1) != '/' && copy('zip://' . $file . '#' . $copy['source'], $copy['base'] . $copy['path'])) {
					$this->model_setting_extension->addPath($extension_install_id, $copy['path']);
				}
			}

			$this->model_setting_extension->editStatus($extension_install_id, 1);

			$json['success'] = $this->language->get('text_install');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function uninstall() {
		$this->load->language('marketplace/installer');

		$json = [];

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = (int)$this->request->get['extension_install_id'];
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
			$results = $this->model_setting_extension->getPathsByExtensionInstallId($extension_install_id);

			rsort($results);

			foreach ($results as $result) {
				$path = '';

				// Remove extension directory and files
				if (substr($result['path'], 0, strlen($extension_install_info['code'])) == $extension_install_info['code']) {
					$path = DIR_EXTENSION . $result['path'];
				}

				// Remove images
				if (substr($result['path'], 0, 6) == 'image/') {
					$path = DIR_IMAGE . substr($result['path'], 6);
				}

				// Remove vendor files
				if (substr($result['path'], 0, 7) == 'vendor/') {
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

			// Remove extension directory
			rmdir(DIR_EXTENSION . $extension_install_info['code'] . '/');

			$this->model_setting_extension->editStatus($extension_install_id, 0);

			$json['success'] = $this->language->get('text_uninstall');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('marketplace/installer');

		$json = [];

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = (int)$this->request->get['extension_install_id'];
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
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['code'] . '.ocmod.zip';

			// Remove file
			if (is_file($file)) {
				unlink($file);
			}

			$this->model_setting_extension->deleteInstall($extension_install_id);

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
