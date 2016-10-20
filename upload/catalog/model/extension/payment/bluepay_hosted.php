<?php

class ModelExtensionPaymentBluePayHosted extends Model {

	public function getMethod($address, $total) {
		$this->load->language('extension/payment/bluepay_hosted');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE geo_zone_id = '" . (int)$this->config->get('bluepay_hosted_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('bluepay_hosted_total') > 0 && $this->config->get('bluepay_hosted_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('bluepay_hosted_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code' => 'bluepay_hosted',
				'title' => $this->language->get('text_title'),
				'terms' => '',
				'sort_order' => $this->config->get('bluepay_hosted_sort_order')
			);
		}

		return $method_data;
	}

	public function addOrder($order_info, $response_data) {
		if ($this->config->get('bluepay_hosted_transaction') == 'SALE') {
			$release_status = 1;
		} else {
			$release_status = null;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "bluepay_hosted_order` SET `order_id` = '" . (int)$order_info['order_id'] . "', `transaction_id` = '" . $this->db->escape($response_data['RRNO']) . "', `date_added` = now(), `date_modified` = now(), `release_status` = '" . (int)$release_status . "', `currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', `total` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");

		return $this->db->getLastId();
	}

	public function addTransaction($bluepay_hosted_order_id, $type, $order_info) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "bluepay_hosted_order_transaction` SET `bluepay_hosted_order_id` = '" . (int)$bluepay_hosted_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");
	}

	public function logger($message) {
		if ($this->config->get('bluepay_hosted_debug') == 1) {
			$log = new Log('bluepay_hosted.log');
			$log->write($message);
		}
	}

}
