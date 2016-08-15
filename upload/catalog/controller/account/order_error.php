<?php
/**
 * Created by PhpStorm.
 * User: Jack Wang
 * Date: 2016-09-23
 * Time: 17:47
 */
class ControllerAccountOrderError extends Controller {
    public function index() {
        $this->load->language("account/order");

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
            'text' => $this->language->get('text_history'),
            'href' => $this->url->link('account/order', '', true)
        );

        $data['order_history_href'] = $this->url->link("account/order", '', true);

        $data['heading_error_title'] = $this->language->get('heading_error_title');
        $data['text_repay'] = $this->language->get("text_repay");
        $data['text_order_error'] = $this->language->get("text_order_error");

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('account/order_error', $data));
    }
}