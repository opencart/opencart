<?php
namespace Opencart\Application\Model\Extension\Opencart\Total;
class Handling extends \Opencart\System\Engine\Model {
	public function getTotal(&$totals, &$taxes, &$total) {
		if (($this->cart->getSubTotal() > $this->config->get('total_handling_total')) && ($this->cart->getSubTotal() > 0)) {
			$this->load->language('extension/opencart/total/handling');

			$totals[] = [
				'code'       => 'handling',
				'title'      => $this->language->get('text_handling'),
				'value'      => $this->config->get('total_handling_fee'),
				'sort_order' => $this->config->get('total_handling_sort_order')
			];

			if ($this->config->get('total_handling_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('total_handling_fee'), $this->config->get('total_handling_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total += $this->config->get('total_handling_fee');
		}
	}
}