<?php
class ModelExtensionFraudFraudLabsPro extends Model {
	public function check($data) {
		// Do not perform fraud check if FraudLabs Pro is disabled or API key is not provided.
		if (!$this->config->get('fraudlabspro_status') ||!$this->config->get('fraudlabspro_key')) {
			return;
		}

		$risk_score = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraudlabspro` WHERE order_id = '" . (int)$data['order_id'] . "'");

		// Do not call FraudLabs Pro API if order is already screened.
		if ($query->num_rows) {
			return;
		}

		$ip = $data['ip'];

		// Detect client IP is store is behind CloudFlare protection.
		if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)){
			$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

		// Get real client IP is they are behind proxy server.
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && filter_var($_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		// Overwrite client IP if simulate IP is provided.
		if (filter_var($this->config->get('fraudlabspro_simulate_ip'), FILTER_VALIDATE_IP)) {
			$ip = $this->config->get('fraudlabspro_simulate_ip');
		}

		$request['key'] = $this->config->get('fraudlabspro_key');
		$request['ip'] = $ip;
		$request['first_name'] = $data['firstname'];
		$request['last_name'] = $data['lastname'];
		$request['bill_city'] = $data['payment_city'];
		$request['bill_state'] = $data['payment_zone'];
		$request['bill_country'] = $data['payment_iso_code_2'];
		$request['bill_zip_code'] = $data['payment_postcode'];
		$request['email_domain'] = utf8_substr(strrchr($data['email'], '@'), 1);
		$request['user_phone'] = $data['telephone'];

		if ($data['shipping_method']) {
			$request['ship_addr'] = $data['shipping_address_1'];
			$request['ship_city'] = $data['shipping_city'];
			$request['ship_state'] = $data['shipping_zone'];
			$request['ship_zip_code'] = $data['shipping_postcode'];
			$request['ship_country'] = $data['shipping_iso_code_2'];
		}
		
		$request['email'] = $data['email'];
		$request['email_hash'] = $this->hashIt($data['email']);
		$request['amount'] = $this->currency->format($data['total'], $data['currency_code'], $data['currency_value'], false);
		$request['quantity'] = 1;
		$request['currency'] = $data['currency_code'];
		$request['payment_mode'] = $data['payment_code'];
		$request['user_order_id'] = $data['order_id'];
		$request['flp_checksum'] = (isset($_COOKIE['flp_checksum'])) ? $_COOKIE['flp_checksum'] : '';
		$request['bin_no'] = (isset($_SESSION['flp_cc_bin'])) ? $_SESSION['flp_cc_bin'] : '';
		$request['card_hash'] = (isset($_SESSION['flp_cc_hash'])) ? $_SESSION['flp_cc_hash'] : '';
		$request['format'] = 'json';
		$request['source'] = 'opencart';
		$request['source_version'] = '2.1.0.2';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.fraudlabspro.com/v1/order/screen?' . http_build_query($request));
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

		$response = curl_exec($curl);

		curl_close($curl);

		$risk_score = 0;

		if (is_null($json = json_decode($response)) === FALSE) {
			$this->db->query("REPLACE INTO `" . DB_PREFIX . "fraudlabspro` SET order_id = '" . (int)$data['order_id'] . "',
				is_country_match = '" . $this->db->escape($json->is_country_match) . "',
				is_high_risk_country = '" . $this->db->escape($json->is_high_risk_country) . "',
				distance_in_km = '" . $this->db->escape($json->distance_in_km) . "',
				distance_in_mile = '" . $this->db->escape($json->distance_in_mile) . "',
				ip_country = '" . $this->db->escape($json->ip_country) . "',
				ip_region = '" . $this->db->escape($json->ip_region) . "',
				ip_city = '" . $this->db->escape($json->ip_city) . "',
				ip_continent = '" . $this->db->escape($json->ip_continent) . "',
				ip_latitude = '" . $this->db->escape($json->ip_latitude) . "',
				ip_longitude = '" . $this->db->escape($json->ip_longitude) . "',
				ip_timezone = '" . $this->db->escape($json->ip_timezone) . "',
				ip_elevation = '" . $this->db->escape($json->ip_elevation) . "',
				ip_domain = '" . $this->db->escape($json->ip_domain) . "',
				ip_mobile_mnc = '" . $this->db->escape($json->ip_mobile_mnc) . "',
				ip_mobile_mcc = '" . $this->db->escape($json->ip_mobile_mcc) . "',
				ip_mobile_brand = '" . $this->db->escape($json->ip_mobile_brand) . "',
				ip_netspeed = '" . $this->db->escape($json->ip_netspeed) . "',
				ip_isp_name = '" . $this->db->escape($json->ip_isp_name) . "',
				ip_usage_type = '" . $this->db->escape($json->ip_usage_type) . "',
				is_free_email = '" . $this->db->escape($json->is_free_email) . "',
				is_new_domain_name = '" . $this->db->escape($json->is_new_domain_name) . "',
				is_proxy_ip_address = '" . $this->db->escape($json->is_proxy_ip_address) . "',
				is_bin_found = '" . $this->db->escape($json->is_bin_found) . "',
				is_bin_country_match = '" . $this->db->escape($json->is_bin_country_match) . "',
				is_bin_name_match = '" . $this->db->escape($json->is_bin_name_match) . "',
				is_bin_phone_match = '" . $this->db->escape($json->is_bin_phone_match) . "',
				is_bin_prepaid = '" . $this->db->escape($json->is_bin_prepaid) . "',
				is_address_ship_forward = '" . $this->db->escape($json->is_address_ship_forward) . "',
				is_bill_ship_city_match = '" . $this->db->escape($json->is_bill_ship_city_match) . "',
				is_bill_ship_state_match = '" . $this->db->escape($json->is_bill_ship_state_match) . "',
				is_bill_ship_country_match = '" . $this->db->escape($json->is_bill_ship_country_match) . "',
				is_bill_ship_postal_match = '" . $this->db->escape($json->is_bill_ship_postal_match) . "',
				is_ip_blacklist = '" . $this->db->escape($json->is_ip_blacklist) . "',
				is_email_blacklist = '" . $this->db->escape($json->is_email_blacklist) . "',
				is_credit_card_blacklist = '" . $this->db->escape($json->is_credit_card_blacklist) . "',
				is_device_blacklist = '" . $this->db->escape($json->is_device_blacklist) . "',
				is_user_blacklist = '" . $this->db->escape($json->is_user_blacklist) . "',
				fraudlabspro_score = '" . $this->db->escape($json->fraudlabspro_score) . "',
				fraudlabspro_distribution = '" . $this->db->escape($json->fraudlabspro_distribution) . "',
				fraudlabspro_status = '" . $this->db->escape($json->fraudlabspro_status) . "',
				fraudlabspro_id = '" . $this->db->escape($json->fraudlabspro_id) . "',
				fraudlabspro_error = '" . $this->db->escape($json->fraudlabspro_error_code) . "',
				fraudlabspro_message = '" . $this->db->escape($json->fraudlabspro_message) . "',
				fraudlabspro_credits = '" .  $this->db->escape($json->fraudlabspro_credits) . "',
				api_key = '" .  $this->config->get('fraudlabspro_key') . "',
				ip_address = '" .  $ip . "'"
			);

			$risk_score = (int)$json->fraudlabspro_score;
		}

		// Do not perform any action if error found
		if ($json->fraudlabspro_error_code) {
			return;
		}

		if ($risk_score > $this->config->get('fraudlabspro_score')) {
			return $this->config->get('fraudlabspro_order_status_id');
		}

		if ($json->fraudlabspro_status == 'REVIEW') {
			return $this->config->get('fraudlabspro_review_status_id');
		}

		if ($json->fraudlabspro_status == 'APPROVE') {
			return $this->config->get('fraudlabspro_approve_status_id');
		}

		if ($json->fraudlabspro_status == 'REJECT') {
			return $this->config->get('fraudlabspro_reject_status_id');
		}

		unset($_SESSION['flp_cc_bin']);
		unset($_SESSION['flp_cc_hash']);
	}

	private function hashIt($s) {
		$hash = 'fraudlabspro_' . $s;

		for ($i = 0; $i < 65536; $i++)
			$hash = sha1('fraudlabspro_' . $hash);

		return $hash;
	}
}