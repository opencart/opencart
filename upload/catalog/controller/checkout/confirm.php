<?php
namespace Opencart\Catalog\Controller\Checkout;
class Confirm extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/checkout');

		$this->load->model('tool/upload');

		// Display order info
		$frequencies = [
			'day'        => $this->language->get('text_day'),
			'week'       => $this->language->get('text_week'),
			'semi_month' => $this->language->get('text_semi_month'),
			'month'      => $this->language->get('text_month'),
			'year'       => $this->language->get('text_year')
		];

		$data['products'] = [];

		foreach ($this->cart->getProducts() as $product) {
			$option_data = [];

			foreach ($product['option'] as $option) {
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

				$option_data[] = [
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				];
			}

			$recurring = '';

			if ($product['recurring']) {
				if ($product['recurring']['trial']) {
					$recurring = sprintf($this->language->get('text_trial_description'), $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
				}

				if ($product['recurring']['duration']) {
					$recurring .= sprintf($this->language->get('text_payment_description'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				} else {
					$recurring .= sprintf($this->language->get('text_payment_cancel'), $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']), $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				}
			}

			$data['products'][] = [
				'cart_id'    => $product['cart_id'],
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'recurring'  => $recurring,
				'quantity'   => $product['quantity'],
				'subtract'   => $product['subtract'],
				'price'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'total'      => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'], $this->session->data['currency']),
				'href'       => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product['product_id'])
			];
		}

		// Gift Voucher
		$data['vouchers'] = [];

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$data['vouchers'][] = [
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency'])
				];
			}
		}

		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;
		$sort_order = [];

		$results = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get('total_' . $result['code'] . '_status')) {
				$this->load->model('extension/' . $result['extension'] . '/total/' . $result['code']);

				// __call can not pass-by-reference so we get PHP to call it as an anonymous function.
				($this->{'model_extension_' . $result['extension'] . '_total_' . $result['code']}->getTotal)($totals, $taxes, $total);
			}
		}

		$sort_order = [];

		foreach ($totals as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $totals);

		$data['totals'] = [];

		foreach ($totals as $total) {
			$data['totals'][] = [
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
			];
		}

		// Validate if payment method has been set.
		if (isset($this->session->data['payment_method'])) {
			$code = $this->session->data['payment_method']['code'];
		} else {
			$code = '';
		}

		$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $code);

		if ($extension_info) {
			$data['payment'] = $this->load->controller('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code']);
		} else {
			$data['payment'] = '';
		}

		return $this->load->view('checkout/confirm', $data);
	}

	public function confirm(): void {
		$this->response->setOutput($this->index());
	}

	public function payment(): string {
		$this->load->language('checkout/checkout');

		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;
		$sort_order = [];

		$results = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get('total_' . $result['code'] . '_status')) {
				$this->load->model('extension/' . $result['extension'] . '/total/' . $result['code']);

				// __call can not pass-by-reference so we get PHP to call it as an anonymous function.
				($this->{'model_extension_' . $result['extension'] . '_total_' . $result['code']}->getTotal)($totals, $taxes, $total);
			}
		}

		$sort_order = [];

		foreach ($totals as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $totals);

		$data['totals'] = [];

		foreach ($totals as $total) {
			$data['totals'][] = [
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
			];
		}

		// Validate if payment method has been set.
		if (isset($this->session->data['payment_method'])) {
			$code = $this->session->data['payment_method']['code'];
		} else {
			$code = '';
		}

		$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $code);

		if ($extension_info) {
			//$data['payment'] = $this->load->controller('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code']);
		} else {
			$data['payment'] = 'dfgdfg';
		}

		return $this->load->view('checkout/confirm', $data);
	}


	public function fhdfh(): void {
		if ($this->config->get('config_checkout_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_checkout_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$json['error'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		$this->load->model('setting/extension');

		if (isset($this->session->data['payment_method'])) {
			// Validate if payment method has been set.
			$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $this->session->data['payment_method']['code']);

			if (!$extension_info) {
				$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
			}
		} else {
			$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}

		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			if (!isset($this->session->data['shipping_address'])) {
				$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate if payment address has been set.
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		$this->load->model('setting/extension');

		if (isset($this->session->data['payment_method'])) {
			// Validate if payment method has been set.
			$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $this->session->data['payment_method']['code']);

			if (!$extension_info) {
				$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
			}
		} else {
			$json['redirect'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'), true);
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$json['redirect'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'), true);
		}
	}
}
