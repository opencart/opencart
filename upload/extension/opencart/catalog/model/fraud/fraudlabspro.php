<?php
namespace Opencart\Application\Model\Extension\Opencart\Fraud;
class FraudLabsPro extends \Opencart\System\Engine\Model {
	public function check($data) {
		// Do not perform fraud check if FraudLabs Pro is disabled.
		if (!$this->config->get('fraud_fraudlabspro_status')) {
			return;
		}

		// Do not perform fraud check if API key is not provided.
		if (!$this->config->get('fraud_fraudlabspro_key')) {
			$this->write_debug_log('FraudLabs Pro validation will not be performed due to API key not provided.');
			return;
		}

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "fraudlabspro` WHERE order_id = '" . (int)$data['order_id'] . "'");

		// Do not call FraudLabs Pro API if order is already screened.
		if ($query->num_rows) {
			return;
		}

		$this->write_debug_log('FraudLabs Pro validation started for Order ' . (int)$data['order_id'] . '.');

		$ip = $data['ip'];

		// get the data of all ips
		$ip_sucuri = $ip_incap = $ip_cf = $ip_forwarded = '::1';
		$ip_remoteadd = $_SERVER['REMOTE_ADDR'];

		// Get real client IP is they are behind Sucuri firewall.
		if(isset($_SERVER['HTTP_X_SUCURI_CLIENTIP']) && filter_var($_SERVER['HTTP_X_SUCURI_CLIENTIP'], FILTER_VALIDATE_IP)){
			$ip_sucuri = $ip = $_SERVER['HTTP_X_SUCURI_CLIENTIP'];
		}

		// Get real client IP is they are behind Incapsula firewall.
		if(isset($_SERVER['HTTP_INCAP_CLIENT_IP']) && filter_var($_SERVER['HTTP_INCAP_CLIENT_IP'], FILTER_VALIDATE_IP)){
			$ip_incap = $ip = $_SERVER['HTTP_INCAP_CLIENT_IP'];
		}

