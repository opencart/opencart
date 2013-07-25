<?php
class ControllerOpenbayOpenbay extends Controller {
    public function install() {
        $this->load->language('openbay/openbay');
        $this->load->model('ebay/openbay');
        $this->load->model('setting/setting');
        $this->load->model('setting/extension');

        $this->model_ebay_openbay->install();
        $this->model_setting_extension->install('openbay', $this->request->get['extension']);
    }

    public function uninstall() {
        $this->load->model('ebay/openbay');
        $this->load->model('setting/setting');
        $this->load->model('setting/extension');

        $this->model_ebay_openbay->uninstall();
        $this->model_setting_extension->uninstall('openbay', $this->request->get['extension']);
        $this->model_setting_setting->deleteSetting($this->request->get['extension']);
    }

    public function index() {
        $this->data = array_merge($this->data, $this->load->language('ebay/overview'));

        $this->document->setTitle($this->language->get('lang_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $this->data['validation']               = $this->ebay->validate();
        $this->data['links_settings']           = $this->url->link('openbay/openbay/settings', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_itemlink']           = $this->url->link('openbay/openbay/viewItemLinks', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_addons']             = $this->url->link('openbay/openbay/viewAddons', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_subscribe']          = $this->url->link('openbay/openbay/viewSubscription', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_itemimport']         = $this->url->link('openbay/openbay/viewItemImport', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_orderimport']        = $this->url->link('openbay/openbay/viewOrderImport', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_usage']              = $this->url->link('openbay/openbay/viewUsage', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_sync']               = $this->url->link('openbay/openbay/viewSync', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_stockreport']        = $this->url->link('openbay/openbay/viewStockReport', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_linkmaintenance']    = $this->url->link('openbay/openbay/viewItemLinkMaintenance', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_summary']            = $this->url->link('openbay/openbay/viewSellerSummary', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_profile']            = $this->url->link('ebay/profile/profileAll', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['links_template']           = $this->url->link('ebay/template/listAll', 'token=' . $this->session->data['token'], 'SSL');

        $this->template = 'ebay/overview.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function settings() {
        $this->data = array_merge($this->data, $this->load->language('ebay/settings'));

        $this->load->model('setting/setting');
        $this->load->model('ebay/openbay');
        $this->load->model('localisation/currency');
        $this->load->model('localisation/order_status');
        $this->load->model('sale/customer_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('openbay', $this->request->post);
            $this->session->data['success'] = $this->language->get('lang_text_success');
            $this->redirect($this->url->link('openbay/openbay&token=' . $this->session->data['token']));
        }

        $this->document->setTitle($this->language->get('lang_heading_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/settings', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_settings'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('openbay/openbay/settings', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');

        /*
         *  Currency Import 
         */
        if (isset($this->request->post['openbay_def_currency'])) {
            $this->data['openbay_def_currency'] = $this->request->post['openbay_def_currency'];
        } else {
            $this->data['openbay_def_currency'] = $this->config->get('openbay_def_currency');
        }
        $this->data['currency_list'] = $this->model_localisation_currency->getCurrencies();

        /*
         *  Customer Import
         */
        if (isset($this->request->post['openbay_def_customer_grp'])) {
            $this->data['openbay_def_customer_grp'] = $this->request->post['openbay_def_customer_grp'];
        } else {
            $this->data['openbay_def_customer_grp'] = $this->config->get('openbay_def_customer_grp');
        }
        $this->data['customer_grp_list'] = $this->model_sale_customer_group->getCustomerGroups();

        $this->data['token'] = $this->session->data['token'];

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->request->post['openbay_status'])) {
            $this->data['openbay_status'] = $this->request->post['openbay_status'];
        } else {
            $this->data['openbay_status'] = $this->config->get('openbay_status');
        }
        if (isset($this->request->post['openbaypro_token'])) {
            $this->data['openbaypro_token'] = $this->request->post['openbaypro_token'];
        } else {
            $this->data['openbaypro_token'] = $this->config->get('openbaypro_token');
        }
        if (isset($this->request->post['openbaypro_secret'])) {
            $this->data['openbaypro_secret'] = $this->request->post['openbaypro_secret'];
        } else {
            $this->data['openbaypro_secret'] = $this->config->get('openbaypro_secret');
        }
        if (isset($this->request->post['openbaypro_string1'])) {
            $this->data['openbaypro_string1'] = $this->request->post['openbaypro_string1'];
        } else {
            $this->data['openbaypro_string1'] = $this->config->get('openbaypro_string1');
        }
        if (isset($this->request->post['openbaypro_string2'])) {
            $this->data['openbaypro_string2'] = $this->request->post['openbaypro_string2'];
        } else {
            $this->data['openbaypro_string2'] = $this->config->get('openbaypro_string2');
        }
        if (isset($this->request->post['openbaypro_enditems'])) {
            $this->data['openbaypro_enditems'] = $this->request->post['openbaypro_enditems'];
        } else {
            $this->data['openbaypro_enditems'] = $this->config->get('openbaypro_enditems');
        }
        if (isset($this->request->post['openbaypro_relistitems'])) {
            $this->data['openbaypro_relistitems'] = $this->request->post['openbaypro_relistitems'];
        } else {
            $this->data['openbaypro_relistitems'] = $this->config->get('openbaypro_relistitems');
        }
        if (isset($this->request->post['openbaypro_logging'])) {
            $this->data['openbaypro_logging'] = $this->request->post['openbaypro_logging'];
        } else {
            $this->data['openbaypro_logging'] = $this->config->get('openbaypro_logging');
        }
        if (isset($this->request->post['openbaypro_created_hours'])) {
            $this->data['openbaypro_created_hours'] = $this->request->post['openbaypro_created_hours'];
        } else {
            $this->data['openbaypro_created_hours'] = $this->config->get('openbaypro_created_hours');
        }
        if (isset($this->request->post['openbaypro_time_offset'])) {
            $this->data['openbaypro_time_offset'] = $this->request->post['openbaypro_time_offset'];
        } else {
            $this->data['openbaypro_time_offset'] = $this->config->get('openbaypro_time_offset');
        }

        /**
         * notification settings
         */
        if (isset($this->request->post['openbaypro_update_notify'])) {
            $this->data['openbaypro_update_notify'] = $this->request->post['openbaypro_update_notify'];
        } else {
            $this->data['openbaypro_update_notify'] = $this->config->get('openbaypro_update_notify');
        }
        if (isset($this->request->post['openbaypro_confirm_notify'])) {
            $this->data['openbaypro_confirm_notify'] = $this->request->post['openbaypro_confirm_notify'];
        } else {
            $this->data['openbaypro_confirm_notify'] = $this->config->get('openbaypro_confirm_notify');
        }
        if (isset($this->request->post['openbaypro_confirmadmin_notify'])) {
            $this->data['openbaypro_confirmadmin_notify'] = $this->request->post['openbaypro_confirmadmin_notify'];
        } else {
            $this->data['openbaypro_confirmadmin_notify'] = $this->config->get('openbaypro_confirmadmin_notify');
        }
        if (isset($this->request->post['openbaypro_email_brand_disable'])) {
            $this->data['openbaypro_email_brand_disable'] = $this->request->post['openbaypro_email_brand_disable'];
        } else {
            $this->data['openbaypro_email_brand_disable'] = $this->config->get('openbaypro_email_brand_disable');
        }
        if (isset($this->request->post['openbaypro_ebay_itm_link'])) {
            $this->data['openbaypro_ebay_itm_link'] = $this->request->post['openbaypro_ebay_itm_link'];
        } else {
            $this->data['openbaypro_ebay_itm_link'] = $this->config->get('openbaypro_ebay_itm_link');
        }

        /**
         * stock allocation
         */
        if (isset($this->request->post['openbaypro_stock_allocate'])) {
            $this->data['openbaypro_stock_allocate'] = $this->request->post['openbaypro_stock_allocate'];
        } else {
            $this->data['openbaypro_stock_allocate'] = $this->config->get('openbaypro_stock_allocate');
        }
        if (isset($this->request->post['openbaypro_create_date'])) {
            $this->data['openbaypro_create_date'] = $this->request->post['openbaypro_create_date'];
        } else {
            $this->data['openbaypro_create_date'] = $this->config->get('openbaypro_create_date');
        }

        /**
         * stock reports
         */
        if (isset($this->request->post['openbaypro_stock_report'])) {
            $this->data['openbaypro_stock_report'] = $this->request->post['openbaypro_stock_report'];
        } else {
            $this->data['openbaypro_stock_report'] = $this->config->get('openbaypro_stock_report');
        }
        if (isset($this->request->post['openbaypro_stock_report_summary'])) {
            $this->data['openbaypro_stock_report_summary'] = $this->request->post['openbaypro_stock_report_summary'];
        } else {
            $this->data['openbaypro_stock_report_summary'] = $this->config->get('openbaypro_stock_report_summary');
        }

        $this->data['durations'] = array(
            'Days_1' => $this->data['lang_listing_1day'],
            'Days_3' => $this->data['lang_listing_3day'],
            'Days_5' => $this->data['lang_listing_5day'],
            'Days_7' => $this->data['lang_listing_7day'],
            'Days_10' => $this->data['lang_listing_10day'],
            'Days_30' => $this->data['lang_listing_30day'],
            'GTC' => $this->data['lang_listing_gtc']
        );
        if (isset($this->request->post['openbaypro_duration'])) {
            $this->data['openbaypro_duration'] = $this->request->post['openbaypro_duration'];
        } else {
            $this->data['openbaypro_duration'] = $this->config->get('openbaypro_duration');
        }

        if (isset($this->request->post['openbay_default_addressformat'])) {
            $this->data['openbay_default_addressformat'] = $this->request->post['openbay_default_addressformat'];
        } else {
            $this->data['openbay_default_addressformat'] = $this->config->get('openbay_default_addressformat');
        }

        /*
         * Payments & tax
         */
        $this->data['payment_options'] = $this->model_ebay_openbay->getPaymentTypes();

        if (isset($this->request->post['ebay_payment_types'])) {
            $this->data['ebay_payment_types'] = $this->request->post['ebay_payment_types'];
        } else {
            $this->data['ebay_payment_types'] = $this->config->get('ebay_payment_types');
        }

        if (isset($this->request->post['field_payment_instruction'])) {
            $this->data['field_payment_instruction'] = $this->request->post['field_payment_instruction'];
        } else {
            $this->data['field_payment_instruction'] = $this->config->get('field_payment_instruction');
        }
        if (isset($this->request->post['field_payment_paypal_address'])) {
            $this->data['field_payment_paypal_address'] = $this->request->post['field_payment_paypal_address'];
        } else {
            $this->data['field_payment_paypal_address'] = $this->config->get('field_payment_paypal_address');
        }
        if (isset($this->request->post['payment_immediate'])) {
            $this->data['payment_immediate'] = $this->request->post['payment_immediate'];
        } else {
            $this->data['payment_immediate'] = $this->config->get('payment_immediate');
        }
        if (isset($this->request->post['ebay_tax_listing'])) {
            $this->data['ebay_tax_listing'] = $this->request->post['ebay_tax_listing'];
        } else {
            $this->data['ebay_tax_listing'] = $this->config->get('ebay_tax_listing');
        }
        if (isset($this->request->post['tax'])) {
            $this->data['tax'] = $this->request->post['tax'];
        } else {
            $this->data['tax'] = $this->config->get('tax');
        }

        if (isset($this->request->post['ebay_import_unpaid'])) {
            $this->data['ebay_import_unpaid'] = $this->request->post['ebay_import_unpaid'];
        } else {
            $this->data['ebay_import_unpaid'] = $this->config->get('ebay_import_unpaid');
        }

        if (isset($this->request->post['EBAY_DEF_PARTIAL_REFUND_ID'])) {
            $this->data['EBAY_DEF_PARTIAL_REFUND_ID'] = $this->request->post['EBAY_DEF_PARTIAL_REFUND_ID'];
        } else {
            $this->data['EBAY_DEF_PARTIAL_REFUND_ID'] = $this->config->get('EBAY_DEF_PARTIAL_REFUND_ID');
        }
        if (isset($this->request->post['EBAY_DEF_IMPORT_ID'])) {
            $this->data['EBAY_DEF_IMPORT_ID'] = $this->request->post['EBAY_DEF_IMPORT_ID'];
        } else {
            $this->data['EBAY_DEF_IMPORT_ID'] = $this->config->get('EBAY_DEF_IMPORT_ID');
        }
        if (isset($this->request->post['EBAY_DEF_PAID_ID'])) {
            $this->data['EBAY_DEF_PAID_ID'] = $this->request->post['EBAY_DEF_PAID_ID'];
        } else {
            $this->data['EBAY_DEF_PAID_ID'] = $this->config->get('EBAY_DEF_PAID_ID');
        }
        if (isset($this->request->post['EBAY_DEF_SHIPPED_ID'])) {
            $this->data['EBAY_DEF_SHIPPED_ID'] = $this->request->post['EBAY_DEF_SHIPPED_ID'];
        } else {
            $this->data['EBAY_DEF_SHIPPED_ID'] = $this->config->get('EBAY_DEF_SHIPPED_ID');
        }
        if (isset($this->request->post['EBAY_DEF_CANCELLED_ID'])) {
            $this->data['EBAY_DEF_CANCELLED_ID'] = $this->request->post['EBAY_DEF_CANCELLED_ID'];
        } else {
            $this->data['EBAY_DEF_CANCELLED_ID'] = $this->config->get('EBAY_DEF_CANCELLED_ID');
        }
        if (isset($this->request->post['EBAY_DEF_REFUNDED_ID'])) {
            $this->data['EBAY_DEF_REFUNDED_ID'] = $this->request->post['EBAY_DEF_REFUNDED_ID'];
        } else {
            $this->data['EBAY_DEF_REFUNDED_ID'] = $this->config->get('EBAY_DEF_REFUNDED_ID');
        }

        $this->data['api_server']       = $this->ebay->getApiServer();
        $this->data['order_statuses']   = $this->model_localisation_order_status->getOrderStatuses();

        $this->template = 'ebay/settings.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function loadSettings() {
        set_time_limit(0);
        $this->response->setOutput(json_encode($this->ebay->loadSettings()));
    }

    public function loadCategories() {
        set_time_limit(0);
        $this->response->setOutput(json_encode($this->ebay->loadCategories()));
    }

    public function loadSellerStore() {
        set_time_limit(0);
        $this->response->setOutput(json_encode($this->ebay->loadSellerStore()));
    }

    public function getCategories() {
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->getCategory($this->request->get['parent']);
        $this->response->setOutput(json_encode($data));
    }

    public function getSuggestedCategories() {
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->getSuggestedCategories($this->request->get['qry']);
        $this->response->setOutput(json_encode($data));
    }

    public function getShippingService() {
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->getShippingService($this->request->get['loc']);
        $this->response->setOutput(json_encode($data));
    }

    public function getEbayCategorySpecifics() {
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->getEbayCategorySpecifics($this->request->get['category']);
        $this->response->setOutput(json_encode($data));
    }

    public function getCategoryFeatures() {
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->getCategoryFeatures($this->request->get['category']);
        $this->response->setOutput(json_encode($data));
    }

    public function searchEbayCatalog(){
        $this->load->model('ebay/product');
        $data = $this->model_ebay_product->searchEbayCatalog($this->request->post);
        $this->response->setOutput(json_encode($data));
    }

    public function viewSellerSummary() {
        $this->data = array_merge($this->data, $this->load->language('ebay/summary'));

        $this->document->setTitle($this->language->get('lang_heading'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/viewSellerSummary', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->data['lang_heading'],
            'separator' => ' :: '
        );

        $this->data['return'] = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['validation'] = $this->ebay->validate();
        $this->data['token'] = $this->session->data['token'];

        $this->template = 'ebay/summary.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function getSellerSummary() {
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->getSellerSummary();
        $this->response->setOutput(json_encode($data));
    }

    public function verifyCreds() {
        $this->load->model('ebay/openbay');
        $response = $this->model_ebay_openbay->verifyCreds();
        $this->response->setOutput(json_encode($response));
    }

    public function importItems() {
        $data = array('d' => $this->request->get['desc'], 'c' => 1, 'n' => $this->request->get['note']);
        $this->ebay->openbay_call_noresponse('setup/getItemsMain/', $data);
        $this->response->setOutput(json_encode(array('msg' => 'ok')));
    }

    public function importOrdersManual() {
        $this->ebay->openbay_call_noresponse('order/getOrdersManual/');
        $this->response->setOutput(json_encode(array('msg' => 'ok')));
    }

    public function getProductStock() {
        $this->load->model('ebay/openbay');
        $response = $this->model_ebay_openbay->getProductStock($this->request->get['pid']);
        return $this->response->setOutput(json_encode($response));
    }

    public function setProductStock() {
        $this->load->model('ebay/openbay');
        $this->load->model('catalog/product');

        $product_id = $this->request->get['product_id'];
        $data       = $this->model_catalog_product->getProduct($product_id);

        $this->ebay->log('User updating eBay stock of product id: ' . $product_id);

        $this->ebay->productUpdateListen($product_id, $data);

        $this->response->setOutput(json_encode(array('msg' => 'ok', 'error' => false)));
    }

    public function getUsage() {
        $this->load->model('ebay/openbay');
        $data               = $this->model_ebay_openbay->getUsage();
        $data['html']       = base64_decode($data['html']);
        $data['lasterror']  = $this->ebay->lasterror;
        $data['lastmsg']    = $this->ebay->lastmsg;
        return $this->response->setOutput(json_encode($data));
    }

    public function getPlans() {
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->getPlans();
        return $this->response->setOutput(json_encode($data));
    }

    public function getMyPlan() {
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->getMyPlan();
        return $this->response->setOutput(json_encode($data));
    }

    public function devClear() {
        if ($this->request->post['pass'] == '') {
            return $this->response->setOutput(json_encode(array('msg' => 'Password needed')));
        } else {

            if ($this->request->post['pass'] != $this->config->get('openbaypro_secret')) {
                return $this->response->setOutput(json_encode(array('msg' => 'Wrong password')));
            } else {
                /*
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "order`");
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "order_history`");
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "order_option`");
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "order_product`");
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "order_total`");
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "customer`");
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "customer_transaction`");
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "address`");
                  $this->db->query("TRUNCATE `" . DB_PREFIX . "ebay_transaction`");
                 */
                $this->db->query("TRUNCATE `" . DB_PREFIX . "manufacturer`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "manufacturer_to_store`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "attribute`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "attribute_description`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "attribute_group`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "attribute_group_description`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "ebay_listing`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "category`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "category_description`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "category_to_store`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "product`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "product_to_store`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "product_description`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "product_attribute`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "product_option`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "product_option_value`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "product_image`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "product_to_category`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "option`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "option_description`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "option_value`");
                $this->db->query("TRUNCATE `" . DB_PREFIX . "option_value_description`");

                if ($this->ebay->addonLoad('openstock') == true) {
                    $this->db->query("TRUNCATE `" . DB_PREFIX . "product_option_relation`");
                }

                return $this->response->setOutput(json_encode(array('msg' => 'Data cleared')));
            }
        }
    }

    public function viewSubscription() {
        $this->data = array_merge($this->data, $this->load->language('ebay/subscription'));

        $this->document->setTitle($this->language->get('lang_page_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/viewAddons', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        $this->data['return']       = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['validation']   = $this->ebay->validate();
        $this->data['token']        = $this->session->data['token'];
        $this->data['obp_token']    = $this->config->get('openbaypro_token');

        $this->template = 'ebay/subscription.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function viewAddons() {
        $this->data = array_merge($this->data, $this->load->language('ebay/addons'));
        $this->document->setTitle($this->language->get('lang_page_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/viewAddons', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        $this->data['return'] = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['addons'] = $this->ebay->addonList();
        $this->data['validation'] = $this->ebay->validate();

        $this->template = 'ebay/addons.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function viewItemImport() {
        $this->load->model('ebay/product');

        $this->data = array_merge($this->data, $this->load->language('ebay/item_import'));

        $this->document->setTitle($this->language->get('lang_page_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/viewItemImport', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        $this->data['return']           = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['validation']       = $this->ebay->validate();
        $this->data['token']            = $this->session->data['token'];
        $this->data['imgImport']        = $this->model_ebay_product->countImportImages();
        $this->data['imgImportLink']    = $this->url->link('openbay/openbay/getImportImages', 'token=' . $this->session->data['token'], 'SSL');


        $this->template = 'ebay/item_import.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function viewStockReport() {
        $this->data = array_merge($this->data, $this->load->language('ebay/stock_report'));

        $this->document->setTitle($this->language->get('lang_page_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/viewStockReport', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        $this->data['return']       = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['validation']   = $this->ebay->validate();
        $this->data['token']        = $this->session->data['token'];

        $this->template = 'ebay/stock_report.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function viewOrderImport() {
        $this->data = array_merge($this->data, $this->load->language('ebay/order_import'));

        $this->document->setTitle($this->language->get('lang_page_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/viewOrderImport', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        $this->data['return']       = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['validation']   = $this->ebay->validate();
        $this->data['token']        = $this->session->data['token'];

        $this->template = 'ebay/order_import.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function viewSync() {
        $this->data = array_merge($this->data, $this->load->language('ebay/syncronise'));

        $this->document->setTitle($this->language->get('lang_page_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/viewSync', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        $this->data['return']       = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['validation']   = $this->ebay->validate();
        $this->data['token']        = $this->session->data['token'];

        $this->template = 'ebay/syncronise.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function viewItemLinks() {
        $this->data = array_merge($this->data, $this->load->language('ebay/item_link'));

        $this->document->setTitle($this->language->get('lang_page_title'));
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_openbay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_ebay'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('openbay/openbay/viewItemLinks', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('lang_heading'),
            'separator' => ' :: '
        );

        $this->data['return']       = $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['validation']   = $this->ebay->validate();
        $this->data['token']        = $this->session->data['token'];
        $this->data['items']        = $this->ebay->getLiveProducts();

        $this->template = 'ebay/item_link.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    public function loadItemLinks() {
        set_time_limit(0);
        $this->load->model('ebay/openbay');
        $data = $this->model_ebay_openbay->loadItemLinks();

        $json = array('msg' => 'ok');

        if (!empty($data)) {
            $json['data'] = $data;
        } else {
            $json['data'] = null;
        }

        $this->response->setOutput(json_encode($json));
    }

    public function saveItemLink() {
        $this->load->model('ebay/openbay');
        $response = $this->model_ebay_openbay->saveItemLink($this->request->get);
        $this->response->setOutput(json_encode($response));
    }

    public function removeItemLink() {
        $this->load->language('openbay/openbay');
        $this->ebay->removeItemId($this->request->get['ebay_id']);
        $this->response->setOutput(json_encode(array('error' => false, 'msg' => $this->language->get('item_link_removed'))));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'openbay/openbay')) {
            $this->error['warning'] = $this->language->get('invalid_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    private function checkConfig() {
        if ($this->config->get('openbaypro_token') == '' || $this->config->get('openbaypro_secret') == '') {
            return false;
        } else {
            return true;
        }
    }

    public function getStockCheck() {
        $this->ebay->log('Requesting stock report.');
        $this->load->model('ebay/openbay');
        $this->model_ebay_openbay->getStockCheck();
    }

    public function edit() {
        if ($this->checkConfig() == true) {
            if (!empty($this->request->get['product_id'])) {
                $this->data = array_merge($this->data, $this->load->language('ebay/edit'));

                $this->load->model('catalog/product');
                $this->load->model('tool/image');
                $this->load->model('catalog/manufacturer');
                $this->load->model('ebay/openbay');
                $this->load->model('ebay/product');

                $this->document->setTitle($this->data['lang_page_title']);
                $this->document->addStyle('view/stylesheet/openbay.css');
                $this->document->addScript('view/javascript/openbay/faq.js');

                $this->template = 'ebay/edit.tpl';

                $this->children = array(
                    'common/header',
                    'common/footer'
                );

                $this->data['action']       = $this->url->link('openbay/openbay/create', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['cancel']       = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['view_link']    = $this->config->get('openbaypro_ebay_itm_link') . $this->ebay->getEbayItemId($this->request->get['product_id']);
                $this->data['token']        = $this->session->data['token'];
                $this->data['product_id']   = $this->request->get['product_id'];

                $this->data['breadcrumbs'] = array();

                $this->data['breadcrumbs'][] = array(
                    'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
                    'text' => $this->language->get('lang_home'),
                    'separator' => FALSE
                );

                $this->data['breadcrumbs'][] = array(
                    'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
                    'text' => $this->language->get('lang_title'),
                    'separator' => ' :: '
                );

                $this->data['breadcrumbs'][] = array(
                    'href' => $this->url->link('openbay/openbay', 'token=' . $this->session->data['token'], 'SSL'),
                    'text' => $this->language->get('lang_ebay'),
                    'separator' => ' :: '
                );

                $this->data['breadcrumbs'][] = array(
                    'href' => $this->url->link('openbay/openbay/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'], 'SSL'),
                    'text' => $this->language->get('lang_pageaction'),
                    'separator' => ' :: '
                );

                $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
            } else {
                $this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'], 'SSL'));
            }
        }
    }

    public function editLoad() {
        $this->load->model('catalog/product');
        $this->load->model('ebay/product');
        $this->load->model('tool/image');

        $item_id        = $this->ebay->getEbayItemId($this->request->get['product_id']);
        $product_info   = $this->model_catalog_product->getProduct($this->request->get['product_id']);

        if (!empty($item_id)) {
            $listings   = $this->ebay->getEbayListing($item_id);
            $stock      = $this->ebay->getProductStockLevel($this->request->get['product_id']);
            $reserve    = $this->ebay->getReserve($this->request->get['product_id'], $item_id);
            $options    = array();

            $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

            if ($this->ebay->addonLoad('openstock') == true && $product_info['has_option'] == 1) {
                $this->load->model('openstock/openstock');
                $this->data['addon']['openstock'] = true;
                $product_info['options'] = $this->model_openstock_openstock->getProductOptionStocks($this->request->get['product_id']);
                $product_info['option_grp'] = $this->model_ebay_product->getProductOptions($this->request->get['product_id']);

                $t = array();
                $t_rel = array();

                foreach($product_info['option_grp'] as $key => $grp){
                    $t_tmp = array();

                    foreach($grp['product_option_value'] as $grp_node){
                        $t_tmp[$grp_node['option_value_id']] = $grp_node['name'];
                        $t_rel[$grp_node['product_option_value_id']] = $grp['name'];
                    }

                    $t[] = array('name' => $grp['name'], 'child' => $t_tmp);
                }

                if(!isset($listings['variations']['Variation'][1])){
                    $listings['variations']['Variation'] = array($listings['variations']['Variation']);
                }

                foreach($product_info['options'] as $option){
                    $option['base64']   = base64_encode(serialize($option['opts']));
                    $optionReserve      = $this->ebay->getReserve($this->request->get['product_id'], $item_id, $option['var']);
                    if($optionReserve == false){
                        $option['reserve'] = 0;
                    }else{
                        $option['reserve']  = $this->ebay->getReserve($this->request->get['product_id'], $item_id, $option['var']);
                    }

                    $ebay_listing = '';

                    foreach($listings['variations']['Variation'] as $listing){

                        $sku = (isset($listing['SKU']) ? $listing['SKU'] : '');

                        if($sku != '' && $sku == $option['var']){
                            $listing['StartPrice'] = number_format($listing['StartPrice'], 2);
                            $listing['Quantity'] = $listing['Quantity'] - $listing['SellingStatus']['QuantitySold'];

                            $ebay_listing = $listing;
                        }
                    }

                    $options[] = array('ebay' => $ebay_listing, 'local' => $option, 'var' => $option['var']);
                }


                //unset variants that dont appear on eBay
                $notlive = array();
                foreach($options as $k => $option){
                    if(empty($option['ebay'])){
                        $notlive[] = $options[$k];
                        unset($options[$k]);
                    }
                }

                $variant = array(
                    'variant' => 1,
                    'data' => array(
                        'grp_info' => array(
                            'optGroupArray'     => base64_encode(serialize($t)),
                            'optGroupRelArray'  => base64_encode(serialize($t_rel)),
                        ),
                        'options' => $options,
                        'optionsinactive' => $notlive
                    )
                );

            }else{
                $variant = array('variant' => 0, 'data' => '');
            }

            $this->data['product'] = $product_info;

            if($reserve == false){ $reserve = 0; }


            $data       = array(
                'listing'   => $listings,
                'stock'     => $stock,
                'reserve'   => $reserve,
                'variant'   => $variant
            );

            if (!empty($listings)) {
                return $this->response->setOutput(json_encode(array('error' => false, 'data' => $data)));
            } else {
                return $this->response->setOutput(json_encode(array('error' => true)));
            }
        } else {
            return $this->response->setOutput(json_encode(array('error' => true)));
        }
    }

    public function editSave() {
        if ($this->checkConfig() == true && $this->request->server['REQUEST_METHOD'] == 'POST') {

            $this->load->model('ebay/openbay');

            $save = $this->model_ebay_openbay->editSave($this->request->post);

            $this->response->setOutput(json_encode($save));
        } else {
            $this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'], 'SSL'));
        }
    }

    public function create() {
        if ($this->checkConfig() == true) {
            if (!empty($this->request->get['product_id'])) {
                //load the language
                $this->data = array_merge($this->data, $this->load->language('ebay/new'));

                //load the models
                $this->load->model('catalog/product');
                $this->load->model('tool/image');
                $this->load->model('catalog/manufacturer');
                $this->load->model('ebay/openbay');
                $this->load->model('ebay/template');
                $this->load->model('ebay/product');
                $this->load->model('ebay/profile');

                //set the title and page info
                $this->document->setTitle($this->data['lang_page_title']);
                $this->document->addStyle('view/stylesheet/openbay.css');
                $this->document->addScript('view/javascript/openbay/faq.js');

                $this->template         = 'ebay/new.tpl';
                $this->children         = array('common/header','common/footer');
                $this->data['action']   = $this->url->link('openbay/openbay/create', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['cancel']   = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'], 'SSL');
                $this->data['token']    = $this->session->data['token'];
                $product_info           = $this->model_catalog_product->getProduct($this->request->get['product_id']);

                if ($this->ebay->addonLoad('openstock') == true && $product_info['has_option'] == 1) {
                    $this->load->model('openstock/openstock');
                    $this->data['addon']['openstock'] = true;
                    $product_info['options'] = $this->model_openstock_openstock->getProductOptionStocks($this->request->get['product_id']);
                    $product_info['option_grp'] = $this->model_ebay_product->getProductOptions($this->request->get['product_id']);
                }

                // get the product tax rate from opencart
                if (isset($product_info['tax_class_id'])) {
                    $product_info['defaults']['tax'] = $this->model_ebay_product->getTaxRate($product_info['tax_class_id']);
                } else {
                    $product_info['defaults']['tax'] = 0.00;
                }

                //get the popular categories the user has used
                $product_info['popular_cats'] = $this->model_ebay_openbay->getPopularCategories();

                //get shipping profiles
                $product_info['profiles_shipping'] = $this->model_ebay_profile->getAll(0);
                //get default shipping profile
                $product_info['profiles_shipping_def'] = $this->model_ebay_profile->getDefault(0);

                //get returns profiles
                $product_info['profiles_returns'] = $this->model_ebay_profile->getAll(1);
                //get default returns profile
                $product_info['profiles_returns_def'] = $this->model_ebay_profile->getDefault(1);
                $this->data['data']['shipping_international_zones']     = $this->model_ebay_openbay->getShippingLocations();

                //get theme profiles
                $product_info['profiles_theme'] = $this->model_ebay_profile->getAll(2);
                //get default returns profile
                $product_info['profiles_theme_def'] = $this->model_ebay_profile->getDefault(2);

                //get generic profiles
                $product_info['profiles_generic'] = $this->model_ebay_profile->getAll(3);
                //get default generic profile
                $product_info['profiles_generic_def'] = $this->model_ebay_profile->getDefault(3);

                //product attributes - this is just a direct pass through used with the template tag
                $product_info['attributes'] = base64_encode(json_encode($this->model_ebay_openbay->getProductAttributes($this->request->get['product_id'])));

                //post edit link
                $product_info['edit_link'] = $this->url->link('openbay/openbay/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'], 'SSL');

                //images
                $product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
                $product_info['product_images'] = array();

                if (!empty($product_info['image'])) {
                    $product_info['product_images'][] = array(
                        'image' => $product_info['image'],
                        'preview' => $this->model_tool_image->resize($product_info['image'], 100, 100),
                        'full' => HTTPS_CATALOG . 'image/' . $product_info['image']
                    );
                }

                foreach ($product_images as $product_image) {
                    if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
                        $image = $product_image['image'];
                    } else {
                        $image = 'no_image.jpg';
                    }

                    $product_info['product_images'][] = array(
                        'image' => $image,
                        'preview' => $this->model_tool_image->resize($image, 100, 100),
                        'full' => HTTPS_CATALOG . 'image/' . $image
                    );
                }

                $product_info['manufacturers']                      = $this->model_catalog_manufacturer->getManufacturers();
                $product_info['payments']                           = $this->model_ebay_openbay->getPaymentTypes();
                $product_info['templates']                          = $this->model_ebay_template->getAll();
                $product_info['store_cats']                         = $this->model_ebay_openbay->getSellerStoreCategories();

                $product_info['dispatchTimes']                      = $this->ebay->getSetting('dispatch_time_max');
                if(is_array($product_info['dispatchTimes'])){ ksort($product_info['dispatchTimes']); }

                $product_info['defaults']['ebay_payment_types']     = $this->config->get('ebay_payment_types');
                $product_info['defaults']['paypal_address']         = $this->config->get('field_payment_paypal_address');
                $product_info['defaults']['payment_instruction']    = $this->config->get('field_payment_instruction');
                $product_info['defaults']['payment_immediate']      = $this->config->get('payment_immediate');

                $product_info['defaults']['gallery_height']         = '400';
                $product_info['defaults']['gallery_width']          = '400';
                $product_info['defaults']['thumb_height']           = '100';
                $product_info['defaults']['thumb_width']            = '100';


                $product_info['defaults']['listing_duration'] = $this->config->get('openbaypro_duration');
                if ($product_info['defaults']['listing_duration'] == '') {
                    $product_info['defaults']['listing_duration'] = 'Days_30';
                }

                if (isset($this->error['warning'])) {
                    $this->data['error_warning'] = $this->error['warning'];
                } else {
                    $this->data['error_warning'] = '';
                }

                if($product_info['quantity'] < 1 && (!isset($product_info['has_option']) || $product_info['has_option'] == 0)){
                    $this->data['error_warning'] = $this->language->get('lang_error_no_stock');
                }

                $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

                $this->data['product'] = $product_info;

                $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
            } else {
                $this->redirect($this->url->link('extension/openbay/itemList&token=' . $this->session->data['token']));
            }
        }
    }

    public function createBulk() {
        if ($this->checkConfig() == true) {
            if(!empty($this->request->post['selected'])){
                $this->data = array_merge($this->data, $this->load->language('ebay/new_bulk'));

                $this->load->model('catalog/product');
                $this->load->model('tool/image');
                $this->load->model('catalog/manufacturer');
                $this->load->model('ebay/openbay');
                $this->load->model('ebay/profile');

                $this->data['error_warning'] = array();

                $activeList = $this->model_ebay_openbay->getLiveListingArray();

                $products = array();

                if ($this->ebay->addonLoad('openstock') == true) {
                    $openstock = 1;
                }else{
                    $openstock = 0;
                }

                foreach ($this->request->post['selected'] as $product_id) {
                    if(!array_key_exists($product_id, $activeList)) {

                        $prod = $this->model_catalog_product->getProduct($product_id);

                        if($openstock == 1 && isset($prod['has_option']) && $prod['has_option'] == 1){
                            $this->data['error_warning']['os'] = $this->language->get('lang_error_variants');
                        }else{
                            if($prod['quantity'] > 0){
                                if ($prod['image'] && file_exists(DIR_IMAGE . $prod['image'])) {
                                    $prod['image'] = $this->model_tool_image->resize($prod['image'], 80, 80);
                                } else {
                                    $prod['image'] = $this->model_tool_image->resize('no_image.jpg', 80, 80);
                                }

                                $products[] = $prod;
                            }else{
                                $this->data['error_warning']['stock'] = $this->language->get('lang_error_stock');
                            }
                        }
                    }else{
                        $this->data['error_warning']['exists'] = $this->language->get('lang_exists');
                    }
                }

                $this->data['count'] = count($products);
                $this->data['token'] = $this->session->data['token'];
                $this->data['listing_link'] = $this->config->get('openbaypro_ebay_itm_link');

                $plan = $this->model_ebay_openbay->getMyPlan();

                if($plan['plan']['listing_bulk'] == 1){
                    if($this->data['count'] == 0){
                        $this->data['error_fail'][] = $this->language->get('lang_error_no_product');
                    }else{
                        if(($plan['plan']['listing_limit'] == 0) || (($plan['usage']['items'] + $this->data['count']) <= $plan['plan']['listing_limit'])){
                            if($this->data['count'] > 5){
                                $this->data['error_warning']['count'] = sprintf($this->language->get('lang_error_count'), $this->data['count']);
                            }

                            //get generic profiles
                            $product_info['profiles_generic'] = $this->model_ebay_profile->getAll(3);
                            //get default generic profile
                            $product_info['profiles_generic_def'] = $this->model_ebay_profile->getDefault(3);
                            if($product_info['profiles_generic_def'] === false){
                                $this->data['error_fail'][] = $this->language->get('lang_error_generic_profile');
                            }

                            //get shipping profiles
                            $product_info['profiles_shipping'] = $this->model_ebay_profile->getAll(0);
                            //get default shipping profile
                            $product_info['profiles_shipping_def'] = $this->model_ebay_profile->getDefault(0);
                            //check it has a default profile
                            if($product_info['profiles_shipping_def'] === false){
                                $this->data['error_fail'][] = $this->language->get('lang_error_ship_profile');
                            }

                            //get returns profiles
                            $product_info['profiles_returns'] = $this->model_ebay_profile->getAll(1);
                            //get default returns profile
                            $product_info['profiles_returns_def'] = $this->model_ebay_profile->getDefault(1);
                            //check it has a default profile
                            if($product_info['profiles_returns_def'] === false){
                                $this->data['error_fail'][] = $this->language->get('lang_error_return_profile');
                            }

                            //get returns profiles
                            $product_info['profiles_theme'] = $this->model_ebay_profile->getAll(2);
                            //get default returns profile
                            $product_info['profiles_theme_def'] = $this->model_ebay_profile->getDefault(2);
                            //check it has a default profile
                            if($product_info['profiles_theme_def'] === false){
                                $this->data['error_fail'][] = $this->language->get('lang_error_theme_profile');
                            }

                            // get the product tax rate
                            if (isset($product_info['tax_class_id'])) {
                                $product_info['defaults']['tax'] = $this->model_ebay_product->getTaxRate($product_info['tax_class_id']);
                            } else {
                                $product_info['defaults']['tax'] = 0.00;
                            }

                            $this->data['products'] = $products;

                            $product_info['manufacturers']  = $this->model_catalog_manufacturer->getManufacturers();
                            $product_info['payments']       = $this->model_ebay_openbay->getPaymentTypes();
                            $product_info['store_cats']     = $this->model_ebay_openbay->getSellerStoreCategories();

                            $product_info['defaults']['ebay_template'] = $this->config->get('ebay_template');

                            $product_info['defaults']['listing_duration'] = $this->config->get('openbaypro_duration');
                            if ($product_info['defaults']['listing_duration'] == '') {
                                $product_info['defaults']['listing_duration'] = 'Days_30';
                            }

                            $this->data['default'] = $product_info;
                        }else{
                            $this->data['error_fail']['plan'] = sprintf($this->language->get('lang_item_limit'), $this->url->link('openbay/openbay/viewSubscription', 'token=' . $this->session->data['token'], 'SSL'));
                        }
                    }
                }else{
                    $this->data['error_fail']['plan'] = sprintf($this->language->get('lang_bulk_plan_error'), $this->url->link('openbay/openbay/viewSubscription', 'token=' . $this->session->data['token'], 'SSL'));
                }

                $this->document->setTitle($this->data['lang_page_title']);
                $this->document->addStyle('view/stylesheet/openbay.css');
                $this->document->addScript('view/javascript/openbay/faq.js');
                $this->document->addScript('view/javascript/openbay/openbay.js');
                $this->template = 'ebay/new_bulk.tpl';

                $this->children = array(
                    'common/header',
                    'common/footer'
                );
                $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
            }else{
                $this->redirect($this->url->link('extension/openbay/itemList&token=' . $this->session->data['token']));
            }
        }
    }

    public function verify() {
        $this->load->model('ebay/openbay');
        $this->load->model('ebay/template');
        $this->load->model('catalog/product');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->checkConfig() == true) {
                $this->model_ebay_openbay->logCategoryUsed($this->request->post['finalCat']);

                $item_id = $this->ebay->getEbayItemId($this->request->post['product_id']);

                if ($item_id == false) { // ensure that the sku is not already listed
                    $data = $this->request->post;

                    if($data['template'] != 'None'){
                        $template = $this->model_ebay_template->get($data['template']);
                        $data['template_html'] = (isset($template['html']) ? base64_encode($template['html']) : '');
                    }else{
                        $data['template_html'] = '';
                    }

                    if (!empty($data['img_tpl'])) {
                        $tmpGalArray = array();
                        $tmpThumbArray = array();
                        $this->load->model('tool/image');

                        foreach ($data['img_tpl'] as $k => $v) {
                            $tmpGalArray[$k] = $this->model_tool_image->resize($v, $data['gallery_width'], $data['gallery_height']);
                            $tmpThumbArray[$k] = $this->model_tool_image->resize($v, $data['thumb_width'], $data['thumb_height']);
                        }

                        $data['img_tpl'] = $tmpGalArray;
                        $data['img_tpl_thumb'] = $tmpThumbArray;
                    }

                    $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$data['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                    $data['product_info'] = $query->row;

                    $verifyResponse = $this->model_ebay_openbay->ebayVerifyAddItem($data, $this->request->get['options']);

                    $this->response->setOutput(json_encode($verifyResponse));
                } else {
                    $this->response->setOutput(json_encode(array('error' => true, 'msg' => 'This item is already listed in your eBay account', 'item' => $item_id)));
                }
            }
        } else {
            $this->redirect($this->url->link('extension/openbay/itemList&token=' . $this->session->data['token']));
        }
    }

    public function verifyBulk() {
        $this->load->model('ebay/profile');
        $this->load->model('ebay/openbay');
        $this->load->model('ebay/template');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->checkConfig() == true) {
                $post = $this->request->post;
                $data = array();

                //load all of the listing defaults and assign to correct variable names
                $profile_shipping           = $this->model_ebay_profile->get($post['shipping_profile']);
                $profile_return             = $this->model_ebay_profile->get($post['return_profile']);
                $profile_template           = $this->model_ebay_profile->get($post['theme_profile']);
                $profile_generic            = $this->model_ebay_profile->get($post['generic_profile']);

                $payments                   = $this->model_ebay_openbay->getPaymentTypes();
                $paymentsAccepted           = $this->config->get('ebay_payment_types');
                $product_info               = $this->model_catalog_product->getProduct($post['product_id']);

                $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$post['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                $data['product_info'] = $query->row;

                $data['description']        = $product_info['description'];
                $data['name']               = $post['title'];
                $data['sub_name']           = '';
                $data['bestoffer']          = 0;
                $data['finalCat']           = $post['finalCat'];
                $data['price'][0]           = $post['price'];
                $data['qty'][0]             = (int)$post['qty'];
                $data['product_id']         = (int)$post['product_id'];
                $data['auction_duration']   = $post['duration'];
                $data['condition']          = (isset($post['condition']) && $post['condition'] != 0 ? $post['condition'] : '');
                $data['auction_type']       = 'FixedPriceItem';
                $data['catalog_epid']       = (isset($post['catalog_epid']) && $post['catalog_epid'] != 0 ? $post['catalog_epid'] : '');

                $data['payment_immediate']  = $this->config->get('payment_immediate');
                $data['paypal_email']       = $this->config->get('field_payment_paypal_address');
                $data['payment_instruction']= $this->config->get('field_payment_instruction');

                $data['returns_within']     = $profile_return['data']['returns_within'];
                $data['return_policy']      = $profile_return['data']['returns_policy'];
                $data['returns_shipping']   = $profile_return['data']['returns_shipping'];
                $data['returns_accepted']   = $profile_return['data']['returns_accepted'];

                $data['location']           = $profile_shipping['data']['postcode'];
                $data['dispatch_time']      = $profile_shipping['data']['dispatch_time'];
                $data['dispatch_time']      = (isset($profile_shipping['data']['get_it_fast']) ? $profile_shipping['data']['get_it_fast'] : 0);

                if(isset($profile_template['data']['ebay_template_id'])){
                    $template = $this->model_ebay_template->get($profile_template['data']['ebay_template_id']);
                    $data['template_html'] = (isset($template['html']) ? base64_encode($template['html']) : '');
                    $data['template'] = $profile_template['data']['ebay_template_id'];
                }else{
                    $data['template_html'] = '';
                    $data['template'] = '';
                }

                $data['gallery_plus']       = $profile_template['data']['ebay_gallery_plus'];
                $data['gallery_super']      = $profile_template['data']['ebay_supersize'];

                $data['private_listing']    = $profile_generic['data']['private_listing'];

                //product attributes - this is just a direct pass through used with the template tag
                $data['attributes'] = base64_encode(json_encode($this->model_ebay_openbay->getProductAttributes($post['product_id'])));

                $data['payments'] = array();
                foreach($payments as $payment){
                    if($paymentsAccepted[$payment['ebay_name']] == 1){
                        $data['payments'][$payment['ebay_name']] = 1;
                    }
                }

                $data['main_image'] = 0;
                $data['img'] = array();

                $product_images = $this->model_catalog_product->getProductImages($post['product_id']);

                $product_info['product_images'] = array();

                if (!empty($product_info['image'])) {
                    $data['img'][] = $product_info['image'];
                }

                if(isset($profile_template['data']['ebay_img_ebay']) && $profile_template['data']['ebay_img_ebay'] == 1){
                    foreach ($product_images as $product_image) {
                        if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
                            $data['img'][] =  $product_image['image'];
                        }
                    }
                }

                if(isset($profile_template['data']['ebay_img_template']) && $profile_template['data']['ebay_img_template'] == 1) {
                    $tmpGalArray = array();
                    $tmpThumbArray = array();

                    //if the user has not set the exclude default image, add it to the array for theme images.
                    $keyOffset = 0;
                    if(!isset($profile_template['data']['default_img_exclude']) || $profile_template['data']['default_img_exclude'] != 1){
                        $tmpGalArray[0] = $this->model_tool_image->resize($product_info['image'], $profile_template['data']['ebay_gallery_width'], $profile_template['data']['ebay_gallery_height']);
                        $tmpThumbArray[0] = $this->model_tool_image->resize($product_info['image'], $profile_template['data']['ebay_thumb_width'], $profile_template['data']['ebay_thumb_height']);
                        $keyOffset = 1;
                    }

                    //loop through the product images and add them.
                    foreach ($product_images as $k => $v) {
                        $tmpGalArray[$k+$keyOffset] = $this->model_tool_image->resize($v['image'], $profile_template['data']['ebay_gallery_width'], $profile_template['data']['ebay_gallery_height']);
                        $tmpThumbArray[$k+$keyOffset] = $this->model_tool_image->resize($v['image'], $profile_template['data']['ebay_thumb_width'], $profile_template['data']['ebay_thumb_height']);
                    }

                    $data['img_tpl']        = $tmpGalArray;
                    $data['img_tpl_thumb']  = $tmpThumbArray;
                }

                $data = array_merge($data, $profile_shipping['data']);

                $verifyResponse = $this->model_ebay_openbay->ebayVerifyAddItem($data, 'no');

                $response = array(
                    'errors'    => $verifyResponse['data']['Errors'],
                    'fees'      => $verifyResponse['data']['Fees'],
                    'itemid'    => (string)$verifyResponse['data']['ItemID'],
                    'preview'   => (string)$verifyResponse['data']['link'],
                    'i'         => $this->request->get['i'],
                    'ack'       => (string)$verifyResponse['data']['Ack'],
                );

                $this->response->setOutput(json_encode($response));
            }
        } else {
            $this->redirect($this->url->link('extension/openbay/itemList&token=' . $this->session->data['token']));
        }
    }

    public function listItem() {
        $this->load->model('ebay/openbay');
        $this->load->model('ebay/template');
        $this->load->model('catalog/product');

        if ($this->checkConfig() == true && $this->request->server['REQUEST_METHOD'] == 'POST') {
            $data = $this->request->post;

            if($data['template'] != 'None'){
                $template = $this->model_ebay_template->get($data['template']);
                $data['template_html'] = (isset($template['html']) ? base64_encode($template['html']) : '');
            }else{
                $data['template_html'] = '';
            }

            if (!empty($data['img_tpl'])) {
                $tmpGalArray = array();
                $tmpThumbArray = array();
                $this->load->model('tool/image');

                foreach ($data['img_tpl'] as $k => $v) {
                    $tmpGalArray[$k] = $this->model_tool_image->resize($v, $data['gallery_width'], $data['gallery_height']);
                    $tmpThumbArray[$k] = $this->model_tool_image->resize($v, $data['thumb_width'], $data['thumb_height']);
                }

                $data['img_tpl'] = $tmpGalArray;
                $data['img_tpl_thumb'] = $tmpThumbArray;
            }

            $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$data['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

            $data['product_info'] = $query->row;

            $verifyResponse = $this->model_ebay_openbay->ebayAddItem($data, $this->request->get['options']);

            $this->response->setOutput(json_encode($verifyResponse));
        } else {
            $this->redirect($this->url->link('extension/openbay/itemList&token=' . $this->session->data['token']));
        }
    }

    public function listItemBulk() {
        $this->load->model('ebay/profile');
        $this->load->model('ebay/openbay');
        $this->load->model('ebay/template');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ($this->checkConfig() == true) {
                $post = $this->request->post;
                $data = array();

                //load all of the listing defaults and assign to correct variable names
                $profile_shipping           = $this->model_ebay_profile->get($post['shipping_profile']);
                $profile_return             = $this->model_ebay_profile->get($post['return_profile']);
                $profile_template           = $this->model_ebay_profile->get($post['theme_profile']);
                $profile_generic            = $this->model_ebay_profile->get($post['generic_profile']);
                $payments                   = $this->model_ebay_openbay->getPaymentTypes();
                $paymentsAccepted           = $this->config->get('ebay_payment_types');
                $product_info               = $this->model_catalog_product->getProduct($post['product_id']);

                $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$post['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                $data['product_info'] = $query->row;

                $data['description']        = $product_info['description'];
                $data['name']               = $post['title'];
                $data['sub_name']           = '';
                $data['bestoffer']          = 0;
                $data['finalCat']           = $post['finalCat'];
                $data['price'][0]           = $post['price'];
                $data['qty'][0]             = $post['qty'];
                $data['product_id']         = $post['product_id'];
                $data['auction_duration']   = $post['duration'];
                $data['condition']          = (isset($post['condition']) && $post['condition'] != 0 ? $post['condition'] : '');
                $data['auction_type']       = 'FixedPriceItem';
                $data['catalog_epid']       = (isset($post['catalog_epid']) && $post['catalog_epid'] != 0 ? $post['catalog_epid'] : '');

                $data['payment_immediate']  = $this->config->get('payment_immediate');
                $data['paypal_email']       = $this->config->get('field_payment_paypal_address');
                $data['payment_instruction']= $this->config->get('field_payment_instruction');

                $data['returns_within']     = $profile_return['data']['returns_within'];
                $data['return_policy']      = $profile_return['data']['returns_policy'];
                $data['returns_shipping']   = $profile_return['data']['returns_shipping'];
                $data['returns_accepted']   = $profile_return['data']['returns_accepted'];

                $data['location']           = $profile_shipping['data']['location'];
                $data['postcode']           = $profile_shipping['data']['postcode'];
                $data['dispatch_time']      = $profile_shipping['data']['dispatch_time'];
                $data['dispatch_time']      = (isset($profile_shipping['data']['get_it_fast']) ? $profile_shipping['data']['get_it_fast'] : 0);

                if(isset($profile_template['data']['ebay_template_id'])){
                    $template = $this->model_ebay_template->get($profile_template['data']['ebay_template_id']);
                    $data['template_html'] = (isset($template['html']) ? base64_encode($template['html']) : '');
                    $data['template'] = $profile_template['data']['ebay_template_id'];
                }else{
                    $data['template_html'] = '';
                    $data['template'] = '';
                }

                $data['gallery_plus']       = $profile_template['data']['ebay_gallery_plus'];
                $data['gallery_super']      = $profile_template['data']['ebay_supersize'];

                $data['private_listing']    = $profile_generic['data']['private_listing'];

                //product attributes - this is just a direct pass through used with the template tag
                $data['attributes'] = base64_encode(json_encode($this->model_ebay_openbay->getProductAttributes($post['product_id'])));

                $data['payments'] = array();
                foreach($payments as $payment){
                    if($paymentsAccepted[$payment['ebay_name']] == 1){
                        $data['payments'][$payment['ebay_name']] = 1;
                    }
                }

                $data['main_image'] = 0;
                $data['img'] = array();

                $product_images = $this->model_catalog_product->getProductImages($post['product_id']);

                $product_info['product_images'] = array();

                if (!empty($product_info['image'])) {
                    $data['img'][] = $product_info['image'];
                }

                if(isset($profile_template['data']['ebay_img_ebay']) && $profile_template['data']['ebay_img_ebay'] == 1){
                    foreach ($product_images as $product_image) {
                        if ($product_image['image'] && file_exists(DIR_IMAGE . $product_image['image'])) {
                            $data['img'][] =  $product_image['image'];
                        }
                    }
                }

                if(isset($profile_template['data']['ebay_img_template']) && $profile_template['data']['ebay_img_template'] == 1) {
                    $tmpGalArray = array();
                    $tmpThumbArray = array();

                    //if the user has not set the exclude default image, add it to the array for theme images.
                    $keyOffset = 0;
                    if(!isset($profile_template['data']['default_img_exclude']) || $profile_template['data']['default_img_exclude'] != 1){
                        $tmpGalArray[0] = $this->model_tool_image->resize($product_info['image'], $profile_template['data']['ebay_gallery_width'], $profile_template['data']['ebay_gallery_height']);
                        $tmpThumbArray[0] = $this->model_tool_image->resize($product_info['image'], $profile_template['data']['ebay_thumb_width'], $profile_template['data']['ebay_thumb_height']);
                        $keyOffset = 1;
                    }

                    //loop through the product images and add them.
                    foreach ($product_images as $k => $v) {
                        $tmpGalArray[$k+$keyOffset] = $this->model_tool_image->resize($v['image'], $profile_template['data']['ebay_gallery_width'], $profile_template['data']['ebay_gallery_height']);
                        $tmpThumbArray[$k+$keyOffset] = $this->model_tool_image->resize($v['image'], $profile_template['data']['ebay_thumb_width'], $profile_template['data']['ebay_thumb_height']);
                    }

                    $data['img_tpl']        = $tmpGalArray;
                    $data['img_tpl_thumb']  = $tmpThumbArray;
                }

                $data = array_merge($data, $profile_shipping['data']);

                $verifyResponse = $this->model_ebay_openbay->ebayAddItem($data, 'no');

                $response = array(
                    'errors'    => $verifyResponse['data']['Errors'],
                    'fees'      => $verifyResponse['data']['Fees'],
                    'itemid'    => (string)$verifyResponse['data']['ItemID'],
                    'preview'   => (string)$verifyResponse['data']['link'],
                    'i'         => $this->request->get['i'],
                    'ack'       => (string)$verifyResponse['data']['Ack'],
                );

                $this->response->setOutput(json_encode($response));
            }
        } else {
            $this->redirect($this->url->link('extension/openbay/itemList&token=' . $this->session->data['token']));
        }
    }

    public function getImportImages() {
        set_time_limit(0);
        $this->ebay->getImages();
        $this->response->setOutput(json_encode(array('error' => false, 'msg' => 'OK')));
    }

    public function repairLinks(){
        $this->load->model('ebay/product');
        $this->model_ebay_product->repairLinks();
        return $this->response->setOutput(json_encode(array('msg' => 'Links repaired')));
    }

    public function deleteAllLocks(){
        $this->ebay->log('deleteAllLocks() - Deleting all locks');
        $this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_order_lock`");
        return $this->response->setOutput(json_encode(array('msg' => 'Locks deleted')));
    }

    public function endItem(){
        return $this->response->setOutput(json_encode($this->ebay->endItem($this->request->get['id'])));
    }
}