<?php
class ControllerExtensionModuleAmazonPay extends Controller {
    public function index() {
        $this->load->model('extension/payment/amazon_login_pay');

        if ($this->config->get('payment_amazon_login_pay_status') && $this->config->get('module_amazon_pay_status') && !empty($this->request->server['HTTPS']) && !($this->config->get('payment_amazon_login_pay_minimum_total') > 0 && $this->config->get('payment_amazon_login_pay_minimum_total') > $this->cart->getSubTotal()) && $this->model_extension_payment_amazon_login_pay->isTotalPositive()) {

            // capital L in Amazon cookie name is required, do not alter for coding standards
            if (!$this->customer->isLogged() && isset($this->request->cookie['amazon_Login_state_cache'])) {
                setcookie('amazon_Login_state_cache', null, -1, '/');
            }

            $amazon_payment_js = $this->model_extension_payment_amazon_login_pay->getWidgetJs();
            $this->document->addScript($amazon_payment_js);

            $data['client_id'] = $this->config->get('payment_amazon_login_pay_client_id');
            $data['merchant_id'] = $this->config->get('payment_amazon_login_pay_merchant_id');
            $data['return_url'] = html_entity_decode($this->url->link('extension/module/amazon_login/login', 'from_amazon_pay=1', true), ENT_COMPAT, "UTF-8");
            $data['button_id'] = 'AmazonPayButton';

            if ($this->config->get('payment_amazon_login_pay_test') == 'sandbox') {
                $data['sandbox'] = isset($this->session->data['user_id']); // Require an active admin panel session to show debug messages
            }

            if ($this->config->get('module_amazon_pay_button_type')) {
                $data['button_type'] = $this->config->get('module_amazon_pay_button_type');
            } else {
                $data['button_type'] = 'PwA';
            }

            if ($this->config->get('module_amazon_pay_button_colour')) {
                $data['button_colour'] = $this->config->get('module_amazon_pay_button_colour');
            } else {
                $data['button_colour'] = 'Gold';
            }

            if ($this->config->get('module_amazon_pay_button_size')) {
                $data['button_size'] = $this->config->get('module_amazon_pay_button_size');
            } else {
                $data['button_size'] = 'medium';
            }

                       if(!empty($this->session->data['language'])) {
              $session_lang = $this->session->data['language'];
              $session_lang_code = current(explode('-', $session_lang));
              $language_region_mapping = array(
                'EUR' => array('de-De', 'es-ES','fr-FR', 'it-IT', 'en-GB'),
                'GBP' => array('de-De', 'es-ES','fr-FR', 'it-IT', 'en-GB'),
                'USD' =>array('en-US')
              );

              if($this->config->get('payment_amazon_login_pay_payment_region')) {
                $merchant_location = $this->config->get('payment_amazon_login_pay_payment_region');
                $available_codes = $language_region_mapping[$merchant_location];
                 $data['language'] = ($this->config->get('payment_amazon_login_pay_language')) ? $this->config->get('payment_amazon_login_pay_language') : 'en-US';
                foreach ($available_codes as $l_code) {
                  $l_code_short = current(explode('-', $l_code));
                  if($session_lang_code == $l_code_short) {
                    $data['language'] = $l_code;
                  }
                }
              }
            } else {
              $data['language'] = ($this->config->get('payment_amazon_login_pay_language')) ? $this->config->get('payment_amazon_login_pay_language') : 'en-US';
            }

            return $this->load->view('extension/module/amazon_login', $data);
        }
    }
}
