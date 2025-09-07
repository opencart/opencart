<?php
class ModelExtensionShippingFedex extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/fedex');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_fedex_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_fedex_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$error = '';

		$quote_data = array();

		if ($status) {
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('shipping_fedex_weight_class_id'));
			$weight_code = strtoupper($this->weight->getUnit($this->config->get('shipping_fedex_weight_class_id')));

			if ($weight_code == 'KGS') {
				$weight_code = 'KG';
			}

			if ($weight_code == 'LBS') {
				$weight_code = 'LB';
			}

			$date = time();

			$day = date('l', $date);

			if ($day == 'Saturday') {
				$date += 172800;
			} elseif ($day == 'Sunday') {
				$date += 86400;
			}

			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry($this->config->get('config_country_id'));

			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->config->get('config_zone_id'));

			if (!$this->config->get('shipping_fedex_test')) {
				$url = 'https://gateway.fedex.com/web-services/';
			} else {
				$url = 'https://gatewaybeta.fedex.com/web-services/';
			}

			// Whoever introduced xml to shipping companies should be flogged
			$xml  = '<?xml version="1.0"?>';
			$xml .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://fedex.com/ws/rate/v10">';
			$xml .= '	<SOAP-ENV:Body>';
			$xml .= '		<ns1:RateRequest>';
			$xml .= '			<ns1:WebAuthenticationDetail>';
			$xml .= '				<ns1:UserCredential>';
			$xml .= '					<ns1:Key>' . $this->config->get('shipping_fedex_key') . '</ns1:Key>';
			$xml .= '					<ns1:Password>' . $this->config->get('shipping_fedex_password') . '</ns1:Password>';
			$xml .= '				</ns1:UserCredential>';
			$xml .= '			</ns1:WebAuthenticationDetail>';
			$xml .= '			<ns1:ClientDetail>';
			$xml .= '				<ns1:AccountNumber>' . $this->config->get('shipping_fedex_account') . '</ns1:AccountNumber>';
			$xml .= '				<ns1:MeterNumber>' . $this->config->get('shipping_fedex_meter') . '</ns1:MeterNumber>';
			$xml .= '			</ns1:ClientDetail>';
			$xml .= '			<ns1:Version>';
			$xml .= '				<ns1:ServiceId>crs</ns1:ServiceId>';
			$xml .= '				<ns1:Major>10</ns1:Major>';
			$xml .= '				<ns1:Intermediate>0</ns1:Intermediate>';
			$xml .= '				<ns1:Minor>0</ns1:Minor>';
			$xml .= '			</ns1:Version>';
			$xml .= '			<ns1:ReturnTransitAndCommit>true</ns1:ReturnTransitAndCommit>';
			$xml .= '			<ns1:RequestedShipment>';
			$xml .= '				<ns1:ShipTimestamp>' . date('c', $date) . '</ns1:ShipTimestamp>';
			$xml .= '				<ns1:DropoffType>' . $this->config->get('shipping_fedex_dropoff_type') . '</ns1:DropoffType>';
			$xml .= '				<ns1:PackagingType>' . $this->config->get('shipping_fedex_packaging_type') . '</ns1:PackagingType>';
			$xml .= '				<ns1:Shipper>';
			$xml .= '					<ns1:Contact>';
			$xml .= '						<ns1:PersonName>' . $this->config->get('config_owner') . '</ns1:PersonName>';
			$xml .= '						<ns1:CompanyName>' . $this->config->get('config_name') . '</ns1:CompanyName>';
			$xml .= '						<ns1:PhoneNumber>' . $this->config->get('config_telephone') . '</ns1:PhoneNumber>';
			$xml .= '					</ns1:Contact>';
			$xml .= '					<ns1:Address>';

			if (in_array($country_info['iso_code_2'], array('US', 'CA'))) {
				$xml .= '						<ns1:StateOrProvinceCode>' . ($zone_info ? $zone_info['code'] : '') . '</ns1:StateOrProvinceCode>';
			} else {
				$xml .= '						<ns1:StateOrProvinceCode></ns1:StateOrProvinceCode>';
			}

			$xml .= '						<ns1:PostalCode>' . $this->config->get('shipping_fedex_postcode') . '</ns1:PostalCode>';
			$xml .= '						<ns1:CountryCode>' . $country_info['iso_code_2'] . '</ns1:CountryCode>';
			$xml .= '					</ns1:Address>';
			$xml .= '				</ns1:Shipper>';

			$xml .= '				<ns1:Recipient>';
			$xml .= '					<ns1:Contact>';
			$xml .= '						<ns1:PersonName>' . $address['firstname'] . ' ' . $address['lastname'] . '</ns1:PersonName>';
			$xml .= '						<ns1:CompanyName>' . $address['company'] . '</ns1:CompanyName>';
			$xml .= '						<ns1:PhoneNumber>' . $this->customer->getTelephone() . '</ns1:PhoneNumber>';
			$xml .= '					</ns1:Contact>';
			$xml .= '					<ns1:Address>';
			$xml .= '						<ns1:StreetLines>' . $address['address_1'] . '</ns1:StreetLines>';
			$xml .= '						<ns1:City>' . $address['city'] . '</ns1:City>';

			if (in_array($address['iso_code_2'], array('US', 'CA'))) {
				$xml .= '						<ns1:StateOrProvinceCode>' . $address['zone_code'] . '</ns1:StateOrProvinceCode>';
			} else {
				$xml .= '						<ns1:StateOrProvinceCode></ns1:StateOrProvinceCode>';
			}

			$xml .= '						<ns1:PostalCode>' . $address['postcode'] . '</ns1:PostalCode>';
			$xml .= '						<ns1:CountryCode>' . $address['iso_code_2'] . '</ns1:CountryCode>';
			$xml .= '						<ns1:Residential>' . ($address['company'] ? 'true' : 'false') . '</ns1:Residential>';
			$xml .= '					</ns1:Address>';
			$xml .= '				</ns1:Recipient>';
			$xml .= '				<ns1:ShippingChargesPayment>';
			$xml .= '					<ns1:PaymentType>SENDER</ns1:PaymentType>';
			$xml .= '					<ns1:Payor>';
			$xml .= '						<ns1:AccountNumber>' . $this->config->get('shipping_fedex_account') . '</ns1:AccountNumber>';
			$xml .= '						<ns1:CountryCode>' . $country_info['iso_code_2'] . '</ns1:CountryCode>';
			$xml .= '					</ns1:Payor>';
			$xml .= '				</ns1:ShippingChargesPayment>';
			$xml .= '				<ns1:RateRequestTypes>' . $this->config->get('shipping_fedex_rate_type') . '</ns1:RateRequestTypes>';
			$xml .= '				<ns1:PackageCount>1</ns1:PackageCount>';
			$xml .= '				<ns1:RequestedPackageLineItems>';
			$xml .= '					<ns1:SequenceNumber>1</ns1:SequenceNumber>';
			$xml .= '					<ns1:GroupPackageCount>1</ns1:GroupPackageCount>';
			$xml .= '					<ns1:Weight>';
			$xml .= '						<ns1:Units>' . $weight_code . '</ns1:Units>';
			$xml .= '						<ns1:Value>' . $weight . '</ns1:Value>';
			$xml .= '					</ns1:Weight>';
			$xml .= '					<ns1:Dimensions>';
			$xml .= '						<ns1:Length>' . $this->config->get('shipping_fedex_length') . '</ns1:Length>';
			$xml .= '						<ns1:Width>' . $this->config->get('shipping_fedex_width') . '</ns1:Width>';
			$xml .= '						<ns1:Height>' . $this->config->get('shipping_fedex_height') . '</ns1:Height>';
			$xml .= '						<ns1:Units>' . strtoupper($this->length->getUnit($this->config->get('shipping_fedex_length_class_id'))) . '</ns1:Units>';
			$xml .= '					</ns1:Dimensions>';
			$xml .= '				</ns1:RequestedPackageLineItems>';
			$xml .= '			</ns1:RequestedShipment>';
			$xml .= '		</ns1:RateRequest>';
			$xml .= '	</SOAP-ENV:Body>';
			$xml .= '</SOAP-ENV:Envelope>';

			$curl = curl_init($url);

			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($curl);

			curl_close($curl);

			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->loadXml($response);

			if ($dom->getElementsByTagName('faultcode')->length > 0) {
				$error = $dom->getElementsByTagName('cause')->item(0)->nodeValue;

				$this->log->write('FEDEX :: ' . $response);
			} elseif ($dom->getElementsByTagName('HighestSeverity')->item(0)->nodeValue == 'FAILURE' || $dom->getElementsByTagName('HighestSeverity')->item(0)->nodeValue == 'ERROR') {
				$error = $dom->getElementsByTagName('HighestSeverity')->item(0)->nodeValue;

				$this->log->write('FEDEX :: ' . $response);
			} else {
				$rate_reply_details = $dom->getElementsByTagName('RateReplyDetails');

				foreach ($rate_reply_details as $rate_reply_detail) {
					$code = strtolower($rate_reply_detail->getElementsByTagName('ServiceType')->item(0)->nodeValue);

						if (!empty($this->config->get('shipping_fedex_service')) && is_array($this->config->get('shipping_fedex_service')) && in_array(strtoupper($code), $this->config->get('shipping_fedex_service'))) {
						$title = $this->language->get('text_' . $code);

						$delivery_time_stamp = $rate_reply_detail->getElementsByTagName('DeliveryTimestamp');

						if ($this->config->get('shipping_fedex_display_time') && $delivery_time_stamp->length) {
							$title .= ' (' . $this->language->get('text_eta') . ' ' . date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($delivery_time_stamp->item(0)->nodeValue)) . ')';
						}

						$rated_shipment_details = $rate_reply_detail->getElementsByTagName('RatedShipmentDetails');

						foreach ($rated_shipment_details as $rated_shipment_detail) {
							$shipment_rate_detail = $rated_shipment_detail->getElementsByTagName('ShipmentRateDetail')->item(0);
							$shipment_rate_detail_type = explode('_', $shipment_rate_detail->getElementsByTagName('RateType')->item(0)->nodeValue);

							if (count($shipment_rate_detail_type) == 3 && $shipment_rate_detail_type[1] == $this->config->get('shipping_fedex_rate_type')) {
								$total_net_charge = $shipment_rate_detail->getElementsByTagName('TotalNetCharge')->item(0);

								break;
							}
						}

						$cost = $total_net_charge->getElementsByTagName('Amount')->item(0)->nodeValue;

						$currency = $total_net_charge->getElementsByTagName('Currency')->item(0)->nodeValue;

						$quote_data[$code] = array(
							'code'         => 'fedex.' . $code,
							'title'        => $title,
							'cost'         => $this->currency->convert($cost, $currency, $this->config->get('config_currency')),
							'tax_class_id' => $this->config->get('shipping_fedex_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, $currency, $this->session->data['currency']), $this->config->get('shipping_fedex_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'], 1.0000000)
						);
					}
				}
			}
		}

		$method_data = array();

		if ($quote_data || $error) {
			$title = $this->language->get('text_title');

			if ($this->config->get('shipping_fedex_display_weight')) {
				$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('shipping_fedex_weight_class_id')) . ')';
			}

			$method_data = array(
				'code'       => 'fedex',
				'title'      => $title,
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('shipping_fedex_sort_order'),
				'error'      => $error
			);
		}

		return $method_data;
	}
}
