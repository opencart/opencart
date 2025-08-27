<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Admin
 *
 *
 *
 * @package Opencart\Admin\Controller\Common
 */
class Admin extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/system/admin');

		$required = [
			'base_old',
			'base_new'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		if (!is_dir($args['base_old'])) {
			return ['error' => $this->language->get('error_exists')];
		}

		if (is_dir($args['base_new'])) {
			return ['error' => $this->language->get('error_admin')];
		}

		$blocked = [
			'admin',
			'catalog',
			'extension',
			'image',
			'install',
			'system'
		];

		$name = basename($args['base_new']);

		if (in_array($name, $blocked)) {
			return ['error' => sprintf($this->language->get('error_allowed'), $name)];
		}

		$limit = 200;

		// 1. We need to copy the files, as rename cannot be used on any directory, the executing script is running under
		$files = oc_directory_read($args['base_old'], true);

		// 3. Split the file copies into chunks.
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
					'base_old' => $args['base_old'],
					'base_new' => $args['base_new'],
					'start'    => $start,
					'end'      => $end
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
		$this->load->language('task/system/admin');

		$required = [
			'base_old',
			'base_new',
			'start',
			'end'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		// 2. Create the new admin folder name
		if (!is_dir($args['base_new'])) {
			mkdir($args['base_new'], 0777);
		}

		// 1. We need to copy the files, as rename cannot be used on any directory, the executing script is running under
		$files = oc_directory_read($args['base_old'], true);

		// 3. Split the file copies into chunks.
		$total = count($files);

		// 4. Copy the files across
		foreach (array_slice($files, $args['start'], $args['end']) as $file) {
			$destination = substr($file, strlen($args['base_old']));

			oc_directory_create($args['base_new'] . dirname($destination), 0777);

			if (is_file($args['base_old'] . $destination) && !is_file($args['base_new'] . $destination)) {
				copy($args['base_old'] . $destination, $args['base_new'] . $destination);
			}
		}

		return ['success' => sprintf($this->language->get('text_move'), $args['start'], $args['end'], $total)];
	}

	/*
	 * Config
	 *
	 * Modify the config files
	 */
	public function config(array $args = []): array {
		$this->load->language('task/system/admin');

		if (!array_key_exists($value, $args)) {

		}

		if (!is_writable(DIR_OPENCART . 'config.php') || !is_writable(DIR_APPLICATION . 'config.php')) {
			return ['error' => $this->language->get('error_writable')];
		}

		// Update the old config files
		$file = $args['base_new'] . 'config.php';

		$output = '';

		$lines = file($file);

		foreach ($lines as $line_id => $line) {
			$status = true;

			if (strpos($line, 'define(\'HTTP_SERVER') !== false) {
				$output .= 'define(\'HTTP_SERVER\', \'' . substr(HTTP_SERVER, 0, strrpos(HTTP_SERVER, '/admin/')) . '/' . $name . '/\');' . "\n";

				$status = false;
			}

			if (strpos($line, 'define(\'DIR_APPLICATION') !== false) {
				$output .= 'define(\'DIR_APPLICATION\', DIR_OPENCART . \'' . $name . '/\');' . "\n";

				$status = false;
			}

			if ($status) {
				$output .= $line;
			}
		}

		$file = fopen($file, 'w');

		fwrite($file, $output);

		fclose($file);

		return ['success' => $this->language->get('text_config')];
	}
}

