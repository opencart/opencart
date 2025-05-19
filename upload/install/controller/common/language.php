<?php
namespace Opencart\Install\Controller\Common;
/**
 * Class Language
 *
 * @package Opencart\Install\Controller\Common
 */
class Language extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('common/language');

		$data['text_language'] = $this->language->get('text_language');

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		} else {
			$route = $this->config->get('action_default');
		}

		$data['languages'] = [];

		$languages = glob(DIR_LANGUAGE . '*', GLOB_ONLYDIR);

		foreach ($languages as $code) {
			$code = basename($code);

			$language = new \Opencart\System\Library\Language($code);
			$language->addPath(DIR_LANGUAGE);
			$language->load('default');

			$data['languages'][] = [
				'text' => $language->get('text_name'),
				'code' => $code,
				'href' => $this->url->link($route, 'language=' . $code)
			];
		}

		if (isset($this->request->get['language'])) {
			$data['code'] = $this->request->get['language'];
		} else {
			$data['code'] = $this->config->get('language_code');
		}

		return $this->load->view('common/language', $data);
	}
}
