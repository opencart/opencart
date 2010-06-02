<?php
class ModelShippingUsps extends Model {
	public function getQuote($address) {
		$this->load->language('shipping/usps');
		
		if ($this->config->get('usps_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('usps_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
      		if (!$this->config->get('usps_geo_zone_id')) {
        		$status = TRUE;
      		} elseif ($query->num_rows) {
        		$status = TRUE;
      		} else {
        		$status = FALSE;
      		}
		} else {
			$status = FALSE;
		}
	  
		$method_data = array();
	
		if ($status) {
			$this->load->model('localisation/country');

			$quote_data = array();
			
			$weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class'), $this->config->get('usps_weight_class'));
			
			$weight = ($weight < 0.1 ? 0.1 : $weight);
			$pounds = floor($weight);
			$ounces = round(16 * ($weight - floor($weight)));
			
			$postcode = str_replace(' ', '', $address['postcode']);
			
			if ($address['iso_code_2'] == 'US') { 
				$xml  = '<RateV3Request USERID="' . $this->config->get('usps_user_id') . '" PASSWORD="' . $this->config->get('usps_password') . '">';
				$xml .= '	<Package ID="1">';
				$xml .=	'		<Service>ALL</Service>';
				$xml .=	'		<ZipOrigination>' . substr($this->config->get('usps_postcode'), 0, 5) . '</ZipOrigination>';
				$xml .=	'		<ZipDestination>' . substr($postcode, 0, 5) . '</ZipDestination>';
				$xml .=	'		<Pounds>' . $pounds . '</Pounds>';
				$xml .=	'		<Ounces>' . $ounces . '</Ounces>';				
				
				if ($this->config->get('usps_size') == 'LARGE') {
					$xml .=	'	<Container>' . $this->config->get('usps_container') . '</Container>';
					$xml .=	'	<Size>' . $this->config->get('usps_size') . '</Size>';
					$xml .= '	<Width>' . $this->config->get('usps_width') . '</Width>';
					$xml .= '	<Length>' . $this->config->get('usps_length') . '</Length>';
					$xml .= '	<Height>' . $this->config->get('usps_height') . '</Height>';
					$xml .= '	<Girth>' . $this->config->get('usps_girth') . '</Girth>';
				} else {
					$xml .=	'	<Container>' . $this->config->get('usps_container') . '</Container>';
					$xml .=	'	<Size>' . $this->config->get('usps_size') . '</Size>';
				}
				
				$xml .=	'		<Machinable>' . ($this->config->get('usps_machinable') ? 'True' : 'False') . '</Machinable>';
				$xml .=	'	</Package>';
				$xml .= '</RateV3Request>';
		
				$request = 'API=RateV3&XML=' . urlencode($xml);
			} else {				
      			$country = array(
					'AF' => 'Afghanistan',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BA' => 'Bosnia-Herzegovina',
                    'BW' => 'Botswana',
                    'BR' => 'Brazil',
                    'VG' => 'British Virgin Islands',
                    'BN' => 'Brunei Darussalam',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'MM' => 'Burma',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island (Australia)',
                    'CC' => 'Cocos Island (Australia)',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros',
                    'CG' => 'Congo (Brazzaville),Republic of the',
                    'ZR' => 'Congo, Democratic Republic of the',
                    'CK' => 'Cook Islands (New Zealand)',
                    'CR' => 'Costa Rica',
                    'CI' => 'Cote d\'Ivoire (Ivory Coast)',
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'TP' => 'East Timor (Indonesia)',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FK' => 'Falkland Islands',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia, Republic of',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GB' => 'Great Britain and Northern Ireland',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GT' => 'Guatemala',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => 'Laos',
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'Macedonia, Republic of',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte (France)',
                    'MX' => 'Mexico',
                    'MD' => 'Moldova',
                    'MC' => 'Monaco (France)',
                    'MN' => 'Mongolia',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'AN' => 'Netherlands Antilles',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn Island',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'QA' => 'Qatar',
                    'RE' => 'Reunion',
                    'RO' => 'Romania',
                    'RU' => 'Russia',
                    'RW' => 'Rwanda',
                    'SH' => 'Saint Helena',
                    'KN' => 'Saint Kitts (St. Christopher and Nevis)',
                    'LC' => 'Saint Lucia',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'YU' => 'Serbia-Montenegro',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SK' => 'Slovak Republic',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia (Falkland Islands)',
                    'KR' => 'South Korea (Korea, Republic of)',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania',
                    'TH' => 'Thailand',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau (Union) Group (Western Samoa)',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VA' => 'Vatican City',
                    'VE' => 'Venezuela',
                    'VN' => 'Vietnam',
                    'WF' => 'Wallis and Futuna Islands',
                    'WS' => 'Western Samoa',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe'
				);
	  			
				if (isset($country[$address['iso_code_2']])) {
					$xml  = '<IntlRateRequest USERID="' . $this->config->get('usps_user_id') . '" PASSWORD="' . $this->config->get('usps_password') . '">';
					$xml .=	'	<Package ID="0">';
					$xml .=	'		<Pounds>' . $pounds . '</Pounds>';
					$xml .=	'		<Ounces>' . $ounces . '</Ounces>';
					$xml .=	'		<MailType>Package</MailType>';
					$xml .=	'		<Country>' . $country[$address['iso_code_2']] . '</Country>';
					$xml .=	'	</Package>';
					$xml .=	'</IntlRateRequest>';
		
					$request = 'API=IntlRate&XML=' . urlencode($xml);
				} else {
					$status = FALSE;	
				}
			}	
			
			if ($status) {
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, 'production.shippingapis.com/ShippingAPI.dll?' . $request);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
				$result = curl_exec($ch);
				
				curl_close($ch);  
				
				if ($result) {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($result);	
					
					$rate_v3_response = $dom->getElementsByTagName('RateV3Response')->item(0);
					$intl_rate_response = $dom->getElementsByTagName('IntlRateResponse')->item(0);
					$error = $dom->getElementsByTagName('Error')->item(0);
					
					if ($rate_v3_response || $intl_rate_response) {
						if ($address['iso_code_2'] == 'US') { 
							$allowed = array(0, 1, 2, 3, 4, 5, 6, 7, 12, 13, 16, 17, 18, 19, 22, 23, 25, 27, 28);
							
							$package = $rate_v3_response->getElementsByTagName('Package')->item(0);
							
							$postages = $package->getElementsByTagName('Postage');
							
							foreach ($postages as $postage) {
								$classid = $postage->getAttribute('CLASSID');
								
								if (in_array($classid, $allowed) && $this->config->get('usps_domestic_' . $classid)) {
			
									$cost = $postage->getElementsByTagName('Rate')->item(0)->nodeValue;
									
									$quote_data[$classid] = array(
										'id'           => 'usps.' . $classid,
										'title'        => $postage->getElementsByTagName('MailService')->item(0)->nodeValue,
										'cost'         => $this->currency->convert($cost, 'USD', $this->currency->getCode()),
										'tax_class_id' => 0,
										'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, 'USD', $this->currency->getCode()), $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
									);							
								}
							} 
						} else {
							$allowed = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 21);
							
							$package = $intl_rate_response->getElementsByTagName('Package')->item(0);
							
							$services = $package->getElementsByTagName('Service');
							
							foreach ($services as $service) {
								$id = $service->getAttribute('ID');
								
								if (in_array($id, $allowed) && $this->config->get('usps_international_' . $id)) {
									$title = $service->getElementsByTagName('SvcDescription')->item(0)->nodeValue;
									
									if ($this->config->get('usps_display_weight')) {	  
										$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
									}
						
									if ($this->config->get('usps_display_time')) {	  
										$title .= ' (' . $this->language->get('text_eta') . ' ' . $service->getElementsByTagName('SvcCommitments')->item(0)->nodeValue . ')';
									}
									
									$cost = $service->getElementsByTagName('Postage')->item(0)->nodeValue;
									
									$quote_data[$id] = array(
										'id'           => 'usps.' . $id,
										'title'        => $title,
										'cost'         => $this->currency->convert($cost, 'USD', $this->currency->getCode()),
										'tax_class_id' => $this->config->get('usps_tax_class_id'),
										'text'         => $this->currency->format($this->tax->calculate($this->currency->convert($cost, 'USD', $this->currency->getCode()), $this->config->get('ups_tax_class_id'), $this->config->get('config_tax')))
									);							
								}
							}
						}
					} elseif ($error) {
						$method_data = array(
							'id'         => 'usps',
							'title'      => $this->language->get('text_title'),
							'quote'      => $quote_data,
							'sort_order' => $this->config->get('usps_sort_order'),
							'error'      => $error->getElementsByTagName('Description')->item(0)->nodeValue
						);					
					}
				}
			}
			
	  		if ($quote_data) {
				
				$title = $this->language->get('text_title');
									
				if ($this->config->get('usps_display_weight')) {	  
					$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class')) . ')';
				}		
			
      			$method_data = array(
        			'id'         => 'usps',
        			'title'      => $title,
        			'quote'      => $quote_data,
					'sort_order' => $this->config->get('usps_sort_order'),
        			'error'      => FALSE
      			);
			}
		}
	
		return $method_data;
	}	
}
?>