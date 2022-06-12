<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
class Shipping extends \Opencart\System\Engine\Model {
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
			$shipping = explode('.', $this->session->data['shipping_method']);

			if (isset($shipping[0]) && isset($shipping[1]) && isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$shipping_method_info = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

				$totals[] = [
					'extension'  => 'opencart',
					'code'       => 'shipping',
					'title'      => $shipping_method_info['title'],
					'value'      => $shipping_method_info['cost'],
					'sort_order' => (int)$this->config->get('total_shipping_sort_order')
				];

				if (isset($this->session->data['shipping_method']['tax_class_id'])) {
					$tax_rates = $this->tax->getRates($shipping_method_info['cost'], $shipping_method_info['tax_class_id']);

					foreach ($tax_rates as $tax_rate) {
						if (!isset($taxes[$tax_rate['tax_rate_id']])) {
							$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
						} else {
							$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
						}
					}
				}

				$total += $shipping_method_info['cost'];
			}
		}
	}
}
