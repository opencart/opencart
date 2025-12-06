<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Tax Class
 *
 * @package Opencart\Admin\Controller\Task\Admin
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
		$this->load->language('task/admin/tax_class');

		// Clear old data
		$task_data = [
			'code'   => 'tax_class',
			'action' => 'task/admin/tax_class.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'tax_class',
			'action' => 'task/admin/tax_class.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON order status list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/tax_class');

		$this->load->model('setting/task');

		$this->load->model('localisation/tax_class');

		$tax_classes = $this->model_localisation_tax_class->getTaxClasses();

		foreach ($tax_classes as $tax_class) {
			$task_data = [
				'code'   => 'tax_class',
				'action' => 'task/admin/tax_class.info',
				'args'   => ['tax_class_id' => $tax_class['tax_class_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'tax_class.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($tax_classes))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/**
	 * Info
	 *
	 * Generate JSON customer group information file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function info(array $args = []): array {
		$this->load->language('task/admin/tax_class');

		$required = [
			'tax_class_id',
			'language_id'
		];

		foreach ($required as $value) {
			if (!array_key_exists($value, $args)) {
				return ['error' => sprintf($this->language->get('error_required'), $value)];
			}
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage((int)$args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/tax_class');

		$tax_class_info = $this->model_localisation_tax_class->getTaxClass((int)$args['tax_class_id']);

		if (!$tax_class_info) {
			return ['error' => $this->language->get('error_tax_class')];
		}

		$filter_data = [
			'filter_customer_group_id' => $tax_class_info['tax_class_id'],
			'filter_language_id'       => $language_info['language_id'],
			'filter_Status'            => true
		];

		$tax_rules = $this->model_localisation_tax_class->getTaxRules($filter_data);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/customer/';
		$filename = 'tax_class-' . $tax_class_info['tax_class_id'] . '.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($customer_group_info + $description_info + ['tax_rate' => $tax_rates]))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_info'), $language_info['name'], $customer_group_info['name'])];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON order status files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/order_status');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/localisation/tax_class.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
