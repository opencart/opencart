<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Storage
 *
 *
 *
 * @package Opencart\Admin\Controller\Common
 */
class Storage extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/system/storage');

		if (isset($args['name'])) {
			$name = preg_replace('/[^a-zA-Z0-9_\.]/', '', $args['name']);
		} else {
			$name = '';
		}

		if (isset($args['path'])) {
			$path = preg_replace('/[^a-zA-Z0-9_\:\/\.]/', '', $args['path']);
		} else {
			$path = '';
		}

		$base_old = DIR_STORAGE;
		$base_new = $path . $name . '/';

		// Check current storage path exists
		if (!is_dir($base_old)) {
			return ['error' => $this->language->get('error_storage')];
		}

		// Check the chosen directory is not in the public webspace
		$root = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../'));

		if ((substr($base_new, 0, strlen($root)) != $root) || ($root == $base_new)) {
			return ['error' => $this->language->get('error_storage_root')];
		}

		if (!str_starts_with($name, 'storage')) {
			return ['error' => $this->language->get('error_storage_name')];
		}

		$this->load->model('setting/task');

		$limit = 200;

		// Make path into an array
		$files = oc_directory_read($base_old, true);

		$total = count($files);

		$page_total = ceil($total / $limit);

		for ($i = 0; $i < $page_total; $i++) {
			$start = ($i - 1) * $limit;

			if ($start > ($total - $limit)) {
				$end = $total;
			} else {
				$end = ($start + $limit);
			}

			$task_data = [
				'code'   => 'storage',
				'action' => 'task/system/storage.move',
				'args'   => [
					'base_old' => $base_old,
					'base_new' => $base_new,
					'start'    => $start,
					'end'      => $end
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$task_data = [
			'code'   => 'storage',
			'action' => 'task/system/storage.config',
			'args'   => ['path' => $base_new]
		];

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'storage',
			'action' => 'task/system/storage.delete',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_success')];
	}

	public function move(array $args = []): array {
		$this->load->language('task/system/storage');

		// Check the chosen directory is not in the public webspace
		$root = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../'));

		if ((substr($args['base_new'], 0, strlen($root)) != $root) || ($root == $args['base_new'])) {
			return ['error' => $this->language->get('error_storage_root')];
		}

		if (!str_starts_with(basename($args['base_new']), 'storage')) {
			return ['error' => $this->language->get('error_storage_name')];
		}

		// Create the new storage folder
		if (!is_dir($args['base_new'])) {
			oc_directory_create($args['base_new'], 0777);
		}

		// Make path into an array
		$files = oc_directory_read($args['base_old'], true);

		for ($i = $args['start']; $i < $args['end']; $i++) {
			$destination = substr($files[$i], strlen($args['base_old']));

			oc_directory_create($args['base_new'] . dirname($destination), 0777);

			if (is_file($args['base_old'] . $destination) && !is_file($args['base_new'] . $destination)) {
				copy($args['base_old'] . $destination, $args['base_new'] . $destination);
			}
		}

		return ['success' => sprintf($this->language->get('text_move'), $args['start'], $args['end'])];
	}

	/*
	 * Config
	 *
	 * Modify the config files
	 */
	public function config(array $args = []): array {
		$this->load->language('task/system/storage');

		if (!is_dir($args['path'])) {
			return ['error' => $this->language->get('error_storage')];
		}

		if (!is_writable(DIR_OPENCART . 'config.php') || !is_writable(DIR_APPLICATION . 'config.php')) {
			return ['error' => $this->language->get('error_writable')];
		}

		$files = [
			DIR_APPLICATION . 'config.php',
			DIR_OPENCART . 'config.php'
		];

		foreach ($files as $file) {
			$output = '';

			$lines = file($file);

			foreach ($lines as $line_id => $line) {
				if (str_contains($line, 'define(\'DIR_STORAGE')) {
					$output .= 'define(\'DIR_STORAGE\', \'' . $args['path'] . '\');' . "\n";
				} else {
					$output .= $line;
				}
			}

			$file = fopen($file, 'w');

			fwrite($file, $output);

			fclose($file);
		}

		return ['success' => $this->language->get('text_config')];
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(array $args = []): array {
		$this->load->language('task/system/storage');

		// Storage directory exists
		$path = DIR_SYSTEM . 'storage/';

		if (!is_dir($path) || DIR_STORAGE == $path) {
			return ['error' => $this->language->get('error_storage')];
		}

		if (!$path) {
			return ['error' => $this->language->get('error_remove')];
		}

		// Delete old admin directory
		oc_directory_delete($path);

		return ['success' => $this->language->get('text_delete')];
	}
}

