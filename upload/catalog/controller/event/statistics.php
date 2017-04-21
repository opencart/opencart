<?php
class ControllerEventStatistics extends Controller {
	// model/account/customer/addCustomer/after
	public function addCustomer(&$route, &$args, &$output) {
		$this->load->model('account/customer');
		
		$customer_info = $this->model_account_customer->getCustomer($output);
		
		if ($customer_info && !$customer_info['approved']) {
			$this->load->model('report/statistics');
	
			$this->model_report_statistics->addValue('customer', 1);
		}
	}
	
	// model/account/customer/addAffiliate/after
	public function addAffiliate(&$route, &$args, &$output) {
		$this->load->model('account/customer');
		
		$affiliate_info = $this->model_account_customer->getAffiliate($output);		
		
		if ($affiliate_info && !$affiliate_info['status']) {
			$this->load->model('report/statistics');

			$this->model_report_statistics->addValue('affiliate', 1);
		}
	}
	
	// model/account/review/addReview/after
	public function addReview(&$route, &$args, &$output) {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('review', 1);	
	}
		
	// model/account/return/addReturn/after
	public function addReturn(&$route, &$args, &$output) {
		$this->load->model('report/statistics');

		$this->model_report_statistics->addValue('return', 1);	
	}	
	
	// model/checkout/order/addOrderHistory/before
	public function addOrderHistory(&$route, &$args, &$output) {
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($args[0]);

		if ($order_info) {
			$this->load->model('report/statistics');
			
			// If order status in complete or proccessing add value to sale total
			if (in_array($args[1], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				$this->model_report_statistics->addValue('order_sale', $order_info['total']);	
			}
			
			// If order status not in complete or proccessing remove value to sale total
			if (!in_array($args[1], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				$this->model_report_statistics->removeValue('order_sale', $order_info['total']);
			}
			
			// Remove from processing status if new status is not array
			if (in_array($order_info['order_status_id'], $this->config->get('config_processing_status')) && !in_array($args[1], $this->config->get('config_processing_status'))) {
				$this->model_report_statistics->removeValue('order_processing', 1);
			}
			
			// Add to processing status if new status is not array		
			if (!in_array($order_info['order_status_id'], $this->config->get('config_processing_status')) && in_array($args[1], $this->config->get('config_processing_status'))) {
				$this->model_report_statistics->addValue('order_processing', 1);
			}
			
			// Remove from complete status if new status is not array
			if (in_array($order_info['order_status_id'], $this->config->get('config_complete_status')) && !in_array($args[1], $this->config->get('config_complete_status'))) {
				$this->model_report_statistics->removeValue('order_complete', 1);
			}
			
			// Add to complete status if new status is not array		
			if (!in_array($order_info['order_status_id'], $this->config->get('config_complete_status')) && in_array($args[1], $this->config->get('config_complete_status'))) {
				$this->model_report_statistics->addValue('order_complete', 1);
			}			
		}
	}
}