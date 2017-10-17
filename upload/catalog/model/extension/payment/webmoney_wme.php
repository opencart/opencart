<?php
class ModelExtensionPaymentWebmoneyWME extends Model {
    public function getMethod($address, $total) {
        $this->load->language('extension/payment/webmoney_wme');
        
        if (($this->config->get('payment_webmoney_wme_status')) && ($total) &&
            (!$this->config->get('payment_webmoney_wme_minimal_order') || ($total >= (float)$this->config->get('payment_webmoney_wme_minimal_order'))) &&
            (!$this->config->get('payment_webmoney_wme_maximal_order') || ($total <= (float)$this->config->get('payment_webmoney_wme_maximal_order')))) {
              $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_webmoney_wme_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
          
            if (!$this->config->get('payment_webmoney_wme_geo_zone_id')) {
                $status = true;
            } elseif ($query->num_rows) {
                $status = true;
            } else {
                $status = false;
            }
            
            if (!$this->currency->has('EUR')) {
                $status = false;
            }

            //hide_mode
            if ($this->config->get('payment_webmoney_wme_hide_mode')) {
                $this->user = new Cart\User($this->registry);
                if (!$this->user->isLogged()) {
                    $status = false;
                }
            }
        } else {
            $status = false;
        }
        
        $method_data = array();
        
        if ($status) {
            $method_data = array(
                'code'        => 'webmoney_wme',
                'title'       => $this->language->get('text_title'),
                'terms'       => '',
                'sort_order'  => $this->config->get('payment_webmoney_wme_sort_order')
            );
        }
        return $method_data;
    }
}
?>