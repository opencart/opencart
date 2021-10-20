<?php

class ControllerApiMyfatoorah extends Controller {

    public function webhook() {
        sleep(30); //to allow the callback code run 1st. 
        //get MyFatoorah-Signature from request headers
        $request_headers = apache_request_headers();
        if (empty($request_headers['MyFatoorah-Signature'])) {
            exit();
        }
        $MyFatoorahsignature = $request_headers['MyFatoorah-Signature'];
        //get webhook data content
        $body                = (file_get_contents("php://input"));
        //track issue
        $webhook             = json_decode($body, true);

        if (empty($webhook['Event'])) {
            exit();
        }
        $this->ocCode = 'payment_' . $webhook['Data']['UserDefinedField'];


        if (empty($this->config->get($this->ocCode . '_webhook_secret_key'))) {
            exit();
        }
        $this->logger = new Log($webhook['Data']['UserDefinedField'] . '.log');
        $this->isLog  = $this->config->get($this->ocCode . '_debug') === '1' ? true : false;
        $apiKey       = $this->config->get($this->ocCode . '_apiKey');
        $isTest       = $this->config->get($this->ocCode . '_test') === '1' ? true : false;
        require_once DIR_SYSTEM . 'library/myfatoorah/PaymentMyfatoorahApiV2.php';
        $this->mfObj  = ($this->isLog) ? new PaymentMyfatoorahApiV2($apiKey, $isTest, $this->logger, 'write') : new PaymentMyfatoorahApiV2($apiKey, $isTest);
        //validate signature

        $this->mfObj->validateSignature($webhook['Data'], $this->config->get($this->ocCode . '_webhook_secret_key'), $MyFatoorahsignature);

        //only one event was tested
        if ($webhook['Event'] == 'TransactionsStatusChanged') {
            return $this->{$webhook['Event']}($webhook['Data']);
        }
        return false;
    }

    function TransactionsStatusChanged($data) {
        $orderId    = $data['CustomerReference'];
        require_once DIR_SYSTEM . '../catalog/controller/extension/payment/myfatoorah_controller.php';
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($orderId);
        if ($order_info['order_status_id'] == $this->config->get($this->ocCode . '_order_status_id')) {
            return true;
        }

        $msgLog = "MyFatoorah Webhook Order #$orderId ----- Get Payment Status";
        $json   = $this->mfObj->getPaymentStatus($data['InvoiceId'], 'InvoiceId', $orderId);
        if (empty($json->focusTransaction)) {
            return false;
        }

        if (!empty($order_info['payment_custom_field']['paymentId']) && $order_info['payment_custom_field']['paymentId'] == $json->focusTransaction->PaymentId) {
            $this->mfObj->log('MyFatoorah Webhook Order #' . $orderId . ': Payment already exists.');
            return true;
        }

        $this->mfObj->log("$msgLog - Result focusTransaction: " . json_encode($json->focusTransaction));

        $msg  = $this->orderHistoryNote($json->focusTransaction);
        $name = $data['UserDefinedField'] . ' (' . $json->focusTransaction->PaymentGateway . ')';
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET payment_method = '" . $this->db->escape($name) . "',payment_custom_field = '" . $this->db->escape(json_encode(array('paymentId' => $paymentId))) . "' WHERE order_id = '" . (int) $orderId . "'");

        if ($json->InvoiceStatus == 'Paid') {
            $this->mfObj->log('MyFatoorah Webhook Order #' . $orderId . ': is Paid.');
            $this->model_checkout_order->addOrderHistory($orderId, $this->config->get($this->ocCode . '_order_status_id'), $msg, true);
            return true;
        } else {
            $this->mfObj->log('MyFatoorah Webhook Order #' . $orderId . ': is not Paid.');
            $this->model_checkout_order->addOrderHistory($orderId, $this->config->get($this->ocCode . '_failed_order_status_id'), $msg, false);
            return false;
        }
    }

//---------------------------------------------------------------------------------------------------------------------------------------------------

    public function orderHistoryNote($data) {
        $note = '<b>MyFatoorah Webhook Payment Details:</b><br>';
        $note .= 'Gateway: ' . $data->PaymentGateway . '<br>';
        $note .= 'Transaction Status: ' . $data->TransactionStatus . '<br>';
        $note .= 'PaymentId: ' . $data->PaymentId . '<br>';
        $note .= 'AuthorizationId: ' . $data->AuthorizationId . '<br>';
        $note .= 'PaidCurrency: ' . $data->PaidCurrency . '<br>';
        $note .= empty($data->Error) ? '' : 'Error: ' . $data->Error . '<br>';
        return $note;
    }

}
