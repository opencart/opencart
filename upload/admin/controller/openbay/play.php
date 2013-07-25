<?php
class ControllerOpenbayPlay extends Controller {
    public function install(){
        $this->load->language('play/install');
        $this->load->model('play/play');
        $this->load->model('setting/setting');
        $this->load->model('setting/extension');

        $this->model_play_play->install();

        $this->model_setting_extension->install('openbay', $this->request->get['extension']);
    }

    public function uninstall(){
        $this->load->language('play/install');
        $this->load->model('play/play');
        $this->load->model('setting/setting');
        $this->load->model('setting/extension');

        $this->model_play_play->uninstall();

        $this->model_setting_extension->uninstall('openbay', $this->request->get['extension']);
        $this->model_setting_setting->deleteSetting($this->request->get['extension']);
    }

    public function index(){
        $this->data = array_merge($this->data, $this->load->language('play/main'));

        $this->document->setTitle('OpenBay Pro for Play.com');
        $this->document->addStyle('view/stylesheet/openbay.css');
        $this->document->addScript('view/javascript/openbay/faq.js');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
            'text' => 'OpenBay Pro',
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=openbay/play&token=' . $this->session->data['token'],
            'text' => $this->data['lang_heading'],
            'separator' => ' :: '
        );

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }

        $this->data['validation'] = $this->ebay->validate();
        $this->data['links_settings'] = HTTPS_SERVER . 'index.php?route=openbay/play/settings&token=' . $this->session->data['token'];
        $this->data['links_pricing'] = HTTPS_SERVER . 'index.php?route=play/product/pricingReport&token=' . $this->session->data['token'];
        $this->data['image']['icon1'] = HTTPS_SERVER . 'view/image/openbay/openbay_icon1.png';
        $this->data['image']['icon13'] = HTTPS_SERVER . 'view/image/openbay/openbay_icon13.png';

        $this->template = 'play/main.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    protected function validate(){
        if (!$this->user->hasPermission('modify', 'openbay/play')) {
            $this->error['warning'] = $this->language->get('invalid_permission');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function settings(){
        $this->data = array_merge($this->data, $this->load->language('play/settings'));

        $this->load->model('setting/setting');
        $this->load->model('play/play');
        $this->load->model('localisation/currency');
        $this->load->model('sale/customer_group');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('play', $this->request->post);

            $this->session->data['success'] = $this->language->get('lang_text_success');

            $this->redirect(HTTPS_SERVER . 'index.php?route=openbay/play&token=' . $this->session->data['token']);
        }

        $this->document->setTitle($this->language->get('lang_heading_title'));
        $this->document->addScript('view/javascript/openbay/faq.js');
        $this->document->addStyle('view/stylesheet/openbay.css');

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_obp'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=openbay/play&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_obp_play'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => HTTPS_SERVER . 'index.php?route=openbay/play/settings&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_settings'),
            'separator' => ' :: '
        );

        $this->data['action'] = HTTPS_SERVER . 'index.php?route=openbay/play/settings&token=' . $this->session->data['token'];
        $this->data['cancel'] = HTTPS_SERVER . 'index.php?route=openbay/play&token=' . $this->session->data['token'];
        $this->data['token'] = $this->session->data['token'];

        /*
         * Error warnings
         */
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        /*
         *  Currency Import
         */
        if (isset($this->request->post['obp_play_def_currency'])) {
            $this->data['obp_play_def_currency'] = $this->request->post['obp_play_def_currency'];
        } else {
            $this->data['obp_play_def_currency'] = $this->config->get('obp_play_def_currency');
        }
        $this->data['currency_list'] = $this->model_localisation_currency->getCurrencies();

        /*
         *  Customer Import
         */
        if (isset($this->request->post['obp_play_def_customer_grp'])) {
            $this->data['obp_play_def_customer_grp'] = $this->request->post['obp_play_def_customer_grp'];
        } else {
            $this->data['obp_play_def_customer_grp'] = $this->config->get('obp_play_def_customer_grp');
        }
        $this->data['customer_grp_list'] = $this->model_sale_customer_group->getCustomerGroups();

        /*
         * Extension status
         */
        if (isset($this->request->post['play_status'])) {
            $this->data['play_status'] = $this->request->post['play_status'];
        } else {
            $this->data['play_status'] = $this->config->get('play_status');
        }

        if (isset($this->request->post['obp_play_token'])) {
            $this->data['obp_play_token'] = $this->request->post['obp_play_token'];
        } else {
            $this->data['obp_play_token'] = $this->config->get('obp_play_token');
        }

        if (isset($this->request->post['obp_play_secret'])) {
            $this->data['obp_play_secret'] = $this->request->post['obp_play_secret'];
        } else {
            $this->data['obp_play_secret'] = $this->config->get('obp_play_secret');
        }

        if (isset($this->request->post['obp_play_key'])) {
            $this->data['obp_play_key'] = $this->request->post['obp_play_key'];
        } else {
            $this->data['obp_play_key'] = $this->config->get('obp_play_key');
        }

        if (isset($this->request->post['obp_play_key2'])) {
            $this->data['obp_play_key2'] = $this->request->post['obp_play_key2'];
        } else {
            $this->data['obp_play_key2'] = $this->config->get('obp_play_key2');
        }

        if (isset($this->request->post['obp_play_logging'])) {
            $this->data['obp_play_logging'] = $this->request->post['obp_play_logging'];
        } else {
            $this->data['obp_play_logging'] = $this->config->get('obp_play_logging');
        }

        if (isset($this->request->post['obp_play_import_id'])) {
            $this->data['obp_play_import_id'] = $this->request->post['obp_play_import_id'];
        } else {
            $this->data['obp_play_import_id'] = $this->config->get('obp_play_import_id');
        }
        if (isset($this->request->post['obp_play_paid_id'])) {
            $this->data['obp_play_paid_id'] = $this->request->post['obp_play_paid_id'];
        } else {
            $this->data['obp_play_paid_id'] = $this->config->get('obp_play_paid_id');
        }
        if (isset($this->request->post['obp_play_shipped_id'])) {
            $this->data['obp_play_shipped_id'] = $this->request->post['obp_play_shipped_id'];
        } else {
            $this->data['obp_play_shipped_id'] = $this->config->get('obp_play_shipped_id');
        }
        if (isset($this->request->post['obp_play_cancelled_id'])) {
            $this->data['obp_play_cancelled_id'] = $this->request->post['obp_play_cancelled_id'];
        } else {
            $this->data['obp_play_cancelled_id'] = $this->config->get('obp_play_cancelled_id');
        }
        if (isset($this->request->post['obp_play_refunded_id'])) {
            $this->data['obp_play_refunded_id'] = $this->request->post['obp_play_refunded_id'];
        } else {
            $this->data['obp_play_refunded_id'] = $this->config->get('obp_play_refunded_id');
        }
        if (isset($this->request->post['obp_play_def_shipto'])) {
            $this->data['obp_play_def_shipto'] = $this->request->post['obp_play_def_shipto'];
        } else {
            $this->data['obp_play_def_shipto'] = $this->config->get('obp_play_def_shipto');
        }
        if (isset($this->request->post['obp_play_def_shipfrom'])) {
            $this->data['obp_play_def_shipfrom'] = $this->request->post['obp_play_def_shipfrom'];
        } else {
            $this->data['obp_play_def_shipfrom'] = $this->config->get('obp_play_def_shipfrom');
        }
        if (isset($this->request->post['obp_play_def_itemcond'])) {
            $this->data['obp_play_def_itemcond'] = $this->request->post['obp_play_def_itemcond'];
        } else {
            $this->data['obp_play_def_itemcond'] = $this->config->get('obp_play_def_itemcond');
        }
        if (isset($this->request->post['obp_play_order_update_notify'])) {
            $this->data['obp_play_order_update_notify'] = $this->request->post['obp_play_order_update_notify'];
        } else {
            $this->data['obp_play_order_update_notify'] = $this->config->get('obp_play_order_update_notify');
        }
        if (isset($this->request->post['obp_play_order_new_notify'])) {
            $this->data['obp_play_order_new_notify'] = $this->request->post['obp_play_order_new_notify'];
        } else {
            $this->data['obp_play_order_new_notify'] = $this->config->get('obp_play_order_new_notify');
        }
        if (isset($this->request->post['obp_play_order_new_notify_admin'])) {
            $this->data['obp_play_order_new_notify_admin'] = $this->request->post['obp_play_order_new_notify_admin'];
        } else {
            $this->data['obp_play_order_new_notify_admin'] = $this->config->get('obp_play_order_new_notify_admin');
        }
        if (isset($this->request->post['obp_play_default_tax'])) {
            $this->data['obp_play_default_tax'] = $this->request->post['obp_play_default_tax'];
        } else {
            $this->data['obp_play_default_tax'] = $this->config->get('obp_play_default_tax');
        }

        $this->data['dispatch_to']      = $this->play->getDispatchTo();
        $this->data['dispatch_from']    = $this->play->getDispatchFrom();
        $this->data['item_conditions']  = $this->play->getItemCondition();

        $this->template = 'play/settings.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }
}
