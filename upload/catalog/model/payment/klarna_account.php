<?php
class ModelPaymentKlarnaAccount extends Model {
    public function getMethod($address, $total) {
        $this->language->load('payment/klarna_account');
		
		$status = true;

		$klarna_account = $this->config->get('klarna_account');
		
		if (!isset($klarna_account[$address['iso_code_3']])) {
			$status = false;
		} elseif (!$klarna_account[$address['iso_code_3']]['status']) {
			$status = false;
		}
		
		if ($status) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$klarna_account[$address['iso_code_3']]['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if ($klarna_account[$address['iso_code_3']]['total'] > $total) {
				$status = false;
			} elseif (!$klarna_account[$address['iso_code_3']]['geo_zone_id']) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}
			
			// Maps countries to currencies
			$country_to_currency = array(
				'NOR' => 'NOK',
				'SWE' => 'SEK',
				'FIN' => 'EUR',
				'DNK' => 'DKK',
				'DEU' => 'EUR',
				'NLD' => 'EUR'
			);
			
				
		}
		


        
        if(!isset($countryToCurrency[$address['iso_code_3']]) || !$this->currency->has($countryToCurrency[$address['iso_code_3']])) {
            $klarnaAccountStatus = false;
        }  
        
        if ($address['iso_code_3'] == 'NLD' && $this->currency->has('EUR') && $this->currency->format($total, 'EUR', '', false) > 250.00) {
            $klarnaAccountStatus = false;
        }
        
        $allPclasses = $this->config->get('klarna_account_pclasses');
        
        if (isset($allPclasses[$address['iso_code_3']])) {
            $pclasses = $allPclasses[$address['iso_code_3']];
        } else {
            $pclasses = array();
        }

        $paymentOptions = array();
        
        if ($klarnaAccountStatus) {
            
            $total = $this->currency->format($total, $countryToCurrency[$address['iso_code_3']], '', false);
            
            foreach ($pclasses as $pclass) {
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
                        if ($total < $pclass['minamount']) {
                            continue;
                        }

                        if ($pclass['type'] == 3) {
                            continue;
                        } else {
                            $sum = $total;

                            $lowestPayment = $this->getLowestPaymentAccount($address['iso_code_3']);
                            $monthlyCost = 0;

                            $monthsFee = $pclass['invoicefee'];
                            $startFee = $pclass['startfee'];

                            $sum += $startFee;

                            $base = ($pclass['type'] == 1);

                            $minpay = ($pclass['type'] === 1) ? $this->getLowestPaymentAccount($address['iso_code_3']) : 0;

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

                    $paymentOptions[$pclass['id']]['monthly_cost'] = $monthlyCost;
                    $paymentOptions[$pclass['id']]['pclass_id'] = $pclass['id'];
                    $paymentOptions[$pclass['id']]['months'] = $pclass['months'];
                }
            }
        }
        
        if (empty($paymentOptions)) {
            $klarnaAccountStatus = false;
        }
        
        usort($paymentOptions, array($this, 'sortPaymentPlans'));
        
        $method = array();
        
        if (!empty($address['company']) || !empty($address['company_id'])) {
            $klarnaAccountStatus = false;
        }
        
        if ($klarnaAccountStatus && !empty($paymentOptions)) {
            
            $method = array(
                'code' => 'klarna_account',
                'title' => sprintf($this->language->get('text_title'), $this->currency->format($this->currency->convert($paymentOptions[0]['monthly_cost'], $countryToCurrency[$address['iso_code_3']], $this->currency->getCode()), 1, 1), $settings['merchant'], strtolower($address['iso_code_2'])),
                'sort_order' => $settings['sort_order'],
            );
        }

        return $method;
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
                $log = new Log('klarna_account.log');
                $log->write('Unknown country ' . $country);
                $lowestPayment = null;
                break;
        }

        return $lowestPayment;
    }

    private function sortPaymentPlans($a, $b) {
        return $a['monthly_cost'] - $b['monthly_cost'];
    }

}
