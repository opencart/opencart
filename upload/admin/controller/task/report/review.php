<?php
namespace Opencart\Admin\Controller\Task\Report;
/**
 * Class Review
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

		$awaiting_total = $this->model_catalog_review->getTotalReviewsAwaitingApproval();

		$this->load->model('report/statistics');

		$this->model_report_statistics->editValue('review', $awaiting_total);

		return ['success' => $this->language->get('text_success')];
	}
}
