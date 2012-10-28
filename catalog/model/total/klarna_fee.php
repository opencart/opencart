<?php
class ModelTotalKlarnaFee extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (($this->cart->getSubTotal() < $this->config->get('klarna_fee_total')) && ($this->cart->getSubTotal() > 0) && isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'klarna_invoice' || $this->session->data['payment_method']['code'] == 'klarna_pp') {
			$this->load->language('total/klarna_fee');
		 	
			$total_data[] = array( 
				'code'       => 'klarna_fee',
        		'title'      => $this->language->get('text_klarna_fee'),
        		'text'       => $this->currency->format($this->config->get('klarna_fee_fee')),
        		'value'      => $this->config->get('klarna_fee_fee'),
				'sort_order' => $this->config->get('klarna_fee_sort_order')
			);

			if ($this->config->get('klarna_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('klarna_fee_fee'), $this->config->get('klarna_fee_tax_class_id'));
				
				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
					}
				}
			}
			
			$total += $this->config->get('klarna_fee_fee');
		}
	}
}
?>