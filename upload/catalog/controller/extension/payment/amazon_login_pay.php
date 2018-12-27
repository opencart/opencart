<?php

class ControllerExtensionPaymentAmazonLoginPay extends Controller {
    public function session_expired() {
        $this->load->language('extension/payment/amazon_login_pay');

        $this->load->model('extension/payment/amazon_login_pay');

        $this->model_extension_payment_amazon_login_pay->cartRedirect($this->language->get('error_session_expired'));
    }

    public function address() {
        $this->load->language('extension/payment/amazon_login_pay');

        $this->load->model('extension/payment/amazon_login_pay');

        $this->document->setTitle($this->language->get('heading_address'));

        // Verify cart
        $this->model_extension_payment_amazon_login_pay->verifyCart();

        // Verify login
        $this->model_extension_payment_amazon_login_pay->verifyLogin();

        // Verify cart total
        //$this->model_extension_payment_amazon_login_pay->verifyTotal();

        // Cancel an existing order reference
        unset($this->session->data['order_id']);
        
        if (!empty($this->session->data['apalwa']['pay']['order_reference_id']) && !$this->model_extension_payment_amazon_login_pay->isOrderInState($this->session->data['apalwa']['pay']['order_reference_id'], array('Canceled', 'Closed', 'Draft'))) {
            $this->model_extension_payment_amazon_login_pay->cancelOrder($this->session->data['apalwa']['pay']['order_reference_id'], "Shipment widget has been requested, cancelling this order reference.");

            unset($this->session->data['apalwa']['pay']['order_reference_id']);
        }

        $data['text_cart'] = $this->language->get('text_cart');

        $data['shipping_methods'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/shipping_methods', '', true), ENT_COMPAT, "UTF-8");
        $data['shipping'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/shipping', '', true), ENT_COMPAT, "UTF-8");
        $data['cart'] = html_entity_decode($this->url->link('checkout/cart'), ENT_COMPAT, "UTF-8");
        $data['session_expired'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/session_expired'), ENT_COMPAT, "UTF-8");

        $data['client_id'] = $this->config->get('payment_amazon_login_pay_client_id');
        $data['merchant_id'] = $this->config->get('payment_amazon_login_pay_merchant_id');

        if ($this->config->get('payment_amazon_login_pay_test') == 'sandbox') {
            $data['sandbox'] = isset($this->session->data['user_id']); // Require an active admin panel session to show debug messages
        }

        $amazon_payment_js = $this->model_extension_payment_amazon_login_pay->getWidgetJs();
        $this->document->addScript($amazon_payment_js);

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', '', true),
            'text' => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/cart'),
            'text' => $this->language->get('breadcrumb_cart')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/payment/amazon_login_pay/address'),
            'current' => true,
            'text' => $this->language->get('breadcrumb_shipping')
        );

        $data['breadcrumbs'][] = array(
            'href' => null,
            'text' => $this->language->get('breadcrumb_payment')
        );

        $data['breadcrumbs'][] = array(
            'href' => null,
            'text' => $this->language->get('breadcrumb_summary')
        );

        $data['content_main'] = $this->load->view('extension/payment/amazon_login_pay_address', $data);
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/payment/amazon_login_pay_generic', $data));
    }

    public function shipping_methods() {
        $this->load->language('extension/payment/amazon_login_pay');

        $json = array();

        try {
            $this->load->model('extension/payment/amazon_login_pay');
            $this->load->model('setting/extension');

            if (!isset($this->request->get['AmazonOrderReferenceId'])) {
                throw $this->model_extension_payment_amazon_login_pay->loggedException($this->language->get('error_shipping_methods'), $this->language->get('error_shipping_methods'));
            }

            $order_reference_id = $this->request->get['AmazonOrderReferenceId'];

            $this->session->data['apalwa']['pay']['order_reference_id'] = $order_reference_id;

            $address = $this->model_extension_payment_amazon_login_pay->getAddress($order_reference_id);

            $quotes = array();

            $results = $this->model_setting_extension->getExtensions('shipping');

            foreach ($results as $result) {
                if (isset($result['code'])) {
                    $code = $result['code'];
                } else {
                    $code = $result['key'];
                }

                if ($this->config->get('shipping_' . $code . '_status')) {
                    $this->load->model('extension/shipping/' . $code);

                    $quote = $this->{'model_extension_shipping_' . $code}->getQuote($address);

                    if ($quote && empty($quote['error'])) {
                        $quotes[$code] = array(
                            'title' => $quote['title'],
                            'quote' => $quote['quote'],
                            'sort_order' => $quote['sort_order'],
                            'error' => $quote['error']
                        );
                    }
                }
            }

            if (empty($quotes)) {
                throw new \RuntimeException($this->language->get('error_no_shipping_methods'));
            }

            $sort_order = array();

            foreach ($quotes as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $quotes);

            $this->session->data['apalwa']['pay']['shipping_methods'] = $quotes;
            $this->session->data['apalwa']['pay']['address'] = $address;

            $json['quotes'] = $quotes;

            if (!empty($this->session->data['apalwa']['pay']['shipping_method']['code'])) {
                $json['selected'] = $this->session->data['apalwa']['pay']['shipping_method']['code'];
            } else {
                $json['selected'] = '';
            }
        } catch (\RuntimeException $e) {
            $json['error'] = $e->getMessage();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function shipping() {
        $this->load->language('extension/payment/amazon_login_pay');

        $this->load->model('extension/payment/amazon_login_pay');
        $this->load->model('extension/module/amazon_login');

        $json = array(
            'redirect' => null,
            'error' => null
        );

        try {
            if (!isset($this->request->post['shipping_method'])) {
                throw $this->model_extension_payment_amazon_login_pay->loggedException("No shipping method provided.", $this->language->get('error_process_order'));
            }

            $shipping_method = explode('.', $this->request->post['shipping_method']);

            if (!isset($shipping_method[0]) || !isset($shipping_method[1]) || !isset($this->session->data['apalwa']['pay']['shipping_methods'][$shipping_method[0]]['quote'][$shipping_method[1]])) {

                throw $this->model_extension_payment_amazon_login_pay->loggedException("Used shipping method is not allowed.", $this->language->get('error_process_order'));
            }

            $this->session->data['apalwa']['pay']['shipping_method'] = $this->session->data['apalwa']['pay']['shipping_methods'][$shipping_method[0]]['quote'][$shipping_method[1]];
            $this->session->data['shipping_method'] = $this->session->data['apalwa']['pay']['shipping_method'];
            $this->session->data['payment_address'] = $this->session->data['apalwa']['pay']['address'];
            $this->session->data['shipping_address'] = $this->session->data['apalwa']['pay']['address'];
            $this->session->data['shipping_country_id'] = $this->session->data['apalwa']['pay']['address']['country_id'];
            $this->session->data['shipping_zone_id'] = $this->session->data['apalwa']['pay']['address']['zone_id'];

            $this->model_extension_module_amazon_login->persistAddress($this->session->data['apalwa']['pay']['address']);

            $json['redirect'] = $this->url->link('extension/payment/amazon_login_pay/payment', '', true);
        } catch (\RuntimeException $e) {
            $json['error'] = $e->getMessage();
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function payment() {
        $this->load->language('extension/payment/amazon_login_pay');

        $this->load->model('extension/payment/amazon_login_pay');

        $this->document->setTitle($this->language->get('heading_payment'));

        // Verify cart
        $this->model_extension_payment_amazon_login_pay->verifyCart();

        // Verify login
        $this->model_extension_payment_amazon_login_pay->verifyLogin();

        // Verify cart total
        //$this->model_extension_payment_amazon_login_pay->verifyTotal();

        $data['confirm'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/confirm', '', true), ENT_COMPAT, "UTF-8");
        $data['back'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/address', '', true), ENT_COMPAT, "UTF-8");
        $data['session_expired'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/session_expired'), ENT_COMPAT, "UTF-8");

        $data['merchant_id'] = $this->config->get('payment_amazon_login_pay_merchant_id');
        $data['client_id'] = $this->config->get('payment_amazon_login_pay_client_id');

        $data['order_reference_id'] = !empty($this->session->data['apalwa']['pay']['order_reference_id']) ? $this->session->data['apalwa']['pay']['order_reference_id'] : null;

        if ($this->config->get('payment_amazon_login_pay_test') == 'sandbox') {
            $data['sandbox'] = isset($this->session->data['user_id']); // Require an active admin panel session to show debug messages
        }

        $data['error'] = '';
        if (isset($this->session->data['apalwa']['error'])) {
            $data['error'] = $this->session->data['apalwa']['error'];
            unset($this->session->data['apalwa']['error']);
        }

        $amazon_payment_js = $this->model_extension_payment_amazon_login_pay->getWidgetJs();
        $this->document->addScript($amazon_payment_js);

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', '', true),
            'text' => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/cart'),
            'text' => $this->language->get('breadcrumb_cart')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/payment/amazon_login_pay/address'),
            'text' => $this->language->get('breadcrumb_shipping')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/payment/amazon_login_pay/payment'),
            'current' => true,
            'text' => $this->language->get('breadcrumb_payment')
        );

        $data['breadcrumbs'][] = array(
            'href' => null,
            'text' => $this->language->get('breadcrumb_summary')
        );

        $data['content_main'] = $this->load->view('extension/payment/amazon_login_pay_payment', $data);
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/payment/amazon_login_pay_generic', $data));
    }

    public function persist_comment() {
        if (isset($this->request->post['comment'])) {
            $this->session->data['comment'] = strip_tags($this->request->post['comment']);

            $this->session->data['apalwa']['pay']['order']['comment'] = $this->session->data['comment'];
        }
    }

    public function coupon_discard() {
        $this->load->model('extension/payment/amazon_login_pay');

        // Verify reference
        $this->model_extension_payment_amazon_login_pay->verifyReference();

        if ($this->model_extension_payment_amazon_login_pay->isOrderInState($this->session->data['apalwa']['pay']['order_reference_id'], array('Draft'))) {
            unset($this->session->data['coupon']);
        }

        $this->response->redirect($this->url->link('extension/payment/amazon_login_pay/confirm', '', true));
    }

    public function standard_checkout() {
        $this->load->language('extension/payment/amazon_login_pay');

        $this->load->model('extension/payment/amazon_login_pay');

        // Verify cart
        $this->model_extension_payment_amazon_login_pay->verifyCart();

        // Verify login
        $this->model_extension_payment_amazon_login_pay->verifyLogin();

        // Cancel an existing order reference
        if (!empty($this->session->data['apalwa']['pay']['order_reference_id']) && $this->model_extension_payment_amazon_login_pay->isOrderInState($this->session->data['apalwa']['pay']['order_reference_id'], array('Open'))) {
            $this->model_extension_payment_amazon_login_pay->cancelOrder($this->session->data['apalwa']['pay']['order_reference_id'], "Shipment widget has been requested, cancelling this order reference.");
        }

        // Unset all payment data
        unset($this->session->data['apalwa']['pay']);
        unset($this->session->data['order_id']);

        // Redirect to the cart
        $this->response->redirect($this->url->link('checkout/cart', '', true));
    }

    public function confirm() {
        $this->load->language('extension/payment/amazon_login_pay');
        $this->load->language('checkout/checkout');
        
        $this->load->model('extension/payment/amazon_login_pay');

        $this->document->setTitle($this->language->get('heading_confirm'));

        // Verify cart
        $this->model_extension_payment_amazon_login_pay->verifyCart();

        // Verify login
        $this->model_extension_payment_amazon_login_pay->verifyLogin();

        // Verify cart total
        // Not needed, as we will display an error message later on...

        // Verify reference
        $this->model_extension_payment_amazon_login_pay->verifyReference();

        // Verify shipping
        $this->model_extension_payment_amazon_login_pay->verifyShipping();

        $data['merchant_id'] = $this->config->get('payment_amazon_login_pay_merchant_id');
        $data['client_id'] = $this->config->get('payment_amazon_login_pay_client_id');
        
        if ($this->config->get('payment_amazon_login_pay_test') == 'sandbox') {
            $data['sandbox'] = isset($this->session->data['user_id']); // Require an active admin panel session to show debug messages
        }

        $amazon_payment_js = $this->model_extension_payment_amazon_login_pay->getWidgetJs();
        $this->document->addScript($amazon_payment_js);

        try {
            $order = $this->model_extension_payment_amazon_login_pay->makeOrder();

            $this->session->data['apalwa']['pay']['order'] = $order;

            $data['order_reference_id'] = $this->session->data['apalwa']['pay']['order_reference_id'];
            $data['order'] = $order;
        } catch (\RuntimeException $e) {
            $this->model_extension_payment_amazon_login_pay->cartRedirect($e->getMessage());
        }

        $data['success'] = '';

        if (!empty($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        if (isset($this->session->data['coupon'])) {
            $data['coupon'] = $this->session->data['coupon'];
        } else {
            $data['coupon'] = '';
        }

        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }

        $data['is_order_total_positive'] = $this->model_extension_payment_amazon_login_pay->isTotalPositive();
        $data['standard_checkout'] = $this->url->link('extension/payment/amazon_login_pay/standard_checkout', '', true);

        $zero_total = $this->currency->format(0, $this->session->data['currency']);
        $data['error_order_total_zero'] = sprintf($this->language->get('error_order_total_zero'), $zero_total);

        $data['process'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/process', '', true), ENT_COMPAT, "UTF-8");
        $data['back'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/payment', '', true), ENT_COMPAT, "UTF-8");
        $data['session_expired'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/session_expired'), ENT_COMPAT, "UTF-8");
        $data['coupon_discard'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/coupon_discard', '', true), ENT_COMPAT, "UTF-8");
        $data['coupon_apply'] = html_entity_decode($this->url->link('extension/total/coupon/coupon', '', true), ENT_COMPAT, "UTF-8");
        $data['persist_comment'] = html_entity_decode($this->url->link('extension/payment/amazon_login_pay/persist_comment', '', true), ENT_COMPAT, "UTF-8");
        $data['is_coupon_change_allowed'] = $this->model_extension_payment_amazon_login_pay->isOrderInState($this->session->data['apalwa']['pay']['order_reference_id'], array('Draft'));

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', '', true),
            'text' => $this->language->get('text_home')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('checkout/cart'),
            'text' => $this->language->get('breadcrumb_cart')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/payment/amazon_login_pay/address'),
            'text' => $this->language->get('breadcrumb_shipping')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/payment/amazon_login_pay/payment'),
            'text' => $this->language->get('breadcrumb_payment')
        );

        $data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/payment/amazon_login_pay/confirm'),
            'current' => true,
            'text' => $this->language->get('breadcrumb_summary')
        );

        $location_currency = $this->config->get('payment_amazon_login_pay_payment_region');
        $rate = round($this->currency->getValue($location_currency) / $this->currency->getValue($order['currency_code']), 8);
        $amount = $this->currency->format($this->currency->convert($order['total'], $this->config->get('config_currency'), $location_currency), $location_currency, 1, true);

        $data['is_amount_converted'] = $order['currency_code'] != $location_currency;
        $data['text_amount_converted'] = sprintf($this->language->get('text_amount_converted'), $location_currency, $rate, $amount);

        $data['content_main'] = $this->load->view('extension/payment/amazon_login_pay_confirm', $data);
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('extension/payment/amazon_login_pay_generic', $data));
    }

    public function process() {
        $this->load->language('extension/payment/amazon_login_pay');
        $this->load->language('checkout/checkout');
        
        $this->load->model('extension/payment/amazon_login_pay');
        $this->load->model('checkout/order');

        // Verify cart
        $this->model_extension_payment_amazon_login_pay->verifyCart();

        // Verify login
        $this->model_extension_payment_amazon_login_pay->verifyLogin();

        // Verify cart total
        // Not needed, as we will display an error message later on...

        // Verify reference
        $this->model_extension_payment_amazon_login_pay->verifyReference();

        // Verify shipping
        $this->model_extension_payment_amazon_login_pay->verifyShipping();

        // Verify order
        $this->model_extension_payment_amazon_login_pay->verifyOrder();

        try {
            $order_reference_id = $this->session->data['apalwa']['pay']['order_reference_id'];

            if (empty($this->session->data['order_id'])) {
                // Up to this point, everything is fine in the session. Save the order and submit it to Amazon.
                $order_id = $this->model_checkout_order->addOrder($this->session->data['apalwa']['pay']['order']);

                $this->session->data['order_id'] = $order_id;

                $this->model_extension_payment_amazon_login_pay->submitOrderDetails($order_reference_id, $order_id);
            } else {
                $order_id = $this->session->data['order_id'];
            }

            // Check constraints
            $constraints = $this->model_extension_payment_amazon_login_pay->fetchOrder($order_reference_id)->Constraints;

            if (!empty($constraints->Constraint)) {
                // We do not expect to fall under the other kinds of constraints. For more information, see: https://pay.amazon.com/us/developer/documentation/apireference/201752890
                $payment_page_errors = array(
                    'PaymentPlanNotSet' => $this->language->get('error_constraint_payment_plan_not_set'),
                    'PaymentMethodNotAllowed' => $this->language->get('error_constraint_payment_method_not_allowed'),
                    'AmountNotSet' => $this->language->get('error_constraint_amount_not_set')
                );

                $constraint_id = (string)$constraints->Constraint->ConstraintID;

                if (in_array($constraint_id, array_keys($payment_page_errors))) {
                    $this->session->data['apalwa']['error'] = $payment_page_errors[$constraint_id];

                    $this->response->redirect($this->url->link('extension/payment/amazon_login_pay/payment', '', true));
                } else {
                    throw new \RuntimeException($constraints->Constraint->Description);
                }
            }

            // Open the order for authorization
            $this->model_extension_payment_amazon_login_pay->confirmOrder($order_reference_id);

            $amazon_order = $this->model_extension_payment_amazon_login_pay->fetchOrder($order_reference_id);

            // The order has been opened for authorization. Store it in the database
            $amazon_login_pay_order_id = $this->model_extension_payment_amazon_login_pay->findOrAddOrder($amazon_order);

            // Authorize the order
            $authorization = $this->model_extension_payment_amazon_login_pay->authorizeOrder($amazon_order);

            // Log the authorization
            $this->model_extension_payment_amazon_login_pay->addAuthorization($amazon_login_pay_order_id, $authorization);

            if ($authorization->AuthorizationStatus->State == 'Declined') {
                $reason_code = (string)$authorization->AuthorizationStatus->ReasonCode;

                switch ($reason_code) {
                    case 'InvalidPaymentMethod' :
                        $this->session->data['apalwa']['error'] = $this->language->get('error_decline_invalid_payment_method');

                        $this->response->redirect($this->url->link('extension/payment/amazon_login_pay/payment', '', true));
                    break;
                    default : 
                        if ($this->model_extension_payment_amazon_login_pay->isOrderInState($order_reference_id, array('Open'))) {
                            $this->model_extension_payment_amazon_login_pay->cancelOrder($order_reference_id, "Authorization has failed with the state: " . $authorization->AuthorizationStatus->State);
                        }

                        $cart_error_messages = array(
                            'TransactionTimedOut' => $this->language->get('error_decline_transaction_timed_out'),
                            'AmazonRejected' => $this->language->get('error_decline_amazon_rejected'),
                            'ProcessingFailure' => $this->language->get('error_decline_processing_failure')
                        );

                        if (in_array($reason_code, array_keys($cart_error_messages))) {
                            //@todo - do the logout with amazon.Login.logout(); instead
                            unset($this->session->data['apalwa']);

                            // capital L in Amazon cookie name is required, do not alter for coding standards
                            if (isset($this->request->cookie['amazon_Login_state_cache'])) {
                                //@todo - rework this by triggering the JavaScript logout
                                setcookie('amazon_Login_state_cache', null, -1, '/');
                            }

                            throw new \RuntimeException($cart_error_messages[$reason_code]);
                        } else {
                            // This should never occur, but just in case...
                            throw $this->model_extension_payment_amazon_login_pay->loggedException("Authorization has failed with code: " . $reason_code, $this->language->get('error_process_order'));
                        }
                    break;
                }
            }

            // Amend the billing address based on the Authorize response
            if (!empty($authorization->AuthorizationBillingAddress)) {
                $this->model_extension_payment_amazon_login_pay->updatePaymentAddress($order_id, $authorization->AuthorizationBillingAddress);
            }

            // Clean the session and redirect to the success page
            unset($this->session->data['apalwa']['pay']);

            // In case a payment has been completed, and the order is not closed, close it.
            if (isset($authorization->CapturedAmount->Amount) && (float)$authorization->CapturedAmount->Amount && $this->model_extension_payment_amazon_login_pay->isOrderInState($order_reference_id, array('Open', 'Suspended'))) {
                $this->model_extension_payment_amazon_login_pay->closeOrder($order_reference_id, "A capture has been performed. Closing the order.");
            }

            // Log any errors triggered by addOrderHistory, but without displaying them
            set_error_handler(array($this->model_extension_payment_amazon_login_pay, 'logHandler'));

            try {
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_amazon_login_pay_pending_status'));
            } catch (\Exception $e) {
                if ($this->config->get('error_log')) {
                    $this->log->write($e->getMessage());
                }
            }

            $this->response->redirect($this->url->link('checkout/success', '', true));
        } catch (\RuntimeException $e) {
            $this->model_extension_payment_amazon_login_pay->cartRedirect($e->getMessage());
        }
    }

    public function ipn() {
        $this->load->model('extension/payment/amazon_login_pay');

        try {
            if (!isset($this->request->get['token'])) {
                throw new \RuntimeException('GET variable "token" is missing.');
            }

            if (trim($this->request->get['token']) == '') {
                throw new \RuntimeException('GET variable "token" set, but is empty.');
            }

            if (!$this->config->get('payment_amazon_login_pay_ipn_token')) {
                throw new \RuntimeException('CONFIG variable "payment_amazon_login_pay_ipn_token" is empty.');
            }

            if (!hash_equals(trim($this->config->get('payment_amazon_login_pay_ipn_token')), trim($this->request->get['token']))) {
                throw new \RuntimeException('Token values are different.');
            }

            // Everything is fine. Process the IPN
            $body = file_get_contents('php://input');

            $this->model_extension_payment_amazon_login_pay->debugLog('IPN BODY', $body);

            if ($body) {
                $xml = $this->model_extension_payment_amazon_login_pay->parseIpnBody($body);

                switch ($xml->getName()) {
                    case 'AuthorizationNotification':
                        $this->model_extension_payment_amazon_login_pay->authorizationIpn($xml);
                        break;
                    case 'CaptureNotification':
                        $this->model_extension_payment_amazon_login_pay->captureIpn($xml);
                        break;
                    case 'RefundNotification':
                        $this->model_extension_payment_amazon_login_pay->refundIpn($xml);
                        break;
                }
            }
        } catch (\RuntimeException $e) {
            $this->model_extension_payment_amazon_login_pay->debugLog('IPN ERROR', $e->getMessage());
        }

        $this->response->addHeader('HTTP/1.1 200 OK');
        $this->response->addHeader('Content-Type: application/json');
    }

    public function capture(&$route, &$args, &$output) {
        $this->load->language('extension/payment/amazon_login_pay');

        $this->load->model('extension/payment/amazon_login_pay');
        $order_id = $args[0];

        $order_info = $this->model_checkout_order->getOrder($order_id);

        if ($order_info['order_status_id'] == $this->config->get('payment_amazon_login_pay_capture_status')) {
            try {
                $amazon_login_pay_order = $this->model_extension_payment_amazon_login_pay->getOrderByOrderId($order_id);

                $capture_response = $this->model_extension_payment_amazon_login_pay->captureOrder($amazon_login_pay_order['amazon_authorization_id'], $amazon_login_pay_order['total'], $amazon_login_pay_order['currency_code']);

                if (isset($capture_response->CaptureStatus->State) && in_array($capture_response->CaptureStatus->State, array('Completed', 'Pending'))) {
                    $order_reference_id = $amazon_login_pay_order['amazon_order_reference_id'];

                    if ($this->model_extension_payment_amazon_login_pay->isOrderInState($order_reference_id, array('Open', 'Suspended'))) {
                        $this->model_extension_payment_amazon_login_pay->closeOrder($order_reference_id, "Captured amount: " . (string)$capture_response->CaptureAmount->Amount . " " . (string)$capture_response->CaptureAmount->CurrencyCode);
                    }

                    $transaction = array(
                        'amazon_login_pay_order_id' => $amazon_login_pay_order['amazon_login_pay_order_id'],
                        'amazon_authorization_id' => $amazon_login_pay_order['amazon_authorization_id'],
                        'amazon_capture_id' => $capture_response->AmazonCaptureId,
                        'amazon_refund_id' => '',
                        'date_added' => date('Y-m-d H:i:s', strtotime((string)$capture_response->CreationTimestamp)),
                        'type' => 'capture',
                        'status' => (string)$capture_response->CaptureStatus->State,
                        'amount' => (float)$capture_response->CaptureAmount->Amount
                    );

                    $this->model_extension_payment_amazon_login_pay->addTransaction($transaction);

                    $this->model_extension_payment_amazon_login_pay->updateStatus($amazon_login_pay_order['amazon_authorization_id'], 'authorization', 'Closed');

                    $this->model_extension_payment_amazon_login_pay->updateCapturedStatus($amazon_login_pay_order['amazon_login_pay_order_id'], 1);
                }
            } catch (\RuntimeException $e) {
                // Do nothing, as the exception is logged in case of debug logging.
            }
        }
    }
}
