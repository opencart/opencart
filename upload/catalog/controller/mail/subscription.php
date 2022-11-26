<?php
namespace Opencart\Catalog\Controller\Mail;
class Subscription extends \Opencart\System\Engine\Controller {
    public function index(string &$route, array &$args, mixed &$output): void {
        if (isset($args[0])) {
            $subscription_id = $args[0];
        } else {
            $subscription_id = 0;
        }

        if (isset($args[1]['subscription'])) {
            $subscription = $args[1]['subscription'];
        } else {
            $subscription = [];
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
        /*
        $subscription['order_product_id']
        $subscription['customer_id']
        $subscription['order_id']
        $subscription['subscription_plan_id']
        $subscription['customer_payment_id'],
        $subscription['name']
        $subscription['description']
        $subscription['trial_price']
        $subscription['trial_frequency']
        $subscription['trial_cycle']
        $subscription['trial_duration']
        $subscription['trial_remaining']
        $subscription['trial_status']
        $subscription['price']
        $subscription['frequency']
        $subscription['cycle']
        $subscription['duration']
        $subscription['remaining']
        $subscription['date_next']
        $subscription['status']
        */

        // Subscription
        $this->load->model('account/subscription');

        $filter_data = [
            'filter_subscription_id'        => $subscription_id,
            'filter_date_next'              => date('Y-m-d H:i:s'),
            'filter_subscription_status_id' => $this->config->get('config_subscription_active_status_id'),
            'start'                         => 0,
            'limit'                         => 1
        ];

        $subscriptions = $this->model_account_subscription->getSubscriptions($filter_data);

        if ($subscriptions) {
            $this->load->language('mail/subscription');

            $this->load->model('account/customer');

            foreach ($subscriptions as $result) {
                $customer_info = $this->model_account_customer->getCustomer($result['customer_id']);

                if ($customer_info && $customer_info['status'] && strtotime($result['date_added']) == strtotime($subscription['date_added']) && strtotime($result['date_next']) == strtotime($subscription['date_next']) && $customer_info['customer_id'] == $subscription['customer_id'] && $result['order_id'] == $subscription['order_id'] && $result['subscription_plan_id'] == $subscription['subscription_plan_id']) {
                    // Only match the latest order ID of the same customer ID
                    // since new subscriptions cannot be re-added with the same
                    // order ID; only as a new order ID added by an extension

                    // Payment Methods
                    $this->load->model('account/payment_method');

                    $payment_method = $this->model_account_payment_method->getPaymentMethod($result['customer_id'], $subscription['customer_payment_id']);

                    if ($payment_method) {
                        // Subscription
                        $this->load->model('checkout/subscription');

                        $subscription_order_product = $this->model_checkout_subscription->getSubscriptionByOrderProductId($result['order_product_id']);

                        if ($subscription_order_product) {
                            // Orders
                            $this->load->model('account/order');

                            // Order Products
                            $order_product = $this->model_account_order->getProduct($result['order_id'], $result['order_product_id']);

                            if ($order_product && $order_product['order_product_id'] == $subscription['order_product_id']) {
                                $products = $this->cart->getProducts();

                                $description = '';

                                foreach ($products as $product) {
                                    if ($product['product_id'] == $order_product['product_id']) {
                                        $trial_price = $this->currency->format($this->tax->calculate($result['trial_price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                                        $trial_cycle = $result['trial_cycle'];
                                        $trial_frequency = $this->language->get('text_' . $result['trial_frequency']);
                                        $trial_duration = $result['trial_duration'];

                                        if ($product['trial_status']) {
                                            $description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
                                        }

                                        $price = $this->currency->format($this->tax->calculate($result['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                                        $cycle = $result['cycle'];
                                        $frequency = $this->language->get('text_' . $result['frequency']);
                                        $duration = $result['duration'];

                                        if ($duration) {
                                            $description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
                                        } else {
                                            $description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
                                        }
                                    }
                                }

                                // Both descriptions need to match to maintain the
                                // mutual agreement of the subscription in accordance
                                // with the service providers
                                if ($description && $description == $subscription['description']) {
                                    // Subscription date
                                    $subscription_period = strtotime($result['date_next']);

                                    // Orders
                                    $this->load->model('checkout/order');

                                    $order_info = $this->model_checkout_order->getOrder($result['order_id']);

                                    // Transactions
                                    $transactions = $this->model_account_subscription->getTransactions($result['customer_id']);

                                    $transaction_dates = array_column($transactions, 'date_added');
                                    $date_max = strtotime(max($transaction_dates));

                                    if ($order_info && $transactions && strtotime($result['date_added']) == $date_max) {
                                        $date_added = strtotime($order_info['date_added']);
                                        $date_added = (strtotime($result['date_added']) - $date_added);
                                        $date_cycle = round($date_added / (60 * 60 * 24));

                                        if ($date_cycle >= 0) {
                                            // Stores
                                            $this->load->model('setting/store');

                                            // Settings
                                            $this->load->model('setting/setting');

                                            $store_info = $this->model_setting_store->getStore($order_info['store_id']);

                                            if ($store_info) {
                                                $store_logo = html_entity_decode($this->model_setting_setting->getValue('config_logo', $store_info['store_id']), ENT_QUOTES, 'UTF-8');
                                                $store_name = html_entity_decode($store_info['name'], ENT_QUOTES, 'UTF-8');

                                                $store_url = $store_info['url'];

                                                $from = html_entity_decode($store_info['config_email'], ENT_QUOTES, 'UTF-8');
                                            } else {
                                                $store_logo = html_entity_decode($this->config->get('config_logo'), ENT_QUOTES, 'UTF-8');
                                                $store_name = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

                                                $store_url = HTTP_SERVER;

                                                $from = html_entity_decode($this->config->get('config_email'), ENT_QUOTES, 'UTF-8');
                                            }

                                            // Subscription Status
                                            $subscription_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `subscription_status_id` = '" . (int)$result['subscription_status_id'] . "' AND `language_id` = '" . (int)$order_info['language_id'] . "'");

                                            if ($subscription_status_query->num_rows) {
                                                $data['subscription_status'] = $subscription_status_query->row['name'];
                                            } else {
                                                $data['subscription_status'] = '';
                                            }

                                            // Languages
                                            $this->load->model('localisation/language');

                                            $language_info = $this->model_localisation_language->getLanguage($order_info['language_id']);

                                            // We need to compare both language IDs as they both need to match.
                                            if ($language_info) {
                                                $language_code = $language_info['code'];
                                            } else {
                                                $language_code = $this->config->get('config_language');
                                            }

                                            // Load the language for any mails using a different country code and prefixing it, so it does not pollute the main data pool.
                                            $this->load->language($language_code, 'mail', $language_code);
                                            $this->load->language('mail/subscription', 'mail', $language_code);

                                            // Add language vars to the template folder
                                            $results = $this->language->all('mail');

                                            foreach ($results as $key => $value) {
                                                $data[$key] = $value;
                                            }

                                            // Image files
                                            $this->load->model('tool/image');

                                            if (is_file(DIR_IMAGE . $store_logo)) {
                                                $data['logo'] = $store_url . 'image/' . $store_logo;
                                            } else {
                                                $data['logo'] = '';
                                            }

                                            $data['text_greeting'] = sprintf($this->language->get('mail_text_greeting'), $store_name);

                                            $data['store'] = $store_name;
                                            $data['store_url'] = $store_url;

                                            $data['customer_id'] = $order_info['customer_id'];

                                            // Subscription
                                            if ($comment && $notify) {
                                                $data['comment'] = nl2br($comment);
                                            } else {
                                                $data['comment'] = '';
                                            }

                                            $data['description'] = $result['description'];

                                            $subject = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);

                                            $data['title'] = sprintf($this->language->get('mail_text_subject'), $store_name, $order_info['order_id']);
                                            $data['link'] = $store_url . 'index.php?route=account/subscription/info&subscription_id=' . $subscription_id;

                                            $data['order_id'] = $order_info['order_id'];
                                            $data['date_added'] = date($this->language->get('mail_date_format_short'), strtotime($order_info['date_added']));
                                            $data['payment_method'] = $order_info['payment_method'];
                                            $data['email'] = $order_info['email'];
                                            $data['telephone'] = $order_info['telephone'];
                                            $data['ip'] = $order_info['ip'];

                                            // Order Totals
                                            $data['totals'] = [];

                                            $order_totals = $this->model_checkout_order->getTotals($order_info['order_id']);

                                            foreach ($order_totals as $order_total) {
                                                $data['totals'][] = [
                                                    'title' => $order_total['title'],
                                                    'text'  => $this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']),
                                                ];
                                            }

                                            // Products
                                            $data['name'] = $order_product['name'];
                                            $data['quantity'] = $order_product['quantity'];
                                            $data['price'] = $this->currency->format($order_product['price'], $order_info['currency_code'], $order_info['currency_value']);
                                            $data['total'] = $this->currency->format($order_product['total'], $order_info['currency_code'], $order_info['currency_value']);

                                            $data['order'] = $this->url->link('account/order/info', 'order_id=' . $order_info['order_id']);
                                            $data['product'] = $this->url->link('product/product', 'product_id=' . $order_product['product_id']);

                                            if ($this->config->get('payment_' . $payment_method['code'] . '_status') && $customer_info['newsletter']) {
                                                $this->load->model('extension/payment/' . $payment_method['code']);

                                                // Promotion
                                                if (property_exists($this->{'model_extension_payment_' . $payment_method['code']}, 'promotion')) {
                                                    /*
                                                      * The extension must create a new order
                                                      * The trial status and the status must
                                                      * also be handled accordingly to complete
                                                      * this transaction. It must not be charged
                                                      * to the customer until the next billing cycle
                                                    */
                                                    $subscription_status_id = $this->{'model_extension_payment_' . $payment_method['code']}->promotion($result['subscription_id']);

                                                    if ($store_info) {
                                                        $subscription_active_status_id = $this->model_setting_setting->getValue('config_subscription_active_status_id', $store_info['store_id']);
                                                    } else {
                                                        $subscription_active_status_id = $this->config->get('config_subscription_active_status_id');
                                                    }

                                                    // Transaction
                                                    if ($subscription_active_status_id == $subscription_status_id) {
                                                        $filter_data = [
                                                            'filter_subscription_status_id' => $subscription_status_id,
                                                            'filter_date_next'              => date('Y-m-d', strtotime($result['date_next'])),
                                                            'start'                         => 0,
                                                            'limit'                         => 1
                                                        ];

                                                        $next_subscriptions = $this->model_account_subscription->getSubscriptions($filter_data);

                                                        if ($next_subscriptions) {
                                                            foreach ($next_subscriptions as $next_subscription) {
                                                                if ($next_subscription['customer_id'] == $customer_info['customer_id'] && (int)$next_subscription['cycle'] >= 0 && strtotime($next_subscription['date_next']) != strtotime($result['date_next'])) {
                                                                    // Transactions
                                                                    $transactions = $this->model_account_subscription->getTransactions($next_subscription['customer_id']);

                                                                    $transaction_dates = array_column($transactions, 'date_added');
                                                                    $next_date_max = strtotime(max($transaction_dates));

                                                                    $next_subscription_period = strtotime($next_subscription['date_next']);
                                                                    $calc_subscription_period = ($next_subscription_period - $subscription_period);
                                                                    $next_subscription_cycle = round($calc_subscription_period / (60 * 60 * 24));

                                                                    $frequencies = [
                                                                        'day',
                                                                        'week',
                                                                        'semi_month',
                                                                        'month',
                                                                        'year'
                                                                    ];

                                                                    // Validate the latest subscription values with the ones edited
                                                                    // by promotional extensions
                                                                    if ($transactions && strtotime($next_subscription['date_next']) == $next_date_max && $next_subscription_cycle >= 0 && $next_subscription['subscription_id'] != $result['subscription_id'] && $next_subscription['order_id'] != $result['order_id'] && $next_subscription['description'] != $description && $next_subscription['order_product_id'] != $result['order_product_id'] && $next_subscription['customer_id'] == $result['customer_id'] && $next_subscription['duration'] == $result['duration'] && $result['duration'] == $subscription['duration'] && $subscription['duration'] == 1 && in_array($next_subscription['frequency'], $frequencies)) {
                                                                        // We need to validate frequencies in compliance of the admin subscription plans
                                                                        // as with the use of the APIs
                                                                        if ($next_subscription['frequency'] == 'semi_month') {
                                                                            $period = strtotime("2 weeks");
                                                                        } else {
                                                                            $period = strtotime($next_subscription['cycle'] . ' ' . $next_subscription['frequency']);
                                                                        }

                                                                        // Calculates the remaining days between the subscription
                                                                        // promotional period and the date added period
                                                                        $period = ($subscription_period - $period);

                                                                        // Calculate remaining period of each features
                                                                        $cycle = round($period / (60 * 60 * 24));

                                                                        // Promotional subscription plans for full membership must be differed
                                                                        // until the time period has exceeded. Therefore, we need to match the
                                                                        // cycle period with the current time period; including pro rata
                                                                        if ($next_subscription['status'] && $subscription['status'] && !$next_subscription['trial_status'] && $cycle >= 0 && $next_subscription['subscription_plan_id'] != $result['subscription_plan_id']) {
                                                                            // Order Products
                                                                            $next_order_product = $this->model_account_order->getProduct($next_subscription['order_id'], $next_subscription['order_product_id']);

                                                                            if ($next_order_product) {
                                                                                $next_order_info = $this->model_account_order->getOrder($next_subscription['order_id']);

                                                                                if ($next_order_info) {
                                                                                    $date_added = strtotime($next_order_info['date_added']);
                                                                                    $date_added = ($next_subscription_period - $date_added);
                                                                                    $next_date_cycle = round($date_added / (60 * 60 * 24));

                                                                                    // If the order date cycle is greater than the next
                                                                                    // cycle time period. Therefore, the order status ID
                                                                                    // must be set to pending from the extension since the
                                                                                    // promotion will occur on the next billing cycle
                                                                                    if ($next_date_cycle >= 0 && $next_order_info['order_status_id'] == $this->config->get('config_subscription_pending_status_id')) {
                                                                                        // Products
                                                                                        $this->load->model('catalog/product');

                                                                                        $product_subscription_info = $this->model_catalog_product->getSubscription($next_order_product['product_id'], $next_subscription['subscription_plan_id']);

                                                                                        if ($product_subscription_info && (int)$product_subscription_info['cycle'] >= 0 && $product_subscription_info['subscription_plan_id'] == $next_subscription['subscription_plan_id'] && $product_subscription_info['duration'] == $next_subscription['duration']) {
                                                                                            // Add Transaction
                                                                                            $this->model_account_subscription->addTransaction($subscription_id, $subscription['order_id'], $this->language->get('text_promotion'), $next_subscription['amount'], 0, $next_order_info['payment_method'], $next_order_info['payment_code']);
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }

                                            // Since we send an email based on subscription statuses
                                            // and not based on promotional products, only subscribed
                                            // customers can receive the emails; either by automation
                                            // or on-demand.
                                            if (($result['trial_status'] && $subscription['trial_status']) || ($result['status'] && $subscription['status'])) {
                                                // Mail
                                                if ($this->config->get('config_mail_engine')) {
                                                    $mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
                                                    $mail->parameter = $this->config->get('config_mail_parameter');
                                                    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                                                    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                                                    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                                                    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                                                    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                                                    $mail->setTo($order_info['email']);
                                                    $mail->setFrom($from);
                                                    $mail->setSender($store_name);
                                                    $mail->setSubject($subject);
                                                    $mail->setHtml($this->load->view('mail/subscription', $data));
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
            }
        }
    }

    public function cancel(string &$route, array &$args, mixed &$output): void {
        if (isset($args[0])) {
            $subscription_id = $args[0];
        } else {
            $subscription_id = 0;
        }

        if (isset($args[1]['subscription'])) {
            $subscription = $args[1]['subscription'];
        } else {
            $subscription = [];
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
        /*
        $subscription['order_product_id']
        $subscription['customer_id']
        $subscription['order_id']
        $subscription['subscription_plan_id']
        $subscription['customer_payment_id'],
        $subscription['name']
        $subscription['description']
        $subscription['trial_price']
        $subscription['trial_frequency']
        $subscription['trial_cycle']
        $subscription['trial_duration']
        $subscription['trial_remaining']
        $subscription['trial_status']
        $subscription['price']
        $subscription['frequency']
        $subscription['cycle']
        $subscription['duration']
        $subscription['remaining']
        $subscription['date_next']
        $subscription['status']
        */

        // Subscription
        $this->load->model('account/subscription');

        $subscription_info = $this->model_account_subscription->getSubscription($subscription_id);

        if ($subscription_info) {
            $this->load->language('mail/subscription');

            // Customers
            $this->load->model('account/customer');

            $customer_info = $this->model_account_customer->getCustomer($subscription_info['customer_id']);

            if ($customer_info && $customer_info['status'] && strtotime($subscription_info['date_added']) == strtotime($subscription['date_added']) && strtotime($subscription_info['date_next']) == strtotime($subscription['date_next']) && $customer_info['customer_id'] == $subscription['customer_id'] && $subscription_info['order_id'] == $subscription['order_id'] && $subscription_info['subscription_plan_id'] == $subscription['subscription_plan_id']) {
                // Only match the latest order ID of the same customer ID
                // since new subscriptions cannot be re-added with the same
                // order ID; only as a new order ID added by an extension

                // Payment Methods
                $this->load->model('account/payment_method');

                $payment_method = $this->model_account_payment_method->getPaymentMethod($subscription_info['customer_id'], $subscription['customer_payment_id']);

                if ($payment_method) {
                    // Subscription date
                    $subscription_period = strtotime($subscription_info['date_next']);

                    // We need to validate frequencies in compliance of the admin subscription plans
                    // as with the use of the APIs
                    if ($subscription_info['frequency'] == 'semi_month') {
                        $period = strtotime("2 weeks");
                    } else {
                        $period = strtotime($subscription_info['cycle'] . ' ' . $subscription_info['frequency']);
                    }

                    // Calculates the remaining days between the subscription
                    // promotional period and the date added period
                    $period = ($subscription_period - $period);

                    // Calculate remaining period of each features
                    $cycle = round($period / (60 * 60 * 24));

                    // If expired subscription without renewal process,
                    // we cancel the subscription
                    if ($cycle < 0 && $subscription_info['status'] && $subscription['status']) {
                        // Orders
                        $this->load->model('account/order');

                        $order_info = $this->model_account_order->getOrder($subscription_info['customer_id']);

                        if ($order_info) {
                            // Cancel
                            if ($this->config->get('payment_' . $payment_method['code'] . '_status')) {
                                $this->load->model('extension/payment/' . $payment_method['code']);

                                if (property_exists($this->{'model_extension_payment_' . $payment_method['code']}, 'cancel')) {
                                    $subscription_status_id = $this->{'model_extension_payment_' . $payment_method['code']}->cancel($subscription_info['subscription_id']);

                                    if ($subscription_status_id == $this->config->get('config_subscription_canceled_status_id')) {
                                        $subscription_info = $this->model_account_subscription->getSubscription($subscription_id);

                                        if ($subscription_info) {
                                            // Since we send an email based on subscription statuses
                                            // and not based on promotional products, only subscribed
                                            // customers can receive the emails; either by automation
                                            // or on-demand.
                                            $this->load->language('mail/subscription_alert');

                                            // HTML Mail
                                            $data['text_received'] = $this->language->get('text_received');
                                            $data['text_orders_id'] = $this->language->get('text_orders_id');
                                            $data['text_subscription_id'] = $this->language->get('text_subscription_id');
                                            $data['text_date_added'] = $this->language->get('text_date_added');
                                            $data['text_subscription_status'] = $this->language->get('text_subscription_status');
                                            $data['text_comment'] = $this->language->get('text_comment');

                                            $data['order_id'] = $order_info['order_id'];
                                            $data['subscription_id'] = $subscription_id;

                                            // Languages
                                            $this->load->model('localisation/language');

                                            $language_info = $this->model_localisation_language->getLanguageByCode($this->config->get('config_language'));

                                            // Subscription Status
                                            $subscription_status_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "subscription_status` WHERE `subscription_status_id` = '" . (int)$subscription_info['subscription_status_id'] . "' AND `language_id` = '" . (int)$language_info['language_id'] . "'");

                                            if ($subscription_status_query->num_rows) {
                                                $data['subscription_status'] = $subscription_status_query->row['name'];
                                            } else {
                                                $data['subscription_status'] = '';
                                            }

                                            if ($comment) {
                                                $data['comment'] = $comment;
                                            } else {
                                                $data['comment'] = '';
                                            }

                                            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($subscription_info['date_added']));

                                            // Cancel Status
                                            $this->model_account_subscription->editStatus($subscription_id, 0);

                                            // Mail
                                            if ($this->config->get('config_mail_engine')) {
                                                $mail = new \Opencart\System\Library\Mail($this->config->get('config_mail_engine'));
                                                $mail->parameter = $this->config->get('config_mail_parameter');
                                                $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                                                $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                                                $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                                                $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                                                $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                                                $mail->setTo($this->config->get('config_email'));
                                                $mail->setFrom($this->config->get('config_email'));
                                                $mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
                                                $mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $order_info['order_id']), ENT_QUOTES, 'UTF-8'));
                                                $mail->setText($this->load->view('mail/subscription_alert', $data));
                                                $mail->send();

                                                // Send to additional alert emails
                                                $emails = explode(',', $this->config->get('config_mail_alert_email'));

                                                foreach ($emails as $email) {
                                                    $email = trim($email);

                                                    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                        $mail->setTo($email);
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
                }
            }
        }
    }
}
