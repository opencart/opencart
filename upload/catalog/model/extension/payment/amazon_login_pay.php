<?php

class ModelExtensionPaymentAmazonLoginPay extends Model {
    const LOG_FILENAME = "amazon_pay.log";

    public function getMethod() {
        // Do nothing, as Amazon Pay is a separate checkout flow, not a payment option in OpenCart.
    }

    public function verifyOrder() {
        if (!isset($this->session->data['apalwa']['pay']['order'])) {
            $this->response->redirect($this->url->link('extension/payment/amazon_login_pay/confirm', '', true));
        }
    }

    public function verifyShipping() {
        if (!isset($this->session->data['apalwa']['pay']['shipping_method']) || !isset($this->session->data['apalwa']['pay']['address'])) {
            $this->response->redirect($this->url->link('extension/payment/amazon_login_pay/address', '', true));
        }
    }

    public function verifyCart() {
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->cartRedirect();
        }

        $products = $this->cart->getProducts();

        foreach ($products as $product) {
            $product_total = 0;

            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $this->cartRedirect();
            }
        }
    }

    public function verifyLogin() {
        // capital L in Amazon cookie name is required, do not alter for coding standards
        if (!isset($this->request->cookie['amazon_Login_state_cache']) || !isset($this->session->data['apalwa']['pay']['profile'])) {
            $this->session->data['apalwa']['login']['error'] = $this->language->get('error_login');

            $this->response->redirect($this->url->link('extension/module/amazon_login/error', '', true));
        }
    }

    public function verifyTotal() {
        $set_minimum = (float)$this->config->get('payment_amazon_login_pay_minimum_total');

        $minimum = $set_minimum > 0 ? $set_minimum : 0.01;

        if ($minimum > $this->cart->getSubTotal() || !$this->isTotalPositive()) {
            $this->cartRedirect(sprintf($this->language->get('error_minimum'), $this->currency->format($minimum, $this->session->data['currency'])));
        }
    }

    public function verifyReference() {
        if (empty($this->session->data['apalwa']['pay']['order_reference_id'])) {
            $this->cartRedirect($this->language->get('error_process_order'));
        }
    }

    public function verifyOrderSessionData() {
        $keys = array(
            'profile',
            'address',
            'order_reference_id',
            'shipping_methods',
            'shipping_method'
        );

        foreach ($keys as $key) {
            if (empty($this->session->data['apalwa']['pay'][$key])) {
                throw $this->loggedException("Missing session data: " . $key, $this->language->get('error_process_order'));
            }
        }
    }

    public function cartRedirect($message = null) {
        unset($this->session->data['apalwa']['pay']);
        unset($this->session->data['order_id']);

        if ($message) {
            $this->session->data['error'] = $message;
        }

        $this->response->redirect($this->url->link('checkout/cart', '', true));
    }

    public function getTotals(&$total_data) {
        $this->load->model('setting/extension');

        $sort_order = array();

        $results = $this->model_setting_extension->getExtensions('total');

        foreach ($results as $key => $value) {
            $sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
        }

        array_multisort($sort_order, SORT_ASC, $results);

        foreach ($results as $result) {
            if ($this->config->get('total_' . $result['code'] . '_status')) {
                $this->load->model('extension/total/' . $result['code']);

                // We have to put the totals in an array so that they pass by reference.
                $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
            }
        }

        $sort_order = array();

        foreach ($total_data['totals'] as $key => &$value) {
            $value['text'] = $this->currency->format($value['value'], $this->session->data['currency']);

            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $total_data['totals']);
    }

    public function isTotalPositive() {
        // Totals
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;
        
        // Because __call can not keep var references so we put them into an array.             
        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );
        
        $this->getTotals($total_data);

        return $total > 0;
    }

    public function makeOrder() {
        $this->verifyOrderSessionData();

        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;

        // Because __call can not keep var references so we put them into an array.
        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        $this->getTotals($total_data);

        $order_data['totals'] = $totals;

        $this->load->language('checkout/checkout');

        $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        $order_data['store_id'] = $this->config->get('config_store_id');
        $order_data['store_name'] = $this->config->get('config_name');

        if ($order_data['store_id']) {
            $order_data['store_url'] = $this->config->get('config_url');
        } else {
            if ($this->request->server['HTTPS']) {
                $order_data['store_url'] = HTTPS_SERVER;
            } else {
                $order_data['store_url'] = HTTP_SERVER;
            }
        }

        $profile = $this->session->data['apalwa']['pay']['profile'];
        $address = $this->session->data['apalwa']['pay']['address'];
        $shipping_method = $this->session->data['apalwa']['pay']['shipping_method'];

        $order_data['customer_id'] = !empty($profile['customer_id']) ? $profile['customer_id'] : 0;
        $order_data['customer_group_id'] = $profile['customer_group_id'];
        $order_data['firstname'] = $profile['firstname'];
        $order_data['lastname'] = $profile['lastname'];
        $order_data['email'] = $profile['email'];
        $order_data['telephone'] = !empty($address['telephone']) ? $address['telephone'] : '';
        $order_data['custom_field'] = array();

        // The payment address details are empty, and shall be provided later when the order gets authorized
        $order_data['payment_firstname'] = ""; //$address['firstname'];
        $order_data['payment_lastname'] = ""; //$address['lastname'];
        $order_data['payment_company'] = ""; //$address['company'];
        $order_data['payment_company_id'] = ""; //$address['company_id'];
        $order_data['payment_tax_id'] = ""; //$address['tax_id'];
        $order_data['payment_address_1'] = ""; //$address['address_1'];
        $order_data['payment_address_2'] = ""; //$address['address_2'];
        $order_data['payment_city'] = ""; //$address['city'];
        $order_data['payment_postcode'] = ""; //$address['postcode'];
        $order_data['payment_zone'] = ""; //$address['zone'];
        $order_data['payment_zone_id'] = 0; //$address['zone_id'];
        $order_data['payment_country'] = ""; //$address['country'];
        $order_data['payment_country_id'] = 0; //$address['country_id'];
        $order_data['payment_address_format'] = ""; //$address['address_format'];
        $order_data['payment_custom_field'] = array();

        $order_data['payment_method'] = $this->language->get('text_lpa');
        $order_data['payment_code'] = 'amazon_login_pay';

        $order_data['shipping_firstname'] = $address['firstname'];
        $order_data['shipping_lastname'] = $address['lastname'];
        $order_data['shipping_company'] = $address['company'];
        $order_data['shipping_address_1'] = $address['address_1'];
        $order_data['shipping_address_2'] = $address['address_2'];
        $order_data['shipping_city'] = $address['city'];
        $order_data['shipping_postcode'] = $address['postcode'];
        $order_data['shipping_zone'] = $address['zone'];
        $order_data['shipping_zone_id'] = $address['zone_id'];
        $order_data['shipping_country'] = $address['country'];
        $order_data['shipping_country_id'] = $address['country_id'];
        $order_data['shipping_address_format'] = $address['address_format'];
        $order_data['shipping_method'] = $this->session->data['apalwa']['pay']['shipping_method']['title'];

        if (isset($shipping_method['code'])) {
            $order_data['shipping_code'] = $shipping_method['code'];
        } else {
            $order_data['shipping_code'] = '';
        }

        $order_data['products'] = array();

        foreach ($this->cart->getProducts() as $product) {
            $option_data = array();

            foreach ($product['option'] as $option) {
                $option_data[] = array(
                    'product_option_id'       => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'option_id'               => $option['option_id'],
                    'option_value_id'         => $option['option_value_id'],
                    'name'                    => $option['name'],
                    'value'                   => $option['value'],
                    'type'                    => $option['type']
                );
            }

            $order_data['products'][] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => $product['model'],
                'option'     => $option_data,
                'download'   => $product['download'],
                'quantity'   => $product['quantity'],
                'subtract'   => $product['subtract'],
                'price'      => $product['price'],
                'text_price' => $this->currency->format($product['price'], $this->session->data['currency']),
                'total'      => $product['total'],
                'text_total' => $this->currency->format($product['total'], $this->session->data['currency']),
                'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => $product['reward']
            );
        }

        // Gift Voucher
        $order_data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $order_data['vouchers'][] = array(
                    'description'      => $voucher['description'],
                    'code'             => token(10),
                    'to_name'          => $voucher['to_name'],
                    'to_email'         => $voucher['to_email'],
                    'from_name'        => $voucher['from_name'],
                    'from_email'       => $voucher['from_email'],
                    'voucher_theme_id' => $voucher['voucher_theme_id'],
                    'message'          => $voucher['message'],
                    'amount'           => $voucher['amount']
                );
            }
        }

        $order_data['comment'] = !empty($this->session->data['comment']) ? $this->session->data['comment'] : '';
        $order_data['total'] = $total_data['total'];

        if (isset($this->request->cookie['tracking'])) {
            $order_data['tracking'] = $this->request->cookie['tracking'];

            $subtotal = $this->cart->getSubTotal();

            // Affiliate
            $affiliate_info = $this->model_account_customer->getAffiliateByTracking($this->request->cookie['tracking']);

            if ($affiliate_info) {
                $order_data['affiliate_id'] = $affiliate_info['customer_id'];
                $order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
            } else {
                $order_data['affiliate_id'] = 0;
                $order_data['commission'] = 0;
            }

            // Marketing
            $this->load->model('checkout/marketing');

            $marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

            if ($marketing_info) {
                $order_data['marketing_id'] = $marketing_info['marketing_id'];
            } else {
                $order_data['marketing_id'] = 0;
            }
        } else {
            $order_data['affiliate_id'] = 0;
            $order_data['commission'] = 0;
            $order_data['marketing_id'] = 0;
            $order_data['tracking'] = '';
        }

        $order_data['language_id'] = $this->config->get('config_language_id');
        $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
        $order_data['currency_code'] = $this->session->data['currency'];
        $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
        $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

        if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
            $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
        } else {
            $order_data['forwarded_ip'] = '';
        }

        if (isset($this->request->server['HTTP_USER_AGENT'])) {
            $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
        } else {
            $order_data['user_agent'] = '';
        }

        if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
        } else {
            $order_data['accept_language'] = '';
        }

        return $order_data;
    }

    public function getWidgetJs() {
        if ($this->config->get('payment_amazon_login_pay_test') == 'sandbox') {
            if ($this->config->get('payment_amazon_login_pay_payment_region') == 'GBP') {
                $amazon_payment_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/uk/sandbox/lpa/js/Widgets.js';
            } elseif ($this->config->get('payment_amazon_login_pay_payment_region') == 'USD') {
                $amazon_payment_js = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js';
            } else {
                $amazon_payment_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/de/sandbox/lpa/js/Widgets.js';
            }
        } else {
            if ($this->config->get('payment_amazon_login_pay_payment_region') == 'GBP') {
                $amazon_payment_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/uk/lpa/js/Widgets.js';
            } elseif ($this->config->get('payment_amazon_login_pay_payment_region') == 'USD') {
                $amazon_payment_js = 'https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js';
            } else {
                $amazon_payment_js = 'https://static-eu.payments-amazon.com/OffAmazonPayments/de/lpa/js/Widgets.js';
            }
        }

        return $amazon_payment_js . '?sellerId=' . $this->config->get('payment_amazon_login_pay_merchant_id');
    }

    public function submitOrderDetails($order_reference_id, $order_id) {
        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($order_id);

        if (empty($order_info)) {
            throw $this->loggedException("Order not found!", $this->language->get('error_process_order'));
        }

        return $this->postCurl("SetOrderReferenceDetails", array(
            'AmazonOrderReferenceId' => $order_reference_id,
            'OrderReferenceAttributes.OrderTotal.CurrencyCode' => $this->config->get('payment_amazon_login_pay_payment_region'),
            'OrderReferenceAttributes.OrderTotal.Amount' => $this->currency->convert($order_info['total'], $this->config->get('config_currency'), $this->config->get('payment_amazon_login_pay_payment_region')),
            'OrderReferenceAttributes.PlatformId' => $this->getPlatformId(),
            'OrderReferenceAttributes.SellerOrderAttributes.SellerOrderId' => $order_id,
            'OrderReferenceAttributes.SellerOrderAttributes.StoreName' => $order_info['store_name']
        ));
    }

    public function confirmOrder($order_reference_id) {
        $this->postCurl("ConfirmOrderReference", array(
            'AmazonOrderReferenceId' => $order_reference_id
        ));
    }

    public function authorizeOrder($order) {
        $capture_now = $this->config->get('payment_amazon_login_pay_mode') == 'payment';

        return $this->postCurl("Authorize", array(
            'AmazonOrderReferenceId' => (string)$order->AmazonOrderReferenceId,
            'AuthorizationReferenceId' => 'auth_' . uniqid(),
            'AuthorizationAmount.Amount' => (float)$order->OrderTotal->Amount,
            'AuthorizationAmount.CurrencyCode' => (string)$order->OrderTotal->CurrencyCode,
            'TransactionTimeout' => 0,
            'CaptureNow' => $capture_now
        ))
            ->ResponseBody
            ->AuthorizeResult
            ->AuthorizationDetails;
    }

    public function fetchOrder($order_reference_id) {
        return $this->postCurl("GetOrderReferenceDetails", array(
            'AmazonOrderReferenceId' => $order_reference_id
        ))
            ->ResponseBody
            ->GetOrderReferenceDetailsResult
            ->OrderReferenceDetails;
    }

    public function captureOrder($amazon_authorization_id, $total, $currency) {
        return $this->postCurl("Capture", array(
            'AmazonAuthorizationId' => $amazon_authorization_id,
            'CaptureReferenceId' => 'capture_' . uniqid(),
            'CaptureAmount.Amount' => $total,
            'CaptureAmount.CurrencyCode' => $currency
        ))
            ->ResponseBody
            ->CaptureResult
            ->CaptureDetails;
    }

    public function cancelOrder($order_reference_id, $reason) {
        $this->postCurl("CancelOrderReference", array(
            'AmazonOrderReferenceId' => $order_reference_id,
            'CancelationReason' => $reason
        ));
    }

    public function closeOrder($order_reference_id, $reason) {
        $this->postCurl("CloseOrderReference", array(
            'AmazonOrderReferenceId' => $order_reference_id,
            'ClosureReason' => $reason
        ));
    }

    public function isOrderInState($order_reference_id, $states = array()) {
        return in_array((string)$this->fetchOrder($order_reference_id)->OrderReferenceStatus->State, $states);
    }

    public function findOrAddOrder($order) {
        $order_id = (int)$order->SellerOrderAttributes->SellerOrderId;

        $order_reference_id = (string)$order->AmazonOrderReferenceId;

        $find_sql = "SELECT * FROM `" . DB_PREFIX . "amazon_login_pay_order` WHERE order_id=" . (int)$order_id . " AND amazon_order_reference_id='" . $this->db->escape($order_reference_id) . "'";

        $find_result = $this->db->query($find_sql);

        if ($find_result->num_rows > 0) {
            return $find_result->row['amazon_login_pay_order_id'];
        }

        $insert = array(
            'order_id' => (int)$order_id,
            'amazon_order_reference_id' => "'" . $this->db->escape($order_reference_id) . "'",
            'amazon_authorization_id' => "''",
            'free_shipping' => (int)$this->isShippingFree($order_id),
            'date_added' => "'" . date('Y-m-d H:i:s', strtotime((string)$order->CreationTimestamp)) . "'",
            'modified' => "'" . date('Y-m-d H:i:s', strtotime((string)$order->OrderReferenceStatus->LastUpdateTimestamp)) . "'",
            'currency_code' => "'" . $this->db->escape((string)$order->OrderTotal->CurrencyCode) . "'",
            'total' => (float)$order->OrderTotal->Amount
        );

        $row = array();

        foreach ($insert as $key => $value) {
            $row[] = "`" . $key . "`=" . $value;
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "amazon_login_pay_order` SET " . implode(',', $row));

        return $this->db->getLastId();
    }

    public function getOrderByOrderId($order_id) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "amazon_login_pay_order` WHERE `order_id`=" . (int)$order_id;

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result->row;
        }

        return null;
    }

    public function addAuthorization($amazon_login_pay_order_id, $authorization) {
        $capture_id = (string)$authorization->IdList->member;

        $type = (string)$authorization->CaptureNow == 'true' ? 'capture' : 'authorization';

        $amount = $type == 'capture' ?
            (float)$authorization->CapturedAmount->Amount :
            (float)$authorization->AuthorizationAmount->Amount;

        $authorization_id = (string)$authorization->AmazonAuthorizationId;

        $transaction = array(
            'amazon_login_pay_order_id' => $amazon_login_pay_order_id,
            'amazon_authorization_id' => $authorization_id,
            'amazon_capture_id' => $capture_id,
            'amazon_refund_id' => '',
            'date_added' => date('Y-m-d H:i:s', strtotime((string)$authorization->CreationTimestamp)),
            'type' => $type,
            'status' => (string)$authorization->AuthorizationStatus->State,
            'amount' => $amount
        );

        $this->addTransaction($transaction);

        $capture_status = $type == 'capture';

        $this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order` SET `amazon_authorization_id`='" . $this->db->escape($authorization_id) . "' WHERE `amazon_login_pay_order_id`='" . $this->db->escape($amazon_login_pay_order_id) . "'");

        $this->updateCapturedStatus($amazon_login_pay_order_id, $capture_status);
    }

    public function updateCapturedStatus($amazon_login_pay_order_id, $status) {
        $this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order` SET `capture_status`=" . (int)$status . " WHERE `amazon_login_pay_order_id`='" . $this->db->escape($amazon_login_pay_order_id) . "'");
    }

    public function addTransaction($data) {
        $insert = array(
            'amazon_login_pay_order_id' => (int)$data['amazon_login_pay_order_id'],
            'amazon_authorization_id' => "'" . $this->db->escape($data['amazon_authorization_id']) . "'",
            'amazon_capture_id' => "'" . $this->db->escape($data['amazon_capture_id']) . "'",
            'amazon_refund_id' => "'" . $this->db->escape($data['amazon_refund_id']) . "'",
            'date_added' => "'" . $this->db->escape($data['date_added']) . "'",
            'type' => "'" . $this->db->escape($data['type']) . "'",
            'status' => "'" . $this->db->escape($data['status']) . "'",
            'amount' => (float)$data['amount']
        );

        $row = array();

        foreach ($insert as $key => $value) {
            $row[] = "`" . $key . "`=" . $value;
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "amazon_login_pay_order_transaction` SET " . implode(',', $row));
    }

    public function isShippingFree($order_id) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "order_total` WHERE order_id=" . (int)$order_id . " AND `value`=0.0000 AND `code`='shipping'";

        return $this->db->query($sql)->num_rows > 0;
    }

    public function getPlatformId() {
        if ($this->config->get('payment_amazon_login_pay_payment_region') == 'USD') {
            return 'A3GK1RS09H3A7D';
        } else {
            return 'A3EIRX2USI2KJV';
        }
    }

    public function parseIpnBody($json) {
        $data = $this->parseJson($json);

        $message = $this->parseJson($data['Message']);

        libxml_use_internal_errors(true);

        try {
            $xml = simplexml_load_string($message['NotificationData']);
        } catch (\Exception $e) {
            $this->debugLog("ERROR", $e->getMessage());

            throw new \RuntimeException($e->getMessage());
        }

        return $xml;
    }

    public function parseJson($json) {
        $message = @json_decode($json, true);

        $json_error = json_last_error();

        if ($json_error != 0) {
            throw new \RuntimeException("Error with message - content is not in json format. Error: " . $json_error);
        }

        return $message;
    }

    public function updateStatus($amazon_id, $type, $status) {
        $this->db->query("UPDATE `" . DB_PREFIX . "amazon_login_pay_order_transaction` SET `status` = '" . $this->db->escape($status) . "' WHERE `amazon_" . $type . "_id` = '" . $this->db->escape($amazon_id) . "' AND `type` = '" . $this->db->escape($type) . "'");
    }

    public function authorizationIpn($xml) {
        $status = (string)$xml->AuthorizationDetails->AuthorizationStatus->State;
        $amazon_authorization_id = (string)$xml->AuthorizationDetails->AmazonAuthorizationId;
        $this->updateStatus($amazon_authorization_id, 'authorization', $status);
        if ($status == 'Declined' || $status == 'Closed') {
            $this->debugLog("NOTICE", $status . ': ' . (string)$xml->AuthorizationDetails->AuthorizationStatus->ReasonCode);
        }
    }

    public function captureIpn($xml) {
        $status = (string)$xml->CaptureDetails->CaptureStatus->State;
        $amazon_capture_id = (string)$xml->CaptureDetails->AmazonCaptureId;
        $this->updateStatus($amazon_capture_id, 'capture', $status);
        if ($status == 'Declined' || $status == 'Canceled' || $status == 'Closed') {
            $this->debugLog("NOTICE", $status . ': ' . (string)$xml->CaptureDetails->CaptureStatus->ReasonCode);
        }
    }

    public function refundIpn($xml) {
        $status = (string)$xml->RefundDetails->RefundStatus->State;
        $amazon_refund_id = (string)$xml->RefundDetails->AmazonRefundId;
        $this->updateStatus($amazon_refund_id, 'refund', $status);
        if ($status == 'Declined') {
            $this->debugLog("NOTICE", $status . ': ' . (string)$xml->RefundDetails->RefundStatus->ReasonCode);
        }
    }

    public function updatePaymentAddress($order_id, $amazon_address) {
        $address = $this->amazonAddressToOcAddress($amazon_address);

        $data = array(
            'payment_firstname' => "'" . $this->db->escape($address['firstname']) . "'",
            'payment_lastname' => "'" . $this->db->escape($address['lastname']) . "'",
            'payment_company' => "'" . $this->db->escape($address['company']) . "'",
            'payment_address_1' => "'" . $this->db->escape($address['address_1']) . "'",
            'payment_address_2' => "'" . $this->db->escape($address['address_2']) . "'",
            'payment_city' => "'" . $this->db->escape($address['city']) . "'",
            'payment_postcode' => "'" . $this->db->escape($address['postcode']) . "'",
            'payment_zone' => "'" . $this->db->escape($address['zone']) . "'",
            'payment_zone_id' => (int)$address['zone_id'],
            'payment_country' => "'" . $this->db->escape($address['country']) . "'",
            'payment_country_id' => (int)$address['country_id'],
            'payment_address_format' => "'" . $this->db->escape($address['address_format']) . "'",
            'payment_custom_field' => "'[]'"
        );

        $update = array();

        foreach ($data as $key => $value) {
            $update[] = "`" . $key . "`=" . $value;
        }

        $sql = "UPDATE `" . DB_PREFIX . "order` SET " . implode(",", $update) . " WHERE `order_id`=" . (int)$order_id;

        $this->db->query($sql);
    }

    public function amazonAddressToOcAddress($amazon_address, $default_telephone = '0000000') {
        $full_name = explode(' ', $amazon_address->Name);
        $amazon_address->FirstName = array_shift($full_name);
        $amazon_address->LastName = implode(' ', $full_name);

        $lines = array_filter(array($amazon_address->AddressLine1, $amazon_address->AddressLine2, $amazon_address->AddressLine3));

        $address_line_1 = array_shift($lines);
        $address_line_2 = implode(' ', $lines);

        $country_info = $this->getCountryInfo($amazon_address);
        $zone_info = $this->getZoneInfo($amazon_address, $country_info);

        return array(
            'firstname' => (string)$amazon_address->FirstName,
            'lastname' => (string)$amazon_address->LastName,
            'company' => '',
            'company_id' => '',
            'tax_id' => '',
            'city' => (string)$amazon_address->City,
            'telephone' => !empty($amazon_address->Phone) ? (string)$amazon_address->Phone : $default_telephone,
            'postcode' => (string)$amazon_address->PostalCode,
            'country' => $country_info['country'],
            'country_id' => $country_info['country_id'],
            'zone' => $zone_info['name'],
            'zone_code' => $zone_info['code'],
            'zone_id' => $zone_info['zone_id'],
            'address_1' => (string)$address_line_1,
            'address_2' => (string)$address_line_2,
            'iso_code_2' => $country_info['iso_code_2'],
            'iso_code_3' => $country_info['iso_code_3'],
            'address_format' => $country_info['address_format']
        );
    }

    public function getAddress($order_reference_id) {
        if (!isset($this->session->data['apalwa']['login']['access_token'])) {
            $this->debugLog("ERROR", $this->language->get('error_shipping_methods'));

            throw new \RuntimeException($this->language->get('error_shipping_methods'));
        }

        $result = $this->postCurl("GetOrderReferenceDetails", array(
            'AmazonOrderReferenceId' => $order_reference_id,
            'AccessToken' => $this->session->data['apalwa']['login']['access_token']
        ));

        $amazon_address = $result
            ->ResponseBody
            ->GetOrderReferenceDetailsResult
            ->OrderReferenceDetails
            ->Destination
            ->PhysicalDestination;

        $order_buyer = $result
            ->ResponseBody
            ->GetOrderReferenceDetailsResult
            ->OrderReferenceDetails
            ->Buyer;

        $order_telephone = !empty($order_buyer->Phone) ? (string)$order_buyer->Phone : '0000000';

        return $this->amazonAddressToOcAddress($amazon_address, $order_telephone);
    }

    public function getCountryInfo($amazon_address) {
        $sql = "SELECT * FROM `" . DB_PREFIX . "country` WHERE LOWER(`iso_code_2`)='" . $this->db->escape(strtolower($amazon_address->CountryCode)) . "' AND status=1 LIMIT 1";

        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return array(
                'country_id' => (int)$result->row['country_id'],
                'country' => $result->row['name'],
                'iso_code_2' => $result->row['iso_code_2'],
                'iso_code_3' => $result->row['iso_code_3'],
                'address_format' => $result->row['address_format']
            );
        }

        return array(
            'country_id' => 0,
            'country' => '',
            'iso_code_2' => '',
            'iso_code_3' => '',
            'address_format' => ''
        );
    }

    public function getZoneInfo($amazon_address, $country_info) {
        if (!empty($amazon_address->StateOrRegion)) {
            $sql = "SELECT `zone_id`, `code`, `name` FROM `" . DB_PREFIX . "zone` WHERE (LOWER(`name`) LIKE '" . $this->db->escape(strtolower($amazon_address->StateOrRegion)) . "' OR LOWER(`code`) LIKE '" . $this->db->escape(strtolower($amazon_address->StateOrRegion)) . "') AND `country_id` = " . (int)$country_info['country_id'] . " LIMIT 1";

            $result = $this->db->query($sql);

            if ($result->num_rows > 0) {
                return array(
                    'zone_id' => (int)$result->row['zone_id'],
                    'name' => $result->row['name'],
                    'code' => $result->row['code']
                );
            }
        }

        return array(
            'zone_id' => 0,
            'name' => '',
            'code' => ''
        );
    }

    public function getCurlUrl() {
        if ($this->config->get('payment_amazon_login_pay_test') == 'sandbox') {
            if ($this->config->get('payment_amazon_login_pay_payment_region') == 'USD') {
                return 'https://mws.amazonservices.com/OffAmazonPayments_Sandbox/2013-01-01/';
            } else {
                return 'https://mws-eu.amazonservices.com/OffAmazonPayments_Sandbox/2013-01-01/';
            }
        } else {
            if ($this->config->get('payment_amazon_login_pay_payment_region') == 'USD') {
                return 'https://mws.amazonservices.com/OffAmazonPayments/2013-01-01/';
            } else {
                return 'https://mws-eu.amazonservices.com/OffAmazonPayments/2013-01-01/';
            }
        }
    }

    public function urlencode($value) {
        return str_replace('%7E', '~', rawurlencode($value));
    }

    public function calculateStringToSignV2($url, $params) {
        $data = 'POST';
        $data .= "\n";
        $endpoint = parse_url($url);
        $data .= $endpoint['host'];
        $data .= "\n";
        $uri = array_key_exists('path', $endpoint) ? $endpoint['path'] : null;
        if (!isset($uri)) {
            $uri = "/";
        }
        $uriencoded = implode("/", array_map(array($this, "urlencode"), explode("/", $uri)));
        $data .= $uriencoded;
        $data .= "\n";
        uksort($params, 'strcmp');
        $data .= http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        return $data;
    }

    public function makePost($url, $action, $extra = array()) {
        $params = array();

        $params['AWSAccessKeyId'] = $this->config->get('payment_amazon_login_pay_access_key');
        $params['Action'] = $action;
        $params['SellerId'] = $this->config->get('payment_amazon_login_pay_merchant_id');
        $params['SignatureMethod'] = 'HmacSHA256';
        $params['SignatureVersion'] = 2;
        $params['Timestamp'] = date('c', time());
        $params['Version'] = '2013-01-01';

        foreach ($extra as $key => $value) {
            $params[$key] = $value;
        }

        $query = $this->calculateStringToSignV2($url, $params);

        $params['Signature'] = base64_encode(hash_hmac('sha256', $query, $this->config->get('payment_amazon_login_pay_access_secret'), true));

        return http_build_query($params);
    }

    public function postCurl($action, $params = array()) {
        $url = $this->getCurlUrl();

        $post = $this->makePost($url, $action, $params);

        $this->debugLog("URL", $url);
        $this->debugLog("POST", $post);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);

        if (empty($response)) {
            $debug = array(
                'curl_getinfo' => $info,
                'curl_errno' => curl_errno($ch),
                'curl_error' => curl_error($ch)
            );

            curl_close($ch);

            throw $this->loggedException($debug, $this->language->get('error_process_order'));
        } else {
            $this->debugLog("SUCCESS", $response);
        }

        curl_close($ch);

        $result = new stdClass;
        $result->Status = (int)$info['http_code'];

        libxml_use_internal_errors(true);

        try {
            $result->ResponseBody = simplexml_load_string($response);

            $result->ResponseBody->registerXPathNamespace('m', 'http://mws.amazonservices.com/schema/OffAmazonPayments/2013-01-01');

            if (isset($result->ResponseBody->Error)) {
                throw $this->loggedException((string)$result->ResponseBody->Error->Message, $this->language->get('error_process_order'));
            }
        } catch (\Exception $e) {
            throw $this->loggedException($e->getMessage(), $this->language->get('error_process_order'));
        }

        return $result;
    }

    public function loggedException($log_message, $error_message) {
        $id = uniqid();

        $this->debugLog("ERROR", $log_message, $id);

        return new \RuntimeException("#" . $id . ": " . $error_message);
    }

    public function logHandler($code, $message, $file, $line) {
        if (error_reporting() === 0) {
            return false;
        }

        switch ($code) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $error = 'Notice';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $error = 'Warning';
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $error = 'Fatal Error';
                break;
            default:
                $error = 'Unknown';
                break;
        }

        $message = 'PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line;

        if ($this->config->get('error_log')) {
            $this->log->write($message);
        }
    }

    public function debugLog($type, $data, $id = null) {
        if (!$this->config->get('payment_amazon_login_pay_debug')) {
            return;
        }

        if (is_array($data)) {
            $message = json_encode($data);
        } else {
            $message = $data;
        }

        ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $message .= PHP_EOL . ob_get_contents();
        ob_end_clean();

        $log = new \Log(self::LOG_FILENAME);

        $log->write(($id ? '[' . $id . ']: ' : '') . $type . " ---> " . $message);

        unset($log);
    }
}
