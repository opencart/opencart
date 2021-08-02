<?php
namespace Opencart\Catalog\Controller\Api;
class Coupon extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/coupon');

		// Delete past coupon in case there is an error
		unset($this->session->data['coupon']);

		$json = [];

		if (isset($this->request->post['coupon'])) {
			$coupon = (string)$this->request->post['coupon'];
		} else {
			$coupon = '';
		}

		$this->load->model('extension/opencart/total/coupon');

		$coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);

		if (!$coupon_info) {
			$json['error'] = $this->language->get('error_coupon');
		}

		if (!$json) {
			$this->session->data['coupon'] = $coupon;

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
