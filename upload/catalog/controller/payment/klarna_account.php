<?php
class ControllerPaymentKlarnaAccount extends Controller {
    protected function index() {
		$this->language->load('payment/klarna_account');
       
	   	$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_additional'] = $this->language->get('text_additional');
		$this->data['text_wait'] = $this->language->get('text_payment_option');
		
	   	$this->data['text_information'] = $this->language->get('text_information');
		$this->data['text_additional'] = $this->language->get('text_additional');		
		$this->data['text_payment_option'] = $this->language->get('text_payment_option');	
		$this->data['text_day'] = $this->language->get('text_day');	
		$this->data['text_month'] = $this->language->get('text_month');	
		$this->data['text_year'] = $this->language->get('text_year');	
		$this->data['text_male'] = $this->language->get('text_male');	
		$this->data['text_female'] = $this->language->get('text_female');		
				
		$this->data['entry_birthday'] = $this->language->get('entry_birthday');	
		$this->data['entry_gender'] = $this->language->get('entry_gender');	
		$this->data['entry_street'] = $this->language->get('entry_street');	
		$this->data['entry_house_no'] = $this->language->get('entry_house_no');	
		$this->data['entry_phone_no'] = $this->language->get('entry_phone_no');	
		
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
			
		$this->load->model('checkout/order');
                
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        // The title stored in the DB gets truncated which causes order_info.tpl to not be displayed properly
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_method` = '" . $this->db->escape($this->language->get('text_title')) . "' WHERE `order_id` = " . (int) $this->session->data['order_id']);
        
        $klarna_account = $this->config->get('klarna_account');
        
        $address_match = false;
        
        // Order must have identical shipping and billing address or have no shipping address at all
        if (empty($order_info['shipping_firstname']) || $order_info['payment_firstname'] == $order_info['shipping_firstname'] && $order_info['payment_lastname'] == $order_info['shipping_lastname'] && $order_info['payment_address_1'] == $order_info['shipping_address_1'] && $order_info['payment_address_2'] == $order_info['shipping_address_2'] && $order_info['payment_postcode'] == $order_info['shipping_postcode'] && $order_info['payment_city'] == $order_info['shipping_city'] && $order_info['payment_zone_id'] == $order_info['shipping_zone_id'] && $order_info['payment_zone_code'] == $order_info['shipping_zone_code'] && $order_info['payment_country_id'] == $order_info['shipping_country_id'] && $order_info['payment_country'] == $order_info['shipping_country'] && $order_info['payment_iso_code_3'] == $order_info['shipping_iso_code_3']) {
            $address_match = true;
        } else {
            $address_match = false;
        }
        
        $country_to_currency = array(
            'NOR' => 'NOK',
            'SWE' => 'SEK',
            'FIN' => 'EUR',
            'DNK' => 'DKK',
            'DEU' => 'EUR',
            'NLD' => 'EUR',
        );
        
        if (!$order_info['payment_company']) {
            $this->data['is_company'] = false;
        } else {
            $this->data['is_company'] = true;
        }
        
        $this->data['phone_number'] = $order_info['telephone'];
        $this->data['company_id'] = $order_info['payment_company_id'];
        
        if ($order_info['payment_iso_code_3'] == 'DEU' || $order_info['payment_iso_code_3'] == 'NLD') {
            $address = $this->splitAddress($order_info['payment_address_1']);
            
            $this->data['street'] = $address[0];
            $this->data['street_number'] = $address[1];
            $this->data['street_extension'] = $address[2];
            
            if ($order_info['payment_iso_code_3'] == 'DEU') {
                $this->data['street_number'] = trim($address[1] . ' ' . $address[2]);
            }
        }
        
        $this->data['address_match'] = $address_match;
        $this->data['country_code'] = $order_info['payment_iso_code_3'];
        $this->data['klarna_country_code'] = $order_info['payment_iso_code_2'];
        
		
		
        $payment_option = array();
        
        // Show part payment options?
        $status = $settings['status'] == '1';        
        
        $countAcc = $this->db->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int) $settings['geo_zone_id'] . "' AND `country_id` = '" . (int) $order_info['payment_country_id'] . "' AND (`zone_id` = '" . (int)$order_info['payment_zone_id'] . "' OR `zone_id` = 0)")->row['count'];
        
        if ($settings['geo_zone_id'] != 0 && $countAcc == 0) {
            $status = false;
        }
        
        if (!empty($order_info['payment_company']) || !empty($order_info['payment_company_id'])) {
            $status = false;
        }
        
        if ($order_info['payment_iso_code_3'] == 'NLD' && $this->currency->has('EUR') && $this->currency->format($order_info['total'], 'EUR', '', false) > 250.00) {
            $status = false;
        }

	
        if ($status) {
            $order_total = $this->currency->format($order_info['total'], $country_to_currency[$order_info['payment_iso_code_3']], '', false);

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
                    if ($order_total < $pclass['minamount']) {
                        continue;
                    }

                    if ($pclass['type'] == 3) {
                        continue;
                    } else {
                        $sum = $order_total;

                        $lowest_payment = $this->getLowestPaymentAccount($order_info['payment_iso_code_3']);
                        $monthly_cost = 0;

                        $monthly_fee = $pclass['invoicefee'];
                        $start_fee = $pclass['startfee'];

                        $sum += $start_fee;

                        $base = ($pclass['type'] == 1);

                        $minimum_payment = ($pclass['type'] === 1) ? $this->getLowestPaymentAccount($country) : 0;

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
                
                $payment_option[$pclass['id']]['monthly_cost'] = $monthly_cost;
                $payment_option[$pclass['id']]['pclass_id'] = $pclass['id'];
                $payment_option[$pclass['id']]['months'] = $pclass['months'];
                $payment_option[$pclass['id']]['title'] = $pclass['description'];
            }
            
        }
        
		if (!$payment_option) {
			$status = false;
		}
		
		$sort_order = array(); 
		  
		foreach ($payment_option as $key => $value) {
			$sort_order[$key] = $value['pclass_id'];
		}
	
		array_multisort($sort_order, SORT_ASC, $payment_option);
        
        $this->data['part_payment_options'] = array();
        
        foreach ($payment_option as $paymentOption) {
            $this->data['part_payment_options'][$paymentOption['pclass_id']] = sprintf($this->language->get('text_monthly_payment'), $paymentOption['title'], $this->currency->format($this->currency->convert($paymentOption['monthly_cost'], $country_to_currency[$order_info['payment_iso_code_3']], $this->currency->getCode()), 1, 1));
        }
        
        $this->data['merchant'] = $klarna_account[$order_info['payment_iso_code_3']]['merchant'];
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/klarna_account.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/klarna_account.tpl';
        } else {
            $this->template = 'default/template/payment/klarna_account.tpl';
        }

        $this->render();
    }

    public function send() {
		$this->language->load('payment/klarna_account');
		
		
        $this->load->model('checkout/order');
        $this->load->model('checkout/coupon');
        
        
        $json = array();

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        $countries = $this->config->get('klarna_account_country');
        $settings = $countries[$order_info['payment_iso_code_3']];
        
        if (!$order_info) {
            $this->response->setOutput(json_encode($json));
            return;
        }
        
        if ($settings['server'] == 'live') {
            $server = 'https://payment.klarna.com/';
        } else {
            $server = 'https://payment-beta.klarna.com/';
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
            $houseNo = $this->request->post['house_no'];
        } else {
            $houseNo = '';
        }
        
        if (isset($this->request->post['house_ext'])) {
            $houseExt = $this->request->post['house_ext'];
        } else {
            $houseExt = '';
        }
        
        $address = array(
            'email' => $order_info['email'],
            'telno' => $this->request->post['phone_no'],
            'cellno' => '',
            'fname' => $order_info['payment_firstname'],
            'lname' => $order_info['payment_lastname'],
            'company' => $order_info['payment_company'],
            'careof' => '',
            'street' => $street,
            'house_number' => $houseNo,
            'house_extension' => $houseExt,
            'zip' => $order_info['payment_postcode'],
            'city' => $order_info['payment_city'],
            'country' => $country,
        );
        
        if ($order_info['payment_iso_code_3'] == 'DEU' || $order_info['payment_iso_code_3'] == 'NLD') {
            $address['street'] = $this->request->post['street'];
            $address['house_number'] = $this->request->post['house_no'];
        }
        
        if ($order_info['payment_iso_code_3'] == 'NLD') {
            $address['house_extension'] = $this->request->post['house_ext'];
        }
        
        $totalQuery = $this->db->query("
            SELECT `title`, `code`, `value`, IF(`tax` IS NULL, 0.0, `tax` / `value` * 100) AS 'tax_rate'
            FROM `" . DB_PREFIX . "order_total`
            LEFT JOIN `" . DB_PREFIX . "order_total_klarna` USING(`order_total_id`)
            WHERE `order_id` = " . (int) $order_info['order_id'] . " AND `code` NOT IN ('sub_total', 'tax', 'total')");
        
        $totals = $totalQuery->rows;
        
        $orderedProducts = $this->db->query("
            SELECT `name`, `model`, `price`, `quantity`, `tax` / `price` * 100 AS 'tax_rate'
            FROM `" . DB_PREFIX . "order_product`
            WHERE `order_id` = " . (int) $order_info['order_id'] . "

            UNION ALL

            SELECT '', `code`, `amount`, '1', 0.00
            FROM `" . DB_PREFIX . "order_voucher`
            WHERE `order_id` = " . (int) $order_info['order_id'])->rows;

        foreach ($orderedProducts as $product) {
            
            $goodsList[] = array(
                'qty' => (int) $product['quantity'],
                'goods' => array(
                    'artno' => $product['model'],
                    'title' => $product['name'],
                    'price' => (int)str_replace('.', '', $this->currency->format($product['price'], $country_to_currency[$order_info['payment_iso_code_3']], '', false)),
                    'vat' => (float)$product['tax_rate'],
                    'discount' => 0.0,
                    'flags' => 0,
                )
            );
        }
        
        foreach ($totals as $total) {
            $goodsList[] = array(
                'qty' => 1,
                'goods' => array(
                    'artno' => '',
                    'title' => $total['title'],
                    'price' => (int) str_replace('.', '', $this->currency->format($total['value'], $country_to_currency[$order_info['payment_iso_code_3']], '', false)),
                    'vat' => (double) $total['tax_rate'],
                    'discount' => 0.0,
                    'flags' => 0,
                )
            );
        }
        
        $digest = '';
        
        foreach ($goodsList as $goods) {
            $digest .= $goods['goods']['title'] . ':';
        }
        
        
        $digest = base64_encode(pack('H*', hash('sha256', $digest . $settings['secret'])));
        
        if (isset($this->request->post['pno'])) {
            $pno = $this->request->post['pno'];
        } elseif (!empty($order_info['payment_company_id'])) {
            $pno = $order_info['payment_company_id'];
        } else {
            $day = sprintf("%02d", (int) $this->request->post['pno_day']);
            $month = sprintf("%02d", (int) $this->request->post['pno_month']);
            $year = (int) $this->request->post['pno_year']; 
            $pno = $day . $month . $year;
        }
        
        $pclass = (int) $this->request->post['payment_plan'];
        
        $gender = '';
        
        if ($order_info['payment_iso_code_3'] == 'DEU' || $order_info['payment_iso_code_3'] == 'NLD') {
            if (isset($this->request->post['gender'])) {
                $gender = (int) $this->request->post['gender'];
            } else {
                $gender = '';
            }
        }
        
        $transaction = array(
            '4.1',
            'API:OPENCART:' . VERSION,
            $pno,
            $gender,
            '',
            '', 
            (string) $order_info['order_id'], 
            '',
            $address, 
            $address, 
            $order_info['ip'],
            0, 
            $currency, 
            $country,
            $language, 
            (int) $settings['merchant'],
            $digest, 
            $encoding,
            $pclass, 
            $goodsList,
            $order_info['comment'],
            array('delay_adjust' => 1),
            array(),
            array(),
            array(),
            array(),
            array(),
        );
        
        $xml  = "<methodCall>";
        $xml .= "  <methodName>add_invoice</methodName>";
        $xml .= '  <params>';
        
        foreach ($transaction as $parameter)  {
            $xml .= '    <param><value>' . $this->constructXmlrpc($parameter) . '</value></param>';
        }
        
        $xml .= "  </params>";
        $xml .= "</methodCall>";        

        $ch = curl_init($server);

        $headers = array(
            'Content-Type: text/xml',
            'Content-Length: ' . strlen($xml),
        );

        curl_setopt($ch, CURLOPT_URL, $server);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $response = curl_exec($ch);
        
        $log = new Log('klarna_account.log');
        if (curl_errno($ch)) {
            $log->write('HTTP Error for order #' . $order_info['order_id'] . '. Code: ' . curl_errno($ch) . ' message: ' . curl_error($ch));
            $json['error'] = $this->language->get('error_network');
        } else {
            preg_match('/<member><name>faultString<\/name><value><string>(.+)<\/string><\/value><\/member>/', $response, $match);

            if (isset($match[1])) {
                preg_match('/<member><name>faultCode<\/name><value><int>([0-9]+)<\/int><\/value><\/member>/', $response, $match2);
                $log->write('Failed to create an invoice for order #' . $order_info['order_id'] . '. Message: ' . utf8_encode($match[1]) . ' Code: ' . $match2[1]);
                $json['error'] = utf8_encode($match[1]); 
            } else {
                $responseXml = new DOMDocument();
                $responseXml->loadXML($response);
                
                $invoiceNumber = $responseXml->getElementsByTagName('string')->item(0)->nodeValue;
                $klarnaOrderStatus = $responseXml->getElementsByTagName('int')->item(0)->nodeValue;

                if ($klarnaOrderStatus == '1') {
                    $orderStatus = $this->config->get('klarna_account_accepted_order_status_id');
                } elseif ($klarnaOrderStatus == '2') {
                    $orderStatus = $this->config->get('klarna_account_pending_order_status_id');
                } else {
                    $orderStatus = $this->config->get('config_order_status_id');
                }
                
                $orderComment = sprintf($this->language->get('text_order_comment'), $invoiceNumber, $this->config->get('config_currency'), $country_to_currency[$order_info['payment_iso_code_3']], $this->currency->getValue($country_to_currency[$order_info['payment_iso_code_3']]));
                
                $this->model_checkout_order->confirm($this->session->data['order_id'], $orderStatus, $orderComment , 1);
                
                $json['redirect'] = $this->url->link('checkout/success');
            }
        }
        
        curl_close($ch);
        
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
    
    private function splitAddress( $address ) {
        $numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        
        $characters = array('-', '/', ' ', '#', '.', 'a', 'b', 'c', 'd', 'e',
                        'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p',
                        'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A',
                        'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L',
                        'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
                        'X', 'Y', 'Z');
        
        $specialchars = array('-', '/', ' ', '#', '.');

        $numpos = $this->strposArr($address, $numbers, 2);

        $streetname = substr($address, 0, $numpos);

        $streetname = trim($streetname);

        $numberpart = substr($address, $numpos);
        
        $numberpart = trim($numberpart);

        $extpos = $this->strposArr($numberpart, $characters, 0);

        if ($extpos != '') {

            $housenumber = substr($numberpart, 0, $extpos);

            $houseextension = substr($numberpart, $extpos);

            $houseextension = str_replace($specialchars, '', $houseextension);
        } else {
            $housenumber = $numberpart;
            $houseextension = '';
        }

        return array($streetname, $housenumber, $houseextension);
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

    private function getLowestPaymentAccount($country) {
        switch ($country) {
            case 'SWE':
                $amount = 50.0;
                break;
            case 'NOR':
                $amount = 95.0;
                break;
            case 'FIN':
                $lowestPayment = 8.95;
                break;
            case 'DNK':
                $amount = 89.0;
                break;
            case 'DEU':
            case 'NLD':
                $amount = 6.95;
                break;

            default:
                $log = new Log('klarna.log');
                $log->write('Unknown country ' . $country);
                
				$this->redirect($this->url->link('checkout/checkout', 'SSL'));
                break;
		}
        
        return $amount;
    }
    
    private function showPartPaymentOptions($order_info, $settings) {        

    }
}
?>