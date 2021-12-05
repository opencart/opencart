<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/language');

		$json = [];

		if (isset($this->request->post['language'])) {
			$language = (string)$this->request->post['language'];
		} else {
			$language = '';
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($language);

		if (!$language_info) {
			$json['error'] = $this->language->get('error_language');
		}

		if (!$json) {
			$this->session->data['language'] = $language;

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
