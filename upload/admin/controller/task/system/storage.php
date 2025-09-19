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
				return ['error' => $this->language->get('error_required', $value)];
			}
		}

		// Check current storage path exists
		if (!is_dir($args['base_old'])) {
			return ['error' => $this->language->get('error_exists_old')];
		}

		if (is_dir($args['base_new'])) {
			return ['error' => $this->language->get('error_exists_new')];
		}

		// Check the chosen directory is not in the public webspace
		$path = str_replace('\\', '/', realpath($args['base_new'] . '/../'));

		$root = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../'));

		if ((substr($root, 0, strlen($path)) != $path) || ($root == $args['base_new'])) {
			return ['error' => $this->language->get('error_root')];
		}

		if (!str_starts_with(basename($args['base_new']), 'storage')) {
			return ['error' => $this->language->get('error_name')];
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
				return ['error' => $this->language->get('error_required', $value)];
			}
		}

		// Check the chosen directory is not in the public webspace
		$path = str_replace('\\', '/', realpath($args['base_new'] . '/../'));

		$root = str_replace('\\', '/', realpath($this->request->server['DOCUMENT_ROOT'] . '/../'));

		if ((substr($root, 0, strlen($path)) != $path) || ($root == $args['base_new'])) {
			return ['error' => $this->language->get('error_root')];
		}

		if (!str_starts_with(basename($args['base_new']), 'storage')) {
			return ['error' => $this->language->get('error_name')];
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

		$progress = 0;

		if ($total) {
			if ($args['start'] > ($total - $args['limit'])) {
				$end = $total;
			} else {
				$end = $args['start'] + $args['limit'];
			}

			$progress = round(($end / $total) * 100, 2);
		}

		return ['success' => $this->language->get('text_move', $progress . '%')];
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
			DIR_OPENCART . 'config.php',
			DIR_APPLICATION . 'config.php'
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
}

