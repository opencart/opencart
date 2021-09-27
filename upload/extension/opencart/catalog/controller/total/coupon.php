<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Total;
class Coupon extends \Opencart\System\Engine\Controller {
	public function index(): string {
		if ($this->config->get('total_coupon_status')) {
			$this->load->language('extension/opencart/total/coupon');

			$data['save'] = $this->url->link('extension/opencart/total/coupon|save', 'language=' . $this->config->get('config_language'), true);

			if (isset($this->session->data['coupon'])) {
				$data['coupon'] = $this->session->data['coupon'];
			} else {
				$data['coupon'] = '';
			}

			return $this->load->view('extension/opencart/total/coupon', $data);
		}

		return '';
	}

	public function save(): void {
		$this->load->language('extension/opencart/total/coupon');

		$json = [];

		if (isset($this->request->post['coupon'])) {
			$coupon = $this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		if (!$this->config->get('total_coupon_status')) {
			$json['error'] = $this->language->get('error_status');
		}

		$this->load->model('extension/opencart/total/coupon');

		$coupon_info = $this->model_extension_opencart_total_coupon->getCoupon($coupon);

		if (!$this->config->get('total_coupon_status') || !$coupon_info) {
			$json['error'] = $this->language->get('error_coupon');
		}

		if (!$json) {
			$this->session->data['coupon'] = $this->request->post['coupon'];

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove(): void {
		$this->load->language('extension/opencart/total/coupon');

		$json = [];

		unset($this->session->data['coupon']);

		$this->session->data['success'] = $this->language->get('text_remove');

		$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
