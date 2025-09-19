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

		$this->load->model('setting/task');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$task_data = [
				'code'   => 'length_class',
				'action' => 'task/admin/length_class.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

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

		if (!array_key_exists('language_id', $args)) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/length_class');

		$length_classes = $this->model_localisation_length_class->getLengthClasses(['filter_language_id' => $language_info['language_id']]);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'length_class.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => $this->language->get('error_directory', $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($length_classes))) {
			return ['error' => $this->language->get('error_file', $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list', $language_info['name'])];
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

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/localisation/length_class.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
