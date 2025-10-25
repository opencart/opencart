<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Security
 *
 * Can be loaded using $this->load->controller('common/security');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Security extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/security');

		$data['list'] = $this->load->controller('common/security.getList');

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('common/security', $data);
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('common/security');

		$this->response->setOutput($this->load->controller('common/security.getList'));
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		// Install directory exists
		$path = DIR_OPENCART . 'install/';

		if (is_dir($path)) {
			$data['install'] = $path;
		} else {
			$data['install'] = '';
		}

		// Storage directory exists
		$path = DIR_SYSTEM . 'storage/';

		if (DIR_STORAGE == $path) {
			$data['storage'] = $path;

			$data['document_root'] = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../')) . '/';

			$path = '';

			$data['paths'] = [];

			$parts = explode('/', rtrim($data['document_root'], '/'));

			foreach ($parts as $part) {
				$path .= $part . '/';

				$data['paths'][] = $path;
			}

			rsort($data['paths']);
		} else {
			$data['storage'] = '';
		}

		// Storage delete
		$path = DIR_SYSTEM . 'storage/';

		if (is_dir($path) && DIR_STORAGE != $path) {
			$data['storage_delete'] = $path;
		} else {
			$data['storage_delete'] = '';
		}

		// Check admin directory is renamed
		$path = DIR_OPENCART . 'admin/';

		if (DIR_APPLICATION == $path) {
			$data['admin'] = 'admin';
		} else {
			$data['admin'] = '';
		}

		// Admin delete
		$path = DIR_OPENCART . 'admin/';

		if (is_dir($path) && DIR_APPLICATION != $path) {
			$data['admin_delete'] = $path;
		} else {
			$data['admin_delete'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		if ($data['install'] || $data['storage'] || $data['storage_delete'] || $data['admin'] || $data['admin_delete']) {
			return $this->load->view('common/security_list', $data);
		} else {
			return '';
		}
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->language('common/security');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$directory = DIR_OPENCART . 'install/';

			if (!is_dir($directory)) {
				$json['error'] = $this->language->get('error_install');
			}
		}

		if (!$json) {
			// Make path into an array
			oc_directory_delete($directory);

			$json['success'] = $this->language->get('text_install_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Storage
	 *
	 * @return void
	 */
	public function storage(): void {
		$this->load->language('common/security');

		$json = [];

		if (isset($this->request->get['name'])) {
			$name = preg_replace('/[^a-zA-Z0-9_\.\-]/', '', basename(html_entity_decode(trim($this->request->get['name']), ENT_QUOTES, 'UTF-8')));
		} else {
			$name = '';
		}

		if (isset($this->request->get['path'])) {
			$path = preg_replace('/[^a-zA-Z0-9_\:\/\.\-]/', '', html_entity_decode(trim($this->request->get['path']), ENT_QUOTES, 'UTF-8'));
		} else {
			$path = '';
		}

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$json) {
			$base_old = DIR_STORAGE;
			$base_new = $path . $name . '/';

			// Check current storage path exists
			if (!is_dir($base_old)) {
				$json['error'] = $this->language->get('error_storage');
			}

			// Check the chosen directory is not in the public webspace C:/xampp/htdocs
			$root = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../')) . '/';

			if ((substr($base_new, 0, strlen($root)) != $path) || ($root == $base_new)) {
				$json['error'] = $this->language->get('error_storage_root');
			}

			if (!str_starts_with($name, 'storage')) {
				$json['error'] = $this->language->get('error_storage_name');
			}

			if (!is_writable(DIR_OPENCART . 'config.php') || !is_writable(DIR_APPLICATION . 'config.php')) {
				$json['error'] = $this->language->get('error_writable');
			}
		}

		if (!$json) {
			$files = oc_directory_read($base_old, true);

			$total = count($files);
			$limit = 200;

			$start = ($page - 1) * $limit;
			$end = ($start > ($total - $limit)) ? $total : ($start + $limit);

			foreach (array_slice($files, $start, $end) as $file) {
				$destination = substr($file, strlen($base_old));

				oc_directory_create($base_new . dirname($destination), 0777);

				// Must not have a path before files and directories can be moved
				if (is_file($base_old . $destination) && !is_file($base_new . $destination)) {
					copy($base_old . $destination, $base_new . $destination);
				}
			}

			if ($end < $total) {
				$json['text'] = sprintf($this->language->get('text_storage_move'), $start, $end, $total);

				$json['next'] = $this->url->link('common/security.storage', '&user_token=' . $this->session->data['user_token'] . '&name=' . $name . '&path=' . $path . '&page=' . ($page + 1), true);
			} else {
				// Modify the config files
				$files = [
					DIR_APPLICATION . 'config.php',
					DIR_OPENCART . 'config.php'
				];

				foreach ($files as $file) {
					$output = '';

					$lines = file($file);

					foreach ($lines as $line_id => $line) {
						if (str_contains($line, 'define(\'DIR_STORAGE')) {
							$output .= 'define(\'DIR_STORAGE\', \'' . $base_new . '\');' . "\n";
						} else {
							$output .= $line;
						}
					}

					$file = fopen($file, 'w');

					fwrite($file, $output);

					fclose($file);
				}

				$json['success'] = $this->language->get('text_storage_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/*
	 * Storage Delete
	 *
	 * @return void
	 */
	public function storage_delete() {
		$this->load->language('common/security');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Storage directory exists
			$path = DIR_SYSTEM . 'storage/';

			if (!is_dir($path) || DIR_STORAGE == $path) {
				$json['error'] = $this->language->get('error_remove');
			}
		}

		if (!$json) {
			// Delete old admin directory
			oc_directory_delete($path);

			$json['success'] = $this->language->get('text_storage_success_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Admin
	 *
	 * @return void
	 */
	public function admin(): void {
		$this->load->language('common/security');

		$json = [];

		if (isset($this->request->get['name'])) {
			$name = preg_replace('/[^a-zA-Z0-9]/', '', basename(html_entity_decode(trim((string)$this->request->get['name']), ENT_QUOTES, 'UTF-8')));
		} else {
			$name = 'admin';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$base_old = DIR_OPENCART . 'admin/';
			$base_new = DIR_OPENCART . $name . '/';

			if (!is_dir($base_old)) {
				$json['error'] = $this->language->get('error_admin_exists_old');
			}

			if ($page == 1 && is_dir($base_new)) {
				$json['error'] = $this->language->get('error_admin_exists_new');
			}

			$blocked = [
				'admin',
				'catalog',
				'extension',
				'image',
				'install',
				'system'
			];

			if (in_array($name, $blocked)) {
				$json['error'] = sprintf($this->language->get('error_admin_allowed'), $name);
			}

			if (!is_writable(DIR_OPENCART . 'config.php') || !is_writable(DIR_APPLICATION . 'config.php')) {
				$json['error'] = $this->language->get('error_writable');
			}
		}

		if (!$json) {
			$files = oc_directory_read($base_old, true);

			$total = count($files);
			$limit = 200;

			$start = ($page - 1) * $limit;
			$end = ($start > ($total - $limit)) ? $total : ($start + $limit);

			// 4. Copy the files across
			foreach (array_slice($files, $start, $end) as $file) {
				$destination = substr($file, strlen($base_old));

				oc_directory_create($base_new . dirname($destination), 0777);

				if (is_file($base_old . $destination) && !is_file($base_new . $destination)) {
					copy($base_old . $destination, $base_new . $destination);
				}
			}

			if ($end < $total) {
				$json['text'] = sprintf($this->language->get('text_admin_move'), $start, $end, $total);

				$json['next'] = $this->url->link('common/security.admin', '&user_token=' . $this->session->data['user_token'] . '&name=' . $name . '&page=' . ($page + 1), true);
			} else {
				// Update the old config files
				$file = $base_new . 'config.php';

				$output = '';

				$lines = file($file);

				foreach ($lines as $line_id => $line) {
					if (strpos($line, 'define(\'HTTP_SERVER') !== false) {
						$output .= 'define(\'HTTP_SERVER\', \'' . substr(HTTP_SERVER, 0, strrpos(HTTP_SERVER, '/admin/')) . '/' . $name . '/\');' . "\n";
					} elseif (strpos($line, 'define(\'DIR_APPLICATION') !== false) {
						$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'' . $name . '/\');' . "\n";
					} else {
						$output .= $line;
					}
				}

				$file = fopen($file, 'w');

				fwrite($file, $output);

				fclose($file);

				$json['success'] = $this->language->get('text_admin_success');

				// 6. Redirect to the new admin
				$json['redirect'] = str_replace('&amp;', '&', substr(HTTP_SERVER, 0, -6) . $name . '/index.php?route=common/login');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function admin_delete(): void {
		$this->load->language('common/security');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Admin directory exists
			$path = DIR_OPENCART . 'admin/';

			if (!is_dir($path) || DIR_APPLICATION == $path) {
				$json['error'] = $this->language->get('error_remove');
			}
		}

		if (!$json) {
			// Delete old admin directory
			oc_directory_delete($path);

			$json['success'] = $this->language->get('text_admin_success_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
