<?php
namespace Opencart\Admin\Controller\Task\Report;
/**
 * Class Review
 *
 * Calculates the total number of awaiting reviews and stores the information in the statics table.
 *
 * @package Opencart\Admin\Controller\Report
 */
class Review extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array
	 */
	public function index(array $args = []): array {
		$this->load->language('task/report/review');

		$this->load->model('catalog/review');

		$this->load->model('report/statistics');

		$this->model_report_statistics->editValue('review', $this->model_catalog_review->getTotalReviewsAwaitingApproval());

		return ['success' => $this->language->get('text_success')];
	}
}
