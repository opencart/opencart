<?php
namespace Opencart\Admin\Controller\Tool;
class Backup extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('tool/backup');

		$this->document->setTitle($this->language->get('heading_title'));

		// Use the ini_get('upload_max_filesize') for the max file size
		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), ini_get('upload_max_filesize'));

		$data['config_file_max_size'] = ((int)preg_filter('/[^0-9]/', '', ini_get('upload_max_filesize')) * 1024 * 1024);

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/backup', 'user_token=' . $this->session->data['user_token'])
		];

		$data['upload'] = $this->url->link('tool/backup.upload', 'user_token=' . $this->session->data['user_token']);

		$this->load->model('tool/backup');

		$ignore = [
			DB_PREFIX . 'user',
			DB_PREFIX . 'user_group'
		];

		$data['tables'] = [];

		$results = $this->model_tool_backup->getTables();

		foreach ($results as $result) {
			if (!in_array($result, $ignore)) {
				$data['tables'][] = $result;
			}
		}

		$data['history'] = $this->getHistory();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/backup', $data));
	}

	public function history(): void {
		$this->load->language('tool/backup');

		$this->response->setOutput($this->getHistory());
	}

	public function getHistory(): string {
		$this->load->language('tool/backup');

		$data['histories'] = [];

		$files = glob(DIR_STORAGE . 'backup/*.sql');

		foreach ($files as $file) {
			$size = filesize($file);

			$i = 0;

			$suffix = [
				'B',
				'KB',
				'MB',
				'GB',
				'TB',
				'PB',
				'EB',
				'ZB',
				'YB'
			];

			while (($size / 1024) > 1) {
				$size = $size / 1024;

				$i++;
			}

			$data['histories'][] = [
				'filename'   => basename($file),
				'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
				'date_added' => date($this->language->get('datetime_format'), filemtime($file)),
				'download'   => $this->url->link('tool/backup.download', 'user_token=' . $this->session->data['user_token'] . '&filename=' . urlencode(basename($file))),
			];
		}

		return $this->load->view('tool/backup_history', $data);
	}

	public function backup(): void {
		$this->load->language('tool/backup');

		$json = [];

		if (isset($this->request->get['filename'])) {
			$filename = basename(html_entity_decode($this->request->get['filename'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filename = date('Y-m-d H.i.s') . '.sql';
		}

		if (isset($this->request->get['table'])) {
			$table = $this->request->get['table'];
		} else {
			$table = '';
		}

		if (isset($this->request->post['backup'])) {
			$backup = $this->request->post['backup'];
		} else {
			$backup = [];
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('tool/backup');

		$allowed = $this->model_tool_backup->getTables();

		if (!in_array($table, $allowed)) {
			$json['error'] = sprintf($this->language->get('error_table'), $table);
		}

		if (!$json) {
			$output = '';

			if ($page == 1) {
				$output .= 'TRUNCATE TABLE `' . $this->db->escape($table) . '`;' . "\n\n";
			}

			$record_total = $this->model_tool_backup->getTotalRecords($table);

			$results = $this->model_tool_backup->getRecords($table, ($page - 1) * 200, 200);

			foreach ($results as $result) {
				$fields = '';

				foreach (array_keys($result) as $value) {
					$fields .= '`' . $value . '`, ';
				}

				$values = '';

				foreach (array_values($result) as $value) {
					$value = str_replace(["\x00", "\x0a", "\x0d", "\x1a"], ['\0', '\n', '\r', '\Z'], $value);
					$value = str_replace(["\n", "\r", "\t"], ['\n', '\r', '\t'], $value);
					$value = str_replace('\\', '\\\\', $value);
					$value = str_replace('\'', '\\\'', $value);
					$value = str_replace('\\\n', '\n', $value);
					$value = str_replace('\\\r', '\r', $value);
					$value = str_replace('\\\t', '\t', $value);

					$values .= '\'' . $value . '\', ';
				}

				$output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
			}

			$position = array_search($table, $backup);

			if (($page * 200) >= $record_total) {
				$output .= "\n";

				if (isset($backup[$position + 1])) {
					$table = $backup[$position + 1];
				} else {
					$table = '';
				}
			}

			if ($position !== false) {
				$json['progress'] = round(($position / count($backup)) * 100);
			} else {
				$json['progress'] = 0;
			}

			$handle = fopen(DIR_STORAGE . 'backup/' . $filename, 'a');

			fwrite($handle, $output);

			fclose($handle);

			if (!$table) {
				$json['success'] = $this->language->get('text_success');
			} elseif (($page * 200) >= $record_total) {
				$json['text'] = sprintf($this->language->get('text_backup'), $table, ($page - 1) * 200, $record_total);

				$json['next'] = $this->url->link('tool/backup.backup', 'user_token=' . $this->session->data['user_token'] . '&filename=' . urlencode($filename) . '&table=' . $table . '&page=1', true);
			} else {
				$json['text'] = sprintf($this->language->get('text_backup'), $table, ($page - 1) * 200, $page * 200);

				$json['next'] = $this->url->link('tool/backup.backup', 'user_token=' . $this->session->data['user_token'] . '&filename=' . urlencode($filename) . '&table=' . $table . '&page=' . ($page + 1), true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function restore(): void {
		$this->load->language('tool/backup');

		$json = [];

		if (isset($this->request->get['filename'])) {
			$filename = basename(html_entity_decode($this->request->get['filename'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filename = '';
		}

		if (isset($this->request->get['position'])) {
			$position = $this->request->get['position'];
		} else {
			$position = 0;
		}

		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_STORAGE . 'backup/' . $filename;

		if (!is_file($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// We set $i so we can batch execute the queries rather than do them all at once.
			$i = 0;
			$start = false;

			$handle = fopen($file, 'r');

			fseek($handle, $position, SEEK_SET);

			while (!feof($handle) && ($i < 100)) {
				$position = ftell($handle);

				$line = fgets($handle, 1000000);

				if (substr($line, 0, 14) == 'TRUNCATE TABLE' || substr($line, 0, 11) == 'INSERT INTO') {
					$sql = '';

					$start = true;
				}

				if ($i > 0 && (substr($line, 0, strlen('TRUNCATE TABLE `' . DB_PREFIX . 'user`')) == 'TRUNCATE TABLE `' . DB_PREFIX . 'user`' || substr($line, 0, strlen('TRUNCATE TABLE `' . DB_PREFIX . 'user_group`')) == 'TRUNCATE TABLE `' . DB_PREFIX . 'user_group`')) {
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

			$size = filesize($file);

			if ($position) {
				$json['progress'] = round(($position / $size) * 100);
			} else {
				$json['progress'] = 0;
			}

			if ($position && !feof($handle)) {
				$json['text'] = sprintf($this->language->get('text_restore'), $position, $size);

				$json['next'] = $this->url->link('tool/backup.restore', 'user_token=' . $this->session->data['user_token'] . '&filename=' . urlencode($filename) . '&position=' . $position, true);
			} else {
				$json['success'] = $this->language->get('text_success');

				$this->cache->delete('*');
			}

			fclose($handle);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function upload(): void {
		$this->load->language('tool/backup');

		$json = [];

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (empty($this->request->files['upload']['name']) || !is_file($this->request->files['upload']['tmp_name'])) {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			// Sanitize the filename
			$filename = basename(html_entity_decode($this->request->files['upload']['name'], ENT_QUOTES, 'UTF-8'));

			if ((oc_strlen($filename) < 3) || (oc_strlen($filename) > 128)) {
				$json['error'] = $this->language->get('error_filename');
			}

			// Allowed file extension types
			if (strtolower(substr(strrchr($filename, '.'), 1)) != 'sql') {
				$json['error'] = $this->language->get('error_file_type');
			}
		}

		if (!$json) {
			move_uploaded_file($this->request->files['upload']['tmp_name'], DIR_STORAGE . 'backup/' . $filename);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function download(): void {
		$this->load->language('tool/backup');

		$json = [];

		if (isset($this->request->get['filename'])) {
			$filename = basename(html_entity_decode($this->request->get['filename'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filename = '';
		}

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$this->response->redirect($this->url->link('error/permission'));
		}

		$file = DIR_STORAGE . 'backup/' . $filename;

		if (!is_file($file)) {
			$this->response->redirect($this->url->link('error/not_found'));
		}

		if (!headers_sent()) {
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));

			if (ob_get_level()) {
				ob_end_clean();
			}

			readfile($file, 'rb');

			exit();
		} else {
			exit($this->language->get('error_headers_sent'));
		}
	}

	public function delete(): void {
		$this->load->language('tool/backup');

		$json = [];

		if (isset($this->request->get['filename'])) {
			$filename = basename(html_entity_decode($this->request->get['filename'], ENT_QUOTES, 'UTF-8'));
		} else {
			$filename = '';
		}

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'tool/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_STORAGE . 'backup/' . $filename;

		if (!is_file($file)) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			unlink($file);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
