<?php
namespace Opencart\Application\Controller\Cron;
class Gdpr extends \Opencart\System\Engine\Controller {
	public function index($cron_id, $code, $cycle, $date_added, $date_modified) {
		$this->load->model('customer/gdpr');
		$this->load->model('customer/customer');

		$results = $this->model_customer_gdpr->getExpires();

		foreach ($results as $result) {
			$this->model_customer_gdpr->editStatus($result['gdpr_id'], 3);

			$customer_info = $this->model_customer_customer->getCustomerByEmail($result['email']);

			if ($customer_info) {
				$this->model_customer_customer->deleteCustomer($customer_info['customer_id']);
			}
		}
	}
}