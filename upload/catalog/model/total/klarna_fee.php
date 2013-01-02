<?php

class ModelTotalKlarnaFee extends Model {

    public function getTotal(&$total_data, &$total, &$taxes) {
        $this->load->language('total/klarna_fee');
        
        if (isset($this->session->data['payment_method']['code']) && $this->session->data['payment_method']['code'] == 'klarna_invoice') {
            $iso3 = $this->db->query("SELECT `iso_code_3` FROM `" . DB_PREFIX . "country` WHERE `country_id` = " . (int) $this->session->data['payment_country_id'])->row['iso_code_3'];

            $countries = $this->config->get('klarna_fee_country');            
            $country = $countries[$iso3];
            
            if ($country['status'] == 1 && $this->cart->getSubTotal() < $country['total']) {
                $total_data[] = array(
                    'code' => 'klarna_fee',
                    'title' => $this->language->get('text_klarna_fee'),
                    'text' => $this->currency->format($country['fee']),
                    'value' => $country['fee'],
                    'sort_order' => $country['sort_order'],
                );
                
                $total += $country['fee'];
                
                $taxRates = $this->tax->getRates($country['fee'], $country['tax_class_id']);
                
                foreach ($taxRates as $taxRate) {
                    if (!isset($taxes[$taxRate['tax_rate_id']])) {
                        $taxes[$taxRate['tax_rate_id']] = $taxRate['amount'];
                    } else {
                        $taxes[$taxRate['tax_rate_id']] += $taxRate['amount'];
                    }
                }
            }
        }
    }

}