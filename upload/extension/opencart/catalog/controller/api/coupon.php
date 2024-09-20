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
	public function index(): bool {
		$this->load->language('extension/opencart/api/coupon');

		$json = [];

		if ($this->request->get['route'] == 'extension/opencart/api/coupon') {
			$controllers = [
				'api/customer',
				'api/cart',
				'api/payment_address',
				'api/shipping_address',
				'api/shipping_method.save',
				'api/payment_method.save',
			];

			foreach ($controllers as $controller) {
				$this->load->controller($controller);
			}

			$this->load->model('setting/extension');

			$extensions = $this->model_setting_extension->getExtensionsByType('total');

			foreach ($extensions as $extension) {
				if ($extension['code'] != 'coupon') {
					$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
				}
			}
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

		$status = true;

		// Set there only to show an errormessage if the extension is being called directly
		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$this->session->data['coupon'] = $coupon;

			if ($this->request->get['route'] == 'extension/opencart/api/coupon') {
				$json['products'] = $this->load->controller('api/cart.getProducts');
				$json['totals'] = $this->load->controller('api/cart.getTotals');
				$json['shipping_required'] = $this->cart->hasShipping();
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));



		if ($this->request->get['route'] == 'extension/opencart/api/coupon' && !$coupon) {
			$status = false;
		}

		return $status;
	}

	public function _test() {
		$controllers = [
			'api/customer',
			'api/cart',
			'api/payment_address',
			'api/shipping_address',
			'api/shipping_method.save',
			'api/payment_method.save',
		];

		foreach ($controllers as $controller) {
			$this->load->controller($controller);
		}

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($extensions as $extension) {
			$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
		}

		$json['products'] = $this->load->controller('api/cart.getProducts');
		$json['totals'] = $this->load->controller('api/cart.getTotals');
		$json['shipping_required'] = $this->cart->hasShipping();
	}
}
