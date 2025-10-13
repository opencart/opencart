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
	 * Index
	 *
	 * @return array<string, mixed>
	 */
	public function gvcfv(): array {
		$this->load->language('api/cart');

		$output = [];

		if (isset($this->request->post['product'])) {
			$products = (array)$this->request->post['product'];
		} else {
			$products = [];
		}

		// Product
		$this->load->model('catalog/product');

		foreach ($products as $key => $product) {
			if (isset($product['product_id'])) {
				$product_id = (int)$product['product_id'];
			} else {
				$product_id = 0;
			}

			if (isset($product['quantity'])) {
				$quantity = (int)$product['quantity'];
			} else {
				$quantity = 0;
			}

			if (isset($product['option'])) {
				$option = array_filter((array)$product['option']);
			} else {
				$option = [];
			}

			if (isset($product['subscription_plan_id'])) {
				$subscription_plan_id = (int)$product['subscription_plan_id'];
			} else {
				$subscription_plan_id = 0;
			}

			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				// Merge variant code with options
				foreach ($product_info['variant'] as $option_id => $value) {
					$option[$option_id] = $value;
				}

				// Validate that have been sent are part of the product
				foreach ($option as $product_option_id => $value) {
					$product_option_info = $this->model_catalog_product->getOption($product_id, (int)$product_option_id);

					if ($product_option_info) {
						if ($product_option_info['type'] == 'select' || $product_option_info['type'] == 'radio' || $product_option_info['type'] == 'checkbox') {
							if (!is_array($value)) {
								$product_option_values = [$value];
							} else {
								$product_option_values = $value;
							}

							foreach ($product_option_values as $product_option_value_id) {
								$product_option_value_info = $this->model_catalog_product->getOptionValue($product_id, $product_option_value_id);

								if (!$product_option_value_info) {
									$output['error']['product_' . (int)$key . '_option_' . (int)$product_option_id] = $this->language->get('error_option');
								} elseif ($product_option_value_info['subtract'] && (!$this->config->get('config_stock_checkout') && (!$product_option_value_info['quantity'] || ($product_option_value_info['quantity'] < $product['quantity'])))) {
									$output['error']['product_' . (int)$key . '_option_' . (int)$product_option_id] = $this->language->get('error_option_stock');
								}
							}
						}
					} else {
						$output['error']['product_' . (int)$key . '_option_' . (int)$product_option_id] = $this->language->get('error_option');
					}
				}

				// Validate required options
				$product_options = $this->model_catalog_product->getOptions($product_id);

				foreach ($product_options as $product_option) {
					if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
						$output['error']['product_' . (int)$key . '_option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
					} elseif (($product_option['type'] == 'text') && !empty($product_option['validation']) && !oc_validate_regex($option[$product_option['product_option_id']], $product_option['validation'])) {
						$output['error']['product_' . (int)$key . '_option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_regex'), $product_option['name']);
					}
				}

				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				// Stock
				if (!$this->config->get('config_stock_checkout') && (!$product_info['quantity'] || ($product_info['quantity'] < $product_total))) {
					$output['error']['product_' . (int)$key . '_product'] = $this->language->get('error_stock');
				}

				// Minimum quantity
				if ($this->request->get['call'] == 'confirm' && ($product_info['minimum'] > $product_total)) {
					$output['error']['product_' . (int)$key . '_product'] = sprintf($this->language->get('error_minimum'), $product_info['name'], $product_info['minimum']);
				}

				// Validate subscription plan
				$subscriptions = $this->model_catalog_product->getSubscriptions($product['product_id']);

				if ($subscriptions && (!$subscription_plan_id || !in_array($subscription_plan_id, array_column($subscriptions, 'subscription_plan_id')))) {
					$output['error']['product_' . (int)$key . '_subscription'] = $this->language->get('error_subscription');
				}
			} else {
				$output['error']['product_' . (int)$key . '_product'] = $this->language->get('error_product');
			}

			if (!$output) {
				$this->cart->add($product_id, $quantity, $option, $subscription_plan_id);
			}
		}

		if (!$output) {
			$output['success'] = $this->language->get('text_success');
		} else {
			$output['error']['warning'] = $this->language->get('error_warning');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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
