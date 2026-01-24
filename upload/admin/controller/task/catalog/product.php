<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Information
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Product extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate information task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/information');

		// List
		$task_data = [
			'code'   => 'information',
			'action' => 'task/catalog/information.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Info
		$this->load->model('catalog/information');

		$informations = $this->model_catalog_information->getInformations();

		foreach ($informations as $information) {
			$task_data = [
				'code'   => 'information',
				'action' => 'task/catalog/information.info',
				'args'   => ['information_id' => $information['information_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}


	public function info(array $args = []): array {
		$this->load->language('task/admin/information');

		if (!array_key_exists('information_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$args['information_id']);

		if (!$information_info) {
			return ['error' => $this->language->get('error_information')];
		}

		$information_info = $information_info + ['description' => $this->model_catalog_information->getDescriptions($information_info['information_id'])];

		$directory = DIR_APPLICATION . 'view/data/catalog/';
		$filename = 'information-' . $information_info['information_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($information_info))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $information_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON information files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/information');

		$file = HTTP_SERVER . 'view/data/admin/information.json';

		if (is_file($file)) {
			unlink($file);
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
