<?php
class ControllerToolBackup extends Controller {
	public function index() {
		$this->load->language('tool/backup');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

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

	public function export() {
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

	public function calculate() {
		$this->load->language('upgrade/upgrade');

		$json = array();

		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!empty($this->request->get['backup'])) {
			$json['error'] = $this->language->get('error_backup');
		}

		if (!$json) {
			$directories = array();

			$backup = explode(',', $this->request->get['backup']);

			if (in_array('file', $backup)) {
				$directories[] = DIR_CATALOG;
				$directories[] = DIR_APPLICATION;
				$directories[] = DIR_CONFIG;
				$directories[] = DIR_SYSTEM . 'engine';
				$directories[] = DIR_SYSTEM . 'helper';
				$directories[] = DIR_SYSTEM . 'library';
			}

			if (in_array('image', $backup)) {
				$directories[] = DIR_IMAGE;
			}

			if (in_array('download', $backup)) {
				$directories[] = DIR_DOWNLOAD;
			}

			if (in_array('upload', $backup)) {
				$directories[] = DIR_UPLOAD;
			}

			if (isset($this->request->post['path'])) {
				$paths = $this->request->post['path'];
			} else {
				$paths = array();
			}

			// Loop through each path
			foreach ($directories as $directory) {
				$path = rtrim(DIR_IMAGE . $path, '/');

				// If path is just a file delete it
				if (is_file($path)) {
					unlink($path);

					// If path is a directory beging deleting each file and sub folder
				} elseif (is_dir($path)) {
					$files = array();

					// Make path into an array
					$path = array($path);

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
				}
			}

			//disk_total_space();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function modified() {
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

	function database() {
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

	function image() {
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

	function download() {
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

	function upload() {
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

	public function download() {
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
}
