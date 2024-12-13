<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Shipping Method
 *
 * @package Opencart\Catalog\Controller\Api
 */
class ShippingMethod extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array<string, mixed>
	 */
	public function index(): array {
		$this->load->language('api/shipping_method');

		$output = [];

		if ($this->cart->hasShipping()) {
			// 1. Validate customer data exists
			if (!isset($this->session->data['customer'])) {
				$output['error'] = $this->language->get('error_customer');
			}

			// 2. Validate shipping address
			if (!isset($this->session->data['shipping_address'])) {
				$output['error'] = $this->language->get('error_shipping_address');
			}

			// 3. Validate shipping method
			$keys = [
				'name',
				'code',
				'cost',
				'tax_class_id'
			];

			foreach ($keys as $key) {
				if (!isset($this->request->post['shipping_method'][$key])) {
					$output['error'] = $this->language->get('error_shipping_method');

					break;
				}
			}
		} else {
			$output['error'] = $this->language->get('error_shipping');
		}

		if (!$output) {
			$this->session->data['shipping_method'] = [
				'name'         => $this->request->post['shipping_method']['name'],
				'code'         => $this->request->post['shipping_method']['code'],
				'cost'         => (float)$this->request->post['shipping_method']['cost'],
				'tax_class_id' => (int)$this->request->post['shipping_method']['tax_class_id'],
				'text'         => $this->currency->format($this->tax->calculate((float)$this->request->post['shipping_method']['cost'], (int)$this->request->post['shipping_method']['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
			];

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}

	/**
	 * Get Shipping Methods
	 *
	 * @return array<string, mixed>
	 */
	public function getShippingMethods(): array {
		$this->load->language('api/shipping_method');

		$output = [];

		// 1. Validate customer data exists
		if (!isset($this->session->data['customer'])) {
			$output['error'] = $this->language->get('error_customer');
		}

		// 2. Validate shipping if required
		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$output['error'] = $this->language->get('error_shipping_address');
			}
		} else {
			$output['error'] = $this->language->get('error_shipping');
		}

		if (!$output) {
			$this->load->model('checkout/shipping_method');

			$shipping_methods = $this->model_checkout_shipping_method->getMethods($this->session->data['shipping_address']);

			if ($shipping_methods) {
				$output['shipping_methods'] = $shipping_methods;
			} else {
				$output['error'] = $this->language->get('error_no_shipping');
			}
		}

		return $output;
	}
}
