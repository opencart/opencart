<?php

class ModelPaymentKlarna extends Model {

    public function getMethod($address, $total) {        
        $this->language->load('payment/klarna');
        
        $klarnaInvoiceStatus = true;
        $klarnaAccountStatus = true;
        
        $countAcc = $this->db->query("SELECT COUNT(*) AS `count` FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('klarna_acc_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')")->row['count'];
        $countInv = $this->db->query("SELECT COUNT(*) AS `count` FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('klarna_inv_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')")->row['count'];
        
        
        if ($this->config->get('klarna_minimum_amount') > $total) {
            $klarnaAccountStatus = false;
            $klarnaInvoiceStatus = false;
        }
        
        if ($this->config->get('klarna_acc_geo_zone_id') != 0 && $countAcc == 0) {
            $klarnaAccountStatus = false;
        }
        
        if ($this->config->get('klarna_inv_geo_zone_id') != 0 && $countInv == 0) {
            $klarnaInvoiceStatus = false;
        }
        
        /* Maps countries to currencies */
        $countries = array(
            'NOR' => 'NOK',
            'SWE' => 'SEK',
            'FIN' => 'EUR',
            'DNK' => 'DKK',
            'DEU' => 'EUR',
            'NLD' => 'EUR',
        );
        
        if(!isset($countries[$address['iso_code_3']]) || $countries[$address['iso_code_3']] != $this->currency->getCode()) {
            $klarnaAccountStatus = false;
            $klarnaInvoiceStatus = false;
        }
        
        $method = array();
        
        if ($klarnaAccountStatus || $klarnaAccountStatus) {
            
            $invoiceFee = $this->config->get('klarna_invoice_fee');
            
            if (!empty($invoiceFee)) {
                $title = sprintf($this->language->get('text_title_with_fee'), $this->currency->format($invoiceFee));
            } else {
                $title = $this->language->get('text_title');
            }
            
            $method = array(
                'code' => 'klarna',
                'title' => $title,
                'sort_order' => $this->config->get('klarna_sort_order')
            );
        }
        
        return $method;
    }
    
}
