<?php

class ControllerExtensionPaymentSquareup extends Controller {
    public function index() {
        $this->load->language('extension/payment/squareup');

        $this->load->library('squareup');

        $data['action'] = $this->url->link('extension/payment/squareup/checkout', 'language=' . $this->config->get('config_language'));
        $data['squareup_js_api'] = Squareup::PAYMENT_FORM_URL;
        
        if (!empty($this->session->data['payment_address']['postcode'])) {
            $data['payment_zip'] = $this->session->data['payment_address']['postcode'];
        } else {
            $data['payment_zip'] = '';
        }

        if ($this->config->get('payment_squareup_enable_sandbox')) {
            $data['app_id'] = $this->config->get('payment_squareup_sandbox_client_id');
            $data['sandbox_message'] = $this->language->get('warning_test_mode');
        } else {
            $data['app_id'] = $this->config->get('payment_squareup_client_id');
            $data['sandbox_message'] = '';
        }

        $data['cards'] = array();

        if ($this->customer->isLogged()) {
            $data['is_logged'] = true;

            $this->load->model('extension/credit_card/squareup');

            $cards = $this->model_extension_credit_card_squareup->getCards($this->customer->getId(), $this->config->get('payment_squareup_enable_sandbox'));

            foreach ($cards as $card) {
                $data['cards'][] = array(
                    'id' => $card['squareup_token_id'],
                    'text' => sprintf($this->language->get('text_card_ends_in'), $card['brand'], $card['ends_in'])
                );
            }
        } else {
            $data['is_logged'] = false;
        }

        return $this->load->view('extension/payment/squareup', $data);
    }

    public function checkout() {
        $this->load->language('extension/payment/squareup');

        $this->load->model('extension/payment/squareup');
        $this->load->model('extension/credit_card/squareup');
        $this->load->model('checkout/order');
        $this->load->model('localisation/country');

        $this->load->library('squareup');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $shipping_country_info = $this->model_localisation_country->getCountry($order_info['shipping_country_id']);

        $billing_country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);

        if (!empty($billing_country_info)) {
            $billing_address = array(
                'first_name' => $order_info['payment_firstname'],
                'last_name' => $order_info['payment_lastname'],
                'address_line_1' => $order_info['payment_address_1'],
                'address_line_2' => $order_info['payment_address_2'],
                'locality' => $order_info['payment_city'],
                'sublocality' => $order_info['payment_zone'],
                'postal_code' => $order_info['payment_postcode'],
                'country' => $billing_country_info['iso_code_2'],
                'organization' => $order_info['payment_company']
            );
        } else {
            $billing_address = array();
        }

        if (!empty($shipping_country_info)) {
            $shipping_address = array(
                'first_name' => $order_info['shipping_firstname'],
                'last_name' => $order_info['shipping_lastname'],
                'address_line_1' => $order_info['shipping_address_1'],
                'address_line_2' => $order_info['shipping_address_2'],
                'locality' => $order_info['shipping_city'],
                'sublocality' => $order_info['shipping_zone'],
                'postal_code' => $order_info['shipping_postcode'],
                'country' => $shipping_country_info['iso_code_2'],
                'organization' => $order_info['shipping_company']
            );
        } else {
            $shipping_address = array();
        }

        $json = array();

