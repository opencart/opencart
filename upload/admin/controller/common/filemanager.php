<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class File Manager
 *
 * Can be loaded using $this->load->controller('common/filemanager');
 *
 * @package Opencart\Admin\Controller\Common
 */
class FileManager extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
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

	/**
	 * List
	 *
	 * @return void
	 */
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

		$allowed = [];

		foreach (explode("\r\n", $this->config->get('config_file_ext_allowed')) as $key => $extension) {
			$allowed[] = '.' . \strtolower($extension);
			$allowed[] = '.' . \strtoupper($extension);
		}

		$directories = [];
		$files = [];

		// Get directories and files
		$paths = array_diff(scandir($directory), ['..', '.']);

		foreach ($paths as $value) {
			if ($filter_name && !str_starts_with($value, $filter_name)) {
				continue;
			}

			$path = str_replace('\\', '/', realpath($directory . $value));

			if (is_dir($path)) {
				$directories[] = $path;
			}

			if (is_file($path) && in_array(substr($value, strrpos($value, '.')), $allowed)) {
				$files[] = $path;
			}
		}

		$total = count($files);
		$limit = 16;
		$start = ($page - 1) * $limit;

		$data['directories'] = [];

		// Image
		$data['images'] = [];

		$this->load->model('tool/image');

		if ($paths) {
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

			// Split the array based on current page number and max number of items per page of 10
			foreach (array_slice(array_merge($directories, $files), $start, $limit) as $path) {
				if (substr($path, 0, strlen($base)) == $base) {
					$name = basename($path);

					if (is_dir($path)) {
						$data['directories'][] = [
							'name' => $name,
							'path' => oc_substr($path, oc_strlen($base)) . '/',
							'href' => $this->url->link('common/filemanager.list', 'user_token=' . $this->session->data['user_token'] . '&directory=' . urlencode(oc_substr($path, oc_strlen($base))) . $url)
						];
					}

					if (is_file($path)) {
						$data['images'][] = [
							'name'  => $name,
							'path'  => oc_substr($path, oc_strlen($base)),
							'href'  => HTTP_CATALOG . 'image/catalog/' . oc_substr($path, oc_strlen($base)),
							'thumb' => $this->model_tool_image->resize(oc_substr($path, oc_strlen(DIR_IMAGE)), $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'))
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

	/**
	 * Upload
	 *
	 * @return void
	 */
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
					$filename = preg_replace('/[\/\\\?%*:|"<>]/', '', basename(html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8')));

					// Validate the filename length
					if (!oc_validate_length($filename, 4, 255)) {
						$json['error'] = $this->language->get('error_filename');
					}

					// Allowed file extension types
					$allowed = explode("\r\n", \strtolower($this->config->get('config_file_ext_allowed')));

					if (!in_array(\strtolower(substr($filename, strrpos($filename, '.') + 1)), $allowed)) {
						$json['error'] = $this->language->get('error_file_type');
					}

					// Allowed file mime types
					$allowed = explode("\r\n", $this->config->get('config_file_mime_allowed'));

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

	/**
	 * Folder
	 *
	 * @return void
	 */
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

		if (isset($this->request->post['folder'])) {
			// Sanitize the folder name
			$folder = preg_replace('/[\/\\\?%*&:|"<>]/', '', basename(html_entity_decode($this->request->post['folder'], ENT_QUOTES, 'UTF-8')));
		} else {
			$folder = '';
		}

		// Check it's a directory
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)) . '/', 0, strlen($base)) != $base) {
			$json['error'] = $this->language->get('error_directory');
		}

		// Validate the filename length
		if (!oc_validate_length($folder, 3, 128)) {
			$json['error'] = $this->language->get('error_folder');
		} elseif (is_dir($directory . $folder)) {
			$json['error'] = $this->language->get('error_exists');
		}

		if (!$json) {
			mkdir($directory . $folder, 0777);

			chmod($directory . $folder, 0777);

			@touch($directory . $folder . '/' . 'index.html');

			$json['success'] = $this->language->get('text_directory');
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

				// Make path into an array
				$directory = [$path];

				// While the path array is still populated keep looping through
				while (count($directory) != 0) {
					$next = array_shift($directory);

					if (is_dir($next)) {
						foreach (array_diff(scandir($next), ['..', '.']) as $file) {
							// If directory add to path array
							$directory[] = $next . '/' . $file;
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
