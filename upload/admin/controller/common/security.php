<?php
namespace Opencart\Admin\Controller\Common;
class Security extends \Opencart\System\Engine\Controller {
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

			$data['document_root'] = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../') . '/');

			$path = '';

			$data['paths'] = [];

			$parts = explode('/', str_replace('\\', '/', rtrim(DIR_SYSTEM, '/')));

			foreach ($parts as $part) {
				$path .= $part . '/';

				if (strlen($data['document_root']) >= strlen($path)) {
					$data['paths'][] = $path;
				}
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

	public function storage(): void {
		$this->load->language('common/security');

		$json = [];

		if ($this->user->hasPermission('modify', 'common/security')) {
			$path_old = DIR_STORAGE;
			$path_new = $this->request->post['path'] . preg_replace('[^a-zA-z0-9]', '', basename(html_entity_decode(trim($this->request->post['name']), ENT_QUOTES, 'UTF-8'))) . '/';

			$path = '';

			$path_data = [];

			$parts = explode('/', str_replace('\\', '/', rtrim(DIR_SYSTEM, '/')));

			foreach ($parts as $part) {
				$path .= $part . '/';

				if (strlen(str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../') . '/')) >= strlen($path)) {
					$path_data[] = $path;
				}
			}

			if (!in_array($this->request->post['path'], $path_data)) {
				$json['error'] = $this->language->get('error_storage');
			}

			if (is_dir($path_new)) {
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
			$directory = [$path_old];

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
			mkdir($path_new, 0777);

			// Copy the
			foreach ($files as $file) {
				$destination = $path_new . substr($file, strlen($path_old));

				if (is_dir($file) && !is_dir($destination)) {
					mkdir($destination, 0777);
				}

				if (is_file($file)) {
					copy($file, $destination);
				}
			}

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
			rmdir($path_old);

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
						$output .= 'define(\'DIR_STORAGE\', \'' . $path_new . '\');' . "\n";
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

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function admin(): void {
		$this->load->language('common/security');

		$json = [];

		if ($this->user->hasPermission('modify', 'common/security')) {
			$name = preg_replace('[^a-zA-z0-9]', '', basename(html_entity_decode(trim($this->request->post['name']), ENT_QUOTES, 'UTF-8')));

			$path_old = DIR_OPENCART . 'admin/';
			$path_new = DIR_OPENCART . $name . '/';

			if (!is_dir($path_old)) {
				$json['error'] = $this->language->get('error_admin');
			}

			if (is_dir($path_new)) {
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
			// 1.  We need to copy the files as rename can not be used on any directory the executing script is running under
			$files = [];

			// Make path into an array
			$directory = [$path_old];

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
			mkdir($path_new, 0777);

			// 3. Copy the files across
			foreach ($files as $file) {
				$destination = $path_new . substr($file, strlen($path_old));

				if (is_dir($file) && !is_dir($destination)) {
					mkdir($destination, 0777);
				}

				if (is_file($file)) {
					copy($file, $destination);
				}
			}

			// Update the old config files
			$file = $path_new . 'config.php';

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
			$url_without_admin_folder = substr(HTTP_SERVER, 0, strrpos(HTTP_SERVER, 'admin/'));
			$url_with_new_admin_folder = $url_without_admin_folder . $name . '/index.php?route=common/security|delete&user_token=' . $this->session->data['user_token'];
			$json['redirect'] = str_replace('&amp;', '&', $url_with_new_admin_folder);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$status = true;

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$status = false;
		}

		$path_old = DIR_OPENCART . 'admin/';

		if (!is_dir($path_old)) {
			$status = false;
		}

		if ($path_old == DIR_APPLICATION) {
			$status = false;
		}

		if ($status) {
			// 1.  We need to copy the files as rename can not be used on any directory the executing script is running under
			$files = [];

			// Make path into an array
			$directory = [$path_old];

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

					// If directory use the remove directory function
				} elseif (is_dir($file)) {
					rmdir($file);
				}
			}

			rmdir($path_old);
		}

		$this->response->redirect($this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token']));
	}
}
