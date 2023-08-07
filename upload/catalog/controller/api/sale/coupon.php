<?php
namespace Opencart\Catalog\Controller\Api\Sale;
/**
 * Class Coupon
 *
 * @package Opencart\Catalog\Controller\Api\Sale
 */
class Coupon extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('api/sale/coupon');

		$json = [];

		if (isset($this->request->post['coupon'])) {
			$coupon = (string)$this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		if ($coupon) {
			$this->load->model('marketing/coupon');

			$coupon_info = $this->model_marketing_coupon->getCoupon($coupon);

			if (!$coupon_info) {
				$json['error'] = $this->language->get('error_coupon');
			}
		}

		if (!$json) {
			if ($coupon) {
				$json['success'] = $this->language->get('text_success');

				$this->session->data['coupon'] = $coupon;
			} else {
				$json['success'] = $this->language->get('text_remove');

				unset($this->session->data['coupon']);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
