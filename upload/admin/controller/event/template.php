<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Template
 *
 * @package Opencart\Admin\Controller\Event
 */
class Template extends \Opencart\System\Engine\Controller {
	/**
	 * Add Template
	 *
	 * Adds task to generate new template data.
	 *
	 * model/design/template/addTemplate/after
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function addTemplate(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'template.info.' . $args[0]['store_id'] . '.' . str_replace('/', '.', $args[0]['route']),
			'action' => 'task/catalog/template.info',
			'args'   => [
				'route'    => $args[0]['route'],
				'store_id' => $args[0]['store_id']
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/**
	 * Edit Template
	 *
	 * Adds task to generate new template data
	 *.
	 * model/design/template/editTemplate/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function editTemplate(string &$route, array &$args): void {
		$task_data = [
			'code'   => 'template.info.' . $args[1]['store_id'] . '.' . str_replace('/', '.', $args[1]['route']),
			'action' => 'task/catalog/template.info',
			'args'   => [
				'route'    => $args[1]['route'],
				'store_id' => $args[1]['store_id']
			]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// Template
		$this->load->model('design/template');

		$template_info = $this->model_design_template->getTemplate($args[0]);

		if ($template_info && ($template_info['store_id'] !== $args[1]['store_id'] && $template_info['route'] !== $args[1]['route'])) {
			$task_data = [
				'code'   => 'template.info.' . $template_info['store_id'] . '.' . str_replace('/', '.', $template_info['route']),
				'action' => 'task/catalog/template.info',
				'args'   => [
					'route'    => $template_info['route'],
					'store_id' => $template_info['store_id']
				]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}

	/**
	 * Delete Template
	 *
	 * Adds task to generate new template data.
	 *
	 * model/design/template/deleteTemplate/before
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 */
	public function deleteTemplate(string &$route, array &$args): void {
		$this->load->model('design/template');

		$template_info = $this->model_design_template->getTemplate($args[0]);

		if ($template_info) {
			$task_data = [
				'code'   => 'template.info.' . $template_info['store_id'] . '.' . str_replace('/', '.', $template_info['route']),
				'action' => 'task/catalog/template.info',
				'args'   => [
					'route'    => $template_info['route'],
					'store_id' => $template_info['store_id']
				]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}
}
