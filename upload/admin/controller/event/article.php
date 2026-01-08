<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Event
 */
class Article extends \Opencart\System\Engine\Controller {
	public function index(string &$route, array &$args, &$output): void {
		$pos = strpos($route, '.');

		if ($pos == false) {
			return;
		}

		$method = substr($route, 0, $pos);

		$callable = [$this, $method];

		if (is_callable($callable)) {
			$callable($route, $args, $output);
		}
	}

	private function addArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.info.' . $output,
			'action' => 'task/catalog/article.info',
			'args'   => ['article_id' => $output]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	private function editArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.info.' . $args[0],
			'action' => 'task/catalog/article.info',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	private function deleteArticle(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'article.delete.' . $args[0],
			'action' => 'task/catalog/article.delete',
			'args'   => ['article_id' => $args[0]]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
