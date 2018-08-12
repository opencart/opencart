<?php

class ModelExtensionPaymentSquareup extends Model {
    const RECURRING_ACTIVE = 1;
    const RECURRING_INACTIVE = 2;
    const RECURRING_CANCELLED = 3;
    const RECURRING_SUSPENDED = 4;
    const RECURRING_EXPIRED = 5;
    const RECURRING_PENDING = 6;
    
    const TRANSACTION_DATE_ADDED = 0;
    const TRANSACTION_PAYMENT = 1;
    const TRANSACTION_OUTSTANDING_PAYMENT = 2;
    const TRANSACTION_SKIPPED = 3;
    const TRANSACTION_FAILED = 4;
    const TRANSACTION_CANCELLED = 5;
    const TRANSACTION_SUSPENDED = 6;
    const TRANSACTION_SUSPENDED_FAILED = 7;
    const TRANSACTION_OUTSTANDING_FAILED = 8;
    const TRANSACTION_EXPIRED = 9;

    public function getMethod($address, $total) {
        $geo_zone_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('payment_squareup_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        $squareup_display_name = $this->config->get('payment_squareup_display_name');

        $this->load->language('extension/payment/squareup');

        if (!empty($squareup_display_name[$this->config->get('config_language_id')])) {
            $title = $squareup_display_name[$this->config->get('config_language_id')];
        } else {
            $title = $this->language->get('text_default_squareup_name');
        }

        $status = true;

        $minimum_total = (float)$this->config->get('payment_squareup_total');

        $squareup_geo_zone_id = $this->config->get('payment_squareup_geo_zone_id');

        if ($minimum_total > 0 && $minimum_total > $total) {
            $status = false;
        } else if (empty($squareup_geo_zone_id)) {
            $status = true;
        } else if ($geo_zone_query->num_rows == 0) {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'      => 'squareup',
                'title'     => $title,
                'terms'     => '',
                'sort_order' => (int)$this->config->get('payment_squareup_sort_order')
            );
        }

        return $method_data;
    }

    public function addTransaction($transaction, $merchant_id, $address, $order_id, $user_agent, $ip) {
        $amount = $this->squareup->standardDenomination($transaction['tenders'][0]['amount_money']['amount'], $transaction['tenders'][0]['amount_money']['currency']);

        $this->db->query("INSERT INTO `" . DB_PREFIX . "squareup_transaction` SET transaction_id='" . $this->db->escape($transaction['id']) . "', merchant_id='" . $this->db->escape($merchant_id) . "', location_id='" . $this->db->escape($transaction['location_id']) . "', order_id='" . (int)$order_id . "', transaction_type='" . $this->db->escape($transaction['tenders'][0]['card_details']['status']) . "', transaction_amount='" . (float)$amount . "', transaction_currency='" . $this->db->escape($transaction['tenders'][0]['amount_money']['currency']) . "', billing_address_city='" . $this->db->escape($address['locality']) . "', billing_address_country='" . $this->db->escape($address['country']) . "', billing_address_postcode='" . $this->db->escape($address['postal_code']) . "', billing_address_province='" . $this->db->escape($address['sublocality']) . "', billing_address_street_1='" . $this->db->escape($address['address_line_1']) . "', billing_address_street_2='" . $this->db->escape($address['address_line_2']) . "', device_browser='" . $this->db->escape($user_agent) . "', device_ip='" . $this->db->escape($ip) . "', created_at='" . $this->db->escape($transaction['created_at']) . "', is_refunded='" . (int)(!empty($transaction['refunds'])) . "', refunded_at='" . $this->db->escape(!empty($transaction['refunds']) ? $transaction['refunds'][0]['created_at'] : '') . "', tenders='" . $this->db->escape(json_encode($transaction['tenders'])) . "', refunds='" . $this->db->escape(json_encode(!empty($transaction['refunds']) ? $transaction['refunds'] : array())) . "'");
    }

