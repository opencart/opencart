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
	 * Trigger admin/model/catalog/review/addReview/after
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function addReview(string &$route, array &$args, &$output): void {
		$this->load->model('setting/task');

		$this->load->model('catalog/product');
		$this->load->model('catalog/review');

		$store_ids = $this->model_catalog_product->getStores($args[1]['product_id']);

		foreach ($store_ids as $store_id) {
			$task_data = [
				'code'   => 'review.info.' . $store_id . '.' . $args[1]['product_id'],
				'action' => 'task/catalog/review.info',
				'args'   => [
					'product_id' => $args[1]['product_id'],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);

			$task_data = [
				'code'   => 'review.info.' . $store_id . '.' . $args[1]['product_id'],
				'action' => 'task/catalog/review.info',
				'args'   => [
					'product_id' => $args[1]['product_id'],
					'store_id'   => $store_id
				]
			];

			$this->model_setting_task->addTask($task_data);
		}
	}

	/*
	 * Edit Review
	 *
	 * Adds task to generate new review data.
	 *
	 * Trigger admin/model/catalog/review/editReview/before
	 *
	 * @param string                $route
	 * @param array<string, string> $args
	 * @param array<string, string> $output
	 *
	 * @return void
	 */
	public function editReview(string &$route, array &$args): void {
		$this->load->model('catalog/review');

		$review_info = $this->model_catalog_review->getReview($args[0]);

		$this->load->model('catalog/product');

		$store_ids = $this->model_catalog_product->getStores($args[0]);

		foreach ($store_ids as $store_id) {


			if ($review_info) {
				$task_data = [
					'code'   => 'review.info.' . $args[1]['product_id'],
					'action' => 'task/catalog/review.info',
					'args'   => ['product_id' => $args[1]['product_id']]
				];

				$this->load->model('setting/task');

				$this->model_setting_task->addTask($task_data);

				$product_ids = array_unique([$args[1]['product_id'], $review_info['product_id']]);

				foreach ($product_ids as $product_id) {
					$task_data = [
						'code'   => 'review.info.' . $product_id,
						'action' => 'task/catalog/review.info',
						'args'   => ['review_id' => $product_id]
					];

					$this->model_setting_task->addTask($task_data);
				}



			}
		}
	}

	/*
	 * Delete Review
	 *
	 * Adds task to generate delete review data.
	 *
	 * Trigger admin/model/catalog/review/deleteReview/before
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
