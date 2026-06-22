<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Translation
 *
 * @package Opencart\Admin\Controller\Event
 */
class Translation extends \Opencart\System\Engine\Controller {
	/**
	 * Add Translation
	 *
	 * Adds task to generate new translation data
	 *
	 * Trigger admin/model/design/translation.addTranslation/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function addTranslation(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'translation.info.' . $args[0]['store_id'] . '.' . $args[0]['language_id'] . '.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/translation.info',
			'args'   => [
				'route'       => $args[0]['route'],
				'language_id' => $args[0]['language_id'],
				'store_id'    => $args[0]['store_id']
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Translation
	 *
	 * Adds task to generate new translation data
	 *
	 * Trigger admin/model/design/translation.editTranslation/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function editTranslation(string &$route, array &$args): void {
		$task_data = [
			'code'   => 'translation.info.' . $args[1]['store_id'] . '.' . $args[1]['language_id'] . '.' . str_replace('/', '.', $args[1]['route']),
			'action' => 'task/catalog/translation.info',
			'args'   => [
				'route'       => $args[1]['route'],
				'language_id' => $args[1]['language_id'],
				'store_id'    => $args[1]['store_id']
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Translation
		$this->load->model('design/translation');

		$translation_info = $this->model_design_translation->getTranslation($args[0]);

		if ($translation_info && ($translation_info['store_id'] !== $args[1]['store_id'] || $translation_info['language_id'] !== $args[1]['language_id'] || $translation_info['route'] !== $args[1]['route'])) {
			$task_data = [
				'code'   => 'translation.info.' . $translation_info['store_id'] . '.' . $translation_info['language_id'] . '.' . str_replace('/', '.', $translation_info['route']),
				'action' => 'task/catalog/translation.info',
				'args'   => [
					'route'       => $translation_info['route'],
					'language_id' => $translation_info['language_id'],
					'store_id'    => $translation_info['store_id']
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Delete Translation
	 *
	 * Adds task to generate new translation data
	 *
	 * Trigger admin/model/design/translation.deleteTranslation/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function deleteTranslation(string &$route, array &$args, &$output): void {
		$this->load->model('design/translation');

		$translation_info = $this->model_design_translation->getTranslation($args[0]);

		if ($translation_info) {
			$task_data = [
				'code'   => 'translation.info.' . $translation_info['store_id'] . '.' . $translation_info['language_id'] . '.' . str_replace('/', '.', $translation_info['route']),
				'action' => 'task/catalog/translation.info',
				'args'   => [
					'route'       => $translation_info['route'],
					'language_id' => $translation_info['language_id'],
					'store_id'    => $translation_info['store_id']
				]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}
}
