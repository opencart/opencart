<?php
/**
 * Payment System Wallet One (Единая касса)
 * 
 * @cms       OpenCart/ocStore 3.0
 * @author    OcTeam
 * @support   https://opencartforum.com/profile/3463-shoputils/
 * @version   1.0
 * @copyright  Copyright (c) 2017 OcStore Team (https://ocstore.com , https://opencartforum.com)
 */
class ControllerExtensionPaymentOcstoreW1 extends Controller {
    private $order;
    private $iso4271 = array(
        'RUR'  => '810',
        'RUB'  => '643',
        'USD'  => '840',
        'EUR'  => '978',
        'ZAR'  => '710',
        'UAH'  => '980',
        'KZT'  => '398',
        'BYR'  => '974',
        'BYN'  => '974',
        'AZN'  => '944',
        'PLN'  => '985',
        'GEL'  => '981',
        'TJS'  => '972'
    );

    public function index() {
        $data['action'] = 'https://wl.walletone.com/checkout/checkout/Index';
        $data['confirm'] = $this->url->link('extension/payment/ocstore_w1/confirm', '', 'SSL');
        
        $this->load->model('checkout/order');
        
        $order_id = $this->session->data['order_id'];
        $order_info = $this->model_checkout_order->getOrder($order_id);
        
        $currency_code = $this->config->get('payment_ocstore_w1_currency');
        $currency_number = $this->getCurrencyNumberByCode($currency_code);
        $merchant_id = $this->config->get('payment_ocstore_w1_shop_id');
        $amount = number_format($this->currency->convert($order_info['total'], $this->config->get('config_currency'), $currency_code), 2, '.', '');
        $payment_id = $order_info['order_id'];

        $timeZone = date_default_timezone_get();
        date_default_timezone_set('Europe/Dublin');
        $data['params'] = array(
            'WMI_MERCHANT_ID'     => $merchant_id,
            'WMI_PAYMENT_AMOUNT'  => $amount,
            'WMI_PAYMENT_NO'      => $payment_id,
            'WMI_CURRENCY_ID'     => $currency_number,
            'WMI_DESCRIPTION'     => sprintf('OrderID: %d; Total: %s (%s)', $payment_id, $amount, $currency_code),
            'WMI_SUCCESS_URL'     => $this->url->link('extension/payment/ocstore_w1/success', '', 'SSL'),
            'WMI_FAIL_URL'        => $this->url->link('extension/payment/ocstore_w1/fail', '', 'SSL'),
            'WMI_EXPIRED_DATE'    => date("Y-m-d\TH:i:s", time() + 60 * 43200)   //1 month
        );
        date_default_timezone_set($timeZone);

        $data['params']['WMI_SIGNATURE'] = $this->calculateSignature($data['params']);
      
        return $this->load->view('extension/payment/ocstore_w1', $data);
    }

    public function confirm() {
        if (!empty($this->session->data['order_id']) && ($this->session->data['payment_method']['code'] == 'ocstore_w1')) {
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_ocstore_w1_order_confirm_status_id'));
        }
    }
    
    public function fail() {
        if ($this->validate(false, false)) {
            $created = isset($this->request->post['WMI_ORDER_STATE']) && ($this->request->post['WMI_ORDER_STATE'] == 'Created');
            if ($this->order['order_status_id'] && !$created) {
                $this->model_checkout_order->addOrderHistory($this->order['order_id'], $this->config->get('payment_ocstore_w1_order_fail_status_id'), 'W1: Payment Fail', true);
            }
        }

        $this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
    }
    
    public function success() {
        $this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
    }
    
    public function callback() {
        //$this->log->Write('CallbackURL: ');
        //$this->log->Write('  POST:' . var_export($this->request->post, true));
        //$this->log->Write('  GET:' . var_export($this->request->get, true));
        if (!$this->validate()) {
            return;
        }

        if ($this->request->post['WMI_ORDER_STATE'] == 'Processing' ||
            $this->request->post['WMI_ORDER_STATE'] == 'Accepted') {
            if ($this->order['order_status_id']) {
                $this->model_checkout_order->addOrderHistory($this->order['order_id'], $this->config->get('payment_ocstore_w1_order_status_id'), 'W1: Order ' . $this->request->post['WMI_ORDER_ID'], true);
            }
        }

        $this->sendOk();
    }

    protected function calculateSignature($params) {
        foreach ($params as $name => $val) {
            if (is_array($val)) {
                usort($val, "strcasecmp");
                $params[$name] = $val;
            }
        }
        uksort($params, "strcasecmp");
        $paramValues = "";

        foreach ($params as $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $v = iconv("utf-8", "windows-1251", $v);
                    $paramValues .= $v;
                }
            } else {
                $value = iconv("utf-8", "windows-1251", $value);
                $paramValues .= $value;
            }
        }

        return base64_encode(pack("H*", md5($paramValues . $this->config->get('payment_ocstore_w1_sign'))));
    }

    protected function getCurrencyNumberByCode($value) {
        return isset($this->iso4271[$value]) ? $this->iso4271[$value] : false;
    }

    protected function validate($check_sign_hash = true, $check_request_method = true) {
        $this->load->model('checkout/order');

        if ($check_request_method) {
            if ($this->request->server['REQUEST_METHOD'] != 'POST') {
                $this->sendForbidden('HTTP method should be POST');
                return false;
            }
        } else {
            //Fix от 19.12.2016. На Fail URL W1 может прислать пустой GET-запрос. В этом случае попытаемся получить order_id из сессии.
            if ($this->request->server['REQUEST_METHOD'] != 'POST') {
                if (isset($this->session->data['order_id'])) {
                    $this->request->post['WMI_PAYMENT_NO'] = $this->session->data['order_id'];
                } else {
                    $this->sendForbidden('Unknown Order ID');
                    return false;
                }
            }
        }

        if ($check_sign_hash) {
            $params = array();
            foreach ($this->request->post as $key => $value) {
                if ($key !== "WMI_SIGNATURE") $params[$key] = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
            }

            $signature = $this->calculateSignature($params);

            if ($this->request->post['WMI_SIGNATURE'] != $signature) {
                $this->sendForbidden(sprintf('Wrong signature: %s', $signature));
                return false;
            }
        }

        $this->order = $this->model_checkout_order->getOrder($this->request->post['WMI_PAYMENT_NO']);

        if (!$this->order) {
            $this->sendForbidden(sprintf('Order ID: %s', $this->request->post['WMI_PAYMENT_NO']));
            return false;
        }

        return true;
    }

    protected function sendForbidden($error) {
        //$this->log->Write('ERROR: ' . $error);
        ob_start();
        echo 'WMI_RESULT=RETRY&WMI_DESCRIPTION=' . urlencode($error);
        ob_end_flush();
    }

    protected function sendOk() {
        //$this->log->Write('SEND OK');
        ob_start();
        echo "WMI_RESULT=OK";
        ob_end_flush();
    }
}
?>