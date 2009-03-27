<?php
class ModelCheckoutCoupon extends Model {
	public function getCoupon($coupon) {
		$status = TRUE;
		
		$coupon = $this->db->query("SELECT * FROM coupon c LEFT JOIN coupon_description cd ON (c.coupon_id = cd.coupon_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "' AND c.code = '" . $this->db->escape($coupon) . "' AND c.date_start < NOW() AND c.date_end > NOW() AND c.status = '1'");
			
		if ($coupon->num_rows) {
			if ($coupon->row['total'] >= $this->cart->getSubTotal()) {
				$status = FALSE;
			}
		
			$coupon_redeem = $this->db->query("SELECT COUNT(*) AS total FROM coupon_redeem WHERE coupon_id = '" . (int)$coupon->row['coupon_id'] . "'");

			if ($coupon_redeem->row['total'] >= $coupon->row['uses_total']) {
				$status = FALSE;
			}
			
			$coupon_redeem = $this->db->query("SELECT COUNT(*) AS total FROM coupon_redeem WHERE coupon_id = '" . (int)$coupon->row['coupon_id'] . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
				
			if ($coupon_redeem->row['total'] >= $coupon->row['uses_customer']) {
				$status = FALSE;
			}
				
			$coupon_product_data = array();
				
			$coupon_product = $this->db->query("SELECT * FROM coupon_product WHERE coupon_id = '" . (int)$coupon->row['coupon_id'] . "'");

			foreach ($coupon_product->rows as $result) {
				$coupon_product_data[] = $result['product_id'];
			}
				
			if ($coupon_product_data) {
				$coupon_product = FALSE;
					
				foreach ($this->cart->getProducts() as $product) {
					if (in_array($product['product_id'], $coupon_product_data)) {
						$coupon_product = TRUE;
							
						break;
					}
				}
					
				if (!$coupon_product) {
					$status = FALSE;
				}
			}
		} else {
			$status = FALSE;
		}
		
		if ($status) {
			$coupon_data = array(
				'coupon_id'     => $coupon->row['coupon_id'],
				'code'          => $coupon->row['code'],
				'name'          => $coupon->row['name'],
				'type'          => $coupon->row['type'],
				'discount'      => $coupon->row['discount'],
				'shipping'      => $coupon->row['shipping'],
				'total'         => $coupon->row['total'],
				'product'       => $coupon_product_data,
				'date_start'    => $coupon->row['coupon_id'],
				'date_end'      => $coupon->row['coupon_id'],
				'uses_total'    => $coupon->row['coupon_id'],
				'uses_customer' => $coupon->row['coupon_id'],
				'status'        => $coupon->row['coupon_id'],
				'date_added'    => $coupon->row['coupon_id']
			);
			
			return $coupon_data;
		}
	}
	
	public function redeem($coupon, $order_id) {
		$coupon = $this->getCoupon($coupon);
		
		if ($coupon) {
			$this->db->query("INSERT coupon_redeem SET coupon_id = '" . (int)$coupon['coupon_id'] . "', order_id = '" . (int)$order_id . "', customer_id = '" . (int)$this->customer->getId() . "', date_added = NOW()");
		}
	}
}
?>