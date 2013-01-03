<?php

class ControllerPaymentKlarnaAccount extends Controller {

    private $error = array();

    public function index() {
        $this->load->model('setting/setting');
        $this->load->model('localisation/order_status');
        $this->load->model('localisation/geo_zone');
        
        $this->data = array_merge($this->data, $this->load->language('payment/klarna_account'));

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->data['country_names'] = array(
            'DEU' => $this->language->get('text_germany'),
            'NLD' => $this->language->get('text_netherlands'),
            'DNK' => $this->language->get('text_denmark'),
            'SWE' => $this->language->get('text_sweden'),
            'NOR' => $this->language->get('text_norway'),
            'FIN' => $this->language->get('text_finland'),
        );

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            
            $klarnaCountry = array();
            $status = false;
            
            foreach (array_keys($this->data['country_names']) as $iso3) {

                if (isset($this->request->post['klarna_account_country'][$iso3]['status']) && $this->request->post['klarna_account_country'][$iso3]['status'] == 1) {
                    $klarnaCountry[$iso3] = $this->request->post['klarna_account_country'][$iso3];
                    $status = true;
                } else {
                    $klarnaCountry[$iso3] = array(
                        'merchant' => '',
                        'secret' => '',
                        'server' => '',
                        'minimum' => '',
                        'status' => '',
                        'sort_order' => '',
                        'geo_zone_id' => '',
                    );
                }
            }
            
            $settings = array(
                'klarna_account_country' => $klarnaCountry,
                'klarna_account_status' => $status,
                'klarna_account_pending_order_status_id' => (int) $this->request->post['klarna_account_pending_order_status_id'],
                'klarna_account_accepted_order_status_id' => (int) $this->request->post['klarna_account_accepted_order_status_id'],
            );
            
            $this->model_setting_setting->editSetting('klarna_account', $settings);
            
            $this->fetchPClasses($klarnaCountry);

            if ($this->error) {
                $this->session->data['error'] = $this->language->get('error_update');
            } else {
                $this->session->data['success'] = $this->language->get('text_success');
            }

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
            'href' => $this->url->link('payment/klarna_account', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('payment/klarna_account', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['klarna_account_pending_order_status_id'])) {
            $this->data['klarna_account_pending_order_status_id'] = $this->request->post['klarna_account_pending_order_status_id'];
        } else {
            $this->data['klarna_account_pending_order_status_id'] = $this->config->get('klarna_account_pending_order_status_id');
        }

        if (isset($this->request->post['klarna_account_accepted_order_status_id'])) {
            $this->data['klarna_account_accepted_order_status_id'] = $this->request->post['klarna_account_accepted_order_status_id'];
        } else {
            $this->data['klarna_account_accepted_order_status_id'] = $this->config->get('klarna_account_accepted_order_status_id');
        }

        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        
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
        
        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            $this->data['klarna_account_country'] = $klarnaCountry;
        } else {
            $this->data['klarna_account_country'] = $this->config->get('klarna_account_country');
        }
        
        /* Getting the Log contents */
        
        if (file_exists(DIR_LOGS . 'klarna_account.log') && is_readable(DIR_LOGS . 'klarna_account.log')) {
            $this->data['klarna_account_log'] = file_get_contents(DIR_LOGS . 'klarna_account.log');
        } else {
            $this->data['klarna_account_log'] = '';
        }
        
        $this->data['clear_log'] = $this->url->link('payment/klarna_account/clearLog', 'token=' . $this->session->data['token'], 'SSL'); 

