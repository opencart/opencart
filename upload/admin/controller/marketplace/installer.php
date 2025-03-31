<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class Installer
 *
 * @package Opencart\Admin\Controller\Marketplace
 */
class Installer extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
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

		// Use the configuration option to get the max file size
		$data['error_upload_size'] = sprintf($this->language->get('error_file_size'), ini_get('upload_max_filesize'));

		$data['config_file_max_size'] = ((int)preg_filter('/[^0-9]/', '', ini_get('upload_max_filesize')) * 1024 * 1024);

		$data['upload'] = $this->url->link('tool/installer.upload', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		if (isset($this->request->get['filter_extension_download_id'])) {
			$data['filter_extension_download_id'] = (int)$this->request->get['filter_extension_download_id'];
		} else {
			$data['filter_extension_download_id'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('marketplace/installer');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		$this->load->language('marketplace/installer');

		if (isset($this->request->get['filter_extension_download_id'])) {
			$filter_extension_download_id = (int)$this->request->get['filter_extension_download_id'];
		} else {
			$filter_extension_download_id = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		// Extension
		$this->load->model('setting/extension');

		// Look for any new extensions
		$files = glob(DIR_STORAGE . 'marketplace/*.ocmod.zip');

		foreach ($files as $file) {
			$code = basename($file, '.ocmod.zip');

			$install_info = $this->model_setting_extension->getInstallByCode($code);

			if (!$install_info) {
				// Unzip the files
				$zip = new \ZipArchive();

				if ($zip->open($file, \ZipArchive::RDONLY)) {
					$install_info = json_decode($zip->getFromName('install.json'), true);

					if ($install_info) {
						$keys = [
							'extension_id',
							'extension_download_id',
							'name',
							'description',
							'code',
							'version',
							'author',
							'link'
						];

						foreach ($keys as $key) {
							if (!isset($install_info[$key])) {
								$install_info[$key] = '';
							}
						}

						$extension_data = [
							'extension_id'          => $install_info['extension_id'],
							'extension_download_id' => $install_info['extension_download_id'],
							'name'                  => strip_tags($install_info['name']),
							'description'           => nl2br(strip_tags($install_info['description'])),
							'code'                  => $code,
							'version'               => $install_info['version'],
							'author'                => $install_info['author'],
							'link'                  => $install_info['link']
						];

						$this->model_setting_extension->addInstall($extension_data);
					}

					$zip->close();
				}
			}
		}

		// Extension
		$data['extensions'] = [];

		$filter_data = [
			'filter_extension_download_id' => $filter_extension_download_id,
			'start'                        => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'                        => $this->config->get('config_pagination_admin')
		];

		$results = $this->model_setting_extension->getInstalls($filter_data);

		foreach ($results as $result) {
			if ($result['extension_id']) {
				$link = $this->url->link('marketplace/marketplace.info', 'user_token=' . $this->session->data['user_token'] . '&extension_id=' . $result['extension_id']);
			} elseif ($result['link']) {
				$link = $result['link'];
			} else {
				$link = '';
			}

			$data['extensions'][] = [
				'link'       => $link,
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'install'    => $this->url->link('marketplace/installer.install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'uninstall'  => $this->url->link('marketplace/installer.uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'delete'     => $this->url->link('marketplace/installer.delete', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id'])
			] + $result;
		}

		// Total Installs
		$extension_total = $this->model_setting_extension->getTotalInstalls($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $extension_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketplace/installer.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($extension_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($extension_total - $this->config->get('config_pagination_admin'))) ? $extension_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $extension_total, ceil($extension_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('marketplace/installer_extension', $data);
	}

	/**
	 * Upload
	 *
	 * @return void
	 */
	public function upload(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		// 1. Validate the file uploaded.
		if (isset($this->request->files['file']['name'])) {
			$filename = basename($this->request->files['file']['name']);
			$code = basename($filename, '.ocmod.zip');

			// Use the temporary upload path
			$temp_file = $this->request->files['file']['tmp_name'];

			// Initialise ZipArchive
			$zip = new \ZipArchive();

			// Zip error codes
			$zip_errors = [
				\ZipArchive::ER_EXISTS => $this->language->get('error_zip_exists'),
				\ZipArchive::ER_INCONS => $this->language->get('error_zip_incons'),
				\ZipArchive::ER_INVAL  => $this->language->get('error_zip_inval'),
				\ZipArchive::ER_MEMORY => $this->language->get('error_zip_memory'),
				\ZipArchive::ER_NOENT  => $this->language->get('error_zip_noent'),
				\ZipArchive::ER_NOZIP  => $this->language->get('error_zip_nozip'),
				\ZipArchive::ER_OPEN   => $this->language->get('error_zip_open'),
				\ZipArchive::ER_READ   => $this->language->get('error_zip_read'),
				\ZipArchive::ER_SEEK   => $this->language->get('error_zip_seek'),
			];

			// Check if the zip is valid
			$result_code = $zip->open($temp_file);

			if ($result_code !== true) {
				$json['error'] = $zip_errors[$result_code] ?? $this->language->get('error_unknown');

				if (is_file($temp_file)) {
					unlink($temp_file);
				}

				$this->response->setOutput(json_encode($json));

				return;
			}

			$zip->close();

			// 2. Validate the filename.
			if (!oc_validate_length($filename, 1, 128)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// 3. Validate is ocmod file.
			if (substr($filename, -10) != '.ocmod.zip') {
				$json['error'] = $this->language->get('error_file_type');
			}

			// 4. Check if there is already a file.
			$file = DIR_STORAGE . 'marketplace/' . $filename;

			if (is_file($file)) {
				$json['error'] = $this->language->get('error_file_exists');

				unlink($this->request->files['file']['tmp_name']);
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}

			if ($this->model_setting_extension->getInstallByCode($code)) {
				$json['error'] = $this->language->get('error_installed');
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		// 5. Validate if the file can be opened and there is install.json that can be read.
		if (!$json) {
			move_uploaded_file($this->request->files['file']['tmp_name'], $file);

			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file, \ZipArchive::RDONLY)) {
				$install_info = json_decode($zip->getFromName('install.json'), true);

				if ($install_info) {
					$keys = [
						'extension_id',
						'extension_download_id',
						'name',
						'description',
						'code',
						'version',
						'author',
						'link'
					];

					foreach ($keys as $key) {
						if (!isset($install_info[$key])) {
							$install_info[$key] = '';
						}
					}
				} else {
					$json['error'] = $this->language->get('error_install');
				}

				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			// Extension
			$extension_data = [
				'extension_id'          => 0,
				'extension_download_id' => 0,
				'name'                  => $install_info['name'],
				'description'           => $install_info['description'],
				'code'                  => $code,
				'version'               => $install_info['version'],
				'author'                => $install_info['author'],
				'link'                  => $install_info['link']
			];

			$this->load->model('setting/extension');

			$this->model_setting_extension->addInstall($extension_data);

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = (int)$this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Extension
		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['code'] . '.ocmod.zip';

			if (!is_file($file)) {
				$json['error'] = sprintf($this->language->get('error_file'), $extension_install_info['code'] . '.ocmod.zip');
			}

			if ($page == 1 && is_dir(DIR_EXTENSION . $extension_install_info['code'] . '/')) {
				$json['error'] = sprintf($this->language->get('error_directory_exists'), $extension_install_info['code'] . '/');
			}

			if ($page > 1 && !is_dir(DIR_EXTENSION . $extension_install_info['code'] . '/')) {
				$json['error'] = sprintf($this->language->get('error_directory'), $extension_install_info['code'] . '/');
			}
		} else {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file, \ZipArchive::RDONLY)) {
				$total = $zip->numFiles;
				$limit = 200;

				$start = ($page - 1) * $limit;
				$end = $start > ($total - $limit) ? $total : ($start + $limit);

				// Check if any of the files already exist.
				for ($i = $start; $i < $end; $i++) {
					$source = $zip->getNameIndex($i);

					$destination = str_replace('\\', '/', $source);

					// Only extract the contents of the upload folder
					$path = $extension_install_info['code'] . '/' . $destination;
					$base = DIR_EXTENSION;
					$prefix = '';

					// OCMOD files should not be copied across
					if (substr($destination, 0, 6) == 'ocmod/') {
						continue;
					}

					// image > image
					if (substr($destination, 0, 6) == 'image/') {
						$path = $destination;
						$base = substr(DIR_IMAGE, 0, -6);
					}

					// We need to store the path differently for vendor folders.
					if (substr($destination, 0, 15) == 'system/storage/') {
						$path = substr($destination, 15);
						$base = DIR_STORAGE;
						$prefix = 'system/storage/';
					}

					// Must not have a path before files and directories can be moved
					$path_new = '';

					$directories = explode('/', dirname($path));

					foreach ($directories as $directory) {
						if (!$path_new) {
							$path_new = $directory;
						} else {
							$path_new = $path_new . '/' . $directory;
						}

						// To fix storage location
						if (!is_dir($base . $path_new . '/') && mkdir($base . $path_new . '/', 0777)) {
							$this->model_setting_extension->addPath($extension_install_id, $prefix . $path_new);
						}
					}

					// If check if the path is not directory and check there is no existing file
					if (substr($source, -1) != '/') {
						if (!is_file($base . $path) && file_put_contents($base . $path, $zip->getFromIndex($i)) !== false) {
							$this->model_setting_extension->addPath($extension_install_id, $prefix . $path);
						}
					}
				}

				$zip->close();

				$this->model_setting_extension->editStatus($extension_install_id, true);
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_install'), $start, $end, $total);

			$url = '';

			if (isset($this->request->get['extension_install_id'])) {
				$url .= '&extension_install_id=' . $this->request->get['extension_install_id'];
			}

			if ($end < $total) {
				$json['next'] = $this->url->link('marketplace/installer.install', 'user_token=' . $this->session->data['user_token'] . $url . '&page=' . ($page + 1), true);
			} else {
				$json['next'] = $this->url->link('marketplace/installer.xml', 'user_token=' . $this->session->data['user_token'] . $url, true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Xml
	 *
	 * @return void
	 */
	public function xml(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = $this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Extension
		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['code'] . '.ocmod.zip';

			if (!is_file($file)) {
				$json['error'] = sprintf($this->language->get('error_file'), $extension_install_info['code'] . '.ocmod.zip');
			}
		} else {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file, \ZipArchive::RDONLY)) {
				// Modification
				$this->load->model('setting/modification');

				// If xml file, just put it straight into the DB
				// Check if any of the files already exist.
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$source = $zip->getNameIndex($i);

					if (substr($source, 0, 6) == 'ocmod/' && substr($source, -10) == '.ocmod.xml') {
						$code = basename($source, '.ocmod.xml');

						// Check to see if the modification is already installed or not.
						$modification_info = $this->model_setting_modification->getModificationByCode($code);

						if (!$modification_info) {
							$xml = $zip->getFromName($source);

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

									$description = $dom->getElementsByTagName('description')->item(0);

									if ($description) {
										$description = $description->nodeValue;
									} else {
										$description = '';
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

									$modification_data = [
										'extension_install_id' => $extension_install_id,
										'name'                 => strip_tags($name),
										'description'          => nl2br(strip_tags($description)),
										'code'                 => $code,
										'author'               => $author,
										'version'              => $version,
										'link'                 => $link,
										'xml'                  => $xml,
										'status'               => 0
									];

									$this->model_setting_modification->addModification($modification_data);
								} catch (\Exception $exception) {
									$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
								}
							}
						}
					}
				}
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_vendor');

			$json['next'] = $this->url->link('marketplace/installer.vendor', 'user_token=' . $this->session->data['user_token'], true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Vendor
	 *
	 * Generate new autoloader file
	 *
	 * @return void
	 */
	public function vendor(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->helper('vendor');

			oc_generate_vendor();

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Uninstall
	 *
	 * @return void
	 */
	public function uninstall(): void {
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

		// Extension
		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			if ($extension_install_info['code'] == 'opencart') {
				$json['error'] = $this->language->get('error_default');
			}

			// Validate if extension being uninstalled
			$extension_total = $this->model_setting_extension->getTotalExtensionsByExtension($extension_install_info['code']);

			if ($extension_total) {
				$json['error'] = sprintf($this->language->get('error_uninstall'), $extension_total);
			}
		} else {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			$files = [];

			// Make path into an array
			$directory = [DIR_EXTENSION . $extension_install_info['code'] . '/'];

			// While the path array is still populated keep looping through
			while (count($directory) != 0) {
				$next = array_shift($directory);

				if (is_dir($next)) {
					foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
						// If directory add to path array
						$directory[] = $file;
					}
				}

				// Add the file to the files to be deleted array
				$files[] = $next;
			}

			// Reverse sort the file array
			rsort($files);

			foreach ($files as $file) {
				// If file just delete
				if (is_file($file)) {
					unlink($file);
				}

				// If directory use the remove directory function
				if (is_dir($file)) {
					rmdir($file);
				}
			}

			// Remove extension directory and files
			$results = $this->model_setting_extension->getPathsByExtensionInstallId($extension_install_id);

			rsort($results);

			foreach ($results as $result) {
				$path = '';

				// Remove images
				if (substr($result['path'], 0, 6) == 'image/') {
					$path = DIR_IMAGE . substr($result['path'], 6);
				}

				// Remove vendor files or any connected extensions that was also installed.
				if (substr($result['path'], 0, 15) == 'system/storage/') {
					$path = DIR_STORAGE . substr($result['path'], 15);
				}

				// Check if the location exists or not
				$path_total = $this->model_setting_extension->getTotalPaths($result['path']);

				if ($path_total < 2) {
					if (is_file($path)) {
						unlink($path);
					}

					if (is_dir($path)) {
						rmdir($path);
					}
				}

				$this->model_setting_extension->deletePath($result['extension_path_id']);
			}

			// Remove extension directory
			$this->model_setting_extension->editStatus($extension_install_id, false);

			// Remove any OCMOD modifications
			$this->load->model('setting/modification');

			$this->model_setting_modification->deleteModificationsByExtensionInstallId($extension_install_id);

			$json['text'] = $this->language->get('text_vendor');

			$url = '';

			if (isset($this->request->get['extension_install_id'])) {
				$url .= '&extension_install_id=' . $this->request->get['extension_install_id'];
			}

			$json['next'] = $this->url->link('marketplace/installer.vendor', 'user_token=' . $this->session->data['user_token'] . $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
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

		// Extension
		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info && $extension_install_info['code'] == 'opencart') {
			$json['error'] = $this->language->get('error_default');
		}

		if (!$extension_install_info) {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['code'] . '.ocmod.zip';

			// Remove file
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
