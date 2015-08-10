<?php
class ControllerAccountLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->model('account/customer');

		// Login override for admin users
		if (!empty($this->request->get['token'])) {
			$this->overrideAdminLogin();
		}

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account',  '',  'SSL'));
		}

		$this->load->language('account/login');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->processValidPostRequest();
		}

		$data['breadcrumbs'] = $this->getBreadcrumbs();

		$this->processAdditionalDataParameters($data);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/login.tpl',  $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/login.tpl',  $data));
		}
	}

	protected function validate() {
		$this->event->trigger('pre.customer.login');

		// Check how many login attempts have been made.
		$login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			if (!$this->customer->login($this->request->post['email'],  $this->request->post['password'])) {
				$this->error['warning'] = $this->language->get('error_login');

				if (isset($this->request->post['email']) && !empty($this->request->post['email'])) {
					$this->model_account_customer->addLoginAttempt($this->request->post['email']);
				}
			} else {
				if (isset($this->request->post['email']) && !empty($this->request->post['email'])) {
					$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
				}
			}
		}

		return !$this->error;
	}

	/**
	 * @param $data
	 */
	private function getBreadcrumbs()
	{
		$breadcrumbs = array();

		$breadcrumbs[] = array(
			'text' => $this->language->get('text_home'), 
			'href' => $this->url->link('common/home')
		);

		$breadcrumbs[] = array(
			'text' => $this->language->get('text_account'), 
			'href' => $this->url->link('account/account',  '',  'SSL')
		);

		$breadcrumbs[] = array(
			'text' => $this->language->get('text_login'), 
			'href' => $this->url->link('account/login',  '',  'SSL')
		);

		return $breadcrumbs;
	}

	private function overrideAdminLogin() {
		$this->customer->logout();
		$this->cart->clear();

		$vars = 'wishlist, payment_address, payment_method, payment_methods, shipping_address, shipping_method,'
			. 'shipping_methods, comment, order_id, coupon, reward, voucher, vouchers';

		$this->session->unsetVariables($vars);

		$customer_info = $this->model_account_customer->getCustomerByToken($this->request->get['token']);

		if ($customer_info && $this->customer->login($customer_info['email'], '', true)) {

			$this->loadDefaultAddress();

			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}
	}

	private function processValidPostRequest()
	{
		$this->event->trigger('pre.customer.login');

		unset($this->session->data['guest']);

		// Restore customers cart
		if ($this->customer->getCart()) {
			foreach ($this->customer->getCart() as $key => $value) {
				$this->cart->add($key, $value);
			}
		}

		// Restore customers wish list
		if ($this->customer->getWishlist()) {
			if (!isset($this->session->data['wishlist'])) {
				$this->session->data['wishlist'] = array();
			}

			foreach ($this->customer->getWishlist() as $product_id) {
				if (!in_array($product_id, $this->session->data['wishlist'])) {
					$this->session->data['wishlist'][] = $product_id;
				}
			}
		}

		$this->loadDefaultAddress();

		// Add to activity log
		$this->load->model('account/activity');

		$activity_data = array(
			'customer_id' => $this->customer->getId(),
			'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
		);

		$this->model_account_activity->addActivity('login', $activity_data);

		// Trigger customer post login event
		$this->event->trigger('post.customer.login');

		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$this->response->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
		} else {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}
	}

	/**
	 * @param $data
	 */
	private function processAdditionalDataParameters(&$data)
	{
		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);
		} else {
			$data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
	}

	private function loadDefaultAddress()
	{
		$config_tax_customer = $this->config->get('config_tax_customer');
		if ($config_tax_customer == 'payment' || $config_tax_customer == 'shipping') {
			// Default Addresses
			$this->load->model('account/address');
			$addressId = $this->customer->getAddressId();
			$this->session->data[$config_tax_customer . '_address'] = $this->model_account_address->getAddress($addressId);
		}
	}
}