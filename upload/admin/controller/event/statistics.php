<?php
namespace Opencart\Application\Controller\Event;
class Statistics extends \Opencart\System\Engine\Controller {
	// admin/model/catalog/review/addReview/after
	public function addReview(&$route, &$args, &$output) {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('review', 1);
	}

	// admin/model/catalog/review/deleteReview/after
	public function deleteReview(&$route, &$args, &$output) {
		$this->load->model('report/statistics');

		$this->model_report_statistics->removeValue('review', 1);
	}

	// admin/model/sale/return/addReturn/after
	public function addReturn(&$route, &$args, &$output) {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('return', 1);
	}

	// admin/model/sale/return/deleteReturn/after
	public function deleteReturn(&$route, &$args, &$output) {
		$this->load->model('report/statistics');

		$this->model_report_statistics->removeValue('return', 1);
	}
}