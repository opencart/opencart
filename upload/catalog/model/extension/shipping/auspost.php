<?php
/**
 * @version    N/A, base on AUSPOST API update on 18 April 2016
 * @link       https://developers.auspost.com.au/docs/reference
 * @since      2.3.0.2   Update on 21 March 2017
 */

class ModelExtensionShippingAusPost extends Model {
	public function getQuote($address) {
		$this->load->language('extension/shipping/auspost');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_auspost_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_auspost_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$error = '';

		$api_key = $this->config->get('shipping_auspost_api');

		$quote_data = array();

		if ($status) {
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('shipping_auspost_weight_class_id'));

			$length = 0;
			$width = 0;
			$height = 0;

			if ($address['iso_code_2'] == 'AU') {

				foreach ($this->cart->getProducts() as $product) {
					if ($product['height'] > $height) {
						$height = $product['height'];
					}

					if ($product['width'] > $width) {
						$width = $product['width'];
					}

					$length += ($product['length']*$product['quantity']);
				}

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_HTTPHEADER, array('AUTH-KEY: ' . $api_key));
				curl_setopt($curl, CURLOPT_URL, 'https://digitalapi.auspost.com.au/postage/parcel/domestic/service.json?from_postcode=' . urlencode($this->config->get('shipping_auspost_postcode')) . '&to_postcode=' . urlencode($address['postcode']) . '&height=' . $height . '&width=' . $width . '&length=' . $height . '&weight=' . urlencode($weight));
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

				$response = curl_exec($curl);

				curl_close($curl);

				if ($response) {
					$response_info = array();

					$response_parts = json_decode($response, true);

					if (isset($response_parts['error'])) {
						$error = $response_parts['error']['errorMessage'];
					} else {
						$response_services = $response_parts['services']['service'];

						foreach ($response_services as $response_service) {
							$quote_data[$response_service['name']] = array(
								'code'         => 'auspost.' .  $response_service['name'],
								'title'        => $response_service['name'],
								'cost'         => $this->currency->convert($response_service['price'], 'AUD', $this->config->get('config_currency')),
								'tax_class_id' => $this->config->get('shipping_auspost_tax_class_id'),
								'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($response_service['price'], 'AUD', $this->session->data['currency']), $this->config->get('shipping_auspost_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'], 1.0000000)
							);
						}
					}
				}
			} else {
				$curl = curl_init();

				curl_setopt($curl, CURLOPT_HTTPHEADER, array('AUTH-KEY: ' .  $api_key));
				curl_setopt($curl, CURLOPT_URL, 'https://digitalapi.auspost.com.au/postage/parcel/international/service.json?country_code=' . urlencode($address['iso_code_2']) . '&weight=' . urlencode($weight));
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

				$response = curl_exec($curl);

				curl_close($curl);

				if ($response) {
					$response_info = array();

					$response_parts = json_decode($response, true);

					if (isset($response_parts['error'])) {
						$error = $response_parts['error']['errorMessage'];
					} else {
						$response_services = $response_parts['services']['service'];

						foreach ($response_services as $response_service) {
							$quote_data[$response_service['name']] = array(
								'code'         => 'auspost.' .  $response_service['name'],
								'title'        => $response_service['name'],
								'cost'         => $this->currency->convert($response_service['price'], 'AUD', $this->config->get('config_currency')),
								'tax_class_id' => $this->config->get('shipping_auspost_tax_class_id'),
								'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($response_service['price'], 'AUD', $this->session->data['currency']), $this->config->get('shipping_auspost_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'], 1.0000000)
							);
						}
					}
				}
			}
		}

		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'auspost',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_auspost_sort_order'),
				'error'      => $error
			);
		}

		return $method_data;
	}
}