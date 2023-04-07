<?php
namespace Opencart\Catalog\Controller\Checkout;
class Voucher extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('checkout/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		if (!isset($this->session->data['vouchers'])) {
			$this->session->data['vouchers'] = [];
		}

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
			'text' => $this->language->get('text_voucher'),
			'href' => $this->url->link('checkout/voucher', 'language=' . $this->config->get('config_language'))
		];

		$data['help_amount'] = sprintf($this->language->get('help_amount'), $this->currency->format($this->config->get('config_voucher_min'), $this->session->data['currency']), $this->currency->format($this->config->get('config_voucher_max'), $this->session->data['currency']));

		$this->session->data['voucher_token'] = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);

		$data['save'] = $this->url->link('checkout/voucher.add', 'language=' . $this->config->get('config_language') . '&voucher_token=' . $this->session->data['voucher_token']);

		if ($this->customer->isLogged()) {
			$data['from_name'] = $this->customer->getFirstName() . ' '  . $this->customer->getLastName();
		} else {
			$data['from_name'] = '';
		}

		if ($this->customer->isLogged()) {
			$data['from_email'] = $this->customer->getEmail();
		} else {
			$data['from_email'] = '';
		}

		$data['amount'] = $this->currency->format($this->config->get('config_voucher_min'), $this->config->get('config_currency'), false, false);

		$this->load->model('checkout/voucher_theme');

		$data['voucher_themes'] = $this->model_checkout_voucher_theme->getVoucherThemes();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('checkout/voucher', $data));
	}

	public function add(): void {
		$this->load->language('checkout/voucher');

		$json = [];

		$keys = [
			'to_name',
			'to_email',
			'from_name',
			'from_email',
			'voucher_theme_id',
			'amount',
			'agree'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if (!isset($this->request->get['voucher_token']) || !isset($this->session->data['voucher_token']) || ($this->session->data['voucher_token'] != $this->request->get['voucher_token'])) {
			$json['redirect'] = $this->url->link('checkout/voucher', 'language=' . $this->config->get('config_language'), true);
		}

		if ((oc_strlen($this->request->post['to_name']) < 1) || (oc_strlen($this->request->post['to_name']) > 64)) {
			$json['error']['to_name'] = $this->language->get('error_to_name');
		}

		if ((oc_strlen($this->request->post['to_email']) > 96) || !filter_var($this->request->post['to_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['to_email'] = $this->language->get('error_email');
		}

		if ((oc_strlen($this->request->post['from_name']) < 1) || (oc_strlen($this->request->post['from_name']) > 64)) {
			$json['error']['from_name'] = $this->language->get('error_from_name');
		}

		if ((oc_strlen($this->request->post['from_email']) > 96) || !filter_var($this->request->post['from_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['from_email'] = $this->language->get('error_email');
		}

		if (!$this->request->post['voucher_theme_id']) {
			$json['error']['theme'] = $this->language->get('error_theme');
		}

		if (($this->currency->convert((int)$this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')) < $this->config->get('config_voucher_min')) || ($this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency')) > $this->config->get('config_voucher_max'))) {
			$json['error']['amount'] = sprintf($this->language->get('error_amount'), $this->currency->format($this->config->get('config_voucher_min'), $this->session->data['currency']), $this->currency->format($this->config->get('config_voucher_max'), $this->session->data['currency']));
		}

		if (!isset($this->request->post['agree'])) {
			$json['error']['warning'] = $this->language->get('error_agree');
		}

		if (!$json) {
			$code = oc_token(10);

			$this->session->data['vouchers'][] = [
				'code'             => $code,
				'description'      => sprintf($this->language->get('text_for'), $this->currency->format($this->request->post['amount'], $this->session->data['currency'], 1.0), $this->request->post['to_name']),
				'to_name'          => $this->request->post['to_name'],
				'to_email'         => $this->request->post['to_email'],
				'from_name'        => $this->request->post['from_name'],
				'from_email'       => $this->request->post['from_email'],
				'voucher_theme_id' => $this->request->post['voucher_theme_id'],
				'message'          => $this->request->post['message'],
				'amount'           => $this->currency->convert($this->request->post['amount'], $this->session->data['currency'], $this->config->get('config_currency'))
			];

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);

			$json['redirect'] = $this->url->link('checkout/voucher.success', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove(): void {
		$this->load->language('checkout/voucher');

		$json = [];

		if (isset($this->request->get['key'])) {
			$key = $this->request->get['key'];
		} else {
			$key = '';
		}

		if (!isset($this->session->data['vouchers'][$key])) {
			$json['error'] = $this->language->get('error_voucher');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_remove');

			unset($this->session->data['vouchers'][$key]);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function success(): void {
		$this->load->language('checkout/voucher');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('checkout/voucher', 'language=' . $this->config->get('config_language'))
		];

		$data['continue'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}
