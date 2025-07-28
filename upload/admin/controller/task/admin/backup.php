<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Backup
 *
 * @package Opencart\Admin\Controller\Ssr
 */
class Backup extends \Opencart\System\Engine\Controller {
	/**
	 * Backup
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('tool/backup');

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

			$results = $this->model_tool_backup->getRecords($table, ($page - 1) * 200, 200);

			foreach ($results as $result) {
				$fields = '';

				foreach (array_keys($result) as $value) {
					$fields .= '`' . $value . '`, ';
				}

				$values = '';

				foreach (array_values($result) as $value) {
					if ($value !== null) {
						$value = str_replace(["\x00", "\x0a", "\x0d", "\x1a"], ['\0', '\n', '\r', '\Z'], $value);
						$value = str_replace(["\n", "\r", "\t"], ['\n', '\r', '\t'], $value);
						$value = str_replace('\\', '\\\\', $value);
						$value = str_replace('\'', '\\\'', $value);
						$value = str_replace('\\\n', '\n', $value);
						$value = str_replace('\\\r', '\r', $value);
						$value = str_replace('\\\t', '\t', $value);

						$values .= '\'' . $value . '\', ';
					} else {
						$values .= 'NULL, ';
					}
				}

				$output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
			}

			$position = array_search($table, $backup);

			// Total Records
			$record_total = $this->model_tool_backup->getTotalRecords($table);

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

			$task_data = [
				'code'   => 'backup',
				'action' => 'catalog/backup',
				'args'   => [
					'filename' => date('Y-m-d H.i.s') . '.sql',
					'backup'   => $backup
				]
			];

			$this->load->model('setting/task');


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
}