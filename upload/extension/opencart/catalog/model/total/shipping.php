<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Shipping
 *
 * Can be called from $this->load->model('extension/opencart/total/shipping');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Shipping extends \Opencart\System\Engine\Model {
	/**
	 * Get Total
	 *
	 * @param array<int, array<string, mixed>> $totals
	 * @param array<int, float>                $taxes
	 * @param float                            $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
			$totals[] = [
				'extension'  => 'opencart',
				'code'       => 'shipping',
				'title'      => $this->session->data['shipping_method']['name'],
				'value'      => $this->session->data['shipping_method']['cost'],
				'sort_order' => (int)$this->config->get('total_shipping_sort_order')
			];

			if (isset($this->session->data['shipping_method']) && isset($this->session->data['shipping_method']['tax_class_id'])) {
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
