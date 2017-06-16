<?php
class ControllerExtensionPaymentKlarnaAccount extends Controller {
	public function index() {
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($order_info) {
			$this->load->language('extension/payment/klarna_account');

			$data['days'] = array();

			for ($i = 1; $i <= 31; $i++) {
				$data['days'][] = array(
					'text'  => sprintf('%02d', $i),
					'value' => $i
				);
			}

			$data['months'] = array();

			for ($i = 1; $i <= 12; $i++) {
				$data['months'][] = array(
					'text'  => sprintf('%02d', $i),
					'value' => $i
				);
			}

			$data['years'] = array();

			for ($i = date('Y'); $i >= 1900; $i--) {
				$data['years'][] = array(
					'text'  => $i,
					'value' => $i
				);
			}

			// Store Taxes to send to Klarna
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			$klarna_tax = array();

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					$taxes = array();
					
					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);

					$amount = 0;

					foreach ($taxes as $tax_id => $value) {
						$amount += $value;
					}

					$klarna_tax[$result['code']] = $amount;
				}
			}

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];

				if (isset($klarna_tax[$value['code']])) {
					if ($klarna_tax[$value['code']]) {
						$totals[$key]['tax_rate'] = abs($klarna_tax[$value['code']] / $value['value'] * 100);
					} else {
						$totals[$key]['tax_rate'] = 0;
					}
				} else {
					$totals[$key]['tax_rate'] = '0';
				}
			}

			$this->session->data['klarna'][$this->session->data['order_id']] = $totals;

			// Order must have identical shipping and billing address or have no shipping address at all
			if ($this->cart->hasShipping() && !($order_info['payment_firstname'] == $order_info['shipping_firstname'] && $order_info['payment_lastname'] == $order_info['shipping_lastname'] && $order_info['payment_address_1'] == $order_info['shipping_address_1'] && $order_info['payment_address_2'] == $order_info['shipping_address_2'] && $order_info['payment_postcode'] == $order_info['shipping_postcode'] && $order_info['payment_city'] == $order_info['shipping_city'] && $order_info['payment_zone_id'] == $order_info['shipping_zone_id'] && $order_info['payment_zone_code'] == $order_info['shipping_zone_code'] && $order_info['payment_country_id'] == $order_info['shipping_country_id'] && $order_info['payment_country'] == $order_info['shipping_country'] && $order_info['payment_iso_code_3'] == $order_info['shipping_iso_code_3'])) {
				$data['error_warning'] = $this->language->get('error_address_match');
			} else {
				$data['error_warning'] = '';
			}

			$klarna_account = $this->config->get('payment_klarna_account');

			$data['merchant'] = $klarna_account[$order_info['payment_iso_code_3']]['merchant'];
			$data['phone_number'] = $order_info['telephone'];

			$country_to_currency = array(
				'NOR' => 'NOK',
				'SWE' => 'SEK',
				'FIN' => 'EUR',
				'DNK' => 'DKK',
				'DEU' => 'EUR',
				'NLD' => 'EUR'
			);

			if ($order_info['payment_iso_code_3'] == 'DEU' || $order_info['payment_iso_code_3'] == 'NLD') {
				$address = $this->splitAddress($order_info['payment_address_1']);

				$data['street'] = $address[0];
				$data['street_number'] = $address[1];
				$data['street_extension'] = $address[2];

				if ($order_info['payment_iso_code_3'] == 'DEU') {
					$data['street_number'] = trim($address[1] . ' ' . $address[2]);
				}
			} else {
				$data['street'] = '';
				$data['street_number'] = '';
				$data['street_extension'] = '';
			}

			$data['company'] = $order_info['payment_company'];
			$data['iso_code_2'] = $order_info['payment_iso_code_2'];
			$data['iso_code_3'] = $order_info['payment_iso_code_3'];

			$payment_option = array();

			$total = $this->currency->format($order_info['total'], $country_to_currency[$order_info['payment_iso_code_3']], '', false);

			$pclasses = $this->config->get('klarna_account_pclasses');

			if (isset($pclasses[$order_info['payment_iso_code_3']])) {
				$pclasses = $pclasses[$order_info['payment_iso_code_3']];
			} else {
				$pclasses = array();
			}

			foreach ($pclasses as $pclass) {
				// 0 - Campaign
				// 1 - Account
				// 2 - Special
				// 3 - Fixed
				if (!in_array($pclass['type'], array(0, 1, 3))) {
					continue;
				}

				if ($pclass['type'] == 2) {
					$monthly_cost = -1;
				} else {
					if ($total < $pclass['minamount']) {
						continue;
					}

					if ($pclass['type'] == 3) {
						continue;
					} else {
						$sum = $total;

						$lowest_payment = $this->getLowestPaymentAccount($order_info['payment_iso_code_3']);
						$monthly_cost = 0;

						$monthly_fee = $pclass['invoicefee'];
						$start_fee = $pclass['startfee'];

						$sum += $start_fee;

						$base = ($pclass['type'] == 1);

						$minimum_payment = ($pclass['type'] === 1) ? $this->getLowestPaymentAccount($order_info['payment_iso_code_3']) : 0;

						if ($pclass['months'] == 0) {
							$payment = $sum;
						} elseif ($pclass['interestrate'] == 0) {
							$payment = $sum / $pclass['months'];
						} else {
							$interest = $pclass['interestrate'] / (100.0 * 12);
							$payment = $sum * $interest / (1 - pow((1 + $interest), -$pclass['months']));
						}

						$payment += $monthly_fee;

						$balance = $sum;
						$pay_data = array();

						$months = $pclass['months'];

						while (($months != 0) && ($balance > 0.01)) {
							$interest = $balance * $pclass['interestrate'] / (100.0 * 12);
							$new_balance = $balance + $interest + $monthly_fee;

							if ($minimum_payment >= $new_balance || $payment >= $new_balance) {
								$pay_data[] = $new_balance;
								break;
							}

							$new_payment = max($payment, $minimum_payment);

							if ($base) {
								$new_payment = max($new_payment, $balance / 24.0 + $monthly_fee + $interest);
							}

							$balance = $new_balance - $new_payment;

							$pay_data[] = $new_payment;

							$months -= 1;
						}

						$monthly_cost = round(isset($pay_data[0]) ? ($pay_data[0]) : 0, 2);

						if ($monthly_cost < 0.01) {
							continue;
						}

						if ($pclass['type'] == 1 && $monthly_cost < $lowest_payment) {
							$monthly_cost = $lowest_payment;
						}

						if ($pclass['type'] == 0 && $monthly_cost < $lowest_payment) {
							continue;
						}
					}
				}

				$payment_option[$pclass['id']]['pclass_id'] = $pclass['id'];
				$payment_option[$pclass['id']]['title'] = $pclass['description'];
				$payment_option[$pclass['id']]['months'] = $pclass['months'];
				$payment_option[$pclass['id']]['monthly_cost'] = $monthly_cost;
			}

			$sort_order = array();

			foreach ($payment_option as $key => $value) {
				$sort_order[$key] = $value['pclass_id'];
			}

			array_multisort($sort_order, SORT_ASC, $payment_option);

			$data['payment_options'] = array();

			foreach ($payment_option as $payment_option) {
				$data['payment_options'][] = array(
					'code'  => $payment_option['pclass_id'],
					'title' => sprintf($this->language->get('text_monthly_payment'), $payment_option['title'], $this->currency->format($this->currency->convert($payment_option['monthly_cost'], $country_to_currency[$order_info['payment_iso_code_3']], $this->session->data['currency']), $this->session->data['currency'], 1))
				);
			}

			return $this->load->view('extension/payment/klarna_account', $data);
		}
	}

	public function send() {
		$this->load->language('extension/payment/klarna_account');

		$json = array();

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		// Order must have identical shipping and billing address or have no shipping address at all
		if ($order_info) {
			if ($order_info['payment_iso_code_3'] == 'DEU' && empty($this->request->post['deu_terms'])) {
				$json['error'] = $this->language->get('error_deu_terms');
			}

			if ($this->cart->hasShipping() && !($order_info['payment_firstname'] == $order_info['shipping_firstname'] && $order_info['payment_lastname'] == $order_info['shipping_lastname'] && $order_info['payment_address_1'] == $order_info['shipping_address_1'] && $order_info['payment_address_2'] == $order_info['shipping_address_2'] && $order_info['payment_postcode'] == $order_info['shipping_postcode'] && $order_info['payment_city'] == $order_info['shipping_city'] && $order_info['payment_zone_id'] == $order_info['shipping_zone_id'] && $order_info['payment_zone_code'] == $order_info['shipping_zone_code'] && $order_info['payment_country_id'] == $order_info['shipping_country_id'] && $order_info['payment_country'] == $order_info['shipping_country'] && $order_info['payment_iso_code_3'] == $order_info['shipping_iso_code_3'])) {
				$json['error'] = $this->language->get('error_address_match');
			}

			if (!$json) {
				$klarna_account = $this->config->get('payment_klarna_account');

				if ($klarna_account[$order_info['payment_iso_code_3']]['server'] == 'live') {
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
					'NLD' => 'EUR'
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
					'country'         => $country
				);

				$product_query = $this->db->query("SELECT `name`, `model`, `price`, `quantity`, `tax` / `price` * 100 AS 'tax_rate' FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = " . (int)$order_info['order_id'] . " UNION ALL SELECT '', `code`, `amount`, '1', 0.00 FROM `" . DB_PREFIX . "order_voucher` WHERE `order_id` = " . (int)$order_info['order_id']);

				foreach ($product_query->rows as $product) {
					$goods_list[] = array(
						'qty'   => (int)$product['quantity'],
						'goods' => array(
							'artno'    => $product['model'],
							'title'    => $product['name'],
							'price'    => (int)str_replace('.', '', $this->currency->format($product['price'], $country_to_currency[$order_info['payment_iso_code_3']], '', false)),
							'vat'      => (float)$product['tax_rate'],
							'discount' => 0.0,
							'flags'    => 0
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
								'flags'    => 0
							)
						);
					}
				}

				$digest = '';

				foreach ($goods_list as $goods) {
					$digest .= utf8_decode(htmlspecialchars(html_entity_decode($goods['goods']['title'], ENT_COMPAT, 'UTF-8'))) . ':';
				}

				$digest = base64_encode(pack('H*', hash('sha256', $digest . $klarna_account[$order_info['payment_iso_code_3']]['secret'])));

				if (isset($this->request->post['pno'])) {
					$pno = $this->request->post['pno'];
				} else {
					$pno = sprintf('%02d', (int)$this->request->post['pno_day']) . sprintf('%02d', (int)$this->request->post['pno_month']) . (int)$this->request->post['pno_year'];
				}

				if (isset($this->request->post['code'])) {
					$pclass = (int)$this->request->post['code'];
				} else {
					$pclass = '';
				}

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
					(int)$klarna_account[$order_info['payment_iso_code_3']]['merchant'],
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

				$header = array();

				$header[] = 'Content-Type: text/xml';
				$header[] = 'Content-Length: ' . strlen($xml);

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);

				$response = curl_exec($curl);

				if (curl_errno($curl)) {
					$log = new Log('klarna_account.log');
					$log->write('HTTP Error for order #' . $order_info['order_id'] . '. Code: ' . curl_errno($curl) . ' message: ' . curl_error($curl));

					$json['error'] = $this->language->get('error_network');
				} else {
					preg_match('/<member><name>faultString<\/name><value><string>(.+)<\/string><\/value><\/member>/', $response, $match);

					if (isset($match[1])) {
						preg_match('/<member><name>faultCode<\/name><value><int>([0-9]+)<\/int><\/value><\/member>/', $response, $match2);

						$log = new Log('klarna_account.log');
						$log->write('Failed to create an invoice for order #' . $order_info['order_id'] . '. Message: ' . utf8_encode($match[1]) . ' Code: ' . $match2[1]);

						$json['error'] = utf8_encode($match[1]);
					} else {
						$xml = new DOMDocument();
						$xml->loadXML($response);

						$invoice_number = $xml->getElementsByTagName('string')->item(0)->nodeValue;
						$klarna_order_status = $xml->getElementsByTagName('int')->item(0)->nodeValue;

						if ($klarna_order_status == '1') {
							$order_status = $klarna_account[$order_info['payment_iso_code_3']]['accepted_status_id'];
						} elseif ($klarna_order_status == '2') {
							$order_status = $klarna_account[$order_info['payment_iso_code_3']]['pending_status_id'];
						} else {
							$order_status = $this->config->get('config_order_status_id');
						}

						$comment = sprintf($this->language->get('text_comment'), $invoice_number, $this->config->get('config_currency'), $country_to_currency[$order_info['payment_iso_code_3']], $this->currency->getValue($country_to_currency[$order_info['payment_iso_code_3']]));

						$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status, $comment, 1);

						$json['redirect'] = $this->url->link('checkout/success');
					}
				}

				curl_close($curl);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
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
				if ($data === array_values($data)) {
					$xml = '<array><data>';

					foreach ($data as $value) {
						$xml .= '<value>' . $this->constructXmlrpc($value) . '</value>';
					}

					$xml .= '</data></array>';
				} else {
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

	private function getLowestPaymentAccount($country) {
		switch ($country) {
			case 'SWE':
				$amount = 50.0;
				break;
			case 'NOR':
				$amount = 95.0;
				break;
			case 'FIN':
				$amount = 8.95;
				break;
			case 'DNK':
				$amount = 89.0;
				break;
			case 'DEU':
			case 'NLD':
				$amount = 5.00;
				break;

			default:
				$log = new Log('klarna.log');
				$log->write('Unknown country ' . $country);

				$amount = null;
				break;
		}

		return $amount;
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