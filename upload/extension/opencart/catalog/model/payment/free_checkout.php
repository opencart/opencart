<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Payment;
class FreeCheckout extends \Opencart\System\Engine\Model {
	public function getMethod(array $address): array {
		$this->load->language('extension/opencart/payment/free_checkout');

		$total = $this->cart->getTotal();

		if (!empty($this->session->data['vouchers'])) {
			$amounts = array_column($this->session->data['vouchers'], 'amount');
		} else {
			$amounts = [];
		}

		$total = $total + array_sum($amounts);

		if ((float)$total <= 0.00) {
			$status = true;
		} elseif ($this->cart->hasSubscription()) {
			$status = false;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$method_data = [
				'code'       => 'free_checkout',
				'title'      => $this->language->get('heading_title'),
				'sort_order' => $this->config->get('payment_free_checkout_sort_order')
			];
		}

		return $method_data;
	}
}