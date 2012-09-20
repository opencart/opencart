<?php

class ControllerPaymentKlarna extends Controller {

    protected function index() {
        $this->load->model('checkout/order');
        $this->load->model('tool/image');
        $this->data = array_merge($this->data, $this->language->load('payment/klarna'));
        
        $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        $addressMatch = false;
        
        if ($orderInfo['payment_firstname'] == $orderInfo['shipping_firstname'] && $orderInfo['payment_lastname'] == $orderInfo['shipping_lastname'] && $orderInfo['payment_address_1'] == $orderInfo['shipping_address_1'] && $orderInfo['payment_address_2'] == $orderInfo['shipping_address_2'] && $orderInfo['payment_postcode'] == $orderInfo['shipping_postcode'] && $orderInfo['payment_city'] == $orderInfo['shipping_city'] && $orderInfo['payment_zone_id'] == $orderInfo['shipping_zone_id'] && $orderInfo['payment_zone_code'] == $orderInfo['shipping_zone_code'] && $orderInfo['payment_country_id'] == $orderInfo['shipping_country_id'] && $orderInfo['payment_country'] == $orderInfo['shipping_country'] && $orderInfo['payment_iso_code_3'] == $orderInfo['shipping_iso_code_3']) {
            $addressMatch = true;
        } else {
            $addressMatch = false;
        }
        
        if (empty($orderInfo['payment_company']) && empty($orderInfo['payment_company_id'])) {
            $this->data['is_company'] = false;
        } else {
            $this->data['is_company'] = true;
        }
        
        $this->data['phone_number'] = $orderInfo['telephone'];
        $this->data['company_id'] = $orderInfo['payment_company_id'];
        
        $country = $orderInfo['payment_iso_code_3'];
        
        if ($country == 'DEU' || $country == 'NLD') {
            $addressParts = $this->splitAddress($orderInfo['payment_address_1']);
            
            $this->data['street'] = $addressParts[0];
            $this->data['street_number'] = $addressParts[1];
            $this->data['street_extension'] = $addressParts[2];
            
            if($country == 'DEU') {
                $this->data['street_number'] = trim($addressParts[1] . ' ' . $addressParts[2]);
            }
        }
        
        $this->data['address_match'] = $addressMatch;
        $this->data['country_code'] = $orderInfo['payment_iso_code_3'];
        $this->data['klarna_send'] = $this->url->link('payment/klarna/send');
        
        $this->data['klarna_nld_warning_banner'] = $this->model_tool_image->resize('data/klarna_nld_warning.jpg', 950, 118);
        
        $partPaymentOptions = array();
        
        // Show part payment options?
        if ($this->showPartPaymentOptions($orderInfo)) {

            $pclasses = $this->config->get('klarna_pclasses');

            if (isset($pclasses[$country])) {
                $pclasses = $pclasses[$country];
            } else {
                $pclasses = array();
            }

            $orderTotal = $this->currency->format($orderInfo['total'], '', '', false);

            foreach ($pclasses as $pclass) {                
                // 0 - Campaign
                // 1 - Account
                // 2 - Special
                // 3 - Fixed
                if (!in_array($pclass['type'], array(0, 1, 3))) {
                    continue;
                }

                if ($pclass['type'] == 2) {
                    $monthlyCost = -1;
                } else {
                    if ($orderTotal < $pclass['minamount']) {
                        continue;
                    }

                    if ($pclass['type'] == 3) {
                        continue;
                    } else {
                        $sum = $orderTotal;

                        $lowestPayment = $this->getLowestPaymentAccount($country);
                        $monthlyCost = 0;

                        $monthsFee = $pclass['invoicefee'];
                        $startFee = $pclass['startfee'];

                        $sum += $startFee;

                        $base = ($pclass['type'] == 1);

                        $minpay = ($pclass['type'] === 1) ? $this->getLowestPaymentAccount($country) : 0;

                        if ($pclass['months'] == 0) {
                            $payment = $sum;
                        } elseif ($pclass['interestrate'] == 0) {
                            $payment = $sum / $pclass['months'];
                        } else {
                            $p = $pclass['interestrate'] / (100.0 * 12);
                            $payment = $sum * $p / (1 - pow((1 + $p), -$pclass['months']));
                        }

                        $payment += $monthsFee;

                        $bal = $sum;
                        $payarray = array();

                        $months = $pclass['months'];
                        while (($months != 0) && ($bal > 0.01)) {
                            $interest = $bal * $pclass['interestrate'] / (100.0 * 12);
                            $newbal = $bal + $interest + $monthsFee;

                            if ($minpay >= $newbal || $payment >= $newbal) {
                                $payarray[] = $newbal;
                                $payarray = $payarray;
                                break;
                            }

                            $newpay = max($payment, $minpay);
                            if ($base) {
                                $newpay = max($newpay, $bal / 24.0 + $monthsFee + $interest);
                            }

                            $bal = $newbal - $newpay;
                            $payarray[] = $newpay;
                            $months -= 1;
                        }

                        $monthlyCost = round(isset($payarray[0]) ? ($payarray[0]) : 0, 2);

                        if ($monthlyCost < 0.01) {
                            continue;
                        }

                        if ($pclass['type'] == 1 && $monthlyCost < $lowestPayment) {
                            $monthlyCost = $lowestPayment;
                        }

                        if ($pclass['type'] == 0 && $monthlyCost < $lowestPayment) {
                            continue;
                        }
                    }
                }
                
                $partPaymentOptions[$pclass['id']]['monthly_cost'] = $monthlyCost;
                $partPaymentOptions[$pclass['id']]['pclass_id'] = $pclass['id'];
                $partPaymentOptions[$pclass['id']]['months'] = $pclass['months'];
            }
            
        }
        
        usort($partPaymentOptions, array($this, 'sortPaymentPlans'));
        
        $this->data['part_payment_options'] = array();
        
        foreach ($partPaymentOptions as $paymentOption) {
            $this->data['part_payment_options'][$paymentOption['pclass_id']] = sprintf($this->language->get('text_monthly_payment'), $paymentOption['months'], $this->currency->format($paymentOption['monthly_cost'], '', 1.0));
        }
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/klarna.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/klarna.tpl';
        } else {
            $this->template = 'default/template/payment/klarna.tpl';
        }

