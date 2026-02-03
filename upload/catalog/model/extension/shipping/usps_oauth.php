<?php
/**
 * Simplified USPS OAuth Shipping Model - Weight-based quotes with smart fallback dimensions
 * @version 1.0-simple-smart-dims for OC 3.0.x.x
 * @date 2026-01
 * @note Uses fixed fallback dimensions scaled by weight to satisfy API requirement
 * Author: cue4cheap
 */
class ModelExtensionShippingUspsOauth extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/usps_oauth');

		// Geo zone check - Updated with shipping_ prefix
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_usps_oauth_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_usps_oauth_geo_zone_id') || $query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = [];

		if ($status) {
			$clientId     = $this->config->get('shipping_usps_oauth_client_id');
			$client_secret = $this->config->get('shipping_usps_oauth_client_secret');
			$endpoint     = $this->config->get('shipping_usps_oauth_test') ? 'https://apis-tem.usps.com/' : 'https://apis.usps.com/';

			$accessToken = $this->getAccessToken($clientId, $client_secret, $endpoint);
			
			if (!$accessToken) {
				if ($this->config->get('shipping_usps_oauth_debug_mode')) {
					$this->log->write('USPS OAuth Error: Failed to obtain Access Token');
				}
				return [];
			}

			// Total weight calculation
			$weight = 0.0;
			$weight_class_to = $this->config->get('shipping_usps_oauth_weight_class_id') ?: 2; // Default to lb

			foreach ($this->cart->getProducts() as $product) {
				if ($product['shipping']) {
					$product_weight = $this->weight->convert($product['weight'], $product['weight_class_id'], $weight_class_to);
					$weight += ($product_weight * $product['quantity']);
				}
			}

			$weight = number_format((float)$weight, 2, '.', '');

			// Smart fallback dimensions based on weight
			$length = 12.0;
			$width  = 10.0;
			$height = 4.0;

			if ($weight < 0.25) {
				$length = 10.0;
				$width  = 6.0;
				$height = 1.0;
			} elseif ($weight > 10) {
				$length = 18.0;
				$width  = 14.0;
				$height = 10.0;
			}

			// Services mapping
			$service_names = [
				'USPS_GROUND_ADVANTAGE'  => $this->language->get('text_usps_ground_advantage'),
				'PRIORITY_MAIL'           => $this->language->get('text_priority_mail'),
				'PRIORITY_MAIL_EXPRESS'   => $this->language->get('text_priority_mail_express'),
				'MEDIA_MAIL'              => $this->language->get('text_media_mail')
			];

			$quote_data = [];

			foreach ($service_names as $usps_code => $title) {
				// Check if service is enabled in settings
				if (!$this->config->get('shipping_usps_oauth_' . strtolower($usps_code))) {
					continue;
				}

				$priceRequest = [
					'originZIPCode'               => $this->config->get('shipping_usps_oauth_postcode'),
					'destinationZIPCode'          => $address['postcode'],
					'weight'                      => (float)$weight,
					'length'                      => (float)$length,
					'width'                       => (float)$width,
					'height'                      => (float)$height,
					'mailClass'                   => $usps_code,
					'processingCategory'          => 'MACHINABLE',
					'rateIndicator'               => 'SP',
					'destinationEntryFacilityType'=> 'NONE',
					'priceType'                   => $this->config->get('shipping_usps_oauth_price_type') ?: 'RETAIL',
					'mailingDate'                 => date('Y-m-d')
				];

				$ch = curl_init($endpoint . 'prices/v3/base-rates/search');
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					'Authorization: Bearer ' . $accessToken,
					'Content-Type: application/json'
				]);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($priceRequest));

				$result = curl_exec($ch);

				if ($this->config->get('shipping_usps_oauth_debug_mode')) {
					$this->log->write('USPS Sent Data (' . $usps_code . '): ' . json_encode($priceRequest));
					$this->log->write('USPS Response: ' . $result);
				}

				$response = json_decode($result, true);
				curl_close($ch);

				$cost = 0;
				if (isset($response['totalBasePrice']) && is_numeric($response['totalBasePrice'])) {
					$cost = (float)$response['totalBasePrice'];
				}

				if ($cost > 0) {
					$handling_fee = $this->config->get('shipping_usps_oauth_handling_fee');
					if (!empty($handling_fee)) {
						$cost += (float)$handling_fee;
					}

					$quote_data[strtolower($usps_code)] = [
						'code'         => 'usps_oauth.' . strtolower($usps_code),
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('shipping_usps_oauth_tax_class_id'),
						'text'         => $this->currency->format(
							$this->tax->calculate(
								$cost,
								$this->config->get('shipping_usps_oauth_tax_class_id'),
								$this->config->get('config_tax')
							),
							$this->session->data['currency']
						)
					];
				}
			}

			if (!empty($quote_data)) {
				$method_data = [
					'code'       => 'usps_oauth',
					'title'      => $this->language->get('text_title'),
					'quote'      => $quote_data,
					'sort_order' => (int)$this->config->get('shipping_usps_oauth_sort_order'),
					'error'      => false
				];
			}
		}

		return $method_data;
	}

	private function getAccessToken($clientId, $client_secret, $endpoint) {
		$ch = curl_init($endpoint . 'oauth2/v3/token');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
			'client_id'     => $clientId,
			'client_secret' => $client_secret,
			'grant_type'    => 'client_credentials'
		]));
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

		$response = curl_exec($ch);
		$data = json_decode($response, true);
		curl_close($ch);

		return isset($data['access_token']) ? $data['access_token'] : false;
	}
}
