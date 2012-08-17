<?php

class ControllerPaymentKlarna extends Controller {

    private $error = array();

    public function index() {
        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');
        
        $this->data = array_merge($this->data, $this->load->language('payment/klarna'));

        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $settings = $this->request->post;
            
            $settings['klarna_status'] = $settings['klarna_acc_status'] == 1 || $settings['klarna_inv_status'] == 1;
            
            $this->model_setting_setting->editSetting('klarna', $settings);
            
            $this->fetchPClasses();

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

        if (isset($this->error['minimum_amount'])) {
            $this->data['error_minimum_amount'] = $this->error['minimum_amount'];
        } else {
            $this->data['error_minimum_amount'] = '';
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
        
        if (isset($this->request->post['klarna_sort_order'])) {
            $this->data['klarna_sort_order'] = $this->request->post['klarna_sort_order'];
        } else {
            $this->data['klarna_sort_order'] = $this->config->get('klarna_sort_order');
        }

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        
        /* Klarna Account */
        
        if (isset($this->request->post['klarna_minimum_amount'])) {
            $this->data['klarna_minimum_amount'] = $this->request->post['klarna_minimum_amount'];
        } else {
            $this->data['klarna_minimum_amount'] = $this->config->get('klarna_minimum_amount');
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

        /* Klarna Invoice */

        if (isset($this->request->post['klarna_inv_status'])) {
            $this->data['klarna_inv_status'] = $this->request->post['klarna_inv_status'];
        } else {
            $this->data['klarna_inv_status'] = $this->config->get('klarna_inv_status');
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

        if (!empty($this->request->post['klarna_minimum_amount']) && !is_numeric($this->request->post['klarna_minimum_amount'])) {
            $this->error['minimum_amount'] = $this->language->get('error_valid_number');
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
    
    private function fetchPClasses() {
        $countries = array(
            'NOR' => array(
                'currency' => 1,
                'country'  => 164,
                'language' => 97,
            ),
            'SWE' => array(
                'currency' => 0,
                'country'  => 209,
                'language' => 138,
            ),
            'FIN' => array(
                'currency' => 2,
                'country'  => 73,
                'language' => 101,
            ),
            'DNK' => array(
                'currency' => 3,
                'country'  => 59,
                'language' => 27,
            ),
            'DEU' => array(
                'currency' => 2,
                'country'  => 81,
                'language' => 28,
            ),
            'NLD' => array(
                'currency' => 2,
                'country'  => 154,
                'language' => 101,
            ),
        );
        
        $this->cache->delete('klarna');
        
        $merchantId = $this->request->post['klarna_merchant'];
        $secret = $this->request->post['klarna_secret'];
        
        $count = 0;
        
        foreach ($countries as $countryCode => $country) {
            $digest = base64_encode(pack("H*", hash('sha256', $merchantId  . ':' . $country['currency'] . ':' . $secret)));
            
            $xml  = "<methodCall>";
            $xml .= "  <methodName>get_pclasses</methodName>";
            $xml .= '  <params>';
            $xml .= ' <param><value><string>4.1</string></value></param>';
            $xml .= ' <param><value><string>PHP:WM:1</string></value></param>';
            $xml .= ' <param><value><int>' . $merchantId . '</int></value></param>';
            $xml .= ' <param><value><int>' . $country['currency'] . '</int></value></param>';
            $xml .= ' <param><value><string>' . $digest . '</string></value></param>';
            $xml .= ' <param><value><int>' . $country['country'] . '</int></value></param>';
            $xml .= ' <param><value><int>' . $country['language'] . '</int></value></param>';
            $xml .= "  </params>";
            $xml .= "</methodCall>";
            
            if ($this->request->post['klarna_server'] == 'live') {
                //$server = 'https://payment.klarna.com';
                $server = 'https://payment-beta.klarna.com';
            } else {
                $server = 'https://payment-beta.klarna.com';
            }
            
            $ch = curl_init($server);

            $headers = array(
                'Content-Type: text/xml',
                'Content-Length: ' . strlen($xml),
            );

            curl_setopt($ch, CURLOPT_URL, $server);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

            $responseString = curl_exec($ch);
            
            $xmlResponse = simplexml_load_string($responseString);
            
            $pclasses = $this->parseResponse($xmlResponse->params->param->value);
            
            foreach($pclasses as $pclass) {
                $classes[] = array(
                    'eid' => $pclass[0],
                    'id' => $pclass[1],
                    'description' => $pclass[2],
                    'months' => $pclass[3],
                    'startfee' => $pclass[4],
                    'invoicefee' => $pclass[5],
                    'interestrate' => $pclass[6],
                    'minamount' => $pclass[7],
                    'country' => $pclass[8],
                    'type' => ($pclass[9] != '-') ? strtotime($pclass[9]) : $pclass[9],
                );
                
                $count++;
            }
            
            $this->cache->set('klarna.' . $countryCode, $classes);

            curl_close($ch);
        }
    }
    
    private function parseResponse($xml) {
        $child = $xml->children();
        $child = $child[0];

        switch ($child->getName()) {
            case 'string':
                $value = (string) $child;
                break;

            case 'boolean':
                $value = (string) $child;

                if ($value == '0') {
                    $value = false;
                } elseif ($value == '1') {
                    $value = true;
                } else {
                    $value = null;
                }

                break;

            case 'integer':
            case 'int':
            case 'i4':
            case 'i8':
                $value = (int) $child;
                break;

            case 'array':
                $value = array();

                foreach ($child->data->value as $val) {
                    $value[] = $this->parseResponse($val);
                }

                break;

            default:
                $value = null;
        }

        return $value;
    }
    
}