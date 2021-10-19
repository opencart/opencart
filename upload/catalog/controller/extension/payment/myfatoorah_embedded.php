<?php

require_once 'myfatoorah_controller.php';

class ControllerExtensionPaymentMyfatoorahEmbedded extends MyfatoorahController {

    protected $id = 'myfatoorah_embedded';

//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function __construct($registry) {
        parent::__construct($registry);
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index() {

        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET payment_method = '" . $this->db->escape($this->id) . "' WHERE order_id = '" . (int) $this->session->data['order_id'] . "'");
//        $this->db->query("UPDATE `" . DB_PREFIX . "order` a INNER JOIN `" . DB_PREFIX . "order` b ON a.order_id = b.order_id SET a.payment_method = '" . $this->db->escape($this->id) . "' where b.payment_method like '<div%' ");

        $data = $this->language->load($this->path);

        if (($this->config->get($this->ocCode . '_test') != '1') && (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')) {
            $data['error'] = $data['error_enable_ssl'];
        } else {
            $data['action'] = 'index.php?route=' . $this->path . '/confirm';
            try {
                $data['session'] = $this->mfObj->getEmbeddedSession();
            } catch (Exception $ex) {
                $data['error'] = $ex->getMessage();
            }
        }
        return $this->load->view($this->path, $data);
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
    public function confirm() {

        if (!isset($this->session->data['order_id'])) {
            $this->session->data['error'] = 'Session has been expired please try again later';
            return $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }

        $this->load->model('checkout/order');

        $this->orderId = $this->session->data['order_id'];
        $order_info    = $this->model_checkout_order->getOrder($this->orderId);

        try {
            $curlData = $this->getPayload($order_info);

            $sessionId = $this->request->post['mfData'];

            $json = $this->mfObj->embeddedPayment($curlData, $sessionId, $this->orderId);
            $msg  = '<b>MyFatoorah Invoice Details:</b><br> Invoice ID ' . $json['invoiceId'] . '<br>';
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get($this->ocCode . '_initial_order_status_id'), $msg, false); //We don't need to send a pending email to the customer.

            $this->response->redirect($json['invoiceURL']);
        } catch (Exception $ex) {
            $this->session->data['error'] = $ex->getMessage();
            $this->response->redirect($this->url->link('checkout/checkout', '', true));
        }
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------
}
