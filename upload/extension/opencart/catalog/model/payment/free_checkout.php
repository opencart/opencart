<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Payment;
/**
 * Class Free Checkout
 *
 * Can be called from $this->load->model('extension/opencart/payment/free_checkout');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Payment
 */
class FreeCheckout extends \Opencart\System\Engine\Model {
	/**
	 * Get Methods
	 *
	 * @param array<string, mixed> $address array of data
	 *
	 * @return array<string, mixed>
	 */
	public function getMethods(array $address = []): array {
		$this->load->language('extension/opencart/payment/free_checkout');

		// Order Totals
		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Cart
		$this->load->model('checkout/cart');

		($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

		if ($this->currency->format($total, $this->config->get('config_currency'), false, false) <= 0.00) {
			$status = true;
		} elseif ($this->cart->hasSubscription()) {
			$status = false;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$option_data['free_checkout'] = [
				'code' => 'free_checkout.free_checkout',
				'name' => $this->language->get('heading_title')
			];

			$method_data = [
				'code'       => 'free_checkout',
				'name'       => $this->language->get('heading_title'),
				'option'     => $option_data,
				'sort_order' => $this->config->get('payment_free_checkout_sort_order')
			];
		}

		return $method_data;
	}
}
