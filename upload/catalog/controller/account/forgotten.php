<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Forgotten
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Forgotten extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/forgotten');

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('account/forgotten', 'language=' . $this->config->get('config_language'))
		];

		$data['confirm'] = $this->url->link('account/forgotten.confirm', 'language=' . $this->config->get('config_language'));
		$data['back'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/forgotten', $data));
	}

	/**
	 * Confirm
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('account/forgotten');

		$json = [];

		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true);
		}

		if (!$json) {
			$post_info = $this->request->post + ['email' => ''];

			// Customer
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getCustomerByEmail($post_info['email']);

			if (!$customer_info) {
				$json['error'] = $this->language->get('error_not_found');
			}
		}

		if (!$json) {
			$this->session->data['success'] = $this->language->get('text_sent');

			$this->model_account_customer->addToken($customer_info['customer_id'], 'password', oc_token(40));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Reset
	 *
	 * @return void
	 */
	public function reset(): void {
		$this->load->language('account/forgotten');

		if (isset($this->request->get['email'])) {
			$email = (string)$this->request->get['email'];
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true));
		}

		// Customer
		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getTokenByCode($code);

		if (!$customer_info || !$customer_info['email'] || $customer_info['email'] != $email || $customer_info['type'] != 'password') {
			$this->session->data['error'] = $this->language->get('error_code');

			$this->model_account_customer->deleteTokenByCode($code);

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_reset'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/forgotten.reset', 'language=' . $this->config->get('config_language'))
		];

		$this->session->data['reset_token'] = oc_token(26);

		$data['save'] = $this->url->link('account/forgotten.password', 'language=' . $this->config->get('config_language') . '&email=' . urlencode($email) . '&code=' . $code . '&reset_token=' . $this->session->data['reset_token']);
		$data['back'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/forgotten_reset', $data));
	}

	/**
	 * Password
	 *
	 * @return void
	 */
	public function password(): void {
		$this->load->language('account/forgotten');

		$json = [];

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

		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true);
		}

		if (!isset($this->request->get['reset_token']) || !isset($this->session->data['reset_token']) || ($this->request->get['reset_token'] != $this->session->data['reset_token'])) {
			$this->session->data['error'] = $this->language->get('error_session');

			$json['redirect'] = $this->url->link('account/forgotten', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Customer
			$this->load->model('account/customer');

			$customer_info = $this->model_account_customer->getTokenByCode($code);

			if (!$customer_info || !$customer_info['email'] || $customer_info['email'] !== $email || $customer_info['type'] != 'password') {
				$this->session->data['error'] = $this->language->get('error_code');

				// Reset token
				$this->model_account_customer->deleteTokenByCode($code);

				$json['redirect'] = $this->url->link('account/forgotten', 'language=' . $this->config->get('config_language'), true);
			}
		}

		if (!$json) {
			$required = [
				'password' => '',
				'confirm'  => ''
			];

			$post_info = $this->request->post + $required;

			$password = html_entity_decode($post_info['password'], ENT_QUOTES, 'UTF-8');

			if (!oc_validate_length($password, (int)$this->config->get('config_password_length'), 40)) {
				$json['error']['password'] = sprintf($this->language->get('error_password_length'), $this->config->get('config_password_length'));
			}

			$required = [];

			if ($this->config->get('config_password_uppercase') && !preg_match('/[A-Z]/', $password)) {
				$required[] = $this->language->get('error_password_uppercase');
			}

			if ($this->config->get('config_password_lowercase') && !preg_match('/[a-z]/', $password)) {
				$required[] = $this->language->get('error_password_lowercase');
			}

			if ($this->config->get('config_password_number') && !preg_match('/[0-9]/', $password)) {
				$required[] = $this->language->get('error_password_number');
			}

			if ($this->config->get('config_password_symbol') && !preg_match('/[^a-zA-Z0-9]/', $password)) {
				$required[] = $this->language->get('error_password_symbol');
			}

			if ($required) {
				$json['error']['password'] = sprintf($this->language->get('error_password'), implode(', ', $required), $this->config->get('config_password_length'));
			}

			if ($post_info['confirm'] != $post_info['password']) {
				$json['error']['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (!$json) {
			$this->session->data['success'] = $this->language->get('text_reset');

			$this->model_account_customer->editPassword($customer_info['email'], $post_info['password']);

			// Remove for token
			unset($this->session->data['reset_token']);

			// Reset token
			$this->model_account_customer->deleteTokenByCode($code);

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
