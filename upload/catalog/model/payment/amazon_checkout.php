<?php
class ModelPaymentAmazonCheckout extends Model {
	public function getCountry($iso2) {
		return $this->db->query("SELECT `country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format` FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape(strtoupper($iso2)) . "' AND `status` = 1 LIMIT 1")->row;
	}

	public function getZone($name, $country_id) {
		return $this->db->query("SELECT `zone_id`, `code` FROM `" . DB_PREFIX . "zone` WHERE (LOWER(`name`) LIKE '" . $this->db->escape(strtolower($name)) . "' OR `code` LIKE '" . $this->db->escape(strtolower($name)) . "') AND `country_id` = " . (int)$country_id . " LIMIT 1")->row;
	}

	public function isAmazonOrder($order_id) {

		if ($this->config->get('amazon_checkout_status')) {
			$status = $this->db->query("SELECT COUNT(*) AS `count` FROM " . DB_PREFIX . "order_amazon WHERE order_id =  " . (int)$order_id)->row['count'] == 1;
		} else {
			$status = false;
		}

		return $status;
	}

	public function setOrderShipping($order_id, $has_free_shipping) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_amazon` (order_id, free_shipping) VALUES (" . (int)$order_id . ", " . (int)$has_free_shipping . ")");
	}

	public function hasFreeShipping($order_id) {
		return $this->db->query("SELECT `free_shipping` FROM `" . DB_PREFIX . "order_amazon` WHERE `order_id` = " . (int)$order_id)->row['free_shipping'] == '1';
	}

	public function getShippingPrice($order_id) {
		return $this->db->query("SELECT `value` + IF(`tax` IS NULL, 0, `tax`) AS 'price' FROM `" . DB_PREFIX . "order_total` `ot` LEFT JOIN `" . DB_PREFIX . "order_total_tax` `ott` USING(`order_total_id`) WHERE `ot`.`code` = 'shipping' AND `order_id` = " . (int)$order_id)->row['price'];
	}

	public function getAdditionalCharges($order_id) {
		return $this->db->query("SELECT `ot`.`title`, `ot`.`order_total_id`, `value` + IF(`tax` IS NULL, 0, `tax`) AS 'price' FROM `" . DB_PREFIX . "order_total` `ot` LEFT JOIN `" . DB_PREFIX . "order_total_tax` `ott` USING(`order_total_id`)  WHERE `ott`.`code` NOT IN ('shipping', 'total', 'sub_total', 'tax') AND `value` > 0 AND `order_id` = " . (int)$order_id)->rows;
	}

	public function addAmazonOrderId($order_id, $amazon_order_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order_amazon` SET `amazon_order_id` = '" . $this->db->escape($amazon_order_id) . "' WHERE `order_id` = " . (int)$order_id);
	}

	public function addTaxesForTotals($order_id, $totals) {
		foreach ($totals as $total) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total_tax` (`order_total_id`, `code`, `tax`) SELECT `order_total_id`, `code`, " . (double)$total['cba_tax'] . " FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = " . (int)$order_id . " AND `code` = '" . $this->db->escape($total['code']) . "' AND `title` = '" . $this->db->escape($total['title']) . "'");
		}
	}

	public function getMethod($address, $total) {
		// Not shown in the payment method list
		return array();
	}

	public function updateCronJobRunTime() {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `group` = 'amazon_checkout' AND `key` = 'amazon_checkout_last_cron_job_run'");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`store_id`, `group`, `key`, `value`, `serialized`) VALUES (0, 'amazon_checkout', 'amazon_checkout_last_cron_job_run', NOW(), 0)");
	}
}
?>