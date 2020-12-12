<?php
namespace Opencart\Application\Controller\Extension\Opencart\Total;
class Voucher extends \Opencart\System\Engine\Controller {
	public function index() {
		if ($this->config->get('total_voucher_status')) {
			$this->load->language('extension/opencart/total/voucher');

			if (isset($this->session->data['voucher'])) {
				$data['voucher'] = $this->session->data['voucher'];
			} else {
				$data['voucher'] = '';
			}

			return $this->load->view('extension/opencart/total/voucher', $data);
		}
	}

	public function voucher() {
		$this->load->language('extension/opencart/total/voucher');

		$json = [];

		if (isset($this->request->post['voucher'])) {
			$voucher = $this->request->post['voucher'];
		} else {
			$voucher = '';
		}

		$this->load->model('account/voucher');

		$voucher_info = $this->model_account_voucher->getVoucher($voucher);

		if (empty($this->request->post['voucher'])) {
			$json['error'] = $this->language->get('error_empty');
		} elseif ($voucher_info) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		} else {
			$json['error'] = $this->language->get('error_voucher');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}