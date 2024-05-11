<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Statistics
 *
 * @package Opencart\Admin\Controller\Event
 */
class Statistics extends \Opencart\System\Engine\Controller {
	/**
	 * Add Review
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 *
	 * admin/model/catalog/review/addReview/after
	 */
	public function addReview(string &$route, array &$args, &$output): void {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('review', 1);
	}

	/**
	 * Delete Review
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 *
	 * admin/model/catalog/review/deleteReview/after
	 */
	public function deleteReview(string &$route, array &$args, &$output): void {
		$this->load->model('report/statistics');

		$this->model_report_statistics->removeValue('review', 1);
	}

	/**
	 * Add Return
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 *
	 * admin/model/sale/returns/addReturn/after
	 */
	public function addReturn(string &$route, array &$args, &$output): void {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('returns', 1);
	}

	/**
	 * Delete Return
	 *
	 * @param string            $route
	 * @param array<int, mixed> $args
	 * @param mixed             $output
	 *
	 * @return void
	 *
	 * admin/model/sale/returns/deleteReturn/after
	 */
	public function deleteReturn(string &$route, array &$args, &$output): void {
		$this->load->model('report/statistics');

		$this->model_report_statistics->removeValue('returns', 1);
	}
}
