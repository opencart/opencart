<?php

class ControllerTotalKlarnaFee extends Controller {

    private $error = array();
    
    public function index() {
        $this->load->model('setting/setting');
        $this->load->model('localisation/tax_class');
        
        $this->data = array_merge($this->data, $this->load->language('total/klarna_fee'));

        $this->document->setTitle($this->language->get('heading_title'));

        $this->data['country_names'] = array(
            'DEU' => $this->language->get('text_germany'),
            'NLD' => $this->language->get('text_netherlands'),
            'DNK' => $this->language->get('text_denmark'),
            'SWE' => $this->language->get('text_sweden'),
            'NOR' => $this->language->get('text_norway'),
            'FIN' => $this->language->get('text_finland'),
        );
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $status = false;
            $klarnaFeeCountry = array();
            
            foreach (array_keys($this->data['country_names']) as $iso3) {
             
                if (isset($this->request->post['klarna_fee_country'][$iso3]['status']) && $this->request->post['klarna_fee_country'][$iso3]['status'] == 1) {
                    $klarnaFeeCountry[$iso3] = $this->request->post['klarna_fee_country'][$iso3];
                    $status = true;
                } else {
                    $klarnaFeeCountry[$iso3] = array(
                        'status' => '',
                        'total' => '',
                        'fee' => '',
                        'tax_class_id' => '',
                        'sort_order' => '',
                    );
                }
            }
            
            $settings = array(
                'klarna_fee_country' => $klarnaFeeCountry,
                'klarna_fee_status' => $status,
            );
            
            $this->model_setting_setting->editSetting('klarna_fee', $settings);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_total'),
            'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('total/klarna_fee', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->data['klarna_fee_country'] = $klarnaFeeCountry;
        } else {
            $this->data['klarna_fee_country'] = $this->config->get('klarna_fee_country');
        }
        
        $this->data['action'] = $this->url->link('total/klarna_fee', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        $this->template = 'total/klarna_fee.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'total/klarna_fee')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

}