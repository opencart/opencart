<?php
class ModelCheckoutCoupon extends Model {
	public function getCoupon($coupon) {
		$status = TRUE;
		
		$coupon_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon c LEFT JOIN " . DB_PREFIX . "coupon_description cd ON (c.coupon_id = cd.coupon_id) WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.code = '" . $this->db->escape($coupon) . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) AND c.status = '1'");
			
		if ($coupon_query->num_rows) {
			if ($coupon_query->row['total'] >= $this->cart->getSubTotal()) {
				$status = FALSE;
			}
		
			$coupon_redeem_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			if ($coupon_query->row['uses_total'] > 0 && ($coupon_redeem_query->row['total'] >= $coupon_query->row['uses_total'])) {
				$status = FALSE;
			}
			
			if ($coupon_query->row['logged'] && !$this->customer->getId()) {
				$status = FALSE;
			}
			
			if ($this->customer->getId()) {
				$coupon_redeem_query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0' AND coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
				
				if ($coupon_query->row['uses_customer'] > 0 && ($coupon_redeem_query->row['total'] >= $coupon_query->row['uses_customer'])) {
					$status = FALSE;
				}
			}
			
			$coupon_product_data = array();
				
			$coupon_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "coupon_product WHERE coupon_id = '" . (int)$coupon_query->row['coupon_id'] . "'");

			foreach ($coupon_product_query->rows as $result) {
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
				'coupon_id'     => $coupon_query->row['coupon_id'],
				'code'          => $coupon_query->row['code'],
				'name'          => $coupon_query->row['name'],
				'type'          => $coupon_query->row['type'],
				'discount'      => $coupon_query->row['discount'],
				'shipping'      => $coupon_query->row['shipping'],
				'total'         => $coupon_query->row['total'],
				'product'       => $coupon_product_data,
				'date_start'    => $coupon_query->row['date_start'],
				'date_end'      => $coupon_query->row['date_end'],
				'uses_total'    => $coupon_query->row['uses_total'],
				'uses_customer' => $coupon_query->row['uses_customer'],
				'status'        => $coupon_query->row['status'],
				'date_added'    => $coupon_query->row['date_added']
			);
			
			return $coupon_data;
		}
	}
	
	public function redeem($coupon) {
		
	}
}
?>