<?php
class ControllerPaymentKlarnaInvoice extends Controller {
	protected function index() {
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($order_info) {      
			$this->language->load('payment/klarna_invoice');

			$this->data['text_additional'] = $this->language->get('text_additional');
			$this->data['text_payment_option'] = $this->language->get('text_payment_option');	
			$this->data['text_wait'] = $this->language->get('text_wait');
			$this->data['text_day'] = $this->language->get('text_day');	
			$this->data['text_month'] = $this->language->get('text_month');	
			$this->data['text_year'] = $this->language->get('text_year');	
			$this->data['text_male'] = $this->language->get('text_male');	
			$this->data['text_female'] = $this->language->get('text_female');		

			$this->data['entry_pno'] = $this->language->get('entry_pno');
			$this->data['entry_dob'] = $this->language->get('entry_dob');	
			$this->data['entry_gender'] = $this->language->get('entry_gender');	
			$this->data['entry_street'] = $this->language->get('entry_street');	
			$this->data['entry_house_no'] = $this->language->get('entry_house_no');	
			$this->data['entry_house_ext'] = $this->language->get('entry_house_ext');
			$this->data['entry_phone_no'] = $this->language->get('entry_phone_no');	
			$this->data['entry_company'] = $this->language->get('entry_company');

			$this->data['button_confirm'] = $this->language->get('button_confirm');		

			$this->data['days'] = array();

			for ($i = 1; $i <= 31; $i++) {
				$this->data['days'][] = array(
					'text'  => sprintf('%02d', $i), 
					'value' => $i
				);
			}

			$this->data['months'] = array();

			for ($i = 1; $i <= 12; $i++) {
				$this->data['months'][] = array(
					'text'  => sprintf('%02d', $i), 
					'value' => $i
				);
			}			

			$this->data['years'] = array();

			for ($i = date('Y'); $i >= 1900; $i--) {
				$this->data['years'][] = array(
					'text'  => $i,
					'value' => $i
				);
			}	

			// Store Taxes to send to Klarna
			$total_data = array();
			$total = 0;

			$this->load->model('setting/extension');

			$sort_order = array(); 

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			$klarna_tax = array();

			foreach ($results as $result) {
				if ($this->config->get($result['code'] . '_status')) {
					$this->load->model('total/' . $result['code']);

					$taxes = array();

					$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);

					$amount = 0;

					foreach ($taxes as $tax_id => $value) {
						$amount += $value;
					}

					$klarna_tax[$result['code']] = $amount;
				}
			}

			foreach ($total_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];

				if (isset($klarna_tax[$value['code']])) {
					if ($klarna_tax[$value['code']]) {
						$total_data[$key]['tax_rate'] = abs($klarna_tax[$value['code']] / $value['value'] * 100);
					} else {
						$total_data[$key]['tax_rate'] = 0;
					}
				} else {
					$total_data[$key]['tax_rate'] = '0';
				}
			}

			$this->session->data['klarna'][$this->session->data['order_id']] = $total_data;

			// Order must have identical shipping and billing address or have no shipping address at all
			if ($this->cart->hasShipping() && !($order_info['payment_firstname'] == $order_info['shipping_firstname'] && $order_info['payment_lastname'] == $order_info['shipping_lastname'] && $order_info['payment_address_1'] == $order_info['shipping_address_1'] && $order_info['payment_address_2'] == $order_info['shipping_address_2'] && $order_info['payment_postcode'] == $order_info['shipping_postcode'] && $order_info['payment_city'] == $order_info['shipping_city'] && $order_info['payment_zone_id'] == $order_info['shipping_zone_id'] && $order_info['payment_zone_code'] == $order_info['shipping_zone_code'] && $order_info['payment_country_id'] == $order_info['shipping_country_id'] && $order_info['payment_country'] == $order_info['shipping_country'] && $order_info['payment_iso_code_3'] == $order_info['shipping_iso_code_3'])) {
				$this->data['error_warning'] = $this->language->get('error_address_match');
			} else {
				$this->data['error_warning'] = '';
			}

