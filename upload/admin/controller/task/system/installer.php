<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class Installer
 *
 * @package Opencart\Admin\Controller\Marketplace
 */
class Installer extends \Opencart\System\Engine\Controller {
	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->language('task/system/installer');

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
				$json['error'] = $this->language->get('error_file', $extension_install_info['code'] . '.ocmod.zip');
			}

			if ($page == 1 && is_dir(DIR_EXTENSION . $extension_install_info['code'] . '/')) {
				$json['error'] = $this->language->get('error_directory_exists', $extension_install_info['code'] . '/');
			}

			if ($page > 1 && !is_dir(DIR_EXTENSION . $extension_install_info['code'] . '/')) {
				$json['error'] = $this->language->get('error_directory', $extension_install_info['code'] . '/');
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
			$json['text'] = $this->language->get('text_install', $start, $end, $total);

			$url = '';

			if (isset($this->request->get['extension_install_id'])) {
				$url .= '&extension_install_id=' . $this->request->get['extension_install_id'];
			}

			if ($end < $total) {
				$json['next'] = $this->url->link('marketplace/installer.install', 'user_token=' . $this->session->data['user_token'] . $url . '&page=' . ($page + 1), true);
			} else {
				$json['next'] = $this->url->link('marketplace/installer.vendor', 'user_token=' . $this->session->data['user_token'] . $url, true);
			}
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
				$json['error'] = $this->language->get('error_uninstall', $extension_total);
			}
		} else {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			oc_directory_delete(DIR_EXTENSION . $extension_install_info['code'] . '/');

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
}
