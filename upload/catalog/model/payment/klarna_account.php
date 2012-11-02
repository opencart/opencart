<?php

class ModelPaymentKlarnaAccount extends Model {

    public function getMethod($address, $total) {        
        $this->language->load('payment/klarna_account');
        
        $klarnaCountry = $this->config->get('klarna_account_country');
        $settings = $klarnaCountry[$address['iso_code_3']];
        
        $klarnaAccountStatus = $settings['status'] == '1';
        
        $zoneCount = $this->db->query("SELECT COUNT(*) AS `count` FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int) $settings['geo_zone_id'] . "' AND `country_id` = '" . (int) $address['country_id'] . "' AND (`zone_id` = '" . (int) $address['zone_id'] . "' OR `zone_id` = 0)")->row['count'];
        
        $minimumTotal = (double) $this->config->get('klarna_account_minimum_amount');
        
        if ($minimumTotal > 0 && $minimumTotal > $total) {
            $klarnaAccountStatus = false;
        }
        
        if ($settings['geo_zone_id'] != 0 && $zoneCount == 0) {
            $klarnaAccountStatus = false;
        }
        
        // Maps countries to currencies
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
        }        
        
        if ($address['iso_code_3'] == 'NLD' && $this->currency->has('EUR') && $this->currency->format($total, 'EUR', '', false) > 250.00) {
            $klarnaAccountStatus = false;
        }
        
        $method = array();
        
        if ($klarnaAccountStatus) {

            $method = array(
                'code' => 'klarna_account',
                'title' => sprintf($this->language->get('text_title'), '$14.00'),
                'sort_order' => $settings['sort_order'],
            );
        }
        
        return $method;
    }
    
}
