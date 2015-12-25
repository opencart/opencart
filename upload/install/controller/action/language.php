<?php
class ControllerActionLanguage extends Controller {
	public function index() {
		if (!isset($this->session->data['language']) || !is_dir(DIR_LANGUAGE . str_replace('../', '/', $this->session->data['language']))) {
			$this->session->data['language'] = $this->config->get('language.default');
		}
		
		// Language
		$language = new Language($this->session->data['language']);
		$language->load($this->session->data['language']);
		$this->registry->set('language', $language);	
	}
}