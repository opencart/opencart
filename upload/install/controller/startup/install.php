<?php
namespace Opencart\Install\Controller\Startup;
class Install extends \Opencart\System\Engine\Controller {
	public function index(): void {
		// Document
		$this->registry->set('document', new \Opencart\System\Library\Document(HTTP_SERVER));

		// URL
		$this->registry->set('url', new \Opencart\System\Library\Url(HTTP_SERVER));

		// Language
		if (isset($this->request->get['language']) && $this->request->get['language'] != $this->config->get('language_code')) {
			$language_data = [];

			$languages = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);

			if ($languages) {
				foreach ($languages as $language) {
					$language_data[] = basename($language);
				}
			}

			if (in_array($this->request->get['language'], $language_data)) {
				$this->config->set('language_code', $this->request->get['language']);
			}
		}

		$language = new \Opencart\System\Library\Language($this->config->get('language_code'));
		$language->addPath(DIR_LANGUAGE);
		$language->load($this->config->get('language_code'));
		$this->registry->set('language', $language);
	}
}