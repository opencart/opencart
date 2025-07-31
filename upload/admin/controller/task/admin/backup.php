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
	public function index(array $args = []): array {
		$this->load->language('task/admin/backup');

		if (!isset($args['backup'])) {
			return ['error' => $this->language->get('error_backup')];
		}

		if (!isset($args['filename'])) {
			return ['error' => $this->language->get('error_filename')];
		}

		$this->load->model('tool/backup');

		$allowed = $this->model_tool_backup->getTables();

		$this->load->model('setting/task');

		foreach ((array)$args['backup'] as $table) {
			if (!in_array($table, $allowed)) {
				return ['error' => sprintf($this->language->get('error_table'), $table)];
			}

			$record_total = $this->model_tool_backup->getTotalRecords($args['table']);

			$start = ($page - 1) * $limit;
			$end = ($start > ($comment_total - $limit)) ? $comment_total : ($start + $limit);

			for ($i = 0; $i <= $record_total; $i++) {
				$task_data = [
					'code'   => 'backup',
					'action' => 'admin/backup.write',
					'args'   => [
						'filename' => $args['filename'],
						'table'    => $table,
						'page'     => 1
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	public function write(array $args = []): array {
		$this->load->language('task/admin/backup');

		if (!isset($args['table'])) {
			return ['error' => $this->language->get('error_table')];
		}

		$this->load->model('tool/backup');

		$allowed = $this->model_tool_backup->getTables();

		if (!in_array($args['table'], $allowed)) {
			return ['error' => sprintf($this->language->get('error_table'), $args['table'])];
		}

		if (isset($args['page'])) {
			$page = (int)$args['page'];
		} else {
			$page = 1;
		}

		$output = '';

		if ($page == 1) {
			$output .= 'TRUNCATE TABLE `' . $this->db->escape($args['table']) . '`;' . "\n\n";
		}

		$results = $this->model_tool_backup->getRecords($args['table'], ($page - 1) * 200, 200);

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

		if (!$table) {
			$json['success'] = $this->language->get('text_success');
		} elseif (($page * 200) >= $record_total) {
			$json['text'] = sprintf($this->language->get('text_backup'), $table, ($page - 1) * 200, $record_total);

			$json['next'] = $this->url->link('tool/backup.backup', 'user_token=' . $this->session->data['user_token'] . '&filename=' . urlencode($filename) . '&table=' . $table . '&page=1', true);
		} else {
			$json['text'] = sprintf($this->language->get('text_backup'), $table, ($page - 1) * 200, $page * 200);

			$json['next'] = $this->url->link('tool/backup.backup', 'user_token=' . $this->session->data['user_token'] . '&filename=' . urlencode($filename) . '&table=' . $table . '&page=' . ($page + 1), true);
		}


		return ['success' => $this->language->get('text_success')];
	}
}