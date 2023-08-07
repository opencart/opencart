<?php
namespace Opencart\Admin\Controller\Event;
/**
 * Class Statistics
 *
 * @package Opencart\Admin\Controller\Event
 */
class Statistics extends \Opencart\System\Engine\Controller {
	// admin/model/catalog/review/addReview/after
	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function addReview(string &$route, array &$args, mixed &$output): void {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('review', 1);
	}

	// admin/model/catalog/review/deleteReview/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function deleteReview(string &$route, array &$args, mixed &$output): void {
		$this->load->model('report/statistics');

		$this->model_report_statistics->removeValue('review', 1);
	}

	// admin/model/sale/returns/addReturn/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function addReturn(string &$route, array &$args, mixed &$output): void {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('returns', 1);
	}

	// admin/model/sale/returns/deleteReturn/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function deleteReturn(string &$route, array &$args, mixed &$output): void {
		$this->load->model('report/statistics');

		$this->model_report_statistics->removeValue('returns', 1);
	}
}
