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
			$products = (array)$this->request->post['product'];
		} else {
			$products = [];
		}

		if (isset($this->request->post['order_id'])) {
			$order_id = (int)$this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$order_quantity = [];
		$order_option_quantity = [];

		// If existing order get products
		if ($order_id) {
			$this->load->model('checkout/order');
			$order_products = $this->model_checkout_order->getProducts($order_id);
			if ($order_products) {
				foreach ($order_products as $product) {
					$product_option = $this->model_checkout_order->getOptions($order_id, $product['order_product_id']);

					$pid = $product['product_id'];
					$qty = (int)$product['quantity'];

					// get order product quantity
					if (!isset($order_quantity[$pid])) {
						$order_quantity[$pid] = 0;
					}
					$order_quantity[$pid] += $qty;

					// get order option quantity
					if (!isset($order_option_quantity[$pid])) {
						$order_option_quantity[$pid] = [];
					}
					foreach ($product_option as $opt) {
						$optId = $opt['product_option_value_id'];
						$order_option_quantity[$pid][$optId] = (int)$product['quantity'];
					}
				}
			}
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

				// Validate options that have been sent are part of the product
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

								$orderOptQty = $order_option_quantity[$product_id][$product_option_value_id] ?? 0;
								$option_stock = $product_option_value_info['quantity'] + $orderOptQty;

								if (!$product_option_value_info) {
									$output['error']['product_' . (int)$key . '_option_' . (int)$product_option_id] = $this->language->get('error_option');
								}
								elseif ($product_option_value_info['subtract'] && (!$this->config->get('config_stock_checkout') && (!$option_stock || ($option_stock < $product['quantity'])))) {
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

				$product_stock = $product_info['quantity'];
				if (isset($order_quantity[$product_id])) {
					$product_stock += $order_quantity[$product_id];
				}

				// Stock
				if (!$this->config->get('config_stock_checkout') && (!$product_stock || ($product_stock < $product_total))) {
					$output['error']['product_' . (int)$key . '_product'] = $this->language->get('error_stock');
				}

				// Minimum quantity
				if (isset($this->request->get['call']) && $this->request->get['call'] == 'confirm' && ($product_info['minimum'] > $product_total)) {
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

		// Add any single product
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

		if (isset($this->request->post['order_id'])) {
			$order_id = (int)$this->request->post['order_id'];
		} else {
			$order_id = 0;
		}

		$order_quantity = [];
		$order_option_quantity = [];

		// If existing order get its products
		if ($order_id) {
			$this->load->model('checkout/order');
			$order_products = $this->model_checkout_order->getProducts($order_id);
			if ($order_products) {
				foreach ($order_products as $product) {
					$product_option = $this->model_checkout_order->getOptions($order_id, $product['order_product_id']);

					$pid = $product['product_id'];
					$qty = (int)$product['quantity'];

					// get order product quantity
					if (!isset($order_quantity[$pid])) {
						$order_quantity[$pid] = 0;
					}
					$order_quantity[$pid] += $qty;

					// get order option quantity
					if (!isset($order_option_quantity[$pid])) {
						$order_option_quantity[$pid] = [];
					}
					foreach ($product_option as $opt) {
						$optId = $opt['product_option_value_id'];
						$order_option_quantity[$pid][$optId] = (int)$product['quantity'];
					}
				}
			}
		}

		// Product
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			$products = $this->cart->getProducts();

			$cart_option_quantity = [];

			// Get all cart products' option quantities
			foreach ($products as $product) {
				$pid = $product['product_id'];
				$qty = $product['quantity'];

				if (!isset($cart_option_quantity[$pid])) {
					$cart_option_quantity[$pid] = [];
				}
				if (!empty($product['option'])) {
					foreach ($product['option'] as $opt) {
						$optId = $opt['product_option_value_id'];

						if (!isset($cart_option_quantity[$pid][$optId])) {
							$cart_option_quantity[$pid][$optId] = 0;
						}
						$cart_option_quantity[$pid][$optId] += $qty;
					}
				}
			}

			// If variant get master product
			if ($product_info['master_id']) {
				$product_id = $product_info['master_id'];
			}

			// Merge variant code with options
			foreach ($product_info['variant'] as $option_id => $value) {
				$option[$option_id] = $value;
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
								// Compare product's option stock to cart + request quantity
								$orderOptQty = $order_option_quantity[$product_id][$product_option_value_id] ?? 0;
								$cartOptQty = $cart_option_quantity[$product_id][$product_option_value_id] ?? 0;
								$product_stock = $product_option_value_info['quantity'] + $orderOptQty;

								if ($product_stock < $cartOptQty + $quantity) {
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
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product_info['product_id']) {
					$product_total += $product_2['quantity'];
					if (isset($order_quantity[$product_2['product_id']])) {
						$product_total -= $order_quantity[$product_2['product_id']];
					}
				}
			}

			// Compare product's total stock to cart + request quantity
			if (!$this->config->get('config_stock_checkout') && (!$product_info['quantity'] || ($product_info['quantity'] < $product_total + $quantity))) {
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
}
