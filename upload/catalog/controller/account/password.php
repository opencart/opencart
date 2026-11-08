<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Password
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Password extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/password');

		$json = [];

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/password', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
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
			$this->load->model('account/customer');

			$this->model_account_customer->editPassword($this->customer->getEmail(), $post_info['password']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
