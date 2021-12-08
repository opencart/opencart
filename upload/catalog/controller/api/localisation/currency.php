<?php
namespace Opencart\Catalog\Controller\Api\Localisation;
class Currency extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/localisation/currency');

		$json = [];

		if (isset($this->request->post['currency'])) {
			$currency = (string)$this->request->post['currency'];
		} else {
			$currency = '';
		}

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($currency);

		if (!$currency_info) {
			$json['error'] = $this->language->get('error_currency');
		}

		if (!$json) {
			$this->session->data['currency'] = $currency;

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
