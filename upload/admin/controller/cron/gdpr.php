<?php
class ControllerCronGdpr extends Controller {
	public function index($cron_id, $code, $cycle, $date_added, $date_modified) {
		$this->load->model('customer/gdpr');
		$this->load->model('customer/customer');

		$results = $this->model_customer_gdpr->getExpires();

		foreach ($results as $result) {
			$this->model_customer_gdpr->deleteGdpr($result['customer_id']);
			$this->model_customer_customer->deleteCustomer($result['customer_id']);
		}
	}
}