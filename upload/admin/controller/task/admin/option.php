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

	public function clear(array $args = []): array {
		$this->load->language('task/admin/option');

		return ['success' => $this->language->get('text_clear')];
	}
}
