<?php
class ModelExtensionFraudMaxMind extends Model {
	public function check($order_info) {
		$risk_score = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "maxmind` WHERE order_id = '" . (int)$order_info['order_id'] . "'");

		if ($query->num_rows) {
			$risk_score = $query->row['risk_score'];
		} else {
			/*
			maxmind api
			http://www.maxmind.com/app/ccv

			paypal api
			https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_IPNandPDTVariables
			*/

			$request = 'i=' . urlencode($order_info['ip']);
			$request .= '&city=' . urlencode($order_info['payment_city']);
			$request .= '&region=' . urlencode($order_info['payment_zone']);
			$request .= '&postal=' . urlencode($order_info['payment_postcode']);
			$request .= '&country=' . urlencode($order_info['payment_country']);
			$request .= '&domain=' . urlencode(utf8_substr(strrchr($order_info['email'], '@'), 1));
			$request .= '&custPhone=' . urlencode($order_info['telephone']);
			$request .= '&license_key=' . urlencode($this->config->get('maxmind_key'));

			if ($order_info['shipping_method']) {
				$request .= '&shipAddr=' . urlencode($order_info['shipping_address_1']);
				$request .= '&shipCity=' . urlencode($order_info['shipping_city']);
				$request .= '&shipRegion=' . urlencode($order_info['shipping_zone']);
				$request .= '&shipPostal=' . urlencode($order_info['shipping_postcode']);
				$request .= '&shipCountry=' . urlencode($order_info['shipping_country']);
			}

			$request .= '&user_agent=' . urlencode($order_info['user_agent']);
			$request .= '&forwardedIP=' . urlencode($order_info['forwarded_ip']);
			$request .= '&emailMD5=' . urlencode(md5(utf8_strtolower($order_info['email'])));
			//$request .= '&passwordMD5=' . urlencode($order_info['password']);
			$request .= '&accept_language=' .  urlencode($order_info['accept_language']);
			$request .= '&order_amount=' . urlencode($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false));
			$request .= '&order_currency=' . urlencode($order_info['currency_code']);

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
				$order_id = $order_info['order_id'];
				$customer_id = $order_info['customer_id'];

				$response_info = array();

				$parts = explode(';', $response);

				foreach ($parts as $part) {
					list($key, $value) = explode('=', $part);

					$response_info[$key] = $value;
				}

				if (isset($response_info['countryMatch'])) {
					$country_match = $response_info['countryMatch'];
				} else {
					$country_match = '';
				}

				if (isset($response_info['countryCode'])) {
					$country_code = $response_info['countryCode'];
				} else {
					$country_code = '';
				}

				if (isset($response_info['highRiskCountry'])) {
					$high_risk_country = $response_info['highRiskCountry'];
				} else {
					$high_risk_country = '';
				}

				if (isset($response_info['distance'])) {
					$distance = $response_info['distance'];
				} else {
					$distance = '';
				}

				if (isset($response_info['ip_region'])) {
					$ip_region = $response_info['ip_region'];
				} else {
					$ip_region = '';
				}

				if (isset($response_info['ip_city'])) {
					$ip_city = $response_info['ip_city'];
				} else {
					$ip_city = '';
				}

				if (isset($response_info['ip_latitude'])) {
					$ip_latitude = $response_info['ip_latitude'];
				} else {
					$ip_latitude = '';
				}

				if (isset($response_info['ip_longitude'])) {
					$ip_longitude = $response_info['ip_longitude'];
				} else {
					$ip_longitude = '';
				}

				if (isset($response_info['ip_isp'])) {
					$ip_isp = $response_info['ip_isp'];
				} else {
					$ip_isp = '';
				}

				if (isset($response_info['ip_org'])) {
					$ip_org = $response_info['ip_org'];
				} else {
					$ip_org = '';
				}

				if (isset($response_info['ip_asnum'])) {
					$ip_asnum = $response_info['ip_asnum'];
				} else {
					$ip_asnum = '';
				}

				if (isset($response_info['ip_userType'])) {
					$ip_user_type = $response_info['ip_userType'];
				} else {
					$ip_user_type = '';
				}

				if (isset($response_info['ip_countryConf'])) {
					$ip_country_confidence = $response_info['ip_countryConf'];
				} else {
					$ip_country_confidence = '';
				}

				if (isset($response_info['ip_regionConf'])) {
					$ip_region_confidence = $response_info['ip_regionConf'];
				} else {
					$ip_region_confidence = '';
				}

				if (isset($response_info['ip_cityConf'])) {
					$ip_city_confidence = $response_info['ip_cityConf'];
				} else {
					$ip_city_confidence = '';
				}

				if (isset($response_info['ip_postalConf'])) {
					$ip_postal_confidence = $response_info['ip_postalConf'];
				} else {
					$ip_postal_confidence = '';
				}

				if (isset($response_info['ip_postalCode'])) {
					$ip_postal_code = $response_info['ip_postalCode'];
				} else {
					$ip_postal_code = '';
				}

				if (isset($response_info['ip_accuracyRadius'])) {
					$ip_accuracy_radius = $response_info['ip_accuracyRadius'];
				} else {
					$ip_accuracy_radius = '';
				}

