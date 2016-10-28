<?php
/**
 * Created by PhpStorm.
 * User: Jack Wang
 * Date: 2016-08-02
 * Time: 18:53
 */

class ControllerPaymentPayOnline extends Controller{
    public function index() {
        $data['button_confirm'] = $this->language->get('button_confirm');

        $data['text_loading'] = $this->language->get('text_loading');

        $data['continue'] = $this->url->link('checkout/pay_action');

        return $this->load->view('payment/pay_online', $data);
    }
}