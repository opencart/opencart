<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class Currency extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate currency task list.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/admin/currency');

		// Clear old data
		$task_data = [
			'code'   => 'currency',
			'action' => 'task/admin/currency.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Create new data
		$task_data = [
			'code'   => 'currency',
			'action' => 'task/admin/currency.list'
		];

		$this->model_setting_task->addTask($task_data);

		return ['success' => $this->language->get('text_task')];
	}

	/**
	 * List
	 *
	 * Generate JSON currency list file.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function list(array $args = []): array {
		$this->load->language('task/admin/currency');

		$this->load->model('localisation/currency');

		$currencies = $this->model_localisation_currency->getCurrencies();

		$directory = DIR_APPLICATION . 'view/data/localisation/';
		$filename = 'currency.json';

		if (!oc_directory_create($directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($directory . $filename, json_encode($currencies))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => $this->language->get('text_list')];
	}

	/*
	 * Refresh
	 *
	 * Gets the latest currency values and updates the database.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function refresh(array $args = []): array {
		$this->load->language('task/admin/currency');

		$this->load->model('setting/task');

		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('currency', $this->config->get('config_currency_engine'));

		if ($extension_info) {
			$this->load->controller('extension/' . $extension_info['extension'] . '/currency/' . $extension_info['code'] . '.currency', $this->config->get('config_currency'));

			// Add a task for generating the country info data
			$task_data = [
				'code'   => 'currency',
				'action' => 'task/admin/currency',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_refresh')];
	}

	/**
	 * Clear
	 *
	 * Delete generated JSON currency files.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function clear(array $args = []): array {
		$this->load->language('task/admin/currency');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			$file = DIR_APPLICATION . 'view/data/' . $language['code'] . '/localisation/currency.json';

			if (is_file($file)) {
				unlink($file);
			}
		}

		return ['success' => $this->language->get('text_clear')];
	}
}
