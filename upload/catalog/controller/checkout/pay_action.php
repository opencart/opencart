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

        if(!empty($this->session->data['order_id']) || (isset($this->request->get['order_id']) && $this->request->get["token"] == md5($this->request->get['order_id'] . $this->session->data['token']))) {
            $order_id = !empty($this->session->data['order_id']) ? $this->session->data['order_id'] : $this->request->get['order_id'];
            $token = isset($this->request->get["token"]) ? $this->request->get["token"] : "";

            $data['paypal_express_url'] = $this->url->link('payment/pp_express/checkout&order_id=' . $order_id . "&token=" . $token, true);
            $data['paypal_pro_url'] = $this->url->link('payment/pp_pro&order_id=' . $order_id . "&token=" . $token, true);

            $data['heading_title'] = $this->language->get('heading_title');

            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('checkout/pay_action', $data));
        } else {
            $this->response->redirect($this->url->link('account/order_error', '', true));
        }
    }
}