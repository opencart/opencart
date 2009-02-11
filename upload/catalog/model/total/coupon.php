<?php
class ModelTotalCoupon extends Model {
	public function getTotal() {
		$this->load->language('total/coupon');
		
		$total_data = array();
		
		if (($this->config->get('coupon_status')) && ($this->coupon->getId())) {
			if ($this->coupon->getDiscount($this->cart->getTotal()) > 0) {
      			$total_data[] = array(
        			'title'      => sprintf($this->language->get('coupon_title'), $this->coupon->getName()),
	    			'text'       => '-' . $this->currency->format($this->coupon->getDiscount($this->cart->getTotal())),
        			'value'      => -$this->coupon->getDiscount($this->cart->getTotal()),
					'sort_order' => $this->config->get('coupon_sort_order')
      			);
			}
						
			if (($this->coupon->getShipping()) && ($this->cart->hasShipping())) {
      			$total_data[] = array(
        			'title'      => $this->language->get('text_shipping'),
	    			'text'       => '-' . $this->currency->format($this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id'])),
        			'value'      => $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']),
      				'sort_order' => $this->config->get('coupon_sort_order')
				);
			}
    	}
	
    	return $total_data;
	}
}
?>