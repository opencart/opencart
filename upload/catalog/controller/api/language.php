<?php
namespace Opencart\Catalog\Controller\Api;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('api/language');

		$json = [];

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('localisation/language');

			$language_info = $this->model_localisation_language->getLanguageByCode($this->request->post['language']);

			if ($language_info) {
				$this->session->data['language'] = $this->request->post['language'];

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);

				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_language');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
