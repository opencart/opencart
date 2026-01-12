<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Review
 *
 * @package Opencart\Admin\Controller\Event
 */
class Review extends \Opencart\System\Engine\Controller {
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
