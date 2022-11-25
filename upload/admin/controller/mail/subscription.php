<?php
namespace Opencart\Admin\Controller\Mail;
class Subscription extends \Opencart\System\Engine\Controller {
    // admin/controller/sale/subscription/addHistory/after
    public function history(string &$route, array &$args, mixed &$output): void {
        if (isset($args[0])) {
            $subscription_id = $args[0];
        } else {
            $subscription_id = 0;
        }

        if (isset($args[1])) {
            $subscription_status_id = $args[1];
        } else {
            $subscription_status_id = 0;
        }

        if (isset($args[2])) {
            $comment = $args[2];
        } else {
            $comment = '';
        }

        if (isset($args[3])) {
            $notify = $args[3];
        } else {
            $notify = '';
        }

        // Subscription
        $this->load->model('sale/subscription');

        $filter_data = [
            'filter_subscription_id'        => $subscription_id,
            'filter_subscription_status_id' => $subscription_status_id,
            'filter_date_next'              => date('Y-m-d H:i:s')
        ];

        $subscriptions = $this->model_checkout_subscription->getSubscriptions($filter_data);

        if ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                // Subscription histories
                $history_total = $this->model_sale_subscription->getTotalHistoriesBySubscriptionStatusId($subscription_status_id);

                // The charge() method handles the subscription statuses in the cron/subscription
                // controller from the catalog whereas an extension needs to return the active subscription status
                if ($history_total && $subscription['subscription_status_id'] == $subscription_status_id) {
                    // Subscription Statuses
                    $this->load->model('localisation/subscription_status');

                    $subscription_status_info = $this->model_localisation_subscription_status->getSubscriptionStatus($subscription_status_id);

                    if ($subscription_status_info) {
                        // Customers
                        $this->load->model('customer/customer');

                        // Customer payment
                        $customer_payment_info = $this->model_customer_customer->getPaymentMehod($subscription['customer_id'], $subscription['customer_payment_id']);

                        if ($customer_payment_info) {
                            // Since the customer payment is integrated into the customer/customer page,
                            // we need to gather the customer's information rather than the order
                            $customer_info = $this->model_customer_customer->getCustomer($subscription['customer_id']);

                            if ($customer_info) {
                                // Settings
                                $this->load->model('setting/setting');

                                // Store
                                $store_info = $this->model_setting_setting->getSetting('config', $customer_info['store_id']);

                                if ($store_info) {
                                    $from = $store_info['config_email'];
                                    $store_name = $store_info['config_name'];
                                    $store_url = $store_info['config_url'];
                                    $alert_email = $store_info['config_mail_alert_email'];
                                } else {
                                    $from = $this->config->get('config_email');
                                    $store_name = $this->config->get('config_name');
                                    $store_url = HTTP_CATALOG;
                                    $alert_email = $this->config->get('config_mail_alert_email');
                                }

                                // Languages
                                $this->load->model('localisation/language');

                                $language_info = $this->model_localisation_language->getLanguage($customer_info['language_id']);

                                if ($language_info) {
                                    if ($comment && $notify) {
                                        $data['comment'] = nl2br($comment);
                                    } else {
                                        $data['comment'] = '';
                                    }

                                    $data['subscription_status'] = $subscription_status_info['name'];

                                    // Languages
                                    $this->load->model('localisation/language');

                                    $language_info = $this->model_localisation_language->getLanguage($customer_info['language_id']);

                                    if ($language_info) {
                                        $language_code = $language_info['code'];
                                    } else {
                                        $language_code = $this->config->get('config_language');
                                    }

                                    // Load the language for any mails using a different country code and prefixing it so it does not pollute the main data pool.
                                    $this->load->language($language_code, 'mail', $language_code);
                                    $this->load->language('mail/subscription', 'mail', $language_code);

                                    $data['date_added'] = date($this->language->get('mail_date_format_short'), $subscription['date_added']);

                                    // Text
                                    $data['text_comment'] = $this->language->get('mail_text_comment');
                                    $data['text_date_added'] = $this->language->get('mail_text_date_added');
                                    $data['text_footer'] = $this->language->get('mail_text_footer');
                                    $data['text_subscription_status'] = $this->language->get('mail_text_subscription_status');

                                    if ($this->config->get('config_mail_engine')) {
                                        $mail = new \Mail($this->config->get('config_mail_engine'));
                                        $mail->parameter = $this->config->get('config_mail_parameter');
                                        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                                        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                                        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                                        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                                        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                                        $mail->setTo($customer_info['email']);
                                        $mail->setFrom($from);
                                        $mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
                                        $mail->setSubject(html_entity_decode(sprintf($this->language->get('mail_text_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
                                        $mail->setText($this->load->view('mail/subscription_history', $data));
                                        $mail->send();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // admin/controller/sale/subscription/addTransaction/after
    public function transaction(string &$route, array &$args, mixed &$output): void {
        if (isset($args[0])) {
            $subscription_id = $args[0];
        } else {
            $subscription_id = 0;
        }

        if (isset($args[1])) {
            $order_id = $args[1];
        } else {
            $order_id = 0;
        }

        if (isset($args[2])) {
            $comment = $args[2];
        } else {
            $comment = '';
        }

        if (isset($args[3])) {
            $amount = $args[3];
        } else {
            $amount = '';
        }

        if (isset($args[4])) {
            $type = $args[4];
        } else {
            $type = '';
        }

        if (isset($args[5])) {
            $payment_method = $args[5];
        } else {
            $payment_method = '';
        }

        if (isset($args[6])) {
            $payment_code = $args[6];
        } else {
            $payment_code = '';
        }

        // Subscription
        $this->load->model('sale/subscription');

        $filter_data = [
            'filter_subscription_id'        => $subscription_id,
            'filter_subscription_status_id' => $this->config->get('config_subscription_canceled_status_id'),
            'filter_date_next'              => date('Y-m-d H:i:s')
        ];

        $subscriptions = $this->model_checkout_subscription->getSubscriptions($filter_data);

        if ($subscriptions) {
            foreach ($subscriptions as $subscription) {
                $transaction_total = $this->model_sale_subscription->getTotalTransactions($subscription_id);

                if ($transaction_total) {
                    // Orders
                    $this->load->model('sale/order');

                    $order_info = $this->model_sale_order->getOrder($order_id);

                    // In this case, since we're canceling a subscription,
                    // the order ID needs to be identical
                    if ($order_info && $subscription['order_id'] == $order_info['order_id']) {
                        // Same for the payment method
                        if ($order_info['payment_method'] == $subscription['payment_method'] && $subscription['payment_method'] == $payment_method) {
                            // Same for the payment code
                            if ($order_info['payment_code'] == $subscription['payment_code'] && $subscription['payment_code'] == $payment_code) {
                                $this->load->language('mail/subscription');

                                // Store
                                $from = $this->config->get('config_email');
                                $store_name = $this->config->get('config_name');
                                $store_url = HTTP_CATALOG;
                                $alert_email = $this->config->get('config_mail_alert_email');

                                if ($comment) {
                                    $data['comment'] = nl2br($comment);
                                } else {
                                    $data['comment'] = '';
                                }

                                $data['subscription_id'] = $subscription_id;
                                $data['payment_method'] = $payment_method;
                                $data['payment_code'] = $payment_code;

                                $data['date_added'] = date($this->language->get('date_format_short'), $subscription['date_added']);

                                if ($this->config->get('config_mail_engine')) {
                                    $mail = new \Mail($this->config->get('config_mail_engine'));
                                    $mail->parameter = $this->config->get('config_mail_parameter');
                                    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                                    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                                    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                                    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                                    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                                    $mail->setTo($from);
                                    $mail->setFrom($from);
                                    $mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
                                    $mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $store_name), ENT_QUOTES, 'UTF-8'));
                                    $mail->setText($this->load->view('mail/subscription_canceled', $data));
                                    $mail->send();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
