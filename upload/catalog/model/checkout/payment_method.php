<?php
namespace Opencart\Catalog\Model\Checkout;
class PaymentMethod extends \Opencart\System\Engine\Controller {
	public function getMethods(array $payment_address): array {
		$method_data = [];

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensionsByType('payment');

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$this->load->model('extension/' . $result['extension'] . '/payment/' . $result['code']);

				$payment_method = $this->{'model_extension_' . $result['extension'] . '_payment_' . $result['code']}->getMethod($payment_address);

				if ($payment_method) {
					$method_data[$result['code']] = $payment_method;
				}
			}
		}

		$sort_order = [];

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		// Stored payment methods
		$this->load->model('account/payment_method');

		$payment_methods = $this->model_account_payment_method->getPaymentMethods($this->customer->getId());

		foreach ($payment_methods as $payment_method) {
			$method_data[$result['code'] . '_' . $result['code']] = [
				'name' => $payment_method['name'],
				'code' => $payment_method['code']
			];
		}

		return $method_data;
	}
}