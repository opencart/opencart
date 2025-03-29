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
	 * @return array<string, mixed>
	 */
	public function index(): array {
		$this->load->language('extension/opencart/api/coupon');

		$output = [];

		if (!empty($this->request->post['coupon'])) {
			$coupon = (string)$this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		if (empty($this->request->post['coupon']) && $this->request->get['call'] == 'confirm') {
			return [];
		}

		// 1. Validate customer data exists
		if (!isset($this->session->data['customer'])) {
			$output['error'] = $this->language->get('error_customer');
		}

		// 2. Validate cart has products.
		if (!$this->cart->hasProducts()) {
			$output['error'] = $this->language->get('error_product');
		}

		if (!$this->config->get('total_coupon_status')) {
			$output['error'] = $this->language->get('error_status');
		}

		if (!$output) {
			// Setting
			$this->load->model('marketing/coupon');

			$coupon_info = $this->model_marketing_coupon->getCoupon($coupon);

			if (!$coupon_info) {
				$output['error'] = $this->language->get('error_coupon');
			}
		}

		// Set there only to show an errormessage if the extension is being called directly
		if (!$output) {
			$this->session->data['coupon'] = $coupon;

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}
}
