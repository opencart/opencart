<?php
namespace Opencart\Application\Model\Extension\Opencart\Payment;
class FreeCheckout extends \Opencart\System\Engine\Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/free_checkout');

		if ($total <= 0.00) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$method_data = [
				'code'       => 'free_checkout',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('payment_free_checkout_sort_order')
			];
		}

		return $method_data;
	}
}