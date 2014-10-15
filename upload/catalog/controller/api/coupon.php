<?php
class ControllerApiCoupon extends Controller {
	public function index() {
		$this->load->language('api/coupon');

		// Delete past coupon in case there is an error
		unset($this->session->data['coupon']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('checkout/coupon');

			if (isset($this->request->post['coupon'])) {
				$coupon = $this->request->post['coupon'];
			} else {
				$coupon = '';
			}

			$coupon_info = $this->model_checkout_coupon->getCoupon($coupon);

			if ($coupon_info) {
				$this->session->data['coupon'] = $this->request->post['coupon'];

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_coupon');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}