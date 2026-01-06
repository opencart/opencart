<?php
namespace Opencart\Admin\Controller\Task\Catalog;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Task\Catalog
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Generate all country data.
	 *
	 * @param array<string, string> $args
	 *
	 * @return array
	 */
	public function add(array $args = []): array {
		$this->load->language('task/catalog/topic');

		// Clear old data
		$task_data = [
			'code'   => 'topic',
			'action' => 'task/catalog/topic.clear',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		// List
		$task_data = [
			'code'   => 'topic',
			'action' => 'task/catalog/topic.list',
			'args'   => []
		];

		$this->model_setting_task->addTask($task_data);

		// Info
		$this->load->model('cms/topic');

		$topics = $this->model_cms_topic->getCountries();

		foreach ($topics as $topic) {
			$task_data = [
				'code'   => 'topic',
				'action' => 'task/catalog/topic.info',
				'args'   => ['topic_id' => $topic['topic_id']]
			];

			$this->model_setting_task->addTask($task_data);
		}

		return ['success' => $this->language->get('text_task')];
	}


}