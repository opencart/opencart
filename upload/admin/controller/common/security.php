<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Security
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

		$data['list'] = $this->controller_common_security->getList();

		$data['user_token'] = $this->session->data['user_token'];

		if ($data['list']) {
			return $this->load->view('common/security', $data);
		} else {
			return '';
		}
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('common/security');

		$this->response->setOutput($this->controller_common_security->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		$this->load->language('common/security');

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
		} elseif (is_dir($path)) {
			$data['storage'] = '';
		}

		if (DIR_APPLICATION != $path && is_dir($path)) {
			$data['storage_delete'] = $this->url->link('common/security.delete', 'user_token=' . $this->session->data['user_token'] . '&type=storage');
		} else {
			$data['storage_delete'] = '';
		}

		// Check admin directory ia renamed
		$path = DIR_OPENCART . 'admin/';

		if (DIR_APPLICATION == $path) {
			$data['admin'] = 'admin';
		} else {
			$data['admin'] = '';
		}

		// Delete old admin directory
		if (DIR_APPLICATION != $path && is_dir($path)) {
			$data['admin_delete'] = $this->url->link('common/security.delete', 'user_token=' . $this->session->data['user_token'] . '&type=admin');
		} else {
			$data['admin_delete'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		if ($data['install'] || $data['storage'] || $data['admin']) {
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

		if ($this->user->hasPermission('modify', 'common/security')) {
			if (!is_dir(DIR_OPENCART . 'install/')) {
				$json['error'] = $this->language->get('error_install');
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$files = [];

			$path = DIR_OPENCART . 'install/';

			// Make path into an array
			$directory = [$path];

			// While the path array is still populated keep looping through
			while (count($directory) != 0) {
				$next = array_shift($directory);

				if (is_dir($next)) {
					foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
						// If directory add to path array
						if (is_dir($file)) {
							$directory[] = $file;
						}

						// Add the file to the files to be deleted array
						$files[] = $file;
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

			rmdir($path);

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

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['name'])) {
			$name = preg_replace('/[^a-zA-Z0-9_\.]/', '', basename(html_entity_decode(trim($this->request->get['name']), ENT_QUOTES, 'UTF-8')));
		} else {
			$name = '';
		}

		if (isset($this->request->get['path'])) {
			$path = preg_replace('/[^a-zA-Z0-9_\:\/\.]/', '', html_entity_decode(trim($this->request->get['path']), ENT_QUOTES, 'UTF-8'));
		} else {
			$path = '';
		}

		if ($this->user->hasPermission('modify', 'common/security')) {
			$base_old = DIR_STORAGE;
			$base_new = $path . $name . '/';

			// Check current storage path exists
			if (!is_dir($base_old)) {
				$json['error'] = $this->language->get('error_storage');
			}

			// Check the chosen directory is not in the public webspace
			$root = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../'));

			if ((substr($base_new, 0, strlen($root)) != $root) || ($root == $base_new)) {
				$json['error'] = $this->language->get('error_storage');
			}

			if (!str_starts_with($name, 'storage')) {
				$json['error'] = $this->language->get('error_storage_name');
			}

			// Make sure the new directory created exists
			if ($page > 1 && !is_dir($base_new)) {
				$json['error'] = $this->language->get('error_storage');
			}

			if (!is_writable(DIR_OPENCART . 'config.php') || !is_writable(DIR_APPLICATION . 'config.php')) {
				$json['error'] = $this->language->get('error_writable');
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$files = [];

			// Make path into an array
			$directory = [$base_old];

			// While the path array is still populated keep looping through
			while (count($directory) != 0) {
				$next = array_shift($directory);

				foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$directory[] = $file;
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// Create the new storage folder
			if (!is_dir($base_new)) {
				mkdir($base_new, 0777);
			}

			// Copy the
			$total = count($files);
			$limit = 200;

			$start = ($page - 1) * $limit;
			$end = ($start > ($total - $limit)) ? $total : ($start + $limit);

			for ($i = $start; $i < $end; $i++) {
				$destination = substr($files[$i], strlen($base_old));

				if (is_dir($base_old . $destination) && !is_dir($base_new . $destination)) {
					mkdir($base_new . $destination, 0777);
				}

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
						if (strpos($line, 'define(\'DIR_STORAGE') !== false) {
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

	/**
	 * Admin
	 *
	 * @return void
	 */
	public function admin(): void {
		$this->load->language('common/security');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['name'])) {
			$name = preg_replace('[^a-zA-Z0-9]', '', basename(html_entity_decode(trim((string)$this->request->get['name']), ENT_QUOTES, 'UTF-8')));
		} else {
			$name = 'admin';
		}

		if ($this->user->hasPermission('modify', 'common/security')) {
			$base_old = DIR_OPENCART . 'admin/';
			$base_new = DIR_OPENCART . $name . '/';

			if (!is_dir($base_old)) {
				$json['error'] = $this->language->get('error_admin');
			}

			$blocked = [
				'admin',
				'catalog',
				'extension',
				'image',
				'install',
				'system'
			];

			if (!in_array($name, $blocked)) {
				$json['error'] = $this->language->get('error_admin_exists');
			}

			if ($page == 1 && is_dir($base_new)) {
				$json['error'] = $this->language->get('error_admin_exists');
			}

			if ($name == 'admin') {
				$json['error'] = $this->language->get('error_admin_name');
			}

			if (!is_writable(DIR_OPENCART . 'config.php') || !is_writable(DIR_APPLICATION . 'config.php')) {
				$json['error'] = $this->language->get('error_writable');
			}
		} else {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// 1.  // 1. We need to copy the files, as rename cannot be used on any directory, the executing script is running under
			$files = [];

			// Make path into an array
			$directory = [$base_old];

			// While the path array is still populated keep looping through
			while (count($directory) != 0) {
				$next = array_shift($directory);

				foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$directory[] = $file;
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// 2. Create the new admin folder name
			if (!is_dir($base_new)) {
				mkdir($base_new, 0777);
			}

			// 3. split the file copies into chunks.
			$total = count($files);
			$limit = 200;

			$start = ($page - 1) * $limit;
			$end = ($start > ($total - $limit)) ? $total : ($start + $limit);

			// 4. Copy the files across
			foreach (array_slice($files, $start, $end) as $file) {
				$destination = substr($file, strlen($base_old));

				if (is_dir($base_old . $destination) && !is_dir($base_new . $destination)) {
					mkdir($base_new . $destination, 0777);
				}

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
					$status = true;

					if (strpos($line, 'define(\'HTTP_SERVER') !== false) {
						$output .= 'define(\'HTTP_SERVER\', \'' . substr(HTTP_SERVER, 0, strrpos(HTTP_SERVER, '/admin/')) . '/' . $name . '/\');' . "\n";

						$status = false;
					}

					if (strpos($line, 'define(\'DIR_APPLICATION') !== false) {
						$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'' . $name . '/\');' . "\n";

						$status = false;
					}

					if ($status) {
						$output .= $line;
					}
				}

				$file = fopen($file, 'w');

				fwrite($file, $output);

				fclose($file);

				$this->session->data['success'] = $this->language->get('text_admin_success');

				// 6. redirect to the new admin
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
	public function delete(): void {
		$this->load->language('common/security');

		$json = [];

		if (isset($this->request->get['type'])) {
			$type = (string)$this->request->get['type'];
		} else {
			$type = '';
		}

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$allowed = [
			'admin'   => DIR_OPENCART . 'admin/',
			'storage' => DIR_SYSTEM . 'storage/'
		];

		if (!in_array($type, $allowed)) {
			$json['error'] = $this->language->get('error_delete');
		}

		// Install directory exists
		$path = DIR_OPENCART . 'install/';

		// Check admin directory ia renamed
		$path = ;

		// Storage directory exists
		$path = ;

		if (!is_dir($path)) {
			$json['error'] = $this->language->get('error_delete');
		}

		if (!$json) {
			$files = [];

			// Make path into an array
			$directory = [$path];

			// While the path array is still populated keep looping through
			while (count($directory) != 0) {
				$next = array_shift($directory);

				if (is_dir($next)) {
					foreach (glob(rtrim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
						// If directory add to path array
						if (is_dir($file)) {
							$directory[] = $file;
						}

						// Add the file to the files to be deleted array
						$files[] = $file;
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

			rmdir($path);

			$json['success'] = $this->language->get('text_delete_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}