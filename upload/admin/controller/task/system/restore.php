<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Restore
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Restore extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate restore task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/system/restore');

		if (!array_key_exists('filename', $args)) {
			return ['error' => $this->language->get('error_filename')];
		}

		$filename = basename(html_entity_decode($args['filename'], ENT_QUOTES, 'UTF-8'));

		if (!oc_validate_length($filename, 5, 128)) {
			return ['error' => $this->language->get('error_filename')];
		}

		$file = DIR_STORAGE . 'backup/' . $filename;

		if (!is_file($file)) {
			return ['error' => $this->language->get('error_file')];
		}

		$maintenance = $this->config->get('config_maintenance');

		$this->config->set('config_maintenance', true);

		$task_data = [
			'code'   => 'restore',
			'action' => 'task/system/restore.read',
			'args'   => [
				'filename'    => $args['filename'],
				'position'    => 0,
				'maintenance' => $maintenance
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_success')];
	}

	/*
	 * Read
	 *
	 * @return array
	 */
	public function read(array $args = []): array {
		$this->load->language('task/system/restore');

		$required = [
			'filename',
			'position',
			'maintenance'
		];

		foreach ($required as $value) {
			if (!isset($args[$value])) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		$file = DIR_STORAGE . 'backup/' . $args['filename'];

		if (!is_file($file)) {
			return ['error' => $this->language->get('error_file')];
		}

		$disallowed = [
			DB_PREFIX . 'task',
			DB_PREFIX . 'user',
			DB_PREFIX . 'user_authorize',
			DB_PREFIX . 'user_group',
			DB_PREFIX . 'user_login',
			DB_PREFIX . 'user_token'
		];

		// We set $i so we can batch execute the queries rather than do them all at once.
		$i = 0;

		$handle = fopen($file, 'r');

		fseek($handle, $args['position'], SEEK_SET);

		while (!feof($handle) && ($i < 1000)) {
			$position = ftell($handle);

			$line = fgets($handle, 4096);

			if ($i > 0) {
				foreach ($disallowed as $table) {
					if (str_starts_with($line, "TRUNCATE TABLE `" . DB_PREFIX . $table . "`") || str_starts_with($line, "INSERT INTO `" . DB_PREFIX . $table . "`")) {
						fseek($handle, $position, SEEK_SET);

						break 2;
					}
				}
			}

			if ((substr($line, 0, 14) == 'TRUNCATE TABLE' || substr($line, 0, 11) == 'INSERT INTO') && substr($line, -2) == ";\n") {
				$this->db->query(substr($line, 0, strlen($line) - 2));
			}

			$i++;
		}

		$position = ftell($handle);

		$size = filesize($file);

		if ($position) {
			$progress = round(($position / $size) * 100, 2);
		} else {
			$progress = 0;
		}

		if ($position && !feof($handle)) {
			$task_data = [
				'code'   => 'backup',
				'action' => 'task/system/restore.read',
				'args'   => [
					'filename'   => $args['filename'],
					'position'   => $position,
					'maintenance'=> $args['maintenance']
				]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		} else {
			$this->config->set('config_maintenance', $args['maintenance']);
		}

		fclose($handle);

		return ['success' => sprintf($this->language->get('text_restore'), $progress . '%')];
	}
}