<?php
class ModelTotalHandling extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (($this->cart->getSubTotal() < $this->config->get('handling_total')) && ($this->cart->getSubTotal() > 0)) {
			$this->language->load('total/handling');

			$total_data[] = array( 
				'code'       => 'handling',
				'title'      => $this->language->get('text_handling'),
				'text'       => $this->currency->format($this->config->get('handling_fee')),
				'value'      => $this->config->get('handling_fee'),
				'sort_order' => $this->config->get('handling_sort_order')
			);

			if ($this->config->get('handling_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('handling_fee'), $this->config->get('handling_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}

			$total += $this->config->get('handling_fee');
		}
	}
}
?>