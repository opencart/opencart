<?php
class ControllerEventActivity extends Controller {
	// model/account/customer/addCustomer/after
	public function addCustomer(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $customer_id,
				'name'        => $args[2]['firstname'] . ' ' . $args[2]['lastname']
			);

			$this->model_account_activity->addActivity('register', $activity_data);
		}
	}
	
	// model/account/customer/editCustomer/after
	public function editCustomer(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('edit', $activity_data);
		}
	}
	
	// model/account/customer/editPassword/after
	public function editPassword(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');
			
			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				);
	
				$this->model_account_activity->addActivity('password', $activity_data);
			} else {
				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('reset', $activity_data);
			}	
		}
	}
	
	// model/account/customer/deleteLoginAttempts
	public function login(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('login', $activity_data);
		}	
	}
	
	// 
	public function forgotten(&$route, &$args, &$output) { 
		if ($this->config->get('config_customer_activity') && $this->request->get['route'] == 'account/forgotten') {
			$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);

			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}
		}	
	}
	
	// model/account/address/addAddress/after
	public function addAddress(&$route, &$args, &$output) { 
		// Add to activity log
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('address_add', $activity_data);
		}	
	}
	
	// model/account/address/editAddress/after
	public function editAddress(&$route, &$args, &$output) { 
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('address_edit', $activity_data);
		}	
	}
	
	// model/account/address/deleteAddress/after
	public function deleteAddress(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);
			
			$this->model_account_activity->addActivity('address_delete', $activity_data);
		}
	}
	
	// model/account/return/addReturn/after
	public function addReturn(&$route, &$args, &$output) {
		if ($this->config->get('config_customer_activity') && $output) {
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'return_id'   => $output
				);

				$this->model_account_activity->addActivity('return_account', $activity_data);
			} else {
				$activity_data = array(
					'name'      => $args[1]['firstname'] . ' ' . $args[1]['lastname'],
					'return_id' => $output
				);

				$this->model_account_activity->addActivity('return_guest', $activity_data);
			}
		}
	}	
	
	// model/checkout/order/addOrderHistory/after
	public function addOrderHistory(&$route, &$args, &$output) {	
		if ($this->config->get('config_customer_activity') && isset($args['last_status_id'])) {
			if (!in_array($args['last_status_id'], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status'))) && in_array($args[0], array_merge($this->config->get('config_processing_status'), $this->config->get('config_complete_status')))) {
				$this->load->model('account/activity');
	
				if ($this->customer->isLogged()) {
					$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
						'order_id'    => $args[0]['order_id']
					);
	
					$this->model_account_activity->addActivity('order_account', $activity_data);
				} else {
					$activity_data = array(
						'name'     => $this->session->data['guest']['firstname'] . ' ' . $this->session->data['guest']['lastname'],
						'order_id' => $this->session->data['order_id']
					);
	
					$this->model_account_activity->addActivity('order_guest', $activity_data);
				}
			}
		}
	}
}