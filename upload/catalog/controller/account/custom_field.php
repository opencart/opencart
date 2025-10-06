<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Custom Field
 *
 * @package Opencart\Catalog\Controller\Account
 */
class CustomField extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		// Customer Group
		if (isset($this->request->get['customer_group_id']) && in_array((int)$this->request->get['customer_group_id'], (array)$this->config->get('config_customer_group_display'))) {
			$customer_group_id = (int)$this->request->get['customer_group_id'];
		} else {
			$customer_group_id = (int)$this->config->get('config_customer_group_id');
		}

		$this->load->model('account/custom_field');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->model_account_custom_field->getCustomFields($customer_group_id)));
	}
}
