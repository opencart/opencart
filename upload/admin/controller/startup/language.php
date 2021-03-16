<?php
namespace Opencart\Admin\Controller\Startup;
class Language extends \Opencart\System\Engine\Controller {
	public function index(): void {
		// Added this code so that backup and restore doesn't show text_restore
		$code = $this->config->get('config_language_admin');

		if ($code) {
			$this->session->data['language_admin'] = $code;
		} elseif (isset($this->session->data['language_admin'])) {
			$this->config->set('config_language_admin', $this->session->data['language_admin']);
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguageByCode($this->config->get('config_language_admin'));

		if ($language_info) {
			$this->config->set('config_language_id', $language_info['language_id']);
		}

		// Language
		$language = new \Opencart\System\Library\Language($this->config->get('config_language_admin'));
		$language->addPath(DIR_LANGUAGE);
		$language->load($this->config->get('config_language_admin'));
		
		$this->registry->set('language', $language);
	}
}