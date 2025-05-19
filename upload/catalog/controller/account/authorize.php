<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Authorize
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Authorize extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/authorize');

		if (isset($this->request->cookie['customer_authorize'])) {
			$token = $this->request->cookie['customer_authorize'];
		} else {
			$token = '';
		}

		// Make sure the customer is logged in.
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		// Check total attempts
		$this->load->model('account/customer');

		$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

		if ($token_info && $token_info['total'] > 2) {
			$this->response->redirect($this->url->link('account/authorize.reset', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['action'] = $this->url->link('account/authorize.save', 'language=' . $this->config->get('config_language'));

		if (!$token_info) {
			// Create a token that can be stored as a cookie and will be used to identify device is safe.
			$token = oc_token(32);

			$authorize_data = [
				'token'      => $token,
				'ip'         => oc_get_ip(),
				'user_agent' => $this->request->server['HTTP_USER_AGENT']
			];

			$this->model_account_customer->addAuthorize($this->customer->getId(), $authorize_data);

			setcookie('customer_authorize', $token, time() + 60 * 60 * 24 * 90);
		}

		// Set the code to be emailed
		$this->session->data['code'] = oc_token(6);

		if (isset($this->request->get['route']) && !str_starts_with($this->request->get['route'], 'account/authorize')) {
			$args = $this->request->get;

			$route = $args['route'];

			unset($args['route']);
			unset($args['customer_token']);

			$url = '';

			if ($args) {
				$url .= http_build_query($args);
			}

			$data['redirect'] = $this->url->link($route, $url);
		} else {
			$data['redirect'] = '';
		}

		$data['language'] = $this->config->get('config_language');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('account/authorize', $data));
	}

	/**
	 * Send
	 *
	 * @return void
	 */
	public function send(): void {
		$this->load->language('account/authorize');

		$json = [];

		if (isset($this->request->cookie['customer_authorize'])) {
			$token = $this->request->cookie['customer_authorize'];
		} else {
			$token = '';
		}

		// 1. Making sure the customer is logged in.
		if ($this->customer->isLogged()) {
			// 2. If token already exists check its valid
			$this->load->model('account/customer');

			$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

			if (!$token_info) {
				$json['redirect'] = $this->url->link('account/authorize', 'language=' . $this->config->get('config_language'), true);
				// If token is valid and total attempts are more than 2, redirect to unlock page.
			} elseif ($token_info['total'] > 2) {
				$json['redirect'] = $this->url->link('account/authorize.reset', 'language=' . $this->config->get('config_language'), true);
			}
		} else {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Set the code to be emailed
			$this->session->data['code'] = oc_token(6);

			$json['success'] = $this->language->get('text_sent');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('account/authorize');

		$json = [];

		$required = [
			'code'     => '',
			'redirect' => ''
		];

		$post_info = $this->request->post + $required;

		if (isset($this->request->cookie['customer_authorize'])) {
			$token = $this->request->cookie['customer_authorize'];
		} else {
			$token = '';
		}

		// Make sure the customer is logged in.
		if ($this->customer->isLogged()) {
			// If token already exists check its valid
			$this->load->model('account/customer');

			$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

			if (!$token_info) {
				$json['redirect'] = $this->url->link('account/authorize', 'language=' . $this->config->get('config_language'), true);
			} elseif ($token_info['total'] > 2) {
				$json['redirect'] = $this->url->link('account/authorize.reset', 'language=' . $this->config->get('config_language'), true);
			} elseif (!isset($post_info['code']) || !isset($this->session->data['code']) || $post_info['code'] != $this->session->data['code']) {
				$total = $token_info['total'] + 1;

				if ($total <= 2) {
					$json['error'] = $this->language->get('error_code');
				} else {
					unset($this->session->data['code']);

					$json['redirect'] = $this->url->link('account/authorize.reset', 'language=' . $this->config->get('config_language'), true);
				}

				$this->model_account_customer->editAuthorizeTotal($token_info['customer_authorize_id'], $total);
			}
		} else {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			unset($this->session->data['code']);

			// On success we need to reset the attempts and status.
			$this->model_account_customer->editAuthorizeStatus($token_info['customer_authorize_id'], true);
			$this->model_account_customer->editAuthorizeTotal($token_info['customer_authorize_id'], 0);

			if (isset($post_info['redirect'])) {
				$redirect = urldecode(html_entity_decode($post_info['redirect'], ENT_QUOTES, 'UTF-8'));
			} else {
				$redirect = '';
			}

			// Register the cookie for security.
			if ($redirect && str_starts_with($redirect, $this->config->get('config_url'))) {
				$json['redirect'] = $redirect . '&customer_token=' . $this->session->data['customer_token'];
			} else {
				$json['redirect'] = $this->url->link('account/account', 'customer_token=' . $this->session->data['customer_token'], true);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Unlock
	 *
	 * @return void
	 */
	public function reset(): void {
		$this->load->language('account/authorize');

		if (isset($this->request->cookie['customer_authorize'])) {
			$token = $this->request->cookie['customer_authorize'];
		} else {
			$token = '';
		}

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		// Check total attempts
		$this->load->model('account/customer');

		$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

		if (!$token_info || $token_info['total'] <= 2) {
			// Redirect if already have a valid token.
			$this->response->redirect($this->url->link('account/authorize', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['language'] = $this->config->get('config_language');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('account/authorize_reset', $data));
	}

	/**
	 * Confirm
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('account/authorize');

		$json = [];

		if (isset($this->request->cookie['customer_authorize'])) {
			$token = $this->request->cookie['customer_authorize'];
		} else {
			$token = '';
		}

		if ($this->customer->isLogged()) {
			// Check total attempts
			$this->load->model('account/customer');

			$token_info = $this->model_account_customer->getAuthorizeByToken($this->customer->getId(), $token);

			if (!$token_info || $token_info['total'] <= 2) {
				$json['redirect'] = $this->url->link('account/authorize', 'language=' . $this->config->get('config_language'), true);
			}
		} else {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Create reset code
			$this->model_account_customer->addToken($this->customer->getId(), 'authorize', oc_token(32));

			$json['success'] = $this->language->get('text_link');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Reset
	 *
	 * We have to keep the reset method from blocking requests because some email clients will block cross site requests.
	 *
	 * @return void
	 */
	public function unlock(): void {
		$this->load->language('account/authorize');

		if (isset($this->request->get['email'])) {
			$email = urldecode((string)$this->request->get['email']);
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

		// Check total attempts
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getTokenByCode($code);

		if ($customer_info && $customer_info['email'] == $email) {
			$data['text_unlock'] = $this->language->get('text_unlock');

			$this->model_account_customer->resetAuthorizes($customer_info['customer_id']);
		} else {
			$data['text_unlock'] = $this->language->get('text_failed');
		}

		// Reset token so it can't be used again
		$this->model_account_customer->deleteTokenByCode($code);

		// Logout customer
		$this->customer->logout();

		unset($this->session->data['order_id']);
		unset($this->session->data['customer']);
		unset($this->session->data['shipping_address']);
		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_address']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['comment']);
		unset($this->session->data['coupon']);
		unset($this->session->data['reward']);

		// Remove customer token if set
		unset($this->session->data['customer_token']);

		$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('account/authorize_unlock', $data));
	}
}
