<?php
namespace Opencart\Admin\Controller\Common;
class FileManager extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('common/filemanager');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);

		// Return the target ID for the file manager to set the value
		if (isset($this->request->get['target'])) {
			$data['target'] = $this->request->get['target'];
		} else {
			$data['target'] = '';
		}

		// Return the thumbnail for the file manager to show a thumbnail
		if (isset($this->request->get['thumb'])) {
			$data['thumb'] = $this->request->get['thumb'];
		} else {
			$data['thumb'] = '';
		}

		if (isset($this->request->get['ckeditor'])) {
			$data['ckeditor'] = $this->request->get['ckeditor'];
		} else {
			$data['ckeditor'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('common/filemanager', $data));
	}

	public function list(): void {
		$this->load->language('common/filemanager');

		$base = DIR_IMAGE . 'catalog/';

		// Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$directory = $base . html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8') . '/';
		} else {
			$directory = $base;
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = basename(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$allowed = [
			'.ico',
			'.jpg',
			'.jpeg',
			'.png',
			'.gif',
			'.webp',
			'.JPG',
			'.JPEG',
			'.PNG',
			'.GIF'
		];

		$data['directories'] = [];
		$data['images'] = [];

		$this->load->model('tool/image');

		// Get directories
		$paths = glob($directory . $filter_name . '*{/,.ico,.jpg,.jpeg,.png,.gif,.webp,.JPG,.JPEG,.PNG,.GIF}', GLOB_BRACE);

		$total = count($paths);
		$limit = 16;

		$start = ($page - 1) * $limit;
		$end = $start > ($total - $limit) ? $total : ($start + $limit);

		if ($paths) {
			// Split the array based on current page number and max number of items per page of 10
			foreach (array_slice($paths, $start, $end) as $path) {
				$path = str_replace('\\', '/', realpath($path));

				if (substr($path, 0, strlen($path)) == $path) {
					$name = basename($path);

					$url = '';

					if (isset($this->request->get['target'])) {
						$url .= '&target=' . $this->request->get['target'];
					}

					if (isset($this->request->get['thumb'])) {
						$url .= '&thumb=' . $this->request->get['thumb'];
					}

					if (isset($this->request->get['ckeditor'])) {
						$url .= '&ckeditor=' . $this->request->get['ckeditor'];
					}

					if (is_dir($path)) {
						$data['directories'][] = [
							'name' => $name,
							'path' => oc_substr($path, oc_strlen($base)) . '/',
							'href' => $this->url->link('common/filemanager.list', 'user_token=' . $this->session->data['user_token'] . '&directory=' . urlencode(oc_substr($path, oc_strlen($base))) . $url)
						];
					}

					if (is_file($path) && in_array(substr($path, strrpos($path, '.')), $allowed)) {
						$data['images'][] = [
							'name'  => $name,
							'path'  => oc_substr($path, oc_strlen($base)),
							'href'  => HTTP_CATALOG . 'image/catalog/' . oc_substr($path, oc_strlen($base)),
							'thumb' => $this->model_tool_image->resize(oc_substr($path, oc_strlen(DIR_IMAGE)), 136, 136)
						];
					}
				}
			}
		}

		if (isset($this->request->get['directory'])) {
			$data['directory'] = urldecode($this->request->get['directory']);
		} else {
			$data['directory'] = '';
		}

		if (isset($this->request->get['filter_name'])) {
			$data['filter_name'] = $this->request->get['filter_name'];
		} else {
			$data['filter_name'] = '';
		}

		// Parent
		$url = '';

		if (isset($this->request->get['directory'])) {
			$pos = strrpos($this->request->get['directory'], '/');

			if ($pos) {
				$url .= '&directory=' . urlencode(substr($this->request->get['directory'], 0, $pos));
			}
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

		if (isset($this->request->get['ckeditor'])) {
			$url .= '&ckeditor=' . $this->request->get['ckeditor'];
		}

		$data['parent'] = $this->url->link('common/filemanager.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Refresh
		$url = '';

		if (isset($this->request->get['directory'])) {
			$url .= '&directory=' . urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

		if (isset($this->request->get['ckeditor'])) {
			$url .= '&ckeditor=' . $this->request->get['ckeditor'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['refresh'] = $this->url->link('common/filemanager.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$url = '';

		if (isset($this->request->get['directory'])) {
			$url .= '&directory=' . urlencode(html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['target'])) {
			$url .= '&target=' . $this->request->get['target'];
		}

		if (isset($this->request->get['thumb'])) {
			$url .= '&thumb=' . $this->request->get['thumb'];
		}

		if (isset($this->request->get['ckeditor'])) {
			$url .= '&ckeditor=' . $this->request->get['ckeditor'];
		}

		// Get total number of files and directories
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('common/filemanager.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$this->response->setOutput($this->load->view('common/filemanager_list', $data));
	}

	public function upload(): void {
		$this->load->language('common/filemanager');

		$json = [];

		$base = DIR_IMAGE . 'catalog/';

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$directory = $base . html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8') . '/';
		} else {
			$directory = $base;
		}

		// Check it's a directory
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)) . '/', 0, strlen($base)) != $base) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Check if multiple files are uploaded or just one
			$files = [];

			if (!empty($this->request->files['file']['name']) && is_array($this->request->files['file']['name'])) {
				foreach (array_keys($this->request->files['file']['name']) as $key) {
					$files[] = [
						'name'     => $this->request->files['file']['name'][$key],
						'type'     => $this->request->files['file']['type'][$key],
						'tmp_name' => $this->request->files['file']['tmp_name'][$key],
						'error'    => $this->request->files['file']['error'][$key],
						'size'     => $this->request->files['file']['size'][$key]
					];
				}
			}

			foreach ($files as $file) {
				if (is_file($file['tmp_name'])) {
					// Sanitize the filename
					$filename = preg_replace('[/\\?%*:|"<>]', '', basename(html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8')));

					// Validate the filename length
					if ((oc_strlen($filename) < 4) || (oc_strlen($filename) > 255)) {
						$json['error'] = $this->language->get('error_filename');
					}

					// Allowed file extension types
					$allowed = [
						'ico',
						'jpg',
						'jpeg',
						'png',
						'gif',
						'webp',
						'JPG',
						'JPEG',
						'PNG',
						'GIF'
					];

					if (!in_array(substr($filename, strrpos($filename, '.') + 1), $allowed)) {
						$json['error'] = $this->language->get('error_file_type');
					}

					// Allowed file mime types
					$allowed = [
						'image/x-icon',
						'image/jpeg',
						'image/pjpeg',
						'image/png',
						'image/x-png',
						'image/gif',
						'image/webp'
					];

					if (!in_array($file['type'], $allowed)) {
						$json['error'] = $this->language->get('error_file_type');
					}

					// Return any upload error
					if ($file['error'] != UPLOAD_ERR_OK) {
						$json['error'] = $this->language->get('error_upload_' . $file['error']);
					}
				} else {
					$json['error'] = $this->language->get('error_upload');
				}

				if (!$json) {
					move_uploaded_file($file['tmp_name'], $directory . $filename);
				}
			}
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_uploaded');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function folder(): void {
		$this->load->language('common/filemanager');

		$json = [];

		$base = DIR_IMAGE . 'catalog/';

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Make sure we have the correct directory
		if (isset($this->request->get['directory'])) {
			$directory = $base . html_entity_decode($this->request->get['directory'], ENT_QUOTES, 'UTF-8') . '/';
		} else {
			$directory = $base;
		}

		// Check its a directory
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)) . '/', 0, strlen($base)) != $base) {
			$json['error'] = $this->language->get('error_directory');
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			// Sanitize the folder name
			$folder = preg_replace('[/\\?%*&:|"<>]', '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));

			// Validate the filename length
			if ((oc_strlen($folder) < 3) || (oc_strlen($folder) > 128)) {
				$json['error'] = $this->language->get('error_folder');
			}

			// Check if directory already exists or not
			if (is_dir($directory . $folder)) {
				$json['error'] = $this->language->get('error_exists');
			}
		}

		if (!$json) {
			mkdir($directory . '/' . $folder, 0777);

			chmod($directory . '/' . $folder, 0777);

			@touch($directory . '/' . $folder . '/' . 'index.html');

			$json['success'] = $this->language->get('text_directory');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('common/filemanager');

		$json = [];

		$base = DIR_IMAGE . 'catalog/';

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['path'])) {
			$paths = $this->request->post['path'];
		} else {
			$paths = [];
		}

		// Loop through each path to run validations
		foreach ($paths as $path) {
			// Convert any html encoded characters.
			$path = html_entity_decode($path, ENT_QUOTES, 'UTF-8');

			// Check path exists
			if (($path == $base) || (substr(str_replace('\\', '/', realpath($base . $path)) . '/', 0, strlen($base)) != $base)) {
				$json['error'] = $this->language->get('error_delete');

				break;
			}
		}

		if (!$json) {
			// Loop through each path
			foreach ($paths as $path) {
				$path = rtrim($base . html_entity_decode($path, ENT_QUOTES, 'UTF-8'), '/');

				$files = [];

				// Make path into an array
				$directory = [$path];

				// While the path array is still populated keep looping through
				while (count($directory) != 0) {
					$next = array_shift($directory);

					if (is_dir($next)) {
						foreach (glob(trim($next, '/') . '/{*,.[!.]*,..?*}', GLOB_BRACE) as $file) {
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
			}

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
