<?php
class ControllerActionLanguage extends Controller {
	public function index() {
		// Language Detection
		$code = '';
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->cookie['language'])) {
			$code = $this->request->cookie['language'];
		}
		
		if (!array_key_exists($code, $languages) || !is_dir(DIR_LANGUAGE . strtolower($code)) && !empty($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);
			
			foreach ($browser_languages as $browser_language) {
				if (array_key_exists(strtolower($browser_language), $languages)) {
					$code = strtolower($browser_language);
					
					break;
				}
			}
		}
		
		if (!array_key_exists($code, $languages) || !is_dir(DIR_LANGUAGE . strtolower($code))) {
			$code = $this->config->get('config_language');
		}
		
		if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $code) {
			setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
		}
				
		if (array_key_exists($code, $languages) && is_dir(DIR_LANGUAGE . strtolower($code))) {
			// Overwrite the default language object
			$language = new Language(strtolower($code));
			$language->load(strtolower($code));
			
			$this->registry->set('language', $language);
			
			// Set the config language_id
			$this->config->set('config_language_id', $languages[$code]['language_id']);		
		} else {
			trigger_error('Error: Could not load language ' . $code . '!');	
			exit();
		}
	}
}
