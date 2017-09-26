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

		$ignore = array(
			DB_PREFIX . 'user',
			DB_PREFIX . 'user_group'
		);

		$data['tables'] = array();

		$results = $this->model_tool_backup->getTables();

		foreach ($results as $result) {
			if (!in_array($result, $ignore)) {
				$data['tables'][] = $result;
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/backup', $data));
	}

	public function history() {
		$this->load->language('tool/backup');

		$data['histories'] = array();

		$results = glob(DIR_STORAGE . 'backup/*.sql');

		foreach ($results as $result) {
			$data['histories'][] = array(
				'filename'   => $result['filename'],
				'size'       => filesize($result['filename']),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		$this->response->setOutput($this->load->view('tool/backup_history', $data));
	}

	public function backup() {
		$this->load->language('tool/backup');

		$json = array();

		if (isset($this->request->get['filename'])) {
			$filename = $this->request->get['filename'];
		} else {
			$filename = '';
		}

		if (isset($this->request->get['table'])) {
			$table = explode(',', $this->request->get['table']);
		} else {
			$table = array();
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_IMAGE . 'backup';

		// Validate the directory
		if (substr(str_replace('\\', '/', realpath($directory . '/' . $filename)), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
			$json['error'] = $this->language->get('error_directory');
		}

		$this->load->model('tool/backup');


		if (!$json) {
			$output = '';

			if ($page == 1) {
				$output .= 'TRUNCATE TABLE `' . $table . '`;' . "\n\n";
			}

			$record_total = $this->model_tool_backup->getTotalRecords($table);

			$results = $this->model_tool_backup->getRecords($table, '');

			foreach ($results as $result) {
				$fields = '';

				foreach (array_keys($result) as $value) {
					$fields .= '`' . $value . '`, ';
				}

				$values = '';

				foreach (array_values($result) as $value) {
					$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
					$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
					$value = str_replace('\\', '\\\\', $value);
					$value = str_replace('\'', '\\\'', $value);
					$value = str_replace('\\\n', '\n', $value);
					$value = str_replace('\\\r', '\r', $value);
					$value = str_replace('\\\t', '\t', $value);

					$values .= '\'' . $value . '\', ';
				}

				$output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
			}

			if (!$filename) {
				$filename =  date('Y-m-d H:i:s') . '.sql';
			}

			$handle = fopen(DIR_STORAGE . 'backup/' . $filename, 'w+');

			fwrite($handle, $file);

			fclose($handle);

			if ($position && !feof($handle)) {
				$output .= "\n\n";

			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function restore() {
		$this->load->language('tool/backup');

		$json = array();

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}


		if (isset($this->request->get['import'])) {
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

	public function upload() {
		if (isset($this->request->files['import']['tmp_name']) && is_uploaded_file($this->request->files['import']['tmp_name'])) {
			$filename = tempnam(DIR_UPLOAD, 'bac');

			move_uploaded_file($this->request->files['import']['tmp_name'], $filename);
		}
	}

	public function download() {
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
}