    public function tokenExpiredEmail() {
        if (!$this->mailResendPeriodExpired('token_expired')) {
            return;
        }

        $mail = new Mail();

        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');

        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $subject = $this->language->get('text_token_expired_subject');
        $message = $this->language->get('text_token_expired_message');

        $mail->setTo($this->config->get('config_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText(strip_tags($message));
        $mail->setHtml($message);

        $mail->send();
    }

    public function tokenRevokedEmail() {
        if (!$this->mailResendPeriodExpired('token_revoked')) {
            return;
        }

        $mail = new Mail();

        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');

        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $subject = $this->language->get('text_token_revoked_subject');
        $message = $this->language->get('text_token_revoked_message');

        $mail->setTo($this->config->get('config_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText(strip_tags($message));
        $mail->setHtml($message);
        
        $mail->send();
    }

    public function cronEmail($result) {
        $mail = new Mail();
        
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');

        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $br = '<br />';

        $subject = $this->language->get('text_cron_subject');

        $message = $this->language->get('text_cron_message') . $br . $br;

        $message .= '<strong>' . $this->language->get('text_cron_summary_token_heading') . '</strong>' . $br;

        if ($result['token_update_error']) {
            $message .= $result['token_update_error'] . $br . $br;
        } else {
            $message .= $this->language->get('text_cron_summary_token_updated') . $br . $br;
        }

        if (!empty($result['transaction_error'])) {
            $message .= '<strong>' . $this->language->get('text_cron_summary_error_heading') . '</strong>' . $br;

            $message .= implode($br, $result['transaction_error']) . $br . $br;
        }

        if (!empty($result['transaction_fail'])) {
            $message .= '<strong>' . $this->language->get('text_cron_summary_fail_heading') . '</strong>' . $br;

            foreach ($result['transaction_fail'] as $order_recurring_id => $amount) {
                $message .= sprintf($this->language->get('text_cron_fail_charge'), $order_recurring_id, $amount) . $br;
            }
        }

        if (!empty($result['transaction_success'])) {
            $message .= '<strong>' . $this->language->get('text_cron_summary_success_heading') . '</strong>' . $br;

            foreach ($result['transaction_success'] as $order_recurring_id => $amount) {
                $message .= sprintf($this->language->get('text_cron_success_charge'), $order_recurring_id, $amount) . $br;
            }
        }

        $mail->setTo($this->config->get('payment_squareup_cron_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender($this->config->get('config_name'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setText(strip_tags($message));
        $mail->setHtml($message);
        $mail->send();
    }

    public function recurringPayments() {
        return (bool)$this->config->get('payment_squareup_recurring_status');
    }

    public function createRecurring($recurring, $order_id, $description, $reference) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring` SET `order_id` = '" . (int)$order_id . "', `date_added` = NOW(), `status` = '" . self::RECURRING_ACTIVE . "', `product_id` = '" . (int)$recurring['product_id'] . "', `product_name` = '" . $this->db->escape($recurring['name']) . "', `product_quantity` = '" . $this->db->escape($recurring['quantity']) . "', `recurring_id` = '" . (int)$recurring['recurring']['recurring_id'] . "', `recurring_name` = '" . $this->db->escape($recurring['recurring']['name']) . "', `recurring_description` = '" . $this->db->escape($description) . "', `recurring_frequency` = '" . $this->db->escape($recurring['recurring']['frequency']) . "', `recurring_cycle` = '" . (int)$recurring['recurring']['cycle'] . "', `recurring_duration` = '" . (int)$recurring['recurring']['duration'] . "', `recurring_price` = '" . (float)$recurring['recurring']['price'] . "', `trial` = '" . (int)$recurring['recurring']['trial'] . "', `trial_frequency` = '" . $this->db->escape($recurring['recurring']['trial_frequency']) . "', `trial_cycle` = '" . (int)$recurring['recurring']['trial_cycle'] . "', `trial_duration` = '" . (int)$recurring['recurring']['trial_duration'] . "', `trial_price` = '" . (float)$recurring['recurring']['trial_price'] . "', `reference` = '" . $this->db->escape($reference) . "'");

        return $this->db->getLastId();
    }

    public function validateCRON() {
        if (!$this->config->get('payment_squareup_status') || !$this->config->get('payment_squareup_recurring_status')) {
            return false;
        }

        if (isset($this->request->get['cron_token']) && $this->request->get['cron_token'] == $this->config->get('payment_squareup_cron_token')) {
            return true;
        }

        if (defined('SQUAREUP_ROUTE')) {
            return true;
        }

        return false;
    }

    public function updateToken() {
        try {
            $response = $this->squareup->refreshToken();

            if (!isset($response['access_token']) || !isset($response['token_type']) || !isset($response['expires_at']) || !isset($response['merchant_id']) || $response['merchant_id'] != $this->config->get('payment_squareup_merchant_id')) {
                return $this->language->get('error_squareup_cron_token');
            } else {
                $this->editTokenSetting(array(
                    'payment_squareup_access_token' => $response['access_token'],
                    'payment_squareup_access_token_expires' => $response['expires_at']
                ));
            }
        } catch (\Squareup\Exception $e) {
            return $e->getMessage();
        }

        return '';
    }

    public function nextRecurringPayments() {
        $payments = array();

        $this->load->library('squareup');

        $recurring_sql = "SELECT * FROM `" . DB_PREFIX . "order_recurring` `or` INNER JOIN `" . DB_PREFIX . "squareup_transaction` st ON (st.transaction_id = `or`.reference) WHERE `or`.status='" . self::RECURRING_ACTIVE . "'";

        $this->load->model('checkout/order');

        foreach ($this->db->query($recurring_sql)->rows as $recurring) {
            if (!$this->paymentIsDue($recurring['order_recurring_id'])) {
                continue;
            }

            $order_info = $this->model_checkout_order->getOrder($recurring['order_id']);

            $billing_address = array(
                'first_name' => $order_info['payment_firstname'],
                'last_name' => $order_info['payment_lastname'],
                'address_line_1' => $recurring['billing_address_street_1'],
                'address_line_2' => $recurring['billing_address_street_2'],
                'locality' => $recurring['billing_address_city'],
                'sublocality' => $recurring['billing_address_province'],
                'postal_code' => $recurring['billing_address_postcode'],
                'country' => $recurring['billing_address_country'],
                'organization' => $recurring['billing_address_company']
            );

            $transaction_tenders = @json_decode($recurring['tenders'], true);

            $price = (float)($recurring['trial'] ? $recurring['trial_price'] : $recurring['recurring_price']);

            $transaction = array(
                'idempotency_key' => uniqid(),
                'amount_money' => array(
                    'amount' => $this->squareup->lowestDenomination($price * $recurring['product_quantity'], $recurring['transaction_currency']),
                    'currency' => $recurring['transaction_currency']
                ),
                'billing_address' => $billing_address,
                'buyer_email_address' => $order_info['email'],
                'delay_capture' => false,
                'customer_id' => $transaction_tenders[0]['customer_id'],
                'customer_card_id' => $transaction_tenders[0]['card_details']['card']['id'],
                'integration_id' => Squareup::SQUARE_INTEGRATION_ID
            );

            $payments[] = array(
                'is_free' => $price == 0,
                'order_id' => $recurring['order_id'],
                'order_recurring_id' => $recurring['order_recurring_id'],
                'billing_address' => $billing_address,
                'transaction' => $transaction
            );
        }

        return $payments;
    }

    public function addRecurringTransaction($order_recurring_id, $reference, $amount, $status) {
        if ($status) {
            $type = self::TRANSACTION_PAYMENT;
        } else {
            $type = self::TRANSACTION_FAILED;
        }

        $this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET order_recurring_id='" . (int)$order_recurring_id . "', reference='" . $this->db->escape($reference) . "', type='" . (int)$type . "', amount='" . (float)$amount . "', date_added=NOW()");
    }

    public function updateRecurringExpired($order_recurring_id) {
        $recurring_info = $this->getRecurring($order_recurring_id);

        if ($recurring_info['trial']) {
            // If we are in trial, we need to check if the trial will end at some point
            $expirable = (bool)$recurring_info['trial_duration'];
        } else {
            // If we are not in trial, we need to check if the recurring will end at some point
            $expirable = (bool)$recurring_info['recurring_duration'];
        }

        // If recurring payment can expire (trial_duration > 0 AND recurring_duration > 0)
        if ($expirable) {
            $number_of_successful_payments = $this->getTotalSuccessfulPayments($order_recurring_id);

            $total_duration = (int)$recurring_info['trial_duration'] + (int)$recurring_info['recurring_duration'];
            
            // If successful payments exceed total_duration
            if ($number_of_successful_payments >= $total_duration) {
                $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET status='" . self::RECURRING_EXPIRED . "' WHERE order_recurring_id='" . (int)$order_recurring_id . "'");

                return true;
            }
        }

        return false;
    }

    public function updateRecurringTrial($order_recurring_id) {
        $recurring_info = $this->getRecurring($order_recurring_id);

        // If recurring payment is in trial and can expire (trial_duration > 0)
        if ($recurring_info['trial'] && $recurring_info['trial_duration']) {
            $number_of_successful_payments = $this->getTotalSuccessfulPayments($order_recurring_id);

            // If successful payments exceed trial_duration
            if ($number_of_successful_payments >= $recurring_info['trial_duration']) {
                $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET trial='0' WHERE order_recurring_id='" . (int)$order_recurring_id . "'");

                return true;
            }
        }

        return false;
    }

    public function suspendRecurringProfile($order_recurring_id) {
        $this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET status='" . self::RECURRING_SUSPENDED . "' WHERE order_recurring_id='" . (int)$order_recurring_id . "'");

        return true;
    }

    private function getLastSuccessfulRecurringPaymentDate($order_recurring_id) {
        return $this->db->query("SELECT date_added FROM `" . DB_PREFIX . "order_recurring_transaction` WHERE order_recurring_id='" . (int)$order_recurring_id . "' AND type='" . self::TRANSACTION_PAYMENT . "' ORDER BY date_added DESC LIMIT 0,1")->row['date_added'];
    }

    private function getRecurring($order_recurring_id) {
        $recurring_sql = "SELECT * FROM `" . DB_PREFIX . "order_recurring` WHERE order_recurring_id='" . (int)$order_recurring_id . "'";

        return $this->db->query($recurring_sql)->row;
    }

    private function getTotalSuccessfulPayments($order_recurring_id) {
        return $this->db->query("SELECT COUNT(*) as total FROM `" . DB_PREFIX . "order_recurring_transaction` WHERE order_recurring_id='" . (int)$order_recurring_id . "' AND type='" . self::TRANSACTION_PAYMENT . "'")->row['total'];
    }

    private function paymentIsDue($order_recurring_id) {
        // We know the recurring profile is active.
        $recurring_info = $this->getRecurring($order_recurring_id);

        if ($recurring_info['trial']) {
            $frequency = $recurring_info['trial_frequency'];
            $cycle = (int)$recurring_info['trial_cycle'];
        } else {
            $frequency = $recurring_info['recurring_frequency'];
            $cycle = (int)$recurring_info['recurring_cycle'];
        }
        // Find date of last payment
        if (!$this->getTotalSuccessfulPayments($order_recurring_id)) {
            $previous_time = strtotime($recurring_info['date_added']);
        } else {
            $previous_time = strtotime($this->getLastSuccessfulRecurringPaymentDate($order_recurring_id));
        }

        switch ($frequency) {
            case 'day' : $time_interval = 24 * 3600; break;
            case 'week' : $time_interval = 7 * 24 * 3600; break;
            case 'semi_month' : $time_interval = 15 * 24 * 3600; break;
            case 'month' : $time_interval = 30 * 24 * 3600; break;
            case 'year' : $time_interval = 365 * 24 * 3600; break;
        }

        $due_date = date('Y-m-d', $previous_time + ($time_interval * $cycle));

        $this_date = date('Y-m-d');

        return $this_date >= $due_date;
    }

    private function editTokenSetting($settings) {
        foreach ($settings as $key => $value) {
            $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code`='payment_squareup' AND `key`='" . $key . "'");

            $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `code`='payment_squareup', `key`='" . $key . "', `value`='" . $this->db->escape($value) . "', serialized=0, store_id=0");
        }
    }

    private function mailResendPeriodExpired($key) {
        $result = (int)$this->cache->get('squareup.' . $key);

        if (!$result) {
            // No result, therefore this is the first e-mail and the re-send period should be regarded as expired.
            $this->cache->set('squareup.' . $key, time());
        } else {
            // There is an entry in the cache. We will calculate the time difference (delta)
            $delta = time() - $result;

            if ($delta >= 15 * 60) {
                // More than 15 minutes have passed, therefore the re-send period has expired.
                $this->cache->set('squareup.' . $key, time());
            } else {
                // Less than 15 minutes have passed before the last e-mail, therefore the re-send period has not expired.
                return false;
            }
        }

        // In all other cases, the re-send period has expired.
        return true;
    }
}
