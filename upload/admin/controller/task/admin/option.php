<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Option
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Option extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the country list JSON files by language.
	 *
	 * @return void
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/option');

		$this->load->model('setting/task');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$task_data = [
				'code'   => 'option',
				'action' => 'task/admin/option.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_success')];
	}

	/**
	 * List
	 *
	 * Generates the length class list file.
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/option');

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$length_class_data = [];

		$this->load->model('localisation/length_class');

		$length_classes = $this->model_localisation_length_class->getLengthClasses();

		foreach ($length_classes as $length_class) {
			$description_info = $this->model_localisation_length_class->getDescription($length_class['length_class_id'], $language_info['language_id']);

			if ($description_info) {
				$length_class_data[$length_class['length_class_id']] = $description_info + $length_class;
			}
		}

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'length_class.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($length_class_data))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}


	public function clear(array $args = []): array {
		$this->load->language('task/admin/option');

		return ['success' => $this->language->get('text_clear')];
	}
}
