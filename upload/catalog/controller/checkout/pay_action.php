<?php
/**
 * Created by PhpStorm.
 * User: Jack Wang
 * Date: 2016-08-02
 * Time: 19:44
 */
class ControllerCheckoutPayAction extends Controller {
    public function index() {
        //
        $this->load->language('checkout/pay_action');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', true)
        );

        $data['paypal_express_url'] = $this->url->link('payment/pp_express/checkout', true);
        $data['paypal_pro_url'] = $this->url->link('payment/pp_pro', true);

        $data['heading_title'] = $this->language->get('heading_title');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('checkout/pay_action', $data));
    }
}