<?php
class ModelPaymentPPProIframe extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/pp_pro_iframe');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('pp_pro_iframe_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('pp_pro_iframe_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('pp_pro_iframe_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
		$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'pp_pro_iframe',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('pp_pro_iframe_sort_order')
			);
		}

		return $method_data;
	}

	public function addOrder($order_data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_iframe_order` SET `order_id` = '" . (int)$order_data['order_id'] . "', `date_added` = NOW(), `date_modified` = NOW(), `capture_status` = '" . $this->db->escape($order_data['capture_status']) . "', `currency_code` = '" . $this->db->escape($order_data['currency_code']) . "', `total` = '" . (float)$order_data['total'] . "', `authorization_id` = '" . $this->db->escape($order_data['authorization_id']) . "'");

		return $this->db->getLastId();
	}

	public function addTransaction($transaction_data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_iframe_order_transaction` SET `paypal_iframe_order_id` = '" . (int)$transaction_data['paypal_iframe_order_id'] . "', `transaction_id` = '" . $this->db->escape($transaction_data['transaction_id']) . "', `parent_transaction_id` = '" . $this->db->escape($transaction_data['parent_transaction_id']) . "', `date_added` = NOW(), `note` = '" . $this->db->escape($transaction_data['note']) . "', `msgsubid` = '" . $this->db->escape($transaction_data['msgsubid']) . "', `receipt_id` = '" . $this->db->escape($transaction_data['receipt_id']) . "', `payment_type` = '" . $this->db->escape($transaction_data['payment_type']) . "', `payment_status` = '" . $this->db->escape($transaction_data['payment_status']) . "', `pending_reason` = '" . $this->db->escape($transaction_data['pending_reason']) . "', `transaction_entity` = '" . $this->db->escape($transaction_data['transaction_entity']) . "', `amount` = '" . (float)$transaction_data['amount'] . "', `debug_data` = '" . $this->db->escape($transaction_data['debug_data']) . "'");
	}
}