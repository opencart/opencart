<?php
class ControllerEventStatistics extends Controller {
	// model/account/customer/addCustomer/after
	public function addCustomer(&$route, &$args, &$output) {
		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->config->get('config_customer_group_id'));
				
		if ($customer_group_info && !$customer_group_info['approval']) {
			$this->load->model('setting/statistics');
	
			$this->model_report_statistics->addValue('customer', 1);
		}
	}
	
	// model/account/customer/addAffiliate/after
	public function addAffiliate(&$route, &$args, &$output) {
		if ($this->config->get('config_affiliate_approval')) {
			$this->load->model('setting/statistics');

			$this->model_report_statistics->addValue('affiliate', 1);
		}
	}
	
	// model/account/customer/editAffiliate/after
	public function editAffiliate(&$route, &$args, &$output) {
		if ($this->config->get('config_affiliate_approval')) {
			$this->load->model('setting/statistics');

			$this->model_report_statistics->addValue('affiliate', 1);
		}
	}
		
	// model/account/review/addAffiliate/after
	public function addReview(&$route, &$args, &$output) {
		$this->load->model('setting/statistics');

		$this->model_report_statistics->addValue('review', 1);	
	}
		
	// model/account/return/addReturn/after
	public function addReturn(&$route, &$args, &$output) {
		$this->load->model('setting/statistics');

		$this->model_report_statistics->addValue('return', 1);	
	}	
	
	// model/checkout/order/addOrderHistory/before
	public function addOrderHistory(&$route, &$args, &$output) {
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($args[0]);

		if ($order_info) {
			// !$safe && !$override && 
			
			if (in_array($args[1], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				$this->load->model('setting/statistics');
	
				$this->model_report_statistics->addValue('order_sale', $order_info['total']);	
				
				$this->load->model('setting/statistics');
	
				$this->model_report_statistics->addValue('order_sale', $order_info['total']);					
			}
			
			
			if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {

				
			}
		}
	}
}