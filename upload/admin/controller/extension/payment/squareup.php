<?php

class ControllerExtensionPaymentSquareup extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('extension/payment/squareup');

        $this->load->model('extension/payment/squareup');
        $this->load->model('setting/setting');

        $this->load->library('squareup');

		$server = HTTP_SERVER;

        $previous_setting = $this->model_setting_setting->getSetting('payment_squareup');

        try {
            if ($this->config->get('payment_squareup_access_token')) {
                if (!$this->squareup->verifyToken($this->config->get('payment_squareup_access_token'))) {
                    unset($previous_setting['payment_squareup_merchant_id']);
                    unset($previous_setting['payment_squareup_merchant_name']);
                    unset($previous_setting['payment_squareup_access_token']);
                    unset($previous_setting['payment_squareup_access_token_expires']);
                    unset($previous_setting['payment_squareup_locations']);
                    unset($previous_setting['payment_squareup_sandbox_locations']);

                    $this->config->set('payment_squareup_merchant_id', null);
                } else {
                    if (!$this->config->get('payment_squareup_locations')) {
                        $previous_setting['payment_squareup_locations'] = $this->squareup->fetchLocations($this->config->get('payment_squareup_access_token'), $first_location_id);
                        $previous_setting['payment_squareup_location_id'] = $first_location_id;
                    }
                }
            }

            if (!$this->config->get('payment_squareup_sandbox_locations') && $this->config->get('payment_squareup_sandbox_token')) {
                $previous_setting['payment_squareup_sandbox_locations'] = $this->squareup->fetchLocations($this->config->get('payment_squareup_sandbox_token'), $first_location_id);
                $previous_setting['payment_squareup_sandbox_location_id'] = $first_location_id;
            }

            $this->model_setting_setting->editSetting('payment_squareup', $previous_setting);
        } catch (\Squareup\Exception $e) {
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => sprintf($this->language->get('text_location_error'), $e->getMessage())
            ));
        }

        $previous_config = new Config();

        foreach ($previous_setting as $key => $value) {
            $previous_config->set($key, $value);
        }        

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
            $this->model_setting_setting->editSetting('payment_squareup', array_merge($previous_setting, $this->request->post));

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->get['save_and_auth'])) {
                $this->response->redirect($this->squareup->authLink($this->request->post['payment_squareup_client_id']));
            } else {
                $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
            }
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data['error_status']                       = $this->getValidationError('status');
        $data['error_display_name']                 = $this->getValidationError('display_name');
        $data['error_client_id']                    = $this->getValidationError('client_id');
        $data['error_client_secret']                = $this->getValidationError('client_secret');
        $data['error_delay_capture']                = $this->getValidationError('delay_capture');
        $data['error_sandbox_client_id']            = $this->getValidationError('sandbox_client_id');
        $data['error_sandbox_token']                = $this->getValidationError('sandbox_token');
        $data['error_location']                     = $this->getValidationError('location');
        $data['error_cron_email']                   = $this->getValidationError('cron_email');
        $data['error_cron_acknowledge']             = $this->getValidationError('cron_acknowledge');

        $data['payment_squareup_status']                    = $this->getSettingValue('payment_squareup_status');
        $data['payment_squareup_status_authorized']         = $this->getSettingValue('payment_squareup_status_authorized');
        $data['payment_squareup_status_captured']           = $this->getSettingValue('payment_squareup_status_captured');
        $data['payment_squareup_status_voided']             = $this->getSettingValue('payment_squareup_status_voided');
        $data['payment_squareup_status_failed']             = $this->getSettingValue('payment_squareup_status_failed');
        $data['payment_squareup_display_name']              = $this->getSettingValue('payment_squareup_display_name');
        $data['payment_squareup_client_id']                 = $this->getSettingValue('payment_squareup_client_id');
        $data['payment_squareup_client_secret']             = $this->getSettingValue('payment_squareup_client_secret');
        $data['payment_squareup_enable_sandbox']            = $this->getSettingValue('payment_squareup_enable_sandbox');
        $data['payment_squareup_debug']                     = $this->getSettingValue('payment_squareup_debug');
        $data['payment_squareup_sort_order']                = $this->getSettingValue('payment_squareup_sort_order');
        $data['payment_squareup_total']                     = $this->getSettingValue('payment_squareup_total');
        $data['payment_squareup_geo_zone_id']               = $this->getSettingValue('payment_squareup_geo_zone_id');
        $data['payment_squareup_sandbox_client_id']         = $this->getSettingValue('payment_squareup_sandbox_client_id');
        $data['payment_squareup_sandbox_token']             = $this->getSettingValue('payment_squareup_sandbox_token');
        $data['payment_squareup_locations']                 = $this->getSettingValue('payment_squareup_locations', $previous_config->get('payment_squareup_locations'));
        $data['payment_squareup_location_id']               = $this->getSettingValue('payment_squareup_location_id');
        $data['payment_squareup_sandbox_locations']         = $this->getSettingValue('payment_squareup_sandbox_locations', $previous_config->get('payment_squareup_sandbox_locations'));
        $data['payment_squareup_sandbox_location_id']       = $this->getSettingValue('payment_squareup_sandbox_location_id');
        $data['payment_squareup_delay_capture']             = $this->getSettingValue('payment_squareup_delay_capture');
        $data['payment_squareup_recurring_status']          = $this->getSettingValue('payment_squareup_recurring_status');
        $data['payment_squareup_cron_email_status']         = $this->getSettingValue('payment_squareup_cron_email_status');
        $data['payment_squareup_cron_email']                = $this->getSettingValue('payment_squareup_cron_email', $this->config->get('config_email'));
        $data['payment_squareup_cron_token']                = $this->getSettingValue('payment_squareup_cron_token');
        $data['payment_squareup_cron_acknowledge']          = $this->getSettingValue('payment_squareup_cron_acknowledge', null, true);
        $data['payment_squareup_notify_recurring_success']  = $this->getSettingValue('payment_squareup_notify_recurring_success');
        $data['payment_squareup_notify_recurring_fail']     = $this->getSettingValue('payment_squareup_notify_recurring_fail');
        $data['payment_squareup_merchant_id']               = $this->getSettingValue('payment_squareup_merchant_id', $previous_config->get('payment_squareup_merchant_id'));
        $data['payment_squareup_merchant_name']             = $this->getSettingValue('payment_squareup_merchant_name', $previous_config->get('payment_squareup_merchant_name'));

        if ($previous_config->get('payment_squareup_access_token') && $previous_config->get('payment_squareup_access_token_expires')) {
            $expiration_time = date_create_from_format('Y-m-d\TH:i:s\Z', $previous_config->get('payment_squareup_access_token_expires'));
            $now = date_create();

            $delta = $expiration_time->getTimestamp() - $now->getTimestamp();
            $expiration_date_formatted = $expiration_time->format('l, F jS, Y h:i:s A, e');

            if ($delta < 0) {
                $this->pushAlert(array(
                    'type' => 'danger',
                    'icon' => 'exclamation-circle',
                    'text' => sprintf($this->language->get('text_token_expired'), $this->url->link('extension/payment/squareup/refresh_token', 'user_token=' . $this->session->data['user_token']))
                ));
            } else if ($delta < (5 * 24 * 60 * 60)) { // token is valid, just about to expire
                $this->pushAlert(array(
                    'type' => 'warning',
                    'icon' => 'exclamation-circle',
                    'text' => sprintf($this->language->get('text_token_expiry_warning'), $expiration_date_formatted, $this->url->link('extension/payment/squareup/refresh_token', 'user_token=' . $this->session->data['user_token']))
                ));
            }

            $data['access_token_expires_time'] = $expiration_date_formatted;
        } else if ($previous_config->get('payment_squareup_client_id')) {
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => sprintf($this->language->get('text_token_revoked'), $this->squareup->authLink($previous_config->get('payment_squareup_client_id')))
            ));

            $data['access_token_expires_time'] = $this->language->get('text_na');
        }

        if ($previous_config->get('payment_squareup_client_id')) {
            $data['payment_squareup_auth_link'] = $this->squareup->authLink($previous_config->get('payment_squareup_client_id'));
        } else {
            $data['payment_squareup_auth_link'] = null;
        }

        $data['payment_squareup_redirect_uri'] = str_replace('&amp;', '&', $this->url->link('extension/payment/squareup/oauth_callback'));
        $data['payment_squareup_refresh_link'] = $this->url->link('extension/payment/squareup/refresh_token', 'user_token=' . $this->session->data['user_token']);

        if ($this->config->get('payment_squareup_enable_sandbox')) {
            $this->pushAlert(array(
                'type' => 'warning',
                'icon' => 'exclamation-circle',
                'text' => $this->language->get('text_sandbox_enabled')
            ));
        }

        if (isset($this->error['warning'])) {
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => $this->error['warning']
            ));
        }

        // Insert success message from the session
        if (isset($this->session->data['success'])) {
            $this->pushAlert(array(
                'type' => 'success',
                'icon' => 'exclamation-circle',
                'text' => $this->session->data['success']
            ));

            unset($this->session->data['success']);
        }

        if ($this->request->server['HTTPS']) {
            // Push the SSL reminder alert
            $this->pushAlert(array(
                'type' => 'info',
                'icon' => 'lock',
                'text' => $this->language->get('text_notification_ssl')
            ));
        } else {
            // Push the SSL reminder alert
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => $this->language->get('error_no_ssl')
            ));
        }

        $tabs = array(
            'tab-transaction',
            'tab-setting',
            'tab-recurring',
            'tab-cron'
        );

        if (isset($this->request->get['tab']) && in_array($this->request->get['tab'], $tabs)) {
            $data['tab'] = $this->request->get['tab'];
        } else if (isset($this->error['cron_email']) || isset($this->error['cron_acknowledge'])) {
            $data['tab'] = 'tab-cron';
        } else if ($this->error) {
            $data['tab'] = 'tab-setting';
        } else {
            $data['tab'] = $tabs[1];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'])
        );

        $data['action'] = html_entity_decode($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
        $data['action_save_auth'] = html_entity_decode($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'] . '&save_and_auth=1'));
        $data['cancel'] = html_entity_decode($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
        $data['url_list_transactions'] = html_entity_decode($this->url->link('extension/payment/squareup/transactions', 'user_token=' . $this->session->data['user_token'] . '&page={page}'));

        $this->load->model('localisation/language');
        $data['languages'] = array();
        foreach ($this->model_localisation_language->getLanguages() as $language) {
            $data['languages'][] = array(
                'language_id' => $language['language_id'],
                'name' => $language['name'] . ($language['code'] == $this->config->get('config_language') ? $this->language->get('text_default') : ''),
                'image' => 'language/' . $language['code'] . '/'. $language['code'] . '.png'
            );
        }

        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $data['payment_squareup_cron_command'] = PHP_BINDIR . '/php -d session.save_path=' . session_save_path() . ' ' . DIR_SYSTEM . 'library/squareup/cron.php ' . parse_url($server, PHP_URL_HOST) . ' 443 > /dev/null 2> /dev/null';
        
        if (!$this->config->get('payment_squareup_cron_token')) {
            $data['payment_squareup_cron_token'] = md5(mt_rand());
        }

        $data['payment_squareup_cron_url'] = 'https://' . parse_url($server, PHP_URL_HOST) . dirname(parse_url($server, PHP_URL_PATH)) . '/index.php?route=extension/recurring/squareup/recurring&cron_token={CRON_TOKEN}';

        $data['catalog'] = HTTP_CATALOG;

        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
            $session = new Session($this->config->get('session_engine'), $this->registry);
            
            $session->start();
                    
            $this->model_user_api->deleteApiSessionBySessonId($session->getId());
            
            $this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
            
            $session->data['api_id'] = $api_info['api_id'];

            $data['api_token'] = $session->getId();
        } else {
            $data['api_token'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $data['alerts'] = $this->pullAlerts();

        $this->clearAlerts();

        $this->response->setOutput($this->load->view('extension/payment/squareup', $data));
    }

    public function transaction_info() {
        $this->load->language('extension/payment/squareup');

        $this->load->model('extension/payment/squareup');

        $this->load->library('squareup');

        if (isset($this->request->get['squareup_transaction_id'])) {
            $squareup_transaction_id = $this->request->get['squareup_transaction_id'];
        } else {
            $squareup_transaction_id = 0;
        }

        $transaction_info = $this->model_extension_payment_squareup->getTransaction($squareup_transaction_id);

        if (empty($transaction_info)) {
            $this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
        }

        $this->document->setTitle(sprintf($this->language->get('heading_title_transaction'), $transaction_info['transaction_id']));

        $data['alerts'] = $this->pullAlerts();

        $this->clearAlerts();

        $data['text_edit'] = sprintf($this->language->get('heading_title_transaction'), $transaction_info['transaction_id']);

        $amount = $this->currency->format($transaction_info['transaction_amount'], $transaction_info['transaction_currency']);

        $data['confirm_capture'] = sprintf($this->language->get('text_confirm_capture'), $amount);
        $data['confirm_void'] = sprintf($this->language->get('text_confirm_void'), $amount);
        $data['confirm_refund'] = $this->language->get('text_confirm_refund');
        $data['insert_amount'] = sprintf($this->language->get('text_insert_amount'), $amount, $transaction_info['transaction_currency']);
        $data['text_loading'] = $this->language->get('text_loading_short');
        
        $data['billing_address_company'] = $transaction_info['billing_address_company'];
        $data['billing_address_street'] = $transaction_info['billing_address_street_1'] . ' ' . $transaction_info['billing_address_street_2'];
        $data['billing_address_city'] = $transaction_info['billing_address_city'];
        $data['billing_address_postcode'] = $transaction_info['billing_address_postcode'];
        $data['billing_address_province'] = $transaction_info['billing_address_province'];
        $data['billing_address_country'] = $transaction_info['billing_address_country'];

        $data['transaction_id'] = $transaction_info['transaction_id'];
        $data['merchant'] = $transaction_info['merchant_id'];
        $data['order_id'] = $transaction_info['order_id'];
        $data['type'] = $transaction_info['transaction_type'];
        $data['amount'] = $amount;
        $data['currency'] = $transaction_info['transaction_currency'];
        $data['browser'] = $transaction_info['device_browser'];
        $data['ip'] = $transaction_info['device_ip'];
        $data['date_created'] = date($this->language->get('datetime_format'), strtotime($transaction_info['created_at']));
        
        $data['cancel'] = $this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'] . '&tab=tab-transaction');

        $data['url_order'] = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $transaction_info['order_id']);
        $data['url_void'] = $this->url->link('extension/payment/squareup' . '/void', 'user_token=' . $this->session->data['user_token'] . '&preserve_alert=true&squareup_transaction_id=' . $transaction_info['squareup_transaction_id']);
        $data['url_capture'] = $this->url->link('extension/payment/squareup' . '/capture', 'user_token=' . $this->session->data['user_token'] . '&preserve_alert=true&squareup_transaction_id=' . $transaction_info['squareup_transaction_id']);
        $data['url_refund'] = $this->url->link('extension/payment/squareup' . '/refund', 'user_token=' . $this->session->data['user_token'] . '&preserve_alert=true&squareup_transaction_id=' . $transaction_info['squareup_transaction_id']);
        $data['url_transaction'] = sprintf(
            Squareup::VIEW_TRANSACTION_URL,
            $transaction_info['transaction_id'],
            $transaction_info['location_id']
        );

        $data['is_authorized'] = in_array($transaction_info['transaction_type'], array('AUTHORIZED'));
        $data['is_captured'] = in_array($transaction_info['transaction_type'], array('CAPTURED'));

        $data['has_refunds'] = (bool)$transaction_info['is_refunded'];

        if ($data['has_refunds']) {
            $refunds = @json_decode($transaction_info['refunds'], true);

            $data['refunds'] = array();

            $data['text_refunds'] = sprintf($this->language->get('text_refunds'), count($refunds));

            foreach ($refunds as $refund) {
                $amount = $this->currency->format(
                    $this->squareup->standardDenomination(
                        $refund['amount_money']['amount'], 
                        $refund['amount_money']['currency']
                    ), 
                    $refund['amount_money']['currency']
                );

                $fee = $this->currency->format(
                    $this->squareup->standardDenomination(
                        $refund['processing_fee_money']['amount'], 
                        $refund['processing_fee_money']['currency']
                    ), 
                    $refund['processing_fee_money']['currency']
                );

                $data['refunds'][] = array(
                    'date_created' => date($this->language->get('datetime_format'), strtotime($refund['created_at'])),
                    'reason' => $refund['reason'],
                    'status' => $refund['status'],
                    'amount' => $amount,
                    'fee' => $fee
                );
            }
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => sprintf($this->language->get('heading_title_transaction'), $transaction_info['squareup_transaction_id']),
            'href' => $this->url->link('extension/payment/squareup/transaction_info', 'user_token=' . $this->session->data['user_token'] . '&squareup_transaction_id=' . $squareup_transaction_id)
        );

        $data['catalog'] = HTTP_CATALOG;

        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
            $session = new Session($this->config->get('session_engine'), $this->registry);
            
            $session->start();
                    
            $this->model_user_api->deleteApiSessionBySessonId($session->getId());
            
            $this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
            
            $session->data['api_id'] = $api_info['api_id'];

            $data['api_token'] = $session->getId();
        } else {
            $data['api_token'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/payment/squareup_transaction_info', $data));
    }

    public function transactions() {
        $this->load->language('extension/payment/squareup');

        $this->load->model('extension/payment/squareup');

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        $result = array(
            'transactions' => array(),
            'pagination' => ''
        );

        $filter_data = array(
            'start' => ($page - 1) * (int)$this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        if (isset($this->request->get['order_id'])) {
            $filter_data['order_id'] = $this->request->get['order_id'];
        }

        $transactions_total = $this->model_extension_payment_squareup->getTotalTransactions($filter_data);
        $transactions = $this->model_extension_payment_squareup->getTransactions($filter_data);

        $this->load->model('sale/order');

        foreach ($transactions as $transaction) {
            $amount = $this->currency->format($transaction['transaction_amount'], $transaction['transaction_currency']);

            $order_info = $this->model_sale_order->getOrder($transaction['order_id']);
            
            $result['transactions'][] = array(
                'squareup_transaction_id' => $transaction['squareup_transaction_id'],
                'transaction_id' => $transaction['transaction_id'],
                'url_order' => $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $transaction['order_id']),
                'url_void' => $this->url->link('extension/payment/squareup/void', 'user_token=' . $this->session->data['user_token'] . '&squareup_transaction_id=' . $transaction['squareup_transaction_id']),
                'url_capture' => $this->url->link('extension/payment/squareup/capture', 'user_token=' . $this->session->data['user_token'] . '&squareup_transaction_id=' . $transaction['squareup_transaction_id']),
                'url_refund' => $this->url->link('extension/payment/squareup/refund', 'user_token=' . $this->session->data['user_token'] . '&squareup_transaction_id=' . $transaction['squareup_transaction_id']),
                'confirm_capture' => sprintf($this->language->get('text_confirm_capture'), $amount),
                'confirm_void' => sprintf($this->language->get('text_confirm_void'), $amount),
                'confirm_refund' => $this->language->get('text_confirm_refund'),
                'insert_amount' => sprintf($this->language->get('text_insert_amount'), $amount, $transaction['transaction_currency']),
                'order_id' => $transaction['order_id'],
                'type' => $transaction['transaction_type'],
                'num_refunds' => count(@json_decode($transaction['refunds'], true)),
                'amount' => $amount,
                'customer' => $order_info['firstname'] . ' ' . $order_info['lastname'],
                'ip' => $transaction['device_ip'],
                'date_created' => date($this->language->get('datetime_format'), strtotime($transaction['created_at'])),
                'url_info' => $this->url->link('extension/payment/squareup/transaction_info', 'user_token=' . $this->session->data['user_token'] . '&squareup_transaction_id=' . $transaction['squareup_transaction_id'])
            );
        }

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $transactions_total,
			'page'  => $page,
			'limit' => $this->config->get('config_limit_admin'),
			'url'   => '{page}'
		));

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($result));
    }

    public function refresh_token() {
        $this->load->language('extension/payment/squareup');

        if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => $this->language->get('error_permission')
            ));

            $this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
        }

        $this->load->model('setting/setting');

        $this->load->library('squareup');

        try {
            $response = $this->squareup->refreshToken();

            if (!isset($response['access_token']) || !isset($response['token_type']) || !isset($response['expires_at']) || !isset($response['merchant_id']) ||
                $response['merchant_id'] != $this->config->get('payment_squareup_merchant_id')) {
                $this->pushAlert(array(
                    'type' => 'danger',
                    'icon' => 'exclamation-circle',
                    'text' => $this->language->get('error_refresh_access_token') 
                ));
            } else {
                $settings = $this->model_setting_setting->getSetting('payment_squareup');

                $settings['payment_squareup_access_token'] = $response['access_token']; 
                $settings['payment_squareup_access_token_expires'] = $response['expires_at'];

                $this->model_setting_setting->editSetting('payment_squareup', $settings); 

                $this->pushAlert(array(
                    'type' => 'success',
                    'icon' => 'exclamation-circle',
                    'text' => $this->language->get('text_refresh_access_token_success')
                ));
            }
        } catch (\Squareup\Exception $e) {
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => sprintf($this->language->get('error_token'), $e->getMessage())
            ));
        }

        $this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
    }

    public function oauth_callback() {
        $this->load->language('extension/payment/squareup');

        if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => $this->language->get('error_permission')
            ));

            $this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
        }

        $this->load->model('setting/setting');

        $this->load->library('squareup');

        if (isset($this->request->get['error']) || isset($this->request->get['error_description'])) {
            // auth error
            if ($this->request->get['error'] == 'access_denied' && $this->request->get['error_description'] == 'user_denied') {
                // user rejected giving auth permissions to his store
                $this->pushAlert(array(
                    'type' => 'warning',
                    'icon' => 'exclamation-circle',
                    'text' => $this->language->get('error_user_rejected_connect_attempt')
                ));
            }

            $this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
        }

        // verify parameters for the redirect from Square (against random url crawling)
        if (!isset($this->request->get['state']) || !isset($this->request->get['code']) || !isset($this->request->get['response_type'])) {
            // missing or wrong info
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => $this->language->get('error_possible_xss')
            ));

            $this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
        }

        // verify the state (against cross site requests)
        if (!isset($this->session->data['payment_squareup_oauth_state']) || $this->session->data['payment_squareup_oauth_state'] != $this->request->get['state']) {
            // state mismatch
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => $this->language->get('error_possible_xss')
            ));

            $this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
        }

        try {
            $token = $this->squareup->exchangeCodeForAccessToken($this->request->get['code']);
            
            $previous_setting = $this->model_setting_setting->getSetting('payment_squareup');

            $previous_setting['payment_squareup_locations'] = $this->squareup->fetchLocations($token['access_token'], $first_location_id);

            if (
                !isset($previous_setting['payment_squareup_location_id']) || 
                (isset($previous_setting['payment_squareup_location_id']) && !in_array(
                    $previous_setting['payment_squareup_location_id'], 
                    array_map(
                        function($location) {
                            return $location['id'];
                        },
                        $previous_setting['payment_squareup_locations']
                    )
                ))
            ) {
                $previous_setting['payment_squareup_location_id'] = $first_location_id;
            }

            if (!$this->config->get('payment_squareup_sandbox_locations') && $this->config->get('payment_squareup_sandbox_token')) {
                $previous_setting['payment_squareup_sandbox_locations'] = $this->squareup->fetchLocations($this->config->get('payment_squareup_sandbox_token'), $first_location_id);
                $previous_setting['payment_squareup_sandbox_location_id'] = $first_location_id;
            }

            $previous_setting['payment_squareup_merchant_id'] = $token['merchant_id'];
            $previous_setting['payment_squareup_merchant_name'] = ''; // only available in v1 of the API, not populated for now
            $previous_setting['payment_squareup_access_token'] = $token['access_token'];
            $previous_setting['payment_squareup_access_token_expires'] = $token['expires_at'];

            $this->model_setting_setting->editSetting('payment_squareup', $previous_setting);

            unset($this->session->data['payment_squareup_oauth_state']);
            unset($this->session->data['payment_squareup_oauth_redirect']);

            $this->pushAlert(array(
                'type' => 'success',
                'icon' => 'exclamation-circle',
                'text' => $this->language->get('text_refresh_access_token_success')
            ));
        } catch (\Squareup\Exception $e) {
            $this->pushAlert(array(
                'type' => 'danger',
                'icon' => 'exclamation-circle',
                'text' => sprintf($this->language->get('error_token'), $e->getMessage())
            ));
        }

        $this->response->redirect($this->url->link('extension/payment/squareup', 'user_token=' . $this->session->data['user_token']));
    }

    public function capture() {
        $this->transactionAction(function($transaction_info, &$json) {
            $updated_transaction = $this->squareup->captureTransaction($transaction_info['location_id'], $transaction_info['transaction_id']);

            $status = $updated_transaction['tenders'][0]['card_details']['status'];

            $this->model_extension_payment_squareup->updateTransaction($transaction_info['squareup_transaction_id'], $status);

            $json['order_history_data'] = array(
                'notify' => 1,
                'order_id' => $transaction_info['order_id'],
                'order_status_id' => $this->model_extension_payment_squareup->getOrderStatusId($transaction_info['order_id'], $status),
                'comment' => $this->language->get('squareup_status_comment_' . strtolower($status)),
            );

            $json['success'] = $this->language->get('text_success_capture');
        });
    }

    public function void() {
        $this->transactionAction(function($transaction_info, &$json) {
            $updated_transaction = $this->squareup->voidTransaction($transaction_info['location_id'], $transaction_info['transaction_id']);

            $status = $updated_transaction['tenders'][0]['card_details']['status'];

            $this->model_extension_payment_squareup->updateTransaction($transaction_info['squareup_transaction_id'], $status);

            $json['order_history_data'] = array(
                'notify' => 1,
                'order_id' => $transaction_info['order_id'],
                'order_status_id' => $this->model_extension_payment_squareup->getOrderStatusId($transaction_info['order_id'], $status),
                'comment' => $this->language->get('squareup_status_comment_' . strtolower($status)),
            );

            $json['success'] = $this->language->get('text_success_void');
        });
    }

    public function refund() {
        $this->transactionAction(function($transaction_info, &$json) {
            if (!empty($this->request->post['reason'])) {
                $reason = $this->request->post['reason'];
            } else {
                $reason = $this->language->get('text_no_reason_provided');
            }

            if (!empty($this->request->post['amount'])) {
                $amount = preg_replace('~[^0-9\.\,]~', '', $this->request->post['amount']);

                if (strpos($amount, ',') !== FALSE && strpos($amount, '.') !== FALSE) {
                    $amount = (float)str_replace(',', '', $amount);
                } else if (strpos($amount, ',') !== FALSE && strpos($amount, '.') === FALSE) {
                    $amount = (float)str_replace(',', '.', $amount);
                } else {
                    $amount = (float)$amount;
                }
            } else {
                $amount = 0;
            }

            $currency = $transaction_info['transaction_currency'];
            $tenders = @json_decode($transaction_info['tenders'], true);

            $updated_transaction = $this->squareup->refundTransaction($transaction_info['location_id'], $transaction_info['transaction_id'], $reason, $amount, $currency, $tenders[0]['id']);

            $status = $updated_transaction['tenders'][0]['card_details']['status'];

            $refunds = array();

            if (!empty($updated_transaction['refunds'])) {
                $refunds = $updated_transaction['refunds'];
            }

            $this->model_extension_payment_squareup->updateTransaction($transaction_info['squareup_transaction_id'], $status, $refunds);

            $last_refund = array_pop($refunds);

            if ($last_refund) {
                $refunded_amount = $this->currency->format(
                    $this->squareup->standardDenomination(
                        $last_refund['amount_money']['amount'], 
                        $last_refund['amount_money']['currency']
                    ), 
                    $last_refund['amount_money']['currency']
                );

                $comment = sprintf($this->language->get('text_refunded_amount'), $refunded_amount, $last_refund['status'], $last_refund['reason']);

                $json['order_history_data'] = array(
                    'notify' => 1,
                    'order_id' => $transaction_info['order_id'],
                    'order_status_id' => $this->model_extension_payment_squareup->getOrderStatusId($transaction_info['order_id']),
                    'comment' => $comment,
                );

                $json['success'] = $this->language->get('text_success_refund');
            } else {
                $json['error'] = $this->language->get('error_no_refund');
            }
        });
    }

    public function order() {
        $this->load->language('extension/payment/squareup');

        $data['url_list_transactions'] = html_entity_decode($this->url->link('extension/payment/squareup/transactions', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'] . '&page={PAGE}'));
        $data['user_token'] = $this->session->data['user_token'];
        $data['order_id'] = (int)$this->request->get['order_id'];

        $data['catalog'] = HTTP_CATALOG;

        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
            $session = new Session($this->config->get('session_engine'), $this->registry);
            
            $session->start();
                    
            $this->model_user_api->deleteApiSessionBySessonId($session->getId());
            
            $this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
            
            $session->data['api_id'] = $api_info['api_id'];

            $data['api_token'] = $session->getId();
        } else {
            $data['api_token'] = '';
        }

        return $this->load->view('extension/payment/squareup_order', $data);
    }

    public function install() {
        $this->load->model('extension/payment/squareup');
        
        $this->model_extension_payment_squareup->createTables();
    }

    public function uninstall() {
        $this->load->model('extension/payment/squareup');

        $this->model_extension_payment_squareup->dropTables();
    }

    public function recurringButtons() {
        if (!$this->user->hasPermission('modify', 'sale/recurring')) {
            return;
        }

        $this->load->model('extension/payment/squareup');

        $this->load->language('extension/payment/squareup');

        if (isset($this->request->get['order_recurring_id'])) {
            $order_recurring_id = $this->request->get['order_recurring_id'];
        } else {
            $order_recurring_id = 0;
        }

        $recurring_info = $this->model_sale_recurring->getRecurring($order_recurring_id);

        $data['button_text'] = $this->language->get('button_cancel_recurring');

        if ($recurring_info['status'] == ModelExtensionPaymentSquareup::RECURRING_ACTIVE) {
            $data['order_recurring_id'] = $order_recurring_id;
        } else {
            $data['order_recurring_id'] = '';
        }

        $this->load->model('sale/order');

        $order_info = $this->model_sale_order->getOrder($recurring_info['order_id']);

        $data['order_id'] = $recurring_info['order_id'];
        $data['store_id'] = $order_info['store_id'];
        $data['order_status_id'] = $order_info['order_status_id'];
        $data['comment'] = $this->language->get('text_order_history_cancel');
        $data['notify'] = 1;

        $data['catalog'] = HTTP_CATALOG;

        // API login
        $this->load->model('user/api');

        $api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

        if ($api_info && $this->user->hasPermission('modify', 'sale/order')) {
            $session = new Session($this->config->get('session_engine'), $this->registry);
            
            $session->start();
                    
            $this->model_user_api->deleteApiSessionBySessonId($session->getId());
            
            $this->model_user_api->addApiSession($api_info['api_id'], $session->getId(), $this->request->server['REMOTE_ADDR']);
            
            $session->data['api_id'] = $api_info['api_id'];

            $data['api_token'] = $session->getId();
        } else {
            $data['api_token'] = '';
        }

        $data['cancel'] = html_entity_decode($this->url->link('extension/payment/squareup/recurringCancel', 'order_recurring_id=' . $order_recurring_id . '&user_token=' . $this->session->data['user_token']));

        return $this->load->view('extension/payment/squareup_recurring_buttons', $data);
    }

    public function recurringCancel() {
        $this->load->language('extension/payment/squareup');

        $json = array();
        
        if (!$this->user->hasPermission('modify', 'sale/recurring')) {
            $json['error'] = $this->language->get('error_permission_recurring');
        } else {
            $this->load->model('sale/recurring');
            
            if (isset($this->request->get['order_recurring_id'])) {
                $order_recurring_id = $this->request->get['order_recurring_id'];
            } else {
                $order_recurring_id = 0;
            }
            
            $recurring_info = $this->model_sale_recurring->getRecurring($order_recurring_id);

            if ($recurring_info) {
                $this->load->model('extension/payment/squareup');

                $this->model_extension_payment_squareup->editOrderRecurringStatus($order_recurring_id, ModelExtensionPaymentSquareup::RECURRING_CANCELLED);

                $json['success'] = $this->language->get('text_canceled_success');
                
            } else {
                $json['error'] = $this->language->get('error_not_found');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['payment_squareup_client_id']) || strlen($this->request->post['payment_squareup_client_id']) > 32) {
            $this->error['client_id'] = $this->language->get('error_client_id');
        }

        if (empty($this->request->post['payment_squareup_client_secret']) || strlen($this->request->post['payment_squareup_client_secret']) > 50) {
            $this->error['client_secret'] = $this->language->get('error_client_secret');
        }

        if (!empty($this->request->post['payment_squareup_enable_sandbox'])) {
            if (empty($this->request->post['payment_squareup_sandbox_client_id']) || strlen($this->request->post['payment_squareup_sandbox_client_id']) > 42) {
                $this->error['sandbox_client_id'] = $this->language->get('error_sandbox_client_id');
            }

            if (empty($this->request->post['payment_squareup_sandbox_token']) || strlen($this->request->post['payment_squareup_sandbox_token']) > 42) {
                $this->error['sandbox_token'] = $this->language->get('error_sandbox_token');
            }

            if ($this->config->get('payment_squareup_merchant_id') && !$this->config->get('payment_squareup_sandbox_locations')) {
                $this->error['warning'] = $this->language->get('text_no_appropriate_locations_warning');
            }

            if ($this->config->get('payment_squareup_sandbox_locations') && isset($this->request->post['payment_squareup_sandbox_location_id']) && !in_array($this->request->post['payment_squareup_sandbox_location_id'], array_map(function($location) {
                return $location['id'];
            }, $this->config->get('payment_squareup_sandbox_locations')))) {
                $this->error['location'] = $this->language->get('error_no_location_selected');
            }
        } else {
            if ($this->config->get('payment_squareup_merchant_id') && !$this->config->get('payment_squareup_locations')) {
                $this->error['warning'] = $this->language->get('text_no_appropriate_locations_warning');
            }

            if ($this->config->get('payment_squareup_locations') && isset($this->request->post['payment_squareup_location_id']) && !in_array($this->request->post['payment_squareup_location_id'], array_map(function($location) {
                return $location['id'];
            }, $this->config->get('payment_squareup_locations')))) {
                $this->error['location'] = $this->language->get('error_no_location_selected');
            }
        }

        if (!empty($this->request->post['payment_squareup_cron_email_status'])) {
            if (!filter_var($this->request->post['payment_squareup_cron_email'], FILTER_VALIDATE_EMAIL)) {
                $this->error['cron_email'] = $this->language->get('error_invalid_email');
            }
        }

        if (!isset($this->request->get['save_and_auth']) && empty($this->request->post['payment_squareup_cron_acknowledge'])) {
            $this->error['cron_acknowledge'] = $this->language->get('error_cron_acknowledge');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }

    protected function transactionAction($callback) {
        $this->load->language('extension/payment/squareup');

        $this->load->model('extension/payment/squareup');

        $this->load->library('squareup');

        $json = array();

        if (!$this->user->hasPermission('modify', 'extension/payment/squareup')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (isset($this->request->get['squareup_transaction_id'])) {
            $squareup_transaction_id = $this->request->get['squareup_transaction_id'];
        } else {
            $squareup_transaction_id = 0;
        }

        $transaction_info = $this->model_extension_payment_squareup->getTransaction($squareup_transaction_id);

        if (empty($transaction_info)) {
            $json['error'] = $this->language->get('error_transaction_missing');
        } else {
            try {
                $callback($transaction_info, $json);
            } catch (\Squareup\Exception $e) {
                $json['error'] = $e->getMessage();
            }
        }

        if (isset($this->request->get['preserve_alert'])) {
            if (!empty($json['error'])) {
                $this->pushAlert(array(
                    'type' => 'danger',
                    'icon' => 'exclamation-circle',
                    'text' => $json['error']
                ));
            }

            if (!empty($json['success'])) {
                $this->pushAlert(array(
                    'type' => 'success',
                    'icon' => 'exclamation-circle',
                    'text' => $json['success']
                ));
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function pushAlert($alert) {
        $this->session->data['payment_squareup_alerts'][] = $alert;
    }

    protected function pullAlerts() {
        if (isset($this->session->data['payment_squareup_alerts'])) {
            return $this->session->data['payment_squareup_alerts'];
        } else {
            return array();
        }
    }

    protected function clearAlerts() {
        unset($this->session->data['payment_squareup_alerts']);
    }

    protected function getSettingValue($key, $default = null, $checkbox = false) {
        if ($checkbox) {
            if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post[$key])) {
                return $default;
            } else {
                return $this->config->get($key);
            }
        }

        if (isset($this->request->post[$key])) {
            return $this->request->post[$key]; 
        } else if ($this->config->has($key)) {
            return $this->config->get($key);
        } else {
            return $default;
        }
    }

    protected function getValidationError($key) {
        if (isset($this->error[$key])) {
            return $this->error[$key];
        } else {
            return '';
        }
    }
}