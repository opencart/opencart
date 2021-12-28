<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Cart extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/cart');

		$json = [];

		// Stock
		if (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
			$json['error']['stock'] = $this->language->get('error_stock');
		}

		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;

		$this->load->model('checkout/cart');

		($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

		$points = 0;

		$frequencies = [
			'day'        => $this->language->get('text_day'),
			'week'       => $this->language->get('text_week'),
			'semi_month' => $this->language->get('text_semi_month'),
			'month'      => $this->language->get('text_month'),
			'year'       => $this->language->get('text_year')
		];

		$json['products'] = [];

		$products = $this->model_checkout_cart->getProducts();

		foreach ($products as $product) {
			$recurring = '';

			if ($product['recurring']) {
				if ($product['recurring']['trial']) {
					$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
				}

				if ($product['recurring']['duration']) {
					$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				} else {
					$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				}
			}

			$points += $product['reward'];

			$json['products'][] = [
				'cart_id'    => $product['cart_id'],
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $product['option'],
				'recurring'  => $recurring,
				'quantity'   => $product['quantity'],
				'stock'      => $product['stock'],
				'minimum'    => $product['minimum'],
				'reward'     => $product['reward'],
				'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']),
			];
		}

		$json['vouchers'] = [];

		$vouchers = $this->model_checkout_cart->getVouchers();

		foreach ($vouchers as $key => $voucher) {
			$json['vouchers'][] = [
				'key'         => $key,
				'description' => sprintf($this->language->get('text_for'), $this->currency->format($voucher['amount'], $this->session->data['currency']), $voucher['to_name']),
				'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency'])
			];
		}

		$json['totals'] = [];

		foreach ($totals as $total) {
			$json['totals'][] = [
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
			];
		}

		$json['shipping_required'] = $this->cart->hasShipping();
		$json['points'] = $points;

		if (isset($this->session->data['affiliate_id'])) {
			$subtotal = $this->cart->getSubTotal();

			// Affiliate
			$this->load->model('account/affiliate');

			$affiliate_info = $this->model_account_affiliate->getAffiliate($this->session->data['affiliate_id']);

			if ($affiliate_info) {
				$json['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function add(): void {
		$this->load->language('api/sale/cart');

		$json = [];

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = (int)$this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
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
			// If variant get master product
			if ($product_info['master_id']) {
				$product_id = $product_info['master_id'];
			}

			// Merge variant code with options
			foreach ($product_info['variant'] as $key => $value) {
				$option[$key] = $value;
			}

			// Validate options
			$product_options = $this->model_catalog_product->getOptions($product_id);

			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}

			// Validate recurring product profile
			$recurrings = $this->model_catalog_product->getProfiles($product_id);

			if ($recurrings) {
				$recurring_ids = [];

				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}

				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring');
				}
			}
		} else {
			$json['error']['product'] = $this->language->get('error_product');
		}

		if (!$json) {
			$this->cart->add($product_id, $quantity, $option, $recurring_id);

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function edit(): void {
		$this->load->language('api/sale/cart');

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

		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['reward']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove(): void {
		$this->load->language('api/sale/cart');

		$json = [];

		if (isset($this->request->post['key'])) {
			$key = (int)$this->request->post['key'];
		} else {
			$key = 0;
		}

		// Remove
		$this->cart->remove($key);

		$json['success'] = $this->language->get('text_success');

		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['reward']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
