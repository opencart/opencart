<?php
class ControllerPaymentLiqPay extends Controller {
  protected function index() {
    $this->data['button_confirm'] = $this->language->get('button_confirm');
    $this->data['button_back'] = $this->language->get('button_back');

    $this->load->model('checkout/order');

    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

    $this->data['action'] = 'https://liqpay.com/?do=click_n_buy';

    $description = $this->config->get('config_store')."  ".$order_info['payment_firstname']."  ".$order_info['payment_address_1']."  ".$order_info['payment_address_2']."  ".$order_info['payment_city']."  ".$order_info['email'];

    $xml_request = "<request>
              <version>1.2</version>
              <result_url>".$this->url->https('checkout/success')."</result_url>
              <server_url>".$this->url->https('payment/liqpay/callback')."</server_url>
              <merchant_id>".$this->config->get('liqpay_merchant_id')."</merchant_id>
              <order_id>".$this->session->data['order_id']."</order_id>
              <amount>".$this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE)."</amount>
              <currency>".$order_info['currency']."</currency>
              <description>".$description."</description>
              <default_phone></default_phone>
              <pay_way>".$this->config->get('liqpay_liqpay_type')."</pay_way>
          </request>";

    $this->data['signature'] = base64_encode(sha1($this->config->get('liqpay_merchant_signature').$xml_request.$this->config->get('liqpay_merchant_signature'),TRUE));
    $this->data['operation_xml'] = base64_encode($xml_request);

    $this->data['back'] = $this->url->https('checkout/payment');

    $this->id       = 'payment';
    $this->template = $this->config->get('config_template') . 'payment/liqpay.tpl';

    $this->render();
  }

  public function confirm() {
    $this->load->model('checkout/order');

    $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
  }

  public function callback() {

    $this->load->model('checkout/order');

    $xml_answer = base64_decode($this->request->post['operation_xml']);
    $signature_answer = base64_encode(sha1($this->config->get('liqpay_merchant_signature').$xml_answer.$this->config->get('liqpay_merchant_signature'),TRUE));

    $xml_answer_orig = $this->request->post['operation_xml'];

    $posleft = strpos($xml_answer, 'order_id');
    $posright = strpos($xml_answer, '/order_id');
    $order_id = substr($xml_answer, $posleft + 9, $posright - $posleft - 10);

    if ($signature_answer ==  $this->request->post['signature'])
    {
      $this->model_checkout_order->update($order_id, $this->config->get('liqpay_order_status_id'));
    }
  }
}
?>