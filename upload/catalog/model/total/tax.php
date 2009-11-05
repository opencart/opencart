<?php
class ModelTotalTax extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->config->get('tax_status')) {
			foreach ($taxes as $key => $value) {
				if ($value > 0) {
					$tax_classes = $this->tax->getDescription($key);
					
					foreach ($tax_classes as $tax_class) {
						$rate = $this->tax->getRate($key);
						
						$tax = $value * ($tax_class['rate'] / $rate);
						
						$total_data[] = array(
	    					'title'      => $tax_class['description'] . ':', 
	    					'text'       => $this->currency->format($tax),
	    					'value'      => $tax,
							'sort_order' => $this->config->get('tax_sort_order')
	    				);
			
						$total += $tax;
					}
				}
			}
		}
	}
}
?>