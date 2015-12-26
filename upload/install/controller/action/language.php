<?php
class ControllerActionLanguage extends Controller {
	public function index() {
		if (!isset($this->session->data['language'])) {
			$languages = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);
			
			foreach ($languages as $language) {
				$languages[] = basename($language);
			}
	
			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);
		
				foreach ($browser_languages as $browser_language) {
					if (in_array($browser_language, $languages)) {
						$this->session->data['language'] = $browser_language;
						break;
					}
				}		
			
			}
		}
		
		if (!isset($this->session->data['language']) || !is_dir(DIR_LANGUAGE . str_replace('../', '/', $this->session->data['language']))) {
			$this->session->data['language'] = $this->config->get('language.default');
		}
		
		// Language
		$language = new Language($this->session->data['language']);
		$language->load($this->session->data['language']);
		$this->registry->set('language', $language);	
	}
}