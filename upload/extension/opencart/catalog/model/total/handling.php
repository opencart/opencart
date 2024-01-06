<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Handling
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Handling extends \Opencart\System\Engine\Model {
	/**
	 * @param \Opencart\System\Engine\Counter $counter
	 *
	 * @return void
	 */
	public function getTotal(\Opencart\System\Engine\Counter $counter): void {
		if (($this->cart->getSubTotal() > (float)$this->config->get('total_handling_total')) && ($this->cart->getSubTotal() > 0)) {
			$this->load->language('extension/opencart/total/handling');

			$counter->totals[] = [
				'extension'  => 'opencart',
				'code'       => 'handling',
				'title'      => $this->language->get('text_handling'),
				'value'      => (float)$this->config->get('total_handling_fee'),
				'sort_order' => (int)$this->config->get('total_handling_sort_order')
			];

			if ($this->config->get('total_handling_tax_class_id')) {
				$tax_rates = $this->tax->getRates((float)$this->config->get('total_handling_fee'), (int)$this->config->get('total_handling_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($counter->taxes[$tax_rate['tax_rate_id']])) {
						$counter->taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$counter->taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$counter->total += (float)$this->config->get('total_handling_fee');
		}
	}
}
