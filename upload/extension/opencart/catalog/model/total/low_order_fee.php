<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Low Order Fee
 *
 * Can be called from $this->load->model('extension/opencart/total/low_order_fee');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class LowOrderFee extends \Opencart\System\Engine\Model {
	/**
	 * Get Total
	 *
	 * @param array<int, array<string, mixed>> $totals
	 * @param  array<int, float>               &$taxes
	 * @param  float                           &$total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if ($this->cart->getSubTotal() && ($this->cart->getSubTotal() < (float)$this->config->get('total_low_order_fee_total'))) {
			$this->load->language('extension/opencart/total/low_order_fee');

			$totals[] = [
				'extension'  => 'opencart',
				'code'       => 'low_order_fee',
				'title'      => $this->language->get('text_low_order_fee'),
				'value'      => (float)$this->config->get('total_low_order_fee_fee'),
				'sort_order' => (int)$this->config->get('total_low_order_fee_sort_order')
			];

			if ($this->config->get('total_low_order_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('total_low_order_fee_fee'), $this->config->get('total_low_order_fee_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[(int)$tax_rate['tax_rate_id']] = (float)$tax_rate['amount'];
					} else {
						$taxes[(int)$tax_rate['tax_rate_id']] += (float)$tax_rate['amount'];
					}
				}
			}

			$total += $this->config->get('total_low_order_fee_fee');
		}
	}
}
