<?php
namespace Opencart\Catalog\Controller\Cron;
class Gdpr extends \Opencart\System\Engine\Controller {
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		$this->load->model('account/gdpr');
		$this->load->model('account/customer');

		$results = $this->model_account_gdpr->getExpires();

		foreach ($results as $result) {
			$this->model_account_gdpr->editStatus($result['gdpr_id'], 3);

			$customer_info = $this->model_account_customer->getCustomerByEmail($result['email']);

			if ($customer_info) {
				$this->model_account_customer->deleteCustomer($customer_info['customer_id']);
			}
		}
	}
}