<?php
class ModelExtensionShippingSFExpress extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/sf_express');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('sf_express_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('sf_express_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('sf_express_weight_class_id'));
			$weight_code = strtolower($this->weight->getUnit($this->config->get('sf_express_weight_class_id')));

			$weight = ($weight < 0.1 ? 0.1 : $weight);

			$length = $this->length->convert($this->config->get('sf_express_length'), $this->config->get('config_length_class_id'), $this->config->get('sf_express_length_class_id'));
			$width = $this->length->convert($this->config->get('sf_express_width'), $this->config->get('config_length_class_id'), $this->config->get('sf_express_length_class_id'));
			$height = $this->length->convert($this->config->get('sf_express_height'), $this->config->get('config_length_class_id'), $this->config->get('sf_express_length_class_id'));

			$length_code = strtolower($this->length->getUnit($this->config->get('sf_express_length_class_id')));

			$address_from = array(
				'country'      => $this->config->get('sf_express_city'),
				'contact_name' => $this->config->get('config_owner'),
				'phone'        => $this->config->get('config_telephone'),
				'fax'          => $this->config->get('config_fax'),
				'email'        => $this->config->get('config_email'),
				'company_name' => $this->config->get('config_name'),
				'street1'      => '',
				'street2'      => '',
				'street3'      => '',
				'city'         => $this->config->get('sf_express_city'), 
				'state'        => $this->config->get('sf_express_state'),
				'postal_code'  => $this->config->get('sf_express_postcode'),
				'type'         => 'business',
				'tax_id'       => ''
			); 
			
			if ($address['company']) {
				$type = 'business';
			} else {
				$type = 'residential';
			}
		
			$address_to = array(
				'country'      => $address['iso_code_3'],
				'contact_name' => $address['firstname'] . ' ' . $address['lastname'],
				'phone'        => $address['iso_code_3'],
				'fax'          => $address['iso_code_3'],
				'email'        => $address['iso_code_3'],
				'company_name' => $address['company'],
				'street1'      => $address['address_1'],
				'street2'      => $address['address_2'],
				'street3'      => '',
				'city'         => $address['city'],
				'state'        => $address['iso_code_3'],
				'postal_code'  => $address['postcode'],
				'type'         => $type,
				'tax_id'       => $address['iso_code_3']
			);

			$payment_method = array(
				'type',
				'account_number',
				'postal_code',
				'country'
			);

			$billing = array(
				'paid_by' => 'shipper',
				'method'  => $payment_method
			);

			$customs = array(
				'purpose'          => 'merchandise',
				'terms_of_trade'   => 'ddp',
				'billing'          => $billing,
				'importer_address' => $address_to
			);

			


			$parcels = array();
			
			$weight = array(
				'unit'  => $weight_code,
				'value' => $weight
			);			
			
			$parcels[] = array(
				'box_type'    =>,
				'dimension'   =>,
				'items'       => array of item,
				'description' =>,
				'weight'      => $weight
			);

				Item

			
			


			$request = array(
				'ship_from' => $address_from,
				'ship_to'   => $address_to,
				'parcels'   => $parcel
			);




			$xml  = '<?xml version="1.0"?>';
			$xml .= '<AccessRequest xml:lang="en-US">';
			$xml .= '	<AccessLicenseNumber>' . $this->config->get('sf_express_key') . '</AccessLicenseNumber>';
			$xml .= '	<UserId>' . $this->config->get('sf_express_username') . '</UserId>';
			$xml .= '	<Password>' . $this->config->get('sf_express_password') . '</Password>';
			$xml .= '</AccessRequest>';
			$xml .= '<?xml version="1.0"?>';
			$xml .= '<RatingServiceSelectionRequest xml:lang="en-US">';
			$xml .= '	<Request>';
			$xml .= '		<TransactionReference>';
			$xml .= '			<CustomerContext>Bare Bones Rate Request</CustomerContext>';
			$xml .= '			<XpciVersion>1.0001</XpciVersion>';
			$xml .= '		</TransactionReference>';
			$xml .= '		<RequestAction>Rate</RequestAction>';
			$xml .= '		<RequestOption>shop</RequestOption>';
			$xml .= '	</Request>';
			$xml .= '   <PickupType>';
			$xml .= '       <Code>' . $this->config->get('sf_express_pickup') . '</Code>';
			$xml .= '   </PickupType>';

			if ($this->config->get('sf_express_country') == 'US' && $this->config->get('sf_express_pickup') == '11') {
				$xml .= '   <CustomerClassification>';
				$xml .= '       <Code>' . $this->config->get('sf_express_classification') . '</Code>';
				$xml .= '   </CustomerClassification>';
			}

			$xml .= '	<Shipment>';
			$xml .= '		<Shipper>';
			$xml .= '			<Address>';
			$xml .= '				<City>' . $this->config->get('sf_express_city') . '</City>';
			$xml .= '				<StateProvinceCode>' . $this->config->get('sf_express_state') . '</StateProvinceCode>';
			$xml .= '				<CountryCode>' . $this->config->get('sf_express_country') . '</CountryCode>';
			$xml .= '				<PostalCode>' . $this->config->get('sf_express_postcode') . '</PostalCode>';
			$xml .= '			</Address>';
			$xml .= '		</Shipper>';
			
			
			$xml .= '		<ShipTo>';
			$xml .= '			<Address>';
			$xml .= ' 				<City>' . $address['city'] . '</City>';
			$xml .= '				<StateProvinceCode>' . $address['zone_code'] . '</StateProvinceCode>';
			$xml .= '				<CountryCode>' . $address['iso_code_2'] . '</CountryCode>';
			$xml .= '				<PostalCode>' . $address['postcode'] . '</PostalCode>';

			if ($this->config->get('sf_express_quote_type') == 'residential') {
				$xml .= '				<ResidentialAddressIndicator />';
			}

			$xml .= '			</Address>';
			$xml .= '		</ShipTo>';
			$xml .= '		<ShipFrom>';
			$xml .= '			<Address>';
			$xml .= '				<City>' . $this->config->get('sf_express_city') . '</City>';
			$xml .= '				<StateProvinceCode>' . $this->config->get('sf_express_state') . '</StateProvinceCode>';
			$xml .= '				<CountryCode>' . $this->config->get('sf_express_country') . '</CountryCode>';
			$xml .= '				<PostalCode>' . $this->config->get('sf_express_postcode') . '</PostalCode>';
			$xml .= '			</Address>';
			$xml .= '		</ShipFrom>';

			$xml .= '		<Package>';
			$xml .= '			<PackagingType>';
			$xml .= '				<Code>' . $this->config->get('sf_express_packaging') . '</Code>';
			$xml .= '			</PackagingType>';

			$xml .= '		    <Dimensions>';
			$xml .= '				<UnitOfMeasurement>';
			$xml .= '					<Code>' . $length_code . '</Code>';
			$xml .= '				</UnitOfMeasurement>';
			$xml .= '				<Length>' . $length . '</Length>';
			$xml .= '				<Width>' . $width . '</Width>';
			$xml .= '				<Height>' . $height . '</Height>';
			$xml .= '			</Dimensions>';

			$xml .= '			<PackageWeight>';
			$xml .= '				<UnitOfMeasurement>';
			$xml .= '					<Code>' . $weight_code . '</Code>';
			$xml .= '				</UnitOfMeasurement>';
			$xml .= '				<Weight>' . $weight . '</Weight>';
			$xml .= '			</PackageWeight>';

			if ($this->config->get('sf_express_insurance')) {
				$xml .= '           <PackageServiceOptions>';
				$xml .= '               <InsuredValue>';
				$xml .= '                   <CurrencyCode>' . $this->session->data['currency'] . '</CurrencyCode>';
				$xml .= '                   <MonetaryValue>' . $this->currency->format($this->cart->getSubTotal(), $this->session->data['currency'], false, false) . '</MonetaryValue>';
				$xml .= '               </InsuredValue>';
				$xml .= '           </PackageServiceOptions>';
			}

			$xml .= '		</Package>';

			$xml .= '	</Shipment>';
			$xml .= '</RatingServiceSelectionRequest>';


			$headers = array(
				'content-type: application/json',
				'postmen-api-key: 8fc7966b-679b-4a57-911d-c5a663229c9e'
			);
			
			$curl = curl_init('https://sandbox-api.postmen.com/v3/labels/da1bf857-3c46-44a1-929e-ab644037f22f');

			curl_setopt($curl, CURLOPT_HEADER, $headers);
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

			$repsonse = curl_exec($curl);

			curl_close($curl);

			$json = json_decode($repsonse, true);

			if ($json) 
				$error = '';
	
				$quote_data = array();
	
				if ($this->config->get('sf_express_' . strtolower($this->config->get('sf_express_origin')) . '_' . $code)) {
					$quote_data[$code] = array(
						'code'         => 'ups.' . $code,
						'title'        => $service_code[$this->config->get('sf_express_origin')][$code],
						'cost'         => $this->currency->convert($cost, $currency, $this->config->get('config_currency')),
						'tax_class_id' => $this->config->get('sf_express_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, $currency, $this->session->data['currency']), $this->config->get('sf_express_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'], 1.0000000)
					);
				}
	
				$title = $this->language->get('text_title');
	
				if ($this->config->get('sf_express_display_weight')) {
					$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('sf_express_weight_class_id')) . ')';
				}
	
				if ($quote_data || $error) {
					$method_data = array(
						'code'       => 'ups',
						'title'      => $title,
						'quote'      => $quote_data,
						'sort_order' => $this->config->get('sf_express_sort_order'),
						'error'      => $error
					);
				}
			}
		}

		return $method_data;
	}
}
