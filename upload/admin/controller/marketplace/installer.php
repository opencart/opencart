<?php
namespace Opencart\Admin\Controller\Marketplace;
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

		$data['config_file_max_size'] = ((int)preg_filter('/[^0-9]/', '', ini_get('upload_max_filesize')) * 1024 * 1024);

		$data['upload'] = $this->url->link('tool/installer|upload', 'user_token=' . $this->session->data['user_token']);

		if (isset($this->request->get['filter_extension_id'])) {
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

	public function extension(): void {
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
				$json['error'] = $this->language->get('error_file_exists');

				unlink($this->request->files['file']['name']);
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
                    if ($this->model_setting_extension->getInstallByCode(basename($filename, '.ocmod.zip'))) {
                        $json['error'] = $this->language->get('error_installed');
                    }

                    if (!$json) {
                        $extension_data = [
                            'extension_id'          => 0,
                            'extension_download_id' => 0,
                            'name'                  => isset($install_info['name']) ? $install_info['name'] : $this->language->get('text_unknown'),
                            'package_name'          => basename($filename, '.ocmod.zip'),
                            'code'              	=> basename($filename, '.ocmod.zip'),
                            'version'               => isset($install_info['version']) ? $install_info['version'] : $this->language->get('text_unknown'),
                            'author'                => isset($install_info['author']) ? $install_info['author'] : $this->language->get('text_unknown'),
                            'link'                  => isset($install_info['link']) ? $install_info['link'] : $this->language->get('text_unknown')
                        ];
            
                        $this->load->model('setting/extension');
            
                        $this->model_setting_extension->addInstall($extension_data);

                        $json['success'] = $this->language->get('text_upload');
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
							
                            $code = $path_parts[0];							

                            if (!$this->model_setting_extension->getInstallByCode($code)) {
                                $extension_data = [
                                    'extension_id'          => 0,
                                    'extension_download_id' => 0,
                                    'name'                  => isset($install_info['name']) ? $install_info['name'] : $this->language->get('text_unknown'),
                                    'package_name'          => basename($filename, '.ocmod.zip'),
                                    'code'              	=> $code,
                                    'version'               => isset($install_info['version']) ? $install_info['version'] : $this->language->get('text_unknown'),
                                    'author'                => isset($install_info['author']) ? $install_info['author'] : $this->language->get('text_unknown'),
                                    'link'                  => isset($install_info['link']) ? $install_info['link'] : $this->language->get('text_unknown')
                                ];
    
                                $this->load->model('setting/extension');		
								
                                $this->model_setting_extension->addInstall($extension_data);
    
                                $json['success'] = $this->language->get('text_upload');
                            }
                        }
                    } else {
                        $json['error'] = $this->language->get('error_unzip');
                    }
				}

				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}		

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['package_name'] . '.ocmod.zip';

			if (!is_file($file)) {
				$json['error'] = sprintf($this->language->get('error_file'), $extension_install_info['package_name'] . '.ocmod.zip');
			}

			if ($page == 1 && is_dir(DIR_EXTENSION . $extension_install_info['code'] . '/')) {
				$json['error'] = sprintf($this->language->get('error_directory_exists'), $extension_install_info['code'] . '/');
			}

			if ($page > 1 && !is_dir(DIR_EXTENSION . $extension_install_info['code'] . '/')) {
				$json['error'] = sprintf($this->language->get('error_directory'), $extension_install_info['code'] . '/');
			}
		} else {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file)) {
                $install_info = json_decode($zip->getFromName('install.json'), true);
				
                if ($install_info) {
                    $total = $zip->numFiles;

                    $start = ($page - 1) * 200;

                    // Check if any of the files already exist.
                    for ($i = $start; $i < ($start + 200); $i++) {
                        $source = $zip->getNameIndex($i);

                        $destination = str_replace('\\', '/', $source);

                        // Only extract the contents of the upload folder
                        $path = $extension_install_info['code'] . '/' . $destination;
                        $base = DIR_EXTENSION;

                        // image > image
                        if (substr($destination, 0, 6) == 'image/') {
                            $path = $destination;
                            $base = substr(DIR_IMAGE, 0, -6);
                        }

                        // We need to store the path differently for vendor folders.
                        if (substr($destination, 0, 22) == 'system/storage/vendor/') {
                            $path = substr($destination, 15);
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
				} else {
					$total = $zip->numFiles;

                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $source = $zip->getNameIndex($i);

                        $destination = str_replace('\\', '/', $source);

                        $name_folder_length = strlen($extension_install_info['code']) + 1;
						
                        if (substr($destination, 0, $name_folder_length) == $extension_install_info['code'] . '/') {
                            $path = $destination;
                            $base = DIR_EXTENSION;

                            // image > image
                            if (substr($destination, $name_folder_length, $name_folder_length + 6) == 'image/') {
                                $path = $destination;
                                $base = substr(DIR_IMAGE, 0, -6);
                            }

                            // We need to store the path differently for vendor folders.
                            if (substr($destination, $name_folder_length, $name_folder_length + 22) == 'system/storage/vendor/') {
                                $path = substr($destination, $name_folder_length + 15);
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

				$zip->close();

				$this->model_setting_extension->editStatus($extension_install_id, 1);
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
			} else {
				$json['next'] = $this->url->link('marketplace/installer|vendor', 'user_token=' . $this->session->data['user_token'] . $url, true);
			}
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

								foreach (glob($next . '*') as $file) {
									if (is_dir($file)) {
										$directories[] = $file . '/';
									}

									if (is_file($file)) {
										$autoload[substr(dirname($file), strlen(DIR_STORAGE . 'vendor/' . $directory . $classmap) + 1)] = substr(dirname($file), strlen(DIR_STORAGE . 'vendor/'));
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

			$json['success'] = $this->language->get('text_install');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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
				$path_total = $this->model_setting_extension->getTotalPaths($result['path']);

				if ($path_total < 2) {
					if (is_file($path)) {
						unlink($path);
					} elseif (is_dir($path)) {
						rmdir($path);
					}
				}

				$this->model_setting_extension->deletePath($result['extension_path_id']);
			}

			// Remove extension directory
			$this->model_setting_extension->editStatus($extension_install_id, 0);

			$json['success'] = $this->language->get('text_uninstall');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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

		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if (!$extension_install_info) {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['package_name'] . '.ocmod.zip';
			
			// Unzip the files
			$zip = new \ZipArchive();

			// Remove file
			if (is_file($file)) {
				if ($zip->open($file)) {
					$install_info = json_decode($zip->getFromName('install.json'), true);
					
					if ($install_info) {
						$zip->close();
						
						unlink($file);
					} else {
						for ($i = 0; $i < $zip->numFiles; $i++) {
							$entry_info = $zip->statIndex($i);
							
							if (substr($entry_info["name"], 0, strlen($extension_install_info['code'] . '/')) == $extension_install_info['code'] . '/') {
								$zip->deleteIndex($i);
							}
						}
						
						$zip->close();
					}
				}
			}

			$this->model_setting_extension->deleteInstall($extension_install_id);

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
