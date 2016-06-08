<?php
/*
List of Royal Mail support Countries

http://www.royalmail.com/sites/default/files/RoyalMail_International_TrackedCoverage_Jan2014.pdf

Afghanistan = AF
Aland Islands = NA
Albania = AL
Algeria = DZ
Andorra = AD
Angola = AO
Anguilla = AI
Antigua / Barbuda = AG
Argentina = AR
Armenia = AM
Aruba = AW
Ascension Island = XA
Australia = AU
Austria = AT
Azerbaijan = AZ
Bahamas = BS
Bahrain = BH
Bangladesh = BD
Barbados = BB
Belarus = BY
Belgium = BE
Belize = BZ
Benin = BJ
Bermuda = BM
Bhutan = BT
Bolivia = BO
Bonaire = BX
Bosnia Hertzegovina = BA
Botswana = BW
Brazil = BR
British Indian Ocean Territory = IO
British Virgin Islands = VG
Brunei = BN
Bulgaria = BG
Burkina Faso = BF
Burundi = BI
Cambodia = KH
Cameroon = CM
Canada = CA
Canary Islands = XC
Cape Verde = CV
Cayman Islands = KY
Central African Republic = CF
Ceuta = CE
Chad = TD
Chile = CL
China (People's Republic of) = CN
Christmas Island (Indian Ocean) = CX
Christmas Island (Pacific Ocean) = CX
Colombia = CO
Comoros Islands = KM
Congo ( Dem. Rep of) = CD
Congo ( Rep of) = CG
Cook Islands = CK
Costa Rica = CR
Croatia = HR
Cuba = CU
Curacao = CB
Cyprus = CY
Czech Republic = CZ
Denmark = DK
Djibouti = DJ
Dominica = DM
Dominican Republic = DO
Ecuador = EC
Egypt = EG
El Salvador = SV
Equatorial Guinea = GQ
Eritrea = ER
Estonia = EE
Ethiopia = ET
Falkland Islands = FK
Faroe Islands = FO
Fiji = FJ
Finland = FI
France = FR
French Guiana = GF
French Polynesia = PF
French South Antarctic Territory = TF
Gabon = GA
Gambia = GM
Georgia = GE
Germany = DE
Ghana = GH
Gibraltar = GI
Greece = GR
Greenland = GL
Grenada = GD
Guadeloupe = GP
Guatemala = GT
Guinea = GN
Guinea-Bissau = GW
Guyana = GY
Haiti = HT
Honduras = HN
Hong Kong = HK
Hungary = HU
Iceland = IS
India = IN
Indonesia = ID
Iran (Islamic Republic of) = IR
Iraq = IQ
Ireland (Republic of) = IE
Israel = IL
Italy = IT
Ivory Coast (Cote D'Ivoire) = CI
Jamaica = JM
Japan = JP
Jordan = JO
Kazakhstan = KZ
Kenya = KE
Kiribati = KI
Kosovo = KV
Kuwait = KW
Kyrgyzstan = KG
Laos (People's Democratic Republic of) = LA
Latvia = LV
Lebanon = LB
Lesotho = LS
Liberia = LR
Libya = LY
Liechtenstein = LI
Lithuania = LT
Luxembourg = LU
Macau = MO
Macedonia = MK
Madagascar = MG
Mahore (also known as Mayotte) = YT
Malawi = MW
Malaysia = MY
Maldives = MV
Mali = ML
Malta = MT
Martinique = MQ
Mauritania = MR
Mauritius = MU
Melilla = XL
Mexico = MX
Moldova = MD
Mongolia = MN
Montenegro = CS
Montserrat = MS
Morocco = MA
Mozambique = MZ
Myanmar = MM
Namibia = NA
Nauru Island = NR
Nepal = NP
Netherlands = NL
New Caledonia = NC
New Zealand = NZ
Nicaragua = NI
Niger Republic = NE
Nigeria = NG
Niue Island = NU
North Korea (People's Democratic Republic of ) = KP
Norway = NO
Oman = OM
Pakistan = PK
Palau (known also as Belau) = PW
Panama = PA
Papua New Guinea = PG
Paraguay = PY
Peru = PE
Philippines = PH
Pitcairn Island = PN
Poland = PL
Portugal = PT
Puerto Rico = PR
Qatar = QA
Reunion Island = RE
Romania = RO
Russian Federation = RU
Rwanda = RW
San Marino = SM
Sao Tome & Principe = ST
Saudi Arabia = SA
Senegal = SN
Serbia = CS
Seychelles = SC
Sierra Leone = SL
Singapore = SG
Slovak Republic = SK
Slovenia = SI
Solomon Islands = SB
Somalia = SO
South Africa ( Republic of) = ZA
South Korea (Republic of) = KR
South Sudan = SD
Spain = ES
Sri Lanka = LK
St Eustatius = SX
St Helena = SH
St Kitts & Nevis = KN
St Lucia = LC
St Maarten = SF
St Vincent & The Grenadines = VC
Sudan = SD
Suriname = SR
Swaziland = SZ
Sweden = SE
Switzerland = CH
Syria = SY
Taiwan = TW
Tajikistan = TJ
Tanzania = TZ
Thailand = TH
Timor-Leste = TP
Togo = TG
Tokelau Islands = TK
Tonga = TO
Trinidad & Tobago = TT
Tristan de Cunha = XT
Tunisia = TN
Turkey = TR
Turkish (Republic of Northern Cyprus) = CP
Turkmenistan = TM
Turks & Caicos Islands = TC
Tuvalu = TV
Uganda = UG
Ukraine = UA
United Arab Emirates = AE
Uruguay = UY
USA = US
Uzbekistan = UZ
Vanuatu = VU
Vatican City State = VA
Venezuela = VE
Vietnam = VN
Wallis & Futuna Islands = WF
Western Sahara = EH
Western Samoa = WS
Yemen, Republic of = YE
Zambia = ZM
Zimbabwe = ZW
*/
class ModelExtensionShippingRoyalMail extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/royal_mail');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('royal_mail_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('royal_mail_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$quote_data = array();

		if ($status) {
			$weight = $this->cart->getWeight();
			$sub_total = $this->cart->getSubTotal();

			// Special Delivery > 500
			if ($this->config->get('royal_mail_special_delivery_500_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_500_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(500, $this->session->data['currency']) . ')';
					}

					$quote_data['special_delivery_500'] = array(
						'code'         => 'royal_mail.special_delivery_500',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// Special Delivery > 1000
			if ($this->config->get('royal_mail_special_delivery_1000_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_1000_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(1000, $this->session->data['currency']) . ')';

					}

					$quote_data['special_delivery_1000'] = array(
						'code'         => 'royal_mail.special_delivery_1000',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// Special Delivery > 2500
			if ($this->config->get('royal_mail_special_delivery_2500_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;
				$insurance = 0;

				$rates = explode(',', $this->config->get('royal_mail_special_delivery_2500_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_special_delivery');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					if ($this->config->get('royal_mail_display_insurance')) {
						$title .= ' (' . $this->language->get('text_insurance') . ' ' . $this->currency->format(2500, $this->session->data['currency']) . ')';
					}

					$quote_data['special_delivery_2500'] = array(
						'code'         => 'royal_mail.special_delivery_2500',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// 1st Class Signed
			if ($this->config->get('royal_mail_1st_class_signed_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_1st_class_signed_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['1st_class_signed'] = array(
						'code'         => 'royal_mail.1st_class_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// 2nd Class Signed
			if ($this->config->get('royal_mail_2nd_class_signed_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_2nd_class_signed_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_2nd_class_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['2nd_class_signed'] = array(
						'code'         => 'royal_mail.2nd_class_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// 1st Class Standard
			if ($this->config->get('royal_mail_1st_class_standard_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_1st_class_standard_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_1st_class_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['1st_class_standard'] = array(
						'code'         => 'royal_mail.1st_class_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// 2nd Class Standard
			if ($this->config->get('royal_mail_2nd_class_standard_status') && $address['iso_code_2'] == 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_2nd_class_standard_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_2nd_class_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['2nd_class_standard'] = array(
						'code'         => 'royal_mail.2nd_class_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			$europe = array(
				'AL',
				'AD',
				'AM',
				'AT',
				'AZ',
				'BY',
				'BE',
				'BG',
				'XC',
				'HR',
				'CY',
				'CZ',
				'DK',
				'EE',
				'FO',
				'FI',
				'FR',
				'GE',
				'DE',
				'GI',
				'GR',
				'GL',
				'HU',
				'IS',
				'IE',
				'IT',
				'KZ',
				'KV',
				'KG',
				'LV',
				'LI',
				'LT',
				'LU',
				'MK',
				'MT',
				'MD',
				'CS',
				'NL',
				'NO',
				'PL',
				'PT',
				'RO',
				'RU',
				'SM',
				'CS',
				'SK',
				'SI',
				'ES',
				'SE',
				'CH',
				'TJ',
				'TR',
				'TM',
				'UA',
				'UZ',
				'VA'
			);

			$non_eu = array(
				'AL',
				'AD',
				'AM',
				'AZ',
				'BY',
				'XC',
				'FO',
				'GE',
				'GL',
				'IS',
				'KZ',
				'KV',
				'KG',
				'LI',
				'MK',
				'MD',
				'CS',
				'NO',
				'RU',
				'SM',
				'CS',
				'CH',
				'TJ',
				'TR',
				'TM',
				'UA',
				'UZ',
				'VA'
			);

			$zone_1 = array(
				'AF',
				'NA',
				'DZ',
				'AO',
				'AI',
				'AG',
				'AR',
				'AW',
				'XA',
				'BS',
				'BH',
				'BD',
				'BB',
				'BZ',
				'BJ',
				'BM',
				'BT',
				'BO',
				'BX',
				'BW',
				'BR',
				'VG',
				'BN',
				'BF',
				'BI',
				'KH',
				'CM',
				'CA',
				'CV',
				'KY',
				'CF',
				'CE',
				'TD',
				'CL',
				'CN',
				'CO',
				'KM',
				'CD',
				'CG',
				'CR',
				'CU',
				'CB',
				'DJ',
				'DM',
				'DO',
				'EC',
				'EG',
				'SV',
				'GQ',
				'ER',
				'ET',
				'FK',
				'GF',
				'GA',
				'GM',
				'GH',
				'GD',
				'GP',
				'GT',
				'GN',
				'GW',
				'GY',
				'HT',
				'HN',
				'HK',
				'IN',
				'ID',
				'IR',
				'IQ',
				'IL',
				'CI',
				'JM',
				'JP',
				'JO',
				'KE',
				'KW',
				'LB',
				'LS',
				'LR',
				'LY',
				'MG',
				'YT',
				'MW',
				'MY',
				'MV',
				'ML',
				'MQ',
				'MR',
				'MU',
				'XL',
				'MX',
				'MN',
				'MS',
				'MA',
				'MZ',
				'MM',
				'NA',
				'NP',
				'NI',
				'NE',
				'NG',
				'KP',
				'OM',
				'PK',
				'PA',
				'PY',
				'PE',
				'PH',
				'PR',
				'QA',
				'RE',
				'RW',
				'ST',
				'SA',
				'SN',
				'SC',
				'SL',
				'SO',
				'ZA',
				'KR',
				'SD',
				'LK',
				'SX',
				'SH',
				'KN',
				'LC',
				'SF',
				'VC',
				'SD',
				'SR',
				'SZ',
				'SY',
				'TW',
				'TZ',
				'TH',
				'TP',
				'TG',
				'TT',
				'XT',
				'TN',
				'CP',
				'TC',
				'UG',
				'AE',
				'UY',
				'US',
				'VU',
				'VE',
				'VN',
				'WF',
				'EH',
				'YE',
				'ZM',
				'ZW'
			);

			$zone_2 = array(
				'AU',
				'IO',
				'CX',
				'CK',
				'FJ',
				'PF',
				'TF',
				'KI',
				'LA',
				'MO',
				'NR',
				'NC',
				'NZ',
				'NU',
				'PW',
				'PG',
				'PN',
				'SG',
				'SB',
				'TK',
				'TO',
				'TV',
				'WS'
			);

			// International Standard
			if ($this->config->get('royal_mail_international_standard_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;

				$rates = array();

				// EU
				if (in_array($address['iso_code_2'], $europe)) {
					$rates = explode(',', $this->config->get('royal_mail_international_standard_eu_rate'));
				}

				// World Zones 1
				if (in_array($address['iso_code_2'], $zone_1)) {
					$rates = explode(',', $this->config->get('royal_mail_international_standard_zone_1_rate'));
				}

				// World Zones 2
				if (in_array($address['iso_code_2'], $zone_2)) {
					$rates = explode(',', $this->config->get('royal_mail_international_standard_zone_2_rate'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_standard');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_standard'] = array(
						'code'         => 'royal_mail.international_standard',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// International Tracked & Signed
			$tracked_signed = array(
				'AD',
				'AR',
				'AT',
				'BY',
				'BE',
				'BG',
				'CA',
				'KY',
				'HR',
				'CZ',
				'DK',
				'EC',
				'FI',
				'FR',
				'DE',
				'GI',
				'GR',
				'HK',
				'HU',
				'IS',
				'IT',
				'JP',
				'LI',
				'LT',
				'LU',
				'MT',
				'NL',
				'NZ',
				'PL',
				'PT',
				'RO',
				'SM',
				'SG',
				'SK',
				'SI',
				'KR',
				'SE',
				'CH',
				'TH',
				'TT',
				'US',
				'VA'
			);

			if ($this->config->get('royal_mail_international_tracked_signed_status') && in_array($address['iso_code_2'], $tracked_signed)) {
				$cost = 0;

				$rates = array();

				// EU
				if (in_array($address['iso_code_2'], $europe)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_signed_eu_rate'));
				}

				// World Zones 1
				if (in_array($address['iso_code_2'], $zone_1)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_signed_zone_1_rate'));
				}

				// World Zones 2
				if (in_array($address['iso_code_2'], $zone_2)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_signed_zone_2_rate'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_tracked_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_tracked_signed'] = array(
						'code'         => 'royal_mail.international_tracked_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// International Tracked
			$tracked = array(
				'AD',
				'AU',
				'AT',
				'BE',
				'BR',
				'CA',
				'XC',
				'HR',
				'DK',
				'EE',
				'FI',
				'FR',
				'DE',
				'HK',
				'HU',
				'IS',
				'IN',
				'IE',
				'LV',
				'LI',
				'LT',
				'LU',
				'MY',
				'MT',
				'NL',
				'NZ',
				'PL',
				'PT',
				'SG',
				'ES',
				'SE',
				'CH',
				'US'
			);

			if ($this->config->get('royal_mail_international_tracked_status') && in_array($address['iso_code_2'], $tracked)) {
				$cost = 0;

				$rates = array();

				// EU
				if (in_array($address['iso_code_2'], $europe) && !in_array($address['iso_code_2'], $non_eu)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_eu_rate'));
				}

				// Non EU
				if (in_array($address['iso_code_2'], $non_eu)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_non_eu_rate'));
				}

				// World Zones 1
				if (in_array($address['iso_code_2'], $zone_1)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_zone_1_rate'));
				}

				// World Zones 2
				if (in_array($address['iso_code_2'], $zone_2)) {
					$rates = explode(',', $this->config->get('royal_mail_international_tracked_zone_2_rate'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_tracked');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_tracked'] = array(
						'code'         => 'royal_mail.international_tracked',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// International Signed
			$signed = array(
				'AF',
				'NA',
				'AL',
				'DZ',
				'AO',
				'AI',
				'AG',
				'AM',
				'AW',
				'XA',
				'AU',
				'AZ',
				'BS',
				'BH',
				'BD',
				'BB',
				'BZ',
				'BJ',
				'BM',
				'BT',
				'BO',
				'BX',
				'BA',
				'BW',
				'BR',
				'IO',
				'VG',
				'BN',
				'BF',
				'BI',
				'KH',
				'CM',
				'XC',
				'CV',
				'CF',
				'CE',
				'TD',
				'CL',
				'CN',
				'CX',
				'CX',
				'CO',
				'KM',
				'CD',
				'CG',
				'CK',
				'CR',
				'CU',
				'CB',
				'CY',
				'DJ',
				'DM',
				'DO',
				'EG',
				'SV',
				'GQ',
				'ER',
				'EE',
				'ET',
				'FK',
				'FO',
				'FJ',
				'GF',
				'PF',
				'TF',
				'GA',
				'GM',
				'GE',
				'GH',
				'GL',
				'GD',
				'GP',
				'GT',
				'GN',
				'GW',
				'GY',
				'HT',
				'HN',
				'IN',
				'ID',
				'IR',
				'IQ',
				'IL',
				'CI',
				'JM',
				'JO',
				'KZ',
				'KE',
				'KI',
				'KV',
				'KW',
				'KG',
				'LA',
				'LV',
				'LB',
				'LS',
				'LR',
				'LY',
				'MO',
				'MK',
				'MG',
				'YT',
				'MW',
				'MY',
				'MV',
				'ML',
				'MQ',
				'MR',
				'MU',
				'XL',
				'MX',
				'MD',
				'MN',
				'CS',
				'MS',
				'MA',
				'MZ',
				'MM',
				'NA',
				'NR',
				'NP',
				'NC',
				'NI',
				'NE',
				'NG',
				'NU',
				'KP',
				'NO',
				'OM',
				'PK',
				'PW',
				'PA',
				'PG',
				'PY',
				'PE',
				'PH',
				'PN',
				'PR',
				'QA',
				'RE',
				'RU',
				'RW',
				'ST',
				'SA',
				'SN',
				'CS',
				'SC',
				'SL',
				'SB',
				'SO',
				'ZA',
				'SD',
				'ES',
				'LK',
				'SX',
				'SH',
				'KN',
				'LC',
				'SF',
				'VC',
				'SD',
				'SR',
				'SZ',
				'SY',
				'TW',
				'TJ',
				'TZ',
				'TP',
				'TG',
				'TK',
				'TO',
				'XT',
				'TN',
				'TR',
				'CP',
				'TM',
				'TC',
				'TV',
				'UG',
				'UA',
				'AE',
				'UY',
				'UZ',
				'VU',
				'VE',
				'VN',
				'WF',
				'EH',
				'WS',
				'YE',
				'ZM',
				'ZW'
			);

			if ($this->config->get('royal_mail_international_signed_status') && in_array($address['iso_code_2'], $signed)) {
				$cost = 0;

				$rates = array();

				// EU
				if (in_array($address['iso_code_2'], $europe)) {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_eu_rate'));
				}

				// World Zones 1
				if (in_array($address['iso_code_2'], $zone_1)) {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_zones_1_rate'));
				}

				// World Zones 2
				if (in_array($address['iso_code_2'], $zone_2)) {
					$rates = explode(',', $this->config->get('royal_mail_international_signed_zones_2_rate'));
				}

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_signed');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_signed'] = array(
						'code'         => 'royal_mail.international_signed',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}

			// Economy
			if ($this->config->get('royal_mail_international_economy_status') && $address['iso_code_2'] != 'GB') {
				$cost = 0;

				$rates = explode(',', $this->config->get('royal_mail_international_economy_rate'));

				foreach ($rates as $rate) {
					$data = explode(':', $rate);

					if ($data[0] >= $weight) {
						if (isset($data[1])) {
							$cost = $data[1];
						}

						break;
					}
				}

				if ((float)$cost) {
					$title = $this->language->get('text_international_economy');

					if ($this->config->get('royal_mail_display_weight')) {
						$title .= ' (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')';
					}

					$quote_data['international_economy'] = array(
						'code'         => 'royal_mail.international_economy',
						'title'        => $title,
						'cost'         => $cost,
						'tax_class_id' => $this->config->get('royal_mail_tax_class_id'),
						'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('royal_mail_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
					);
				}
			}
		}

		$method_data = array();

		if ($quote_data) {
			$method_data = array(
				'code'       => 'royal_mail',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('royal_mail_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}