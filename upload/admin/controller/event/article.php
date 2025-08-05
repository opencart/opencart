<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Event
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * Adds task to generate new article list
	 *
	 * Called using admin/model/cms/article/addArticle/after
	 * Called using admin/model/cms/article/editArticle/after
	 * Called using admin/model/cms/article/deleteArticle/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 *
	 * @return void
	 */
	public function index(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article',
			'action' => 'task/catalog/article',
			'args'   => []
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