        $this->render();
    }

    public function send() {
        $this->load->model('checkout/order');
        $this->load->model('checkout/coupon');
        
        $json = array();

        $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        if (!$orderInfo) {
            $this->response->setOutput(json_encode($json));
            return;
        }
        
        $discount = 0;
        
        if (isset($this->session->data['coupon'])) {
            $couponInfo = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);;
        } else {
            $couponInfo = false;
        }
        
        if ($couponInfo['type'] == 'F') {
            $couponInfo['discount'] = min($couponInfo['discount'], $subTotal);
        }
        
        if ($this->config->get('klarna_server') == 'live') {
            $server = 'https://payment.klarna.com/';
        } elseif ($this->config->get('klarna_server') == 'beta') {
            $server = 'https://payment-beta.klarna.com/';
        }
        
        switch ($orderInfo['payment_iso_code_3']) {
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
        
        $address = array(
            'email' => $orderInfo['email'],
            'telno' => $this->request->post['phone_no'],
            'cellno' => '',
            'fname' => $orderInfo['payment_firstname'],
            'lname' => $orderInfo['payment_lastname'],
            'company' => $orderInfo['payment_company'],
            'careof' => '',
            'street' => trim($orderInfo['payment_address_1'] . ' ' . $orderInfo['payment_address_2']),
            'house_number' => '',
            'house_extension' => '',
            'zip' => $orderInfo['payment_postcode'],
            'city' => $orderInfo['payment_city'],
            'country' => $country,
        );
        
        if ($orderInfo['payment_iso_code_3'] == 'DEU' || $orderInfo['payment_iso_code_3'] == 'NLD') {
            $address['street'] = $this->request->post['street'];
            $address['house_number'] = $this->request->post['house_no'];
        }
        
        if ($orderInfo['payment_iso_code_3'] == 'NLD') {
            $address['house_extension'] = $this->request->post['house_ext'];
        }
        
        // Discounts
        $result = $this->db->query("SELECT (SELECT ABS(SUM(`value`)) FROM `" . DB_PREFIX . "order_total` WHERE (`code` = 'credit' OR `code` = 'voucher') AND `order_id` = " . (int) $orderInfo['order_id'] . ") AS `credit`, (SELECT ABS(SUM(`value`)) FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = " . (int) $orderInfo['order_id'] . " AND `value` < 0 AND `code` != 'credit' AND `code` != 'voucher') AS `discount`")->row;
        
        $totalDiscount = $result['discount'];
        if ($couponInfo && $couponInfo['shipping'] == '1') {
            $totalDiscount -= $this->session->data['shipping_method']['cost'];
        }
        
        $totalCredit = $result['credit'];

        $goodsList = array();
        
        foreach ($this->cart->getProducts() as $product) {
            $price = 0;
            
            $discount = min($product['total'], $totalDiscount);
            $totalDiscount -= $discount;
            
            $credit = min($product['total'] - $discount, $totalCredit);
            $totalCredit -= $credit;
            
            $productTax = $this->tax->getTax($product['price'] - $discount / $product['quantity'], $product['tax_class_id']);
            
            $price = $product['price'] - $credit / $product['quantity'] - $discount / $product['quantity'] + $productTax;
            
            $goodsList[] = array(
                'qty' => (int) $product['quantity'],
                'goods' => array(
                    'artno' => $product['model'],
                    'title' => $product['name'],
                    'price' => (int) str_replace('.', '', $this->currency->format($price, '', '', false)),
                    'vat' => 0.0,
                    'discount' => 0.0,
                    'flags' => 32,
                )
            );
        }
        
        if ($couponInfo && $couponInfo['shipping'] == '1') {
            $price = $this->tax->getTax(0, $this->session->data['shipping_method']['tax_class_id']);
        } else {
            $price = $this->tax->calculate($this->session->data['shipping_method']['cost'], $this->session->data['shipping_method']['tax_class_id']);
        }    

        $goodsList[] = array(
            'qty' => 1,
            'goods' => array(
                'artno' => $orderInfo['shipping_code'],
                'title' => $orderInfo['shipping_method'],
                'price' => (int) str_replace('.', '', $this->currency->format($price, '', '', false)),
                'vat' => 0.0,
                'discount' => 0.0,
                'flags' => 8 + 32,
            )
        );
        
        // Klarna Fee and other handling fees
        
        $results = $this->db->query("SELECT `code`, `value` FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = " . (int) $orderInfo['order_id'] . "")->rows;
        
        $fees = 0;
        
        foreach ($results as $result) {
            if ($result['code'] == 'handling') {
                $fees += $this->tax->calculate($result['value'], $this->config->get('handling_tax_class_id'));
            }
            
            if ($result['code'] == 'klarna_fee') {
                $fees += $this->tax->calculate($result['value'], $this->config->get('klarna_fee_tax_class_id'));
            }
            
            if ($result['code'] == 'low_order_fee') {
                $fees += $this->tax->calculate($result['value'], $this->config->get('low_order_fee_tax_class_id'));
            }
        }
        
        if ($fees > 0) {
            $goodsList[] = array(
                'qty' => 1,
                'goods' => array(
                    'artNo' => '',
                    'title' => 'Handling fees',
                    'price' => (int) str_replace('.', '', $this->currency->format($fees, '', '', false)),
                    'vat' => 0.0,
                    'discount' => 0.0,
                    'flags' => 16 + 32,
                )
            );
        }

        $digest = '';
        
        foreach ($goodsList as $goods) {
            $digest .= $goods['goods']['title'] . ':';
        }
        
        $digest = base64_encode(pack('H*', hash('sha256', $digest . $this->config->get('klarna_secret'))));
        
        if (isset($this->request->post['pno'])) {
            $pno = $this->request->post['pno'];
        } elseif (!empty($orderInfo['payment_company_id'])) {
            $pno = $orderInfo['payment_company_id'];
        } else {
            $day = sprintf("%02d", (int) $this->request->post['pno_day']);
            $month = sprintf("%02d", (int) $this->request->post['pno_month']);
            $year = (int) $this->request->post['pno_year']; 
            $pno = $day . $month . $year;
        }
        
        $pclass = -1;
        
        if ($this->showPartPaymentOptions($orderInfo)) {
            $pclass = (int) $this->request->post['payment_plan'];
        }
        
        $yearlySalary = array();
        if ($country == 'DNK' && $pclass != -1) {
            $yearlySalary['yearly_salary'] = (int) $this->request->post['yearly_salary'];
        }
        
        $gender = 1;
        
        if ($orderInfo['payment_iso_code_3'] == 'DEU' || $orderInfo['payment_iso_code_3'] == 'NLD') {
            $gender = (int) $this->request->post['gender'];
        }
        
        $transaction = array(
            '4.1',
            'API:OPENCART:' . VERSION,
            $pno,
            $gender,
            '',
            '', 
            (string) $this->session->data['order_id'], 
            '',
            $address, 
            $address, 
            $orderInfo['ip'], 
            0, 
            $currency, 
            $country,
            $language, 
            (int) $this->config->get('klarna_merchant'),
            $digest, 
            $encoding,
            $pclass, 
            $goodsList,
            $orderInfo['comment'],
            array('delay_adjust' => 1),
            array(),
            $yearlySalary, // yearly_salary for customers in Denmark when Part Payments are used
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

        $response = curl_exec($ch);
        
        $log = new Log('klarna.log');
        if (curl_errno($ch)) {
            $log->write('HTTP Error for order #' . $orderInfo['order_id'] . '. Code: ' . curl_errno($ch) . ' message: ' . curl_error($ch));
            $json['error'] = $this->language->get('error_network');
        } else {
            preg_match('/<member><name>faultString<\/name><value><string>(.+)<\/string><\/value><\/member>/', $response, $match);

            if (isset($match[1])) {
                preg_match('/<member><name>faultCode<\/name><value><int>([0-9]+)<\/int><\/value><\/member>/', $response, $match2);
                $log->write('Failed to create an invoice for order #' . $orderInfo['order_id'] . '. Message: ' . utf8_encode($match[1]) . ' Code: ' . $match2[1]);
                $json['error'] = utf8_encode($match[1]); 
            } else {
                $xml = simplexml_load_string($response);
                
                $invoiceNumber = (string) $xml->params->param->value->array->data->value[0]->string;
                $klarnaOrderStatus = (int) $xml->params->param->value->array->data->value[1]->int;

                if ($klarnaOrderStatus == 1) {
                    $orderStatus = $this->config->get('klarna_accepted_order_status_id');
                } elseif ($klarnaOrderStatus == 2) {
                    $orderStatus = $this->config->get('klarna_pending_order_status_id');
                } else {
                    $orderStatus = $this->config->get('config_order_status_id');
                }
                
                $this->model_checkout_order->confirm($this->session->data['order_id'], $orderStatus, "Klarna's Invoice ID: " . $invoiceNumber, 1);
                
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
                $xml = '<int>' . (int) $data . '</int>';
                break;
            
            case 'double':
                $xml = '<double>' . (double) $data . '</double>';
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
                $lowestPayment = 50.0;
                break;
            case 'NOR':
                $lowestPayment = 95.0;
                break;
            case 'FIN':
                $lowestPayment = 8.95;
                break;
            case 'DNK':
                $lowestPayment = 89.0;
                break;
            case 'DEU':
            case 'NLD':
                $lowestPayment = 6.95;
                break;

            default:
                $log = new Log('klarna.log');
                $log->write('Unknown country ' . $country);
                $this->redirect($this->url->link('checkout/checkout', 'SSL'));
                break;
        }
        
        return $lowestPayment;
    }
    
    private function showPartPaymentOptions($orderInfo) {
        $status = $this->config->get('klarna_acc_status') == '1';        
        
        $countAcc = $this->db->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int) $this->config->get('klarna_acc_geo_zone_id') . "' AND `country_id` = '" . (int) $orderInfo['payment_country_id'] . "' AND (`zone_id` = '" . (int)$orderInfo['payment_zone_id'] . "' OR `zone_id` = 0)")->row['count'];
        
        if ($this->config->get('klarna_acc_geo_zone_id') != 0 && $countAcc == 0) {
            $status = false;
        }
        
        if (!empty($orderInfo['payment_company']) || !empty($orderInfo['payment_company_id'])) {
            $status = false;
        }
        
        if ($orderInfo['payment_iso_code_3'] == 'NLD' && $this->currency->format($orderInfo['total'], 'EUR', '', false) > 250.00) {
            $status = false;
        }

        return $status;
    }
    
    private function sortPaymentPlans($a, $b) {
        return $a['months'] - $b['months'];
    }

}