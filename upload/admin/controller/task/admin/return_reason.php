<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Return Reason
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class ReturnReason extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the return reason list JSON files.
	 *
	 * @return void
	 */
	public function index(): array {
		$this->load->language('task/admin/return_reason');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			// Add a task for generating the return reason list
			$task_data = [
				'code'   => 'return_reason',
				'action' => 'admin/return_reason.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_success')];
	}

	public function list(array $args = []): array {
		$this->load->language('task/admin/return_reason');

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/return_reason');

		$return_reasons = $this->model_localisation_return_reason->getReturnReasons(['filter_language_id' => $language_info['language_id']]);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'return_reason.json';

		if (!oc_directory_create($base . $directory, 0777)) {
			return ['error' => sprintf($this->language->get('error_directory'), $directory)];
		}

		if (!file_put_contents($base . $directory . $filename, json_encode($return_reasons))) {
			return ['error' => sprintf($this->language->get('error_file'), $directory . $filename)];
		}

		return ['success' => sprintf($this->language->get('text_list'), $language_info['name'])];
	}

	public function clear(): void {
		$this->load->language('task/admin/language');

		$json = [];

		if (!$this->user->hasPermission('modify', 'admin/custom_field')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
