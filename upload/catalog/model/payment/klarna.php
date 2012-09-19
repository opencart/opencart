<?php

class ModelPaymentKlarna extends Model {

    public function getMethod($address, $total) {        
        $this->language->load('payment/klarna');
        
        $klarnaInvoiceStatus = $this->config->get('klarna_inv_status') == '1';
        $klarnaAccountStatus = $this->config->get('klarna_acc_status') == '1';
        
        $countAcc = $this->db->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int) $this->config->get('klarna_acc_geo_zone_id') . "' AND `country_id` = '" . (int) $address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = 0)")->row['count'];
        $countInv = $this->db->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int) $this->config->get('klarna_inv_geo_zone_id') . "' AND `country_id` = '" . (int) $address['country_id'] . "' AND (`zone_id` = '" . (int) $address['zone_id'] . "' OR `zone_id` = 0)")->row['count'];
        
        $minimumTotal = (double) $this->config->get('klarna_minimum_amount');
        
        if ($minimumTotal > 0 && $minimumTotal > $total) {
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
        
        if ($address['iso_code_3'] == 'NLD' && $this->currency->has('EUR') && $this->currency->format($total, 'EUR', '', false) > 250.00) {
            $klarnaAccountStatus = false;
        }
        
        $method = array();
        
        if ($klarnaAccountStatus || $klarnaAccountStatus) {
            
            $method = array(
                'code' => 'klarna',
                'title' => $this->language->get('text_title'),
                'sort_order' => $this->config->get('klarna_sort_order')
            );
        }
        
        return $method;
    }
    
}
