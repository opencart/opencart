<?php
namespace Opencart\Catalog\Controller\Event;
/**
 * Class Activity
 *
 * @package Opencart\Catalog\Controller\Event
 */
class Activity extends \Opencart\System\Engine\Controller {
	// catalog/model/account/customer/addCustomer/after
	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function addCustomer(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = [
				'customer_id' => $output,
				'name'        => $args[0]['firstname'] . ' ' . $args[0]['lastname']
			];

			$this->model_account_activity->addActivity('register', $activity_data);
		}
	}
	
	// catalog/model/account/customer/editCustomer/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function editCustomer(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = [
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			];

			$this->model_account_activity->addActivity('edit', $activity_data);
		}
	}
	
	// catalog/model/account/customer/editPassword/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function editPassword(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');
			
			if ($this->customer->isLogged()) {
				$activity_data = [
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
				];
	
				$this->model_account_activity->addActivity('password', $activity_data);
			} else {
				$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);
		
				if ($customer_info) {
					$activity_data = [
						'customer_id' => $customer_info['customer_id'],
						'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
					];
	
					$this->model_account_activity->addActivity('reset', $activity_data);
				}
			}	
		}
	}
	
	// catalog/model/account/customer/deleteLoginAttempts/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function login(string &$route, array &$args, mixed &$output): void {
		if (isset($this->request->get['route']) && ($this->request->get['route'] == 'account/login' || $this->request->get['route'] == 'checkout/login.save') && $this->config->get('config_customer_activity')) {
			$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);

			if ($customer_info) {
				$this->load->model('account/activity');
	
				$activity_data = [
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				];
	
				$this->model_account_activity->addActivity('login', $activity_data);
			}
		}	
	}
	
	// catalog/model/account/customer/editCode/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function forgotten(string &$route, array &$args, mixed &$output): void {
		if (isset($this->request->get['route']) && $this->request->get['route'] == 'account/forgotten' && $this->config->get('config_customer_activity')) {
			$this->load->model('account/customer');
			
			$customer_info = $this->model_account_customer->getCustomerByEmail($args[0]);

			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = [
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				];

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}
		}	
	}
	
	// catalog/model/account/customer/addTransaction/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function addTransaction(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/customer');
			
			$customer_info = $this->model_account_customer->getCustomer($args[0]);

			if ($customer_info) {
				$this->load->model('account/activity');
	
				$activity_data = [
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname'],
					'order_id'    => $args[3]
				];
	
				$this->model_account_activity->addActivity('transaction', $activity_data);
			}
		}
	}	
	
	// catalog/model/account/affiliate/addAffiliate/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function addAffiliate(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = [
				'customer_id' => $args[0],
				'name'        => $args[1]['firstname'] . ' ' . $args[1]['lastname']
			];

			$this->model_account_activity->addActivity('affiliate_add', $activity_data);
		}
	}	
	
	// catalog/model/account/affiliate/editAffiliate/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function editAffiliate(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = [
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			];

			$this->model_account_activity->addActivity('affiliate_edit', $activity_data);
		}
	}
	
	// catalog/model/account/address/addAddress/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function addAddress(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = [
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			];

			$this->model_account_activity->addActivity('address_add', $activity_data);
		}	
	}
	
	// catalog/model/account/address/editAddress/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function editAddress(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = [
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			];

			$this->model_account_activity->addActivity('address_edit', $activity_data);
		}	
	}
	
	// catalog/model/account/address/deleteAddress/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function deleteAddress(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity')) {
			$this->load->model('account/activity');

			$activity_data = [
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			];
			
			$this->model_account_activity->addActivity('address_delete', $activity_data);
		}
	}
	
	// catalog/model/account/returns/addReturn/after

	/**
	 * @param string $route
	 * @param array  $args
	 * @param mixed  $output
	 *
	 * @return void
	 */
	public function addReturn(string &$route, array &$args, mixed &$output): void {
		if ($this->config->get('config_customer_activity') && $output) {
			$this->load->model('account/activity');

			if ($this->customer->isLogged()) {
				$activity_data = [
					'customer_id' => $this->customer->getId(),
					'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName(),
					'return_id'   => $output
				];

				$this->model_account_activity->addActivity('return_account', $activity_data);
			} else {
				$activity_data = [
					'name'      => $args[0]['firstname'] . ' ' . $args[0]['lastname'],
					'return_id' => $output
				];

				$this->model_account_activity->addActivity('return_guest', $activity_data);
			}
		}
	}	
	
	// catalog/model/checkout/order/addHistory/before

	/**
	 * @param string $route
	 * @param array  $args
	 *
	 * @return void
	 */
	public function addHistory(string &$route, array &$args): void {
		if ($this->config->get('config_customer_activity')) {
			// If the last order status id returns 0, and the new order status is not, then we record it as new order
			$this->load->model('checkout/order');
			
			$order_info = $this->model_checkout_order->getOrder($args[0]);

			if ($order_info && !$order_info['order_status_id'] && $args[1]) {
				$this->load->model('account/activity');
	
				if ($order_info['customer_id']) {
					$activity_data = [
						'customer_id' => $order_info['customer_id'],
						'name'        => $order_info['firstname'] . ' ' . $order_info['lastname'],
						'order_id'    => $args[0]
					];
	
					$this->model_account_activity->addActivity('order_account', $activity_data);
				} else {
					$activity_data = [
						'name'     => $order_info['firstname'] . ' ' . $order_info['lastname'],
						'order_id' => $args[0]
					];
	
					$this->model_account_activity->addActivity('order_guest', $activity_data);
				}
			}
		}
	}
}
