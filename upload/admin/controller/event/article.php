<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Event
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Add Article
	 *
	 * Adds task to generate new article data.
	 *
	 * Called using admin/model/cms/article/addArticle/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	private function addArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.list',
			'action' => 'task/catalog/article.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'article.info.' . $output,
			'action' => 'task/catalog/article.info',
			'args'   => ['article_id' => $output]
		];

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Edit Article
	 *
	 * Adds task to generate new article data.
	 *
	 * Called using admin/model/cms/article/addArticle/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	private function editArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.list',
			'action' => 'task/catalog/article.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'article.info.' . $args[0],
			'action' => 'task/catalog/article.info',
			'args'   => ['article_id' => $args[0]]
		];

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Delete Article
	 *
	 * Adds task to generate new article data.
	 *
	 * Called using admin/model/cms/article/addArticle/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	private function deleteArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.list',
			'action' => 'task/catalog/article.list',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);

		$task_data = [
			'code'   => 'article.delete.' . $args[0],
			'action' => 'task/catalog/article.delete',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
