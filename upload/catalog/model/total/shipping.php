<?php
class ModelTotalShipping extends Model {  
  	public function getTotal() {
		$this->load->language('total/shipping');
		
		$total_data = array();
		
		if (($this->config->get('shipping_status')) && ($this->cart->hasShipping())) { 
			$total_data[] = array(
        		'title'        => $this->session->data['shipping_method']['title'] . ':',
        		'text'         => $this->currency->format($this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'], $this->config->get('config_tax'))),
        		'value'        => $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'], $this->config->get('config_tax')),
      			'tax'          => 0,
				'tax_class_id' => $this->session->data['shipping_method']['tax_class_id'],
				'sort_order'   => $this->config->get('shipping_sort_order')
			);
    	}
    
		return $total_data;
  	}
}
?>