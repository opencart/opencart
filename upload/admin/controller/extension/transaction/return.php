<?php
class ControllerExtensionTransactionReturn extends Controller {
	public function index() {
		$this->load->language('extension/transaction/return');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/transaction/return', 'user_token=' . $this->session->data['user_token'])
		);

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/transaction/return', $data));	
	}
				
	public function custom_field_approval() {
		$this->load->language('extension/transaction/return');
		
		if (isset($this->request->get['filter_custom_field_id'])) {
			$filter_custom_field_id = $this->request->get['filter_custom_field_id'];
		} else {
			$filter_custom_field_id = '';
		}
		
		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = 0;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}		

		$data['return_custom_fields'] = array();

		$filter_data = array(
			'filter_custom_field_id'	=> $filter_custom_field_id,
			'filter_status'				=> $filter_status,			
			'start'                    	=> ($page - 1) * $this->config->get('config_pagination'),
			'limit'                    	=> $this->config->get('config_pagination')
		);
		
		// Shipping - Compatibility code for old extension folders
		$files = glob(DIR_APPLICATION . 'controller/extension/shipping/*.php');
		
		$this->load->model('extension/transaction/return');

		$this->load->model('customer/custom_field');	

		$custom_field_return_total = $this->model_customer_custom_field->getTotalCustomFields($filter_data);

		$results = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($results as $result) {
			if ($result['location'] == 'return_address') {
				if ($files) {
					$transactions = array();
					
					foreach ($files as $key => $file) {				
						$extension = basename($file, '.php');
						
						if ($this->config->get('shipping_' . $extension . '_status')) {
							$this->load->language('extension/shipping/' . $extension, $extension);

							$transactions[$key][$result['custom_field_id']] = array(
								'name'      	=> $this->language->get($extension . '_heading_title'),
								'code'			=> html_entity_decode($extension, ENT_QUOTES, 'UTF-8'),
							);
						}
					}
				}
				
				$approve_info = $this->model_extension_transaction_return->getApprove($result['custom_field_id']);
				
				$deny_info = $this->model_extension_transaction_return->getDeny($result['custom_field_id']);
				
				if ($transactions) {
					foreach ($transactions as $key => $custom_fields) {
						if (!empty($custom_fields) && is_array($custom_fields)) {
							foreach ($custom_fields as $custom_field => $extension) {
								if (isset($custom_field['custom_field_id']) && $custom_field['custom_field_id'] == $result['custom_field_id']) {
									$data['return_custom_fields'][] = array(
										'name'				=> html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'),
										'status'			=> $result['status'],
										'extension_name'	=> html_entity_decode($extension['name'], ENT_QUOTES, 'UTF-8'),
										'validate'			=> ($approve_info || $deny_info ? true : false),
										'approve'       	=> $this->url->link('extension/transaction/return/approve', 'user_token=' . $this->session->data['user_token'] . '&custom_field_id=' . $result['custom_field_id'] . '&code=' . $extension['code']),
										'deny'          	=> $this->url->link('extension/transaction/return/deny', 'user_token=' . $this->session->data['user_token'] . '&custom_field_id=' . $result['custom_field_id'] . '&code=' . $extension['code']),
									);
								}
							}
						}
					}
				}
			}
		}

		$url = '';

		if (isset($this->request->get['filter_custom_field_id'])) {
			$url .= '&filter_custom_field_id=' . $this->request->get['filter_custom_field_id'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $custom_field_return_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/transaction/return/custom_field_approval', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		));

		$this->response->setOutput($this->load->view('extension/transaction/return_list', $data));
	}

	public function approve() {
		$this->load->language('extension/transaction/return');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/transaction/return')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/transaction/return');
			
			$this->model_extension_transaction_return->approve($this->request->get['custom_field_id'], $this->request->get['code']);
			
			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	
	
	public function deny() {
		$this->load->language('extension/transaction/return');

		$json = array();
				
		if (!$this->user->hasPermission('modify', 'extension/transaction/return')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/transaction/return');
			
			$this->model_extension_transaction_return->deny($this->request->get['custom_field_id'], $this->request->get['code']);
					
			$json['success'] = $this->language->get('text_success');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function install() {
		$this->load->model('extension/transaction/return');
		
		$this->model_extension_transaction_return->install();
	}
	
	public function uninstall() {
		$this->load->model('extension/transaction/return');
		
		$this->model_extension_transaction_return->uninstall();
	}
	
	// admin/model/customer/custom_field/deleteCustomField/after
	public function deleteCustomField(&$route, &$args, &$output) {
		$this->load->model('extension/transaction/return');
		
		$this->model_extension_transaction_return->deleteCustomField($output);
	}
}
