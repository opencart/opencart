<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Store extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/store');

		$json = [];

		if (isset($this->request->post['store_id'])) {
			$store_id = (int)$this->request->post['store_id'];
		} else {
			$store_id = 0;
		}

		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($store_id);

		if (!$store_info) {
			$json['error'] = $this->language->get('error_store');
		}

		if (!$json) {
			$this->session->data['store_id'] = $store_id;

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);

			$totals = [];
			$taxes = $this->cart->getTaxes();
			$total = 0;

			$this->load->model('checkout/cart');

			($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

			$json['products'] = $this->model_checkout_cart->getProducts();
			$json['vouchers'] = $this->model_checkout_cart->getVouchers();
			$json['totals'] = $totals;

			$json['shipping_required'] = $this->cart->hasShipping();
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
