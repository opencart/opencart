<?php

class ControllerModulePPLayout extends Controller {

    protected function index($setting) {
        $status = $this->config->get('pp_express_status');
        
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || 
                (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout')) || 
                (!$this->customer->isLogged() && ($this->cart->hasRecurringProducts() || $this->cart->hasDownload()))) {
            
            $status = false;
        }
        
        if ($status) {

            $this->load->model('payment/pp_express');

            $this->data['is_mobile'] = $this->model_payment_pp_express->isMobile();

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/pp_layout.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/module/pp_layout.tpl';
            } else {
                $this->template = 'default/template/module/pp_layout.tpl';
            }

            $this->data['payment_url'] = $this->url->link('payment/pp_express/express', '', 'SSL');

            $this->render();
        }
    }

}

?>