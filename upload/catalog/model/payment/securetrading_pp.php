<?php
class ModelPaymentSecureTradingPp extends Model {
	
	public function getMethod($address, $total) {
		$this->load->language('payment/securetrading_pp');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('securetrading_pp_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('securetrading_pp_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('securetrading_pp_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'securetrading_pp',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('securetrading_pp_sort_order')
			);
		}

		return $method_data;
	}
	
	public function getOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "securetrading_pp_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");		

		return $qry->row;
	}
	
	public function editOrder($order_id, $order) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET shipping_firstname = '" . $this->db->escape($order['shipping_firstname']) ."', shipping_lastname = '" . $this->db->escape($order['shipping_lastname']) . "', shipping_address_1 = '" . $this->db->escape($order['shipping_address_1']) . "', shipping_address_2 = '" . $this->db->escape($order['shipping_address_2']) . "', shipping_city = '" . $this->db->escape($order['shipping_city']) . "', shipping_zone = '" . $this->db->escape($order['shipping_zone']) ."', shipping_zone_id = " . (int) $order['shipping_zone_id'] .  ", shipping_country = '" . $this->db->escape($order['shipping_country']) . "', shipping_country_id = " . (int) $order['shipping_country_id'] . ", shipping_postcode = '" . $this->db->escape($order['shipping_postcode']) . "',  payment_firstname = '" . $this->db->escape($order['payment_firstname']) ."', payment_lastname = '" . $this->db->escape($order['payment_lastname']) . "', payment_address_1 = '" . $this->db->escape($order['payment_address_1']) . "', payment_address_2 = '" . $this->db->escape($order['payment_address_2']) . "', payment_city = '" . $this->db->escape($order['payment_city']) . "', payment_zone = '" . $this->db->escape($order['payment_zone']) ."', payment_zone_id = " . (int) $order['payment_zone_id'] .  ", payment_country = '" . $this->db->escape($order['payment_country']) . "', payment_country_id = " . (int) $order['payment_country_id'] . ", payment_postcode = '" . $this->db->escape($order['payment_postcode']) . "' WHERE order_id = " . (int) $order_id);
	}
	
	public function addReference($order_id, $reference) {
		$this->db->query("REPLACE INTO " . DB_PREFIX . "securetrading_pp_order SET order_id = " . (int) $order_id . ", transaction_reference = '" . $this->db->escape($reference) . "',  `created` = now()");
	}
	
	public function confirmOrder($order_id, $order_status_id, $comment = '', $notify = false) {
		
		$this->logger('confirmOrder');
		
		$this->load->model('checkout/order');
		
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = 0 WHERE order_id = "  . (int) $order_id);

		$this->model_checkout_order->confirm($order_id, $order_status_id, $comment, $notify);

		$order_info = $this->model_checkout_order->getOrder($order_id);

		$securetrading_pp_order = $this->getOrder($order_id);

		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
		
		switch($this->config->get('securetrading_pp_settle_status')){
			case 0:
				$trans_type = 'auth';
				break;
			case 1:
				$trans_type = 'auth';
				break;
			case 2:
				$trans_type = 'suspended';
				break;
			case 100:
				$trans_type = 'payment';
				break;
			default :
				$trans_type = 'default';
		}
		
		$this->db->query("UPDATE `" . DB_PREFIX . "securetrading_pp_order` SET `settle_type`='" . $this->config->get('securetrading_pp_settle_status') . "', `modified` = now(), `currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', `total` = '" . $amount . "' WHERE order_id = " . (int)$order_info['order_id']);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "securetrading_pp_order_transaction` SET `securetrading_pp_order_id` = '" . (int)$securetrading_pp_order['securetrading_pp_order_id'] . "', `amount` = '" . $amount . "', type = '" . $trans_type . "',  `created` = now()");
	
	}
	
	public function updateOrder($order_id, $order_status_id, $comment = '', $notify = false) {
		$this->load->model('checkout/order');
		
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = " . (int) $order_status_id . " WHERE order_id = "  . (int) $order_id);
		
		$this->model_checkout_order->update($order_id, $order_status_id, $comment, $notify);
	}
	
	public function getCountry($iso_code_2) {
		return $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE LOWER(iso_code_2) = '" . $this->db->escape($iso_code_2) . "'")->row;
	}
	
	public function logger($message) {
			$log = new Log('secure.log');
			$log->write($message);
	}
}