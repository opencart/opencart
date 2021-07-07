<?php
namespace Opencart\Catalog\Controller\Account;
class Forgotten extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

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

		$data['confirm'] = $this->url->link('account/forgotten|confirm', 'language=' . $this->config->get('config_language'));

		$data['back'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/forgotten', $data));
	}

	public function confirm(): void {
		$this->load->language('account/forgotten');

		$json = [];

		if ($this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));
		}

		if (empty($this->request->post['email']) || (utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$json['error'] = $this->language->get('error_email');
		}

		if (!$json) {
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

			if ($customer_info) {
				$this->model_account_customer->editCode($this->request->post['email'], token(40));
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function reset(): void {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('account/reset');

		if (isset($this->request->get['email'])) {
			$email = $this->request->get['email'];
		} else {
			$email = '';
		}

		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('account/customer');

		$customer_info = $this->model_account_customer->getCustomerByEmail($email);

		if (!$customer_info || !$customer_info['code'] || $customer_info['code'] !== $code) {
			$this->model_account_customer->editCode($email, '');

			$this->session->data['error'] = $this->language->get('error_code');

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/reset', 'language=' . $this->config->get('config_language'))
		];

		$this->session->data['reset_token'] = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);

		$data['reset'] = $this->url->link('account/reset|password', 'language=' . $this->config->get('config_language') . '&reset_token=' . $this->session->data['reset_token'] . '&email=' . urlencode($email) . '&code=' . $code);

		$data['back'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/reset', $data));
	}

	protected function password(): bool {
		$this->load->language('account/reset');

		$keys = [
			'password',
			'confirm'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if (!isset($this->request->get['reset_token']) || !isset($this->session->data['reset_token']) || ($this->session->data['reset_token'] != $this->request->get['reset_token'])) {
			$json['error']['warning'] = $this->language->get('error_token');
		}

		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, 'UTF-8')) > 40)) {
			$json['error']['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$json['error']['confirm'] = $this->language->get('error_confirm');
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_account_customer->editPassword($customer_info['email'], $this->request->post['password']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}



		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}
