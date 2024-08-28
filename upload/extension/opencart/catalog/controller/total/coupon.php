<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Total;
/**
 * Class Coupon
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Total
 */
class Coupon extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		if ($this->config->get('total_coupon_status')) {
			$this->load->language('extension/opencart/total/coupon');

			$data['save'] = $this->url->link('extension/opencart/total/coupon.save', 'language=' . $this->config->get('config_language'), true);
			$data['remove'] = $this->url->link('extension/opencart/total/coupon.remove', 'language=' . $this->config->get('config_language'), true);
			$data['list'] = $this->url->link('checkout/cart.list', 'language=' . $this->config->get('config_language'), true);

			if (isset($this->session->data['coupon'])) {
				$data['coupon'] = $this->session->data['coupon'];
			} else {
				$data['coupon'] = '';
			}

			return $this->load->view('extension/opencart/total/coupon', $data);
		}

		return '';
	}

	/**
	 * Save
	 *
	 * @return void
	 */
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
	public function remove() {
		$this->load->language('extension/opencart/total/coupon');

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

	/**
	 * @return void
	 */
	public function api(): void {
		$this->load->language('extension/opencart/total/coupon');

		$json = [];

		if ($this->request->get['route'] == 'extension/opencart/total/coupon.api') {
			$this->load->controller('api/customer');
			$this->load->controller('api/cart');
			$this->load->controller('api/payment_address');
			$this->load->controller('api/shipping_address');
			$this->load->controller('api/shipping_method.save');
			$this->load->controller('api/payment_method.save');
		}

		if (isset($this->request->post['coupon'])) {
			$coupon = (string)$this->request->post['coupon'];
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
		}

		if ($this->request->get['route'] == 'extension/opencart/total/coupon.api') {
			$json['products'] = $this->load->controller('api/cart.getProducts');
			$json['totals'] = $this->load->controller('api/cart.getTotals');
			$json['shipping_required'] = $this->cart->hasShipping();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
