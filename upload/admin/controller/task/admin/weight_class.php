<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Weight Class
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class WeightClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate weight class task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/weight_class');

		// Clear old data
		$task_data = [
			'code'   => 'weight_class',
			'action' => 'task/admin/weight_class.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'weight_class',
			'action' => 'task/admin/weight_class.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON weight class list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/weight_class');

		$weight_class_data = [];

		$this->load->model('localisation/weight_class');

		$weight_classes = $this->model_localisation_weight_class->getWeightClasses();

		foreach ($weight_classes as $weight_class) {
			$weight_class_data[] = $weight_class + ['description' => $this->model_localisation_weight_class->getDescriptions($weight_class['weight_class_id'])];
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'weight_class.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($weight_class_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON weight class files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/weight_class');

		$file = DIR_APPLICATION . 'view/data/localisation/weight_class.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
