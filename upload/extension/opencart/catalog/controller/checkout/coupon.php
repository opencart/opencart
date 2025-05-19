<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Checkout;
/**
 * Class Coupon
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Checkout
 */
class Coupon extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		if ($this->config->get('total_coupon_status')) {
			$this->load->language('extension/opencart/checkout/coupon');

			$data['save'] = $this->url->link('extension/opencart/checkout/coupon.save', 'language=' . $this->config->get('config_language'), true);
			$data['remove'] = $this->url->link('extension/opencart/checkout/coupon.remove', 'language=' . $this->config->get('config_language'), true);
			$data['list'] = $this->url->link('checkout/cart.list', 'language=' . $this->config->get('config_language'), true);

			if (isset($this->session->data['coupon'])) {
				$data['coupon'] = $this->session->data['coupon'];
			} else {
				$data['coupon'] = '';
			}

			return $this->load->view('extension/opencart/checkout/coupon', $data);
		}

		return '';
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/checkout/coupon');

		$json = [];

		// Coupon
		if (isset($this->request->post['coupon'])) {
			$coupon = $this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		if (!$this->config->get('total_coupon_status')) {
			$json['error'] = $this->language->get('error_status');
		}

		$this->load->model('marketing/coupon');

		$coupon_info = $this->model_marketing_coupon->getCoupon($coupon);

		if (!$coupon_info) {
			$json['error'] = $this->language->get('error_coupon');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$this->session->data['coupon'] = $coupon;

			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Remove
	 *
	 * @return void
	 */
	public function remove(): void {
		$this->load->language('extension/opencart/checkout/coupon');

		$json = [];

		if (!isset($this->session->data['coupon'])) {
			$json['error'] = $this->language->get('error_remove');
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_remove');

			unset($this->session->data['coupon']);

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
