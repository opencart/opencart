<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Backup
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Backup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates backup task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/system/backup');

		$required = [
			'filename',
			'backup'
		];

		foreach ($required as $value) {
			if (empty($args[$value])) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		$filename = basename(html_entity_decode($args['filename'], ENT_QUOTES, 'UTF-8'));

		if (!oc_validate_length($filename, 5, 128)) {
			return ['error' => $this->language->get('error_filename')];
		}

		$limit = 10;

		$backup = (array)$args['backup'];

		sort($backup);

		$this->load->model('setting/task');

		$this->load->model('tool/backup');

		foreach ($backup as $table) {
			$record_total = $this->model_tool_backup->getTotalRecords($table);

			$page_total = ceil($record_total / $limit);

			for ($i = 0; $i <= $page_total; $i++) {
				$start = $i * $limit;

				if ($start > ($record_total - $limit)) {
					$end = $record_total;
				} else {
					$end = ($start + $limit);
				}

				$task_data = [
					'code'   => 'backup',
					'action' => 'task/system/backup.write',
					'args'   => [
						'filename' => $filename,
						'table'    => $table,
						'start'    => $start,
						'end'      => $end,
						'total'    => $record_total
					]
				];

				$this->model_setting_task->addTask($task_data);
			}
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * Write
	 *
	 * @return array
	 */
	public function write(array $args = []): array {
		$this->load->language('task/system/backup');

		$required = [
			'filename',
			'table',
			'start',
			'end',
			'total'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		$filename = basename(html_entity_decode($args['filename'], ENT_QUOTES, 'UTF-8'));

		if (!oc_validate_length($filename, 5, 128)) {
			return ['error' => $this->language->get('error_filename')];
		}

		$disallowed = [
			DB_PREFIX . 'task',
			DB_PREFIX . 'user',
			DB_PREFIX . 'user_authorize',
			DB_PREFIX . 'user_group',
			DB_PREFIX . 'user_login',
			DB_PREFIX . 'user_token'
		];

		if (!str_starts_with((string)$args['table'], DB_PREFIX) || in_array((string)$args['table'], $disallowed)) {
			return ['error' => sprintf($this->language->get('error_table'), $args['table'])];
		}

		$output = '';

		if (!$args['start']) {
			$output .= 'TRUNCATE TABLE `' . $this->db->escape((string)$args['table']) . '`;' . "\n\n";
		}

		$this->load->model('tool/backup');

		$results = $this->model_tool_backup->getRecords((string)$args['table'], (int)$args['start'], (int)$args['end']);

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

			$output .= "INSERT INTO `" . $args['table'] . "` ('" . preg_replace('/, $/', '', $fields) . "') VALUES ('" . preg_replace('/, $/', '', $values) . "');" . "\n";
		}

		if ($args['end'] == $args['total']) {
			$output .= "\n";
		}

		$handle = fopen(DIR_STORAGE . 'backup/' . $filename, 'a');

		fwrite($handle, $output);

		fclose($handle);

		return ['success' => sprintf($this->language->get('text_backup'), $args['table'], $args['start'], $args['end'], $args['total'])];
	}
}