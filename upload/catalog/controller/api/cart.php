<?php
namespace Opencart\catalog\controller\api;
/**
 * Class Cart
 *
 * @package Opencart\Catalog\Controller\Api\Sale
 */
class Cart extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('api/cart');

		$json = [];

		$this->load->controller('api/cart.products');

		// Stock
		if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
			$json['error']['stock'] = $this->language->get('error_stock');
		}

		$json['products'] = $this->getProducts();
		$json['totals'] = $this->getTotals();

		$json['shipping_required'] = $this->cart->hasShipping();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function getProducts(): array {
		$product_data = [];

		$this->load->model('checkout/cart');

		$products = $this->model_checkout_cart->getProducts();

		foreach ($products as $product) {
			$description = '';

			if ($product['subscription']) {
				if ($product['subscription']['trial_status']) {
					$trial_price = $this->currency->format($this->tax->calculate($product['subscription']['trial_price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_cycle = $product['subscription']['trial_cycle'];
					$trial_frequency = $this->language->get('text_' . $product['subscription']['trial_frequency']);
					$trial_duration = $product['subscription']['trial_duration'];

					$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
				}

				$price = $this->currency->format($this->tax->calculate($product['subscription']['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$cycle = $product['subscription']['cycle'];
				$frequency = $this->language->get('text_' . $product['subscription']['frequency']);
				$duration = $product['subscription']['duration'];

				if ($duration) {
					$description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
				} else {
					$description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
				}
			}

			$product_data[] = [
				'cart_id'              => $product['cart_id'],
				'product_id'           => $product['product_id'],
				'name'                 => $product['name'],
				'model'                => $product['model'],
				'option'               => $product['option'],
				'subscription_plan_id' => $product['subscription'] ? $product['subscription']['subscription_plan_id'] : 0,
				'subscription'         => $description,
				'quantity'             => $product['quantity'],
				'stock'                => $product['stock'],
				'minimum'              => $product['minimum'],
				'reward'               => $product['reward'],
				'price'                => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'total'                => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency'])
			];
		}

		return $product_data;
	}

	private function getTotals(): array {
		$total_data = [];

		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;

		$this->load->model('checkout/cart');

		($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

		$total_data = [];

		foreach ($totals as $total) {
			$total_data[] = [
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
			];
		}

		return $total_data;
	}

	/**
	 * @return void
	 */
	public function products(): void {
		$this->load->language('api/cart');

		$json = [];

		if (isset($this->request->post['product'])) {
			$products = (array)$this->request->post['product'];
		} else {
			$products = [];
		}

		$this->load->model('catalog/product');

		foreach ($products as $key => $product) {
			$product_info = $this->model_catalog_product->getProduct($product['product_id']);

			if ($product_info) {
				if (isset($product['quantity'])) {
					$quantity = (int)$product['quantity'];
				} else {
					$quantity = 1;
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

				// Merge variant code with options
				foreach ($product_info['variant'] as $option_id => $value) {
					$option[$option_id] = $value;
				}

				// Validate options
				$product_options = $this->model_catalog_product->getOptions($product['product_id']);

				foreach ($product_options as $product_option) {
					if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
						$json['error']['option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
					}
				}

				// Validate Subscription plan
				$subscriptions = $this->model_catalog_product->getSubscriptions($product['product_id']);

				if ($subscriptions && !in_array($product['subscription_plan_id'], array_column($subscriptions, 'subscription_plan_id'))) {
					$json['error']['subscription_' . $key] = $this->language->get('error_subscription');
				}
			} else {
				$json['error']['product'] = $this->language->get('error_product');
			}


			if (!$json) {
				$this->cart->add($product['product_id'], $quantity, $option, $subscription_plan_id);
			}
		}

		if (!$json) {
			$json['success'] = $this->language->get('text_success');

			$json['products'] = $this->getProducts();
			$json['totals'] = $this->getTotals();
			$json['shipping_required'] = $this->cart->hasShipping();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Add
	 *
	 * @return void
	 */
	public function add(): void {
		$this->load->language('api/cart');

		$json = [];

		$this->load->controller('api/cart.products');

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

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			// If variant get master product
			if ($product_info['master_id']) {
				$product_id = $product_info['master_id'];
			}

			// Merge variant code with options
			foreach ($product_info['variant'] as $option_id => $value) {
				$option[$option_id] = $value;
			}

			// Validate options
			$product_options = $this->model_catalog_product->getOptions($product_id);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option_' . $product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

			// Validate Subscription plan
			$subscriptions = $this->model_catalog_product->getSubscriptions($product_id);

			if ($subscriptions && !in_array($subscription_plan_id, array_column($subscriptions, 'subscription_plan_id'))) {
				$json['error']['subscription'] = $this->language->get('error_subscription');
			}
		} else {
			$json['error']['warning'] = $this->language->get('error_product');
		}

		if (!$json) {
			$this->cart->add($product_id, $quantity, $option, $subscription_plan_id);

			$json['success'] = $this->language->get('text_success');

			$json['products'] = $this->getProducts();
			$json['totals'] = $this->getTotals();
			$json['shipping_required'] = $this->cart->hasShipping();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Edit
	 *
	 * @return void
	 */
	public function edit(): void {
		$this->load->language('api/cart');

		$json = [];

		if (isset($this->request->post['key'])) {
			$key = (int)$this->request->post['key'];
		} else {
			$key = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = (int)$this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$this->cart->update($key, $quantity);

		$json['success'] = $this->language->get('text_success');

		$json['products'] = $this->getProducts();
		$json['totals'] = $this->getTotals();
		$json['shipping_required'] = $this->cart->hasShipping();

		unset($this->session->data['reward']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Remove
	 *
	 * @return void
	 */
	public function remove(): void {
		$this->load->language('api/cart');

		$json = [];

		if (isset($this->request->post['key'])) {
			$key = (int)$this->request->post['key'];
		} else {
			$key = 0;
		}

		// Remove
		$this->cart->remove($key);

		$json['success'] = $this->language->get('text_success');

		$json['products'] = $this->getProducts();
		$json['totals'] = $this->getTotals();
		$json['shipping_required'] = $this->cart->hasShipping();

		unset($this->session->data['reward']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
