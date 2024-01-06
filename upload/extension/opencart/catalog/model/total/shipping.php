<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Shipping
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Shipping extends \Opencart\System\Engine\Model {
	/**
	 * @param \Opencart\System\Engine\Counter $counter
	 *
	 * @return void
	 */
	public function getTotal(\Opencart\System\Engine\Counter $counter): void {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
			$counter->totals[] = [
				'extension'  => 'opencart',
				'code'       => 'shipping',
				'title'      => $this->session->data['shipping_method']['name'],
				'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => (int)$this->config->get('total_shipping_sort_order')
			];

			if (isset($this->session->data['shipping_method']['tax_class_id'])) {
				$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($counter->taxes[$tax_rate['tax_rate_id']])) {
						$counter->taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$counter->taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$counter->total += $this->session->data['shipping_method']['cost'];
		}
	}
}
