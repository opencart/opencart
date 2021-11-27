<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Cart extends \Opencart\System\Engine\Controller {
	public function add(): void {
		$this->load->language('api/sale/cart');

		$json = [];

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = (int)$this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		if (isset($this->request->post['option'])) {
			$option = array_filter($this->request->post['option']);
		} else {
			$option = [];
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			$product_options = $this->model_catalog_product->getOptions($product_id);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}
		} else {
			$json['error']['product'] = $this->language->get('error_product');
		}

		if (!$json) {
			$this->cart->add($product_id, $quantity, $option);

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit(): void {
		$this->load->language('api/sale/cart');

		$json = [];

		if (!$json) {
			$this->cart->update($this->request->post['key'], (int)$this->request->post['quantity']);

			$json['success'] = $this->language->get('text_success');
		}

		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['reward']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove(): void {
		$this->load->language('api/sale/cart');

		$json = [];

		if (isset($this->request->post['key'])) {
			$key = (string)$this->request->post['key'];
		} else {
			$key = '';
		}

		if (!isset($this->session->data['vouchers'][$key])) {
			$json['error'] = $this->language->get('error_product');
		}

		// Remove
		if (!$json) {
			unset($this->session->data['vouchers'][$key]);

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function items(): void {
		$this->load->language('api/sale/cart');

		$json = [];

		// Stock
		if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
			$json['error']['stock'] = $this->language->get('error_stock');
		}

		$this->load->model('checkout/cart');

		$json['products'] = $this->model_checkout_cart->getProducts();
		$json['vouchers'] = $this->model_checkout_cart->getVouchers();
		$json['totals'] = $this->model_checkout_cart->getTotals();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
