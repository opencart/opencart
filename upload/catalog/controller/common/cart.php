<?php
namespace Opencart\Catalog\Controller\Common;
/**
 * Class Cart
 *
 * Can be called from $this->load->controller('common/cart');
 *
 * @package Opencart\Catalog\Controller\Common
 */
class Cart extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/cart');

		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Cart
		$this->load->model('checkout/cart');

		// Image
		$this->load->model('tool/image');

		// Display prices
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

			$price_status = true;
		} else {
			$price_status = false;
		}

		$data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts(), $this->session->data['currency'], $total);

		// Products
		$data['products'] = [];

		$products = $this->model_checkout_cart->getProducts();

		foreach ($products as $product) {
			if ($product['option']) {
				foreach ($product['option'] as $key => $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$product['option'][$key]['value'] = (oc_strlen($value) > 20 ? oc_substr($value, 0, 20) . '..' : $value);
				}
			}

			$subscription = '';

			if ($product['subscription']) {
				if ($product['subscription']['duration']) {
					$subscription .= sprintf($this->language->get('text_subscription_duration'), $this->session->data['currency'], $price_status ?? $product['subscription']['price'], $product['subscription']['cycle'], $product['subscription']['frequency'], $product['subscription']['duration']);
				} else {
					$subscription .= sprintf($this->language->get('text_subscription_cancel'), $this->session->data['currency'], $price_status ?? $product['subscription']['price'], $product['subscription']['cycle'], $product['subscription']['frequency']);
				}
			}

			$data['products'][] = [
				'thumb'        => $this->model_tool_image->resize($product['image'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
				'subscription' => $subscription,
				'price'        => $price_status ? $product['price'] : '',
				'total'        => $price_status ? $product['total'] : '',
				'href'         => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product['product_id'])
			] + $product;
		}

		// Totals
		$data['totals'] = $totals;

		$data['list'] = $this->url->link('common/cart.info', 'language=' . $this->config->get('config_language'));
		$data['remove'] = $this->url->link('common/cart.remove', 'language=' . $this->config->get('config_language'));

		$data['cart'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));
		$data['checkout'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));

		$data['currency'] = $this->session->data['currency'];

		return $this->load->view('common/cart', $data);
	}

	/**
	 * Info
	 *
	 * @return void
	 */
	public function info(): void {
		$this->response->setOutput($this->index());
	}

	/**
	 * Remove Product
	 *
	 * @return void
	 */
	public function remove(): void {
		$this->load->language('checkout/cart');

		$json = [];

		if (isset($this->request->post['key'])) {
			$key = (int)$this->request->post['key'];
		} else {
			$key = 0;
		}

		if (!$this->cart->has($key)) {
			$json['error'] = $this->language->get('error_product');
		}

		if (!$json) {
			$this->cart->remove($key);

			$json['success'] = $this->language->get('text_remove');

			unset($this->session->data['order_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['reward']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
