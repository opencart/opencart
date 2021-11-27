<?php
namespace Opencart\Catalog\Controller\Common;
class Cart extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('common/cart');

		$data['products'] = [];

		$this->load->model('checkout/cart');

		$products = $this->model_checkout_cart->getProducts();

		foreach ($products as $product) {
			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = (float)$this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

				$price = $this->currency->format($unit_price, $this->session->data['currency']);
				$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
			} else {
				$price = false;
				$total = false;
			}


			$data['products'][] = [
				'cart_id'   => $product['cart_id'],
				'thumb'     => $product['image'],
				'name'      => $product['name'],
				'model'     => $product['model'],
				'option'    => $product['option'],
				'recurring' => ($product['recurring'] ? $product['recurring']['name'] : ''),
				'quantity'  => $product['quantity'],
				'price'     => $price,
				'total'     => $total,
				'href'      => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product['product_id'])
			];
		}

		// Gift Voucher
		$data['vouchers'] = [];

		$vouchers = $this->model_checkout_cart->getVouchers();

		foreach ($vouchers as $key => $voucher) {
			$data['vouchers'][] = [
				'key'         => $key,
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency'])
			];
		}


		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$total = $this->currency->format($this->cart->getTotal(), $this->session->data['currency']);
		} else {
			$total = $this->currency->format(0, $this->session->data['currency']);
		}

		$data['text_items'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $total);



		$data['totals'] = [];

		// Totals
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$totals = $this->model_checkout_cart->getTotals();

			foreach ($totals as $total) {
				$data['totals'][] = [
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				];
			}
		}

		$data['cart'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));
		$data['checkout'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));

		return $this->load->view('common/cart', $data);
	}

	public function info(): void {
		$this->response->setOutput($this->index());
	}
}
