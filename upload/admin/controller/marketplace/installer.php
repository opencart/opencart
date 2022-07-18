<?php
namespace Opencart\Admin\Controller\Marketplace;

use \Composer\Semver\Comparator;

class Installer extends \Opencart\System\Engine\Controller {
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

		// Use the ini_get('upload_max_filesize') for the max file size
		[$code, $format_size, $size] = format_size();

		$data['error_upload_size'] = sprintf($this->language->get('error_format_' . $code), $format_size);

		$data['config_file_max_size'] = ((int)$size);

		$data['upload'] = $this->url->link('tool/installer|upload', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['filter_extension_download_id'] = (int)($this->request->get['filter_extension_download_id'] ?? '');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('marketplace/installer', $data));
	}

	public function list(): void {
		$this->load->language('marketplace/cron');

		$this->response->setOutput($this->getList());
	}

	public function getList(): string {
		$this->load->language('marketplace/installer');

		$filter_extension_download_id = (int)($this->request->get['filter_extension_download_id'] ?? '');

		$sort = $this->request->get['sort'] ?? 'name';

		$order = $this->request->get['order'] ?? 'ASC';

		$page = (int)($this->request->get['page'] ?? 1);

		$data['extensions'] = [];
		
		$this->load->model('setting/extension');
		
		$filter_data = [];

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

			$upgrade = false;
			$downgrade = false;
			$installed_extension_info = [];

			if($result['status']) {
				$installed_extension_info = json_decode(file_get_contents(DIR_EXTENSION . $result['codename'] . '/install.json'), true);
				$downgrade  = Comparator::lessThan($result['version'], $installed_extension_info['version']);
			} else {
				$this->removeExtensionFilesAndPaths($result);
			}

			$data['extensions'][] = [
				'name'       => $result['name'],
				'version'    => $installed_extension_info['version'] ?? $result['version'],
				'author'     => $result['author'],
				'status'     => $result['status'],
				'link'       => $link,
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'downgrade'  => $downgrade ? $this->url->link('marketplace/installer|replace&action=downgrade', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']) : false,
				'install'    => $this->url->link('marketplace/installer|install', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']),
				'uninstall'  => ($result['codename'] != 'opencart') ? $this->url->link('marketplace/installer|uninstall', 'user_token=' . $this->session->data['user_token'] . '&extension_install_id=' . $result['extension_install_id']) : false,
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

		return $this->load->view('marketplace/installer_extension', $data);
	}

	public function upload(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		// 1. Validate the file uploaded.
		if (isset($this->request->files['file']['name'])) {
			$filename = basename($this->request->files['file']['name']);

			// 2. Validate the filename.
			if ((utf8_strlen($filename) < 1) || (utf8_strlen($filename) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// 3. Validate is ocmod file.
			if (substr($filename, -10) != '.ocmod.zip') {
				$json['error'] = $this->language->get('error_file_type');
			}

			// 4. check if there is already a file
			$file = DIR_STORAGE . 'marketplace/' . $filename;

			if (is_file($file)) {
				unlink(DIR_STORAGE . 'marketplace/' . $this->request->files['file']['name']);
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		// 5. Validate if the file can be opened and there is a install.json that can be read.
		if (!$json) {
			move_uploaded_file($this->request->files['file']['tmp_name'], $file);

			// Unzip the files
			$zip = new \ZipArchive();
			
			$extension_data = [];

			if ($zip->open($file, \ZipArchive::RDONLY)) {
                $install_info = json_decode($zip->getFromName('install.json'), true);

				if ($install_info) { 

					if (!isset($install_info['name']) || !$install_info['name']) {
						$json['error'] = sprintf($this->language->get('error_name'), '');
					}

					if (!isset($install_info['version']) || !$install_info['version']) {
						$json['error'] = sprintf($this->language->get('error_version'), '');
					}

					if (!isset($install_info['codename']) || !$install_info['codename']) {
						$json['error'] = sprintf($this->language->get('error_codename'), '');
					}

					if(!$json) {
	
						$installed_info = $this->model_setting_extension->getInstallByCodename($install_info['codename']);

						$this->addInstall($install_info, $installed_info, $filename);

						if ($installed_info && Comparator::greaterThan($install_info['version'], $installed_info['version'])) {
							$json['upgrade'][] = $installed_info['extension_install_id'];
						} else {
							$json['success'] = $this->language->get('text_upload');
						}
					}
				} else {
                    $file_paths = [];
					
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $path = $zip->getNameIndex($i);
						
                        $path_parts = explode('/', $path);
						
                        if (!empty($path_parts[1]) && $path_parts[1] == 'install.json') {
                            $file_paths[] = $path;
                        }
                    }

                    if ($file_paths) {
                        foreach ($file_paths as $file_path) {
                            $path_parts = explode('/', $file_path);
							
                            $install_info = json_decode($zip->getFromName($path_parts[0] . '/install.json'), true);
							
							if (!isset($install_info['name']) || !$install_info['name']) {
								$error = sprintf($this->language->get('error_name'), $path_parts[0] . '/');
								continue;
							}
		
							if (!isset($install_info['version']) || !$install_info['version']) {
								$error = sprintf($this->language->get('error_version'), $path_parts[0] . '/');
								continue;
							}
		
							if (!isset($install_info['codename']) || !$install_info['codename']) {
								$error = sprintf($this->language->get('error_codename'), $path_parts[0] . '/');
								continue;
							}
							
                            $folder = $path_parts[0];	
							
							$installed_info = $this->model_setting_extension->getInstallByCodename($install_info['codename']);
							
							$this->addInstall($install_info, $installed_info, $filename);

							if($installed_info && Comparator::greaterThan($install_info['version'], $installed_info['version'])) {
								$json['upgrade'][] = $installed_info['extension_install_id'];
							} else {
								$json['success'] = $this->language->get('text_upload');
							}
                        }
						if (!isset($json['success']) && isset($error)) {
							$json['error'] = $error;
						}
                    } else {
                        $json['error'] = $this->language->get('error_install');
                    }
				}

				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}	
		if(isset($json['error']) && is_file($file)) {
			unlink($file);
		}	

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		$this->load->language('marketplace/installer');

		$json = [];
	
		$extension_install_id = (int)($this->request->get['extension_install_id'] ?? 0);
		
		$page = (int)($this->request->get['page'] ?? 1);

		$install_folders = [];	

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_installs = [];

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['package_name'] . '.ocmod.zip';

			if (!is_file($file)) {
				$json['error'] = sprintf($this->language->get('error_file'), $extension_install_info['package_name'] . '.ocmod.zip');
			}

		} else {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file)) {
				$total = $zip->numFiles;

				$extension_installs[] = $extension_install_info;
				
                $this->addExtensionFilesAndPaths($extension_installs, $file, $page, $zip, $json, $install_folders);

				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			
			$json['text'] = sprintf($this->language->get('text_progress'), 2, $total);

			$url = '';

			if (isset($this->request->get['extension_install_id'])) {
				$url .= '&extension_install_id=' . $this->request->get['extension_install_id'];
			}

			if (($page * 200) <= $total) {
				$json['next'] = $this->url->link('marketplace/installer|install', 'user_token=' . $this->session->data['user_token'] . $url . '&page=' . ($page + 1), true);
				$json['progress'] = (($page * 200)/$total) * 100;

				if ($install_folders) {
					$json['next'] .= '&install_folder=' . end($install_folders);
				}
			} else {
				$this->model_setting_extension->editStatus($extension_install_id, 1);
				$json['next'] = $this->url->link('marketplace/installer|vendor', 'user_token=' . $this->session->data['user_token'] . '&action=install' . $url, true);
				$json['progress'] = 100;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function replace(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		$extension_install_ids = ($this->request->post['extension_install_ids'] ?? []);

		if (!$extension_install_ids) {
			$extension_install_id = (int)($this->request->get['extension_install_id'] ?? 0);
			$extension_install_ids[] = $extension_install_id;
		}

		$page = (int)($this->request->get['page'] ?? 1);

		$install_folders = [];

		$action = $this->request->get['action'];

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_installs = [];

		foreach ($extension_install_ids as $extension_install_id) {
			$extension_installs[] = $this->model_setting_extension->getInstall($extension_install_id);
		}

		if ($extension_installs) {
			foreach ($extension_installs as $extension_install_info) {
				$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['package_name'] . '.ocmod.zip';
				if (!is_file($file)) {
					$json['error'] = sprintf($this->language->get('error_file'), $extension_install_info['package_name'] . '.ocmod.zip');
				}
			}
		} else {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file)) {			
                $total = $zip->numFiles;

				if ($page == 1) {
					foreach ($extension_installs as $extension_install_info) {
						$this->removeExtensionFilesAndPaths($extension_install_info);
					}
				}

                $this->addExtensionFilesAndPaths($extension_installs, $file, $page, $zip, $json, $install_folders);

				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_progress'), 2, $total);

			$url = '&action=' . $action;

			if (isset($this->request->get['extension_install_id'])) {
				$url .= '&extension_install_id=' . $this->request->get['extension_install_id'];
			} elseif(isset($this->request->post['extension_install_ids'])) {
				$json['extension_install_ids'] = json_encode($this->request->post['extension_install_ids']);
			}

			if (($page * 200) <= $total) {
				$json['next'] = $this->url->link('marketplace/installer|replace', 'user_token=' . $this->session->data['user_token'] . $url . '&page=' . ($page + 1), true);
				$json['progress'] = (($page * 200)/$total) * 100;

				if ($install_folders && isset($this->request->post['extension_install_ids'])) {
					$json['install_folders'] = $install_folders;
				} elseif ($install_folders && isset($this->request->get['extension_install_id'])) {
					$url .= '&install_folder=' . end($install_folders);
				}
			} else {
				$this->model_setting_extension->editStatus($extension_install_id, 1);
				$json['next'] = $this->url->link('marketplace/installer|vendor', 'user_token=' . $this->session->data['user_token'] . $url, true);
				$json['progress'] = 100;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function uninstall(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		$extension_install_id = (int)($this->request->get['extension_install_id'] ?? 0);

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info && $extension_install_info['codename'] == 'opencart') {
			$json['error'] = $this->language->get('error_default');
		}
		if (!$extension_install_info) {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {

			$this->uninstallExtensions($extension_install_info['codename']);
			
			$this->removeExtensionFilesAndPaths($extension_install_info);

			// Remove extension directory
			$this->model_setting_extension->editStatus($extension_install_id, 0);

			$json['next'] = $this->url->link('marketplace/installer|vendor', 'user_token=' . $this->session->data['user_token'] . '&action=uninstall', true);

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/* Generate new autoloader file */
	public function vendor(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$action = $this->request->get['action'];
			// Generate php autoload file
			$code = '<?php' . "\n";

			$files = glob(DIR_STORAGE . 'vendor/*/*/composer.json');

			foreach ($files as $file) {
				$output = json_decode(file_get_contents($file), true);

				$code .= '// ' . $output['name'] . "\n";

				if (isset($output['autoload'])) {
					$directory = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));

					// Autoload psr-4 files
					if (isset($output['autoload']['psr-4'])) {
						$autoload = $output['autoload']['psr-4'];

						foreach ($autoload as $namespace => $path) {
							if (!is_array($path)) {
								$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . $path . '\', true);' . "\n";
							} else {
								foreach ($path as $value) {
									$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . $value . '\', true);' . "\n";
								}
							}
						}
					}

					// Autoload psr-0 files
					if (isset($output['autoload']['psr-0'])) {
						$autoload = $output['autoload']['psr-0'];

						foreach ($autoload as $namespace => $path) {
							if (!is_array($path)) {
								$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . $path . '\', true);' . "\n";
							} else {
								foreach ($path as $value) {
									$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $directory . '/' . $value . '\', true);' . "\n";
								}
							}
						}
					}

					// Autoload classmap
					if (isset($output['autoload']['classmap'])) {
						$autoload = [];

						$classmaps = $output['autoload']['classmap'];

						foreach ($classmaps as $classmap) {
							$directories = [dirname($file) . '/' . $classmap];

							while (count($directories) != 0) {
								$next = array_shift($directories);

								if (is_dir($next)) {
									foreach (glob(trim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
										if (is_dir($file)) {
											$directories[] = $file . '/';
										}

										if (is_file($file)) {
											$namespace = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/' . $directory . $classmap) + 1);

											if ($namespace) {
												$autoload[$namespace] = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));
											}
										}
									}
								}
							}
						}

						foreach ($autoload as $namespace => $path) {
							$code .= '$autoloader->register(\'' . rtrim($namespace, '\\') . '\', DIR_STORAGE . \'vendor/' . $path . '\', true);' . "\n";
						}
					}

					// Autoload files
					if (isset($output['autoload']['files'])) {
						$files = $output['autoload']['files'];

						foreach ($files as $file) {
							$code .= 'require_once(DIR_STORAGE . \'vendor/' . $directory . '/' . $file . '\');' . "\n";
						}
					}
				}

				$code .= "\n";
			}

			file_put_contents(DIR_SYSTEM . 'vendor.php', trim($code));

			$json['success'] = $this->language->get('text_' . $action);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('marketplace/installer');

		$json = [];
	
		$extension_install_id = (int)($this->request->get['extension_install_id'] ?? 0);

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if (!$extension_install_info) {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['package_name'] . '.ocmod.zip';
			
			// Unzip the files
			$zip = new \ZipArchive();

			$this->removeExtensionFilesAndPaths($extension_install_info);

			// Remove file
			$this->deleteExtensionInArchieve($extension_install_info['package_name'], $extension_install_info['codename']);

			$this->model_setting_extension->deleteInstall($extension_install_id);

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function uninstallExtensions(string $install_codename): void {
		$results = $this->model_setting_extension->getPaths($install_codename . '/admin/controller/%/%.php');

		foreach ($results as $result) {
			$path_parts = explode('/', $result['path']);
			if(file_exists(DIR_APPLICATION . 'controller/extension/' . $path_parts[3] . '.php')) {

				$extension_type = $path_parts[3];
				$extension_code = basename($path_parts[4], '.php');

				$this->model_setting_extension->uninstall($extension_type, $extension_code);

				if ($extension_type == 'module') {
					$this->load->model('setting/module');

					$this->model_setting_module->deleteModulesByCode($install_codename . '.' . $extension_code);

				}

				try {
					// Call uninstall method if it exists
					$this->load->controller('extension/' . $install_codename . '/' . $extension_type . '/' . $extension_code . '|uninstall');
				} catch (\Throwable $e) {
					$sting = get_class($e) . ': ' . $e->getMessage() . "\n";
					$sting .= 'File: ' . $e->getFile() . "\n";
					$sting .= 'Line: ' . $e->getLine() . "\n";

					$this->log->write($sting);
				}
			}
		}
	}

	private function addExtensionFilesAndPaths(array $extension_installs, string $file, int $page, object $zip, array &$json, array &$install_folders = []): void {

		$install_info = json_decode($zip->getFromName('install.json'), true);

		if ($install_info) {
			$total = $zip->numFiles;
			$install_folders[$install_info['codename']] = '';

			foreach ($extension_installs as $extension_install_info) {
				if ($page > 1 && !is_dir(DIR_EXTENSION . $extension_install_info['codename'] . '/')) {
					$json['error'] = sprintf($this->language->get('error_directory'), $extension_install_info['codename'] . '/');
				}
			}
		} else {

			if(isset($this->request->get['install_folder'])) {
				foreach ($extension_installs as $extension_install_info) {
					$install_folders[$extension_install_info['codename']] = $this->request->get['install_folder'];
				}	
			} elseif (isset($this->request->post['install_folders'])) {
				$install_folders = $this->request->post['install_folders'];
			} else {
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$path = $zip->getNameIndex($i);

					$path_parts = explode('/', $path);
					
					if (!empty($path_parts[1]) && $path_parts[1] == 'install.json') {
						$install_info = json_decode($zip->getFromName($path_parts[0] . '/install.json'), true);
						foreach ($extension_installs as $extension_install_info) {
							if (isset($install_info['codename']) && $install_info['codename'] == $extension_install_info['codename']) {
								$install_folders[$install_info['codename']] = $path_parts[0];
							}
						}
					}
				}
				if(!$install_folders) {
					foreach ($extension_installs as $extension_install_info) {
						if ($page > 1 && !is_dir(DIR_EXTENSION . $extension_install_info['codename'] . '/')) {
							$json['error'] = sprintf($this->language->get('error_directory'), $extension_install_info['codename'] . '/');
						}
					}
				}
			}
		}

		if (!$json) {

			$start = ($page - 1) * 200;

			$total = $zip->numFiles;

			for ($i = $start; $i < ($start + 200); $i++) {
				$source = $zip->getNameIndex($i);

				$destination = str_replace('\\', '/', $source);

				foreach ($extension_installs as $extension_install_info) {
					
					$install_folder = $install_folders[$extension_install_info['codename']];

					$extension_install_id = $extension_install_info['extension_install_id'];

					$name_folder_length = $install_folder ? strlen($install_folder) + 1 : 0;
				
					if (!$install_folder || substr($destination, 0, $name_folder_length) == $install_folder . '/') {
						$path = $install_folder ? str_replace($install_folder, $extension_install_info['codename'], $destination) : $extension_install_info['codename'] . '/' . $destination;
						$base = DIR_EXTENSION;

						// image > image
						if (substr($destination, $name_folder_length, 6) == 'image/') {
							$path = $install_folder ? str_replace($install_folder . '/', '', $destination) : $destination;
							$base = substr(DIR_IMAGE, 0, -6);
						}
						
						// We need to store the path differently for vendor folders.
						if (substr($destination, $name_folder_length, 22) == 'system/storage/vendor/') {
							$path = $install_folder ? substr(str_replace($install_folder . '/', '', $destination), 15) : substr($destination, 15);
							$base = DIR_STORAGE;
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

							if (!is_dir($base . $path_new) && mkdir($base . $path_new, 0777)) {
								$this->model_setting_extension->addPath($extension_install_id, $path_new);
							}
						}

						// If check if the path is not directory and check there is no existing file
						if (substr($path, -1) != '/') {
							if (!is_file($base . $path) && copy('zip://' . $file . '#' . $source, $base . $path)) {
								$this->model_setting_extension->addPath($extension_install_id, $path);
							}
						}
					}
				}
			}
		}
	}

	private function removeExtensionFilesAndPaths(array $extension_install_info): void {
		$extension_install_id = $extension_install_info['extension_install_id'];

		// Make path into an array
		$directory = [DIR_EXTENSION . $extension_install_info['codename'] . '/'];

		$files = [];

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

		rsort($files);

		foreach ($files as $file) {
			// If file just delete
			if (is_file($file)) {
				unlink($file);

			// If directory use the remove directory function
			} elseif (is_dir($file)) {
				$empty_directory = !glob(rtrim($file, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE);
				if (!$empty_directory) {
					$sting  = 'PHP Warning: rmdir(' . $file . "): Directory not empty in\n";
					$sting .= 'File: ' . __FILE__ . "\n";
					$sting .= 'Line: ' . __LINE__ . "\n";

					$this->log->write($sting);
				} else {
					rmdir($file);
				}
			}
		}
		
		$results = $this->model_setting_extension->getPathsByExtensionInstallId($extension_install_id);

		rsort($results);

		foreach ($results as $result) {
			$path = '';

			// Remove images
			if (substr($result['path'], 0, 6) == 'image/') {
				$path = DIR_IMAGE . substr($result['path'], 6);
			}

			// Remove vendor files
			if (substr($result['path'], 0, 7) == 'vendor/') {
				$path = DIR_STORAGE . $result['path'];
			}

			// Check if the location exists or not
			$path_total = $this->model_setting_extension->getTotalPaths($result['path']);

			if ($path && $path_total < 2) {
				if (is_file($path)) {
					unlink($path);
				} elseif (is_dir($path)) {
					rmdir($path);
				}
			}

			$this->model_setting_extension->deletePath($result['extension_path_id']);
		}
	}

	private function addInstall(array $install_info, array $installed_info, $filename): void {
		if ($installed_info) {
			if(basename($filename, '.ocmod.zip') != $installed_info['package_name']) {
				$this->deleteExtensionInArchieve($installed_info['package_name'], $installed_info['codename']);
			}
			$extension_data = [
				'extension_install_id'  => $installed_info['extension_install_id'],
				'extension_id'          => $installed_info['extension_id'],
				'extension_download_id' => $installed_info['extension_download_id'],
				'name'                  => $install_info['name'],
				'package_name'          => basename($filename, '.ocmod.zip'),
				'codename'              	=> $install_info['codename'],
				'version'               => $install_info['version'],
				'author'                => $install_info['author'] ?? $this->language->get('text_unknown'),
				'link'                  => $install_info['link'] ?? $this->language->get('text_unknown')
			];

			$this->model_setting_extension->editInstall($extension_data);

		} else {
			$extension_data = [
				'extension_id'          => 0,
				'extension_download_id' => 0,
				'name'                  => $install_info['name'],
				'package_name'          => basename($filename, '.ocmod.zip'),
				'codename'              	=> $install_info['codename'],
				'version'               => $install_info['version'],
				'author'                => $install_info['author'] ?? $this->language->get('text_unknown'),
				'link'                  => $install_info['link'] ?? $this->language->get('text_unknown')
			];		
			
			$this->model_setting_extension->addInstall($extension_data);
			
		}
	}

	private function deleteExtensionInArchieve(string $package_name, string $codename): void {
		$file = DIR_STORAGE . 'marketplace/' . $package_name . '.ocmod.zip';
			
		// Unzip the files
		$zip = new \ZipArchive();

		if (is_file($file)) {
			if ($zip->open($file)) {
				$install_info = json_decode($zip->getFromName('install.json'), true);

				$total_installs = $this->model_setting_extension->getTotalInstallsByPackageName($package_name);
				
				if ($install_info || $total_installs <= 1) {
					$zip->close();
					
					unlink($file);
				} else {
					for ($i = 0; $i < $zip->numFiles; $i++) {
						$entry_info = $zip->statIndex($i);
						if(empty($install_folder)) {
							$path = $zip->getNameIndex($i);
							$path = str_replace('\\', '/', $path);
							$path_parts = explode('/', $path);

							$install_info = json_decode($zip->getFromName($path_parts[0] . '/install.json'), true);
							if($install_info && $install_info['codename'] == $codename) {
								$install_folder = $path_parts[0];
							}
						}
						if (!empty($install_folder) && substr($entry_info["name"], 0, strlen($install_folder . '/')) == $install_folder . '/') {
							$zip->deleteIndex($i);
						}
					}
					
					$zip->close();
				}
			}
		}
	}
}