			// The title stored in the DB gets truncated which causes order_info.tpl to not be displayed properly
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_method` = '" . $this->db->escape($this->language->get('text_title')) . "' WHERE `order_id` = " . (int)$this->session->data['order_id']);

			$klarna_invoice = $this->config->get('klarna_invoice');

			$this->data['merchant'] = $klarna_invoice[$order_info['payment_iso_code_3']]['merchant'];
			$this->data['phone_number'] = $order_info['telephone'];

			if ($order_info['payment_iso_code_3'] == 'DEU' || $order_info['payment_iso_code_3'] == 'NLD') {
				$address = $this->splitAddress($order_info['payment_address_1']);

				$this->data['street'] = $address[0];
				$this->data['street_number'] = $address[1];
				$this->data['street_extension'] = $address[2];

				if ($order_info['payment_iso_code_3'] == 'DEU') {
					$this->data['street_number'] = trim($address[1] . ' ' . $address[2]);
				}
			} else {
				$this->data['street'] = '';
				$this->data['street_number'] = '';
				$this->data['street_extension'] = '';
			}

			$this->data['company'] = $order_info['payment_company'];
			$this->data['iso_code_2'] = $order_info['payment_iso_code_2'];
			$this->data['iso_code_3'] = $order_info['payment_iso_code_3'];

			// Get the invoice fee
			$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = " . (int)$order_info['order_id'] . " AND `code` = 'klarna_fee'");

			if ($query->num_rows && !$query->row['value']) {
				$this->data['klarna_fee'] = $query->row['value'];
			} else {
				$this->data['klarna_fee'] = '';
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/klarna_invoice.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/payment/klarna_invoice.tpl';
			} else {
				$this->template = 'default/template/payment/klarna_invoice.tpl';
			}

			$this->render();
		}
	}

	public function send() {
		$this->language->load('payment/klarna_invoice');

		$json = array();

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		// Order must have identical shipping and billing address or have no shipping address at all
		if ($order_info) {
			if ($order_info['payment_iso_code_3'] == 'DEU' && empty($this->request->post['deu_terms'])) {
				$json['error'] =  $this->language->get('error_deu_terms');
			}

			if ($this->cart->hasShipping() && !($order_info['payment_firstname'] == $order_info['shipping_firstname'] && $order_info['payment_lastname'] == $order_info['shipping_lastname'] && $order_info['payment_address_1'] == $order_info['shipping_address_1'] && $order_info['payment_address_2'] == $order_info['shipping_address_2'] && $order_info['payment_postcode'] == $order_info['shipping_postcode'] && $order_info['payment_city'] == $order_info['shipping_city'] && $order_info['payment_zone_id'] == $order_info['shipping_zone_id'] && $order_info['payment_zone_code'] == $order_info['shipping_zone_code'] && $order_info['payment_country_id'] == $order_info['shipping_country_id'] && $order_info['payment_country'] == $order_info['shipping_country'] && $order_info['payment_iso_code_3'] == $order_info['shipping_iso_code_3'])) {
				$json['error'] = $this->language->get('error_address_match');
			}

			if (!$json) {		
				$klarna_invoice = $this->config->get('klarna_invoice');

				if ($klarna_invoice[$order_info['payment_iso_code_3']]['server'] == 'live') {
					$url = 'https://payment.klarna.com/';
				} else {
					$url = 'https://payment.testdrive.klarna.com/';
				}

				$country_to_currency = array(
					'NOR' => 'NOK',
					'SWE' => 'SEK',
					'FIN' => 'EUR',
					'DNK' => 'DKK',
					'DEU' => 'EUR',
					'NLD' => 'EUR',
				);

				switch ($order_info['payment_iso_code_3']) {
					// Sweden
					case 'SWE':
						$country = 209;
						$language = 138;
						$encoding = 2;
						$currency = 0;
						break;
					// Finland
					case 'FIN':
						$country = 73;
						$language = 37;
						$encoding = 4;
						$currency = 2;
						break;
					// Denmark
					case 'DNK':
						$country = 59;
						$language = 27;
						$encoding = 5;
						$currency = 3;
						break;
					// Norway	
					case 'NOR':
						$country = 164;
						$language = 97;
						$encoding = 3;
						$currency = 1;
						break;
					// Germany	
					case 'DEU':
						$country = 81;
						$language = 28;
						$encoding = 6;
						$currency = 2;
						break;
					// Netherlands															
					case 'NLD':
						$country = 154;
						$language = 101;
						$encoding = 7;
						$currency = 2;
						break;
				}

				if (isset($this->request->post['street'])) {
					$street = $this->request->post['street'];
				} else {
					$street = $order_info['payment_address_1'];
				}

				if (isset($this->request->post['house_no'])) {
					$house_no = $this->request->post['house_no'];
				} else {
					$house_no = '';
				}

				if (isset($this->request->post['house_ext'])) {
					$house_ext = $this->request->post['house_ext'];
				} else {
					$house_ext = '';
				}

				$address = array(
					'email'           => $order_info['email'],
					'telno'           => $this->request->post['phone_no'],
					'cellno'          => '',
					'fname'           => $order_info['payment_firstname'],
					'lname'           => $order_info['payment_lastname'],
					'company'         => $order_info['payment_company'],
					'careof'          => '',
					'street'          => $street,
					'house_number'    => $house_no,
					'house_extension' => $house_ext,
					'zip'             => $order_info['payment_postcode'],
					'city'            => $order_info['payment_city'],
					'country'         => $country,
				);

				$product_query = $this->db->query("SELECT `name`, `model`, `price`, `quantity`, `tax` / `price` * 100 AS 'tax_rate' FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = " . (int)$order_info['order_id'] . " UNION ALL SELECT '', `code`, `amount`, '1', 0.00 FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = " . (int)$order_info['order_id'])->rows;

				foreach ($product_query as $product) {
					$goods_list[] = array(
						'qty'   => (int)$product['quantity'],
						'goods' => array(
							'artno'    => $product['model'],
							'title'    => $product['name'],
							'price'    => (int)str_replace('.', '', $this->currency->format($product['price'], $country_to_currency[$order_info['payment_iso_code_3']], '', false)),
							'vat'      => (float)$product['tax_rate'],
							'discount' => 0.0,
							'flags'    => 0,
						)
					);
				}

				if (isset($this->session->data['klarna'][$this->session->data['order_id']])) {
					$totals = $this->session->data['klarna'][$this->session->data['order_id']];
				} else {
					$totals = array();
				}

				foreach ($totals as $total) {
					if ($total['code'] != 'sub_total' && $total['code'] != 'tax' && $total['code'] != 'total') {
						$goods_list[] = array(
							'qty'   => 1,
							'goods' => array(
								'artno'    => '',
								'title'    => $total['title'],
								'price'    => (int)str_replace('.', '', $this->currency->format($total['value'], $country_to_currency[$order_info['payment_iso_code_3']], '', false)),
								'vat'      => (float)$total['tax_rate'],
								'discount' => 0.0,
								'flags'    => 0,
							)
						);
					}
				}

				$digest = '';

				foreach ($goods_list as $goods) {
					$digest .= utf8_decode(htmlspecialchars(html_entity_decode($goods['goods']['title'], ENT_COMPAT, "UTF-8"))) . ':';
				}

				$digest = base64_encode(pack('H*', hash('sha256', $digest . $klarna_invoice[$order_info['payment_iso_code_3']]['secret'])));

				if (isset($this->request->post['pno'])) {
					$pno = $this->request->post['pno'];
				} else {
					$pno = sprintf('%02d', (int)$this->request->post['pno_day']) . sprintf('%02d', (int)$this->request->post['pno_month']) . (int)$this->request->post['pno_year'];
				}

				$pclass = -1;

				if (isset($this->request->post['gender']) && ($order_info['payment_iso_code_3'] == 'DEU' || $order_info['payment_iso_code_3'] == 'NLD')) {
					$gender = (int)$this->request->post['gender'];
				} else {
					$gender = '';
				}

				$transaction = array(
					'4.1',
					'API:OPENCART:' . VERSION,
					$pno,
					$gender,
					'',
					'', 
					(string)$order_info['order_id'], 
					'',
					$address, 
					$address, 
					$order_info['ip'],
					0, 
					$currency, 
					$country,
					$language, 
					(int)$klarna_invoice[$order_info['payment_iso_code_3']]['merchant'],
					$digest, 
					$encoding,
					$pclass, 
					$goods_list,
					$order_info['comment'],
					array('delay_adjust' => 1),
					array(),
					array(),
					array(),
					array(),
					array(),
				);

				$xml  = '<methodCall>';
				$xml .= '  <methodName>add_invoice</methodName>';
				$xml .= '  <params>';

				foreach ($transaction as $parameter)  {
					$xml .= '    <param><value>' . $this->constructXmlrpc($parameter) . '</value></param>';
				}

				$xml .= '  </params>';
				$xml .= '</methodCall>';        

				$header  = 'Content-Type: text/xml' . "\n";
				$header .= 'Content-Length: ' . strlen($xml) . "\n";

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_HEADER, $header);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

				$response = curl_exec($curl);

				if (curl_errno($curl)) {
					$log = new Log('klarna_invoice.log');
					$log->write('HTTP Error for order #' . $order_info['order_id'] . '. Code: ' . curl_errno($curl) . ' message: ' . curl_error($curl));

					$json['error'] = $this->language->get('error_network');
				} else {
					preg_match('/<member><name>faultString<\/name><value><string>(.+)<\/string><\/value><\/member>/', $response, $match);

					if (isset($match[1])) {
						preg_match('/<member><name>faultCode<\/name><value><int>([0-9]+)<\/int><\/value><\/member>/', $response, $match2);

						$log = new Log('klarna_invoice.log');
						$log->write('Failed to create an invoice for order #' . $order_info['order_id'] . '. Message: ' . utf8_encode($match[1]) . ' Code: ' . $match2[1]);

						$json['error'] = utf8_encode($match[1]); 
					} else {
						$xml = new DOMDocument();
						$xml->loadXML($response);

						$invoice_number = $xml->getElementsByTagName('string')->item(0)->nodeValue;
						$klarna_order_status = $xml->getElementsByTagName('int')->item(0)->nodeValue;

						if ($klarna_order_status == '1') {
							$order_status = $klarna_invoice[$order_info['payment_iso_code_3']]['accepted_status_id'];
						} elseif ($klarna_order_status == '2') {
							$order_status = $klarna_invoice[$order_info['payment_iso_code_3']]['pending_status_id'];
						} else {
							$order_status = $this->config->get('config_order_status_id');
						}

						$comment = sprintf($this->language->get('text_comment'), $invoice_number, $this->config->get('config_currency'), $country_to_currency[$order_info['payment_iso_code_3']], $this->currency->getValue($country_to_currency[$order_info['payment_iso_code_3']]));

						$this->model_checkout_order->confirm($this->session->data['order_id'], $order_status, $comment, 1);

						$json['redirect'] = $this->url->link('checkout/success');
					}
				}

				curl_close($curl);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	private function constructXmlrpc($data) {
		$type = gettype($data);

		switch ($type) {
			case 'boolean':
				if ($data == true) {
					$value = 1;
				} else {
					$value = false;
				}

				$xml = '<boolean>' . $value . '</boolean>';
				break;
			case 'integer':
				$xml = '<int>' . (int)$data . '</int>';
				break;
			case 'double':
				$xml = '<double>' . (float)$data . '</double>';
				break;
			case 'string':
				$xml = '<string>' . htmlspecialchars($data) . '</string>';
				break;
			case 'array':
				// is numeric ?
				if ($data === array_values($data)) {
					$xml = '<array><data>';

					foreach ($data as $value) {
						$xml .= '<value>' . $this->constructXmlrpc($value) . '</value>';
					}

					$xml .= '</data></array>';

				} else {
					// array is associative
					$xml = '<struct>';

					foreach ($data as $key => $value) {
						$xml .= '<member>';
						$xml .= '  <name>' . htmlspecialchars($key) . '</name>';
						$xml .= '  <value>' . $this->constructXmlrpc($value) . '</value>';
						$xml .= '</member>';
					}

					$xml .= '</struct>';
				}

				break;
			default:
				$xml = '<nil/>';
				break;
		}

		return $xml;
	}

	private function splitAddress($address) {
		$numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

		$characters = array('-', '/', ' ', '#', '.', 'a', 'b', 'c', 'd', 'e',
						'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
						'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A',
						'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
						'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
						'X', 'Y', 'Z');

		$specialchars = array('-', '/', ' ', '#', '.');

		$num_pos = $this->strposArr($address, $numbers, 2);

		$street_name = substr($address, 0, $num_pos);

		$street_name = trim($street_name);

		$number_part = substr($address, $num_pos);

		$number_part = trim($number_part);

		$ext_pos = $this->strposArr($number_part, $characters, 0);

		if ($ext_pos != '') {
			$house_number = substr($number_part, 0, $ext_pos);

			$house_extension = substr($number_part, $ext_pos);

			$house_extension = str_replace($specialchars, '', $house_extension);
		} else {
			$house_number = $number_part;
			$house_extension = '';
		}

		return array($street_name, $house_number, $house_extension);
	}

	private function strposArr($haystack, $needle, $where) {
		$defpos = 10000;

		if (!is_array($needle)) {
			$needle = array($needle);
		}

		foreach ($needle as $what) {
			if (($pos = strpos($haystack, $what, $where)) !== false) {
				if ($pos < $defpos) {
					$defpos = $pos;
				}
			}
		}

		return $defpos;
	}
}
?>
