<?php
namespace Opencart\Application\Controller\Event;
class Localisation extends \Opencart\System\Engine\Controller {
    // catalog/model/checkout/order/addHistory/after
    public function validateCountry(&$route, &$args, &$output) {
        $this->load->model('checkout/order');
        
        $order_info = $this->model_checkout_order->getOrder($args[0]);
        
        if ($order_info) {
            $this->load->language('checkout/checkout');
            
            $this->load->model('localisation/country');
            
            $payment_countries_total = $this->model_localisation_country->getTotalCountriesByName($order_info['payment_country']);            
            $shipping_countries_total = $this->model_localisation_country->getTotalCountriesByName($order_info['shipping_country']);
            
            if (($payment_countries_total > 1) || ($shipping_countries_total > 1)) {
                $comment = sprintf($this->language->get('error_country_duplicate'), $order_info['payment_country'], $order_info['shipping_country']);
                        
                $this->model_checkout_prder->addHistory($order_info['order_id'], $order_info['order_status_id'], $comment);
            }
        }
    }
    
    // catalog/model/checkout/order/addHistory/after
    public function validateZone(&$route, &$args, &$output) {
        $this->load->model('checkout/order');
        
        $order_info = $this->model_checkout_order->getOrder($args[0]);
        
        if ($order_info) {
            $this->load->language('checkout/checkout');
            
            $this->load->model('localisation/zone');
            
            $payment_zones_total = $this->model_localisation_zone->getTotalZonesByName($order_info['payment_country']);
            $shipping_zones_total = $this->model_localisation_zone->getTotalZonesByName($order_info['shipping_country']);
            
            if (($payment_zones_total > 1) || ($shipping_zones_total > 1)) {
                $comment = sprintf($this->language->get('error_country_duplicate'), $order_info['payment_zone'], $order_info['shipping_zone']);
                        
                $this->model_checkout_prder->addHistory($order_info['order_id'], $order_info['order_status_id'], $comment);
            }
        }
    }
}
