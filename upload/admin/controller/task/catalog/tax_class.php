<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Tax Class
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class TaxClass extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate tax class task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/catalog/tax_class');

		$this->load->model('setting/task');

		$this->load->model('localisation/tax_class');

		$tax_classes = $this->model_localisation_tax_class->getTaxClasses();

		foreach ($tax_classes as $tax_class) {
			$task_data = [
				'code'   => 'tax_class',
				'action' => 'task/catalog/tax_class.info',
				'args'   => ['tax_class_id' => $tax_class['tax_class_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * Info
	 *
	 * Generate tax class information.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/catalog/tax_class');

		if (!array_key_exists('tax_class_id', $args)) {
			return ['error' => $this->language->get('error_required')];
		}

		// Tax Class
		$this->load->model('localisation/tax_class');

		$tax_class_info = $this->model_localisation_tax_class->getTaxClass((int)$args['tax_class_id']);

		if (!$tax_class_info) {
			return ['error' => $this->language->get('error_tax_class')];
		}

		$directory = DIR_CATALOG . 'view/data/localisation/';
		$filename = 'tax_class-' . $tax_class_info['tax_class_id'] . '.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($tax_class_info))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $tax_class_info['title'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON country files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/catalog/tax_class');

		$files = oc_directory_read( DIR_CATALOG . 'view/data/localisation/', false, '/tax_class\-.+\.json$/');

		foreach ($files as $file) {
			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
