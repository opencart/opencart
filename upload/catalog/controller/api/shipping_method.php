<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Shipping Method
 *
 * @package Opencart\Catalog\Controller\Api
 */
class ShippingMethod extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('api/shipping_method');

		$json = [];

		if ($this->request->get['route'] == 'api/shipping_method') {
			$this->load->controller('api/customer');
			$this->load->controller('api/cart');
			$this->load->controller('api/shipping_address');

			$this->load->model('setting/extension');

			$extensions = $this->model_setting_extension->getExtensionsByType('total');

			foreach ($extensions as $extension) {
				$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
			}
		}

		// 1. Validate customer data exists
		if (!isset($this->session->data['customer'])) {
			$json['error']['customer'] = $this->language->get('error_customer');
		}

		// 2. Validate shipping if required
		if ($this->cart->hasShipping()) {
			if (!isset($this->session->data['shipping_address'])) {
				$json['error']['shipping_address'] = $this->language->get('error_shipping_address');
			}
		} else {
			$json['error']['warning'] = $this->language->get('error_shipping');
		}

		if (!$json) {
			$this->load->model('checkout/shipping_method');

			$shipping_methods = $this->model_checkout_shipping_method->getMethods($this->session->data['shipping_address']);

			if ($shipping_methods) {
				$json['shipping_methods'] = $shipping_methods;
			} else {
				$json['error']['warning'] = $this->language->get('error_no_shipping');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('api/shipping_method');

		$json = [];

		if ($this->request->get['route'] == 'api/shipping_method.save') {
			$this->load->controller('api/customer');
			$this->load->controller('api/cart');
			$this->load->controller('api/shipping_address');

			$this->load->model('setting/extension');

			$extensions = $this->model_setting_extension->getExtensionsByType('total');

			foreach ($extensions as $extension) {
				$this->load->controller('extension/' . $extension['extension'] . '/api/' . $extension['code']);
			}
		}

		if ($this->cart->hasShipping()) {
			// 1. Validate customer data exists
			if (!isset($this->session->data['customer'])) {
				$json['error']['customer'] = $this->language->get('error_customer');
			}

			// 2. Validate shipping address
			if (!isset($this->session->data['shipping_address'])) {
				$json['error']['shipping_address'] = $this->language->get('error_shipping_address');
			}

			// 3. Validate shipping method
			if (isset($this->request->post['shipping_method'])) {
				if (empty($this->request->post['shipping_method']['name'])) {
					$json['error']['warning'] = $this->language->get('error_name');
				}

				if (empty($this->request->post['shipping_method']['code'])) {
					$json['error']['warning'] = $this->language->get('error_code');
				}

				if (empty($this->request->post['shipping_method']['cost'])) {
					$json['error']['warning'] = $this->language->get('error_cost');
				}

				if (!isset($this->request->post['shipping_method']['tax_class_id'])) {
					$json['error']['warning'] = $this->language->get('error_tax_class');
				}
			} else {
				$json['error']['warning'] = $this->language->get('error_shipping_method');
			}
		} else {
			$json['error']['warning'] = $this->language->get('error_shipping');
		}

		if (!$json) {
			$this->session->data['shipping_method'] = [
				'name'         => $this->request->post['shipping_method']['name'],
				'code'         => $this->request->post['shipping_method']['code'],
				'cost'         => (float)$this->request->post['shipping_method']['cost'],
				'tax_class_id' => (int)$this->request->post['shipping_method']['tax_class_id'],
				'text'         => $this->currency->format($this->tax->calculate((float)$this->request->post['shipping_method']['cost'], (int)$this->request->post['shipping_method']['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
			];

			$json['success'] = $this->language->get('text_success');
		}

		if ($this->request->get['route'] == 'api/shipping_method.save') {
			$json['products'] = $this->load->controller('api/cart.getProducts');
			$json['totals'] = $this->load->controller('api/cart.getTotals');
			$json['shipping_required'] = $this->cart->hasShipping();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
