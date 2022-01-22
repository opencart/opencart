<?php
namespace Opencart\Admin\Controller\Common;
class Security extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('common/security');

		// Check install directory exists
		$data['install'] = DIR_OPENCART . 'install/';

		// Check storage directory exists
		if (DIR_STORAGE == DIR_SYSTEM . 'storage/') {
			$data['error_storage'] = $this->language->get('error_storage');

			// Check install directory exists
			$data['storage'] = DIR_SYSTEM . 'storage/';

			$path = '';

			$data['paths'] = [];

			$parts = explode('/', str_replace('\\', '/', rtrim(DIR_SYSTEM, '/')));

			foreach ($parts as $part) {
				$path .= $part . '/';

				$data['paths'][] = $path;
			}

			rsort($data['paths']);

			$data['document_root'] = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../') . '/');
		} else {
			$data['error_storage'] = '';
		}

		// Check admin directory ia renamed
		if (DIR_APPLICATION == DIR_OPENCART . 'admin/') {
			$data['error_admin'] = $this->language->get('error_admin');
		} else {
			$data['error_admin'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('common/security', $data);
	}

	public function install(): void {
		$this->load->language('common/security');

		$json = [];

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!is_dir(DIR_OPENCART . 'install/')) {
			$json['error'] = $this->language->get('error_install');
		}

		if (!$json) {
			$files = [];

			// Make path into an array
			$directory = [DIR_OPENCART . 'install/'];

			// While the path array is still populated keep looping through
			while (count($directory) != 0) {
				$next = array_shift($directory);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$directory[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
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

			$json['success'] = $this->language->get('text_install_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function storage(): void {
		$this->load->language('common/security');

		$json = [];

		if ($this->request->post['path']) {
			$path = $this->request->post['path'];
		} else {
			$path = '';
		}

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (DIR_STORAGE != DIR_SYSTEM . 'storage/') {
			$json['error'] = $this->language->get('error_storage_path');
		}

		$path = '';

		$path_data = [];

		$parts = explode('/', str_replace('\\', '/', rtrim(DIR_SYSTEM, '/')));

		foreach ($parts as $part) {
			$path .= $part . '/';

			$path_data[] = $path;
		}

		if (!in_array($path, $path_data)) {
			$json['error'] = $this->language->get('error_storage_exists');
		}

		if (!is_writable(realpath(DIR_APPLICATION . '/../') . '/config.php') || !is_writable(DIR_APPLICATION . 'config.php')) {
			$json['error'] = $this->language->get('error_storage_writable');
		}

		if (!$json) {
			$files = [];

			// Make path into an array
			$source = [DIR_SYSTEM . 'storage/'];

			// While the path array is still populated keep looping through
			while (count($source) != 0) {
				$next = array_shift($source);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$source[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			// Create the new storage folder
			if (!is_dir($path)) {
				mkdir($path, 0777);
			}

			// Copy the
			foreach ($files as $file) {
				$destination = $path . $directory . substr($file, strlen(DIR_SYSTEM . 'storage/'));

				if (is_dir($file) && !is_dir($destination)) {
					mkdir($destination, 0777);
				}

				if (is_file($file)) {
					copy($file, $destination);
				}
			}

			// Modify the config files
			$files = [
				DIR_APPLICATION . 'config.php',
				realpath(DIR_APPLICATION . '/../') . '/config.php'
			];

			foreach ($files as $file) {
				$output = '';

				$lines = file($file);

				foreach ($lines as $line_id => $line) {
					if (strpos($line, 'define(\'DIR_STORAGE') !== false) {
						$output .= 'define(\'DIR_STORAGE\', \'' . $path . $directory . '/\');' . "\n";
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

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!is_dir(DIR_OPENCART . 'admin/')) {
			$json['error'] = $this->language->get('error_admin_exists');
		}

		if ($this->request->post['name'] == 'admin/') {
			$json['error'] = $this->language->get('error_admin');
		}


		if ($name = 'admin/') {
			$json['error'] = $this->language->get('error_admin');
		}

		if (!$json) {
			$new_name = DIR_OPENCART . $this->request->post['name'] . '/';

			rename(DIR_OPENCART . 'admin/', $new_name);

			$files = [
				DIR_OPENCART . 'config.php',
				$new_name . 'config.php'
			];

			foreach ($files as $file) {
				$output = '';

				$lines = file($file);

				foreach ($lines as $line_id => $line) {
					if (strpos($line, 'define(\'DIR_STORAGE') !== false) {
						$output .= 'define(\'DIR_STORAGE\', \'' . $path . $directory . '/\');' . "\n";
					} else {
						$output .= $line;
					}
				}

				$file = fopen($file, 'w');

				fwrite($file, $output);

				fclose($file);
			}

			$json['redirect'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
