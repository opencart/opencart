<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class ShippingMethod extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/shipping_method');

		$json = [];

		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$json['error'] = $this->language->get('error_shipping_address');
			}
		} else {
			$json['error'] = $this->language->get('error_shipping');
		}

		if (!$json) {
			// Shipping Methods
			$this->load->model('checkout/shipping_method');

			$shipping_methods = $this->model_checkout_shipping_method->getMethods($this->session->data['shipping_address']);

			if ($shipping_methods) {
				$this->session->data['shipping_methods'] = $shipping_methods;

				$json['shipping_methods'] = $shipping_methods;
			} else {
				$json['error'] = $this->language->get('error_no_shipping');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function save(): void {
		$this->load->language('api/sale/shipping_method');

		$json = [];

		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$json['error'] = $this->language->get('error_shipping_address');
			}

			if (isset($this->request->post['shipping_method'])) {
				$shipping = explode('.', $this->request->post['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$json['error'] = $this->language->get('error_shipping_method');
				}
			} else {
				$json['error'] = $this->language->get('error_shipping_method');
			}
		} else {
			$json['error'] = $this->language->get('error_shipping');
		}

		if (!$json) {
			$this->session->data['shipping_method'] = $this->request->post['shipping_method'];

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}