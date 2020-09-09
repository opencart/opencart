<?php
namespace Opencart\Application\Model\Extension\Opencart\Total;
class LowOrderFee extends \Opencart\System\Engine\Model {
	public function getTotal(&$totals, &$taxes, &$total) {
		if ($this->cart->getSubTotal() && ($this->cart->getSubTotal() < $this->config->get('total_low_order_fee_total'))) {
			$this->load->language('extension/opencart/total/low_order_fee');

			$totals[] = [
				'code'       => 'low_order_fee',
				'title'      => $this->language->get('text_low_order_fee'),
				'value'      => $this->config->get('total_low_order_fee_fee'),
				'sort_order' => $this->config->get('total_low_order_fee_sort_order')
			];

			if ($this->config->get('total_low_order_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('total_low_order_fee_fee'), $this->config->get('total_low_order_fee_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total += $this->config->get('total_low_order_fee_fee');
		}
	}
}