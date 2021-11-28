<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Currency extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/currency');

		$json = [];

		if (isset($this->request->post['currency'])) {
			$code = (string)$this->request->post['currency'];
		} else {
			$code = '';
		}

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($code);

		if (!$currency_info) {
			$json['error'] = $this->language->get('error_currency');
		}

		if (!$json) {
			$this->session->data['currency'] = $code;

			$json['success'] = $this->language->get('text_success');

			$totals = [];
			$taxes = $this->cart->getTaxes();
			$total = 0;

			$this->load->model('checkout/cart');

			($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

			$json['products'] = $this->model_checkout_cart->getProducts();
			$json['vouchers'] = $this->model_checkout_cart->getVouchers();
			$json['totals'] = $totals;

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
