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

		$required = [
			'base_old',
			'base_new'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		// Check current storage path exists
		if (!is_dir($args['base_old'])) {
			return ['error' => $this->language->get('error_exists')];
		}

		// Check the chosen directory is not in the public webspace
		$root = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../'));

		if ((substr($args['base_new'], 0, strlen($root)) != $root) || ($root == $args['base_new'])) {
			return ['error' => $this->language->get('error_storage_root')];
		}

		if (!str_starts_with(basename($args['base_new']), 'storage')) {
			return ['error' => $this->language->get('error_storage_name')];
		}

		$this->load->model('setting/task');

		$limit = 200;

		$files = oc_directory_read($args['base_old'], true);

		$total = count($files);

		$page_total = ceil($total / $limit);

		for ($i = 0; $i < $page_total; $i++) {
			$start = $i * $limit;

			$task_data = [
				'code'   => 'storage',
				'action' => 'task/system/storage.move',
				'args'   => [
					'base_old' => $args['base_old'],
					'base_new' => $args['base_new'],
					'start'    => $start,
					'limit'    => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$task_data = [
			'code'   => 'storage',
			'action' => 'task/system/storage.config',
			'args'   => ['path' => $args['base_new']]
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

		$required = [
			'base_old',
			'base_new',
			'start',
			'limit'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

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

		$total = count($files);

		foreach (array_slice($files, $args['start'], $args['limit']) as $file) {
			$destination = substr($file, strlen($args['base_old']));

			oc_directory_create($args['base_new'] . dirname($destination), 0777);

			if (is_file($args['base_old'] . $destination) && !is_file($args['base_new'] . $destination)) {
				copy($args['base_old'] . $destination, $args['base_new'] . $destination);
			}
		}

		return ['success' => sprintf($this->language->get('text_move'), (!$args['start'] && $total) ? 1 : $args['start'], ($args['start'] > ($args['total'] - $args['limit'])) ? $args['total'] : $args['start'] + $args['limit'], $total)];
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
			return ['error' => $this->language->get('error_delete')];
		}

		// Delete old admin directory
		oc_directory_delete($path);

		return ['success' => $this->language->get('text_delete')];
	}
}

