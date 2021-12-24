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

		if ($coupon) {
			$this->load->model('marketing/coupon');

			$coupon_info = $this->model_marketing_coupon->getCoupon($coupon);

			if (!$coupon_info) {
				$json['error'] = $this->language->get('error_coupon');
			}
		}

		if (!$json) {
			if ($coupon) {
				$this->session->data['coupon'] = $coupon;

				$json['success'] = $this->language->get('text_success');
			} else {
				unset($this->session->data['coupon']);

				$json['success'] = $this->language->get('text_remove');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
