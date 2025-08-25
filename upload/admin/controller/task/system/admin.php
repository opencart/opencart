<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Admin
 *
 *
 *
 * @package Opencart\Admin\Controller\Common
 */
class Admin extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/system/admin');



		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['name'])) {
			$name = preg_replace('/[^a-zA-Z0-9]/', '', basename(html_entity_decode(trim((string)$this->request->get['name']), ENT_QUOTES, 'UTF-8')));
		} else {
			$name = 'admin';
		}

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$base_old = DIR_OPENCART . 'admin/';
			$base_new = DIR_OPENCART . $name . '/';

			if (!is_dir($base_old)) {
				$json['error'] = $this->language->get('error_admin');
			}

			if ($page == 1 && is_dir($base_new)) {
				$json['error'] = $this->language->get('error_admin_exists');
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
			// 2. Create the new admin folder name
			if (!is_dir($base_new)) {
				mkdir($base_new, 0777);
			}

			// 1. We need to copy the files, as rename cannot be used on any directory, the executing script is running under
			$files = oc_directory_read($base_old, true);

			// 3. Split the file copies into chunks.
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

				// 6. Redirect to the new admin
				$json['redirect'] = str_replace('&amp;', '&', substr(HTTP_SERVER, 0, -6) . $name . '/index.php?route=common/login');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function move(array $args = []): array {








	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/system/security');

		$json = [];

		if (isset($this->request->get['remove'])) {
			$remove = (string)$this->request->get['remove'];
		} else {
			$remove = '';
		}

		if (!$this->user->hasPermission('modify', 'common/security')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$path = '';

			if ($remove == 'storage') {
				// Storage directory exists
				$path = DIR_SYSTEM . 'storage/';

				if (!is_dir($path) || DIR_STORAGE == $path) {
					$json['error'] = $this->language->get('error_storage');
				}
			}

			// Admin directory exists
			if ($remove == 'admin') {
				$path = DIR_OPENCART . 'admin/';

				if (!is_dir($path) || DIR_APPLICATION == $path) {
					$json['error'] = $this->language->get('error_admin');
				}
			}

			if (!$path) {
				$json['error'] = $this->language->get('error_remove');
			}
		}

		if (!$json) {
			// Delete old admin directory
			oc_directory_delete($path);

			$json['success'] = $this->language->get('text_' . $remove . '_delete_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

