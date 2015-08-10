<?php
class ModelFraudMaxMind extends Model {
	public function check($data) {
		$risk_score = 0;

		$fraud_info = $this->getFraud($data['order_id']);

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "maxmind` WHERE order_id = '" . (int)$order_id . "'");
		
		if ($query->num_rows) {
			$risk_score = $query->row['risk_score'];
		} else {
			/*
			maxmind api
			http://www.maxmind.com/app/ccv

			paypal api
			https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_IPNandPDTVariables
			*/

			$request = 'i=' . urlencode($data['ip']);
			$request .= '&city=' . urlencode($data['payment_city']);
			$request .= '&region=' . urlencode($data['payment_zone']);
			$request .= '&postal=' . urlencode($data['payment_postcode']);
			$request .= '&country=' . urlencode($data['payment_country']);
			$request .= '&domain=' . urlencode(utf8_substr(strrchr($data['email'], '@'), 1));
			$request .= '&custPhone=' . urlencode($data['telephone']);
			$request .= '&license_key=' . urlencode($this->config->get('config_fraud_key'));

			if ($data['shipping_method']) {
				$request .= '&shipAddr=' . urlencode($data['shipping_address_1']);
				$request .= '&shipCity=' . urlencode($data['shipping_city']);
				$request .= '&shipRegion=' . urlencode($data['shipping_zone']);
				$request .= '&shipPostal=' . urlencode($data['shipping_postcode']);
				$request .= '&shipCountry=' . urlencode($data['shipping_country']);
			}

			$request .= '&user_agent=' . urlencode($data['user_agent']);
			$request .= '&forwardedIP=' . urlencode($data['forwarded_ip']);
			$request .= '&emailMD5=' . urlencode(md5(utf8_strtolower($data['email'])));
			//$request .= '&passwordMD5=' . urlencode($data['password']);
			$request .= '&accept_language=' .  urlencode($data['accept_language']);
			$request .= '&order_amount=' . urlencode($this->currency->format($data['total'], $data['currency_code'], $data['currency_value'], false));
			$request .= '&order_currency=' . urlencode($data['currency_code']);

			$curl = curl_init('https://minfraud1.maxmind.com/app/ccv2r');

			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

			$response = curl_exec($curl);

			curl_close($curl);

			$risk_score = 0;

			if ($response) {
				$order_id = $data['order_id'];
				$customer_id = $data['customer_id'];

				$data = array();

				$parts = explode(';', $response);

				foreach ($parts as $part) {
					list($key, $value) = explode('=', $part);

					$data[$key] = $value;
				}

				if (isset($data['countryMatch'])) {
					$country_match = $data['countryMatch'];
				} else {
					$country_match = '';
				}

				if (isset($data['countryCode'])) {
					$country_code = $data['countryCode'];
				} else {
					$country_code = '';
				}

				if (isset($data['highRiskCountry'])) {
					$high_risk_country = $data['highRiskCountry'];
				} else {
					$high_risk_country = '';
				}

				if (isset($data['distance'])) {
					$distance = $data['distance'];
				} else {
					$distance = '';
				}

				if (isset($data['ip_region'])) {
					$ip_region = $data['ip_region'];
				} else {
					$ip_region = '';
				}

				if (isset($data['ip_city'])) {
					$ip_city = $data['ip_city'];
				} else {
					$ip_city = '';
				}

				if (isset($data['ip_city'])) {
					$ip_city = $data['ip_city'];
				} else {
					$ip_city = '';
				}

				if (isset($data['ip_latitude'])) {
					$ip_latitude = $data['ip_latitude'];
				} else {
					$ip_latitude = '';
				}

				if (isset($data['ip_longitude'])) {
					$ip_longitude = $data['ip_longitude'];
				} else {
					$ip_longitude = '';
				}

				if (isset($data['ip_isp'])) {
					$ip_isp = $data['ip_isp'];
				} else {
					$ip_isp = '';
				}

				if (isset($data['ip_org'])) {
					$ip_org = $data['ip_org'];
				} else {
					$ip_org = '';
				}

				if (isset($data['ip_asnum'])) {
					$ip_asnum = $data['ip_asnum'];
				} else {
					$ip_asnum = '';
				}

				if (isset($data['ip_userType'])) {
					$ip_user_type = $data['ip_userType'];
				} else {
					$ip_user_type = '';
				}

				if (isset($data['ip_countryConf'])) {
					$ip_country_confidence = $data['ip_countryConf'];
				} else {
					$ip_country_confidence = '';
				}

				if (isset($data['ip_regionConf'])) {
					$ip_region_confidence = $data['ip_regionConf'];
				} else {
					$ip_region_confidence = '';
				}

				if (isset($data['ip_cityConf'])) {
					$ip_city_confidence = $data['ip_cityConf'];
				} else {
					$ip_city_confidence = '';
				}

				if (isset($data['ip_postalConf'])) {
					$ip_postal_confidence = $data['ip_postalConf'];
				} else {
					$ip_postal_confidence = '';
				}

				if (isset($data['ip_postalCode'])) {
					$ip_postal_code = $data['ip_postalCode'];
				} else {
					$ip_postal_code = '';
				}

				if (isset($data['ip_accuracyRadius'])) {
					$ip_accuracy_radius = $data['ip_accuracyRadius'];
				} else {
					$ip_accuracy_radius = '';
				}

				if (isset($data['ip_netSpeedCell'])) {
					$ip_net_speed_cell = $data['ip_netSpeedCell'];
				} else {
					$ip_net_speed_cell = '';
				}

				if (isset($data['ip_metroCode'])) {
					$ip_metro_code = $data['ip_metroCode'];
				} else {
					$ip_metro_code = '';
				}
				if (isset($data['ip_areaCode'])) {
					$ip_area_code = $data['ip_areaCode'];
				} else {
					$ip_area_code = '';
				}

				if (isset($data['ip_timeZone'])) {
					$ip_time_zone = $data['ip_timeZone'];
				} else {
					$ip_time_zone = '';
				}

				if (isset($data['ip_regionName'])) {
					$ip_region_name = $data['ip_regionName'];
				} else {
					$ip_region_name = '';
				}

				if (isset($data['ip_domain'])) {
					$ip_domain = $data['ip_domain'];
				} else {
					$ip_domain = '';
				}
				if (isset($data['ip_countryName'])) {
					$ip_country_name = $data['ip_countryName'];
				} else {
					$ip_country_name = '';
				}

				if (isset($data['ip_continentCode'])) {
					$ip_continent_code = $data['ip_continentCode'];
				} else {
					$ip_continent_code = '';
				}

				if (isset($data['ip_corporateProxy'])) {
					$ip_corporate_proxy = $data['ip_corporateProxy'];
				} else {
					$ip_corporate_proxy = '';
				}

				if (isset($data['anonymousProxy'])) {
					$anonymous_proxy = $data['anonymousProxy'];
				} else {
					$anonymous_proxy = '';
				}

				if (isset($data['proxyScore'])) {
					$proxy_score = $data['proxyScore'];
				} else {
					$proxy_score = '';
				}

				if (isset($data['isTransProxy'])) {
					$is_trans_proxy = $data['isTransProxy'];
				} else {
					$is_trans_proxy = '';
				}

				if (isset($data['freeMail'])) {
					$free_mail = $data['freeMail'];
				} else {
					$free_mail = '';
				}

				if (isset($data['carderEmail'])) {
					$carder_email = $data['carderEmail'];
				} else {
					$carder_email = '';
				}

				if (isset($data['highRiskUsername'])) {
					$high_risk_username = $data['highRiskUsername'];
				} else {
					$high_risk_username = '';
				}

				if (isset($data['highRiskPassword'])) {
					$high_risk_password = $data['highRiskPassword'];
				} else {
					$high_risk_password = '';
				}

				if (isset($data['binMatch'])) {
					$bin_match = $data['binMatch'];
				} else {
					$bin_match = '';
				}

				if (isset($data['binCountry'])) {
					$bin_country = $data['binCountry'];
				} else {
					$bin_country = '';
				}

				if (isset($data['binNameMatch'])) {
					$bin_name_match = $data['binNameMatch'];
				} else {
					$bin_name_match = '';
				}

				if (isset($data['binName'])) {
					$bin_name = $data['binName'];
				} else {
					$bin_name = '';
				}

				if (isset($data['binPhoneMatch'])) {
					$bin_phone_match = $data['binPhoneMatch'];
				} else {
					$bin_phone_match = '';
				}

				if (isset($data['binPhone'])) {
					$bin_phone = $data['binPhone'];
				} else {
					$bin_phone = '';
				}

				if (isset($data['custPhoneInBillingLoc'])) {
					$customer_phone_in_billing_location = $data['custPhoneInBillingLoc'];
				} else {
					$customer_phone_in_billing_location = '';
				}

				if (isset($data['shipForward'])) {
					$ship_forward = $data['shipForward'];
				} else {
					$ship_forward = '';
				}

				if (isset($data['cityPostalMatch'])) {
					$city_postal_match = $data['cityPostalMatch'];
				} else {
					$city_postal_match = '';
				}

				if (isset($data['shipCityPostalMatch'])) {
					$ship_city_postal_match = $data['shipCityPostalMatch'];
				} else {
					$ship_city_postal_match = '';
				}

				if (isset($data['score'])) {
					$score = $data['score'];
				} else {
					$score = '';
				}

				if (isset($data['explanation'])) {
					$explanation = $data['explanation'];
				} else {
					$explanation = '';
				}

				if (isset($data['riskScore'])) {
					$risk_score = $data['riskScore'];
				} else {
					$risk_score = '';
				}

				if (isset($data['queriesRemaining'])) {
					$queries_remaining = $data['queriesRemaining'];
				} else {
					$queries_remaining = '';
				}

				if (isset($data['maxmindID'])) {
					$maxmind_id = $data['maxmindID'];
				} else {
					$maxmind_id = '';
				}

				if (isset($data['err'])) {
					$error = $data['err'];
				} else {
					$error = '';
				}

				$this->db->query("INSERT INTO `" . DB_PREFIX . "maxmind` SET order_id = '" . (int)$order_id . "', customer_id = '" . (int)$customer_id . "', country_match = '" . $this->db->escape($country_match) . "', country_code = '" . $this->db->escape($country_code) . "', high_risk_country = '" . $this->db->escape($high_risk_country) . "', distance = '" . (int)$distance . "', ip_region = '" . $this->db->escape($ip_region) . "', ip_city = '" . $this->db->escape($ip_city) . "', ip_latitude = '" . $this->db->escape($ip_latitude) . "', ip_longitude = '" . $this->db->escape($ip_longitude) . "', ip_isp = '" . $this->db->escape($ip_isp) . "', ip_org = '" . $this->db->escape($ip_org) . "', ip_asnum = '" . (int)$ip_asnum . "', ip_user_type = '" . $this->db->escape($ip_user_type) . "', ip_country_confidence = '" . $this->db->escape($ip_country_confidence) . "', ip_region_confidence = '" . $this->db->escape($ip_region_confidence) . "', ip_city_confidence = '" . $this->db->escape($ip_city_confidence) . "', ip_postal_confidence = '" . $this->db->escape($ip_postal_confidence) . "', ip_postal_code = '" . $this->db->escape($ip_postal_code) . "', ip_accuracy_radius = '" . (int)$ip_accuracy_radius . "', ip_net_speed_cell = '" . $this->db->escape($ip_net_speed_cell) . "', ip_metro_code = '" . (int)$ip_metro_code . "', ip_area_code = '" . (int)$ip_area_code . "', ip_time_zone = '" . $this->db->escape($ip_time_zone) . "', ip_region_name = '" . $this->db->escape($ip_region_name) . "', ip_domain = '" . $this->db->escape($ip_domain) . "', ip_country_name = '" . $this->db->escape($ip_country_name) . "', ip_continent_code = '" . $this->db->escape($ip_continent_code) . "', ip_corporate_proxy = '" . $this->db->escape($ip_corporate_proxy) . "', anonymous_proxy = '" . $this->db->escape($anonymous_proxy) . "', proxy_score = '" . (float)$proxy_score . "', is_trans_proxy = '" . $this->db->escape($is_trans_proxy) . "', free_mail = '" . $this->db->escape($free_mail) . "', carder_email = '" . $this->db->escape($carder_email) . "', high_risk_username = '" . $this->db->escape($high_risk_username) . "', high_risk_password = '" . $this->db->escape($high_risk_password) . "', bin_match = '" . $this->db->escape($bin_match) . "', bin_country = '" . $this->db->escape($bin_country) . "',  bin_name_match = '" . $this->db->escape($bin_name_match) . "', bin_name = '" . $this->db->escape($bin_name) . "', bin_phone_match = '" . $this->db->escape($bin_phone_match) . "', bin_phone = '" . $this->db->escape($bin_phone) . "', customer_phone_in_billing_location = '" . $this->db->escape($customer_phone_in_billing_location) . "', ship_forward = '" . $this->db->escape($ship_forward) . "', city_postal_match = '" . $this->db->escape($city_postal_match) . "', ship_city_postal_match = '" . $this->db->escape($ship_city_postal_match) . "', score = '" . (float)$score . "', explanation = '" . $this->db->escape($explanation) . "', risk_score = '" . (float)$risk_score . "', queries_remaining = '" . (int)$queries_remaining . "', maxmind_id = '" . $this->db->escape($maxmind_id) . "', error = '" . $this->db->escape($error) . "', date_added = NOW()");
			}
		}

		if ($risk_score > $this->config->get('maxmind_score') && $this->config->get('maxmind_key')) {
			return $this->config->get('maxmind_order_status_id');
		}		
	}
}