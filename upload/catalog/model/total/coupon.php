<?php
class ModelTotalCoupon extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['coupon']) && $this->config->get('coupon_status')) {
			$this->load->model('checkout/coupon');
			 
			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
			
			if ($coupon) {
				$discount_total = 0;
				
				if (!$coupon['product']) {
					$coupon_total = $this->cart->getSubTotal();
				} else {
					$coupon_total = 0;
				
					foreach ($this->cart->getProducts() as $product) {
						if (in_array($product['product_id'], $coupon['product'])) {
							$coupon_total += $product['total'];
						}
					}					
				}
				
				if ($coupon['type'] == 'F') {
					$coupon['discount'] = min($coupon['discount'], $coupon_total);
				}
				
				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;
					
					if (!$coupon['product']) {
						$status = TRUE;
					} else {
						if (in_array($product['product_id'], $coupon['product'])) {
							$status = TRUE;
						} else {
							$status = FALSE;
						}
					}
					
					if ($status) {
						if ($coupon['type'] == 'F') {
							$discount = $coupon['discount'] * ($product['total'] / $coupon_total);
						} elseif ($coupon['type'] == 'P') {
							$discount = $product['total'] / 100 * $coupon['discount'];
						}
				
						if ($product['tax_class_id']) {
							$taxes[$product['tax_class_id']] -= ($product['total'] / 100 * $this->tax->getRate($product['tax_class_id'])) - (($product['total'] - $discount) / 100 * $this->tax->getRate($product['tax_class_id']));
						}
					}
					
					$discount_total += $discount;
				}
				
				if ($coupon['shipping'] && isset($this->session->data['shipping_method'])) {
					if (isset($this->session->data['shipping_method']['tax_class_id']) && $this->session->data['shipping_method']['tax_class_id']) {
						$taxes[$this->session->data['shipping_method']['tax_class_id']] -= $this->session->data['shipping_method']['cost'] / 100 * $this->tax->getRate($this->session->data['shipping_method']['tax_class_id']);
					}
					
					$discount_total += $this->session->data['shipping_method']['cost'];				
				}				
      			
				$total_data[] = array(
        			'title'      => $coupon['name'] . ':',
	    			'text'       => '-' . $this->currency->format($discount_total),
        			'value'      => - $discount_total,
					'sort_order' => $this->config->get('coupon_sort_order')
      			);

				$total -= $discount_total;
			} 
		}
	}
}
?>