<?php
class ControllerEventLanguage extends Controller {
	public function index() {
		// Language Detection
		$languages = array();
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'");
		
		foreach ($query->rows as $result) {
			$languages[$result['code']] = $result;
		}
		
		if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages)) {
			$code = $session->data['language'];
		} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages)) {
			$code = $request->cookie['language'];
		} else {
			$detect = '';
		
			if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) {
				$browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);
		
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
		
			$code = $detect ? $detect : $config->get('config_language');
		}
		
		if (!isset($session->data['language']) || $session->data['language'] != $code) {
			$session->data['language'] = $code;
		}
		
		if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {
			setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
		}
		
		$config->set('config_language_id', $languages[$code]['language_id']);
		$config->set('config_language', $languages[$code]['code']);

		// Language
		$language = new Language($languages[$code]['directory']);
		$language->load($languages[$code]['directory']);
		$registry->set('language', $language);	
	}
}
