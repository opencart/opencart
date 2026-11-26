<?php
namespace Opencart\Catalog\Controller\Cron;
/**
 * Class Gdpr
 *
 * @package Opencart\Catalog\Controller\Cron
 */
class Gdpr extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param int    $cron_id
	 * @param string $code
	 * @param string $cycle
	 * @param string $date_added
	 * @param string $date_modified
	 *
	 * @return void
	 */
	public function index(int $cron_id, string $code, string $cycle, string $date_added, string $date_modified): void {
		// GDPR
		$this->load->model('account/gdpr');

		// Customer
		$this->load->model('account/customer');

		if ($this->config->get('config_gdpr_remove_immediate')) {
			// Accounts are deleted when the request is approved. Finish the requests
			// that still have orders or returns inside the GDPR limit.
			foreach ($this->model_account_gdpr->getRemovals() as $result) {
				$customer_info = $this->model_account_customer->getCustomerByEmail($result['email']);

				$customer_id = $customer_info ? (int)$customer_info['customer_id'] : 0;

				if ($customer_id) {
					$this->model_account_customer->deleteCustomer($customer_id);
				}

				$this->model_account_gdpr->eraseCustomerData($result['email'], $customer_id);

				if (!$this->model_account_gdpr->getTotalRetainedRecords($result['email'])) {
					$this->model_account_gdpr->editStatus($result['gdpr_id'], 3);
				}
			}

			return;
		}

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
