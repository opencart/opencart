<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Length Class
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class LengthClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate length class task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
     */
	public function index(array $args = []): array {
		$this->load->language('task/admin/length_class');

		// Clear old data
		$task_data = [
			'code'   => 'length_class',
			'action' => 'task/admin/length_class.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'length_class',
			'action' => 'task/admin/length_class.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON length class list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/length_class');

		$length_class_data = [];

		$this->load->model('localisation/length_class');

		$length_classes = $this->model_localisation_length_class->getLengthClasses();

		foreach ($length_classes as $length_class) {
			$length_class_data[] = $length_class + ['description' => $this->model_localisation_length_class->getDescriptions($length_class['length_class_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'length_class.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($length_class_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON length class files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/length_class');

		$file = DIR_APPLICATION . 'view/data/localisation/length_class.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
