<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class LowOrderFee
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class LowOrderFee extends \Opencart\System\Engine\Model {
	/**
	 * @param \Opencart\System\Engine\Counter $counter
	 *
	 * @return void
	 */
	public function getTotal(\Opencart\System\Engine\Counter $counter): void {
		if ($this->cart->getSubTotal() && ($this->cart->getSubTotal() < (float)$this->config->get('total_low_order_fee_total'))) {
			$this->load->language('extension/opencart/total/low_order_fee');

			$counter->totals[] = [
				'extension'  => 'opencart',
				'code'       => 'low_order_fee',
				'title'      => $this->language->get('text_low_order_fee'),
				'value'      => (float)$this->config->get('total_low_order_fee_fee'),
				'sort_order' => (int)$this->config->get('total_low_order_fee_sort_order')
			];

			if ($this->config->get('total_low_order_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('total_low_order_fee_fee'), $this->config->get('total_low_order_fee_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($counter->taxes[$tax_rate['tax_rate_id']])) {
						$counter->taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$counter->taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$counter->total += $this->config->get('total_low_order_fee_fee');
		}
	}
}
