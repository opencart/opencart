<?php
namespace Opencart\Admin\Controller\Extension\OcLanguageExample\Startup;
class German extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if ($this->config->get('language_german_status')) {
			$code = 'de-de';

			if (isset($this->session->data['language']) && $this->session->data['language'] == $code) {
				$this->load->model('localisation/language');

				$language_info = $this->model_localisation_language->getLanguageByCode($code);

				if ($language_info) {
					$this->language->addPath(DIR_EXTENSION . 'oc_language_example/admin/language/');
					$this->language->load($code);
				}
			}
		}
	}
}