        $this->template = 'payment/klarna_account.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/klarna_account')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
    
    public function clearLog() {
        $this->load->language('payment/klarna_account');
        
        $success = file_put_contents(DIR_LOGS . 'klarna_account.log', '') !== false;
        
        if ($success) {
            $this->session->data['success'] = $this->language->get('text_log_clear');
        } else {
            $this->session->data['error'] = $this->language->get('error_log_clear');
        }
        
        $this->redirect($this->url->link('payment/klarna_account', 'token=' . $this->session->data['token'], 'SSL'));
    }
    
    private function fetchPClasses($klarnaCountries) {
        $log = new Log('klarna_account.log');
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
        
        $result = array();
        
        foreach ($countries as $countryCode => $country) {
            
            if ($klarnaCountries[$countryCode]['status'] != 1) {
                continue;
            }
            
            $digest = base64_encode(pack("H*", hash('sha256', $klarnaCountries[$countryCode]['merchant']  . ':' . $country['currency'] . ':' . $klarnaCountries[$countryCode]['secret'])));
            
            $xml  = "<methodCall>";
            $xml .= "  <methodName>get_pclasses</methodName>";
            $xml .= '  <params>';
            $xml .= ' <param><value><string>4.1</string></value></param>';
            $xml .= ' <param><value><string>API:OPENCART:' . VERSION . '</string></value></param>';
            $xml .= ' <param><value><int>' . (int) $klarnaCountries[$countryCode]['merchant'] . '</int></value></param>';
            $xml .= ' <param><value><int>' . $country['currency'] . '</int></value></param>';
            $xml .= ' <param><value><string>' . $digest . '</string></value></param>';
            $xml .= ' <param><value><int>' . $country['country'] . '</int></value></param>';
            $xml .= ' <param><value><int>' . $country['language'] . '</int></value></param>';
            $xml .= "  </params>";
            $xml .= "</methodCall>";
            
            if ($klarnaCountries[$countryCode]['server'] == 'live') {
                $server = 'https://payment.klarna.com';
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
            
            if ($responseString !== False) {

                $responseXml = new DOMDocument();
                $responseXml->loadXML($responseString);

                $xpath = new DOMXPath($responseXml);

                $nodes = $xpath->query('//methodResponse/params/param/value');

                if ($nodes->length == 0) {
                    $log = new Log('klarna_account.log');
                    $log->write(sprintf($this->language->get('error_retrieve_pclass'), $countryCode));
                    continue;
                }

                $pclasses = $this->parseResponse($nodes->item(0)->firstChild, $responseXml);

                while ($pclasses) {
                    $pclass = array_slice($pclasses, 0, 10);
                    $pclasses = array_slice($pclasses, 10);

                    $pclass[3] /= 100;
                    $pclass[4] /= 100;
                    $pclass[5] /= 100;
                    $pclass[6] /= 100;
                    $pclass[9] = ($pclass[9] != '-') ? strtotime($pclass[9]) : $pclass[9];

                    array_unshift($pclass, $klarnaCountries[$countryCode]['merchant']);

                    $result[$countryCode][] = array(
                        'eid' => intval($pclass[0]),
                        'id' => intval($pclass[1]),
                        'description' => $pclass[2],
                        'months' => intval($pclass[3]),
                        'startfee' => floatval($pclass[4]),
                        'invoicefee' => floatval($pclass[5]),
                        'interestrate' => floatval($pclass[6]),
                        'minamount' => floatval($pclass[7]),
                        'country' => intval($pclass[8]),
                        'type' => intval($pclass[9]),
                    );
                }

            } else {
                $this->error['errro_http'] = sprintf($this->language->get('error_http_error'), curl_errno($ch), curl_error($ch));
                $log->write(sprintf($this->language->get('error_http_error'), curl_errno($ch), curl_error($ch)));
            }
            
            curl_close($ch);
        }
        
        $settings = $this->model_setting_setting->getSetting('klarna_account');
        $settings['klarna_account_pclasses'] = $result;
        $this->model_setting_setting->editSetting('klarna_account', $settings);
    }
    
    private function parseResponse($node, $document) {
        $child = $node;

        switch ($child->nodeName) {
            case 'string':
                $value = $child->nodeValue;
                break;

            case 'boolean':
                $value = (string) $child->nodeValue;

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
                $value = (int) $child->nodeValue;
                break;

            case 'array':
                $value = array();
                
                $xpath = new DOMXPath($document);
                $entries = $xpath->query('.//array/data/value', $child);
                
                for ($i = 0; $i < $entries->length; $i++) {
                    $entry = $entries->item($i)->firstChild;
                    
                    $value[] = $this->parseResponse($entry, $document);
                }

                break;

            default:
                $value = null;
        }

        return $value;
    }
    
}