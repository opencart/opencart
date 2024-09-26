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
	 * Set shipping method
	 *
	 * @return array
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
			if (empty($this->request->post['shipping_method_name'])) {
				$output['error'] = $this->language->get('error_name');
			}

			if (empty($this->request->post['shipping_method_code'])) {
				$output['error'] = $this->language->get('error_code');
			}

			if (empty($this->request->post['shipping_method_cost'])) {
				$output['error'] = $this->language->get('error_cost');
			}

			if (!isset($this->request->post['shipping_method_tax_class_id'])) {
				$output['error'] = $this->language->get('error_tax_class');
			}
		} else {
			$output['error'] = $this->language->get('error_shipping');
		}

		if (!$output) {
			$this->session->data['shipping_method'] = [
				'name'         => $this->request->post['shipping_method_name'],
				'code'         => $this->request->post['shipping_method_code'],
				'cost'         => (float)$this->request->post['shipping_method_cost'],
				'tax_class_id' => (int)$this->request->post['shipping_method_tax_class_id'],
				'text'         => $this->currency->format($this->tax->calculate((float)$this->request->post['shipping_method_cost'], (int)$this->request->post['shipping_method_tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
			];

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}

	/**
	 * Get shipping methods
	 *
	 * @return array
	 */
	public function getShippingMethods(): array {
		$this->load->language('api/shipping_method');

		$output = [];

		// Add keys for missing post vars
		$keys = [
			'shipping_method_name',
			'shipping_method_code',
			'shipping_method_cost',
			'shipping_method_tax_class_id'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

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