		// Get real client IP is they are behind CloudFlare protection.
		if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) && filter_var($_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)){
			$ip_cf = $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		}

		// Get real client IP is they are behind proxy server.
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$xip = trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));
			
			if (filter_var($xip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
				$ip_forwarded = $ip = $xip;
			}
		}

		// Overwrite client IP if simulate IP is provided.
		if (filter_var($this->config->get('fraud_fraudlabspro_simulate_ip'), FILTER_VALIDATE_IP)) {
			$ip = $this->config->get('fraud_fraudlabspro_simulate_ip');
		}

		$request['key'] = $this->config->get('fraud_fraudlabspro_key');
		$request['ip'] = $ip;
		$request['ip_remoteadd'] = $ip_remoteadd;
		$request['ip_sucuri'] = $ip_sucuri;
		$request['ip_incap'] = $ip_incap;
		$request['ip_cf'] = $ip_cf;
		$request['ip_forwarded'] = $ip_forwarded;
		$request['first_name'] = $data['firstname'];
		$request['last_name'] = $data['lastname'];
		$request['bill_addr'] = $data['payment_address_1'];
		$request['bill_city'] = $data['payment_city'];
		$request['bill_state'] = $data['payment_zone'];
		$request['bill_country'] = $data['payment_iso_code_2'];
		$request['bill_zip_code'] = $data['payment_postcode'];
		$request['email_domain'] = utf8_substr(strrchr($data['email'], '@'), 1);
		$request['user_phone'] = $data['telephone'];

		if ($data['shipping_method']) {
			$request['ship_first_name'] = $data['shipping_firstname'];
			$request['ship_last_name'] = $data['shipping_lastname'];
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
		$request['source_version'] = '3.0.3.0';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://api.fraudlabspro.com/v1/order/screen?' . http_build_query($request));
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

		$response = curl_exec($curl);

		curl_close($curl);

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
				fraudlabspro_rules = '" . $this->db->escape($json->fraudlabspro_rules) . "',
				fraudlabspro_score = '" . $this->db->escape($json->fraudlabspro_score) . "',
				fraudlabspro_distribution = '" . $this->db->escape($json->fraudlabspro_distribution) . "',
				fraudlabspro_status = '" . $this->db->escape($json->fraudlabspro_status) . "',
				fraudlabspro_id = '" . $this->db->escape($json->fraudlabspro_id) . "',
				fraudlabspro_error = '" . $this->db->escape($json->fraudlabspro_error_code) . "',
				fraudlabspro_message = '" . $this->db->escape($json->fraudlabspro_message) . "',
				fraudlabspro_credits = '" .  $this->db->escape($json->fraudlabspro_credits) . "',
				api_key = '" .  $this->config->get('fraud_fraudlabspro_key') . "',
				ip_address = '" .  $ip . "'"
			);
		} else {
			$this->write_debug_log('Order ' . (int)$data['order_id'] . ' data contains invalid value.');
		}

		if (isset($_SESSION['flp_cc_bin'])) {
			unset($_SESSION['flp_cc_bin']);
		}

		if (isset($_SESSION['flp_cc_hash'])) {
			unset($_SESSION['flp_cc_hash']);
		}

		// Do not perform any action if error found
		if ($json->fraudlabspro_error_code) {
			$this->write_debug_log('Error code:' . $json->fraudlabspro_error_code . ' found in Order ' . (int)$data['order_id'] . '.');
			return;
		}

		if (($this->config->get('fraud_fraudlabspro_zapier_approve') && $json->fraudlabspro_status == 'APPROVE') || ($this->config->get('fraud_fraudlabspro_zapier_review') && $json->fraudlabspro_status == 'REVIEW') || ( $this->config->get('fraud_fraudlabspro_zapier_reject') && $json->fraudlabspro_status == 'REJECT')) {
			// Use zaptrigger API to get zap information
			$zap_request['key'] = $this->config->get('fraud_fraudlabspro_key');
			$zap_request['format'] = 'json';

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, 'https://api.fraudlabspro.com/v1/zaptrigger?' . http_build_query($zap_request));
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

			$zap_response = curl_exec($curl);

			curl_close($curl);

			if (is_null($zap_json = json_decode($zap_response)) === FALSE) {
				$target_url = $zap_json->target_url;
			} else {
				$target_url = '';
			}

			if (!empty($target_url)) {
				$zapresponse = $this->http($target_url, [
					'id'			=> $json->fraudlabspro_id,
					'date_created'	=> gmdate('Y-m-d H:i:s'),
					'flp_status'	=> $json->fraudlabspro_status,
					'full_name'		=> $data['firstname'] . ' ' . $data['lastname'],
					'email'			=> $data['email'],
					'order_id'		=> $data['order_id'],
				]);
				$zapdata = json_decode($zapresponse);
				if (is_object($zapdata)) {
					if ($zapdata->status == 'success') {
						$this->write_debug_log('Hooks sent to Zapier successful.');
					} else {
						$this->write_debug_log('Hooks sent to Zapier failed.');
					}
				} else {
					$this->write_debug_log('Failed in sending hook to Zapier.');
				}
			} else {
				$this->write_debug_log('Zapier target_url not found.');
			}
		}

		$this->write_debug_log('FraudLabs Pro validation completed with status [' . $json->fraudlabspro_status . ']. Transaction ID = ' . $json->fraudlabspro_id . '.');

		if ($json->fraudlabspro_status == 'REVIEW') {
			return $this->config->get('fraud_fraudlabspro_review_status_id');
		}

		if ($json->fraudlabspro_status == 'APPROVE') {
			return $this->config->get('fraud_fraudlabspro_approve_status_id');
		}

		if ($json->fraudlabspro_status == 'REJECT') {
			return $this->config->get('fraud_fraudlabspro_reject_status_id');
		}
	}

	private function hashIt($s) {
		$hash = 'fraudlabspro_' . $s;

		for ($i = 0; $i < 65536; $i++)
			$hash = sha1('fraudlabspro_' . $hash);

		return $hash;
	}

	// Write to debug log to record details of process.
	private function write_debug_log($message) {
		if (!$this->config->get('fraud_fraudlabspro_debug_status')) {
			return;
		}

		file_put_contents( 'FLP_debug.log', gmdate('Y-m-d H:i:s') . "\t" . $message . "\n", FILE_APPEND );
	}

	private function http($url, $fields = ''){
		$ch = curl_init();

		if ($fields) {
			$data_string = json_encode($fields);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, '1.1');
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string))
		);

		$response = curl_exec($ch);

		if (!curl_errno($ch)) {
			return $response;
		}

		return false;
	}
}
