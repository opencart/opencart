<?php
class ControllerPaymentKlarnaAccount extends Controller {
    private $error = array();
	private $pclasses = array();

    public function index() {
		$this->language->load('payment/klarna_account');
		
		$this->document->setTitle($this->language->get('heading_title'));
        
		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {            
			$status = false;
			
			foreach ($this->request->post['klarna_account'] as $klarna_account) {
				if ($klarna_account['status']) {
					$status = true;
					
					break;
				}
			}			
			
			$data = array(
				'klarna_account_pclasses' => $this->pclasses,
				'klarna_account_status'   => $status
			);
			
			$this->model_setting_setting->editSetting('klarna_account', array_merge($this->request->post, $data));
            
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_beta'] = $this->language->get('text_beta');
		$this->data['text_sweden'] = $this->language->get('text_sweden');
		$this->data['text_norway'] = $this->language->get('text_norway');
		$this->data['text_finland'] = $this->language->get('text_finland');
		$this->data['text_denmark'] = $this->language->get('text_denmark');
		$this->data['text_germany'] = $this->language->get('text_germany');
		$this->data['text_netherlands'] = $this->language->get('text_netherlands');
	
		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_server'] = $this->language->get('entry_server');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$this->data['entry_accepted_status'] = $this->language->get('entry_accepted_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_clear'] = $this->language->get('button_clear');
		
		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_log'] = $this->language->get('tab_log');
				        
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_payment'),
            'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('payment/klarna_account', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
        $this->data['action'] = $this->url->link('payment/klarna_account', 'token=' . $this->session->data['token'], 'SSL');
       
	    $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['countries'] = array();
		
		$this->data['countries'][] = array(
			'name' => $this->language->get('text_germany'),
			'code' => 'DEU'
		);
		
		$this->data['countries'][] = array(
			'name' => $this->language->get('text_netherlands'),
			'code' => 'NLD'
		);
		
		$this->data['countries'][] = array(
			'name' => $this->language->get('text_denmark'),
			'code' => 'DNK'
		);
		
		$this->data['countries'][] = array(
			'name' => $this->language->get('text_sweden'),
			'code' => 'SWE'
		);
		
		$this->data['countries'][] = array(
			'name' => $this->language->get('text_norway'),
			'code' => 'NOR'
		);
		
		$this->data['countries'][] = array(
			'name' => $this->language->get('text_finland'),
			'code' => 'FIN'
		);
				
		if (isset($this->request->post['klarna_account'])) {
			$this->data['klarna_account'] = $this->request->post['klarna_account'];
		} else {
			$this->data['klarna_account'] = $this->config->get('klarna_account');
		}
		
		$this->load->model('localisation/geo_zone');
			
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');
			
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
					        
		$file = DIR_LOGS . 'klarna_account.log';
		
        if (file_exists($file)) {
            $this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
        } else {
            $this->data['log'] = '';
        }
        
        $this->data['clear'] = $this->url->link('payment/klarna_account/clear', 'token=' . $this->session->data['token'], 'SSL'); 

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
		
        $log = new Log('klarna_account.log');
        
		$country = array(    
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
        
        foreach ($this->request->post['klarna_account'] as $key => $klarna_account) {
            if ($klarna_account['status']) {
				$digest = base64_encode(pack("H*", hash('sha256', $klarna_account['merchant']  . ':' . $country[$key]['currency'] . ':' . $klarna_account['secret'])));
				
				$xml  = '<methodCall>';
				$xml .= '  <methodName>get_pclasses</methodName>';
				$xml .= '  <params>';
				$xml .= '    <param><value><string>4.1</string></value></param>';
				$xml .= '    <param><value><string>API:OPENCART:' . VERSION . '</string></value></param>';
				$xml .= '    <param><value><int>' . (int)$klarna_account['merchant'] . '</int></value></param>';
				$xml .= '    <param><value><int>' . $country[$key]['currency'] . '</int></value></param>';
				$xml .= '    <param><value><string>' . $digest . '</string></value></param>';
				$xml .= '    <param><value><int>' . $country[$key]['country'] . '</int></value></param>';
				$xml .= '    <param><value><int>' . $country[$key]['language'] . '</int></value></param>';
				$xml .= '  </params>';
				$xml .= '</methodCall>';
				
				if ($klarna_account['server'] == 'live') {
					$url = 'https://payment.klarna.com';
				} else {
					$url = 'https://payment-beta.klarna.com';
				}
				
				$curl = curl_init();
	
				$header  = 'Content-Type: text/xml' . "\n";
				$header .= 'Content-Length: ' . strlen($xml) . "\n";
	
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_HEADER, $header);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
	
				$response = curl_exec($curl);
				
				if ($response !== false) {
					$xml = new DOMDocument();
					$xml->loadXML($response);
	
					$xpath = new DOMXPath($xml);
	
					$nodes = $xpath->query('//methodResponse/params/param/value');
	
					if ($nodes->length == 0) {
						$this->error['warning'] = $this->language->get('error_log');
						
                    	$error_code = $xpath->query('//methodResponse/fault/value/struct/member/value/int')->item(0)->nodeValue;
                    	$error_message = $xpath->query('//methodResponse/fault/value/struct/member/value/string')->item(0)->nodeValue;
											
						$log->write(sprintf($this->language->get('error_pclass'), $key, $error_code, $error_message));
						
						continue;
					}
	
					$pclasses = $this->parseResponse($nodes->item(0)->firstChild, $xml);
	
					while ($pclasses) {
						$pclass = array_slice($pclasses, 0, 10);
						$pclasses = array_slice($pclasses, 10);
	
						$pclass[3] /= 100;
						$pclass[4] /= 100;
						$pclass[5] /= 100;
						$pclass[6] /= 100;
						$pclass[9] = ($pclass[9] != '-') ? strtotime($pclass[9]) : $pclass[9];
	
						array_unshift($pclass, $klarna_account['merchant']);
	
						$this->pclasses[$key][] = array(
							'eid'          => intval($pclass[0]),
							'id'           => intval($pclass[1]),
							'description'  => $pclass[2],
							'months'       => intval($pclass[3]),
							'startfee'     => floatval($pclass[4]),
							'invoicefee'   => floatval($pclass[5]),
							'interestrate' => floatval($pclass[6]),
							'minamount'    => floatval($pclass[7]),
							'country'      => intval($pclass[8]),
							'type'         => intval($pclass[9]),
						);
					}
				} else {
					$this->error['warning'] = $this->language->get('error_log');
					
					$log->write(sprintf($this->language->get('error_curl'), curl_errno($curl), curl_error($curl)));
				}
				
				curl_close($curl);
			}
		}

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
	
    private function parseResponse($node, $document) {
        $child = $node;

        switch ($child->nodeName) {
            case 'string':
                $value = $child->nodeValue;
                break;
            case 'boolean':
                $value = (string)$child->nodeValue;

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
                $value = (int)$child->nodeValue;
                break;
            case 'array':
                $value = array();
                
                $xpath = new DOMXPath($document);
                $entries = $xpath->query('.//array/data/value', $child);
                
                for ($i = 0; $i < $entries->length; $i++) {
                    $value[] = $this->parseResponse($entries->item($i)->firstChild, $document);
                }

                break;
            default:
                $value = null;
        }
		
		return $value;
	}  
    
    public function clear() {
        $this->language->load('payment/klarna_account');

		$file = DIR_LOGS . 'klarna_account.log';
		
		$handle = fopen($file, 'w+'); 
				
		fclose($handle); 		
		
		$this->session->data['success'] = $this->language->get('text_success');
        
        $this->redirect($this->url->link('payment/klarna_account', 'token=' . $this->session->data['token'], 'SSL'));
    }
}
?>