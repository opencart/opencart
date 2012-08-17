<?php

class ControllerPaymentKlarna extends Controller {

    private $error = array();

    public function index() {
        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');
        $this->load->model('localisation/tax_class');
        
        $this->data = array_merge($this->data, $this->load->language('payment/klarna'));

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $settings = $this->request->post;
            
            $settings['klarna_status'] = $settings['klarna_acc_status'] == 1 || $settings['klarna_inv_status'] == 1;
            
            $this->model_setting_setting->editSetting('klarna', $settings);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }
        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['merchant'])) {
            $this->data['error_merchant'] = $this->error['merchant'];
        } else {
            $this->data['error_merchant'] = '';
        }

        if (isset($this->error['secret'])) {
            $this->data['error_secret'] = $this->error['secret'];
        } else {
            $this->data['error_secret'] = '';
        }

        if (isset($this->error['acc_minimum_amount'])) {
            $this->data['error_acc_minimum_amount'] = $this->error['acc_minimum_amount'];
        } else {
            $this->data['error_acc_minimum_amount'] = '';
        }

        if (isset($this->error['klarna_invoice_fee'])) {
            $this->data['error_klarna_invoice_fee'] = $this->error['klarna_invoice_fee'];
        } else {
            $this->data['error_klarna_invoice_fee'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_payment'),
            'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('payment/klarna', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/klarna', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['klarna_merchant'])) {
            $this->data['klarna_merchant'] = $this->request->post['klarna_merchant'];
        } else {
            $this->data['klarna_merchant'] = $this->config->get('klarna_merchant');
        }

        if (isset($this->request->post['klarna_secret'])) {
            $this->data['klarna_secret'] = $this->request->post['klarna_secret'];
        } else {
            $this->data['klarna_secret'] = $this->config->get('klarna_secret');
        }

        if (isset($this->request->post['klarna_server'])) {
            $this->data['klarna_server'] = $this->request->post['klarna_server'];
        } else {
            $this->data['klarna_server'] = $this->config->get('klarna_server');
        }

        if (isset($this->request->post['klarna_pending_order_status_id'])) {
            $this->data['klarna_pending_order_status_id'] = $this->request->post['klarna_pending_order_status_id'];
        } else {
            $this->data['klarna_pending_order_status_id'] = $this->config->get('klarna_pending_order_status_id');
        }

        if (isset($this->request->post['klarna_accepted_order_status_id'])) {
            $this->data['klarna_accepted_order_status_id'] = $this->request->post['klarna_accepted_order_status_id'];
        } else {
            $this->data['klarna_accepted_order_status_id'] = $this->config->get('klarna_accepted_order_status_id');
        }

        if (isset($this->request->post['klarna_invoice_fee'])) {
            $this->data['klarna_invoice_fee'] = $this->request->post['klarna_invoice_fee'];
        } else {
            $this->data['klarna_invoice_fee'] = $this->config->get('klarna_invoice_fee');
        }

        if (isset($this->request->post['klarna_invoice_fee_tax_class'])) {
            $this->data['klarna_invoice_fee_tax_class'] = $this->request->post['klarna_invoice_fee_tax_class'];
        } else {
            $this->data['klarna_invoice_fee_tax_class'] = $this->config->get('klarna_invoice_fee_tax_class');
        }

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        $this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
        
        /* Klarna Account */
        
        if (isset($this->request->post['klarna_acc_minimum_amount'])) {
            $this->data['klarna_acc_minimum_amount'] = $this->request->post['klarna_acc_minimum_amount'];
        } else {
            $this->data['klarna_acc_minimum_amount'] = $this->config->get('klarna_acc_minimum_amount');
        }
        
        if (isset($this->request->post['klarna_acc_geo_zone_id'])) {
            $this->data['klarna_acc_geo_zone_id'] = $this->request->post['klarna_acc_geo_zone_id'];
        } else {
            $this->data['klarna_acc_geo_zone_id'] = $this->config->get('klarna_acc_geo_zone_id');
        }

        if (isset($this->request->post['klarna_acc_status'])) {
            $this->data['klarna_acc_status'] = $this->request->post['klarna_acc_status'];
        } else {
            $this->data['klarna_acc_status'] = $this->config->get('klarna_acc_status');
        }

        if (isset($this->request->post['klarna_acc_sort_order'])) {
            $this->data['klarna_acc_sort_order'] = $this->request->post['klarna_acc_sort_order'];
        } else {
            $this->data['klarna_acc_sort_order'] = $this->config->get('klarna_acc_sort_order');
        }

        /* Klarna Invoice */

        if (isset($this->request->post['klarna_inv_status'])) {
            $this->data['klarna_inv_status'] = $this->request->post['klarna_inv_status'];
        } else {
            $this->data['klarna_inv_status'] = $this->config->get('klarna_inv_status');
        }

        if (isset($this->request->post['klarna_inv_sort_order'])) {
            $this->data['klarna_inv_sort_order'] = $this->request->post['klarna_inv_sort_order'];
        } else {
            $this->data['klarna_inv_sort_order'] = $this->config->get('klarna_inv_sort_order');
        }
        
        if (isset($this->request->post['klarna_inv_geo_zone_id'])) {
            $this->data['klarna_inv_geo_zone_id'] = $this->request->post['klarna_inv_geo_zone_id'];
        } else {
            $this->data['klarna_inv_geo_zone_id'] = $this->config->get('klarna_inv_geo_zone_id');
        }
        
        if (isset($this->session->data['success'])) {
            $this->data['message_success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['message_success'] = '';
        }
        
        if (isset($this->session->data['error'])) {
            $this->data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        }
        
        /* Getting the Log contents */
        
        if (file_exists(DIR_LOGS . 'klarna.log') && is_readable(DIR_LOGS . 'klarna.log')) {
            $this->data['klarna_log'] = file_get_contents(DIR_LOGS . 'klarna.log');
        } else {
            $this->data['klarna_log'] = '';
        }
        
        $this->data['clear_log'] = $this->url->link('payment/klarna/clearLog', 'token=' . $this->session->data['token'], 'SSL'); 

        $this->template = 'payment/klarna.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/klarna')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['klarna_merchant']) {
            $this->error['merchant'] = $this->language->get('error_merchant');
        }

        if (!$this->request->post['klarna_secret']) {
            $this->error['secret'] = $this->language->get('error_secret');
        }
        
        if (!empty($this->request->post['klarna_acc_minimum_amount']) && !is_numeric($this->request->post['klarna_acc_minimum_amount'])) {
            $this->error['acc_minimum_amount'] = $this->language->get('error_valid_number');
        }
        
        if (!empty($this->request->post['klarna_invoice_fee']) && !is_numeric($this->request->post['klarna_invoice_fee'])) {
            $this->error['klarna_invoice_fee'] = $this->language->get('error_valid_number');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
    
    public function clearLog() {
        $this->load->language('payment/klarna');
        
        $success = file_put_contents(DIR_LOGS . 'klarna.log', '') !== false;
        
        if ($success) {
            $this->session->data['success'] = $this->language->get('text_log_clear');
        } else {
            $this->session->data['error'] = $this->language->get('error_log_clear');
        }
        
        $this->redirect($this->url->link('payment/klarna', 'token=' . $this->session->data['token'], 'SSL'));
    }

}