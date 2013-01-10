<?php
class ModelPaymentKlarnaInvoice extends Model {
    public function getMethod($address, $total) {        
        $this->language->load('payment/klarna_invoice');
		
		$status = true;
		
		$klarna_invoice = $this->config->get('klarna_invoice');
		
		if (!isset($klarna_invoice[$address['iso_code_3']])) {
			$status = false;
		} elseif (!$klarna_invoice[$address['iso_code_3']]['status']) {
			$status = false;
		}
        
		if ($status) {  
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$klarna_invoice[$address['iso_code_3']]['geo_zone_id'] . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if ($klarna_invoice[$address['iso_code_3']]['total'] > 0 && $klarna_invoice[$address['iso_code_3']]['total'] > $total) {
				$status = false;
			} elseif (!$klarna_invoice[$address['iso_code_3']]['geo_zone_id']) {
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
				'NLD' => 'EUR',
			);				
				
			if (!isset($country_to_currency[$address['iso_code_3']]) || !$this->currency->has($country_to_currency[$address['iso_code_3']])) {
				$status = false;
			} 
			
			if ($address['iso_code_3'] == 'NLD' && $this->currency->has('EUR') && $this->currency->format($total, 'EUR', '', false) > 250.00) {
				$status = false;
			}
		}
         
        $method = array();
        
        if ($status) {
            $iso3 = $this->db->query("SELECT `iso_code_3` FROM `" . DB_PREFIX . "country` WHERE `country_id` = " . (int) $this->session->data['payment_country_id'])->row['iso_code_3'];

            $countries = $this->config->get('klarna_fee_country');
            $country = $countries[$iso3];

            if ($country['status'] == 1 && $this->cart->getSubTotal() < $country['total']) {
                $klarnaFee = $this->currency->format($this->tax->calculate($country['fee'], $country['tax_class_id']), $countryToCurrency[$address['iso_code_3']], '', false);
                $klarnaFeeText = $this->currency->format($this->tax->calculate($country['fee'], $country['tax_class_id']), '', '');
                
                $title = sprintf($this->language->get('text_title'), $klarnaFeeText, $settings['merchant'], strtolower($address['iso_code_2']), $klarnaFee);
            } else {
                $title = sprintf($this->language->get('text_title_no_fee'), $settings['merchant'], strtolower($address['iso_code_2']));
            }
            
            $method = array(
                'code'       => 'klarna_invoice',
                'title'      => $title,
                'sort_order' => $settings['sort_order'],
            );
        }
        
        return $method;
    }
}
?>