<?php
class ControllerModuleAmazonPay extends Controller {
	public function index() {

		$this->load->model('payment/amazon_login_pay');

		if ($this->config->get('amazon_login_pay_status') && $this->config->get('amazon_pay_status') && !empty($_SERVER['HTTPS']) && !($this->config->get('amazon_login_pay_minimum_total') > 0 && $this->config->get('amazon_login_pay_minimum_total') > $this->cart->getTotal())) {
			// capital L in Amazon cookie name is required, do not alter for coding standards
			if (!$this->customer->isLogged() && isset($this->request->cookie['amazon_Login_state_cache'])) {
				setcookie('amazon_Login_state_cache', '', time() - 4815162342);
			}

			$amazon_payment_js = $this->model_payment_amazon_login_pay->getWidgetJs();
			$this->document->addScript($amazon_payment_js);

			$data['amazon_login_pay_client_id'] = $this->config->get('amazon_login_pay_client_id');
			$data['amazon_pay_return_url'] = $this->url->link('module/amazon_pay/login', '', 'SSL');
			if ($this->config->get('amazon_login_pay_test') == 'sandbox') {
				$data['amazon_login_pay_test'] = true;
			}

			if ($this->config->get('amazon_pay_button_type')) {
				$data['amazon_pay_button_type'] = $this->config->get('amazon_pay_button_type');
			} else {
				$data['amazon_pay_button_type'] = 'PwA';
			}

			if ($this->config->get('amazon_pay_button_colour')) {
				$data['amazon_pay_button_colour'] = $this->config->get('amazon_pay_button_colour');
			} else {
				$data['amazon_pay_button_colour'] = 'Gold';
			}

			if ($this->config->get('amazon_pay_button_size')) {
				$data['amazon_pay_button_size'] = $this->config->get('amazon_pay_button_size');
			} else {
				$data['amazon_pay_button_size'] = 'medium';
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/amazon_pay.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/amazon_pay.tpl', $data);
			} else {
				return $this->load->view('default/template/module/amazon_pay.tpl', $data);
			}
		}
	}

	public function login() {
		$this->load->model('payment/amazon_login_pay');
		$this->load->model('account/customer');
		$this->load->model('account/customer_group');
		$this->load->language('payment/amazon_login_pay');

		unset($this->session->data['lpa']);
		unset($this->session->data['access_token']);

		if (isset($this->request->get['access_token'])) {
			$this->session->data['access_token'] = $this->request->get['access_token'];
			$user = $this->model_payment_amazon_login_pay->getUserInfo($this->request->get['access_token']);
		}

		if ((array)$user) {
			if (isset($user->error)) {
				$this->model_payment_amazon_login_pay->logger($user->error . ': ' . $user->error_description);
				$this->session->data['lpa']['error'] = $this->language->get('error_login');
				$this->response->redirect($this->url->link('payment/amazon_login_pay/loginFailure', '', 'SSL'));
			}

			if ($this->customer->isLogged() && $this->customer->getEmail() != $user->email) {
				$this->session->data['lpa']['error'] = sprintf($this->language->get('error_login_email'), $this->config->get('config_name'));
				$this->response->redirect($this->url->link('payment/amazon_login_pay/loginFailure', '', 'SSL'));
			} elseif ($this->customer->isLogged()) {
				$this->model_payment_amazon_login_pay->logger('isLogged');
				$this->response->redirect($this->url->link('payment/amazon_login_pay/address', '', 'SSL'));
			}

			$customer_info = $this->model_account_customer->getCustomerByEmail($user->email);
			$this->model_payment_amazon_login_pay->logger($user);

			if ($customer_info) {
				if ($this->validate($user->email)) {
					unset($this->session->data['guest']);

					$this->load->model('account/address');

					if ($this->config->get('config_tax_customer') == 'payment') {
						$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					}

					if ($this->config->get('config_tax_customer') == 'shipping') {
						$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					}

					$this->load->model('account/activity');

					$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
					);

					$this->model_account_activity->addActivity('login', $activity_data);
					$this->model_payment_amazon_login_pay->logger('Customer logged in - ID: ' . $customer_info['customer_id'] . ', Email: ' . $customer_info['email']);
				} else {
					$this->model_payment_amazon_login_pay->logger('Could not login to - ID: ' . $customer_info['customer_id'] . ', Email: ' . $customer_info['email']);
					$this->session->data['lpa']['error'] = $this->language->get('error_login');
					$this->response->redirect($this->url->link('payment/amazon_login_pay/loginFailure', '', 'SSL'));
				}

				$this->response->redirect($this->url->link('payment/amazon_login_pay/address', '', 'SSL'));
			} else {
				$country_id = 0;
				$zone_id = 0;

				$full_name = explode(' ', $user->name);
				$last_name = array_pop($full_name);
				$first_name = implode(' ', $full_name);

				$data = array(
					'customer_group_id' => (int)$this->config->get('config_customer_group_id'),
					'firstname' => $first_name,
					'lastname' => $last_name,
					'email' => $user->email,
					'telephone' => '',
					'fax' => '',
					'password' => uniqid(rand(), true),
					'company' => '',
					'address_1' => '',
					'address_2' => '',
					'city' => '',
					'postcode' => '',
					'country_id' => (int)$country_id,
					'zone_id' => (int)$zone_id,
				);

				$customer_id = $this->model_account_customer->addCustomer($data);

				$this->model_payment_amazon_login_pay->logger('Customer ID created: ' . $customer_id);

				if ($this->validate($user->email)) {
					unset($this->session->data['guest']);

					$this->load->model('account/address');

					if ($this->config->get('config_tax_customer') == 'payment') {
						$this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					}

					if ($this->config->get('config_tax_customer') == 'shipping') {
						$this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
					}

					$this->load->model('account/activity');

					$activity_data = array(
						'customer_id' => $this->customer->getId(),
						'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
					);

					$this->model_account_activity->addActivity('login', $activity_data);
					$this->model_payment_amazon_login_pay->logger('Customer logged in - ID: ' . $customer_id . ', Email: ' . $user->email);
					$this->response->redirect($this->url->link('payment/amazon_login_pay/address', '', 'SSL'));
				} else {
					$this->model_payment_amazon_login_pay->logger('Could not login to - ID: ' . $customer_id . ', Email: ' . $user->email);
					$this->session->data['lpa']['error'] = $this->language->get('error_login');
					$this->response->redirect($this->url->link('payment/amazon_login_pay/loginFailure', '', 'SSL'));
				}
			}
		} else {

			$this->session->data['lpa']['error'] = $this->language->get('error_login');
			$this->response->redirect($this->url->link('payment/amazon_login_pay/loginFailure', '', 'SSL'));
		}
	}

	public function logout() {
		unset($this->session->data['lpa']);
		unset($this->session->data['access_token']);

		// capital L in Amazon cookie name is required, do not alter for coding standards
		if (isset($this->request->cookie['amazon_Login_state_cache'])) {
			setcookie('amazon_Login_state_cache', '', time() - 4815162342);
		}
	}

	protected function validate($email) {
		if (!$this->customer->login($email, '', true)) {
			$this->error['warning'] = $this->language->get('error_login');
		}

		$customer_info = $this->model_account_customer->getCustomerByEmail($email);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}