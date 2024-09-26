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
	public function index(): array {
		$this->load->language('extension/opencart/api/coupon');

		$output = [];

		if (!empty($this->request->post['coupon'])) {
			$coupon = (string)$this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		if (!$this->config->get('total_coupon_status')) {
			$output['error'] = $this->language->get('error_status');
		}

		$this->load->model('marketing/coupon');

		$coupon_info = $this->model_marketing_coupon->getCoupon($coupon);

		if (!$coupon_info) {
			$output['error'] = $this->language->get('error_coupon');
		}

		// Set there only to show an errormessage if the extension is being called directly
		if (!$output) {
			$this->session->data['coupon'] = $coupon;

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}

	public function validate(): bool {
		if (empty($this->request->post['coupon']) || (isset($this->session->data['coupon']) && $this->request->post['coupon'] == $this->session->data['coupon'])) {
			return true;
		}

		return false;
	}
}
