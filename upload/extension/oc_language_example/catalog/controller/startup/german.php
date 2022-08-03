<?php
namespace Opencart\Catalog\Controller\Extension\OcLanguageExample\Startup;
class German extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if ($this->config->get('language_german_status')) {
			$code = 'de-de';

			if (isset($this->request->get['language']) && $this->request->get['language'] == $code) {
				$this->load->model('localisation/language');

				$language_info = $this->model_localisation_language->getLanguageByCode($code);

				if ($language_info) {
					$this->language->addPath(DIR_EXTENSION . 'oc_language_example/catalog/language/');
					$this->language->load($code);
				}
			}
		}
	}
}