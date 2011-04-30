<?php
class ModelCheckoutVoucher extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if (isset($this->session->data['voucher'])) {
			$this->load->language('total/voucher');
			
			$this->load->model('checkout/voucher');
			 
			$voucher_info = $this->model_checkout_voucher->getVoucher($this->session->data['voucher']);
			
			if ($voucher_info) {
				
				if ($voucher_info['type'] == 'F') {
					$voucher_info['discount'] = min($voucher_info['discount'], $sub_total);
				}
				
				foreach ($this->cart->getProducts() as $product) {
					$discount = 0;
					
					if (!$voucher_info['product']) {
						$status = true;
					} else {
						if (in_array($product['product_id'], $voucher_info['product'])) {
							$status = true;
						} else {
							$status = false;
						}
					}
					
					if ($status) {
						if ($voucher_info['type'] == 'F') {
							$discount = $voucher_info['discount'] * ($product['total'] / $sub_total);
						} elseif ($voucher_info['type'] == 'P') {
							$discount = $product['total'] / 100 * $voucher_info['discount'];
						}
				
						if ($product['tax_class_id']) {
							$taxes[$product['tax_class_id']] -= ($product['total'] / 100 * $this->tax->getRate($product['tax_class_id'])) - (($product['total'] - $discount) / 100 * $this->tax->getRate($product['tax_class_id']));
						}
					}
					
					$discount_total += $discount;
				}
				
				if ($voucher_info['shipping'] && isset($this->session->data['shipping_method'])) {
					if (isset($this->session->data['shipping_method']['tax_class_id']) && $this->session->data['shipping_method']['tax_class_id']) {
						$taxes[$this->session->data['shipping_method']['tax_class_id']] -= $this->session->data['shipping_method']['cost'] / 100 * $this->tax->getRate($this->session->data['shipping_method']['tax_class_id']);
					}
					
					$discount_total += $this->session->data['shipping_method']['cost'];				
				}				
      			
				$total_data[] = array(
					'code'       => 'voucher',
        			'title'      => sprintf($this->language->get('text_voucher'), $this->session->data['voucher']),
	    			'text'       => $this->currency->format(-$discount_total),
        			'value'      => -$discount_total,
					'sort_order' => $this->config->get('voucher_sort_order')
      			);

				$total -= $discount_total;
			} 
		}
	}
	
	public function confirm($order_info, $order_total) {
		$code = '';
		
		$start = strpos($order_total['title'], '(') + 1;
		$end = strrpos($order_total['title'], ')');
		
		if ($start && $end) {  
			$code = substr($order_total['title'], $start, $end - $start);
		}	
		
		$this->load->model('checkout/voucher');
		
		$voucher_info = $this->model_checkout_voucher->getVoucher($code);
		
		if ($voucher_info) {
			$this->model_checkout_voucher->redeem($voucher_info['voucher_id'], $order_info['order_id'], $order_total['value']);	
		}						
	}	
}
?>