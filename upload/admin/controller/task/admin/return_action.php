<?php
namespace Opencart\Admin\Controller\Task\Admin;
/**
 * Class Return Action
 *
 * @package Opencart\Admin\Controller\Task\Admin
 */
class ReturnAction extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generates the return reason list JSON files.
	 *
	 * @return void
	 */
	public function index(): array {
		$this->load->language('task/admin/return_action');

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			// Add a task for generating the country list
			$task_data = [
				'code'   => 'return_action',
				'action' => 'admin/return_action.list',
				'args'   => ['language_id' => $language['language_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_success')];
	}

	public function list(array $args = []): array {
		$this->load->language('task/admin/return_action');

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($args['language_id']);

		if (!$language_info) {
			return ['error' => $this->language->get('error_language')];
		}

		$this->load->model('localisation/return_action');

		$return_reasons = $this->model_localisation_return_action->getReturnActions(['filter_language_id' => $language_info['language_id']]);

		$base = DIR_APPLICATION . 'view/data/';
		$directory = $language_info['code'] . '/localisation/';
		$filename = 'return_action.json';

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
