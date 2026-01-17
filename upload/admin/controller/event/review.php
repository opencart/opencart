<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Review
 *
 * @package Opencart\Admin\Controller\Event
 */
class Review extends \Opencart\System\Engine\Controller {
	/*
	 * Add Review
	 *
	 * Adds task to generate new review data.
	 *
	 * Called using admin/model/catalog/review/addReview/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addReview(string &$route, array &$args, &$output): void {
		$task_data = [
			'code'   => 'review.' . $args[1]['product_id'],
			'action' => 'task/catalog/review',
			'args'   => ['product_id' => $args[1]['product_id']]
		];

		$this->load->model('setting/task');

		$this->model_setting_task->addTask($task_data);
	}

	/*
	 * Edit Review
	 *
	 * Adds task to generate new review data.
	 *
	 * Called using admin/model/catalog/review/editReview/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editReview(string &$route, array &$args, &$output): void {
		$review_info = $this->model_catalog_review->getReview($args[0]);

		if ($review_info) {
			$task_data = [
				'code'   => 'review.' . $args[1]['product_id'],
				'action' => 'task/catalog/review',
				'args'   => ['product_id' => $args[1]['product_id']]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			// In case product was switched we want to update old review list
			if ($args[1]['product_id'] != $review_info['product_id']) {
				$task_data = [
					'code'   => 'review.' . $review_info['product_id'],
					'action' => 'task/catalog/country.info',
					'args'   => ['review_id' => $review_info['product_id']]
				];

				$this->load->model('setting/task');

				$this->model_setting_task->addTask($task_data);
			}
		}
	}

	/*
	 * Delete Review
	 *
	 * Adds task to generate delete review data.
	 *
	 * Called using admin/model/catalog/review/deleteReview/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function deleteReview(string &$route, array &$args, &$output): void {
		$this->load->model('catalog/review');

		$review_info = $this->model_catalog_review->getReview($args[0]);

		if ($review_info) {
			$task_data = [
				'code'   => 'review.' . $review_info['product_id'],
				'action' => 'task/catalog/review',
				'args'   => ['product_id' => $review_info['product_id']]
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);
		}
	}
}
