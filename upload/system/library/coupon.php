<?php
final class Coupon {
	private $coupon_id;
	private $name;
	private $description;
	private $code;
	private $discount;
	private $prefix;
	private $shipping;
	private $status = FALSE;
	
	public function Coupon() {
		$this->cart = Registry::get('cart');
		$this->customer = Registry::get('customer');
		$this->db = Registry::get('db');
		$this->language = Registry::get('language');
		$this->session = Registry::get('session');
		
		if (isset($this->session->data['coupon_id'])) {
			$coupon = $this->db->query("SELECT * FROM coupon c LEFT JOIN coupon_description cd ON (c.coupon_id = cd.coupon_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "' AND c.coupon_id = '" . (int)$this->session->data['coupon_id'] . "' AND c.date_start < NOW() AND c.date_end > NOW() AND c.status = '1'");
			
			if ($coupon->num_rows) {
				$coupon_redeem = $this->db->query("SELECT COUNT(*) AS total FROM coupon_redeem WHERE coupon_id = '" . (int)$coupon->row['coupon_id'] . "'");

				$this->status = ($coupon->row['uses_customer'] > $coupon_redeem->row['total']);
				
				if ($this->status) {
					$coupon_redeem = $this->db->query("SELECT COUNT(*) AS total FROM coupon_redeem WHERE coupon_id = '" . (int)$coupon->row['coupon_id'] . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
				
					$this->status = ($coupon->row['uses_customer'] > $coupon_redeem->row['total']);
				}
			}
			
			if ($this->status) {
				$this->coupon_id = $coupon->row['coupon_id'];
				$this->name = $coupon->row['name'];
				$this->description = $coupon->row['description'];
				$this->code = $coupon->row['code'];
				$this->discount = $coupon->row['discount'];
				$this->prefix = $coupon->row['prefix'];
				$this->shipping = $coupon->row['shipping'];
			} else {
				unset($this->session->data['coupon']);
			}
		}
	}
	
	public function set($code) {
		$coupon = $this->db->query("SELECT * FROM coupon c LEFT JOIN coupon_description cd ON (c.coupon_id = cd.coupon_id) WHERE cd.language_id = '" . (int)$this->language->getId() . "' AND c.code = '" . $this->db->escape($code) . "' AND c.date_start < NOW() AND c.date_end > NOW() AND c.status = '1'");

		if ($coupon->num_rows) {
			$coupon_redeem = $this->db->query("SELECT COUNT(*) AS total FROM coupon_redeem WHERE coupon_id = '" . (int)$coupon->row['coupon_id'] . "'");

			$this->status = ($coupon->row['uses_customer'] > $coupon_redeem->row['total']);
				
			if ($this->status) {
				$coupon_redeem = $this->db->query("select COUNT(*) AS total FROM coupon_redeem WHERE coupon_id = '" . (int)$coupon->row['coupon_id'] . "' AND customer_id = '" . (int)$this->customer->getId() . "'");
			
				$this->status = ($coupon->row['uses_customer'] > $coupon_redeem->row['total']);
			}		
		}
		
		if ($this->status) {
			$this->session->data['coupon_id'] = $coupon->row['coupon_id'];
			
			$this->coupon_id = $coupon->row['coupon_id'];
			$this->name = $coupon->row['name'];
			$this->description = $coupon->row['description'];
			$this->code = $coupon->row['code'];
			$this->discount = $coupon->row['discount'];
			$this->prefix = $coupon->row['prefix'];
			$this->shipping = $coupon->row['shipping'];
			
			return TRUE;
		} else {
			return FALSE;
		}		
	}
			
	public function redeem($order_id) {
		if ($this->coupon_id) {
			$this->db->query("INSERT coupon_redeem SET coupon_id = '" . (int)$this->coupon_id . "', customer_id = '" . (int)$this->customer->getId() . "', order_id = '" . (int)$order_id . "', date_added = NOW()");
		}
	}

	public function getId() {
		return $this->coupon_id;
	}
		
	public function getName() {
		return $this->name;
	}
	
	public function getDescription() {
		return $this->description;
	}
		
	public function getCode() {
		return $this->code;
	}

	public function getDiscount($value) {
		if ($this->prefix) {
			if ($this->prefix == '%') {
				return ($value * $this->discount / 100);
			} elseif ($this->prefix == '-') {
				return $this->discount;
			}
		}
	}
		
	public function getShipping() {
		return $this->shipping;
	}
}
?>