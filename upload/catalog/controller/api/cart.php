<?php
namespace Opencart\Catalog\Controller\Api;
/**
 * Class Cart
 *
 * Can be loaded using $this->load->controller('api/cart');
 *
 * @package Opencart\Catalog\Controller\Api
 */
class Cart extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array<string, mixed>
	 */
	public function index(): array {
		$this->load->language('api/cart');

		$output = [];

		if (isset($this->request->post['product'])) {
			$posted_products = (array)$this->request->post['product'];
		} else {
			$posted_products = [];
		}

		// Product
		$this->load->model('catalog/product');

		foreach ($posted_products as $key => $posted_product) {
			if (isset($posted_product['product_id'])) {
				$product_id = (int)$posted_product['product_id'];
			} else {
				$product_id = 0;
			}

			if (isset($posted_product['quantity'])) {
				$quantity = (int)$posted_product['quantity'];
			} else {
				$quantity = 0;
			}

			if (isset($posted_product['option'])) {
				$option = array_filter((array)$posted_product['option']);
			} else {
				$option = [];
			}

			if (isset($posted_product['subscription_plan_id'])) {
				$subscription_plan_id = (int)$posted_product['subscription_plan_id'];
			} else {
				$subscription_plan_id = 0;
			}

			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				// Merge variant code with options
				foreach ($product_info['variant'] as $option_id => $value) {
					$option[$option_id] = $value;
				}

				// Validate the options that have been sent are part of the product, and with sufficient stock levels
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
								} elseif ($product_option_value_info['subtract'] && (!$this->config->get('config_stock_checkout') && ($this->adjustedProductOptionValueStock($product_id, $product_option_value_id) < $posted_product['quantity']))) {
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

				foreach ($posted_products as $product_2) {
					if ($product_2['product_id'] == $posted_product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				// Stock
				if (!$this->config->get('config_stock_checkout') && ($this->adjustedProductStock($product_info) < $product_total)) {
					$output['error']['product_' . (int)$key . '_product'] = $this->language->get('error_stock');
				}

				// Minimum quantity
				if (isset($this->request->get['call']) && $this->request->get['call'] == 'confirm' && ($product_info['minimum'] > $product_total)) {
					$output['error']['product_' . (int)$key . '_product'] = sprintf($this->language->get('error_minimum'), $product_info['name'], $product_info['minimum']);
				}

				// Validate subscription plan
				$subscriptions = $this->model_catalog_product->getSubscriptions($posted_product['product_id']);

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

		return $output;
	}

	/**
	 * Add Product
	 *
	 * Add any single product
	 *
	 * @return array<string, mixed>
	 */
	public function addProduct(): array {
		$this->load->language('api/cart');

		$output = [];

		// Add any single products
		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = (int)$this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		if (isset($this->request->post['option'])) {
			$option = array_filter((array)$this->request->post['option']);
		} else {
			$option = [];
		}

		if (isset($this->request->post['subscription_plan_id'])) {
			$subscription_plan_id = (int)$this->request->post['subscription_plan_id'];
		} else {
			$subscription_plan_id = 0;
		}

		// Product
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			// If variant get master product
			if ($product_info['master_id']) {
				$product_id = $product_info['master_id'];
			}

			// Merge variant code with options
			foreach ($product_info['variant'] as $product_option_id => $value) {
				$option[$product_option_id] = $value;
			}

			// Validate the options that have been sent are part of the product
			foreach ($option as $product_option_id => $value) {
				$product_option_info = $this->model_catalog_product->getOption($product_id, $product_option_id);

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
								$output['error']['option_' . $product_option_id] = $this->language->get('error_option');
							} elseif ($product_option_value_info['subtract']) {
								$product_option_value_quantity = $this->adjustedProductOptionValueStock($product_id, $product_option_value_id);
								if (!$product_option_value_quantity || $product_option_value_quantity < $quantity + $this->cartOptionValueQuantity($product_option_value_id)) {
									$output['error']['option_' . $product_option_id] = $this->language->get('error_option_stock');
								}
							}
						}
					}
				} else {
					$output['error']['option_' . $product_option_id] = $this->language->get('error_option');
				}
			}

			// Validate Options
			$product_options = $this->model_catalog_product->getOptions($product_id);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$output['error']['option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				} elseif (($product_option['type'] == 'text') && !empty($product_option['validation']) && !oc_validate_regex($option[$product_option['product_option_id']], $product_option['validation'])) {
					$output['error']['option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_regex'), $product_option['name']);
				}
			}

			// Stock
			$product_total = $quantity;

			$products = $this->cart->getProducts();

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product_info['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if (!$this->config->get('config_stock_checkout') && (!$product_info['quantity'] || ($this->adjustedProductStock($product_info) < $product_total))) {
				$output['error']['warning'] = $this->language->get('error_stock');
			}

			// Validate subscription plan
			$subscriptions = $this->model_catalog_product->getSubscriptions($product_id);

			if ($subscriptions && (!$subscription_plan_id || !in_array($subscription_plan_id, array_column($subscriptions, 'subscription_plan_id')))) {
				$output['error']['subscription'] = $this->language->get('error_subscription');
			}
		} else {
			$output['error']['warning'] = $this->language->get('error_product');
		}

		if (!$output) {
			$this->cart->add($product_info['product_id'], $quantity, $option, $subscription_plan_id);

			$output['success'] = $this->language->get('text_success');
		}

		return $output;
	}

	/**
	 * Get products
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getProducts(): array {
		$this->load->language('api/cart');

		// We fetch any products that have an error
		$product_data = [];

		// Cart
		$this->load->model('checkout/cart');

		$products = $this->model_checkout_cart->getProducts();

		foreach ($products as $product) {
			$subscription = '';

			if ($product['subscription']) {
				if ($product['subscription']['trial_status']) {
					$subscription .= sprintf($this->language->get('text_subscription_trial'), $product['subscription']['trial_price_text'], $product['subscription']['trial_cycle'], $product['subscription']['trial_frequency'], $product['subscription']['trial_duration']);
				}

				if ($product['subscription']['duration']) {
					$subscription .= sprintf($this->language->get('text_subscription_duration'), $product['subscription']['price_text'], $product['subscription']['cycle'], $product['subscription']['frequency'], $product['subscription']['duration']);
				} else {
					$subscription .= sprintf($this->language->get('text_subscription_cancel'), $product['subscription']['price_text'], $product['subscription']['cycle'], $product['subscription']['frequency']);
				}
			}

			$product_data[] = [
				'subscription_plan_id' => $product['subscription'] ? $product['subscription']['subscription_plan_id'] : 0,
				'subscription'         => $subscription
			] + $product;
		}

		return $product_data;
	}

	/**
	 * Get Totals
	 *
	 * @return array<string, mixed>
	 */
	public function getTotals(): array {
		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Cart
		$this->load->model('checkout/cart');

		($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

		$total_data = [];

		foreach ($totals as $total) {
			$total_data[] = ['text' => $this->currency->format($total['value'], $this->session->data['currency'])] + $total;
		}

		return $total_data;
	}

	/**
	 * Get adjusted product stock if product was originally part of the order
	 *
	 * @param array<string, mixed> $product_info
	 *
	 * @return int
	 */
	protected function adjustedProductStock(array $product_info): int {
		$quantity = $product_info['quantity'];

		$order_id = isset($this->request->post['order_id']) ? (int)$this->request->post['order_id'] : 0;

		if ($order_id == 0) {
			return $quantity;
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			return $quantity;
		}

		if ($order_info['order_status_id'] == 0) {
			return $quantity;
		}

		$order_products = $this->model_checkout_order->getProducts($order_id);
		foreach ($order_products as $order_product) {
			if ($order_product['product_id'] == $product_info['product_id']) {
				$quantity += $order_product['quantity'];
			}
		}

		return $quantity;
	}

	/**
	 * Get adjusted product option value stocks if they were originally part of the order
	 *
	 * @param int $product_id
	 * @param int $product_option_value_id
	 *
	 * @return int
	 */
	protected function adjustedProductOptionValueStock(int $product_id, int $product_option_value_id): int {
		$quantity = 0;

		$this->load->model('catalog/product');
		$product_option_value_info = $this->model_catalog_product->getOptionValue($product_id, $product_option_value_id);

		if (empty($product_option_value_info)) {
			return $quantity;
		}

		$quantity = $product_option_value_info['quantity'];

		$order_id = isset($this->request->post['order_id']) ? (int)$this->request->post['order_id'] : 0;

		if ($order_id == 0) {
			return $quantity;
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		if (!$order_info) {
			return $quantity;
		}

		if ($order_info['order_status_id'] == 0) {
			return $quantity;
		}

		$order_products = $this->model_checkout_order->getProducts($order_id);

		foreach ($order_products as $order_product) {
			$order_product_id = $order_product['order_product_id'];
			$order_product_quantity = $order_product['quantity'];
			$order_product_options = $this->model_checkout_order->getOptions($order_id, $order_product_id);
			foreach ($order_product_options as $order_product_option) {
				if ($order_product_option['product_option_value_id'] == $product_option_value_id) {
					$quantity += $order_product_quantity;
				}
			}
		}

		return $quantity;
	}

	/**
	 * Get the total cart quantity for a product option value
	 *
	 * @param int $product_option_value_id
	 *
	 * @return int
	 */
	protected function cartOptionValueQuantity(int $product_option_value_id): int {
		$quantity = 0;
		$cart_products = $this->cart->getProducts();

		foreach ($cart_products as $cart_product) {
			foreach ($cart_product['option'] as $option) {
				if ($option['product_option_value_id'] == $product_option_value_id) {
					$quantity += $cart_product['quantity'];
				}
			}
		}

		return $quantity;
	}
}
