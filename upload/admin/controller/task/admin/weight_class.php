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
	 * Generates weight class task list.
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/weight_class');

		$this->load->model('setting/task');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$task_data = [
				'code'   => 'weight_class',
				'action' => 'task/admin/weight_class.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates the weight class list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/weight_class');

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$weight_class_data = [];

		$this->load->model('localisation/weight_class');

		$weight_classes = $this->model_localisation_weight_class->getWeightClasses();

		foreach ($weight_classes as $weight_class) {
			$description_info = $this->model_localisation_weight_class->getDescription($weight_class['weight_class_id'], $language_info['language_id']);

			if ($description_info) {
				$weight_class_data[$weight_class['weight_class_id']] = $description_info + $weight_class;
			}
		}

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'weight_class.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($weight_class_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}

	public function clear(array $args = []): array {
		$this->load->language('task/admin/weight_class');

		return ['success' => $this->language->get('text_clear')];
	}
}
