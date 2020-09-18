<?php

use \googleshopping\exception\Connection as ConnectionException;
use \googleshopping\Googleshopping;
use \googleshopping\traits\LibraryLoader;
use \googleshopping\traits\StoreLoader;

class ControllerExtensionAdvertiseGoogle extends Controller {
    use StoreLoader;
    use LibraryLoader;

    private $error = array();
    private $store_id = 0;

    public function __construct($registry) {
        parent::__construct($registry);

        $this->store_id = isset($this->request->get['store_id']) ? (int)$this->request->get['store_id'] : 0;

        $this->loadStore($this->store_id);

        $this->loadLibrary($this->store_id);
    }

    public function index() {
        $this->load->language('extension/advertise/google');

        $this->load->model('extension/advertise/google');

        $this->load->config('googleshopping/googleshopping');

        // Fix clashes with third-party extension table names
        $this->model_extension_advertise_google->renameTables();

        // Even though this should be ran during install, there are known cases of webstores which do not trigger the install method. This is why we run createTables here explicitly.
        $this->model_extension_advertise_google->createTables();

        // Fix a missing AUTO_INCREMENT
        $this->model_extension_advertise_google->fixColumns();
        
        // Redirect to the preliminary check-list
        if (!$this->setting->get('advertise_google_checklist_confirmed')) {
            $this->response->redirect($this->url->link('extension/advertise/google/checklist', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
        }

        try {
            // If we have not connected, navigate to connect screen
            if (!$this->setting->has('advertise_google_access_token')) {
                $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } else if (!$this->setting->has('advertise_google_gmc_account_selected')) {
                // In case the merchant has made no decision about which GMC account to use, redirect to the form for connection
                $this->response->redirect($this->url->link('extension/advertise/google/merchant', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } else if (!$this->googleshopping->isStoreUrlClaimed()) {
                if (empty($this->session->data['error'])) {
                    $this->session->data['error'] = $this->language->get('error_store_url_claim');
                }

                // In case the merchant has made no decision about which GMC account to use, redirect to the form for connection
                $this->response->redirect($this->url->link('extension/advertise/google/merchant', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } else if (count($this->googleshopping->getTargets($this->store_id)) == 0) {
                $this->response->redirect($this->url->link('extension/advertise/google/campaign', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } else if (!$this->setting->has('advertise_google_gmc_shipping_taxes_configured')) {
                // In case the merchant has not set up shipping and taxes, redirect them to the form for shipping and taxes
                $this->response->redirect($this->url->link('extension/advertise/google/shipping_taxes', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } else if (count($this->model_extension_advertise_google->getMapping($this->store_id)) == 0) {
                // In case the merchant has not set up mapping, redirect them to the form for mapping
                $this->response->redirect($this->url->link('extension/advertise/google/mapping', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            }

            // Pull the campaign reports
            $this->googleshopping->getCampaignReports();
        } catch (ConnectionException $e) {
            $this->session->data['error'] = $e->getMessage();

            $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
        } catch (\RuntimeException $e) {
            $this->error['warning'] = $e->getMessage();
        }

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateSettings()) {
            $this->applyNewSettings($this->request->post);

            try {
                // Profilactic target push, as sometimes targets are not initialized properly
                $this->googleshopping->pushTargets();
                $this->googleshopping->pushCampaignStatus();

                $this->session->data['success'] = $this->language->get('success_index');

                $this->response->redirect($this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (ConnectionException $e) {
                $this->session->data['error'] = $e->getMessage();

                $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (\RuntimeException $e) {
                $this->error['warning'] = $e->getMessage();
            }
        }

        $this->document->setTitle($this->language->get('heading_title'));

        $data = array();

        $data['text_connected'] = sprintf($this->language->get('text_connected'), $this->setting->get('advertise_google_gmc_account_id'));

        $data['error'] = '';

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else if (!empty($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        $data['error_cron_email']                   = $this->getValidationError('cron_email');
        $data['error_cron_acknowledge']             = $this->getValidationError('cron_acknowledge');

        $data['success'] = '';

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $advertised_count = $this->model_extension_advertise_google->getAdvertisedCount($this->store_id);
        $last_cron_executed = (int)$this->setting->get('advertise_google_cron_last_executed');

        $data['warning'] = '';

        if (!$this->setting->get('advertise_google_status') && $this->model_extension_advertise_google->hasActiveTarget($this->store_id)) {
            $data['warning'] = $this->language->get('warning_disabled');
        } else if (!$this->model_extension_advertise_google->hasActiveTarget($this->store_id)) {
            $data['warning'] = sprintf($this->language->get('warning_no_active_campaigns'), $this->url->link('extension/advertise/google/campaign', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&from_dashboard=true', true));
        } else if ($advertised_count == 0) {
            $data['warning'] = sprintf($this->language->get("warning_no_advertised_products"), $this->language->get("text_video_tutorial_url_advertise"));
        } else if ($last_cron_executed + 24 * 60 * 60 <= time()) {
            $data['warning'] = sprintf($this->language->get("warning_last_cron_executed"), $this->language->get("text_tutorial_cron"));
        }

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extensions'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true),
        );

        $reporting_intervals = $this->config->get('advertise_google_reporting_intervals');

        $data['user_token'] = $this->session->data['user_token'];

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true);
        $data['action'] = $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);
        $data['shipping_taxes'] = $this->url->link('extension/advertise/google/shipping_taxes', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&from_dashboard=true', true);
        $data['campaign'] = $this->url->link('extension/advertise/google/campaign', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&from_dashboard=true', true);
        $data['mapping'] = $this->url->link('extension/advertise/google/mapping', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&from_dashboard=true', true);
        $data['disconnect'] = $this->url->link('extension/advertise/google/disconnect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);
        $data['list_ads'] = html_entity_decode($this->url->link('extension/advertise/google/list_ads', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
        $data['advertise'] = html_entity_decode($this->url->link('extension/advertise/google/advertise', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
        $data['url_popup'] = html_entity_decode($this->url->link('extension/advertise/google/popup_product', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
        $data['url_category_autocomplete'] = html_entity_decode($this->url->link('extension/advertise/google/category_autocomplete', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
        $data['url_debug_log_download'] = html_entity_decode($this->url->link('extension/advertise/google/debug_log_download', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');

        $data['advertise_google_status']                    = $this->getSettingValue('advertise_google_status', 0);
        $data['advertise_google_debug_log']                 = $this->getSettingValue('advertise_google_debug_log', 0);
        $data['advertise_google_cron_email_status']         = $this->getSettingValue('advertise_google_cron_email_status');
        $data['advertise_google_cron_email']                = $this->getSettingValue('advertise_google_cron_email', $this->config->get('config_email'));
        $data['advertise_google_cron_token']                = $this->getSettingValue('advertise_google_cron_token');
        $data['advertise_google_cron_acknowledge']          = $this->getSettingValue('advertise_google_cron_acknowledge', null, true);

        if (isset($this->request->post['advertise_google_reporting_interval'])) {
            $data['advertise_google_reporting_interval'] = $this->request->post['advertise_google_reporting_interval'];
        } else if ($this->setting->has('advertise_google_reporting_interval') && in_array($this->setting->get('advertise_google_reporting_interval'), $reporting_intervals)) {
            $data['advertise_google_reporting_interval'] = $this->setting->get('advertise_google_reporting_interval');
        } else {
            $data['advertise_google_reporting_interval'] = $this->config->get('advertise_google_reporting_intervals_default');
        }

        $server = $this->googleshopping->getStoreUrl();

        $data['advertise_google_cron_command'] = 'export CUSTOM_SERVER_NAME=' . parse_url($server, PHP_URL_HOST) . '; export CUSTOM_SERVER_PORT=443; export ADVERTISE_GOOGLE_CRON=1; export ADVERTISE_GOOGLE_STORE_ID=' . $this->store_id . '; ' . PHP_BINDIR . '/php -d session.save_path=' . session_save_path() . ' -d memory_limit=256M ' . DIR_SYSTEM . 'library/googleshopping/cron.php > /dev/null 2> /dev/null';
        
        if (!$this->setting->get('advertise_google_cron_token')) {
            $data['advertise_google_cron_token'] = md5(mt_rand());
        }

        $host_and_uri = parse_url($server, PHP_URL_HOST) . parse_url($server, PHP_URL_PATH);

        $data['advertise_google_cron_url'] = 'https://' . rtrim($host_and_uri, '/') . '/index.php?route=extension/advertise/google/cron&cron_token={CRON_TOKEN}';

        $data['reporting_intervals'] = array();

        foreach ($reporting_intervals as $interval) {
            $data['reporting_intervals'][$interval] = $this->language->get('text_reporting_interval_' . $interval);
        }

        $campaign_reports = $this->setting->get('advertise_google_report_campaigns');

        $data['campaigns'] = $this->googleshopping->getTargets($this->store_id);

        $data['text_report_date_range'] = sprintf($this->language->get('text_report_date_range'), $campaign_reports['date_range']);
        $data['text_ads_intro'] = sprintf($this->language->get('text_ads_intro'), $data['shipping_taxes']);
        $data['advertise_google_report_campaigns'] = $campaign_reports['reports'];
        $data['text_panel_heading'] = sprintf($this->language->get('text_panel_heading'), $this->googleshopping->getStoreName());

        $data['text_selection_all'] = str_replace("'", "\\'", $this->language->get('text_selection_all'));
        $data['text_selection_page'] = str_replace("'", "\\'", $this->language->get('text_selection_page'));

        $data['tab_settings'] = $this->load->view('extension/advertise/google_settings', $data);
        $data['tab_ads']      = $this->load->view('extension/advertise/google_ads', $data);
        $data['tab_reports']  = $this->load->view('extension/advertise/google_reports', $data);

        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/advertise/google', $data));
    }

    public function debug_log_download() {
        $filename = sprintf(Googleshopping::DEBUG_LOG_FILENAME, $this->store_id);

        header('Pragma: no-cache');
        header('Expires: 0');
        header('Content-Description: File Transfer');
        header('Content-Type: plain/text');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');

        $file = DIR_LOGS . $filename;

        if (file_exists($file)) {
            readfile($file);
        }

        exit;
    }

    public function advertise() {
        $this->load->language('extension/advertise/google');
        
        $json = array(
            'success' => null,
            'redirect' => null,
            'error' => null,
            'warning' => null
        );

        if ($this->validatePermission()) {
            $this->load->model('extension/advertise/google');

            $select = array();
            $filter_data = array();

            if (!empty($this->request->post['all_pages'])) {
                $filter_data = $this->getFilter($this->request->post['filter']);
            } else if (isset($this->request->post['select']) && is_array($this->request->post['select'])) {
                $select = $this->request->post['select'];
            }

            if (!empty($select) || !empty($filter_data)) {
                $target_ids = !empty($this->request->post['target_ids']) ? $this->request->post['target_ids'] : array();

                if (!empty($select)) {
                    $this->model_extension_advertise_google->setAdvertisingBySelect($select, $target_ids, $this->store_id);
                } else if (!empty($filter_data)) {
                    $this->model_extension_advertise_google->setAdvertisingByFilter($filter_data, $target_ids, $this->store_id);
                }

                if (!empty($target_ids)) {
                    $json['success'] = $this->language->get('success_advertise_listed');
                } else {
                    $json['success'] = $this->language->get('success_advertise_unlisted');
                }
            }
        } else {
            $json['error'] = $this->error['warning'];
        }

        // Refresh warnings
        $advertised_count = $this->model_extension_advertise_google->getAdvertisedCount($this->store_id);
        $last_cron_executed = (int)$this->setting->get('advertise_google_cron_last_executed');

        if (!$this->setting->get('advertise_google_status') && $this->model_extension_advertise_google->hasActiveTarget($this->store_id)) {
            $json['warning'] = $this->language->get('warning_disabled');
        } else if (!$this->model_extension_advertise_google->hasActiveTarget($this->store_id)) {
            $json['warning'] = sprintf($this->language->get('warning_no_active_campaigns'), $this->url->link('extension/advertise/google/campaign', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&from_dashboard=true', true));
        } else if ($advertised_count == 0) {
            $json['warning'] = sprintf($this->language->get("warning_no_advertised_products"), $this->language->get("text_video_tutorial_url_advertise"));
        } else if ($last_cron_executed + 24 * 60 * 60 <= time()) {
            $json['warning'] = sprintf($this->language->get("warning_last_cron_executed"), $this->language->get("text_tutorial_cron"));
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function list_ads() {
        $json = array();

        $this->load->model('extension/advertise/google');

        $this->model_extension_advertise_google->insertNewProducts(array(), $this->store_id);

        $this->load->language('extension/advertise/google');

        $page = (int)$this->request->post['page'];

        $filter_data = array(
            'sort' => $this->request->post['sort'],
            'order' => $this->request->post['order'],
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $filter_data = array_merge($filter_data, $this->getFilter($this->request->post['filter']));

        $products = $this->googleshopping->getProducts($filter_data, $this->store_id);

        $json['products'] = array_map(array($this, 'product'), $products);

        $product_total = $this->googleshopping->getTotalProducts($filter_data, $this->store_id);

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $this->request->post['page'];
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = '{page}';

        $pages = ceil($product_total / $this->config->get('config_limit_admin'));

        $json['showing'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, $pages);

        $json['pagination'] = $pagination->render();
        $json['total'] = (int)$product_total;
        $json['pages'] = (int)$pages;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function merchant() {
        $this->load->language('extension/advertise/google');

        $this->document->setTitle($this->language->get('heading_merchant'));

        $this->document->addStyle('view/stylesheet/googleshopping/stepper.css');

        $this->load->model('extension/advertise/google');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validatePermission()) {
            try {
                $redirect_uri = html_entity_decode($this->url->link('extension/advertise/google/callback_merchant', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
                $state = md5(microtime(true) . $redirect_uri . microtime(true));

                $auth_url_data = array(
                    'account_type' => $this->request->post['advertise_google_gmc_account_type'],
                    'redirect_uri' => $redirect_uri . '&state=' . $state
                );

                $this->session->data['advertise_google'] = $auth_url_data;
                $this->session->data['advertise_google']['state'] = $state;

                $this->response->redirect($this->googleshopping->getMerchantAuthUrl($auth_url_data));
            } catch (ConnectionException $e) {
                $this->session->data['error'] = $e->getMessage();

                $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (\RuntimeException $e) {
                $this->error['warning'] = $e->getMessage();
            }
        }

        $data = array();

        $data['error'] = '';

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else if (!empty($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        $data['success'] = '';

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extensions'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/advertise/google/merchant', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true),
        );

        $data['cancel']       = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true);
        $data['action']       = $this->url->link('extension/advertise/google/merchant', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->post['advertise_google_gmc_account_type'])) {
            $data['advertise_google_gmc_account_type'] = $this->request->post['advertise_google_gmc_account_type'];
        } else {
            $data['advertise_google_gmc_account_type'] = 'api';
        }

        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $data['current_step'] = 2;
        $data['steps'] = $this->load->view('extension/advertise/google_steps', $data);

        $this->response->setOutput($this->load->view('extension/advertise/google_merchant', $data));
    }

    public function shipping_taxes() {
        $this->load->language('extension/advertise/google');

        $this->document->setTitle($this->language->get('heading_shipping_taxes'));

        $this->document->addStyle('view/stylesheet/googleshopping/stepper.css');

        $this->load->model('extension/advertise/google');

        $this->load->config('googleshopping/googleshopping');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateShippingAndTaxes()) {
            try {
                $this->applyNewSettings($this->request->post);

                $this->googleshopping->pushShippingAndTaxes();

                $this->applyNewSettings(array(
                    'advertise_google_gmc_shipping_taxes_configured' => '1'
                ));

                $this->session->data['success'] = $this->language->get('success_shipping_taxes');

                $this->response->redirect($this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (ConnectionException $e) {
                $this->session->data['error'] = $e->getMessage();

                $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (\RuntimeException $e) {
                $this->error['warning'] = $e->getMessage();
            }
        }

        $available_carriers = array();

        try {
            $available_carriers = $this->googleshopping->getAvailableCarriers();
        } catch (ConnectionException $e) {
            $this->session->data['error'] = $e->getMessage();

            $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
        } catch (\RuntimeException $e) {
            $this->error['warning'] = $e->getMessage();
        }

        $data = array();

        $data['error'] = '';

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else if (!empty($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        if (isset($this->error['min_transit_time'])) {
            $data['error_min_transit_time'] = $this->error['min_transit_time'];
        } else {
            $data['error_min_transit_time'] = '';
        }

        if (isset($this->error['max_transit_time'])) {
            $data['error_max_transit_time'] = $this->error['max_transit_time'];
        } else {
            $data['error_max_transit_time'] = '';
        }

        if (isset($this->error['flat_rate'])) {
            $data['error_flat_rate'] = $this->error['flat_rate'];
        } else {
            $data['error_flat_rate'] = '';
        }

        if (isset($this->error['carrier_postcode'])) {
            $data['error_carrier_postcode'] = $this->error['carrier_postcode'];
        } else {
            $data['error_carrier_postcode'] = '';
        }

        if (isset($this->error['carrier_price_percentage'])) {
            $data['error_carrier_price_percentage'] = $this->error['carrier_price_percentage'];
        } else {
            $data['error_carrier_price_percentage'] = '';
        }

        if (isset($this->error['carrier'])) {
            $data['error_carrier'] = $this->error['carrier'];
        } else {
            $data['error_carrier'] = '';
        }

        $data['success'] = '';

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $data['from_dashboard'] = isset($this->request->get['from_dashboard']);

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extensions'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true),
        );

        if ($data['from_dashboard']) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_shipping_taxes'),
                'href' => $this->url->link('extension/advertise/google/shipping_taxes', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&from_dashboard=true', true),
            );
        }

        if ($data['from_dashboard']) {
            $data['cancel']       = $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['cancel']       = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true);
        }

        $data['action']       = $this->url->link('extension/advertise/google/shipping_taxes', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->post['advertise_google_shipping_taxes'])) {
            $data['advertise_google_shipping_taxes'] = $this->request->post['advertise_google_shipping_taxes'];
        } else if ($this->setting->has('advertise_google_shipping_taxes')) {
            $data['advertise_google_shipping_taxes'] = $this->setting->get('advertise_google_shipping_taxes');
        } else {
            $data['advertise_google_shipping_taxes'] = array(
                'shipping_type' => 'flat',
                'flat_rate' => $this->config->get('shipping_flat_cost'),
                'min_transit_time' => 1,
                'max_transit_time' => 14,
                'carrier_price_percentage' => 5,
                'tax_type' => $this->config->get('config_country_id') == 223 ? 'usa' : 'not_usa'
            );
        }

        $data['available_carriers'] = $available_carriers;

        $data['states'] = $this->config->get('advertise_google_tax_usa_states');

        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $data['current_step'] = 4;
        $data['steps'] = $this->load->view('extension/advertise/google_steps', $data);

        $this->response->setOutput($this->load->view('extension/advertise/google_shipping_taxes', $data));
    }

    public function mapping() {
        $this->load->language('extension/advertise/google');

        $this->document->setTitle($this->language->get('heading_mapping'));

        $this->document->addStyle('view/stylesheet/googleshopping/stepper.css');

        $this->load->model('extension/advertise/google');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateMapping()) {
            try {
                foreach ($this->request->post['advertise_google_mapping'] as $google_product_category => $category_id) {
                    $this->model_extension_advertise_google->setCategoryMapping($google_product_category, $this->store_id, $category_id);
                }

                if (!empty($this->request->post['advertise_google_modify_existing'])) {
                    $this->model_extension_advertise_google->updateGoogleProductCategoryMapping($this->store_id);
                }

                $this->session->data['success'] = $this->language->get('success_mapping');

                $this->response->redirect($this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (ConnectionException $e) {
                $this->session->data['error'] = $e->getMessage();

                $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (\RuntimeException $e) {
                $this->error['warning'] = $e->getMessage();
            }
        }

        $data = array();

        $data['error'] = '';

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else if (!empty($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        $data['success'] = '';

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $data['from_dashboard'] = isset($this->request->get['from_dashboard']);

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extensions'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true),
        );

        if ($data['from_dashboard']) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_shipping_taxes'),
                'href' => $this->url->link('extension/advertise/google/mapping', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&from_dashboard=true', true),
            );
        }

        $this->load->config('googleshopping/googleshopping');

        $data['mapping'] = array();

        foreach ($this->config->get('advertise_google_google_product_categories') as $google_product_category_id => $google_product_category_name) {
            if ($google_product_category_id == 0) continue;

            $category_id = '';
            $name = '';

            if (null !== $category = $this->model_extension_advertise_google->getMappedCategory($google_product_category_id, $this->store_id)) {
                $category_id = $category['category_id'];
                $name = $category['name'];
            }

            $map = array(
                'google_product_category' => array(
                    'id' => $google_product_category_id,
                    'name' => $google_product_category_name
                ),
                'oc_category' => array(
                    'category_id' => $category_id,
                    'name' => $name
                )
            );

            $data['mapping'][] = $map;
        }

        $data['mapping_json'] = json_encode($data['mapping']);

        if ($data['from_dashboard']) {
            $data['cancel']       = $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['cancel']       = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true);
        }

        $data['action']       = $this->url->link('extension/advertise/google/mapping', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);

        $data['user_token'] = $this->session->data['user_token'];

        $data['url_mapping_verify'] = html_entity_decode($this->url->link('extension/advertise/google/mapping_verify', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
        $data['url_category_autocomplete'] = html_entity_decode($this->url->link('extension/advertise/google/category_autocomplete', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');

        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $data['current_step'] = 5;
        $data['steps'] = $this->load->view('extension/advertise/google_steps', $data);

        $this->response->setOutput($this->load->view('extension/advertise/google_mapping', $data));
    }

    public function mapping_verify() {
        $this->load->language('extension/advertise/google');

        $this->load->model('extension/advertise/google');
        
        $data = array();

        $json = array(
            'submit_directly' => !$this->model_extension_advertise_google->isAnyProductCategoryModified($this->store_id),
            'modal_confirmation' => $this->load->view('extension/advertise/google_mapping_verify', $data)
        );

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function campaign_test() {
        $json = array(
            'status' => false,
            'redirect' => null,
            'error' => null
        );

        if ($this->validatePermission()) {
            try {
                $json['status'] = $this->googleshopping->testCampaigns();
            } catch (ConnectionException $e) {
                $this->session->data['error'] = $e->getMessage();

                $json['redirect'] = html_entity_decode($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
            } catch (\RuntimeException $e) {
                $json['status'] = false;
                $json['error'] = $e->getMessage();
            }

            $this->applyNewSettings(array(
                'advertise_google_can_edit_campaigns' => (int)$json['status']
            ));
        } else {
            $json['error'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function campaign() {
        $this->load->language('extension/advertise/google');

        $this->document->setTitle($this->language->get('heading_campaign'));

        $this->document->addStyle('view/stylesheet/googleshopping/stepper.css');

        $this->load->model('extension/advertise/google');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateCampaign()) {
            $this->applyNewSettings($this->request->post);

            // If there is no redirect from the push of targets, go back to the extension dashboard
            $this->response->redirect($this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
        }

        $data = array();

        $data['error'] = '';

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else if (!empty($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        $data['success'] = '';

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $data['warning'] = '';

        if (!$this->setting->get('advertise_google_status') && $this->model_extension_advertise_google->hasActiveTarget($this->store_id)) {
            $data['warning'] = $this->language->get('warning_paused_targets');
        }

        $data['from_dashboard'] = isset($this->request->get['from_dashboard']);

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extensions'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true),
        );

        if ($data['from_dashboard']) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_campaign'),
                'href' => $this->url->link('extension/advertise/google/campaign', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&from_dashboard=true', true),
            );
        }

        if (isset($this->request->post['advertise_google_auto_advertise'])) {
            $data['advertise_google_auto_advertise'] = $this->request->post['advertise_google_auto_advertise'];
        } else if ($this->setting->has('advertise_google_auto_advertise')) {
            $data['advertise_google_auto_advertise'] = $this->setting->get('advertise_google_auto_advertise');
        } else {
            $data['advertise_google_auto_advertise'] = '0';
        }

        if ($data['from_dashboard']) {
            $data['cancel']       = $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['cancel']       = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true);
        }

        $data['action'] = $this->url->link('extension/advertise/google/campaign', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);
        $data['target_add'] = html_entity_decode($this->url->link('extension/advertise/google/target_add', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
        $data['target_edit'] = html_entity_decode($this->url->link('extension/advertise/google/target_edit', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&advertise_google_target_id={target_id}', true), ENT_QUOTES, 'UTF-8');
        $data['target_delete'] = html_entity_decode($this->url->link('extension/advertise/google/target_delete', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&advertise_google_target_id={target_id}', true), ENT_QUOTES, 'UTF-8');
        $data['target_list'] = html_entity_decode($this->url->link('extension/advertise/google/target_list', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
        $data['url_campaign_test'] = html_entity_decode($this->url->link('extension/advertise/google/campaign_test', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
        $data['can_edit_campaigns'] = (bool)$this->setting->get('advertise_google_can_edit_campaigns');
        $data['text_roas_warning'] = sprintf($this->language->get('warning_roas'), date($this->language->get('date_format_long'), time() + Googleshopping::ROAS_WAIT_INTERVAL));

        $data['json_allowed_targets'] = json_encode($this->model_extension_advertise_google->getAllowedTargets());

        $targets = $this->googleshopping->getTargets($this->store_id);

        foreach ($targets as &$target) {
            if (!$target['roas_status']) {
                $target['roas_warning'] = sprintf($this->language->get('warning_roas'), date($this->language->get('date_format_long'), $target['roas_available_on']));
            } else {
                $target['roas_warning'] = null;
            }
        }

        $data['targets'] = $targets;
        $data['json_targets'] = json_encode($targets);

        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $data['current_step'] = 3;
        $data['steps'] = $this->load->view('extension/advertise/google_steps', $data);

        $this->response->setOutput($this->load->view('extension/advertise/google_campaign', $data));
    }

    public function target_add() {
        $this->load->language('extension/advertise/google');

        $json = array(
            'success' => null,
            'redirect' => null,
            'error' => null
        );

        if ($this->validatePermission()) {
            if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateTarget()) {
                $this->load->model('extension/advertise/google');

                $target = array(
                    'store_id' => $this->store_id,
                    'campaign_name' => str_replace(',', '&#44;', trim($this->request->post['campaign_name'])),
                    'country' => $this->request->post['country'],
                    'status' => $this->request->post['status'] == 'active' ? 'active' : 'paused',
                    'budget' => (float)preg_replace('~[^0-9\.]~i', '', $this->request->post['budget']),
                    'roas' => isset($this->request->post['roas']) ? (int)$this->request->post['roas'] : 0,
                    'feeds' => array_values($this->request->post['feed'])
                );

                $this->model_extension_advertise_google->addTarget($target, $this->store_id);

                try {
                    $this->googleshopping->pushTargets();

                    $json['success'] = $this->language->get('success_target_add');
                } catch (ConnectionException $e) {
                    $this->session->data['error'] = $e->getMessage();

                    $json['redirect'] = html_entity_decode($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
                } catch (\RuntimeException $e) {
                    $json['error'] = $e->getMessage();
                }
            } else {
                $json['error'] = $this->error['warning'];

                if (isset($this->error['campaign_name'])) {
                    $json['error_campaign_name'] = $this->error['campaign_name'];
                }

                if (isset($this->error['country'])) {
                    $json['error_country'] = $this->error['country'];
                }

                if (isset($this->error['budget'])) {
                    $json['error_budget'] = $this->error['budget'];
                }

                if (isset($this->error['feed'])) {
                    $json['error_feed'] = $this->error['feed'];
                }
            }
        } else {
            $json['error'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function target_edit() {
        $this->load->language('extension/advertise/google');

        $json = array(
            'success' => null,
            'redirect' => null,
            'error' => null
        );

        if ($this->validatePermission()) {
            if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateTarget()) {
                $this->load->model('extension/advertise/google');

                $target = array(
                    'campaign_name' => str_replace(',', '&#44;', trim($this->request->post['campaign_name'])),
                    'country' => $this->request->post['country'],
                    'status' => $this->request->post['status'] == 'active' ? 'active' : 'paused',
                    'budget' => (float)preg_replace('~[^0-9\.]~i', '', $this->request->post['budget']),
                    'roas' => isset($this->request->post['roas']) ? (int)$this->request->post['roas'] : 0,
                    'feeds' => array_values($this->request->post['feed'])
                );

                $this->googleshopping->editTarget((int)$this->request->get['advertise_google_target_id'], $target);

                try {
                    $this->googleshopping->pushTargets();

                    $json['success'] = $this->language->get('success_target_edit');
                } catch (ConnectionException $e) {
                    $this->session->data['error'] = $e->getMessage();

                    $json['redirect'] = html_entity_decode($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
                } catch (\RuntimeException $e) {
                    $json['error'] = $e->getMessage();
                }
            } else {
                $json['error'] = $this->error['warning'];

                if (isset($this->error['campaign_name'])) {
                    $json['error_campaign_name'] = $this->error['campaign_name'];
                }

                if (isset($this->error['country'])) {
                    $json['error_country'] = $this->error['country'];
                }

                if (isset($this->error['budget'])) {
                    $json['error_budget'] = $this->error['budget'];
                }

                if (isset($this->error['feed'])) {
                    $json['error_feed'] = $this->error['feed'];
                }
            }
        } else {
            $json['error'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function target_delete() {
        $this->load->language('extension/advertise/google');

        $json = array(
            'success' => null,
            'redirect' => null,
            'error' => null
        );

        if ($this->validatePermission()) {
            $this->load->model('extension/advertise/google');

            $advertise_google_target_id = (int)$this->request->get['advertise_google_target_id'];

            $target_info = $this->googleshopping->getTarget($advertise_google_target_id);

            if (!empty($target_info)) {
                try {
                    $this->googleshopping->deleteCampaign($target_info['campaign_name']);

                    $this->googleshopping->deleteTarget($advertise_google_target_id);

                    $json['success'] = $this->language->get('success_target_delete');
                } catch (ConnectionException $e) {
                    $this->session->data['error'] = $e->getMessage();

                    $json['redirect'] = html_entity_decode($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
                } catch (\RuntimeException $e) {
                    $json['error'] = $e->getMessage();
                }
            }
        } else {
            $json['error'] = $this->error['warning'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function target_list() {
        $this->load->language('extension/advertise/google');

        $json = array(
            'targets' => null,
            'error' => null
        );

        $this->load->model('extension/advertise/google');

        $targets = $this->googleshopping->getTargets($this->store_id);

        foreach ($targets as &$target) {
            if (!$target['roas_status']) {
                $target['roas_warning'] = sprintf($this->language->get('warning_roas'), date($this->language->get('date_format_long'), $target['roas_available_on']));
            } else {
                $target['roas_warning'] = null;
            }
        }

        $json['targets'] = $targets;

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function callback_merchant() {
        $state_verified =
            !empty($this->session->data['advertise_google']['state']) &&
            !empty($this->request->get['state']) && 
            $this->request->get['state'] == $this->session->data['advertise_google']['state'];

        $error = isset($this->request->get['error']) ? $this->request->get['error'] : null;
        $merchant_id = isset($this->request->get['merchant_id']) ? $this->request->get['merchant_id'] : null;

        if ($state_verified && is_null($error)) {
            $this->load->language('extension/advertise/google');

            try {
                $this->googleshopping->verifySite();

                $this->load->model('user/user');
                $user_info = $this->model_user_user->getUser($this->user->getId());

                $this->applyNewSettings(array(
                    'advertise_google_gmc_account_selected' => true,
                    'advertise_google_gmc_account_id' => $merchant_id,
                    'advertise_google_gmc_account_accepted_by' => array(
                        'user_id' => $user_info['user_id'],
                        'user_group_id' => $user_info['user_group_id'],
                        'user_group' => $user_info['user_group'],
                        'username' => $user_info['username'],
                        'firstname' => $user_info['firstname'],
                        'lastname' => $user_info['lastname'],
                        'email' => $user_info['email'],
                        'ip' => $user_info['ip']
                    ),
                    'advertise_google_gmc_account_accepted_at' => time(),
                    'advertise_google_conversion_tracker' => $this->googleshopping->getConversionTracker(),
                    'advertise_google_can_edit_campaigns' => '0'
                ));

                if ($this->session->data['advertise_google']['account_type'] == 'api') {
                    $this->session->data['success'] = sprintf($this->language->get('success_merchant_access'), $merchant_id);
                } else {
                    $this->session->data['success'] = $this->language->get('success_merchant');
                }

                if (count($this->googleshopping->getTargets($this->store_id)) > 0) {
                    $this->response->redirect($this->url->link('extension/advertise/google/campaign', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
                }
            } catch (ConnectionException $e) {
                $this->session->data['error'] = $e->getMessage();

                unset($this->session->data['advertise_google']);

                $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (\RuntimeException $e) {
                $this->session->data['error'] = $e->getMessage();
            }
        } else if (!is_null($error)) {
            $this->session->data['error'] = $error;

            $setting = $this->model_setting_setting->getSetting('advertise_google', $this->store_id);

            unset($setting['advertise_google_status']);
            unset($setting['advertise_google_work']);
            unset($setting['advertise_google_gmc_account_selected']);
            unset($setting['advertise_google_gmc_shipping_taxes_configured']);
            unset($setting['advertise_google_can_edit_campaigns']);

            $this->model_setting_setting->editSetting('advertise_google', $setting, $this->store_id);
        }

        unset($this->session->data['advertise_google']);

        $this->response->redirect($this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
    }

    public function callback_connect() {
        $state_verified =
            !empty($this->session->data['advertise_google']['state']) &&
            !empty($this->request->get['state']) && 
            $this->request->get['state'] == $this->session->data['advertise_google']['state'];

        if ($state_verified) {
            $this->load->language('extension/advertise/google');

            $this->load->model('extension/advertise/google');

            try {
                $access = $this->googleshopping->access($this->session->data['advertise_google'], urldecode($this->request->get['code']));

                $this->applyNewSettings(array(
                    'advertise_google_app_id' => $this->session->data['advertise_google']['app_id'],
                    'advertise_google_app_secret' => $this->session->data['advertise_google']['app_secret'],
                    'advertise_google_status' => $this->session->data['advertise_google']['status'],
                    'advertise_google_cron_token' => $this->session->data['advertise_google']['cron_token'],
                    'advertise_google_cron_acknowledge' => $this->session->data['advertise_google']['cron_acknowledge'],
                    'advertise_google_cron_email' => $this->session->data['advertise_google']['cron_email'],
                    'advertise_google_cron_email_status' => $this->session->data['advertise_google']['cron_email_status'],
                    'advertise_google_access_token' => $access['access_token'],
                    'advertise_google_refresh_token' => $access['refresh_token']
                ));

                $this->session->data['success'] = $this->language->get('success_connect');

                if (count($this->googleshopping->getTargets($this->store_id)) > 0 && $this->setting->get('advertise_google_gmc_account_selected')) {
                    $this->response->redirect($this->url->link('extension/advertise/google/campaign', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
                }
            } catch (ConnectionException $e) {
                $this->session->data['error'] = $e->getMessage();

                $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (\RuntimeException $e) {
                $this->session->data['error'] = $e->getMessage();
            }
        } else if (isset($this->request->get['error'])) {
            $this->session->data['error'] = $this->request->get['error'];
        }

        unset($this->session->data['advertise_google']);

        if ($this->setting->get('advertise_google_gmc_account_selected')) {
            $this->response->redirect($this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
        } else {
            $this->response->redirect($this->url->link('extension/advertise/google/merchant', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
        }
    }

    public function connect() {
        $this->load->language('extension/advertise/google');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->document->addStyle('view/stylesheet/googleshopping/stepper.css');

        $this->load->model('extension/advertise/google');

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateSettings() && $this->validateConnect()) {
            unset($this->session->data['advertise_google']);

            $this->session->data['advertise_google']['app_id'] = $this->request->post['advertise_google_app_id'];
            $this->session->data['advertise_google']['app_secret'] = $this->request->post['advertise_google_app_secret'];
            $this->session->data['advertise_google']['status'] = $this->request->post['advertise_google_status'];
            $this->session->data['advertise_google']['cron_email_status'] = $this->request->post['advertise_google_cron_email_status'];
            $this->session->data['advertise_google']['cron_email'] = $this->request->post['advertise_google_cron_email'];
            $this->session->data['advertise_google']['cron_token'] = $this->request->post['advertise_google_cron_token'];
            $this->session->data['advertise_google']['cron_acknowledge'] = isset($this->request->post['advertise_google_cron_acknowledge']);
            $this->session->data['advertise_google']['redirect_uri'] = html_entity_decode($this->url->link('extension/advertise/google/callback_connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true), ENT_QUOTES, 'UTF-8');
            $this->session->data['advertise_google']['state'] = md5(microtime(true) . json_encode($this->session->data['advertise_google']) . microtime(true));

            $url = $this->googleshopping->authorize($this->session->data['advertise_google']);

            $this->response->redirect($url);
        }

        $data = array();

        $data['error'] = '';

        if (isset($this->session->data['error'])) {
            if (empty($this->session->data['success']) && $this->getSettingValue('advertise_google_app_id', false) && $this->getSettingValue('advertise_google_app_secret', false)) {
                $data['error'] = $this->session->data['error'];
            }
            unset($this->session->data['error']);
        } else if (!empty($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        $data['error_cron_email']                   = $this->getValidationError('cron_email');
        $data['error_cron_acknowledge']             = $this->getValidationError('cron_acknowledge');

        if (isset($this->error['app_id'])) {
            $data['error_app_id'] = $this->error['app_id'];
        } else {
            $data['error_app_id'] = '';
        }

        if (isset($this->error['app_secret'])) {
            $data['error_app_secret'] = $this->error['app_secret'];
        } else {
            $data['error_app_secret'] = '';
        }

        $data['success'] = '';

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        }

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extensions'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true),
        );

        $data['advertise_google_status']                = $this->getSettingValue('advertise_google_status', 1);
        $data['advertise_google_app_id']                = $this->getSettingValue('advertise_google_app_id', '');
        $data['advertise_google_app_secret']            = $this->getSettingValue('advertise_google_app_secret', '');
        $data['advertise_google_cron_email_status']     = $this->getSettingValue('advertise_google_cron_email_status');
        $data['advertise_google_cron_email']            = $this->getSettingValue('advertise_google_cron_email', $this->config->get('config_email'));
        $data['advertise_google_cron_token']            = $this->getSettingValue('advertise_google_cron_token');
        $data['advertise_google_cron_acknowledge']      = $this->getSettingValue('advertise_google_cron_acknowledge', null, true);

        $server = $this->googleshopping->getStoreUrl();

        $data['advertise_google_cron_command'] = 'export CUSTOM_SERVER_NAME=' . parse_url($server, PHP_URL_HOST) . '; export CUSTOM_SERVER_PORT=443; export ADVERTISE_GOOGLE_CRON=1; export ADVERTISE_GOOGLE_STORE_ID=' . $this->store_id . '; ' . PHP_BINDIR . '/php -d session.save_path=' . session_save_path() . ' -d memory_limit=256M ' . DIR_SYSTEM . 'library/googleshopping/cron.php > /dev/null 2> /dev/null';
        
        if (!$this->setting->get('advertise_google_cron_token')) {
            $data['advertise_google_cron_token'] = md5(mt_rand());
        }

        $host_and_uri = parse_url($server, PHP_URL_HOST) . dirname(parse_url($server, PHP_URL_PATH));

        $data['advertise_google_cron_url'] = 'https://' . rtrim($host_and_uri, '/') . '/index.php?route=extension/advertise/google/cron&cron_token={CRON_TOKEN}';

        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $data['cancel']       = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true);
        $data['action']       = $this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);

        $data['text_connect_intro'] = sprintf($this->language->get('text_connect_intro'), Googleshopping::API_URL);

        $data['current_step'] = 1;
        $data['steps'] = $this->load->view('extension/advertise/google_steps', $data);

        $this->response->setOutput($this->load->view('extension/advertise/google_connect', $data));
    }

    public function disconnect() {
        $this->load->language('extension/advertise/google');

        if ($this->validatePermission()) {
            try {
                $this->load->model('setting/setting');

                $this->googleshopping->disconnect();

                foreach ($this->googleshopping->getTargets($this->store_id) as $target) {
                    $this->googleshopping->deleteTarget($target['target_id']);
                }

                $setting = $this->model_setting_setting->getSetting('advertise_google', $this->store_id);

                unset($setting['advertise_google_status']);
                unset($setting['advertise_google_work']);
                unset($setting['advertise_google_access_token']);
                unset($setting['advertise_google_refresh_token']);
                unset($setting['advertise_google_gmc_account_selected']);
                unset($setting['advertise_google_gmc_shipping_taxes_configured']);
                unset($setting['advertise_google_can_edit_campaigns']);

                $this->model_setting_setting->editSetting('advertise_google', $setting, $this->store_id);

                $this->session->data['success'] = $this->language->get('success_disconnect');
            } catch (ConnectionException $e) {
                $this->session->data['error'] = $e->getMessage();

                $this->response->redirect($this->url->link('extension/advertise/google/connect', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
            } catch (\RuntimeException $e) {
                $this->session->data['error'] = $e->getMessage();
            }
        } else {
            $this->session->data['error'] = $this->error['warning'];
        }

        $this->response->redirect($this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
    }

    public function checklist() {
        $this->load->language('extension/advertise/google');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validatePermission()) {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('advertise_google', $this->request->post, $this->store_id);

            $this->response->redirect($this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true));
        }

        $data = array();

        $data['error'] = '';

        if (isset($this->session->data['error'])) {
            $data['error'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else if (!empty($this->error['warning'])) {
            $data['error'] = $this->error['warning'];
        }

        $data['breadcrumbs']   = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extensions'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true),
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/advertise/google', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true),
        );

        $data['text_panel_heading'] = sprintf($this->language->get('text_panel_heading'), $this->googleshopping->getStoreName());

        $data['cancel']       = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=advertise', true);
        $data['action']       = $this->url->link('extension/advertise/google/checklist', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'], true);
        
        $data['header']       = $this->load->controller('common/header');
        $data['column_left']  = $this->load->controller('common/column_left');
        $data['footer']       = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/advertise/google_checklist', $data));
    }

    public function popup_product() {
        $json = array(
            'body' => '',
            'title' => '',
            'success' => false,
            'required_fields' => [],
            'success_message' => ''
        );

        $this->language->load('extension/advertise/google');

        $this->load->model('extension/advertise/google');

        $operand_info = NULL;
        $form_data = NULL;
        $filter_data = NULL;
        $product_ids = array();

        if ($this->request->post['operand']['type'] == 'single') {
            $product_advertise_google_id = $this->request->post['operand']['data'];

            $product_info = $this->model_extension_advertise_google->getProductByProductAdvertiseGoogleId($product_advertise_google_id);

            if ($product_info !== NULL) {
                $json['product_id'] = $product_info['product_id'];
                
                // Required variables:
                $operand_info = array(
                    'title' => sprintf($this->language->get('text_popup_title_single'), $product_info['name'], $product_info['model'])
                );

                $required_fields = $this->model_extension_advertise_google->getRequiredFieldsByProductIds(array($product_info['product_id']), $this->store_id);

                if ($this->request->post['action'] == 'submit') {
                    $form_data = array_merge($this->request->post['form'], array(
                        'product_id' => $product_info['product_id']
                    ));
                }

                $options = $this->model_extension_advertise_google->getProductOptionsByProductIds(array($product_info['product_id']));

                $default_form_data = $this->model_extension_advertise_google->getProductAdvertiseGoogle($product_advertise_google_id);
            }
        } else if ($this->request->post['operand']['type'] == 'multiple') {
            if (!empty($this->request->post['operand']['data']['all_pages'])) {
                $filter_data = $this->getFilter($this->request->post['operand']['data']['filter']);

                $total_products = $this->googleshopping->getTotalProducts($filter_data, $this->store_id);

                // Required variables:
                $operand_info = array(
                    'title' => sprintf($this->language->get('text_popup_title_multiple'), $total_products)
                );

                $required_fields = $this->model_extension_advertise_google->getRequiredFieldsByFilter($filter_data, $this->store_id);

                if ($this->request->post['action'] == 'submit') {
                    $form_data = $this->request->post['form'];
                }

                $options = $this->model_extension_advertise_google->getProductOptionsByFilter($filter_data);
            } else {
                $product_ids = $this->request->post['operand']['data']['select'];

                $total_products = count($product_ids);

                // Required variables:
                $operand_info = array(
                    'title' => sprintf($this->language->get('text_popup_title_multiple'), $total_products)
                );

                $required_fields = $this->model_extension_advertise_google->getRequiredFieldsByProductIds($product_ids, $this->store_id);

                if ($this->request->post['action'] == 'submit') {
                    $form_data = $this->request->post['form'];
                }

                $options = $this->model_extension_advertise_google->getProductOptionsByProductIds($product_ids);
            }

            $default_form_data = array(
                'google_product_category' => '',
                'condition' => '',
                'adult' => '',
                'multipack' => '',
                'is_bundle' => '',
                'age_group' => '',
                'color' => '',
                'gender' => '',
                'size_type' => '',
                'size_system' => '',
                'size' => ''
            );
        }

        if ($operand_info !== NULL) {
            $json['title'] = $operand_info['title'];
            $json['success_message'] = $this->language->get('success_product');

            $this->load->config('googleshopping/googleshopping');

            $json['required_fields'] = $required_fields;

            if ($this->request->post['action'] == 'submit' && $this->validateProduct($required_fields)) {
                $form_data['store_id'] = (int)$this->store_id;

                if ($this->request->post['operand']['type'] == 'single') {
                    $this->model_extension_advertise_google->updateSingleProductFields($form_data);
                } else if ($this->request->post['operand']['type'] == 'multiple') {
                    if (!empty($this->request->post['operand']['data']['all_pages'])) {
                        $this->model_extension_advertise_google->updateMultipleProductFields($filter_data, $form_data);
                    } else {
                        foreach ($product_ids as $product_id) {
                            $form_data['product_id'] = (int)$product_id;
                            $this->model_extension_advertise_google->updateSingleProductFields($form_data);
                        }
                    }
                }

                $json['success'] = true;
            }

            $data['error'] = '';

            if (!empty($this->error['warning'])) {
                $data['error'] = $this->error['warning'];
            }

            if (isset($this->error['color'])) {
                $data['error_color'] = $this->error['color'];
            } else {
                $data['error_color'] = '';
            }

            if (isset($this->error['size_system'])) {
                $data['error_size_system'] = $this->error['size_system'];
            } else {
                $data['error_size_system'] = '';
            }

            if (isset($this->error['size_type'])) {
                $data['error_size_type'] = $this->error['size_type'];
            } else {
                $data['error_size_type'] = '';
            }

            if (isset($this->error['size'])) {
                $data['error_size'] = $this->error['size'];
            } else {
                $data['error_size'] = '';
            }

            if (isset($this->error['product_category'])) {
                $data['error_product_category'] = $this->error['product_category'];
            } else {
                $data['error_product_category'] = '';
            }

            if (isset($this->error['condition'])) {
                $data['error_condition'] = $this->error['condition'];
            } else {
                $data['error_condition'] = '';
            }

            if (isset($this->error['age_group'])) {
                $data['error_age_group'] = $this->error['age_group'];
            } else {
                $data['error_age_group'] = '';
            }

            if (isset($this->error['gender'])) {
                $data['error_gender'] = $this->error['gender'];
            } else {
                $data['error_gender'] = '';
            }

            if (isset($this->error['adult'])) {
                $data['error_adult'] = $this->error['adult'];
            } else {
                $data['error_adult'] = '';
            }

            if (isset($this->error['multipack'])) {
                $data['error_multipack'] = $this->error['multipack'];
            } else {
                $data['error_multipack'] = '';
            }

            if (isset($this->error['is_bundle'])) {
                $data['error_is_bundle'] = $this->error['is_bundle'];
            } else {
                $data['error_is_bundle'] = '';
            }

            $data['google_product_categories'] = $this->config->get('advertise_google_google_product_categories');
            $data['conditions'] = array(
                'new' => $this->language->get('text_condition_new'),
                'refurbished' => $this->language->get('text_condition_refurbished'),
                'used' => $this->language->get('text_condition_used')
            );
            $data['age_groups'] = array(
                '' => $this->language->get('text_does_not_apply'),
                'newborn' => $this->language->get('text_age_group_newborn'),
                'infant' => $this->language->get('text_age_group_infant'),
                'toddler' => $this->language->get('text_age_group_toddler'),
                'kids' => $this->language->get('text_age_group_kids'),
                'adult' => $this->language->get('text_age_group_adult')
            );
            $data['genders'] = array(
                'unisex' => $this->language->get('text_gender_unisex'),
                'female' => $this->language->get('text_gender_female'),
                'male' => $this->language->get('text_gender_male')
            );
            $data['size_systems'] = array(
                '' => $this->language->get('text_does_not_apply')
            );
            foreach ($this->config->get('advertise_google_size_systems') as $system) {
                $data['size_systems'][$system] = $system;
            }

            $data['size_types'] = array(
                '' => $this->language->get('text_does_not_apply'),
                'regular' => $this->language->get('text_size_type_regular'),
                'petite' => $this->language->get('text_size_type_petite'),
                'plus' => $this->language->get('text_size_type_plus'),
                'big and tall' => $this->language->get('text_size_type_big_and_tall'),
                'maternity' => $this->language->get('text_size_type_maternity')
            );

            $data['options'] = array(
                '' => $this->language->get('text_does_not_apply')
            );

            foreach ($options as $option) {
                $data['options'][$option['option_id']] = $option['name'];
            }

            $data['required_fields'] = json_encode($required_fields);

            if ($this->request->post['action'] == 'submit') {
                $form_data = $this->request->post['form'];
            } else {
                $form_data = $default_form_data;
            }

            $data['google_product_category'] = $form_data['google_product_category'];
            $data['condition'] = $form_data['condition'];
            $data['adult'] = $form_data['adult'];
            $data['multipack'] = $form_data['multipack'];
            $data['is_bundle'] = $form_data['is_bundle'];
            $data['age_group'] = $form_data['age_group'];
            $data['color'] = $form_data['color'];
            $data['gender'] = $form_data['gender'];
            $data['size_type'] = $form_data['size_type'];
            $data['size_system'] = $form_data['size_system'];
            $data['size'] = $form_data['size'];

            $json['body'] = $this->load->view('extension/advertise/google_popup_product', $data);
        } else {
            $json['title'] = $this->language->get('error_popup_not_found_title');
            $json['body'] = $this->language->get('error_popup_not_found_body');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function popup_issues() {
        $json = array(
            'body' => '',
            'title' => ''
        );

        $this->language->load('extension/advertise/google');

        $this->load->model('catalog/product');
        $this->load->model('extension/advertise/google');

        $product_id = isset($this->request->get['product_id']) ? (int)$this->request->get['product_id'] : 0;

        $product_issues = $this->model_extension_advertise_google->getProductIssues($product_id, $this->store_id);

        if ($product_issues !== NULL) {
            $json['title'] = sprintf($this->language->get('text_popup_title_single'), $product_issues['name'], $product_issues['model']);

            $data['product_issues'] = $product_issues['entries'];

            $json['body'] = $this->load->view('extension/advertise/google_popup_issues', $data);
        } else {
            $json['title'] = $this->language->get('error_popup_not_found_title');
            $json['body'] = $this->language->get('error_popup_not_found_body');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function admin_link(&$route, &$data, &$template) {
        if (!$this->user->hasPermission('access', 'extension/advertise/google')) {
            return;
        }

        foreach ($data['menus'] as &$menu) {
            if ($menu['id'] == 'menu-marketing') {
                $children = array();

                $this->load->model('setting/store');

                $children[] = array(
                    'name' => $this->config->get('config_name'),
                    'children' => array(),
                    'href' => $this->url->link('extension/advertise/google', 'store_id=0&user_token=' . $this->session->data['user_token'], true)
                );

                foreach ($this->model_setting_store->getStores() as $store) {
                    $children[] = array(
                        'name' => $store['name'],
                        'children' => array(),
                        'href' => $this->url->link('extension/advertise/google', 'store_id=' . $store['store_id'] . '&user_token=' . $this->session->data['user_token'], true)
                    );
                }

                array_push($menu['children'], array(
                    'name' => 'Google Shopping',
                    'children' => $children,
                    'href' => ''
                ));

                return;
            }
        }
    }

    public function addProduct(&$route, &$args, &$output) {
        $this->load->model('extension/advertise/google');
        $this->load->model('catalog/product');

        foreach ($this->model_catalog_product->getProductStores($output) as $store_id) {
            $this->model_extension_advertise_google->insertNewProducts(array($output), $store_id);
        }
    }

    public function copyProduct(&$route, &$args, &$output) {
        $this->load->model('extension/advertise/google');
        $this->load->model('catalog/product');

        $final_product_id = $this->model_extension_advertise_google->getFinalProductId();

        if (!empty($final_product_id)) {
            foreach ($this->model_catalog_product->getProductStores($final_product_id) as $store_id) {
                $this->model_extension_advertise_google->insertNewProducts(array($final_product_id), $store_id);
            }
        }
    }

    public function deleteProduct(&$route, &$args, &$output) {
        $this->load->model('extension/advertise/google');

        $this->model_extension_advertise_google->deleteProducts(array((int)$args[0]));
    }

    public function install() {
        $this->load->model('extension/advertise/google');

        $this->model_extension_advertise_google->createTables();
        $this->model_extension_advertise_google->createEvents();
    }

    public function uninstall() {
        $this->load->model('extension/advertise/google');

        $this->model_extension_advertise_google->dropTables();
        $this->model_extension_advertise_google->deleteEvents();
    }

    public function category_autocomplete() {
        $json = array();

        if (isset($this->request->get['filter_name'])) {
            $this->load->model('extension/advertise/google');

            $filter_data = array(
                'filter_name' => $this->request->get['filter_name'],
                'sort'        => 'name',
                'order'       => 'ASC',
                'start'       => 0,
                'limit'       => 5
            );

            $results = $this->model_extension_advertise_google->getCategories($filter_data, $this->store_id);

            foreach ($results as $result) {
                $json[] = array(
                    'category_id' => $result['category_id'],
                    'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                );
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function getFilter($array) {
        if (!empty($array)) {
            return array(
                'filter_product_name' => $array['product_name'],
                'filter_product_model' => $array['product_model'],
                'filter_category_id' => $array['category_id'],
                'filter_is_modified' => $array['is_modified'],
                'filter_store_id' => $this->store_id
            );
        }

        return array(
            'filter_store_id' => $this->store_id
        );
    }

    protected function applyNewSettings($new_settings) {
        $this->load->model('setting/setting');

        $old_settings = $this->model_setting_setting->getSetting('advertise_google', $this->store_id);

        $new_settings = array_merge($old_settings, $new_settings);

        $this->model_setting_setting->editSetting('advertise_google', $new_settings, $this->store_id);

        foreach ($new_settings as $key => $value) {
            $this->setting->set($key, $value);
        }
    }

    protected function product(&$row) {        
        $this->load->config('googleshopping/googleshopping');

        $this->load->model('tool/image');

        if (!empty($row['image']) && file_exists(DIR_IMAGE . $row['image'])) {
            $image = $this->model_tool_image->resize($row['image'], 50, 50);
        } else {
            $image = $this->model_tool_image->resize('no_image.png', 50, 50);
        }

        return array(
            'product_advertise_google_id' => (int)$row['product_advertise_google_id'],
            'product_id' => (int)$row['product_id'],
            'image' => $image,
            'name' => htmlentities(html_entity_decode($row['name'], ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8'),
            'model' => $row['model'],
            'impressions' => (int)$row['impressions'],
            'clicks' => (int)$row['clicks'],
            'conversions' => (int)$row['conversions'],
            'cost' => $this->googleshopping->currencyFormat($row['cost']),
            'conversion_value' => $this->googleshopping->currencyFormat($row['conversion_value']),
            'destination_status' => $row['destination_status'],
            'is_modified' => (bool)$row['is_modified'],
            'has_issues' => (bool)$row['has_issues'],
            'url_issues' => html_entity_decode($this->url->link('extension/advertise/google/popup_issues', 'store_id=' . $this->store_id . '&user_token=' . $this->session->data['user_token'] . '&product_id=' . $row['product_id'], true), ENT_QUOTES, 'UTF-8'),
            'campaigns' => $this->model_extension_advertise_google->getProductCampaigns((int)$row['product_id'], $this->store_id)
        );
    }

    protected function getSettingValue($key, $default = null, $checkbox = false) {
        if ($checkbox) {
            if ($this->request->server['REQUEST_METHOD'] == 'POST' && !isset($this->request->post[$key])) {
                return $default;
            } else {
                return $this->setting->get($key);
            }
        }

        if (isset($this->request->post[$key])) {
            return $this->request->post[$key]; 
        } else if ($this->setting->has($key)) {
            return $this->setting->get($key);
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

    protected function validateSettings() {
        $this->validatePermission();

        if (empty($this->request->post['advertise_google_status'])) {
            return true;
        }

        if (!empty($this->request->post['advertise_google_cron_email_status'])) {
            if (!filter_var($this->request->post['advertise_google_cron_email'], FILTER_VALIDATE_EMAIL)) {
                $this->error['cron_email'] = $this->language->get('error_invalid_email');
            }
        }

        if (empty($this->request->post['advertise_google_cron_acknowledge'])) {
            $this->error['cron_acknowledge'] = $this->language->get('error_cron_acknowledge');
        }

        if ($this->error && empty($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_form');
        }

        return !$this->error;
    }

    protected function validateShippingAndTaxes() {
        $this->validatePermission();
        
        if (empty($this->request->post['advertise_google_shipping_taxes']['min_transit_time']) || !is_numeric($this->request->post['advertise_google_shipping_taxes']['min_transit_time']) || (int)$this->request->post['advertise_google_shipping_taxes']['min_transit_time'] < 0) {
            $this->error['min_transit_time'] = $this->language->get('error_min_transit_time');
        } else if (empty($this->request->post['advertise_google_shipping_taxes']['max_transit_time']) || !is_numeric($this->request->post['advertise_google_shipping_taxes']['max_transit_time']) || (int)$this->request->post['advertise_google_shipping_taxes']['max_transit_time'] < (int)$this->request->post['advertise_google_shipping_taxes']['min_transit_time']) {
            $this->error['max_transit_time'] = $this->language->get('error_max_transit_time');
        }

        switch ($this->request->post['advertise_google_shipping_taxes']['shipping_type']) {
            case 'flat' : 
                if (!isset($this->request->post['advertise_google_shipping_taxes']['flat_rate']) || !is_numeric($this->request->post['advertise_google_shipping_taxes']['flat_rate']) || (float)$this->request->post['advertise_google_shipping_taxes']['flat_rate'] <= 0) {
                    $this->error['flat_rate'] = $this->language->get('error_flat_rate');
                }
            break;
            case 'carrier' :
                if (empty($this->request->post['advertise_google_shipping_taxes']['carrier'])) {
                    $this->error['warning'] = $this->language->get('error_carrier');
                }

                if (empty($this->request->post['advertise_google_shipping_taxes']['carrier_postcode'])) {
                    $this->error['carrier_postcode'] = $this->language->get('error_carrier_postcode');
                }

                if (!isset($this->request->post['advertise_google_shipping_taxes']['carrier_price_percentage']) || !is_numeric($this->request->post['advertise_google_shipping_taxes']['carrier_price_percentage']) || (float)$this->request->post['advertise_google_shipping_taxes']['carrier_price_percentage'] < 0 || (float)$this->request->post['advertise_google_shipping_taxes']['carrier_price_percentage'] > 100) {
                    $this->error['carrier_price_percentage'] = $this->language->get('error_carrier_price_percentage');
                }
            break;
        }

        switch ($this->request->post['advertise_google_shipping_taxes']['tax_type']) {
            case 'usa' :
                if (empty($this->request->post['advertise_google_shipping_taxes']['tax'])) {
                    $this->error['warning'] = $this->language->get('error_tax');
                }
            break;
        }

        if (!isset($this->error['warning']) && $this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateMapping() {
        $this->validatePermission();
        
        if (!isset($this->error['warning']) && $this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateProduct($required_fields) {
        if (!$this->user->hasPermission('modify', 'extension/advertise/google')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->error)) {
            foreach ($required_fields as $key => $requirements) {
                if (empty($requirements['selected_field']) && (!isset($this->request->post['form'][$key]) || $this->request->post['form'][$key] == '')) {
                    $this->error[$key] = $this->language->get('error_field_no_value');
                } else if (!empty($requirements['selected_field'])) {
                    foreach ($requirements['selected_field'] as $dependency => $values) {
                        if (in_array($this->request->post['form'][$dependency], $values) && (!isset($this->request->post['form'][$key]) || $this->request->post['form'][$key] == '')) {
                            $this->error[$key] = $this->language->get('error_field_no_value');
                        }
                    }
                }
            }
        }

        if (!isset($this->error['warning']) && $this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validatePermission() {
        if (!$this->user->hasPermission('modify', 'extension/advertise/google')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    protected function validateCampaign() {
        $this->validatePermission();

        $this->load->model('extension/advertise/google');

        $targets = $this->googleshopping->getTargets($this->store_id);

        if (empty($targets)) {
            $this->error['warning'] = $this->language->get('error_no_targets');
        }

        if (!isset($this->error['warning']) && $this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateConnect() {
        $this->validatePermission();

        if (!isset($this->request->post['advertise_google_app_id']) || trim($this->request->post['advertise_google_app_id']) == '') {
            $this->error['app_id'] = $this->language->get('error_empty_app_id');
        } else if ($this->model_extension_advertise_google->isAppIdUsed($this->request->post['advertise_google_app_id'], $this->store_id)) {
            $this->error['app_id'] = $this->language->get('error_used_app_id');
        }

        if (!isset($this->request->post['advertise_google_app_secret']) || trim($this->request->post['advertise_google_app_secret']) == '') {
            $this->error['app_secret'] = $this->language->get('error_empty_app_secret');
        }

        if (!isset($this->error['warning']) && $this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateTarget() {
        $this->validatePermission();

        if (!isset($this->request->post['budget']) || !is_numeric($this->request->post['budget']) || (float)$this->request->post['budget'] < 5) {
            $this->error['budget'] = $this->language->get('error_budget');
        }

        if (empty($this->request->post['feed']) || !is_array($this->request->post['feed'])) {
            $this->error['feed'] = $this->language->get('error_empty_feed');
        } else {
            foreach ($this->request->post['feed'] as $feed) {
                if (empty($feed['language']) || empty($feed['currency'])) {
                    $this->error['feed'] = $this->language->get('error_invalid_feed');
                    break;
                }
            }
        }

        if (empty($this->request->post['country'])) {
            $this->error['country'] = $this->language->get('error_empty_country');
        }

        if (empty($this->request->post['campaign_name']) || trim($this->request->post['campaign_name']) == '') {
            $this->error['campaign_name'] = $this->language->get('error_empty_campaign_name');
        } else {
            $disallowed_names = [];

            $this->load->model('extension/advertise/google');

            foreach ($this->googleshopping->getTargets($this->store_id) as $existing_target) {
                if (isset($this->request->get['advertise_google_target_id']) && $existing_target['target_id'] == $this->request->get['advertise_google_target_id']) {
                    // Ignore this target as it is currntly being edited
                    continue;
                }

                $disallowed_names[] = strtolower(str_replace('&#44;', ',', trim($existing_target['campaign_name'])));
            }

            if (in_array(trim(strtolower($this->request->post['campaign_name'])), $disallowed_names)) {
                $this->error['campaign_name'] = $this->language->get('error_campaign_name_in_use');
            }

            if (strtolower(trim($this->request->post['campaign_name'])) == 'total') {
                $this->error['campaign_name'] = $this->language->get('error_campaign_name_total');
            }
        }

        if (!isset($this->error['warning']) && $this->error) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }
}