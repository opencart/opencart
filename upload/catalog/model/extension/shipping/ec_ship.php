<?php
class ModelExtensionShippingECShip extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/ec_ship');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('shipping_ec_ship_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('shipping_ec_ship_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		//convert iso_code_3 to ec-ship country code
		$country_codes = array(
			'AFG'                            => 'AFA',
			'ALB'                            => 'ALA',
			'DZA'                            => 'DZA',
			'AND'                            => 'ADA',
			'AGO'                            => 'AOA',
			'AIA'                            => 'AIA',
			'ATG'                            => 'AGA',
			'ARG'                            => 'ARA',
			'ARM'                            => 'AMA',
			'ASC'                            => 'ACA',
			'ABW'                            => 'AWA',
			'AUT'                            => 'ATA',
			'AZE'                            => 'AZA',
			'BHS'                            => 'BSA',
			'BHR'                            => 'BHA',
			'BGD'                            => 'BDA',
			'BRB'                            => 'BBA',
			'BLR'                            => 'BYA',
			'BEL'                            => 'BEA',
			'BLZ'                            => 'BZA',
			'BEN'                            => 'BJA',
			'BMU'                            => 'BMA',
			'BTN'                            => 'BTA',
			'BOL'                            => 'BOA',
			'BIH'                            => 'BAA',
			'BWA'                            => 'BWA',
			'BRA'                            => 'BRA',
			'IOT'                            => 'IOA',
			'BRN'                            => 'BNA',
			'BGR'                            => 'BGA',
			'BFA'                            => 'BFA',
			'BDI'                            => 'BIA',
			'KHM'                            => 'KHA',
			'CMR'                            => 'CMA',
			'CAN'                            => 'CAA',
			'CPV'                            => 'CVA',
			'CYM'                            => 'KYA',
			'CAF'                            => 'CFA',
			'TCD'                            => 'TDA',
			'CHL'                            => 'CLA',
			'CXR'                            => 'CXA',
			'CCK'                            => 'CCA',
			'COL'                            => 'COA',
			'COM'                            => 'KMA',
			'COG'                            => 'CGA',
			'CRI'                            => 'CRA',
			'CIV'                            => 'CIA',
			'HRV'                            => 'HRA',
			'CUB'                            => 'CUA',
			'CYP'                            => 'CYA',
			'CZE'                            => 'CZA',
			'DNK'                            => 'DKA',
			'DJI'                            => 'DJA',
			'DMA'                            => 'DMA',
			'DOM'                            => 'DOA',
			'TLS'                            => 'TPA',
			'ECU'                            => 'ECA',
			'EGY'                            => 'EGA',
			'SLV'                            => 'SVA',
			'GNQ'                            => 'GQA',
			'ERI'                            => 'ERA',
			'EST'                            => 'EEA',
			'ETH'                            => 'ETA',
			'FLK'                            => 'FKA',
			'FRO'                            => 'FOA',
			'FJI'                            => 'FJA',
			'FIN'                            => 'FIA',
			'FRA'                            => 'FRA',
			'GUF'                            => 'GFA',
			'PYF'                            => 'PFA',
			'GAB'                            => 'GAA',
			'GMB'                            => 'GMA',
			'GEO'                            => 'GEA',
			'DEU'                            => 'DEA',
			'GHA'                            => 'GHA',
			'GIB'                            => 'GIA',
			'GRC'                            => 'GRA',
			'GRL'                            => 'GLA',
			'GRD'                            => 'GDA',
			'GUM'                            => 'GUA',
			'GTM'                            => 'GTA',
			'GIN'                            => 'GNA',
			'GNB'                            => 'GWA',
			'GUY'                            => 'GYA',
			'HTI'                            => 'HTA',
			'HND'                            => 'HNA',
			'HKG'							 => 'HKG',
			'HUN'                            => 'HUA',
			'ISL'                            => 'ISA',
			'IND'                            => 'INA',
			'IDN'                            => 'IDA',
			'IRN'                            => 'IRA',
			'IRQ'                            => 'IQA',
			'IRL'                            => 'IEA',
			'ISR'                            => 'ILA',
			'ITA'                            => 'ITA',
			'JAM'                            => 'JMA',
			'JPN'                            => 'JPA',
			'JOR'                            => 'JOA',
			'KAZ'                            => 'KZA',
			'KEN'                            => 'KEA',
			'KIR'                            => 'KIA',
			'PRK'                            => 'KPA',
			'KOR'                            => 'KRA',
			'KWT'                            => 'KWA',
			'KGZ'                            => 'KGA',
			'LAO'                            => 'LAA',
			'LVA'                            => 'LVA',
			'LBN'                            => 'LBA',
			'LSO'                            => 'LSA',
			'LBR'                            => 'LRA',
			'LBY'                            => 'LYA',
			'LIE'                            => 'LIA',
			'LTU'                            => 'LTA',
			'LUX'                            => 'LUA',
			'MAC'                            => 'MOA',
			'MDG'                            => 'MGA',
			'MWI'                            => 'MWA',
			'MDV'                            => 'MVA',
			'MLI'                            => 'MLA',
			'MLT'                            => 'MTA',
			'MHL'                            => 'MHA',
			'MRT'                            => 'MRA',
			'MUS'                            => 'MUA',
			'MEX'                            => 'MXA',
			'MDA'                            => 'MDA',
			'MCO'                            => 'MCA',
			'MNG'                            => 'MNA',
			'MNE'                            => 'MEA',
			'MSR'                            => 'MSA',
			'MAR'                            => 'MAA',
			'MOZ'                            => 'MZA',
			'MMR'                            => 'MMA',
			'NAM'                            => 'NAA',
			'NRU'                            => 'NRA',
			'NPL'                            => 'NPA',
			'NLD'                            => 'NLA',
			'ANT' 				             => 'ANA',
			'NCL'                            => 'NCA',
			'NZL'                            => 'NZA',
			'NIC'                            => 'NIA',
			'NER'                            => 'NEA',
			'NGA'                            => 'NGA',
			'NFK'                            => 'NFA',
			'NOR'                            => 'NOA',
			'OMN'                            => 'OMA',
			'PAK'                            => 'PKA',
			'PAN'                            => 'PAA',
			'PNG'                            => 'PGA',
			'PRY'                            => 'PYA',
			'PER'                            => 'PEA',
			'PHL'                            => 'PHA',
			'PCN'                            => 'PNA',
			'POL'                            => 'PLA',
			'PRT'                            => 'PTA',
			'PRI'                            => 'PRA',
			'QAT'                            => 'QAA',
			'REU'                            => 'REA',
			'ROM'                            => 'ROA',
			'RUS'                            => 'RUA',
			'RWA'                            => 'RWA',
			'ASM'                            => 'ASA',
			'SMR'                            => 'SMA',
			'STP'                            => 'STA',
			'SAU'                            => 'SAA',
			'SEN'                            => 'SNA',
			'SRB'                            => 'RSA',
			'SYC'                            => 'SCA',
			'SLE'                            => 'SLA',
			'SGP'                            => 'SGA',
			'SVK'                            => 'SKA',
			'SVN'                            => 'SIA',
			'SLB'                            => 'SBA',
			'SOM'                            => 'SOA',
			'ZAF'                            => 'ZAA',
			'ESP'                            => 'ESA',
			'LKA'                            => 'LKA',
			'KNA'                            => 'KNA',
			'SHN'                            => 'SHA',
			'LCA'                            => 'LCA',
			'SPM'                            => 'PMA',
			'VCT'                            => 'VCA',
			'SDN'                            => 'SDA',
			'SUR'                            => 'SRA',
			'SWZ'                            => 'SZA',
			'SWE'                            => 'SEA',
			'CHE'                            => 'CHA',
			'SYR'                            => 'SYA',
			'TWN'                            => 'TWA',
			'TJK'                            => 'TJA',
			'TZA'                            => 'TZA',
			'THA'                            => 'THA',
			'TGO'                            => 'TGA',
			'TON'                            => 'TOA',
			'TTO'                            => 'TTA',
			'SHN'                            => 'TAA',
			'TUN'                            => 'TNA',
			'TUR'                            => 'TRA',
			'TKM'                            => 'TMA',
			'TCA'                            => 'TCA',
			'TUV'                            => 'TVA',
			'UGA'                            => 'UGA',
			'UKR'                            => 'UAA',
			'ARE'                            => 'AEA',
			'GBR'                            => 'GBA',
			'URY'                            => 'UYA',
			'UZB'                            => 'UZA',
			'VUT'                            => 'VUA',
			'VAT'                            => 'VAA',
			'VEN'                            => 'VEA',
			'VNM'                            => 'VNA',
			'VIA'                            => 'VIA',
			'WLF'                            => 'WFA',
			'YEM '                           => 'YEA',
			'ZMB'                            => 'ZMA',
			'ZWE'                            => 'ZWA',
			'AUS'                  => array(
				'WA'                         => 'AUA',
				'OTHERS'                     => 'AUB',
			),
			'CHN'                  => array(
				'BE'                         => 'CNA',
				'FU'                         => 'CNB',
				'GU'                         => 'CNC',
				'ZH'                         => 'CND',
				'YU'                         => 'CNE',
				'SG'                         => 'CNF',
				'OTHERS'                     => 'CNG',
				'TI'                         => 'CNH',
				'FU'                         => 'CNJ',
			),
			'MYS'                  => array(
				'OTHERS'                     => 'MYA',
				'MY-12'                      => 'MYB',
				'MY-13'                      => 'MYC',
			),
			'USA'                  => array(
				'HI'                         => 'USA',
				'NY'                         => 'USB',
				'OTHERS'                     => 'USC',
			)
 		);

		$service = array(
			'ARM' => $this->config->get('shipping_ec_ship_air_registered_mail'),
			'APL' => $this->config->get('shipping_ec_ship_air_parcel'),
			'AEP' => $this->config->get('shipping_ec_ship_e_express_service_to_us'),
			'AEC' => $this->config->get('shipping_ec_ship_e_express_service_to_canada'),
			'AEG' => $this->config->get('shipping_ec_ship_e_express_service_to_united_kingdom'),
			'AER' => $this->config->get('shipping_ec_ship_e_express_service_to_russia'),
			'AE1' => $this->config->get('shipping_ec_ship_e_express_service_one'),
			'AE2' => $this->config->get('shipping_ec_ship_e_express_service_two'),
			'EMS' => $this->config->get('shipping_ec_ship_speed_post'),
			'SMP' => $this->config->get('shipping_ec_ship_smart_post'),
			'LCP' => $this->config->get('shipping_ec_ship_local_courier_post'),
			'LPL' => $this->config->get('shipping_ec_ship_local_parcel')
		);

		//Countries available service
		$shipCode = array(
			'AUS' => array(
				'AE2' => $this->language->get('text_e_express_service_two'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'NZL' => array(
				'AE2' => $this->language->get('text_e_express_service_two'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'KOR' => array(
				'AE2' => $this->language->get('text_e_express_service_two'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'SGP' => array(
				'AE2' => $this->language->get('text_e_express_service_two'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'VNM' => array(
				'AE2' => $this->language->get('text_e_express_service_two'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'DEU' => array(
				'AE1' => $this->language->get('text_e_express_service_one'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'FRA' => array(
				'AE1' => $this->language->get('text_e_express_service_one'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'NOR' => array(
				'AE1' => $this->language->get('text_e_express_service_one'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'RUS' => array(
				'AER' => $this->language->get('text_e_express_service_to_russia'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'GBR' => array(
				'AEG' => $this->language->get('text_e_express_service_to_united_kingdom'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'CAN' => array(
				'AEC' => $this->language->get('text_e_express_service_to_canada'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'USA' => array(
				'AEP' => $this->language->get('text_e_express_service_to_us'),
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			),

			'HKG' => array(
				'SMP' => $this->language->get('text_smart_post'),
				'LCP' => $this->language->get('text_local_courier_post'),
				'LPL' => $this->language->get('text_local_parcel')
			),

			'OTHERS' => array(
				'ARM' => $this->language->get('text_air_registered_mail'),
				'APL' => $this->language->get('text_air_parcel'),
				'EMS' => $this->language->get('text_speed_post')
			)
		);

		$method_data = array();
		$error = '';

		if ($status) {
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->config->get('shipping_ec_ship_weight_class_id'));
			$weight_code = strtolower($this->weight->getUnit($this->config->get('shipping_ec_ship_weight_class_id')));

			$weight = ($weight < 0.1 ? 0.1 : $weight);

			$address_from = array(
				'country'      => "HKG",
				'contact_name' => $this->config->get('config_owner'),
				'phone'        => $this->config->get('config_telephone'),
				'email'        => $this->config->get('config_email'),
				'company_name' => $this->config->get('config_name')
			);

			$address_to = array(
				'country'	   => '',
				'contact_name' => $address['firstname'] . ' ' . $address['lastname'],
				'company_name' => $address['company'],
				'code'         => ''
			);

			foreach ($shipCode as $key => $value) {
				if ($address['iso_code_3'] == $key) {
					$address_to['code'] = $shipCode[$key];
					break;
				} else {
					$address_to['code'] = $shipCode['OTHERS'];
				}
			}

			foreach ($service as $key => $value) {
				if (!isset($service[$key])) {
					unset($address_to['code'][$key]);
				}
			}

			if (isset($country_codes[$address['iso_code_3']]) && is_array($country_codes[$address['iso_code_3']])) {
				foreach ($country_codes[$address['iso_code_3']] as $key => $value) {
					if ($address['zone_code'] == $key) {
						$address_to['country'] = $country_codes[$address['iso_code_3']][$address['zone_code']];
						break;
					} else {
						$address_to['country'] = $country_codes[$address['iso_code_3']]['OTHERS'];
					}
				}
			} elseif (isset($country_codes[$address['iso_code_3']])) {
				$address_to['country'] = $country_codes[$address['iso_code_3']];
			} else {
				$error = $this->language->get('text_unavailable');
			}

			if (!$this->config->get('shipping_ec_ship_test')) {
				$url = 'https://www.ec-ship.hk/API/services/Calculator?wsdl';
			} else {
				$url = 'http://www.ec-ship.hk/API-trial/services/Calculator?wsdl';
			}

			// Creating date using yyyy-mm-ddThh:mm:ssZ format
			$tm_created = gmdate('Y-m-d\TH:i:s\Z');
			$tm_expires = gmdate('Y-m-d\TH:i:s\Z', gmdate('U') + 180);


			// Generating, packing and encoding a random number
			$simple_nonce = mt_rand();
			$encoded_nonce = base64_encode(pack('H*', $simple_nonce));


			$username   = $this->config->get('shipping_ec_ship_api_username');
			$password   = $this->config->get('shipping_ec_ship_api_key');
			$passdigest = base64_encode(pack('H*',sha1(pack('H*', $simple_nonce) . pack('a*', $tm_created) . pack('a*', $password))));


			// Initializing namespaces
			$ns_wsse       = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
			$ns_wsu        = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
			$password_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest';
			$encoding_type = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary';

			// Creating WSS identification header using SimpleXML
			$root = new SimpleXMLElement('<root/>');

			$security = $root->addChild('wsse:Security', null, $ns_wsse);

			$usernameToken = $security->addChild('wsse:UsernameToken', null, $ns_wsse);
			$usernameToken->addChild('wsse:Username', $username, $ns_wsse);
			$usernameToken->addChild('wsse:Password', $passdigest, $ns_wsse)->addAttribute('Type', $password_type);
			$usernameToken->addChild('wsse:Nonce', $encoded_nonce, $ns_wsse)->addAttribute('EncodingType', $encoding_type);
			$usernameToken->addChild('wsu:Created', $tm_created, $ns_wsu);

			// Recovering XML value from that object
			$root->registerXPathNamespace('wsse', $ns_wsse);
			$full = $root->xpath('/root/wsse:Security');
			$auth = $full[0]->asXML();

			$objSoapVarWSSEHeader = new SoapHeader($ns_wsse, 'Security', new SoapVar($auth, XSD_ANYXML), true);

			$objClient = new SoapClient($url);

			$objClient->__setSoapHeaders(array($objSoapVarWSSEHeader));

			$request = array(
				'ecshipUsername'     => $this->config->get('shipping_ec_ship_username'),
				'integratorUsername' => $this->config->get('shipping_ec_ship_api_username'),
				'countryCode'        => $address_to['country'],
			    'shipCode' 			 => '',
			    'weight'			 => $weight
			);

			$objResponseArray = array();

			foreach ($address_to['code'] as $key => $value) {
				$api01Req = new api01Req($request['ecshipUsername'], $request['integratorUsername'], $request['countryCode'], $key, $request['weight']);
				$params = array("api01Req" => $api01Req);
				$objResponse = $objClient->__soapCall("getTotalPostage", array($params));
				$objResponse = json_decode(json_encode($objResponse), true);
				$objResponse['getTotalPostageReturn']['serviceName'] = $value;
				array_push($objResponseArray, $objResponse);
			}

			if ($objResponseArray){
				$code = 'ec_ship';
				$quote_data = array();

				foreach ($objResponseArray as $key => $value) {
					if ($value['getTotalPostageReturn']['status'] == 0) {
						$quote_data[$key] = array(
							'code'         => 'ec_ship.' . $key,
							'title'        => $value['getTotalPostageReturn']['serviceName'],
							'cost'         => $value['getTotalPostageReturn']['totalPostage'],
							'tax_class_id' => $this->config->get('shipping_ec_ship_tax_class_id'),
							'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($value['getTotalPostageReturn']['totalPostage'], 'HKD', $this->session->data['currency']), $this->config->get('shipping_ec_ship_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'], 1.0000000)
						);
					}
				}
			}
			if ($quote_data || $error) {
				$method_data = array(
					'code'       => 'ec_ship',
					'title'      => $this->language->get('text_title'),
					'quote'      => $quote_data,
					'sort_order' => $this->config->get('shipping_ec_ship_sort_order'),
					'error'      => $error
				);
			}
		}
		return $method_data;
	}
}

class api01Req {
	private $ecshipUsername;
	private $integratorUsername;
	private $countryCode;
	private $shipCode;
	private $weight;

    function __construct($ecshipUsername, $integratorUsername, $countryCode, $shipCode, $weight) {
		$this->ecshipUsername 		= $ecshipUsername;
		$this->integratorUsername   = $integratorUsername;
		$this->countryCode 			= $countryCode;
		$this->shipCode			    = $shipCode;
		$this->weight			    = $weight;
    }
}
