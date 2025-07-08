<?php
class ControllerExtensionPaymentCardinity extends Controller {
	private $error = array();

	private $external_mode_only;

	public function index() {

		$this->load->model('extension/payment/cardinity');
		$this->model_extension_payment_cardinity->createMissingTables();

		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		$this->load->language('extension/payment/cardinity');

		//if using old version
		if(version_compare(phpversion(), '7.2.5', '<') == true){
			$this->external_mode_only = true;
			$this->config->set('payment_cardinity_external', 1);
			$this->model_setting_setting->editSettingValue('payment_cardinity', 'payment_cardinity_external', 1); 
		}
	

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$commonSettings = $this->request->post;
			unset($commonSettings['api_data']);
			$this->model_setting_setting->editSetting('payment_cardinity', $commonSettings, 0);
			
			if (isset($this->request->post['api_data'])) {
				foreach($this->request->post['api_data'] as $store_Id => $storeAPIData){					
					$allSettings = array_merge($storeAPIData, $commonSettings);
					$this->model_setting_setting->editSetting('payment_cardinity', $allSettings, $store_Id);	
				}				
			}


			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}


		
		if( isset($this->error['api_data']) ){

			//mark error by store
			foreach($this->error['api_data'] as $storeId => $data){
				
				//internal
				if (isset($this->error['api_data'][$storeId]['key'])) {
					$data['error_key'][$storeId] = $this->error['api_data'][$storeId]['key'];
				} else {
					$data['error_key'][$storeId] = '';
				}	
				if (isset($this->error['api_data'][$storeId]['secret'])) {
					$data['error_secret'][$storeId] = $this->error['api_data'][$storeId]['secret'];
				} else {
					$data['error_secret'][$storeId] = '';
				}	

				//external
				if (isset($this->error['api_data'][$storeId]['project_key'])) {
					$data['error_project_key'][$storeId] = $this->error['api_data'][$storeId]['project_key'];
				} else {
					$data['error_project_key'][$storeId] = '';
				}	
				if (isset($this->error['api_data'][$storeId]['project_secret'])) {
					$data['error_project_secret'][$storeId] = $this->error['api_data'][$storeId]['project_secret'];
				} else {
					$data['error_project_secret'][$storeId] = '';
				}	
			}
		}
		
	

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/cardinity', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/cardinity', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);


		
		$listOfStore = $this->model_setting_store->getStores(); 
		
		
		//add a index for the default store
		array_unshift($listOfStore, array(
			'store_id' => 0,
			'name' => 'Default'
		));

		foreach($listOfStore as $store){

			$storeId = $store['store_id'];

			if (isset($this->request->post['api_data'][$storeId]['payment_cardinity_key'])) {
				$data['api_data'][$storeId]['payment_cardinity_key'] = $this->request->post['api_data'][$storeId]['payment_cardinity_key'];
			} else {
				$data['api_data'][$storeId]['payment_cardinity_key'] =  $this->model_setting_setting->getSettingValue('payment_cardinity_key', $storeId);				//$this->config->get('payment_cardinity_key', 1 );
			}
	
			if (isset($this->request->post['api_data'][$storeId]['payment_cardinity_secret'])) {
				$data['api_data'][$storeId]['payment_cardinity_secret'] = $this->request->post['api_data'][$storeId]['payment_cardinity_secret'];
			} else {
				$data['api_data'][$storeId]['payment_cardinity_secret'] = $this->model_setting_setting->getSettingValue('payment_cardinity_secret', $storeId);
			}
	
			if (isset($this->request->post['api_data'][$storeId]['payment_cardinity_project_key'])) {
				$data['api_data'][$storeId]['payment_cardinity_project_key'] = $this->request->post['api_data'][$storeId]['payment_cardinity_project_key'];
			} else {
				$data['api_data'][$storeId]['payment_cardinity_project_key'] = $this->model_setting_setting->getSettingValue('payment_cardinity_project_key', $storeId);
			}
	
			if (isset($this->request->post['api_data'][$storeId]['payment_cardinity_project_secret'])) {
				$data['api_data'][$storeId]['payment_cardinity_project_secret'] = $this->request->post['api_data'][$storeId]['payment_cardinity_project_secret'];
			} else {
				$data['api_data'][$storeId]['payment_cardinity_project_secret'] = $this->model_setting_setting->getSettingValue('payment_cardinity_project_secret', $storeId);
			}

		}



		//Common fields for all stores

		if (isset($this->request->post['payment_cardinity_external'])) {
			$data['payment_cardinity_external'] = $this->request->post['payment_cardinity_external'];
		} else {
			$data['payment_cardinity_external'] = $this->config->get('payment_cardinity_external');
		}

		if (isset($this->request->post['payment_cardinity_debug'])) {
			$data['payment_cardinity_debug'] = $this->request->post['payment_cardinity_debug'];
		} else {
			$data['payment_cardinity_debug'] = $this->config->get('payment_cardinity_debug');
		}

		if (isset($this->request->post['payment_cardinity_total'])) {
			$data['payment_cardinity_total'] = $this->request->post['payment_cardinity_total'];
		} else {
			$data['payment_cardinity_total'] = $this->config->get('payment_cardinity_total');
		}

		if (isset($this->request->post['payment_cardinity_order_status_id'])) {
			$data['payment_cardinity_order_status_id'] = $this->request->post['payment_cardinity_order_status_id'];
		} else {
			$data['payment_cardinity_order_status_id'] = $this->config->get('payment_cardinity_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_cardinity_geo_zone_id'])) {
			$data['payment_cardinity_geo_zone_id'] = $this->request->post['payment_cardinity_geo_zone_id'];
		} else {
			$data['payment_cardinity_geo_zone_id'] = $this->config->get('payment_cardinity_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_cardinity_status'])) {
			$data['payment_cardinity_status'] = $this->request->post['payment_cardinity_status'];
		} else {
			$data['payment_cardinity_status'] = $this->config->get('payment_cardinity_status');
		}

		if (isset($this->request->post['payment_cardinity_sort_order'])) {
			$data['payment_cardinity_sort_order'] = $this->request->post['payment_cardinity_sort_order'];
		} else {
			$data['payment_cardinity_sort_order'] = $this->config->get('payment_cardinity_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		$data['php_version_old'] = $this->external_mode_only;
		$data['entry_log'] = $this->language->get('entry_log');
		$data['yearNow'] = (int) date("Y");
		$data['entry_log_action'] = $this->url->link('extension/payment/cardinity/getTransactions',  'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		

		$data['stores_data'] = print_r($this->model_setting_store->getStores(), true);
		$data['stores'] = $this->model_setting_store->getStores();

		$this->response->setOutput($this->load->view('extension/payment/cardinity', $data));
	}

	public function order() {
		$this->load->language('extension/payment/cardinity');

		$data['user_token'] = $this->session->data['user_token'];
		$data['order_id'] = $this->request->get['order_id'];

		return $this->load->view('extension/payment/cardinity_order', $data);
	}

	public function getPayment() {

		if(version_compare(phpversion(), '7.2.5', '<') == true){
			$this->response->setOutput($this->load->view('extension/payment/cardinity_order_na'));
			return;
		}

		
		$this->load->language('extension/payment/cardinity');

		$this->load->model('extension/payment/cardinity');

		$data['column_refund'] = $this->language->get('column_refund');
		$data['column_date'] = $this->language->get('column_date');
		$data['column_refund_history'] = $this->language->get('column_refund_history');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_description'] = $this->language->get('column_description');

		$data['button_refund'] = $this->language->get('button_refund');

		$data['user_token'] = $this->session->data['user_token'];

		$client = $this->model_extension_payment_cardinity->createClient(array(
			'key'    => $this->config->get('payment_cardinity_key'),
			'secret' => $this->config->get('payment_cardinity_secret')
		));

		$order = $this->model_extension_payment_cardinity->getOrder($this->request->get['order_id']);

		$data['payment'] = false;

		$data['refunds'] = array();

		if ($order && $order['payment_id']) {
			$data['payment'] = true;

			$payment = $this->model_extension_payment_cardinity->getPayment($client, $order['payment_id']);

            if(!$payment){
                return;
            }

			$data['refund_action'] = false;

			$successful_statuses = array(
				'approved'
			);

			if (in_array($payment->getStatus(), $successful_statuses)) {
				$data['refund_action'] = true;
			}

			$max_refund_amount = $payment->getAmount();

			$refunds = $this->model_extension_payment_cardinity->getRefunds($client, $order['payment_id']);

			if ($refunds) {
				foreach ($refunds as $refund) {
					$successful_refund_statuses = array(
						'approved'
					);

					if (in_array($refund->getStatus(), $successful_refund_statuses)) {
						$max_refund_amount -= $refund->getAmount();
					}

					$data['refunds'][] = array(
						'date_added'  => date($this->language->get('datetime_format'), strtotime($refund->getCreated())),
						'amount'	  => $this->currency->format($refund->getAmount(), $refund->getCurrency(), '1.00000000', true),
						'status'	  => $refund->getStatus(),
						'description' => $refund->getDescription()
					);
				}
			}

			if (!$max_refund_amount) {
				$data['refund_action'] = false;
			}

			$data['payment_id'] = $payment->getId();
			$data['symbol_left'] = $this->currency->getSymbolLeft($payment->getCurrency());
			$data['symbol_right'] = $this->currency->getSymbolRight($payment->getCurrency());

			$data['max_refund_amount'] = $this->currency->format($max_refund_amount, $payment->getCurrency(), '1.00000000', false);
		}

		$this->response->setOutput($this->load->view('extension/payment/cardinity_order_ajax', $data));
	}

	public function refund() {
		$this->load->language('extension/payment/cardinity');

		$this->load->model('extension/payment/cardinity');

		$json = array();

		$success = $error = '';

		$client = $this->model_extension_payment_cardinity->createClient(array(
			'key'    => $this->config->get('payment_cardinity_key'),
			'secret' => $this->config->get('payment_cardinity_secret')
		));

		$refund = $this->model_extension_payment_cardinity->refundPayment($client, $this->request->post['payment_id'], (float)number_format($this->request->post['amount'], 2), $this->request->post['description']);

		if ($refund) {
            if($refund->isApproved()){
                $success = $this->language->get('refund_approved');
            }else if($refund->isProcessing()){
                $success = $this->language->get('refund_processing');
            }else {
                $error = $this->language->get('refund_declined');
            }
            $json['refund'] = array(
                'date_added'  => date($this->language->get('datetime_format'), strtotime($refund->getCreated())),
                'amount'	  => $this->currency->format($refund->getAmount(), $refund->getCurrency(), '1.00000000', true),
                'status' =>  $refund->getStatus(),
                'description' =>  $refund->getDescription(),
            );
		} else {
			$error = $this->language->get('text_error_generic');
		}

		$json['success'] = $success;
		$json['error'] = $error;

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		$this->load->model('extension/payment/cardinity');

		$check_credentials = true;

		if (version_compare(phpversion(), '5.4', '<')) {
			$this->error['warning'] = $this->language->get('error_php_version');
		}

		if (!$this->user->hasPermission('modify', 'extension/payment/cardinity')) {
			$this->error['warning'] = $this->language->get('error_permission');

			$check_credentials = false;
		}

		
		if ($this->request->post['payment_cardinity_external'] == 0) {

			//validate by each store
			foreach($this->request->post['api_data'] as $storeId => $storeAPIdata){
				
				//Validate required feilds for internal integration 
				if (!$this->request->post['api_data'][$storeId]['payment_cardinity_key']) {
					$this->error['api_data'][$storeId]['key'] = $this->language->get('error_key');
					
					$check_credentials = false;
				}
		
				if (!$this->request->post['api_data'][$storeId]['payment_cardinity_secret']) {
					$this->error['api_data'][$storeId]['secret'] = $this->language->get('error_secret')."!";					
					
					$check_credentials = false;
				}
				if (!class_exists('Cardinity\Client')) {
					$this->error['warning'] = $this->language->get('error_composer');
		
					$check_credentials = false;
				}

				if ($check_credentials) {
					$client = $this->model_extension_payment_cardinity->createClient(array(
						'key'    => $this->request->post['api_data'][$storeId]['payment_cardinity_key'],
						'secret' => $this->request->post['api_data'][$storeId]['payment_cardinity_secret']
					));
		
					$verify_credentials = $this->model_extension_payment_cardinity->verifyCredentials($client);
		
					if (!$verify_credentials) {						
						$this->error['warning'] = $this->language->get('error_connection');
						
						
					}
				}
				
			}		
			

		}elseif ($this->request->post['payment_cardinity_external'] == 1) {			

			//validate by each store
			foreach($this->request->post['api_data'] as $storeId => $storeAPIdata){
				
				//validate required fields for external hosted payment
				if (!$this->request->post['api_data'][$storeId]['payment_cardinity_project_key']) {
					$this->error['api_data'][$storeId]['project_key'] = $this->language->get('error_project_key');
		
					$check_credentials = false;
				}
		
				if (!$this->request->post['api_data'][$storeId]['payment_cardinity_project_secret']) {
					$this->error['api_data'][$storeId]['project_secret'] = $this->language->get('error_project_secret');
		
					$check_credentials = false;
				}		

			}			
		}

		

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	public function getTransactions(){
		
		if(isset($this->request->post['cardinity_error_log'])){
			$fileName = 'cardinity';
		}else{
			$fileName = 'crd-transactions-'.$this->request->post['cardinity_trns_year'].'-'. $this->request->post['cardinity_trns_month'];
		}	
		
		
		$fileToDownload =  $fileName .'.log';
		
		// Process download
		if(file_exists(DIR_LOGS.$fileToDownload)) {
		   
			$downloadName = $fileName. time(). '.log';
		
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.basename($downloadName));
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize(DIR_LOGS.$fileToDownload));
			readfile(DIR_LOGS.$fileToDownload);
			exit;

        } else {
			$this->response->redirect($this->url->link('extension/payment/cardinity', 'user_token=' . $this->session->data['user_token'], true));
		}				

	}

	public function install() {
		$this->load->model('extension/payment/cardinity');

		$this->model_extension_payment_cardinity->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/cardinity');

		$this->model_extension_payment_cardinity->uninstall();
	}
}