<?php
class ControllerToolBackup extends Controller {
	public function index() {
		$this->load->language('tool/backup');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/backup', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['user_token'] = $this->session->data['user_token'];

		$data['export'] = $this->url->link('tool/backup/export', 'user_token=' . $this->session->data['user_token'], true);
		
		$this->load->model('tool/backup');

		$data['tables'] = $this->model_tool_backup->getTables();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/backup', $data));
	}

	public function zip() {
		$this->load->language('upgrade/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function modified() {
		$this->load->language('upgrade/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			set_time_limit(0);

			$curl = curl_init('https://www.opencart.com/index.php?route=api/modified/' . VERSION);

			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

			$response = curl_exec($curl);

			curl_close($curl);

			$response_info = json_decode($response, true);

			if ($response_info) {
				foreach ($response_info['file'] as $file) {
					$destination = str_replace('\\', '/', substr($file, strlen($directory . '/')));

					$path = str_replace('\\', '/', realpath(DIR_CATALOG . '../')) . '/' . $destination;

					// Check if the copy location exists or not
					if (substr($destination, 0, 5) == 'admin') {
						$path = DIR_APPLICATION . substr($destination, 6);
					}

					if (substr($destination, 0, 7) == 'catalog') {
						$path = DIR_CATALOG . substr($destination, 8);
					}

					if (substr($destination, 0, 7) == 'install') {
						$path = DIR_IMAGE . substr($destination, 8);
					}

					if (substr($destination, 0, 5) == 'image') {
						$path = DIR_IMAGE . substr($destination, 6);
					}

					if (substr($destination, 0, 6) == 'system') {
						$path = DIR_SYSTEM . substr($destination, 7);
					}

					if (is_dir($file) && !is_dir($path)) {
						if (!mkdir($path, 0777)) {
							$json['error'] = sprintf($this->language->get('error_directory'), $destination);
						}
					}

					if (is_file($file)) {
						if (!rename($file, $path)) {
							$json['error'] = sprintf($this->language->get('error_file'), $destination);
						}
					}
				}
			} else {
				$json['error'] = $this->language->get('error_download');
			}







			$json['text'] = $this->language->get('text_unzip');

			$json['next'] = str_replace('&amp;', '&', $this->url->link('tool/upgrade/unzip', 'user_token=' . $this->session->data['user_token']));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function image() {
		$this->load->language('tool/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!isset($this->session->data['backup'])) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$directory = rtrim(DIR_IMAGE, '/');

			$files = array();

			// Make path into an array
			$path = array($directory);

			// While the path array is still populated keep looping through
			while (count($path) != 0) {
				$next = array_shift($path);

				foreach (glob($next) as $file) {
					// If directory add to path array
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					// Add the file to the files to be deleted array
					$files[] = $file;
				}
			}

			foreach ($files as $file) {
				$destination = str_replace('\\', '/', substr($file, strlen($directory . '/')));

				//DIR_STORAGE . 'backup/image/'

				if (is_dir($file) && !is_dir($path)) {
					if (!mkdir($path, 0777)) {
						$json['error'] = sprintf($this->language->get('error_directory'), $destination);
					}
				}

				if (is_file($file)) {
					if (!rename($file, $path)) {
						$json['error'] = sprintf($this->language->get('error_file'), $destination);
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function download() {
		$this->load->language('upgrade/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			//$files = glob(DIR_DOWNLOAD . );



			//foreach ($files as $file) {



			//}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function upload() {
		$this->load->language('upgrade/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function database() {
		$this->load->language('upgrade/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function export() {
		$this->load->language('upgrade/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->language('tool/backup');

			if (!isset($this->request->post['backup'])) {
				$this->session->data['error'] = $this->language->get('error_export');

				$this->response->redirect($this->url->link('tool/backup', 'user_token=' . $this->session->data['user_token'], true));
			} elseif (!$this->user->hasPermission('modify', 'tool/backup')) {
				$this->session->data['error'] = $this->language->get('error_permission');

				$this->response->redirect($this->url->link('tool/backup', 'user_token=' . $this->session->data['user_token'], true));
			} else {
				$this->response->addheader('Pragma: public');
				$this->response->addheader('Expires: 0');
				$this->response->addheader('Content-Description: File Transfer');
				$this->response->addheader('Content-Type: application/octet-stream');
				$this->response->addheader('Content-Disposition: attachment; filename="' . DB_DATABASE . '_' . date('Y-m-d_H-i-s', time()) . '_backup.sql"');
				$this->response->addheader('Content-Transfer-Encoding: binary');

				$this->load->model('tool/backup');

				$this->response->setOutput($this->model_tool_backup->backup($this->request->post['backup']));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function import() {
		$this->load->language('tool/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (isset($this->request->files['import']['tmp_name']) && is_uploaded_file($this->request->files['import']['tmp_name'])) {
			$filename = tempnam(DIR_UPLOAD, 'bac');

			move_uploaded_file($this->request->files['import']['tmp_name'], $filename);
		} elseif (isset($this->request->get['import'])) {
			$filename = html_entity_decode($this->request->get['import'], ENT_QUOTES, 'UTF-8');
		} else {
			$filename = '';
		}

		if (!is_file($filename)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (isset($this->request->get['position'])) {
			$position = $this->request->get['position'];
		} else {
			$position = 0;
		}

		if (!$json) {
			// We set $i so we can batch execute the queries rather than do them all at once.
			$i = 0;
			$start = false;

			$handle = fopen($filename, 'r');

			fseek($handle, $position, SEEK_SET);

			while (!feof($handle) && ($i < 100)) {
				$position = ftell($handle);

				$line = fgets($handle, 1000000);

				if (substr($line, 0, 14) == 'TRUNCATE TABLE' || substr($line, 0, 11) == 'INSERT INTO') {
					$sql = '';

					$start = true;
				}

				if ($i > 0 && (substr($line, 0, 24) == 'TRUNCATE TABLE `oc_user`' || substr($line, 0, 30) == 'TRUNCATE TABLE `oc_user_group`')) {
					fseek($handle, $position, SEEK_SET);

					break;
				}

				if ($start) {
					$sql .= $line;
				}

				if ($start && substr($line, -2) == ";\n") {
					$this->db->query(substr($sql, 0, strlen($sql) -2));

					$start = false;
				}

				$i++;
			}

			$position = ftell($handle);

			$size = filesize($filename);

			$json['total'] = round(($position / $size) * 100);

			if ($position && !feof($handle)) {
				$json['next'] = str_replace('&amp;', '&', $this->url->link('tool/backup/import', 'user_token=' . $this->session->data['user_token'] . '&import=' . $filename . '&position=' . $position, true));

				fclose($handle);
			} else {
				fclose($handle);

				unlink($filename);

				$json['success'] = $this->language->get('text_success');

				$this->cache->delete('*');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
