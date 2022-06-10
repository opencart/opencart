<?php
namespace Opencart\Catalog\Controller\Account;
class CustomField extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$json = [];

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && in_array((int)$this->request->get['customer_group_id'], (array)$this->config->get('config_customer_group_display'))) {
			$customer_group_id = (int)$this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = [
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}