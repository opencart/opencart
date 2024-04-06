<?php
namespace Opencart\Catalog\Controller\Api\Localisation;
/**
 * Class Language
 *
 * @package Opencart\Catalog\Controller\Api\Localisation
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('api/localisation/language');

		$json = [];

		if (isset($this->request->post['language'])) {
			$language = (string)$this->request->post['language'];
		} else {
			$language = '';
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_currency->getCurrencyByCode($language);

		if (!$language_info) {
			$json['error'] = $this->language->get('error_language');
		}

		if (!$json) {
			$this->session->data['language'] = $language;

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
