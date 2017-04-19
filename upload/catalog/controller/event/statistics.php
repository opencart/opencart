<?php
class ControllerEventActivity extends Controller {
	// model/account/customer/addCustomer/after
	public function addCustomer(&$route, &$args, &$output) {
		$this->load->model('setting/statistics');

		$this->model_report_statistics->addStatisticsValue('customer', 1);
	}
	
	// model/account/customer/addAffiliate/after
	public function addAffiliate(&$route, &$args, &$output) {
		$this->load->model('setting/statistics');

		$this->model_report_statistics->addStatisticsValue('affiliate', 1);
	}	
	
	// model/account/review/addAffiliate/after
	public function addReview(&$route, &$args, &$output) {
		$this->load->model('setting/statistics');

		$this->model_report_statistics->addStatisticsValue('review', 1);	
	}
		
	// model/account/return/addReturn/after
	public function addReturn(&$route, &$args, &$output) {
		$this->load->model('setting/statistics');

		$this->model_report_statistics->addStatisticsValue('return', 1);	
	}	
	
	// model/checkout/order/addOrderHistory/after
	public function addOrderHistory(&$route, &$args, &$output) {
		$this->load->model('checkout/order');
				
		$order_info = $this->model_checkout_order->getOrder($args[0]);

		if ($order_info) {
			// !$safe && !$override && 
			
			if (in_array($args[1], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				$this->load->model('setting/statistics');
	
				$this->model_report_statistics->addStatisticsValue('review', 1);	
	

					$this->model_account_activity->addActivity('order_account', $activity_data);
				} else {
					$activity_data = array(
						'name'     => $order_info['firstname'] . ' ' . $order_info['lastname'],
						'order_id' => $args[0]
					);
	
					$this->model_account_activity->addActivity('order_guest', $activity_data);
				}
			}
			
			
			if (in_array($order_info['order_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && !in_array($order_status_id, array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {

				
			}
		}
	}
}