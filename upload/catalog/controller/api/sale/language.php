<?php
namespace Opencart\Catalog\Controller\Api\Sale;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/sale/language');

		$json = [];

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($this->request->post['language']);

		if (!$language_info) {
			$json['error'] = $this->language->get('error_language');
		}

		if (!$json) {
			$this->session->data['language'] = $this->request->post['language'];

			$json['success'] = $this->language->get('text_success');

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
