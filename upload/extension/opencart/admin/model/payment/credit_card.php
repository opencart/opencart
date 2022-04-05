<?php
namespace Opencart\Admin\Model\Extension\Opencart\Payment;
class CreditCard extends \Opencart\System\Engine\Model {
	public function charge(int $customer_id, int $customer_payment_id, float $amount): int {
		$this->load->language('extension/opencart/payment/credit_card');

		$json = [];

		if (!$json) {



		}

		return $this->config->get('config_subscription_active_status_id');
	}
}
