<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Review
 *
 * @package Opencart\Admin\Controller\Event
 */
class Review extends \Opencart\System\Engine\Controller {
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

	public function addReview(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'review.list.' . $args['product_id'],
			'action' => 'task/catalog/review',
			'args'   => ['product_id' => $args['product_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function editReview(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'review.list.' . $args['product_id'],
			'action' => 'task/catalog/review',
			'args'   => ['product_id' => $args['product_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	public function deleteReview(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'review.list.' . $args['product_id'],
			'action' => 'task/catalog/review',
			'args'   => ['product_id' => $args['product_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}
}
