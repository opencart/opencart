<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Login
 *
 * Can be loaded using $this->load->controller('account/login');
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Login extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/login');

		$json = [];

		// Stop any undefined index messages.
		$required = [
			'email'    => '',
			'password' => ''
		];

		$post_info = $this->request->post + $required;

		$this->customer->logout();

		if (!isset($this->request->get['login_token']) || !isset($this->session->data['login_token']) || ($this->request->get['login_token'] != $this->session->data['login_token'])) {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Check how many login attempts have been made.
			$this->load->model('account/customer');

			$login_info = $this->model_account_customer->getLoginAttempts($post_info['email']);

			if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
				$json['error']['warning'] = $this->language->get('error_attempts');
			}
		}

		if (!$json) {
			// Check if customer has been approved.
			$customer_info = $this->model_account_customer->getCustomerByEmail($post_info['email']);

			if ($customer_info && !$customer_info['status']) {
				$json['error']['warning'] = $this->language->get('error_approved');
			} elseif (!$this->customer->login($post_info['email'], html_entity_decode($post_info['password'], ENT_QUOTES, 'UTF-8'))) {
				$json['error']['warning'] = $this->language->get('error_login');

				$this->model_account_customer->addLoginAttempt($post_info['email']);
			}
		}

		if (!$json) {
			// Unset any previous data stored in the session.
			unset($this->session->data['order_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);

			// Remove form token from session
			unset($this->session->data['login_token']);

			// Add customer details into session
			$this->session->data['customer'] = [
				'customer_id'       => $customer_info['customer_id'],
				'customer_group_id' => $customer_info['customer_group_id'],
				'firstname'         => $customer_info['firstname'],
				'lastname'          => $customer_info['lastname'],
				'email'             => $customer_info['email'],
				'telephone'         => $customer_info['telephone']
			];

			// Wishlist
			if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
				$this->load->model('account/wishlist');

				foreach ($this->session->data['wishlist'] as $key => $product_id) {
					$this->model_account_wishlist->addWishlist($this->customer->getId(), $product_id);

					unset($this->session->data['wishlist'][$key]);
				}
			}

			// Log the IP info
			$this->model_account_customer->addLogin($this->customer->getId(), oc_get_ip());

			// Create customer token
			$this->session->data['customer_token'] = oc_token(26);

			$this->model_account_customer->deleteLoginAttempts($post_info['email']);
			
			// Return the details of signing up
			$json['customer'] = [
				'customer_id'       => $customer_id,
				'customer_group_id' => $customer_group_id,
				'firstname'         => $post_info['firstname'],
				'lastname'          => $post_info['lastname'],
				'email'             => $post_info['email'],
				'telephone'         => $post_info['telephone'],
				'newsletter'        => $post_info['newsletter'],
				'products'          => $this->cart->getProducts(),
				'addresses'         => $this->model_account_customer->getAddresses(),
				'wishlist'          => $this->model_account_wishlist->getWishlist(),
				'token'             => $this->session->data['customer_token']
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Token
	 *
	 * @return void
	 */	
	public function token(): void {
		$json = [];

		$this->session->data['login_token'] = oc_token(26);

		$json['token'] = $this->session->data['login_token'];

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Validate
	 *
	 * @return bool
	 */
	public function validate(): bool {
		return !(!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token'])));
	}
}