				if (isset($response_info['ip_netSpeedCell'])) {
					$ip_net_speed_cell = $response_info['ip_netSpeedCell'];
				} else {
					$ip_net_speed_cell = '';
				}

				if (isset($response_info['ip_metroCode'])) {
					$ip_metro_code = $response_info['ip_metroCode'];
				} else {
					$ip_metro_code = '';
				}
				if (isset($response_info['ip_areaCode'])) {
					$ip_area_code = $response_info['ip_areaCode'];
				} else {
					$ip_area_code = '';
				}

				if (isset($response_info['ip_timeZone'])) {
					$ip_time_zone = $response_info['ip_timeZone'];
				} else {
					$ip_time_zone = '';
				}

				if (isset($response_info['ip_regionName'])) {
					$ip_region_name = $response_info['ip_regionName'];
				} else {
					$ip_region_name = '';
				}

				if (isset($response_info['ip_domain'])) {
					$ip_domain = $response_info['ip_domain'];
				} else {
					$ip_domain = '';
				}
				if (isset($response_info['ip_countryName'])) {
					$ip_country_name = $response_info['ip_countryName'];
				} else {
					$ip_country_name = '';
				}

				if (isset($response_info['ip_continentCode'])) {
					$ip_continent_code = $response_info['ip_continentCode'];
				} else {
					$ip_continent_code = '';
				}

				if (isset($response_info['ip_corporateProxy'])) {
					$ip_corporate_proxy = $response_info['ip_corporateProxy'];
				} else {
					$ip_corporate_proxy = '';
				}

				if (isset($response_info['anonymousProxy'])) {
					$anonymous_proxy = $response_info['anonymousProxy'];
				} else {
					$anonymous_proxy = '';
				}

				if (isset($response_info['proxyScore'])) {
					$proxy_score = $response_info['proxyScore'];
				} else {
					$proxy_score = '';
				}

				if (isset($response_info['isTransProxy'])) {
					$is_trans_proxy = $response_info['isTransProxy'];
				} else {
					$is_trans_proxy = '';
				}

				if (isset($response_info['freeMail'])) {
					$free_mail = $response_info['freeMail'];
				} else {
					$free_mail = '';
				}

				if (isset($response_info['carderEmail'])) {
					$carder_email = $response_info['carderEmail'];
				} else {
					$carder_email = '';
				}

				if (isset($response_info['highRiskUsername'])) {
					$high_risk_username = $response_info['highRiskUsername'];
				} else {
					$high_risk_username = '';
				}

				if (isset($response_info['highRiskPassword'])) {
					$high_risk_password = $response_info['highRiskPassword'];
				} else {
					$high_risk_password = '';
				}

				if (isset($response_info['binMatch'])) {
					$bin_match = $response_info['binMatch'];
				} else {
					$bin_match = '';
				}

				if (isset($response_info['binCountry'])) {
					$bin_country = $response_info['binCountry'];
				} else {
					$bin_country = '';
				}

				if (isset($response_info['binNameMatch'])) {
					$bin_name_match = $response_info['binNameMatch'];
				} else {
					$bin_name_match = '';
				}

				if (isset($response_info['binName'])) {
					$bin_name = $response_info['binName'];
				} else {
					$bin_name = '';
				}

				if (isset($response_info['binPhoneMatch'])) {
					$bin_phone_match = $response_info['binPhoneMatch'];
				} else {
					$bin_phone_match = '';
				}

				if (isset($response_info['binPhone'])) {
					$bin_phone = $response_info['binPhone'];
				} else {
					$bin_phone = '';
				}

				if (isset($response_info['custPhoneInBillingLoc'])) {
					$customer_phone_in_billing_location = $response_info['custPhoneInBillingLoc'];
				} else {
					$customer_phone_in_billing_location = '';
				}

				if (isset($response_info['shipForward'])) {
					$ship_forward = $response_info['shipForward'];
				} else {
					$ship_forward = '';
				}

				if (isset($response_info['cityPostalMatch'])) {
					$city_postal_match = $response_info['cityPostalMatch'];
				} else {
					$city_postal_match = '';
				}

				if (isset($response_info['shipCityPostalMatch'])) {
					$ship_city_postal_match = $response_info['shipCityPostalMatch'];
				} else {
					$ship_city_postal_match = '';
				}

				if (isset($response_info['score'])) {
					$score = $response_info['score'];
				} else {
					$score = '';
				}

				if (isset($response_info['explanation'])) {
					$explanation = $response_info['explanation'];
				} else {
					$explanation = '';
				}

				if (isset($response_info['riskScore'])) {
					$risk_score = $response_info['riskScore'];
				} else {
					$risk_score = '';
				}

				if (isset($response_info['queriesRemaining'])) {
					$queries_remaining = $response_info['queriesRemaining'];
				} else {
					$queries_remaining = '';
				}

				if (isset($response_info['maxmindID'])) {
					$maxmind_id = $response_info['maxmindID'];
				} else {
					$maxmind_id = '';
				}

				if (isset($response_info['err'])) {
					$error = $response_info['err'];
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
