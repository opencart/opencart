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
			} elseif ($klarna_account[$address['iso_code_3']]['geo_zone_id'] && $query->num_rows) {
				$status = false;
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
			
			if (!isset($country_to_currency[$address['iso_code_3']]) || !$this->currency->has($country_to_currency[$address['iso_code_3']])) {
				$status = false;
			} 
			
			if ($address['iso_code_3'] == 'NLD' && $this->currency->has('EUR') && $this->currency->format($total, 'EUR', '', false) > 250.00) {
				$status = false;
			}			
		}
        
		$allPclasses = $this->config->get('klarna_account_pclasses');
		
		if (isset($allPclasses[$address['iso_code_3']])) {
			$pclasses = $allPclasses[$address['iso_code_3']];
		} else {
			$pclasses = array();
		}

		$payment_option = array();
		
		if ($status) {
			
			$total = $this->currency->format($total, $country_to_currency[$address['iso_code_3']], '', false);
			
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
						$monthly_cost = -1;
					} else {
						if ($total < $pclass['minamount']) {
							continue;
						}

						if ($pclass['type'] == 3) {
							continue;
						} else {
							$sum = $total;

							$lowest_payment = $this->getLowestPaymentAccount($address['iso_code_3']);
							$monthly_cost = 0;

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

							$monthly_cost = round(isset($payarray[0]) ? ($payarray[0]) : 0, 2);

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
				}
			}
		}
		
		if (!$payment_option) {
			$status = false;
		}
		
		usort($payment_option, array($this, 'sortPaymentPlans'));
		
		
		
		if (!empty($address['company']) || !empty($address['company_id'])) {
			$klarnaAccountStatus = false;
		}
		
			
		$method = array();
		
		if ($status) {
			$method = array(
				'code'       => 'klarna_account',
				'title'      => sprintf($this->language->get('text_title'), $this->currency->format($this->currency->convert($payment_option[0]['monthly_cost'], $countryToCurrency[$address['iso_code_3']], $this->currency->getCode()), 1, 1), $settings['merchant'], strtolower($address['iso_code_2'])),
				'sort_order' => $settings['sort_order']
			);
		}
		
        return $method;
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
                $amount = 6.95;
                break;
            default:
                $log = new Log('klarna_account.log');
                $log->write('Unknown country ' . $country);
                
				$amount = NULL;
                break;
        }

        return $amount;
    }

    private function sortPaymentPlans($a, $b) {
        return $a['monthly_cost'] - $b['monthly_cost'];
    }
}
?>