<?php
class ControllerCommonFileManager extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('common/filemanager');

		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['entry_folder'] = $this->language->get('entry_folder');
		$this->data['entry_move'] = $this->language->get('entry_move');
		$this->data['entry_copy'] = $this->language->get('entry_copy');
		$this->data['entry_rename'] = $this->language->get('entry_rename');

		$this->data['button_folder'] = $this->language->get('button_folder');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_move'] = $this->language->get('button_move');
		$this->data['button_copy'] = $this->language->get('button_copy');
		$this->data['button_rename'] = $this->language->get('button_rename');
		$this->data['button_upload'] = $this->language->get('button_upload');
		$this->data['button_refresh'] = $this->language->get('button_refresh');
		$this->data['button_submit'] = $this->language->get('button_submit');

		$this->data['error_select'] = $this->language->get('error_select');
		$this->data['error_directory'] = $this->language->get('error_directory');

		$this->data['token'] = $this->session->data['token'];

		$this->data['directory'] = HTTP_CATALOG . 'image/data/';

		$this->load->model('tool/image');

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		if (isset($this->request->get['field'])) {
			$this->data['field'] = $this->request->get['field'];
		} else {
			$this->data['field'] = '';
		}

		if (isset($this->request->get['CKEditorFuncNum'])) {
			$this->data['fckeditor'] = $this->request->get['CKEditorFuncNum'];
		} else {
			$this->data['fckeditor'] = false;
		}

		$this->template = 'common/filemanager.tpl';

		$this->response->setOutput($this->render());
	}

	public function image() {
		$this->load->model('tool/image');

		if (isset($this->request->get['image'])) {
			$this->response->setOutput($this->model_tool_image->resize(html_entity_decode($this->request->get['image'], ENT_QUOTES, 'UTF-8'), 100, 100));
		}
	}

	public function directory() {
		$json = array();

		if (isset($this->request->post['directory'])) {
			$directories = glob(rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['directory']), '/') . '/*', GLOB_ONLYDIR);

			if ($directories) {
				$i = 0;

				foreach ($directories as $directory) {
					$json[$i]['data'] = basename($directory);
					$json[$i]['attributes']['directory'] = utf8_substr($directory, strlen(DIR_IMAGE . 'data/'));

					$children = glob(rtrim($directory, '/') . '/*', GLOB_ONLYDIR);

					if ($children)  {
						$json[$i]['children'] = ' ';
					}

					$i++;
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function files() {
		$json = array();

		if (!empty($this->request->post['directory'])) {
			$directory = DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['directory']);
		} else {
			$directory = DIR_IMAGE . 'data/';
		}

		$allowed = array(
			'.jpg',
			'.jpeg',
			'.png',
			'.gif'
		);

		$files = glob(rtrim($directory, '/') . '/*');

		if ($files) {
			foreach ($files as $file) {
				if (is_file($file)) {
					$ext = strrchr($file, '.');
				} else {
					$ext = '';
				}

				if (in_array(strtolower($ext), $allowed)) {
					$size = filesize($file);

					$i = 0;

					$suffix = array(
						'B',
						'KB',
						'MB',
						'GB',
						'TB',
						'PB',
						'EB',
						'ZB',
						'YB'
					);

					while (($size / 1024) > 1) {
						$size = $size / 1024;
						$i++;
					}

					$json[] = array(
						'filename' => basename($file),
						'file'     => utf8_substr($file, utf8_strlen(DIR_IMAGE . 'data/')),
						'size'     => round(utf8_substr($size, 0, utf8_strpos($size, '.') + 4), 2) . $suffix[$i]
					);
				}
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function create() {
		$this->language->load('common/filemanager');

		$json = array();

		if (isset($this->request->post['directory'])) {
			if (isset($this->request->post['name']) || $this->request->post['name']) {
				$directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['directory']), '/');

				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				}

				if (file_exists($directory . '/' . str_replace('../', '', $this->request->post['name']))) {
					$json['error'] = $this->language->get('error_exists');
				}
			} else {
				$json['error'] = $this->language->get('error_name');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			mkdir($directory . '/' . str_replace('../', '', $this->request->post['name']), 0777);

			$json['success'] = $this->language->get('text_create');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->language->load('common/filemanager');

		$json = array();

		if (isset($this->request->post['path'])) {
			$path = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($path)) {
				$json['error'] = $this->language->get('error_select');
			}

			if ($path == rtrim(DIR_IMAGE . 'data/', '/')) {
				$json['error'] = $this->language->get('error_delete');
			}
		} else {
			$json['error'] = $this->language->get('error_select');
		}

		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if (is_file($path)) {
				unlink($path);
			} elseif (is_dir($path)) {
				$files = array();

				$path = array($path . '*');

				while(count($path) != 0) {
					$next = array_shift($path);

					foreach(glob($next) as $file) {
						if (is_dir($file)) {
							$path[] = $file . '/*';
						}

						$files[] = $file;
					}
				}

				rsort($files);

				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif(is_dir($file)) {
						rmdir($file);
					}
				}
			}

			$json['success'] = $this->language->get('text_delete');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function move() {
		$this->language->load('common/filemanager');

		$json = array();

		if (isset($this->request->post['from']) && isset($this->request->post['to'])) {
			$from = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->request->post['from'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($from)) {
				$json['error'] = $this->language->get('error_missing');
			}

			if ($from == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_default');
			}

			$to = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->request->post['to'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($to)) {
				$json['error'] = $this->language->get('error_move');
			}

			if (file_exists($to . '/' . basename($from))) {
				$json['error'] = $this->language->get('error_exists');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			rename($from, $to . '/' . basename($from));

			$json['success'] = $this->language->get('text_move');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function copy() {
		$this->language->load('common/filemanager');

		$json = array();

		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
				$json['error'] = $this->language->get('error_filename');
			}

			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_copy');
			}

			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}

			$new_name = dirname($old_name) . '/' . str_replace('../', '', html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8') . $ext);

			if (file_exists($new_name)) {
				$json['error'] = $this->language->get('error_exists');
			}
		} else {
			$json['error'] = $this->language->get('error_select');
		}

		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if (is_file($old_name)) {
				copy($old_name, $new_name);
			} else {
				$this->recursiveCopy($old_name, $new_name);
			}

			$json['success'] = $this->language->get('text_copy');
		}

		$this->response->setOutput(json_encode($json));
	}

	function recursiveCopy($source, $destination) {
		$directory = opendir($source);

		@mkdir($destination);

		while (false !== ($file = readdir($directory))) {
			if (($file != '.') && ($file != '..')) {
				if (is_dir($source . '/' . $file)) {
					$this->recursiveCopy($source . '/' . $file, $destination . '/' . $file);
				} else {
					copy($source . '/' . $file, $destination . '/' . $file);
				}
			}
		}

		closedir($directory);
	}

	public function folders() {
		$this->response->setOutput($this->recursiveFolders(DIR_IMAGE . 'data/'));
	}

	protected function recursiveFolders($directory) {
		$output = '';

		$output .= '<option value="' . utf8_substr($directory, strlen(DIR_IMAGE . 'data/')) . '">' . utf8_substr($directory, strlen(DIR_IMAGE . 'data/')) . '</option>';

		$directories = glob(rtrim(str_replace('../', '', $directory), '/') . '/*', GLOB_ONLYDIR);

		foreach ($directories  as $directory) {
			$output .= $this->recursiveFolders($directory);
		}

		return $output;
	}

	public function rename() {
		$this->language->load('common/filemanager');

		$json = array();

		if (isset($this->request->post['path']) && isset($this->request->post['name'])) {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
				$json['error'] = $this->language->get('error_filename');
			}

			$old_name = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', html_entity_decode($this->request->post['path'], ENT_QUOTES, 'UTF-8')), '/');

			if (!file_exists($old_name) || $old_name == DIR_IMAGE . 'data') {
				$json['error'] = $this->language->get('error_rename');
			}

			if (is_file($old_name)) {
				$ext = strrchr($old_name, '.');
			} else {
				$ext = '';
			}

			$new_name = dirname($old_name) . '/' . str_replace('../', '', html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8') . $ext);

			if (file_exists($new_name)) {
				$json['error'] = $this->language->get('error_exists');
			}
		}

		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			rename($old_name, $new_name);

			$json['success'] = $this->language->get('text_rename');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function upload() {
		$this->language->load('common/filemanager');

		$json = array();

		if (isset($this->request->post['directory'])) {
			if (isset($this->request->files['image']) && $this->request->files['image']['tmp_name']) {
				$filename = basename(html_entity_decode($this->request->files['image']['name'], ENT_QUOTES, 'UTF-8'));

				if ((strlen($filename) < 3) || (strlen($filename) > 255)) {
					$json['error'] = $this->language->get('error_filename');
				}

				$directory = rtrim(DIR_IMAGE . 'data/' . str_replace('../', '', $this->request->post['directory']), '/');

				if (!is_dir($directory)) {
					$json['error'] = $this->language->get('error_directory');
				}

				if ($this->request->files['image']['size'] > 300000) {
					$json['error'] = $this->language->get('error_file_size');
				}

				$allowed = array(
					'image/jpeg',
					'image/pjpeg',
					'image/png',
					'image/x-png',
					'image/gif',
					'application/x-shockwave-flash'
				);

				if (!in_array($this->request->files['image']['type'], $allowed)) {
					$json['error'] = $this->language->get('error_file_type');
				}

				$allowed = array(
					'.jpg',
					'.jpeg',
					'.gif',
					'.png',
					'.flv'
				);

				if (!in_array(strtolower(strrchr($filename, '.')), $allowed)) {
					$json['error'] = $this->language->get('error_file_type');
				}

				// Check to see if any PHP files are trying to be uploaded
				$content = file_get_contents($this->request->files['image']['tmp_name']);

				if (preg_match('/\<\?php/i', $content)) {
					$json['error'] = $this->language->get('error_file_type');
				}

				if ($this->request->files['image']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = 'error_upload_' . $this->request->files['image']['error'];
				}
			} else {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$this->user->hasPermission('modify', 'common/filemanager')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($json['error'])) {
			if (@move_uploaded_file($this->request->files['image']['tmp_name'], $directory . '/' . $filename)) {
				$json['success'] = $this->language->get('text_uploaded');
			} else {
				$json['error'] = $this->language->get('error_uploaded');
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>