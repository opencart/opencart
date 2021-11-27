<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Coupon extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/coupon');

		$json = [];

		if (isset($this->request->post['coupon'])) {
			$coupon = (string)$this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		$this->load->model('extension/opencart/total/coupon');

		$coupon_info = $this->model_extension_opencart_total_coupon->getCoupon($coupon);

		if (!$coupon_info) {
			$json['error'] = $this->language->get('error_coupon');
		}

		if (!$json) {
			$this->session->data['coupon'] = $coupon;

			$json['success'] = $this->language->get('text_success');

			$this->load->model('checkout/cart');

			$json['products'] = $this->model_checkout_cart->getProducts();
			$json['vouchers'] = $this->model_checkout_cart->getVouchers();
			$json['totals'] = $this->model_checkout_cart->getTotals();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear(): void {
		$this->load->language('api/sale/coupon');

		$json = [];

		// Delete past coupon in case there is an error
		unset($this->session->data['coupon']);

		$json['success'] = $this->language->get('text_success');

		$this->load->model('checkout/cart');

		$json['products'] = $this->model_checkout_cart->getProducts();
		$json['vouchers'] = $this->model_checkout_cart->getVouchers();
		$json['totals'] = $this->model_checkout_cart->getTotals();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
