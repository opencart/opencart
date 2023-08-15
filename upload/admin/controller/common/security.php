<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Security
 *
 * @package Opencart\Admin\Controller\Common
 */
class Security extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/security');

		// Check install directory exists
		if (is_dir(DIR_OPENCART . 'install/')) {
			$data['install'] = DIR_OPENCART . 'install/';
		} else {
			$data['install'] = '';
		}

		// Check storage directory exists
		if (DIR_STORAGE == DIR_SYSTEM . 'storage/') {
			// Check install directory exists
			$data['storage'] = DIR_STORAGE;

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

		// Check admin directory ia renamed
		if (DIR_APPLICATION == DIR_OPENCART . 'admin/') {
			$data['admin'] = 'admin';
		} else {
			$data['admin'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		if ($data['install'] || $data['storage'] || $data['admin']) {
			return $this->load->view('common/security', $data);
		} else {
			return '';
		}
	}

	/**
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
	 * @return void
	 */
	public function storage(): void {
		$this->load->language('common/security');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['name'])) {
			$name = preg_replace('[^a-zA-z0-9_]', '', basename(html_entity_decode(trim($this->request->get['name']), ENT_QUOTES, 'UTF-8')));
		} else {
			$name = '';
		}

		if (isset($this->request->get['path'])) {
			$path = preg_replace('[^a-zA-z0-9_\:\/]', '', html_entity_decode(trim($this->request->get['path']), ENT_QUOTES, 'UTF-8'));
		} else {
			$path = '';
		}

		$json = [];

		if ($this->user->hasPermission('modify', 'common/security')) {
			$base_old = DIR_STORAGE;
			$base_new = $path . $name . '/';

			if (!is_dir($base_old)) {
				$json['error'] = $this->language->get('error_storage');
			}

			$root = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../'));

			if ((substr($base_new, 0, strlen($root)) != $root) || ($root == $base_new)) {
				$json['error'] = $this->language->get('error_storage');
			}

			if (is_dir($base_new) && $page < 2) {
				$json['error'] = $this->language->get('error_storage_exists');
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
			$end = $start > ($total - $limit) ? $total : ($start + $limit);

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
				$json['next'] = $this->url->link('common/security.storage', '&user_token=' . $this->session->data['user_token'] . '&name=' . $name . '&path=' . $path . '&page=' . ($page + 1), true);
			} else {
				// Start deleting old storage location files.
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

				rmdir($base_old);

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
	 * @return void
	 */
	public function admin(): void {
		$this->load->language('common/security');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['name'])) {
			$name = preg_replace('[^a-zA-z0-9]', '', basename(html_entity_decode(trim((string)$this->request->get['name']), ENT_QUOTES, 'UTF-8')));
		} else {
			$name = 'admin';
		}

		$json = [];

		if ($this->user->hasPermission('modify', 'common/security')) {
			$base_old = DIR_OPENCART . 'admin/';
			$base_new = DIR_OPENCART . $name . '/';

			if (!is_dir($base_old)) {
				$json['error'] = $this->language->get('error_admin');
			}

			if (is_dir($base_new) && $page < 2) {
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
			$end = $start > ($total - $limit) ? $total : ($start + $limit);

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

			if (($page * $limit) <= $total) {
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

				// 6. redirect to the new admin
				$json['redirect'] = str_replace('&amp;', '&', substr(HTTP_SERVER, 0, -6) . $name . '/index.php?route=common/login');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 *
	 */
	public function __destruct() {
		// Remove old admin if exists
		$path = DIR_OPENCART . 'admin/';

		if (is_dir($path) && DIR_APPLICATION != $path) {
			// 1. We need to copy the files, as rename cannot be used on any directory, the executing script is running under
			$files = [];

			// Make path into an array
			$directory = [$path];

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

			// 4. reverse file order
			rsort($files);

			// 5. Delete the old admin directory
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

			rmdir($path);
		}
	}
}
