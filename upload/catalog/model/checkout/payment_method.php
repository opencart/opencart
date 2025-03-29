<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class Payment Method
 *
 * Can be called using $this->load->model('checkout/payment_method');
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class PaymentMethod extends \Opencart\System\Engine\Model {
	/**
	 * Get Methods
	 *
	 * @param array<string, mixed> $payment_address array of data
	 *
	 * @return array<string, mixed>
	 */
	public function getMethods(array $payment_address = []): array {
		$method_data = [];

		// Extensions
		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensionsByType('payment');

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$this->load->model('extension/' . $result['extension'] . '/payment/' . $result['code']);

				$key = 'model_extension_' . $result['extension'] . '_payment_' . $result['code'];

				if ($this->{$key}->getMethods) {
					$payment_methods = $this->{$key}->getMethods($payment_address);

					if ($payment_methods) {
						$method_data[$result['code']] = $payment_methods;
					}
				}
			}
		}

		$sort_order = [];

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		return $method_data;
	}
}
