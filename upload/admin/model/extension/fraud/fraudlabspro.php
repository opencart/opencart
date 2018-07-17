<?php
class ModelExtensionFraudFraudLabsPro extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "fraudlabspro` (
				`order_id` VARCHAR(11) NOT NULL,
				`is_country_match` CHAR(2) NOT NULL,
				`is_high_risk_country` CHAR(2) NOT NULL,
				`distance_in_km` VARCHAR(10) NOT NULL,
				`distance_in_mile` VARCHAR(10) NOT NULL,
				`ip_address` VARCHAR(39) NOT NULL,
				`ip_country` VARCHAR(2) NOT NULL,
				`ip_continent` VARCHAR(20) NOT NULL,
				`ip_region` VARCHAR(21) NOT NULL,
				`ip_city` VARCHAR(21) NOT NULL,
				`ip_latitude` VARCHAR(21) NOT NULL,
				`ip_longitude` VARCHAR(21) NOT NULL,
				`ip_timezone` VARCHAR(10) NOT NULL,
				`ip_elevation` VARCHAR(10) NOT NULL,
				`ip_domain` VARCHAR(50) NOT NULL,
				`ip_mobile_mnc` VARCHAR(100) NOT NULL,
				`ip_mobile_mcc` VARCHAR(100) NOT NULL,
				`ip_mobile_brand` VARCHAR(100) NOT NULL,
				`ip_netspeed` VARCHAR(10) NOT NULL,
				`ip_isp_name` VARCHAR(50) NOT NULL,
				`ip_usage_type` VARCHAR(30) NOT NULL,
				`is_free_email` CHAR(2) NOT NULL,
				`is_new_domain_name` CHAR(2) NOT NULL,
				`is_proxy_ip_address` CHAR(2) NOT NULL,
				`is_bin_found` CHAR(2) NOT NULL,
				`is_bin_country_match` CHAR(2) NOT NULL,
				`is_bin_name_match` CHAR(2) NOT NULL,
				`is_bin_phone_match` CHAR(2) NOT NULL,
				`is_bin_prepaid` CHAR(2) NOT NULL,
				`is_address_ship_forward` CHAR(2) NOT NULL,
				`is_bill_ship_city_match` CHAR(2) NOT NULL,
				`is_bill_ship_state_match` CHAR(2) NOT NULL,
				`is_bill_ship_country_match` CHAR(2) NOT NULL,
				`is_bill_ship_postal_match` CHAR(2) NOT NULL,
				`is_ip_blacklist` CHAR(2) NOT NULL,
				`is_email_blacklist` CHAR(2) NOT NULL,
				`is_credit_card_blacklist` CHAR(2) NOT NULL,
				`is_device_blacklist` CHAR(2) NOT NULL,
				`is_user_blacklist` CHAR(2) NOT NULL,
				`fraudlabspro_score` CHAR(3) NOT NULL,
				`fraudlabspro_distribution` CHAR(3) NOT NULL,
				`fraudlabspro_status` CHAR(10) NOT NULL,
				`fraudlabspro_id` CHAR(15) NOT NULL,
				`fraudlabspro_error` CHAR(3) NOT NULL,
				`fraudlabspro_message` VARCHAR(50) NOT NULL,
				`fraudlabspro_credits` VARCHAR(10) NOT NULL,
				`api_key` CHAR(32) NOT NULL,
				PRIMARY KEY (`order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("ALTER TABLE `" . DB_PREFIX . "fraudlabspro` CHANGE COLUMN `ip_address` `ip_address` VARCHAR(39) NOT NULL;");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` (`language_id`, `name`) VALUES (1, 'Fraud');");
		$status_fraud_id = $this->db->getLastId();

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` (`language_id`, `name`) VALUES (1, 'Fraud Review');");

		$status_fraud_review_id = $this->db->getLastId();

		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`code`, `key`, `value`, `serialized`) VALUES ('fraudlabspro', 'fraud_fraudlabspro_review_status_id', '" . (int)$status_fraud_review_id . "', '0');");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`code`, `key`, `value`, `serialized`) VALUES ('fraudlabspro', 'fraud_fraudlabspro_approve_status_id', '2', '0');");
		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`code`, `key`, `value`, `serialized`) VALUES ('fraudlabspro', 'fraud_fraudlabspro_reject_status_id', '8', '0');");

		$this->cache->delete('order_status.' . (int)$this->config->get('config_language_id'));
	}

	public function uninstall() {
		//$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "fraudlabspro`");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `name` = 'Fraud'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `name` = 'Fraud Review'");
	}

	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraudlabspro` WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}

	public function addOrderHistory($order_id, $data, $store_id = 0) {
		$json = array();

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($store_id);

		if ($store_info) {
			$url = $store_info['url'];
		} else {
			$url = HTTP_CATALOG;
		}

		if (isset($this->session->data['cookie'])) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, $url . 'index.php?route=api/order/history&order_id=' . $order_id);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
			curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');

			$json = curl_exec($curl);

			curl_close($curl);
		}

		return $json;
	}
}
