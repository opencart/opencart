<?php
namespace Opencart\Admin\Controller\Task\System;
/**
 * Class Admin
 *
 *
 *
 * @package Opencart\Admin\Controller\Task\System
 */
class Admin extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/system/admin');

		if (!array_key_exists('name', $args)) {
			return ['error' => $this->language->get('error_name')];
		}

		$name = preg_replace('/[^a-zA-Z0-9]/', '', basename(html_entity_decode(trim($args['name']), ENT_QUOTES, 'UTF-8')));

		$blocked = [
			'admin',
			'catalog',
			'extension',
			'image',
			'install',
			'system'
		];

		if (in_array($name, $blocked)) {
			return ['error' => $this->language->get('error_allowed', $name)];
		}

		$base_old = DIR_OPENCART . 'admin/';
		$base_new = DIR_OPENCART . $name . '/';

		if (!is_dir($base_old)) {
			return ['error' => $this->language->get('error_exists')];
		}

		if (is_dir($base_new)) {
			return ['error' => $this->language->get('error_admin')];
		}

		$limit = 200;

		// 1. We need to copy the files, as rename cannot be used on any directory, the executing script is running under
		$files = oc_directory_read($base_old, true);

		// 3. Split the file copies into chunks.
		$total = count($files);

		$page_total = ceil($total / $limit);

		for ($i = 0; $i < $page_total; $i++) {
			$start = $i * $limit;

			$task_data = [
				'code'   => 'storage',
				'action' => 'task/system/admin.move',
				'args'   => [
					'name'  => $name,
					'start' => $start,
					'limit' => $limit
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$task_data = [
			'code'   => 'storage',
			'action' => 'task/system/admin.config',
			'args'   => ['name' => $name]
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/*
	 * Move
	 *
	 * Moves admin directory.
	 *
	 * @args array []
	 *
	 * @return array
	 */
	public function move(array $args = []): array {
		$this->load->language('task/system/admin');

		$required = [
			'name',
			'start',
			'limit'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => $this->language->get('error_required', $value)];
			}
		}

		$name = preg_replace('/[^a-zA-Z0-9]/', '', basename(html_entity_decode(trim($args['name']), ENT_QUOTES, 'UTF-8')));

		// 2. Create the new admin folder name
		$blocked = [
			'admin',
			'catalog',
			'extension',
			'image',
			'install',
			'system'
		];

		if (in_array($name, $blocked)) {
			return ['error' => $this->language->get('error_allowed', $name)];
		}

		$base_old = DIR_OPENCART . 'admin/';
		$base_new = DIR_OPENCART . $name . '/';

		if (!is_dir($base_old)) {
			return ['error' => $this->language->get('error_exists_old')];
		}

		if (!is_dir($base_new) && !@mkdir($base_new, 0777)) {
			return ['error' => $this->language->get('error_exists_new')];
		}

		// 1. We need to copy the files, as rename cannot be used on any directory, the executing script is running under
		$files = oc_directory_read($base_old, true);

		// 3. Split the file copies into chunks.
		$total = count($files);

		// 4. Copy the files across
		foreach (array_slice($files, $args['start'], $args['limit']) as $file) {
			$destination = substr($file, strlen($base_old));

			oc_directory_create($base_new . dirname($destination), 0777);

			if (is_file($base_old . $destination) && !is_file($base_new . $destination)) {
				copy($base_old . $destination, $base_new . $destination);
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
		$this->load->language('task/system/admin');

		if (!array_key_exists('name', $args)) {
			return ['error' => $this->language->get('error_name')];
		}

		$name = preg_replace('/[^a-zA-Z0-9]/', '', basename(html_entity_decode(trim($args['name']), ENT_QUOTES, 'UTF-8')));

		$base_new = DIR_OPENCART .  $name . '/';

		$blocked = [
			'admin',
			'catalog',
			'extension',
			'image',
			'install',
			'system'
		];

		if (in_array($name, $blocked)) {
			return ['error' => $this->language->get('error_allowed', $name)];
		}

		if (!is_writable($base_new . 'config.php')) {
			return ['error' => $this->language->get('error_writable')];
		}

		// Update the config file
		$file = $base_new . 'config.php';

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

