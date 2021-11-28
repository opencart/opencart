<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Total;
class Voucher extends \Opencart\System\Engine\Controller {
	public function index(): string {
		if ($this->config->get('total_voucher_status')) {
			$this->load->language('extension/opencart/total/voucher');

			$data['save'] = $this->url->link('extension/opencart/total/voucher|save', 'language=' . $this->config->get('config_language'), true);

			if (isset($this->session->data['voucher'])) {
				$data['voucher'] = $this->session->data['voucher'];
			} else {
				$data['voucher'] = '';
			}

			return $this->load->view('extension/opencart/total/voucher', $data);
		}

		return '';
	}

	public function save(): void {
		$this->load->language('extension/opencart/total/voucher');

		$json = [];

		if (isset($this->request->post['voucher'])) {
			$voucher = (string)$this->request->post['voucher'];
		} else {
			$voucher = '';
		}

		$this->load->model('checkout/voucher');

		$voucher_info = $this->model_checkout_voucher->getVoucher($voucher);

		if (!$this->config->get('total_voucher_status') || !$voucher_info) {
			$json['error'] = $this->language->get('error_voucher');
		}

		if (!$json) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove(): void {
		$this->load->language('extension/opencart/total/voucher');

		$json = [];

		unset($this->session->data['voucher']);

		$this->session->data['success'] = $this->language->get('text_remove');

		$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}