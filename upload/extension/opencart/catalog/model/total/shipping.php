<?php
namespace Opencart\Application\Model\Extension\Opencart\Total;
class Shipping extends \Opencart\System\Engine\Model {
	public function getTotal(&$totals, &$taxes, &$total) {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
			$totals[] = [
				'code'       => 'shipping',
				'title'      => $this->session->data['shipping_method']['title'],
				'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => $this->config->get('total_shipping_sort_order')
			];

			if ($this->session->data['shipping_method']['tax_class_id']) {
				$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total += $this->session->data['shipping_method']['cost'];
		}
	}
}