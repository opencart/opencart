<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/language');

		$json = [];

		if (isset($this->request->post['language'])) {
			$code = (string)$this->request->post['language'];
		} else {
			$code = '';
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($code);

		if (!$language_info) {
			$json['error'] = $this->language->get('error_language');
		}

		if (!$json) {
			$this->session->data['language'] = $code;

			$json['success'] = $this->language->get('text_success');

			$this->load->model('checkout/cart');

			$json['products'] = $this->model_checkout_cart->getProducts();
			$json['vouchers'] = $this->model_checkout_cart->getVouchers();
			$json['totals'] = $this->model_checkout_cart->getTotals();

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
