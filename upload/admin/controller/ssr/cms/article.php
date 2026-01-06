<?php
namespace Opencart\admin\controller\ssr\cms;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Event
 */
class Article extends \Opencart\System\Engine\Controller {
	public function add(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.info.' . $output,
			'action' => 'task/catalog/article.info',
			'args'   => ['article_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function edit(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.info.' . $args[0],
			'action' => 'task/catalog/article.info',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function delete(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.delete.' . $args[0],
			'action' => 'task/catalog/article.delete',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
