<?php
class ControllerReportStatistics extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/statistics');

		$this->getList();	
	}
	
	public function ordersale() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/statistics');		

		if ($this->validate()) {
			$this->load->model('sale/order');
			
			$this->model_report_statistics->editValue('order_sale', $this->model_sale_order->getTotalSales(array('filter_order_status' => implode(',', array_merge($this->config->get('config_complete_status'), $this->config->get('config_processing_status'))))));		
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->getList();	
	}
		
	public function orderprocessing() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/statistics');		

		if ($this->validate()) {
			$this->load->model('sale/order');
			
			$this->model_report_statistics->editValue('order_processing', $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_processing_status')))));		
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->getList();	
	}
	
	public function ordercomplete() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));		
		
		$this->load->model('report/statistics');
		
		if ($this->validate()) {
			$this->load->model('sale/order');
			
			$this->model_report_statistics->editValue('order_complete', $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_complete_status')))));		
		
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));
		}		
		
		$this->getList();	
	}
	
	public function orderother() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));	
		
		$this->load->model('report/statistics');
		
		if ($this->validate()) {
			$this->load->model('localisation/order_status');
				
			$order_status_data = array();
	
			$results = $this->model_localisation_order_status->getOrderStatuses();
	
			foreach ($results as $result) {
				if (!in_array($result['order_status_id'], array_merge($this->config->get('config_complete_status'), $this->config->get('config_processing_status')))) {
					$order_status_data[] = $result['order_status_id'];
				}
			}		
			
			$this->load->model('sale/order');
			
			$this->model_report_statistics->editValue('order_other', $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $order_status_data))));
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->getList();	
	}

	public function returns() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));	
				
		$this->load->model('report/statistics');
		
		if ($this->validate()) {
			$this->load->model('sale/return');
			
			$this->model_report_statistics->editValue('return', $this->model_sale_return->getTotalReturns(array('filter_return_status_id' => $this->config->get('config_return_status_id'))));
		
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));		
		}
		
		$this->getList();	
	}
	
	public function customer() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));	
				
		$this->load->model('report/statistics');
		
		if ($this->validate()) {	
			$this->load->model('customer/customer');
			
			$this->model_report_statistics->editValue('customer', $this->model_customer_customer->getTotalCustomers(array('filter_approved' => 0)));
		
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));		
		}
		
		$this->getList();	
	}	
		
	public function affiliate() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));	
				
		$this->load->model('report/statistics');
		
		if ($this->validate()) {
			$this->load->model('customer/customer');
	
			$this->model_report_statistics->editValue('affiliate', $this->model_customer_customer->getTotalAffiliates(array('filter_approved' => 0)));
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->getList();				
	}

	public function product() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));	
				
		$this->load->model('report/statistics');
		
		if ($this->validate()) {		
			$this->load->model('catalog/product');
			
			$this->model_report_statistics->editValue('product', $this->model_catalog_product->getTotalProducts(array('filter_quantity' => 0)));

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));
		}
		
		$this->getList();
	}	
	
	public function review() {
		$this->load->language('report/statistics');

		$this->document->setTitle($this->language->get('heading_title'));	
				
		$this->load->model('report/statistics');	
		
		if ($this->validate()) {	
			$this->load->model('catalog/review');
				
			$this->model_report_statistics->editValue('review', $this->model_catalog_review->getTotalReviews(array('filter_status' => 0)));
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getList();
	}
	
	public function getList() {
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/statistics', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['statistics'] = array();
		
		$this->load->model('report/statistics');
		
		$results = $this->model_report_statistics->getStatistics();
		
		foreach ($results as $result) {
			$data['statistics'][] = array(
				'name'  => $this->language->get('text_' . $result['code']),
				'value' => $result['value'],
				'href'  => $this->url->link('report/statistics/' . str_replace('_', '', $result['code']), 'user_token=' . $this->session->data['user_token'], true)
			);
		}
				
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
							
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/statistics', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'report/statistics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}	
}