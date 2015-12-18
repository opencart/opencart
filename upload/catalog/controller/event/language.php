<?php
class ControllerEventLanguage extends Controller {
	public function index() {
		// Language Detection
		$languages = array();
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");
		
		foreach ($query->rows as $result) {
			$languages[$result['code']] = $result;
		}
		
		if (isset($this->request->cookie['language']) && array_key_exists($this->request->cookie['language'], $languages)) {
			$code = $this->request->cookie['language'];
		} else {
			$detect = '';
		
			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE']) && $this->request->server['HTTP_ACCEPT_LANGUAGE']) {
				$browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);
		
				foreach ($browser_languages as $browser_language) {
					foreach ($languages as $key => $value) {
						if ($value['status']) {
							$locale = explode(',', $value['locale']);
		
							if (in_array($browser_language, $locale)) {
								$detect = $key;
								break 2;
							}
						}
					}
				}
			}
		
			$code = $detect ? $detect : $this->config->get('config_language');
		}
		
		if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $code) {
			setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
		}
		
		$this->config->set('config_language_id', $languages[$code]['language_id']);
		$this->config->set('config_language', $languages[$code]['code']);

		// Language
		$language = new Language($languages[$code]['directory']);
		$language->load($languages[$code]['directory']);
		
		$this->registry->set('language', $language);
	}
}
