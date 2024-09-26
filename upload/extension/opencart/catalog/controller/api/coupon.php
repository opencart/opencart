<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Api;
/**
 * Class Coupon
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Api
 */
class Coupon extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/api/coupon');

		$json = [];

		if ($this->request->get['route'] == 'extension/opencart/api/coupon') {
			$this->load->controller('api/order');
		}

		if (!empty($this->request->post['coupon'])) {
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

		// Set there only to show an errormessage if the extension is being called directly
		if (!$json) {
			$this->session->data['coupon'] = $coupon;

			$json['success'] = $this->language->get('text_success');
		}

		if ($this->request->get['route'] == 'extension/opencart/api/coupon') {
			$json['products'] = $this->load->controller('api/cart.getProducts');
			$json['totals'] = $this->load->controller('api/cart.getTotals');
			$json['shipping_required'] = $this->cart->hasShipping();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
