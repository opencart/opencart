<?php
namespace Opencart\Application\Controller\Event;
class Localisation extends \Opencart\System\Engine\Controller {
    // catalog/model/checkout/order/addHistory/after
    public function validateZone(&$route, &$args, &$output) {
        $this->load->model('checkout/order');
        
        $order_info = $this->model_checkout_order->getOrder($args[0]);
        
        if ($order_info) {
            $this->load->language('checkout/checkout');
            
            $this->load->model('localisation/zone');
            
            $zones = $this->model_localisation_zone->getZonesByCountryId($order_info['payment_country_id']);
            
            if ($zones) {
                $zone_names = array_column($zones, 'name');                
                $zones_acv = array_count_values($zone_names);
                
                if ($zones_acv) {
                    foreach ($zones_acv as $zones_total => $zone_name) {
                        if ($zones_total > 1 && (($order_info['payment_zone'] == $zone_name) || ($order_info['shipping_zone'] == $zone_name))) {
                            $comment = sprintf($this->language->get('error_duplicate_zone'), $zone_name);
                        
                            $this->model_checkout_prder->addHistory($order_info['order_id'], $order_info['order_status_id'], $comment);
                        }
                    }
                }
            }
        }
    }
}
