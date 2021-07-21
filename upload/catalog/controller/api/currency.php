<?php
namespace Opencart\Catalog\Controller\Api;
class Currency extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/currency');

		$json = [];

		$this->load->model('localisation/currency');

		$currency_info = $this->model_localisation_currency->getCurrencyByCode($this->request->post['currency']);

		if (!$currency_info) {
			$json['error'] = $this->language->get('error_currency');
		}

		if (!$json) {
			$this->session->data['currency'] = $this->request->post['currency'];

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
