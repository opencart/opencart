<?php
class ControllerPaymentKlarnaInvoice extends Controller {
    private $error = array();

    public function index() {
		$this->language->load('payment/klarna_invoice');
        
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$status = false;
			
			foreach ($this->request->post['klarna_invoice'] as $klarna_invoice) {
				if ($klarna_invoice['status']) {
					$status = true;
					
					break;
				}
			}			
			
			$data = array(
				'klarna_invoice_pclasses' => $this->pclasses,
				'klarna_invoice_status'   => $status
			);
			
			$this->model_setting_setting->editSetting('klarna_invoice', array_merge($this->request->post, $data));
			
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
            'href'      => $this->url->link('payment/klarna_invoice', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
		
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		
        $this->data['action'] = $this->url->link('payment/klarna_invoice', 'token=' . $this->session->data['token'], 'SSL');
       
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

		if (isset($this->request->post['klarna_invoice'])) {
			$this->data['klarna_invoice'] = $this->request->post['klarna_invoice'];
		} else {
			$this->data['klarna_invoice'] = $this->config->get('klarna_invoice');
		}
		
		$this->load->model('localisation/geo_zone');
			
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');
			
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        
		$file = DIR_LOGS . 'klarna_invoice.log';
        
        if (file_exists($file)) {
            $this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
        } else {
            $this->data['log'] = '';
        }
        
        $this->data['clear'] = $this->url->link('payment/klarna_invoice/clear', 'token=' . $this->session->data['token'], 'SSL'); 

        $this->template = 'payment/klarna_invoice.tpl';
        $this->children = array(
            'common/header',
            'common/footer',
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/klarna_invoice')) {
            $this->error['warning'] = $this->language->get('error_permission');
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
                    $value[] = $this->parseResponse($entries->item($i)->firstChild, $document);
                }

                break;

            default:
                $value = null;
        }

        return $value;
    }
	
    public function clear() {
        $this->language->load('payment/klarna_invoice');
		
		$file = DIR_LOGS . 'klarna_invoice.log';
		
		$handle = fopen($file, 'w+'); 
				
		fclose($handle); 
				
		$this->session->data['success'] = $this->language->get('text_success');
        
        $this->redirect($this->url->link('payment/klarna_invoice', 'token=' . $this->session->data['token'], 'SSL'));
    }    
}
