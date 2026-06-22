<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Event
 */
class Topic extends \Opencart\System\Engine\Controller {
	/*
	 * Add Topic
	 *
	 * Adds task to generate new topic data.
	 *
	 * Trigger admin/model/cms/topic/addTopic/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addTopic(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['topic_store'])) {
			$store_ids = (array)$args[1]['topic_store'];
		}

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'topic.list.' . $store_id,
				'action' => 'task/catalog/topic.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'topic.info.' . $store_id . '.' . $output,
				'action' => 'task/catalog/topic.info',
				'args'   => [
					'topic_id' => $output,
					'store_id' => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Edit Topic
	 *
	 * Adds task to generate new topic data.
	 *
	 * Trigger admin/model/cms/topic/editTopic/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editTopic(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$store_ids = [];

		if (isset($args[1]['topic_store'])) {
			$store_ids = (array)$args[1]['topic_store'];
		}

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'topic.list.' . $store_id,
				'action' => 'task/catalog/topic.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'topic.info.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/topic.info',
				'args'   => [
					'topic_id' => $args[0],
					'store_id' => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}

		// Remove store ID's
		$this->load->model('cms/topic');

		$remove_ids = array_diff($this->model_cms_topic->getStores($args[0]), $store_ids);

		foreach ($remove_ids as $remove_id) {
			$task_data = [
				'code'   => 'topic.delete.' . $remove_id . '.' . $args[0],
				'action' => 'task/catalog/topic.delete',
				'args'   => [
					'topic_id' => $args[0],
					'store_id' => $remove_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Delete Topic
	 *
	 * Adds task to generate delete topic data.
	 *
	 * Trigger admin/model/cms/topic/deleteTopic/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteTopic(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$this->load->model('catalog/information');

		$store_ids = $this->model_catalog_information->getStores($args[0]);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'topic.list.' . $store_id,
				'action' => 'task/catalog/topic.list',
				'args'   => ['store_id' => $store_id]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'topic.delete.' . $store_id . '.' . $args[0],
				'action' => 'task/catalog/topic.delete',
				'args'   => [
					'topic_id' => $args[0],
					'store_id' => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}
}
