<?php
class ModelTotalHandling extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->config->get('handling_status') && ($this->cart->getSubTotal() < $this->config->get('handling_total'))) {
			$this->load->language('total/handling');
		 	
			$this->load->model('localisation/currency');
			
			$total_data[] = array( 
        		'title'      => $this->language->get('text_handling'),
        		'text'       => $this->currency->format($this->config->get('handling_fee')),
        		'value'      => $this->config->get('handling_fee'),
				'sort_order' => $this->config->get('handling_sort_order')
			);

			if ($this->config->get('handling_tax_class_id')) {
				if (!isset($taxes[$this->config->get('handling_tax_class_id')])) {
					$taxes[$this->config->get('handling_tax_class_id')] = $this->config->get('handling_fee') / 100 * $this->tax->getRate($this->config->get('handling_tax_class_id'));
				} else {
					$taxes[$this->config->get('handling_tax_class_id')] += $this->config->get('handling_fee') / 100 * $this->tax->getRate($this->config->get('handling_tax_class_id'));
				}
			}
			
			$total += $this->config->get('handling_fee');
		}
	}
}
?>