        try {
            // Ensure we have registered the customer with Square
            $square_customer = $this->model_extension_credit_card_squareup->getCustomer($this->customer->getId(), $this->config->get('payment_squareup_enable_sandbox'));

            if (!$square_customer && $this->customer->isLogged()) {
                $square_customer = $this->squareup->addLoggedInCustomer();

                $this->model_extension_credit_card_squareup->addCustomer($square_customer);
            }

            $use_saved = false;
            $square_card_id = null;

            // check if user is logged in and wanted to save this card
            if ($this->customer->isLogged() && !empty($this->request->post['squareup_select_card'])) {
                $card_verified = $this->model_extension_credit_card_squareup->verifyCardCustomer($this->request->post['squareup_select_card'], $this->customer->getId());

                if (!$card_verified) {
                    throw new \Squareup\Exception($this->registry, $this->language->get('error_card_invalid'));
                }

                $card = $this->model_extension_credit_card_squareup->getCard($this->request->post['squareup_select_card']);

                $use_saved = true;
                $square_card_id = $card['token'];
            } else if ($this->customer->isLogged() && isset($this->request->post['squareup_save_card'])) {
                // Save the card
                $card_data = array(
                    'card_nonce' => $this->request->post['squareup_nonce'],
                    'billing_address' => $billing_address,
                    'cardholder_name' => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname']
                );

                $square_card = $this->squareup->addCard($square_customer['square_customer_id'], $card_data);

                if (!$this->model_extension_credit_card_squareup->cardExists($this->customer->getId(), $square_card)) {
                    $this->model_extension_credit_card_squareup->addCard($this->customer->getId(), $this->config->get('payment_squareup_enable_sandbox'), $square_card);
                }
                
                $use_saved = true;
                $square_card_id = $square_card['id'];
            }

            // Prepare Transaction
            $transaction_data = array(
                'idempotency_key' => uniqid(),
                'amount_money' => array(
                    'amount' => $this->squareup->lowestDenomination($order_info['total'], $order_info['currency_code']),
                    'currency' => $order_info['currency_code']
                ),
                'billing_address' => $billing_address,
                'buyer_email_address' => $order_info['email'],
                'delay_capture' => !$this->cart->hasRecurringProducts() && $this->config->get('payment_squareup_delay_capture'),
                'integration_id' => Squareup::SQUARE_INTEGRATION_ID
            );
            
            if (!empty($shipping_address)) {
                $transaction_data['shipping_address'] = $shipping_address;
            }

            if ($use_saved) {
                $transaction_data['customer_card_id'] = $square_card_id;
                $transaction_data['customer_id'] = $square_customer['square_customer_id'];
            } else {
                $transaction_data['card_nonce'] = $this->request->post['squareup_nonce'];
            }

            $transaction = $this->squareup->addTransaction($transaction_data);

            if (isset($this->request->server['HTTP_USER_AGENT'])) {
                $user_agent = $this->request->server['HTTP_USER_AGENT'];
            } else {
                $user_agent = '';
            }

            if (isset($this->request->server['REMOTE_ADDR'])) {
                $ip = $this->request->server['REMOTE_ADDR'];
            } else {
                $ip = '';
            }

            $this->model_extension_payment_squareup->addTransaction($transaction, $this->config->get('payment_squareup_merchant_id'), $billing_address, $this->session->data['order_id'], $user_agent, $ip);

            if (!empty($transaction['tenders'][0]['card_details']['status'])) {
                $transaction_status = strtolower($transaction['tenders'][0]['card_details']['status']);
            } else {
                $transaction_status = '';
            }

            $order_status_id = $this->config->get('payment_squareup_status_' . $transaction_status);

            if ($order_status_id) {
                if ($this->cart->hasRecurringProducts() && $transaction_status == 'captured') {
                    foreach ($this->cart->getRecurringProducts() as $item) {
                        if ($item['recurring']['trial']) {
                            $trial_price = $this->tax->calculate($item['recurring']['trial_price'] * $item['quantity'], $item['tax_class_id']);
                            $trial_amt = $this->currency->format($trial_price, $this->session->data['currency']);
                            $trial_text =  sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration']);

                            $item['recurring']['trial_price'] = $trial_price;
                        } else {
                            $trial_text = '';
                        }

                        $recurring_price = $this->tax->calculate($item['recurring']['price'] * $item['quantity'], $item['tax_class_id']);
                        $recurring_amt = $this->currency->format($recurring_price, $this->session->data['currency']);
                        $recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency']);

                        $item['recurring']['price'] = $recurring_price;

                        if ($item['recurring']['duration'] > 0) {
                            $recurring_description .= sprintf($this->language->get('text_length'), $item['recurring']['duration']);
                        }

                        if (!$item['recurring']['trial']) {
                            // We need to override this value for the proper calculation in updateRecurringExpired
                            $item['recurring']['trial_duration'] = 0;
                        }


                        $this->model_extension_payment_squareup->createRecurring($item, $this->session->data['order_id'], $recurring_description, $transaction['id']);
                    }
                }

                $order_status_comment = $this->language->get('squareup_status_comment_' . $transaction_status);

                $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $order_status_id, $order_status_comment, true);
            }

            $json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'));
        } catch (\Squareup\Exception $e) {
            if ($e->isCurlError()) {
                $json['error'] = $this->language->get('text_token_issue_customer_error');
            } else if ($e->isAccessTokenRevoked()) {
                // Send reminder e-mail to store admin to refresh the token
                $this->model_extension_payment_squareup->tokenRevokedEmail();

                $json['error'] = $this->language->get('text_token_issue_customer_error');
            } else if ($e->isAccessTokenExpired()) {
                // Send reminder e-mail to store admin to refresh the token
                $this->model_extension_payment_squareup->tokenExpiredEmail();

                $json['error'] = $this->language->get('text_token_issue_customer_error');
            } else {
                $json['error'] = $e->getMessage();
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    
}
